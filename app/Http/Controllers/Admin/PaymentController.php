<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * عرض جميع المدفوعات
     */
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.serviceCompany.user', 'invoice.logisticsCompany.user']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب طريقة الدفع
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function($iq) use ($search) {
                      $iq->where('invoice_number', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest('payment_date')->paginate(20);

        // إحصائيات
        $stats = [
            'total_payments' => Payment::count(),
            'confirmed_payments' => Payment::where('status', 'confirmed')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'rejected_payments' => Payment::where('status', 'rejected')->count(),
            'total_amount' => Payment::where('status', 'confirmed')->sum('amount'),
            'avg_payment_amount' => Payment::where('status', 'confirmed')->avg('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * عرض تفاصيل المدفوعة
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'invoice.serviceCompany.user.profile',
            'invoice.logisticsCompany.user.profile',
            'invoice.payments' => function($query) {
                $query->latest('payment_date');
            }
        ]);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * تحديث حالة المدفوعة
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $payment->status;
        $newStatus = $request->status;

        DB::transaction(function() use ($payment, $request, $oldStatus, $newStatus) {
            $payment->update([
                'status' => $newStatus,
                'admin_notes' => $request->admin_notes,
                'verified_at' => $newStatus === 'confirmed' ? now() : null,
                'verified_by' => $newStatus === 'confirmed' ? auth()->id() : null,
            ]);

            $invoice = $payment->invoice;

            // تحديث الفاتورة حسب الحالة الجديدة
            if ($oldStatus !== 'confirmed' && $newStatus === 'confirmed') {
                $invoice->increment('paid_amount', $payment->amount);
                $invoice->decrement('remaining_amount', $payment->amount);

                if ($invoice->remaining_amount <= 0) {
                    $invoice->update(['payment_status' => 'paid', 'status' => 'paid']);
                } else {
                    $invoice->update(['payment_status' => 'partial']);
                }

                $serviceCompany = $invoice->serviceCompany;
                $serviceCompany->increment('total_paid', $payment->amount);
                $serviceCompany->decrement('total_outstanding', $payment->amount);
            }
        });

        return redirect()->back()->with('success', 'تم تحديث حالة المدفوعة بنجاح');
    }

    /**
     * تأكيد المدفوعة
     */
    public function confirm(Request $request, Payment $payment)
    {
        if ($payment->status === 'confirmed') {
            return redirect()->back()->with('error', 'المدفوعة مؤكدة بالفعل');
        }

        $payment->update([
            'status' => 'confirmed',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'تم تأكيد المدفوعة بنجاح');
    }

    /**
     * رفض المدفوعة
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'تم رفض المدفوعة');
    }
}
