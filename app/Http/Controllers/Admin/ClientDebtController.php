<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientDebt;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientDebtController extends Controller
{
    /**
     * إنشاء حساب جديد للعميل المدين
     */
    public function createAccount(ClientDebt $clientDebt)
    {
        if ($clientDebt->status !== ClientDebt::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'هذا العميل لديه حساب بالفعل أو تم معالجة طلبه.');
        }

        // التحقق من عدم وجود حساب بنفس البريد الإلكتروني
        $existingUser = User::where('email', $clientDebt->email)->first();
        if ($existingUser) {
            return redirect()->back()
                ->with('error', 'يوجد حساب بالفعل بهذا البريد الإلكتروني: ' . $clientDebt->email);
        }

        DB::beginTransaction();
        try {
            // إنشاء كلمة مرور عشوائية
            $password = Str::random(8);

            // إنشاء المستخدم
            $user = User::create([
                'name' => $clientDebt->contact_person,
                'email' => $clientDebt->email,
                'phone' => $clientDebt->phone,
                'company_name' => $clientDebt->company_name,
                'user_type' => 'service_company',
                'password' => Hash::make($password),
                'status' => 'active',
                'email_verified_at' => now(), // تفعيل الحساب مباشرة
            ]);

            // إنشاء سجل في جدول service_companies
            $serviceCompany = \App\Models\ServiceCompany::create([
                'user_id' => $user->id,
                'total_outstanding' => $clientDebt->amount,
                'total_paid' => 0,
                'credit_limit' => 0, // العميل لا يحتاج ائتمان
                'payment_status' => \App\Models\ServiceCompany::PAYMENT_REGULAR,
            ]);

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'logistics_company_id' => $clientDebt->fundingRequest->logistics_company_id,
                'service_company_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT),
                'original_amount' => $clientDebt->amount,
                'paid_amount' => 0,
                'remaining_amount' => $clientDebt->amount,
                'due_date' => $clientDebt->due_date,
                'payment_status' => 'unpaid',
                'description' => 'فاتورة تحصيل مستحقات - طلب تمويل #' . $clientDebt->fundingRequest->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // تحديث حالة الدين
            $clientDebt->update([
                'status' => ClientDebt::STATUS_ACCOUNT_CREATED,
                'created_user_id' => $user->id,
                'created_invoice_id' => $invoice->id,
            ]);

            // TODO: إرسال إيميل ببيانات الدخول
            // سيتم تطوير هذا لاحقاً
            
            DB::commit();

            return redirect()->back()
                ->with('success', 'تم إنشاء حساب العميل بنجاح! تم إنشاء الفاتورة برقم: ' . $invoice->invoice_number . 
                    '. كلمة المرور المؤقتة: ' . $password);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage());
        }
    }

    /**
     * عرض جميع العملاء المدينين
     */
    public function index(Request $request)
    {
        $query = ClientDebt::with(['fundingRequest.logisticsCompany', 'createdUser', 'createdInvoice']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب اسم الشركة
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_person', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $clientDebts = $query->latest()->paginate(20);

        // إحصائيات
        $stats = [
            'total_clients' => ClientDebt::count(),
            'pending_accounts' => ClientDebt::where('status', ClientDebt::STATUS_PENDING)->count(),
            'created_accounts' => ClientDebt::where('status', ClientDebt::STATUS_ACCOUNT_CREATED)->count(),
            'total_amount' => ClientDebt::sum('amount'),
        ];

        return view('admin.client-debts.index', compact('clientDebts', 'stats'));
    }

    /**
     * عرض تفاصيل عميل مدين محدد
     */
    public function show(ClientDebt $clientDebt)
    {
        $clientDebt->load(['fundingRequest.logisticsCompany', 'createdUser', 'createdInvoice']);

        return view('admin.client-debts.show', compact('clientDebt'));
    }
}