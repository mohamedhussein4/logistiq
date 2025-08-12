<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\PaymentRequest;
use App\Models\PaymentProof;
use App\Models\BankAccount;
use App\Models\ElectronicWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض صفحة الشراء المباشر للمنتج
     */
    public function showDirectPurchase($productId)
    {
        $product = Product::findOrFail($productId);
        $bankAccounts = BankAccount::active()->ordered()->get();
        $electronicWallets = ElectronicWallet::active()->ordered()->get();

        return view('user.purchase.direct-payment', compact('product', 'bankAccounts', 'electronicWallets'));
    }

    /**
     * معالجة الشراء المباشر وإنشاء الطلب
     */
    public function processDirectPurchase(Request $request, $productId)
    {
        // تسجيل البيانات الواردة للتصحيح
        \Log::info('Purchase request data:', [
            'productId' => $productId,
            'data' => $request->all(),
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:bank_transfer,electronic_wallet',
            'payment_account_id' => 'required|integer',
            'payment_notes' => 'nullable|string|max:1000',
        ]);


        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            $quantity = $request->quantity;
            $totalAmount = $product->price * $quantity;


            // التحقق من توفر المخزون
            if ($product->stock_quantity < $quantity) {
                return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
            }

            // إنشاء الطلب
            $order = ProductOrder::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'total_amount' => $totalAmount,
                'delivery_address' => 'سيتم التواصل لتحديد عنوان التسليم',
                'status' => 'pending',
                'notes' => $request->payment_notes ?: 'طلب شراء مباشر',
            ]);

            // إنشاء طلب الدفع
            $paymentRequest = PaymentRequest::create([
                'user_id' => auth()->id(),
                'request_number' => 'PAY-' . date('Ymd') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'payment_type' => 'product_order',
                'related_id' => $order->id,
                'related_type' => ProductOrder::class,
                'amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_account_id' => $request->payment_account_id,
                'payment_account_type' => $request->payment_method === 'bank_transfer' ? 'bank_account' : 'electronic_wallet',
                'payment_notes' => $request->payment_notes,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('user.purchase.success', $order->id)->with('success', 'تم إنشاء الطلب بنجاح. يرجى رفع إثبات التحويل لإتمام العملية.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Purchase error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'product_id' => $productId
            ]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * صفحة نجاح الشراء مع رفع إثبات التحويل
     */
    public function purchaseSuccess($orderId)
    {
        $order = ProductOrder::with(['product', 'paymentRequests'])->findOrFail($orderId);

        // التحقق من أن الطلب يخص المستخدم الحالي
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $paymentRequest = $order->paymentRequests->first();

        // جلب بيانات وسيلة الدفع
        $paymentAccount = null;
        if ($paymentRequest->payment_account_type === 'bank_account') {
            $paymentAccount = BankAccount::find($paymentRequest->payment_account_id);
        } else {
            $paymentAccount = ElectronicWallet::find($paymentRequest->payment_account_id);
        }

        return view('user.purchase.success', compact('order', 'paymentRequest', 'paymentAccount'));
    }

    /**
     * رفع إثبات التحويل للشراء المباشر
     */
    public function uploadPurchaseProof(Request $request)
    {
        $request->validate([
            'payment_request_id' => 'required|exists:payment_requests,id',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $paymentRequest = PaymentRequest::findOrFail($request->payment_request_id);

            // التحقق من أن طلب الدفع يخص المستخدم الحالي
            if ($paymentRequest->user_id !== auth()->id()) {
                return response()->json(['error' => 'غير مصرح لك بهذا الإجراء'], 403);
            }

            // رفع الملف
            $file = $request->file('proof_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('payment-proofs', $fileName, 'public');

            // إنشاء سجل إثبات الدفع
            $proof = PaymentProof::create([
                'payment_request_id' => $paymentRequest->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'description' => $request->description,
                'status' => 'pending',
            ]);

            // تحديث حالة طلب الدفع
            $paymentRequest->update(['status' => 'processing']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم رفع إثبات التحويل بنجاح. سيتم مراجعته من قبل الإدارة.',
                'proof' => $proof
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage()], 500);
        }
    }

    /**
     * عرض طلبات الشراء للمستخدم
     */
    public function myOrders()
    {
        $orders = ProductOrder::with(['product', 'paymentRequests.paymentProofs'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.purchase.my-orders', compact('orders'));
    }

    /**
     * عرض تفاصيل طلب محدد
     */
    public function showOrder($orderId)
    {
        $order = ProductOrder::with(['product', 'paymentRequests.paymentProofs'])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        return view('user.purchase.order-details', compact('order'));
    }
}
