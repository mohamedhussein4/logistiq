<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogisticsCompany;
use App\Models\ServiceCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * عرض جميع المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with(['logisticsCompany', 'serviceCompany', 'profile']);

        // فلترة حسب نوع المستخدم
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض تفاصيل مستخدم
     */
    public function show(User $user)
    {
        $user->load(['profile', 'logisticsCompany', 'serviceCompany', 'productOrders.product']);

        $stats = [];

        if ($user->isLogisticsCompany() && $user->logisticsCompany) {
            $stats = [
                'total_funding_requests' => $user->logisticsCompany->fundingRequests()->count(),
                'approved_requests' => $user->logisticsCompany->fundingRequests()->where('status', 'approved')->count(),
                'total_funded' => $user->logisticsCompany->total_funded,
                'available_balance' => $user->logisticsCompany->available_balance,
            ];
        }

        if ($user->isServiceCompany() && $user->serviceCompany) {
            $stats = [
                'total_invoices' => $user->serviceCompany->invoices()->count(),
                'overdue_invoices' => $user->serviceCompany->invoices()->overdue()->count(),
                'total_outstanding' => $user->serviceCompany->total_outstanding,
                'total_paid' => $user->serviceCompany->total_paid,
            ];
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * تحديث حالة المستخدم
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,pending,suspended'
        ]);

        $oldStatus = $user->status;
        $user->update(['status' => $request->status]);

        // إنشاء سجل في الشركة إذا تم تفعيل المستخدم لأول مرة
        if ($oldStatus === 'pending' && $request->status === 'active') {
            if ($user->user_type === User::TYPE_LOGISTICS && !$user->logisticsCompany) {
                LogisticsCompany::create([
                    'user_id' => $user->id,
                    'available_balance' => 0,
                    'total_funded' => 0,
                    'total_requests' => 0,
                ]);
            }

            if ($user->user_type === User::TYPE_SERVICE_COMPANY && !$user->serviceCompany) {
                ServiceCompany::create([
                    'user_id' => $user->id,
                    'total_outstanding' => 0,
                    'total_paid' => 0,
                    'payment_status' => 'regular',
                ]);
            }
        }

        $statusName = User::getStatuses()[$request->status];
        return redirect()->back()->with('success', "تم تحديث حالة المستخدم إلى: {$statusName}");
    }

    /**
     * التحقق من المستخدم
     */
    public function verify(User $user)
    {
        if ($user->profile) {
            $user->profile->update(['verification_status' => 'approved']);
        }

        return redirect()->back()->with('success', 'تم التحقق من المستخدم بنجاح');
    }

    /**
     * عرض ملف المستخدم
     */
    public function profile(User $user)
    {
        $user->load('profile');
        return view('admin.users.profile', compact('user'));
    }

    /**
     * حذف المستخدم
     */
    public function destroy(User $user)
    {
        // التحقق من أن المستخدم ليس أدمن
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'لا يمكن حذف المستخدم الأدمن');
        }

        // التحقق من وجود بيانات مرتبطة
        if ($user->productOrders()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف المستخدم لوجود طلبات شراء مرتبطة به');
        }

        if ($user->isLogisticsCompany() && $user->logisticsCompany && $user->logisticsCompany->fundingRequests()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف الشركة لوجود طلبات تمويل مرتبطة بها');
        }

        if ($user->isServiceCompany() && $user->serviceCompany && $user->serviceCompany->invoices()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف الشركة لوجود فواتير مرتبطة بها');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * عرض نموذج إضافة مستخدم جديد
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'user_type' => 'required|in:admin,logistics,service_company,regular',
                'password' => 'required|string|min:8|confirmed',
                'status' => 'required|in:active,pending,suspended',
                'admin_notes' => 'nullable|string',

                // Company fields (conditional)
                'company_name' => 'nullable:user_type,logistics,service_company|string|max:255',
                'commercial_register' => 'nullable|string|max:100',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',

                // Logistics company fields
                'available_balance' => 'nullable|numeric|min:0',
                'credit_limit' => 'nullable|numeric|min:0',

                // Service company fields
                'total_outstanding' => 'nullable|numeric|min:0',
                'total_paid' => 'nullable|numeric|min:0',

                // Options
                'email_verified' => 'boolean',
                'force_password_change' => 'boolean',
                'send_welcome_email' => 'boolean',
            ]);

            $userData = $request->only([
                'name', 'email', 'phone', 'user_type', 'status', 'admin_notes'
            ]);

            $userData['password'] = bcrypt($request->password);

            if ($request->email_verified) {
                $userData['email_verified_at'] = now();
            }

            // إنشاء المستخدم
            $user = User::create($userData);

            // إنشاء بيانات الشركة حسب النوع
            if ($request->user_type === 'logistics') {
                LogisticsCompany::create([
                    'user_id' => $user->id,
                    'company_name' => $request->company_name,
                    'commercial_register' => $request->commercial_register,
                    'address' => $request->address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'available_balance' => $request->available_balance ?? 0,
                    'credit_limit' => $request->credit_limit ?? 0,
                ]);
            } elseif ($request->user_type === 'service_company') {
                ServiceCompany::create([
                    'user_id' => $user->id,
                    'company_name' => $request->company_name,
                    'commercial_register' => $request->commercial_register,
                    'address' => $request->address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'total_outstanding' => $request->total_outstanding ?? 0,
                    'total_paid' => $request->total_paid ?? 0,
                ]);
            }

            // إرسال رسالة ترحيبية (إذا كان مطلوب)
            if ($request->send_welcome_email) {
                // يمكن إضافة كود إرسال البريد هنا
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'تم إنشاء المستخدم "' . $user->name . '" بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء المستخدم: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * عرض نموذج تعديل المستخدم
     */
    public function edit(User $user)
    {
        $user->load(['logisticsCompany', 'serviceCompany']);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'user_type' => 'required|in:admin,logistics,service_company,regular',
                'password' => 'nullable|string|min:8|confirmed',
                'status' => 'required|in:active,pending,suspended,inactive',
                'admin_notes' => 'nullable|string',

                // Company fields (conditional)
                'company_name' => 'nullable|string|max:255',
                'company_registration' => 'nullable|string|max:100',
                'address' => 'nullable|string',
                'contact_person' => 'nullable|string|max:255',

                // Balance adjustments
                'balance_adjustment' => 'nullable|numeric',
                'adjustment_type' => 'nullable|in:add,subtract',

                // Logistics company fields
                'credit_limit' => 'nullable|numeric|min:0',
                'company_type' => 'nullable|string|max:100',

                // Service company fields
                'service_credit_limit' => 'nullable|numeric|min:0',
                'payment_status' => 'nullable|in:regular,overdue,under_review',
                'tax_number' => 'nullable|string|max:100',
                'bank_account' => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            // تحديث البيانات الأساسية للمستخدم
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_type' => $request->user_type,
                'status' => $request->status,
                'company_name' => $request->company_name,
                'company_registration' => $request->company_registration,
            ];

            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            $user->update($userData);

            // معالجة تعديل الرصيد للشركات اللوجيستية
            if ($request->user_type === 'logistics' && $request->filled('balance_adjustment')) {
                $logisticsCompany = $user->logisticsCompany;
                if ($logisticsCompany) {
                    $adjustment = floatval($request->balance_adjustment);
                    if ($request->adjustment_type === 'subtract') {
                        $adjustment = -$adjustment;
                    }

                    // تحديث الرصيد
                    $newBalance = $logisticsCompany->available_balance + $adjustment;
                    $logisticsCompany->update(['available_balance' => max(0, $newBalance)]);

                    // تسجيل المعاملة في جدول BalanceTransaction إذا كان موجوداً
                    try {
                        if (class_exists('App\Models\BalanceTransaction')) {
                            \App\Models\BalanceTransaction::create([
                                'user_id' => $user->id,
                                'type' => $adjustment > 0 ? 'credit' : 'debit',
                                'amount' => abs($adjustment),
                                'description' => 'تعديل رصيد من لوحة التحكم',
                                'reference_type' => 'admin_adjustment',
                                'reference_id' => $user->id,
                                'balance_before' => $logisticsCompany->available_balance - $adjustment,
                                'balance_after' => $logisticsCompany->available_balance,
                                'created_by' => Auth::id(),
                            ]);
                        }
                    } catch (\Exception $e) {
                        // تجاهل أخطاء جدول المعاملات إذا لم يكن موجوداً
                    }
                }
            }

            // تحديث أو إنشاء بيانات الشركة اللوجيستية
            if ($request->user_type === 'logistics') {
                $logisticsData = [
                    'company_name' => $request->company_name,
                    'contact_person' => $request->contact_person,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'commercial_register' => $request->company_registration,
                    'credit_limit' => $request->credit_limit ?? 0,
                    'company_type' => $request->company_type,
                    'status' => $request->status,
                ];

                $user->logisticsCompany()->updateOrCreate(
                    ['user_id' => $user->id],
                    $logisticsData
                );
            }

            // تحديث أو إنشاء بيانات الشركة الطالبة للخدمة
            elseif ($request->user_type === 'service_company') {
                $serviceData = [
                    'company_name' => $request->company_name,
                    'contact_person' => $request->contact_person,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'commercial_register' => $request->company_registration,
                    'credit_limit' => $request->service_credit_limit ?? 0,
                    'payment_status' => $request->payment_status ?? 'regular',
                    'tax_number' => $request->tax_number,
                    'bank_account' => $request->bank_account,
                    'status' => $request->status,
                ];

                $user->serviceCompany()->updateOrCreate(
                    ['user_id' => $user->id],
                    $serviceData
                );
            }

            // حذف بيانات الشركة إذا تم تغيير نوع المستخدم
            if ($user->wasChanged('user_type')) {
                $oldUserType = $user->getOriginal('user_type');

                if ($oldUserType === 'logistics' && $request->user_type !== 'logistics') {
                    $user->logisticsCompany()->delete();
                }

                if ($oldUserType === 'service_company' && $request->user_type !== 'service_company') {
                    $user->serviceCompany()->delete();
                }
            }

            // تحديث ملاحظات الإدارة
            if ($request->filled('admin_notes')) {
                $user->update(['admin_notes' => $request->admin_notes]);
            }

            DB::commit();

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'تم تحديث بيانات المستخدم "' . $user->name . '" بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * حذف متعدد للمستخدمين
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // جلب المستخدمين المحددين
            $users = User::whereIn('id', $request->user_ids)->get();

            // فلترة المستخدمين الإداريين
            $adminUsers = $users->where('user_type', 'admin');
            $nonAdminUsers = $users->where('user_type', '!=', 'admin');

            if ($adminUsers->count() > 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'لا يمكن حذف المستخدمين الإداريين');
            }

            $deletedCount = $nonAdminUsers->count();

            if ($deletedCount === 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'لم يتم تحديد مستخدمين صالحين للحذف');
            }

            // حذف المستخدمين
            User::whereIn('id', $nonAdminUsers->pluck('id'))->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', "تم حذف {$deletedCount} مستخدم بنجاح");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المستخدمين: ' . $e->getMessage());
        }
    }
}
