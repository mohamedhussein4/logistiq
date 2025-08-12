<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\PaymentProof;
use App\Models\BankAccount;
use App\Models\ElectronicWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * عرض قائمة طلبات الدفع
     */
    public function index(Request $request)
    {
        $query = PaymentRequest::with(['user', 'paymentProofs']);

        // فلترة حسب النوع
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $paymentRequests = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => PaymentRequest::count(),
            'pending' => PaymentRequest::where('status', 'pending')->count(),
            'processing' => PaymentRequest::where('status', 'processing')->count(),
            'completed' => PaymentRequest::where('status', 'completed')->count(),
            'failed' => PaymentRequest::where('status', 'failed')->count(),
        ];

        return view('admin.payments.index', compact('paymentRequests', 'stats'));
    }

    /**
     * عرض تفاصيل طلب الدفع
     */
    public function show($id)
    {
        $paymentRequest = PaymentRequest::with([
            'user',
            'paymentProofs',
            'bankAccount',
            'electronicWallet'
        ])->findOrFail($id);

        return view('admin.payments.show', compact('paymentRequest'));
    }

    /**
     * تحديث حالة طلب الدفع
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $paymentRequest = PaymentRequest::findOrFail($id);

        try {
            DB::beginTransaction();

            $paymentRequest->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'processed_at' => now(),
            ]);

            // إذا تم إكمال الدفع، تحديث العنصر المرتبط
            if ($request->status === 'completed') {
                $this->processCompletedPayment($paymentRequest);
            }

            DB::commit();

            return back()->with('success', 'تم تحديث حالة طلب الدفع بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث الحالة.');
        }
    }

    /**
     * مراجعة إثبات الدفع
     */
    public function reviewProof(Request $request, $proofId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        $proof = PaymentProof::findOrFail($proofId);
        $paymentRequest = $proof->paymentRequest;

        try {
            DB::beginTransaction();

            if ($request->action === 'approve') {
                $proof->approve(auth()->id());

                // إذا كان هذا أول إثبات موافق عليه، تحديث حالة طلب الدفع
                if ($paymentRequest->paymentProofs()->approved()->count() === 1) {
                    $paymentRequest->markAsProcessing();
                }
            } else {
                $proof->reject($request->rejection_reason, auth()->id());
            }

            DB::commit();

            return back()->with('success', 'تم مراجعة إثبات الدفع بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء مراجعة الإثبات.');
        }
    }

    /**
     * إدارة الحسابات البنكية
     */
    public function bankAccounts()
    {
        $bankAccounts = BankAccount::ordered()->get();

        return view('admin.payments.bank-accounts', compact('bankAccounts'));
    }

    /**
     * إدارة المحافظ الإلكترونية
     */
    public function electronicWallets()
    {
        $electronicWallets = ElectronicWallet::ordered()->get();

        return view('admin.payments.electronic-wallets', compact('electronicWallets'));
    }

    /**
     * حفظ الحساب البنكي
     */
    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'branch_code' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        BankAccount::create($request->all());

        return back()->with('success', 'تم إضافة الحساب البنكي بنجاح.');
    }

    /**
     * حفظ المحفظة الإلكترونية
     */
    public function storeElectronicWallet(Request $request)
    {
        $request->validate([
            'wallet_name' => 'required|string|max:255',
            'wallet_type' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ElectronicWallet::create($request->all());

        return back()->with('success', 'تم إضافة المحفظة الإلكترونية بنجاح.');
    }

    /**
     * تحديث الحساب البنكي
     */
    public function updateBankAccount(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'branch_code' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount->update($request->all());

        return back()->with('success', 'تم تحديث الحساب البنكي بنجاح.');
    }

    /**
     * تحديث المحفظة الإلكترونية
     */
    public function updateElectronicWallet(Request $request, $id)
    {
        $request->validate([
            'wallet_name' => 'required|string|max:255',
            'wallet_type' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $electronicWallet = ElectronicWallet::findOrFail($id);
        $electronicWallet->update($request->all());

        return back()->with('success', 'تم تحديث المحفظة الإلكترونية بنجاح.');
    }

    /**
     * جلب بيانات حساب بنكي للتعديل
     */
    public function getBankAccount($id)
    {
        try {
            $bankAccount = BankAccount::findOrFail($id);
            return response()->json($bankAccount);
        } catch (\Exception $e) {
            return response()->json(['error' => 'الحساب غير موجود'], 404);
        }
    }

    /**
     * حذف الحساب البنكي
     */
    public function destroyBankAccount($id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        // التحقق من عدم وجود طلبات دفع مرتبطة
        if ($bankAccount->paymentRequests()->exists()) {
            return back()->with('error', 'لا يمكن حذف الحساب البنكي لوجود طلبات دفع مرتبطة به.');
        }

        $bankAccount->delete();

        return back()->with('success', 'تم حذف الحساب البنكي بنجاح.');
    }

    /**
     * جلب بيانات محفظة إلكترونية للتعديل
     */
    public function getElectronicWallet($id)
    {
        try {
            $electronicWallet = ElectronicWallet::findOrFail($id);
            return response()->json($electronicWallet);
        } catch (\Exception $e) {
            return response()->json(['error' => 'المحفظة غير موجودة'], 404);
        }
    }

    /**
     * حذف المحفظة الإلكترونية
     */
    public function destroyElectronicWallet($id)
    {
        $electronicWallet = ElectronicWallet::findOrFail($id);

        // التحقق من عدم وجود طلبات دفع مرتبطة
        if ($electronicWallet->paymentRequests()->exists()) {
            return back()->with('error', 'لا يمكن حذف المحفظة الإلكترونية لوجود طلبات دفع مرتبطة بها.');
        }

        $electronicWallet->delete();

        return back()->with('success', 'تم حذف المحفظة الإلكترونية بنجاح.');
    }

    /**
     * معالجة الدفع المكتمل
     */
    private function processCompletedPayment($paymentRequest)
    {
        switch ($paymentRequest->payment_type) {
            case 'product_order':
                // تحديث حالة طلب المنتج
                $order = \App\Models\ProductOrder::find($paymentRequest->related_id);
                if ($order) {
                    $order->update(['status' => 'confirmed']);
                }
                break;

            case 'invoice':
                // تحديث حالة الفاتورة
                $invoice = \App\Models\Invoice::find($paymentRequest->related_id);
                if ($invoice) {
                    $invoice->update([
                        'payment_status' => 'paid',
                        'paid_amount' => $invoice->paid_amount + $paymentRequest->amount,
                        'remaining_amount' => max(0, $invoice->remaining_amount - $paymentRequest->amount),
                    ]);
                }
                break;

            case 'funding_request':
                // تحديث حالة طلب التمويل
                $fundingRequest = \App\Models\FundingRequest::find($paymentRequest->related_id);
                if ($fundingRequest) {
                    $fundingRequest->update(['status' => 'disbursed']);
                }
                break;
        }
    }
}
