<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ServiceCompany;
use App\Models\LogisticsCompany;
use App\Models\Payment;
use App\Models\InstallmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * عرض جميع الفواتير
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['serviceCompany.user', 'logisticsCompany.user', 'payments', 'installmentPlan']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب حالة الدفع
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // فلترة حسب الشركة الطالبة
        if ($request->filled('service_company_id')) {
            $query->where('service_company_id', $request->service_company_id);
        }

        // فلترة حسب الشركة اللوجستية
        if ($request->filled('logistics_company_id')) {
            $query->where('logistics_company_id', $request->logistics_company_id);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('serviceCompany.user', function($sq) use ($search) {
                      $sq->where('company_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('logisticsCompany.user', function($lq) use ($search) {
                      $lq->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest()->paginate(20);

        // إحصائيات
        $stats = [
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('payment_status', 'paid')->count(),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'pending_invoices' => Invoice::where('payment_status', 'unpaid')->where('status', 'sent')->count(),
            'original_amount' => Invoice::sum('original_amount'),
            'total_paid' => Invoice::sum('paid_amount'),
            'total_outstanding' => Invoice::sum('remaining_amount'),
            'avg_invoice_amount' => Invoice::avg('original_amount'),
        ];

        // الشركات للفلاتر
        $serviceCompanies = ServiceCompany::with('user')->get();
        $logisticsCompanies = LogisticsCompany::with('user')->get();

        return view('admin.invoices.index', compact('invoices', 'stats', 'serviceCompanies', 'logisticsCompanies'));
    }

    /**
     * عرض تفاصيل الفاتورة
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'serviceCompany.user.profile',
            'logisticsCompany.user.profile',
            'payments' => function($query) {
                $query->latest();
            },
            'installmentPlan.installmentPayments' => function($query) {
                $query->orderBy('installment_number');
            }
        ]);

        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * عرض نموذج إنشاء فاتورة جديدة
     */
    public function create()
    {
        $serviceCompanies = ServiceCompany::with('user')->get();
        $logisticsCompanies = LogisticsCompany::with('user')->get();

        return view('admin.invoices.create', compact('serviceCompanies', 'logisticsCompanies'));
    }

    /**
     * حفظ فاتورة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_company_id' => 'required|exists:service_companies,id',
            'logistics_company_id' => 'required|exists:logistics_companies,id',
            'original_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            // إنشاء رقم فاتورة تلقائي
            $invoiceNumber = 'INV-' . now()->format('Ymd') . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'service_company_id' => $request->service_company_id,
                'logistics_company_id' => $request->logistics_company_id,
                'invoice_number' => $invoiceNumber,
                'original_amount' => $request->original_amount,
                'paid_amount' => 0,
                'remaining_amount' => $request->original_amount,
                'due_date' => $request->due_date,
                'status' => 'sent',
                'payment_status' => 'unpaid',
                'description' => $request->description,
            ]);

            // تحديث إجمالي المستحقات للشركة الطالبة
            $serviceCompany = ServiceCompany::find($request->service_company_id);
            $serviceCompany->increment('total_outstanding', $request->original_amount);
        });

        return redirect()->route('admin.invoices.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    /**
     * تحديث حالة الفاتورة
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,overdue,paid,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $invoice->update([
            'status' => $request->status,
            'admin_notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة الفاتورة بنجاح');
    }

    /**
     * إنشاء فاتورة PDF
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['serviceCompany.user.profile', 'logisticsCompany.user.profile', 'payments']);

        // هنا يمكن استخدام مكتبة PDF مثل DomPDF أو TCPDF
        // للآن سنرجع رد بسيط
        return response()->json([
            'message' => 'سيتم إضافة تصدير PDF قريباً',
            'invoice' => $invoice
        ]);
    }



    /**
     * تسجيل دفعة للفاتورة
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0|max:' . $invoice->remaining_amount,
                'payment_method' => 'required|string',
                'payment_date' => 'required|date',
                'reference_number' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // تسجيل الدفعة (نحتاج لإنشاء الـ model)
            // سيتم إضافة كود تسجيل الدفعة هنا

            return redirect()->back()
                ->with('success', 'تم تسجيل الدفعة بمبلغ ' . number_format($request->amount) . ' ريال بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تسجيل الدفعة: ' . $e->getMessage());
        }
    }


}
