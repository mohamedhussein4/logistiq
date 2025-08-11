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

        return view('admin.reports.index', compact('generalStats', 'financialStats'));
    }

    /**
     * تقرير المستخدمين
     */
    public function users(Request $request)
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

        return view('admin.reports.users', compact('stats', 'dateFrom', 'dateTo'));
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
     * تصدير تقرير
     */
    public function export(Request $request, $type)
    {
        return response()->json(['message' => 'سيتم إضافة التصدير قريباً']);
    }
}
