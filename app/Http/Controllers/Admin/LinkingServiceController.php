<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinkingService;
use App\Models\LogisticsCompany;
use App\Models\ServiceCompany;
use Illuminate\Http\Request;

class LinkingServiceController extends Controller
{
    /**
     * عرض جميع خدمات الربط
     */
    public function index(Request $request)
    {
        $query = LinkingService::with(['logisticsCompany.user', 'serviceCompany.user']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب نوع الخدمة
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // فلترة حسب الشركة اللوجستية
        if ($request->filled('logistics_company_id')) {
            $query->where('logistics_company_id', $request->logistics_company_id);
        }

        // فلترة حسب الشركة الطالبة
        if ($request->filled('service_company_id')) {
            $query->where('service_company_id', $request->service_company_id);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('logisticsCompany.user', function($lq) use ($search) {
                    $lq->where('company_name', 'like', "%{$search}%");
                })->orWhereHas('serviceCompany.user', function($sq) use ($search) {
                    $sq->where('company_name', 'like', "%{$search}%");
                });
            });
        }

        $linkingServices = $query->latest('linked_at')->paginate(20);

        // إحصائيات
        $stats = [
            'total_services' => LinkingService::count(),
            'active_services' => LinkingService::where('status', 'active')->count(),
            'completed_services' => LinkingService::where('status', 'completed')->count(),
            'pending_services' => LinkingService::where('status', 'pending')->count(),
            'total_amount' => LinkingService::sum('amount'),
            'total_commission' => LinkingService::sum('commission'),
            'avg_service_amount' => LinkingService::avg('amount'),
        ];

        // بيانات للفلاتر
        $logisticsCompanies = LogisticsCompany::with('user')->get();
        $serviceCompanies = ServiceCompany::with('user')->get();

        return view('admin.linking-services.index', compact('linkingServices', 'stats', 'logisticsCompanies', 'serviceCompanies'));
    }

    /**
     * عرض نموذج إنشاء خدمة ربط جديدة
     */
    public function create()
    {
        $logisticsCompanies = LogisticsCompany::with('user')->get();
        $serviceCompanies = ServiceCompany::with('user')->get();

        return view('admin.linking-services.create', compact('logisticsCompanies', 'serviceCompanies'));
    }

    /**
     * حفظ خدمة ربط جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'logistics_company_id' => 'required|exists:logistics_companies,id',
            'service_company_id' => 'required|exists:service_companies,id',
            'service_type' => 'required|in:financing,logistics,warehousing,distribution',
            'amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        // التحقق من عدم وجود ربط مكرر
        $existingService = LinkingService::where('logistics_company_id', $request->logistics_company_id)
            ->where('service_company_id', $request->service_company_id)
            ->where('service_type', $request->service_type)
            ->where('status', 'active')
            ->exists();

        if ($existingService) {
            return redirect()->back()->with('error', 'يوجد ربط نشط بالفعل بين هاتين الشركتين لنفس نوع الخدمة');
        }

        $commission = $request->amount * ($request->commission_rate / 100);

        LinkingService::create([
            'logistics_company_id' => $request->logistics_company_id,
            'service_company_id' => $request->service_company_id,
            'service_type' => $request->service_type,
            'status' => 'active',
            'amount' => $request->amount,
            'commission' => $commission,
            'commission_rate' => $request->commission_rate,
            'description' => $request->description,
            'linked_at' => now(),
        ]);

        return redirect()->route('admin.linking_services.index')->with('success', 'تم إنشاء خدمة الربط بنجاح');
    }

    /**
     * عرض تفاصيل خدمة الربط
     */
    public function show(LinkingService $linkingService)
    {
        $linkingService->load([
            'logisticsCompany.user.profile',
            'serviceCompany.user.profile'
        ]);

        return view('admin.linking-services.show', compact('linkingService'));
    }

    /**
     * تحديث حالة خدمة الربط
     */
    public function updateStatus(Request $request, LinkingService $linkingService)
    {
        $request->validate([
            'status' => 'required|in:active,completed,pending,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $linkingService->update([
            'status' => $request->status,
            'admin_notes' => $request->notes,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة خدمة الربط بنجاح');
    }

    /**
     * تحديث العمولة
     */
    public function updateCommission(Request $request, LinkingService $linkingService)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $newCommission = $linkingService->amount * ($request->commission_rate / 100);

        $linkingService->update([
            'commission_rate' => $request->commission_rate,
            'commission' => $newCommission,
            'admin_notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'تم تحديث العمولة بنجاح');
    }

    /**
     * إحصائيات متقدمة
     */
    public function analytics()
    {
        $stats = [
            // إحصائيات عامة
            'total_services' => LinkingService::count(),
            'total_amount' => LinkingService::sum('amount'),
            'total_commission' => LinkingService::sum('commission'),
            'avg_commission_rate' => LinkingService::avg('commission_rate'),

            // إحصائيات حسب الحالة
            'by_status' => LinkingService::selectRaw('status, count(*) as count, sum(amount) as total_amount, sum(commission) as total_commission')
                ->groupBy('status')
                ->get()
                ->keyBy('status'),

            // إحصائيات حسب نوع الخدمة
            'by_service_type' => LinkingService::selectRaw('service_type, count(*) as count, sum(amount) as total_amount')
                ->groupBy('service_type')
                ->get()
                ->keyBy('service_type'),

            // إحصائيات شهرية
            'monthly_services' => LinkingService::selectRaw('MONTH(linked_at) as month, count(*) as count, sum(amount) as total, sum(commission) as commission')
                ->whereYear('linked_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get(),

            // أكبر الخدمات
            'largest_services' => LinkingService::with(['logisticsCompany.user', 'serviceCompany.user'])
                ->orderBy('amount', 'desc')
                ->take(10)
                ->get(),

            // أنشط الشركات اللوجستية
            'top_logistics_companies' => LogisticsCompany::withCount('linkingServices')
                ->withSum('linkingServices', 'amount')
                ->with('user')
                ->orderBy('linking_services_sum_amount', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.linking-services.analytics', compact('stats'));
    }

    /**
     * تصدير خدمات الربط
     */
    public function export(Request $request)
    {
        $query = LinkingService::with(['logisticsCompany.user', 'serviceCompany.user']);

        // تطبيق الفلاتر
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $services = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="linking_services_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($services) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'رقم الخدمة',
                'الشركة اللوجستية',
                'الشركة الطالبة',
                'نوع الخدمة',
                'المبلغ',
                'معدل العمولة',
                'العمولة',
                'الحالة',
                'تاريخ الربط',
                'تاريخ الإكمال'
            ]);

            // Data
            foreach ($services as $service) {
                fputcsv($file, [
                    $service->id,
                    $service->logisticsCompany->user->company_name,
                    $service->serviceCompany->user->company_name,
                    $service->getServiceTypeNameAttribute(),
                    number_format($service->amount, 2),
                    $service->commission_rate . '%',
                    number_format($service->commission, 2),
                    $service->getStatusNameAttribute(),
                    $service->linked_at->format('Y-m-d'),
                    $service->completed_at?->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
