<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FundingRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * صفحة التقارير الرئيسية
     */
    public function index()
    {
        // إحصائيات عامة
        $generalStats = [
            'total_users' => User::count(),
            'total_funding_requests' => FundingRequest::count(),
            'total_invoices' => Invoice::count(),
            'total_payments' => Payment::count(),
            'total_orders' => ProductOrder::count(),
        ];

        // إحصائيات مالية
        $financialStats = [
            'total_funding_disbursed' => FundingRequest::where('status', 'disbursed')->sum('amount'),
            'total_payments_received' => Payment::where('status', 'confirmed')->sum('amount'),
            'total_orders_revenue' => ProductOrder::whereIn('status', ['delivered'])->sum('total_amount'),
            'total_outstanding' => Invoice::sum('remaining_amount'),
        ];

        // تجميع البيانات للنظرة العامة (متوافق مع view)
        $overview = [
            'total_revenue' => $financialStats['total_payments_received'] + $financialStats['total_orders_revenue'],
            'total_users' => $generalStats['total_users'],
            'total_transactions' => $generalStats['total_payments'] + $generalStats['total_orders'],
            'growth_rate' => $this->calculateGrowthRate(),
        ];

        // إعداد بيانات البطاقات
        $reports = [
            'financial' => [
                'revenue' => $financialStats['total_payments_received'],
                'profit' => $financialStats['total_payments_received'] * 0.15, // نسبة ربح افتراضية
            ],
            'users' => [
                'total' => $generalStats['total_users'],
                'active' => User::where('status', 'active')->count(),
            ],
            'products' => [
                'total_sales' => ProductOrder::sum('total_amount'),
                'low_stock' => 5, // عدد المنتجات منخفضة المخزون (قيمة تجريبية)
            ],
            'orders' => [
                'total' => $generalStats['total_orders'],
                'completed' => ProductOrder::where('status', 'delivered')->count(),
            ],
            'funding' => [
                'total_requests' => $generalStats['total_funding_requests'],
                'original_amount' => $financialStats['total_funding_disbursed'],
            ],
        ];

        return view('admin.reports.index', compact('generalStats', 'financialStats', 'overview', 'reports'));
    }

    /**
     * حساب معدل النمو
     */
    private function calculateGrowthRate()
    {
        $currentMonthUsers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($lastMonthUsers == 0) return 0;

        return round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1);
    }

    /**
     * تقرير المستخدمين
     */
    public function usersReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(6)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_users' => User::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'by_type' => User::selectRaw('user_type, count(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('user_type')
                ->get()
                ->keyBy('user_type'),
            'by_status' => User::selectRaw('status, count(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('status')
                ->get()
                ->keyBy('status'),
        ];

        // تحويل البيانات للتنسيق المطلوب في العرض
        $user_types = [];
        foreach ($stats['by_type'] as $type => $data) {
            $user_types[$type] = $data->count;
        }

        $users_stats = [
            'total' => $stats['total_users'],
            'active' => $stats['by_status']['active']->count ?? 0,
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'engagement_rate' => 87, // قيمة ثابتة حالياً
        ];

        return view('admin.reports.users', compact('stats', 'dateFrom', 'dateTo', 'user_types', 'users_stats'));
    }

    /**
     * تقرير مالي
     */
    public function financialReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_revenue' => Payment::where('status', 'confirmed')
                ->whereBetween('payment_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'total_funding' => FundingRequest::where('status', 'disbursed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('amount'),
            'total_outstanding' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('remaining_amount'),
            'total_paid' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('paid_amount'),
        ];

        return view('admin.reports.financial', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * تقرير المنتجات
     */
    public function productsReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_orders' => ProductOrder::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_revenue' => ProductOrder::whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('total_amount'),
            'total_quantity' => ProductOrder::whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('quantity'),
            'avg_order_value' => ProductOrder::whereBetween('created_at', [$dateFrom, $dateTo])
                ->avg('total_amount'),
        ];

        return view('admin.reports.products', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * تقرير الطلبات
     */
    public function ordersReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_orders' => ProductOrder::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'pending_orders' => ProductOrder::where('status', 'pending')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'completed_orders' => ProductOrder::where('status', 'delivered')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'cancelled_orders' => ProductOrder::where('status', 'cancelled')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
        ];

        return view('admin.reports.orders', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * تقرير التمويل
     */
    public function fundingReport(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(6)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_requests' => FundingRequest::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'approved_requests' => FundingRequest::where('status', 'approved')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'disbursed_amount' => FundingRequest::where('status', 'disbursed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->sum('amount'),
            'pending_requests' => FundingRequest::where('status', 'pending')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
        ];

        return view('admin.reports.funding', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * تقرير الإيرادات
     */
    public function revenue(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $totalStats = [
            'total_payments' => Payment::where('status', 'confirmed')
                ->whereBetween('payment_date', [$dateFrom, $dateTo])
                ->sum('amount'),
            'total_sales' => ProductOrder::whereIn('status', ['delivered'])
                ->whereBetween('ordered_at', [$dateFrom, $dateTo])
                ->sum('total_amount'),
        ];

        return view('admin.reports.revenue', compact('totalStats', 'dateFrom', 'dateTo'));
    }

    /**
     * تقرير الفواتير
     */
    public function invoices(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $stats = [
            'total_invoices' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'total_amount' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])->sum('original_amount'),
            'paid_amount' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])->sum('paid_amount'),
            'outstanding_amount' => Invoice::whereBetween('created_at', [$dateFrom, $dateTo])->sum('remaining_amount'),
        ];

        return view('admin.reports.invoices', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * تقرير المنتجات
     */
    public function products(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $salesStats = [
            'total_orders' => ProductOrder::whereBetween('ordered_at', [$dateFrom, $dateTo])->count(),
            'total_revenue' => ProductOrder::whereBetween('ordered_at', [$dateFrom, $dateTo])->sum('total_amount'),
            'total_quantity' => ProductOrder::whereBetween('ordered_at', [$dateFrom, $dateTo])->sum('quantity'),
        ];

        return view('admin.reports.products', compact('salesStats', 'dateFrom', 'dateTo'));
    }

    /**
     * تصدير التقرير المالي
     */
    public function exportFinancial(Request $request)
    {
        return response()->json(['message' => 'سيتم إضافة تصدير التقرير المالي قريباً']);
    }

    /**
     * تصدير تقرير المستخدمين
     */
    public function exportUsers(Request $request)
    {
        return response()->json(['message' => 'سيتم إضافة تصدير تقرير المستخدمين قريباً']);
    }

    /**
     * تصدير تقرير المنتجات
     */
    public function exportProducts(Request $request)
    {
        return response()->json(['message' => 'سيتم إضافة تصدير تقرير المنتجات قريباً']);
    }

    /**
     * تصدير تقرير عام
     */
    public function export(Request $request, $type)
    {
        return response()->json(['message' => 'سيتم إضافة التصدير قريباً']);
    }
}
