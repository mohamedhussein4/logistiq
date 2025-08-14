<?php

namespace App\Http\Controllers\ServiceCompany;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\BankAccount;
use App\Models\ServiceCompany;
use App\Models\InstallmentPlan;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ServiceCompanyController extends Controller
{
    public function publicPage()
    {
        // إحصائيات وهمية للصفحة العامة
        $stats = [
            'total_clients' => 150,
            'paid_amount' => 250000,
            'pending_amount' => 75000,
            'success_rate' => 95
        ];

        return view('client-portal', compact('stats'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        // البحث عن بيانات الشركة الطالبة للخدمة في جدول service_companies
        $serviceCompany = ServiceCompany::where('user_id', $user->id)->first();

        // إذا لم تكن بيانات الشركة موجودة، إنشاء سجل جديد
        if (!$serviceCompany) {
            $serviceCompany = ServiceCompany::create([
                'user_id' => $user->id,
                'total_outstanding' => 0,
                'total_paid' => 0,
                'credit_limit' => 50000,
                'payment_status' => ServiceCompany::PAYMENT_REGULAR,
            ]);
        }

        // الإحصائيات الرئيسية من قاعدة البيانات
        $stats = [
            'total_outstanding' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', '!=', 'paid')->sum('remaining_amount'),
            'paid_this_month' => Payment::whereHas('invoice', function($query) use ($serviceCompany) {
                    $query->where('service_company_id', $serviceCompany->id);
                })
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total_invoices' => Invoice::where('service_company_id', $serviceCompany->id)->count(),
            'paid_invoices' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', 'paid')->count(),
            'pending_invoices' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', 'unpaid')->count(),
            'overdue_invoices' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', 'overdue')->count(),
        ];

        // الفواتير الحديثة (آخر 20 للجدول)
        $recentInvoices = Invoice::where('service_company_id', $serviceCompany->id)
            ->with(['logisticsCompany'])
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($invoice) {
                $statusLabels = [
                    'unpaid' => 'غير مدفوع',
                    'partial' => 'مدفوع جزئياً',
                    'paid' => 'مدفوع بالكامل',
                    'overdue' => 'متأخر'
                ];
                $invoice->payment_status_label = $statusLabels[$invoice->payment_status] ?? $invoice->payment_status;
                return $invoice;
            });

        // المدفوعات الحديثة (آخر 20 للجدول)
        $recentPayments = Payment::whereHas('invoice', function($query) use ($serviceCompany) {
                $query->where('service_company_id', $serviceCompany->id);
            })
            ->with(['invoice'])
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($payment) {
                $statusLabels = [
                    'pending' => 'معلق',
                    'confirmed' => 'مؤكد',
                    'failed' => 'فشل',
                    'cancelled' => 'ملغي'
                ];
                $methodLabels = [
                    'bank_transfer' => 'تحويل بنكي',
                    'cash' => 'نقداً',
                    'check' => 'شيك',
                    'online' => 'دفع إلكتروني'
                ];
                $payment->status_label = $statusLabels[$payment->status] ?? $payment->status;
                $payment->payment_method_label = $methodLabels[$payment->payment_method] ?? $payment->payment_method;
                return $payment;
            });

        // الحصول على البنوك والمحافظ الإلكترونية النشطة لنظام الدفع
        $bankAccounts = BankAccount::active()->ordered()->get();

        return view('service-company.dashboard', compact('serviceCompany', 'stats', 'recentInvoices', 'recentPayments', 'bankAccounts'));
    }

    /**
     * دفع سريع من الصفحة الرئيسية
     */
    public function quickPayment(Request $request)
    {
        $user = Auth::user();
        $serviceCompany = ServiceCompany::where('user_id', $user->id)->first();

        if (!$serviceCompany) {
            return redirect()->back()->with('error', 'لم يتم العثور على بيانات الشركة');
        }

        $request->validate([
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bank_transfer,electronic_wallet',
            'payment_account_id' => 'required|integer',
            'payment_notes' => 'nullable|string|max:1000',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120', // 5MB max
        ]);

        // البحث عن الفاتورة
        $invoice = Invoice::where('service_company_id', $serviceCompany->id)
            ->where('invoice_number', $request->invoice_number)
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'لم يتم العثور على الفاتورة المحددة');
        }

        if ($invoice->payment_status === 'paid') {
            return redirect()->back()->with('error', 'هذه الفاتورة مدفوعة بالكامل بالفعل');
        }

        if ($request->amount > $invoice->remaining_amount) {
            return redirect()->back()->with('error', 'المبلغ المدخل أكبر من المبلغ المستحق');
        }

        try {
            DB::beginTransaction();

            // إنشاء طلب دفع جديد
            $paymentRequest = PaymentRequest::create([
                'user_id' => $user->id,
                'request_number' => 'SRV-PAY-' . date('Ymd') . '-' . str_pad(PaymentRequest::count() + 1, 6, '0', STR_PAD_LEFT),
                'payment_type' => 'invoice',
                'related_id' => $invoice->id,
                'related_type' => Invoice::class,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_account_id' => $request->payment_account_id,
                'payment_account_type' => $request->payment_method === 'bank_transfer' ? BankAccount::class : null,
                'payment_notes' => $request->payment_notes ?: 'دفع سريع من لوحة تحكم الشركة الطالبة',
                'status' => 'pending',
            ]);

            // رفع إثبات التحويل إذا كان موجوداً
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('payment-proofs', $fileName, 'public');

                // إنشاء سجل إثبات التحويل
                $paymentRequest->paymentProofs()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'description' => 'إثبات تحويل للفاتورة رقم: ' . $invoice->invoice_number,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            $message = 'تم إرسال طلب الدفع بنجاح. رقم الطلب: ' . $paymentRequest->request_number;
            if ($request->hasFile('payment_proof')) {
                $message .= ' وتم رفع إثبات التحويل للمراجعة.';
            } else {
                $message .= '. يرجى رفع إثبات التحويل لإتمام العملية بسرعة.';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال طلب الدفع: ' . $e->getMessage());
        }
    }

    public function profile()
    {
        $user = Auth::user();

        // التأكد من أن المستخدم له صلاحية الوصول لهذه الصفحة
        if ($user->user_type !== 'service_company') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $serviceCompany = $user->serviceCompany;

        // إذا لم تكن بيانات الشركة موجودة في الجدول المنفصل، استخدم بيانات المستخدم
        if (!$serviceCompany) {
            $serviceCompany = (object) [
                'id' => $user->id,
                'user_id' => $user->id,
                'company_name' => $user->company_name,
                'commercial_register' => $user->company_registration,
                'total_outstanding' => 0,
                'total_paid' => 0,
                'credit_limit' => 50000,
                'address' => null,
                'account_status' => $user->status,
                'user' => $user // إضافة reference للـ user
            ];
        }

        // الإحصائيات الرئيسية من قاعدة البيانات
        $stats = [
            'total_outstanding' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', '!=', 'paid')->sum('remaining_amount'),
            'paid_this_month' => Payment::whereHas('invoice', function($query) use ($serviceCompany) {
                    $query->where('service_company_id', $serviceCompany->id);
                })
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total_invoices' => Invoice::where('service_company_id', $serviceCompany->id)->count(),
            'paid_invoices' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', 'paid')->count(),
            'pending_invoices' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', 'unpaid')->count(),
            'overdue_invoices' => Invoice::where('service_company_id', $serviceCompany->id)
                ->where('payment_status', 'overdue')->count(),
        ];

        // إضافة معلومات الدفعة القادمة
        $nextPayment = Invoice::where('service_company_id', $serviceCompany->id)
            ->where('payment_status', '!=', 'paid')
            ->orderBy('due_date')
            ->first();

        if ($nextPayment) {
            $stats['next_payment_due'] = $nextPayment->due_date->format('d/m/Y');
            $stats['next_payment_amount'] = $nextPayment->remaining_amount;
        }

        // الفواتير الحقيقية
        $invoices = Invoice::where('service_company_id', $serviceCompany->id)
            ->with(['logisticsCompany'])
            ->latest()
            ->get()
            ->map(function ($invoice) {
                $statusLabels = [
                    'unpaid' => 'غير مدفوع',
                    'partial' => 'مدفوع جزئياً',
                    'paid' => 'مدفوع بالكامل',
                    'overdue' => 'متأخر'
                ];
                $invoice->payment_status_label = $statusLabels[$invoice->payment_status] ?? $invoice->payment_status;
                return $invoice;
            });

        // المدفوعات الحقيقية
        $payments = Payment::whereHas('invoice', function($query) use ($serviceCompany) {
                $query->where('service_company_id', $serviceCompany->id);
            })
            ->with(['invoice'])
            ->latest()
            ->get()
            ->map(function ($payment) {
                $statusLabels = [
                    'pending' => 'معلق',
                    'completed' => 'مكتمل',
                    'failed' => 'فشل',
                    'cancelled' => 'ملغي'
                ];
                $methodLabels = [
                    'bank_transfer' => 'تحويل بنكي',
                    'cash' => 'نقداً',
                    'check' => 'شيك',
                    'online' => 'دفع إلكتروني'
                ];
                $payment->status_label = $statusLabels[$payment->status] ?? $payment->status;
                $payment->payment_method_label = $methodLabels[$payment->payment_method] ?? $payment->payment_method;
                return $payment;
            });

        // خطط التقسيط الحقيقية
        $installmentPlans = InstallmentPlan::whereHas('invoice', function($query) use ($serviceCompany) {
                $query->where('service_company_id', $serviceCompany->id);
            })
            ->with(['invoice'])
            ->latest()
            ->get()
            ->map(function ($plan) {
                $statusLabels = [
                    'pending' => 'معلق',
                    'active' => 'نشط',
                    'completed' => 'مكتمل',
                    'cancelled' => 'ملغي'
                ];
                $plan->status_label = $statusLabels[$plan->status] ?? $plan->status;
                return $plan;
            });

        // الشركات اللوجيستية التي تتعامل معها هذه الشركة
        $logisticsCompanies = User::where('user_type', 'logistics')
            ->where('status', 'active')
            ->whereHas('logisticsInvoices', function ($query) use ($serviceCompany) {
                $query->where('service_company_id', $serviceCompany->id);
            })
            ->withCount(['logisticsInvoices' => function ($query) use ($serviceCompany) {
                $query->where('service_company_id', $serviceCompany->id);
            }])
            ->get()
            ->map(function ($logistics) use ($serviceCompany) {
                $logistics->total_invoices = Invoice::where('service_company_id', $serviceCompany->id)
                    ->where('logistics_company_id', $logistics->id)
                    ->count();
                $logistics->total_amount = Invoice::where('service_company_id', $serviceCompany->id)
                    ->where('logistics_company_id', $logistics->id)
                    ->sum('original_amount');
                return $logistics;
            });

        // طلبات الدفع للمستخدم الحالي
        $paymentRequests = PaymentRequest::where('user_id', $user->id)
            ->with(['related', 'bankAccount', 'paymentProofs'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // طلبات المنتجات للمستخدم الحالي (إذا كان لديه)
        $productOrders = ProductOrder::where('user_id', $user->id)
            ->with(['product', 'paymentRequests'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('service-company.profile', compact(
            'serviceCompany',
            'stats',
            'invoices',
            'payments',
            'installmentPlans',
            'logisticsCompanies',
            'paymentRequests',
            'productOrders'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->user_type !== 'service_company') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'company_registration' => 'nullable|string|max:255',
        ], [
            'name.required' => 'اسم المستخدم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
        ]);

        // استخدام User model بدلاً من Auth::user() مباشرة لضمان وجود update method
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_registration' => $request->company_registration,
        ]);

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if ($user->user_type !== 'service_company') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون 8 أحرف على الأقل',
            'new_password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // استخدام User model بدلاً من Auth::user() مباشرة لضمان وجود update method
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    /**
     * جلب تفاصيل الفاتورة لعرضها في مربع حوار
     */
    public function getInvoiceDetails($id)
    {
        $user = Auth::user();

        if ($user->user_type !== 'service_company') {
            return response()->json(['error' => 'غير مصرح لك بالوصول لهذا المورد'], 403);
        }

        // البحث عن الفاتورة
        $invoice = Invoice::with(['logisticsCompany'])
            ->where('id', $id)
            ->whereHas('serviceCompany', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (!$invoice) {
            return response()->json(['error' => 'الفاتورة غير موجودة'], 404);
        }

        // إعداد البيانات للإرجاع
        $invoiceData = [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'original_amount' => $invoice->original_amount,
            'paid_amount' => $invoice->paid_amount,
            'remaining_amount' => $invoice->remaining_amount,
            'payment_status' => $invoice->payment_status,
            'payment_status_label' => $invoice->payment_status_label,
            'created_at' => $invoice->created_at->format('Y-m-d'),
            'due_date' => $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '-',
            'description' => $invoice->description,
            'logistics_company' => null
        ];

        // إضافة بيانات الشركة إذا كانت موجودة
        if ($invoice->logisticsCompany) {
            $invoiceData['logistics_company'] = [
                'name' => $invoice->logisticsCompany->name,
                'contact_person' => $invoice->logisticsCompany->contact_person,
                'phone' => $invoice->logisticsCompany->phone,
                'email' => $invoice->logisticsCompany->email,
                'address' => $invoice->logisticsCompany->address,
            ];
        }

        return response()->json($invoiceData);
    }
}
