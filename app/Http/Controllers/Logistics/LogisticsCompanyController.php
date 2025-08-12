<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FundingRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\ServiceCompany;
use App\Models\LogisticsCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LogisticsCompanyController extends Controller
{
    /**
     * عرض الصفحة العامة للشركات اللوجستية (بدون تسجيل دخول)
     */
    public function publicPage()
    {
        // بيانات تجريبية للصفحة العامة
        $stats = [
            'available_balance' => 125000,
            'total_funded' => 850000,
            'total_requests' => 23,
            'last_request_status' => 'تم الصرف',
            'last_request_date' => '2024-01-15'
        ];

        return view('logistics', compact('stats'));
    }

    /**
     * عرض لوحة تحكم الشركة اللوجستية
     */
    public function dashboard()
    {
        $user = Auth::user();
        $logisticsCompany = $user->logisticsCompany;

        // الإحصائيات الرئيسية
        $stats = [
            'available_balance' => $logisticsCompany->available_balance ?? 0,
            'credit_limit' => $logisticsCompany->credit_limit ?? 0,
            'used_credit' => $logisticsCompany->used_credit ?? 0,
            'total_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->id)->count(),
            'pending_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->id)
                ->where('status', 'pending')->count(),
            'approved_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->id)
                ->where('status', 'approved')->count(),
            'total_invoices' => Invoice::where('logistics_company_id', $logisticsCompany->id)->count(),
            'paid_invoices' => Invoice::where('logistics_company_id', $logisticsCompany->id)
                ->where('status', 'paid')->count(),
            'pending_invoices' => Invoice::where('logistics_company_id', $logisticsCompany->id)
                ->where('status', 'pending')->count(),
        ];

        return view('logistics.dashboard', compact('logisticsCompany', 'stats'));
    }

    /**
     * عرض صفحة الملف الشخصي للشركة اللوجستية
     */
    public function profile()
    {
        $user = Auth::user();

        // التحقق من نوع المستخدم والتأكد من وجود الشركة اللوجستية
        if ($user->user_type !== 'logistics') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $logisticsCompany = $user->logisticsCompany;

        // إذا لم تكن بيانات الشركة موجودة في الجدول المنفصل، استخدم بيانات المستخدم
        if (!$logisticsCompany) {
            $logisticsCompany = (object) [
                'id' => $user->id,
                'user_id' => $user->id,
                'company_name' => $user->company_name,
                'commercial_register' => $user->company_registration,
                'available_balance' => 0,
                'credit_limit' => 100000,
                'used_credit' => 0,
                'address' => null,
                'account_status' => $user->status,
                'user' => $user // إضافة reference للـ user
            ];
        }

        // الإحصائيات الرئيسية من قاعدة البيانات
        $stats = [
            'available_balance' => $logisticsCompany->available_balance ?? 0,
            'credit_limit' => $logisticsCompany->credit_limit ?? 0,
            'used_credit' => $logisticsCompany->used_credit ?? 0,
            'total_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->user_id)->count(),
            'pending_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->user_id)
                ->where('status', 'pending')->count(),
            'approved_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->user_id)
                ->where('status', 'approved')->count(),
            'disbursed_requests' => FundingRequest::where('logistics_company_id', $logisticsCompany->user_id)
                ->where('status', 'disbursed')->count(),
            'total_invoices' => Invoice::where('logistics_company_id', $logisticsCompany->user_id)->count(),
            'paid_invoices' => Invoice::where('logistics_company_id', $logisticsCompany->user_id)
                ->where('payment_status', 'paid')->count(),
            'pending_invoices' => Invoice::where('logistics_company_id', $logisticsCompany->user_id)
                ->where('payment_status', 'unpaid')->count(),
        ];

        // طلبات التمويل الحقيقية
        $fundingRequests = FundingRequest::where('logistics_company_id', $logisticsCompany->user_id)
            ->latest()
            ->get()
            ->map(function ($request) {
                $statusLabels = [
                    'pending' => 'معلق',
                    'under_review' => 'قيد المراجعة',
                    'approved' => 'موافق عليه',
                    'disbursed' => 'تم الصرف',
                    'rejected' => 'مرفوض'
                ];
                $reasonLabels = [
                    'operational' => 'تشغيلي',
                    'expansion' => 'توسع',
                    'equipment' => 'معدات',
                    'emergency' => 'طارئ',
                    'other' => 'أخرى'
                ];
                $request->status_label = $statusLabels[$request->status] ?? $request->status;
                $request->reason_label = $reasonLabels[$request->reason] ?? $request->reason;
                return $request;
            });

        // الفواتير الحقيقية
        $invoices = Invoice::where('logistics_company_id', $logisticsCompany->user_id)
            ->with(['serviceCompany'])
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

        // الشركات الطالبة للخدمة (العملاء الحقيقيين)
        $serviceCompanies = User::where('user_type', 'service_company')
            ->where('status', 'active')
            ->whereHas('invoices', function ($query) use ($logisticsCompany) {
                $query->where('logistics_company_id', $logisticsCompany->user_id);
            })
            ->withCount(['invoices' => function ($query) use ($logisticsCompany) {
                $query->where('logistics_company_id', $logisticsCompany->user_id);
            }])
            ->get()
            ->map(function ($client) use ($logisticsCompany) {
                $client->total_outstanding = Invoice::where('logistics_company_id', $logisticsCompany->user_id)
                    ->where('service_company_id', $client->id)
                    ->where('payment_status', '!=', 'paid')
                    ->sum('remaining_amount');
                $client->account_status = $client->status;
                return $client;
            });

        // طلبات المنتجات للمستخدم الحالي
        $productOrders = ProductOrder::where('user_id', $user->id)
            ->with(['product', 'paymentRequests'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // طلبات الدفع للمستخدم الحالي
        $paymentRequests = PaymentRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('logistics.profile', compact(
            'logisticsCompany',
            'stats',
            'fundingRequests',
            'invoices',
            'serviceCompanies',
            'productOrders',
            'paymentRequests'
        ));
    }

    /**
     * تحديث بيانات الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // التحقق من نوع المستخدم
        if ($user->user_type !== 'logistics') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_registration' => 'nullable|string|max:50',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'company_registration' => $request->company_registration,
        ]);

        return redirect()->route('logistics.profile')
            ->with('success', 'تم تحديث البيانات بنجاح');
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // التحقق من نوع المستخدم
        if ($user->user_type !== 'logistics') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ], [
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير وصغير ورقم ورمز خاص'
        ]);

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('logistics.profile')
            ->with('success', 'تم تحديث كلمة المرور بنجاح');
    }
}
