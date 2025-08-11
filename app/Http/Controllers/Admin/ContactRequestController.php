<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    /**
     * عرض جميع طلبات التواصل
     */
    public function index(Request $request)
    {
        $query = ContactRequest::query();

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب نوع الخدمة
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contactRequests = $query->latest()->paginate(20);

        // إحصائيات
        $stats = [
            'total_requests' => ContactRequest::count(),
            'new_requests' => ContactRequest::where('status', 'new')->count(),
            'in_progress_requests' => ContactRequest::where('status', 'in_progress')->count(),
            'completed_requests' => ContactRequest::where('status', 'completed')->count(),
            'today_requests' => ContactRequest::whereDate('created_at', today())->count(),
            'this_week_requests' => ContactRequest::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_requests' => ContactRequest::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.contact-requests.index', compact('contactRequests', 'stats'));
    }

    /**
     * عرض تفاصيل طلب التواصل
     */
    public function show(ContactRequest $contactRequest)
    {
        return view('admin.contact-requests.show', compact('contactRequest'));
    }

    /**
     * تحديث حالة طلب التواصل
     */
    public function updateStatus(Request $request, ContactRequest $contactRequest)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $contactRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'responded_at' => $request->status === 'in_progress' ? now() : $contactRequest->responded_at,
            'completed_at' => $request->status === 'completed' ? now() : $contactRequest->completed_at,
        ]);

        $statusName = ContactRequest::getStatuses()[$request->status];
        return redirect()->back()->with('success', "تم تحديث حالة الطلب إلى: {$statusName}");
    }

    /**
     * إضافة رد على طلب التواصل
     */
    public function respond(Request $request, ContactRequest $contactRequest)
    {
        $request->validate([
            'response_message' => 'required|string|max:2000',
            'response_method' => 'required|in:email,phone,whatsapp,meeting',
        ]);

        $contactRequest->update([
            'status' => 'in_progress',
            'response_message' => $request->response_message,
            'response_method' => $request->response_method,
            'responded_at' => now(),
            'responded_by' => auth()->id(),
        ]);

        // هنا يمكن إضافة منطق إرسال الرد عبر البريد الإلكتروني أو الواتساب

        return redirect()->back()->with('success', 'تم إرسال الرد بنجاح');
    }

    /**
     * حذف طلب التواصل
     */
    public function destroy(ContactRequest $contactRequest)
    {
        $contactRequest->delete();
        return redirect()->route('admin.contact_requests.index')->with('success', 'تم حذف طلب التواصل بنجاح');
    }

    /**
     * إحصائيات متقدمة
     */
    public function analytics()
    {
        $stats = [
            // إحصائيات عامة
            'total_requests' => ContactRequest::count(),
            'response_rate' => ContactRequest::whereNotNull('responded_at')->count() / max(ContactRequest::count(), 1) * 100,
            'avg_response_time' => ContactRequest::whereNotNull('responded_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours')
                ->first()->avg_hours ?? 0,

            // إحصائيات حسب الحالة
            'by_status' => ContactRequest::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->keyBy('status'),

            // إحصائيات حسب نوع الخدمة
            'by_service_type' => ContactRequest::selectRaw('service_type, count(*) as count')
                ->groupBy('service_type')
                ->get()
                ->keyBy('service_type'),

            // إحصائيات شهرية
            'monthly_requests' => ContactRequest::selectRaw('MONTH(created_at) as month, count(*) as count')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get(),

            // معدل الاستجابة الشهري
            'monthly_response_rate' => ContactRequest::selectRaw('
                    MONTH(created_at) as month,
                    count(*) as total,
                    sum(case when responded_at is not null then 1 else 0 end) as responded,
                    round(sum(case when responded_at is not null then 1 else 0 end) / count(*) * 100, 2) as response_rate
                ')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];

        return view('admin.contact-requests.analytics', compact('stats'));
    }

    /**
     * تصدير طلبات التواصل
     */
    public function export(Request $request)
    {
        $query = ContactRequest::query();

        // تطبيق الفلاتر
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="contact_requests_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'رقم الطلب',
                'اسم الشركة',
                'الشخص المسؤول',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'نوع الخدمة',
                'الرسالة',
                'الحالة',
                'تاريخ الطلب',
                'تاريخ الرد',
                'رسالة الرد'
            ]);

            // Data
            foreach ($requests as $contactRequest) {
                fputcsv($file, [
                    $contactRequest->id,
                    $contactRequest->company_name,
                    $contactRequest->contact_person,
                    $contactRequest->email,
                    $contactRequest->phone,
                    $contactRequest->getServiceTypeNameAttribute(),
                    $contactRequest->message,
                    $contactRequest->getStatusNameAttribute(),
                    $contactRequest->created_at->format('Y-m-d H:i'),
                    $contactRequest->responded_at?->format('Y-m-d H:i'),
                    $contactRequest->response_message
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * إشارة طلبات التواصل المهمة
     */
    public function markImportant(ContactRequest $contactRequest)
    {
        $contactRequest->update(['is_important' => !$contactRequest->is_important]);

        $status = $contactRequest->is_important ? 'مهم' : 'عادي';
        return redirect()->back()->with('success', "تم تحديد الطلب كـ {$status}");
    }

    /**
     * الحصول على إحصائيات سريعة للداشبورد
     */
    public function quickStats()
    {
        return response()->json([
            'new_today' => ContactRequest::where('status', 'new')->whereDate('created_at', today())->count(),
            'pending_response' => ContactRequest::where('status', 'new')->count(),
            'total_this_month' => ContactRequest::whereMonth('created_at', now()->month)->count(),
            'avg_response_time' => ContactRequest::whereNotNull('responded_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours')
                ->first()->avg_hours ?? 0,
        ]);
    }
}
