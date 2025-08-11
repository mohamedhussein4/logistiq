<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundingRequest;
use App\Models\LogisticsCompany;
use App\Models\ClientDebt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundingRequestController extends Controller
{
    /**
     * عرض جميع طلبات التمويل
     */
    public function index(Request $request)
    {
        $query = FundingRequest::with(['logisticsCompany.user']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الشركة
        if ($request->filled('company_id')) {
            $query->where('logistics_company_id', $request->company_id);
        }

        // فلترة حسب المبلغ
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('requested_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('requested_at', '<=', $request->date_to);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('logisticsCompany.user', function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhere('description', 'like', "%{$search}%");
        }

        $fundingRequests = $query->latest('requested_at')->paginate(20);

        // إحصائيات
        $stats = [
            'total_requests' => FundingRequest::count(),
            'pending_requests' => FundingRequest::where('status', 'pending')->count(),
            'approved_requests' => FundingRequest::where('status', 'approved')->count(),
            'disbursed_requests' => FundingRequest::where('status', 'disbursed')->count(),
            'rejected_requests' => FundingRequest::where('status', 'rejected')->count(),
            'total_amount_requested' => FundingRequest::sum('amount'),
            'total_amount_disbursed' => FundingRequest::where('status', 'disbursed')->sum('amount'),
            'avg_request_amount' => FundingRequest::avg('amount'),
        ];

        // الشركات للفلتر
        $companies = LogisticsCompany::with('user')->get();

        return view('admin.funding-requests.index', compact('fundingRequests', 'stats', 'companies'));
    }

    /**
     * عرض تفاصيل طلب التمويل
     */
    public function show(FundingRequest $fundingRequest)
    {
        $fundingRequest->load([
            'logisticsCompany.user.profile', 
            'logisticsCompany.fundingRequests',
            'clientDebts'
        ]);

        // آخر طلبات هذه الشركة
        $recentRequests = $fundingRequest->logisticsCompany
            ->fundingRequests()
            ->where('id', '!=', $fundingRequest->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.funding-requests.show', compact('fundingRequest', 'recentRequests'));
    }

    /**
     * تحديث حالة طلب التمويل
     */
    public function updateStatus(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,approved,rejected,disbursed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request, $fundingRequest) {
            $oldStatus = $fundingRequest->status;
            $newStatus = $request->status;

            // تحديث الطلب
            $fundingRequest->update([
                'status' => $newStatus,
                'admin_notes' => $request->admin_notes,
                'approved_at' => $newStatus === 'approved' ? now() : $fundingRequest->approved_at,
                'disbursed_at' => $newStatus === 'disbursed' ? now() : $fundingRequest->disbursed_at,
                'rejected_at' => $newStatus === 'rejected' ? now() : null,
            ]);

            // إذا تم الصرف، تحديث رصيد الشركة
            if ($newStatus === 'disbursed' && $oldStatus !== 'disbursed') {
                $logisticsCompany = $fundingRequest->logisticsCompany;
                $logisticsCompany->increment('available_balance', $fundingRequest->amount);
                $logisticsCompany->increment('total_funded', $fundingRequest->amount);
                $logisticsCompany->increment('total_requests');
                $logisticsCompany->update(['last_request_status' => 'تم الصرف']);
            }

            // إذا تم الرفض بعد الموافقة، استرداد المبلغ
            if ($newStatus === 'rejected' && $oldStatus === 'disbursed') {
                $logisticsCompany = $fundingRequest->logisticsCompany;
                $logisticsCompany->decrement('available_balance', $fundingRequest->amount);
                $logisticsCompany->decrement('total_funded', $fundingRequest->amount);
                $logisticsCompany->update(['last_request_status' => 'مرفوض']);
            }
        });

        return redirect()->back()->with('success', 'تم تحديث حالة طلب التمويل بنجاح');
    }

    /**
     * الموافقة على طلب التمويل
     */
    public function approve(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($fundingRequest->status !== 'pending' && $fundingRequest->status !== 'under_review') {
            return redirect()->back()->with('error', 'لا يمكن الموافقة على هذا الطلب في حالته الحالية');
        }

        $fundingRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'تم الموافقة على طلب التمويل بنجاح');
    }

    /**
     * رفض طلب التمويل
     */
    public function reject(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        if ($fundingRequest->status === 'disbursed') {
            return redirect()->back()->with('error', 'لا يمكن رفض طلب تم صرفه بالفعل');
        }

        DB::transaction(function () use ($request, $fundingRequest) {
            // إذا كان الطلب مصروف، استرداد المبلغ
            if ($fundingRequest->status === 'disbursed') {
                $logisticsCompany = $fundingRequest->logisticsCompany;
                $logisticsCompany->decrement('available_balance', $fundingRequest->amount);
                $logisticsCompany->decrement('total_funded', $fundingRequest->amount);
            }

            $fundingRequest->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            $fundingRequest->logisticsCompany->update([
                'last_request_status' => 'مرفوض'
            ]);
        });

        return redirect()->back()->with('success', 'تم رفض طلب التمويل');
    }

    /**
     * صرف المبلغ
     */
    public function disburse(Request $request, FundingRequest $fundingRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($fundingRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'يجب الموافقة على الطلب أولاً قبل الصرف');
        }

        DB::transaction(function () use ($request, $fundingRequest) {
            // تحديث الطلب
            $fundingRequest->update([
                'status' => 'disbursed',
                'disbursed_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            // تحديث رصيد الشركة
            $logisticsCompany = $fundingRequest->logisticsCompany;
            $logisticsCompany->increment('available_balance', $fundingRequest->amount);
            $logisticsCompany->increment('total_funded', $fundingRequest->amount);
            $logisticsCompany->increment('total_requests');
            $logisticsCompany->update(['last_request_status' => 'تم الصرف']);
        });

        return redirect()->back()->with('success', 'تم صرف المبلغ بنجاح وإضافته لرصيد الشركة');
    }

    /**
     * إحصائيات متقدمة
     */
    public function analytics()
    {
        $stats = [
            // إحصائيات عامة
            'total_requests' => FundingRequest::count(),
            'total_amount' => FundingRequest::sum('amount'),
            'average_amount' => FundingRequest::avg('amount'),

            // إحصائيات حسب الحالة
            'by_status' => FundingRequest::selectRaw('status, count(*) as count, sum(amount) as total_amount')
                ->groupBy('status')
                ->get()
                ->keyBy('status'),

            // إحصائيات شهرية
            'monthly_requests' => FundingRequest::selectRaw('MONTH(requested_at) as month, count(*) as count, sum(amount) as total')
                ->whereYear('requested_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get(),

            // أكبر الطلبات
            'largest_requests' => FundingRequest::with('logisticsCompany.user')
                ->orderBy('amount', 'desc')
                ->take(10)
                ->get(),

            // أنشط الشركات
            'most_active_companies' => LogisticsCompany::withCount('fundingRequests')
                ->with('user')
                ->orderBy('funding_requests_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.funding-requests.analytics', compact('stats'));
    }

    /**
     * تصدير البيانات
     */
    public function export(Request $request)
    {
        $query = FundingRequest::with(['logisticsCompany.user']);

        // تطبيق نفس الفلاتر
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('requested_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('requested_at', '<=', $request->date_to);
        }

        $requests = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="funding_requests_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'رقم الطلب',
                'اسم الشركة',
                'المبلغ',
                'السبب',
                'الحالة',
                'تاريخ الطلب',
                'تاريخ الموافقة',
                'تاريخ الصرف',
                'ملاحظات الإدارة'
            ]);

            // Data
            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->logisticsCompany->user->company_name,
                    number_format($request->amount, 2),
                    $request->reason,
                    $request->getStatusNameAttribute(),
                    $request->requested_at->format('Y-m-d H:i'),
                    $request->approved_at?->format('Y-m-d H:i'),
                    $request->disbursed_at?->format('Y-m-d H:i'),
                    $request->admin_notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
