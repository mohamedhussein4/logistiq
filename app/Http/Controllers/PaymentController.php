<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\PaymentProof;
use App\Models\BankAccount;
use App\Models\ElectronicWallet;
use App\Models\ProductOrder;
use App\Models\Invoice;
use App\Models\FundingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض صفحة الدفع للمنتجات
     */
    public function showProductPayment($orderId)
    {
        $order = ProductOrder::with('product')->findOrFail($orderId);

        // التحقق من أن المستخدم يملك الطلب
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $bankAccounts = BankAccount::active()->ordered()->get();
        $electronicWallets = ElectronicWallet::active()->ordered()->get();

        return view('payments.product-payment', compact('order', 'bankAccounts', 'electronicWallets'));
    }

    /**
     * عرض صفحة الدفع للفواتير
     */
    public function showInvoicePayment($invoiceId)
    {
        $invoice = Invoice::with(['serviceCompany', 'logisticsCompany'])->findOrFail($invoiceId);

        // التحقق من الصلاحيات
        $user = auth()->user();
        $canPay = false;

        if ($user->isLogisticsCompany() && $invoice->logistics_company_id === $user->logisticsCompany->id) {
            $canPay = true;
        } elseif ($user->isServiceCompany() && $invoice->service_company_id === $user->serviceCompany->id) {
            $canPay = true;
        }

        if (!$canPay) {
            abort(403);
        }

        $bankAccounts = BankAccount::active()->ordered()->get();
        $electronicWallets = ElectronicWallet::active()->ordered()->get();

        return view('payments.invoice-payment', compact('invoice', 'bankAccounts', 'electronicWallets'));
    }

    /**
     * إنشاء طلب دفع جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:product_order,invoice,funding_request,other',
            'related_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,electronic_wallet,cash,check',
            'payment_account_id' => 'nullable|integer',
            'payment_account_type' => 'nullable|in:bank_account,electronic_wallet',
            'payment_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء طلب الدفع
            $paymentRequest = PaymentRequest::create([
                'user_id' => auth()->id(),
                'request_number' => 'PAY-' . time() . '-' . auth()->id(),
                'payment_type' => $request->payment_type,
                'related_id' => $request->related_id,
                'related_type' => $this->getRelatedType($request->payment_type),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_account_id' => $request->payment_account_id,
                'payment_account_type' => $request->payment_account_type,
                'payment_notes' => $request->payment_notes,
                'status' => PaymentRequest::STATUS_PENDING,
            ]);

            DB::commit();

            return redirect()->route('payments.show', $paymentRequest->id)
                           ->with('success', 'تم إنشاء طلب الدفع بنجاح. يرجى رفع إثبات الدفع.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إنشاء طلب الدفع. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * عرض تفاصيل طلب الدفع
     */
    public function show($id)
    {
        $paymentRequest = PaymentRequest::with(['user', 'paymentProofs', 'bankAccount', 'electronicWallet'])
                                       ->findOrFail($id);

        // التحقق من الصلاحيات
        if ($paymentRequest->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('payments.show', compact('paymentRequest'));
    }

    /**
     * رفع إثبات الدفع
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'proof_file' => 'required|file|mimes:jpeg,png,gif,pdf,doc,docx|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        $paymentRequest = PaymentRequest::findOrFail($id);

        // التحقق من الصلاحيات
        if ($paymentRequest->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $file = $request->file('proof_file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('payment_proofs', $fileName, 'public');

            PaymentProof::create([
                'payment_request_id' => $paymentRequest->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->description,
                'status' => PaymentProof::STATUS_PENDING,
            ]);

            // تحديث حالة طلب الدفع إلى قيد المعالجة
            $paymentRequest->markAsProcessing();

            DB::commit();

            return redirect()->route('payments.show', $paymentRequest->id)
                           ->with('success', 'تم رفع إثبات الدفع بنجاح. سيتم مراجعته من قبل الإدارة.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * قائمة طلبات الدفع للمستخدم
     */
    public function index()
    {
        $paymentRequests = PaymentRequest::with(['paymentProofs'])
                                        ->where('user_id', auth()->id())
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(15);

        return view('payments.index', compact('paymentRequests'));
    }

    /**
     * إلغاء طلب الدفع
     */
    public function cancel($id)
    {
        $paymentRequest = PaymentRequest::findOrFail($id);

        // التحقق من الصلاحيات
        if ($paymentRequest->user_id !== auth()->id()) {
            abort(403);
        }

        // يمكن الإلغاء فقط إذا كان معلق
        if (!$paymentRequest->isPending()) {
            return back()->with('error', 'لا يمكن إلغاء طلب الدفع في هذه الحالة.');
        }

        $paymentRequest->update(['status' => PaymentRequest::STATUS_CANCELLED]);

        return redirect()->route('payments.index')
                       ->with('success', 'تم إلغاء طلب الدفع بنجاح.');
    }

    /**
     * الحصول على نوع العنصر المرتبط
     */
    private function getRelatedType($paymentType)
    {
        $types = [
            'product_order' => ProductOrder::class,
            'invoice' => Invoice::class,
            'funding_request' => FundingRequest::class,
            'other' => null,
        ];

        return $types[$paymentType] ?? null;
    }

    /**
     * الحصول على معلومات الحسابات البنكية والمحافظ
     */
    public function getPaymentAccounts()
    {
        $bankAccounts = BankAccount::active()->ordered()->get();
        $electronicWallets = ElectronicWallet::active()->ordered()->get();

        return response()->json([
            'bank_accounts' => $bankAccounts,
            'electronic_wallets' => $electronicWallets,
        ]);
    }


}
