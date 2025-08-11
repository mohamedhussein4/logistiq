<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogisticsCompany;
use App\Models\ServiceCompany;
use App\Models\FundingRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ContactRequest;
use App\Models\LinkingService;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', User::STATUS_ACTIVE)->count(),
            'pending_users' => User::where('status', User::STATUS_PENDING)->count(),

            'logistics_companies' => User::where('user_type', User::TYPE_LOGISTICS)->count(),
            'service_companies' => User::where('user_type', User::TYPE_SERVICE_COMPANY)->count(),
            'regular_users' => User::where('user_type', User::TYPE_REGULAR)->count(),

            'total_funding_requests' => FundingRequest::count(),
            'pending_funding_requests' => FundingRequest::where('status', 'pending')->count(),
            'approved_funding_requests' => FundingRequest::where('status', 'approved')->count(),

            'total_invoices' => Invoice::count(),
            'overdue_invoices' => Invoice::overdue()->count(),
            'paid_invoices' => Invoice::paid()->count(),

            'total_orders' => ProductOrder::count(),
            'pending_orders' => ProductOrder::where('status', 'pending')->count(),
            'completed_orders' => ProductOrder::where('status', 'delivered')->count(),

            'total_contact_requests' => ContactRequest::count(),
            'new_contact_requests' => ContactRequest::where('status', 'new')->count(),

            'total_revenue' => Payment::where('status', 'confirmed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'confirmed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        $recent_activities = [
            'new_users' => User::latest()->take(5)->get(),
            'recent_funding_requests' => FundingRequest::with('logisticsCompany')
                ->latest()
                ->take(5)
                ->get(),
            'recent_payments' => Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
                ->leftJoin('users as service_companies', 'invoices.service_company_id', '=', 'service_companies.id')
                ->select(
                    'payments.*',
                    'invoices.invoice_number',
                    'service_companies.company_name as service_company_name',
                    'service_companies.name as service_company_user_name'
                )
                ->latest('payments.created_at')
                ->take(5)
                ->get(),
            'recent_orders' => ProductOrder::with(['user', 'product'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        $monthly_stats = [
            'users_growth' => $this->getMonthlyUserGrowth(),
            'revenue_trend' => $this->getMonthlyRevenueTrend(),
            'orders_trend' => $this->getMonthlyOrdersTrend(),
        ];

        return view('admin.dashboard', compact('stats', 'recent_activities', 'monthly_stats'));
    }

    /**
     * نمو المستخدمين الشهري
     */
    private function getMonthlyUserGrowth()
    {
        return User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->count];
            });
    }

    /**
     * اتجاه الإيرادات الشهرية
     */
    private function getMonthlyRevenueTrend()
    {
        return Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->where('status', 'confirmed')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->total];
            });
    }

    /**
     * اتجاه الطلبات الشهرية
     */
    private function getMonthlyOrdersTrend()
    {
        return ProductOrder::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->month => $item->count];
            });
    }



    /**
     * عرض طلبات التمويل
     */
    public function fundingRequests(Request $request)
    {
        $query = FundingRequest::with('logisticsCompany');

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->latest()->paginate(20);

        return view('admin.funding-requests.index', compact('requests'));
    }

    /**
     * تحديث حالة طلب التمويل
     */
    public function updateFundingRequestStatus(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,approved,disbursed,rejected',
            'notes' => 'nullable|string'
        ]);

        $fundingRequest->update(['status' => $request->status]);

        // إذا تم الموافقة، نضيف المبلغ للرصيد المتاح
        if ($request->status === 'disbursed') {
            $logisticsCompany = $fundingRequest->logisticsCompany;
            $logisticsCompany->increment('available_balance', $fundingRequest->amount);
            $logisticsCompany->increment('total_funded', $fundingRequest->amount);
            $logisticsCompany->update(['last_request_status' => 'تم الصرف']);

            $fundingRequest->update(['disbursed_at' => now()]);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * عرض الفواتير
     */
    public function invoices(Request $request)
    {
        $query = Invoice::with(['serviceCompany', 'logisticsCompany']);

        // فلترة حسب حالة الدفع
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // فلترة حسب تاريخ الاستحقاق
        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->due_date_to);
        }

        $invoices = $query->latest()->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * عرض تفاصيل فاتورة
     */
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['serviceCompany', 'logisticsCompany', 'payments', 'installmentPlan.installmentPayments']);

        return view('admin.invoices.show', compact('invoice'));
    }




}
