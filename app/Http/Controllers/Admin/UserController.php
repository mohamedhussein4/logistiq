<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogisticsCompany;
use App\Models\ServiceCompany;
use Illuminate\Http\Request;

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
            ]);

            $userData = $request->only([
                'name', 'email', 'phone', 'user_type', 'status', 'admin_notes'
            ]);

            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            // تحديث بيانات المستخدم
            $user->update($userData);

            // تحديث أو إنشاء بيانات الشركة حسب النوع
            if ($request->user_type === 'logistics') {
                $user->logisticsCompany()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_name' => $request->company_name,
                        'commercial_register' => $request->commercial_register,
                        'address' => $request->address,
                        'city' => $request->city,
                        'postal_code' => $request->postal_code,
                        'available_balance' => $request->available_balance ?? 0,
                        'credit_limit' => $request->credit_limit ?? 0,
                    ]
                );
            } elseif ($request->user_type === 'service_company') {
                $user->serviceCompany()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_name' => $request->company_name,
                        'commercial_register' => $request->commercial_register,
                        'address' => $request->address,
                        'city' => $request->city,
                        'postal_code' => $request->postal_code,
                        'total_outstanding' => $request->total_outstanding ?? 0,
                        'total_paid' => $request->total_paid ?? 0,
                    ]
                );
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'تم تحديث بيانات المستخدم "' . $user->name . '" بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage())
                ->withInput();
        }
    }
}
