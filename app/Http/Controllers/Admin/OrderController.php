<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض جميع الطلبات
     */
    public function index(Request $request)
    {
        $query = ProductOrder::with(['user', 'product']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب المنتج
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('ordered_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('ordered_at', '<=', $request->date_to);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhere('delivery_address', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest('ordered_at')->paginate(20);

        // إحصائيات
        $stats = [
            'total_orders' => ProductOrder::count(),
            'pending_orders' => ProductOrder::where('status', 'pending')->count(),
            'confirmed_orders' => ProductOrder::where('status', 'confirmed')->count(),
            'processing_orders' => ProductOrder::where('status', 'processing')->count(),
            'shipped_orders' => ProductOrder::where('status', 'shipped')->count(),
            'delivered_orders' => ProductOrder::where('status', 'delivered')->count(),
            'cancelled_orders' => ProductOrder::where('status', 'cancelled')->count(),
            'total_revenue' => ProductOrder::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->sum('total_amount'),
            'avg_order_value' => ProductOrder::avg('total_amount'),
        ];

        // بيانات للفلاتر
        $products = Product::all();
        $users = User::where('user_type', '!=', 'admin')->get();

        return view('admin.orders.index', compact('orders', 'stats', 'products', 'users'));
    }

    /**
     * عرض تفاصيل الطلب
     */
    public function show(ProductOrder $order)
    {
        $order->load([
            'user.profile',
            'product.category',
            'paymentRequests.paymentProofs',
            'paymentRequests.bankAccount'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, ProductOrder $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::transaction(function() use ($order, $request, $oldStatus, $newStatus) {
            // تحديث الطلب
            $order->update([
                'status' => $newStatus,
                'admin_notes' => $request->notes,
                'confirmed_at' => $newStatus === 'confirmed' && $oldStatus !== 'confirmed' ? now() : $order->confirmed_at,
                'shipped_at' => $newStatus === 'shipped' && $oldStatus !== 'shipped' ? now() : $order->shipped_at,
                'delivered_at' => $newStatus === 'delivered' && $oldStatus !== 'delivered' ? now() : $order->delivered_at,
                'cancelled_at' => $newStatus === 'cancelled' && $oldStatus !== 'cancelled' ? now() : $order->cancelled_at,
            ]);

            // إدارة المخزون
            if ($oldStatus === 'pending' && $newStatus === 'confirmed') {
                // خصم من المخزون عند التأكيد
                $product = $order->product;
                if ($product->stock_quantity >= $order->quantity) {
                    $product->decrement('stock_quantity', $order->quantity);

                    // تحديث حالة المنتج إذا نفد المخزون
                    if ($product->stock_quantity <= 0) {
                        $product->update(['status' => 'out_of_stock']);
                    }
                } else {
                    throw new \Exception('المخزون غير كافي لتأكيد الطلب');
                }
            } elseif ($oldStatus === 'confirmed' && $newStatus === 'cancelled') {
                // إرجاع المخزون عند الإلغاء
                $product = $order->product;
                $product->increment('stock_quantity', $order->quantity);

                // تحديث حالة المنتج إذا كان متوقف بسبب نفاد المخزون
                if ($product->status === 'out_of_stock') {
                    $product->update(['status' => 'active']);
                }
            }
        });

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    /**
     * شحن الطلب
     */
    public function ship(Request $request, ProductOrder $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'shipping_company' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($order->status !== 'processing') {
            return redirect()->back()->with('error', 'لا يمكن شحن الطلب في حالته الحالية');
        }

        $order->update([
            'status' => 'shipped',
            'shipped_at' => now(),
            'tracking_number' => $request->tracking_number,
            'shipping_company' => $request->shipping_company,
            'admin_notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'تم شحن الطلب بنجاح');
    }

    /**
     * تأكيد التسليم
     */
    public function deliver(Request $request, ProductOrder $order)
    {
        $request->validate([
            'delivery_notes' => 'nullable|string|max:500'
        ]);

        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'لا يمكن تأكيد التسليم لطلب غير مشحون');
        }

        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'delivery_notes' => $request->delivery_notes,
        ]);

        return redirect()->back()->with('success', 'تم تأكيد تسليم الطلب بنجاح');
    }

    /**
     * إلغاء الطلب
     */
    public function cancel(Request $request, ProductOrder $order)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'لا يمكن إلغاء الطلب في حالته الحالية');
        }

        DB::transaction(function() use ($order, $request) {
            // إرجاع المخزون إذا كان الطلب مؤكد
            if ($order->status === 'confirmed' || $order->status === 'processing' || $order->status === 'shipped') {
                $product = $order->product;
                $product->increment('stock_quantity', $order->quantity);

                // تحديث حالة المنتج إذا كان متوقف بسبب نفاد المخزون
                if ($product->status === 'out_of_stock') {
                    $product->update(['status' => 'active']);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);
        });

        return redirect()->back()->with('success', 'تم إلغاء الطلب بنجاح');
    }

    /**
     * إحصائيات متقدمة
     */
    public function analytics()
    {
        $stats = [
            // إحصائيات عامة
            'total_orders' => ProductOrder::count(),
            'total_revenue' => ProductOrder::whereIn('status', ['delivered'])->sum('total_amount'),
            'avg_order_value' => ProductOrder::avg('total_amount'),

            // إحصائيات حسب الحالة
            'by_status' => ProductOrder::selectRaw('status, count(*) as count, sum(total_amount) as total_amount')
                ->groupBy('status')
                ->get()
                ->keyBy('status'),

            // إحصائيات شهرية
            'monthly_orders' => ProductOrder::selectRaw('MONTH(ordered_at) as month, count(*) as count, sum(total_amount) as revenue')
                ->whereYear('ordered_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get(),

            // أكبر الطلبات
            'largest_orders' => ProductOrder::with(['user', 'product'])
                ->orderBy('total_amount', 'desc')
                ->take(10)
                ->get(),

            // أكثر المنتجات مبيعاً
            'top_products' => Product::withSum('orders', 'quantity')
                ->withSum('orders', 'total_amount')
                ->orderBy('orders_sum_quantity', 'desc')
                ->take(10)
                ->get(),

            // أكثر العملاء شراءً
            'top_customers' => User::withCount('productOrders')
                ->withSum('productOrders', 'total_amount')
                ->where('user_type', '!=', 'admin')
                ->orderBy('product_orders_sum_total_amount', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.orders.analytics', compact('stats'));
    }

    /**
     * تصدير الطلبات
     */
    public function export(Request $request)
    {
        $query = ProductOrder::with(['user', 'product']);

        // تطبيق الفلاتر
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('ordered_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('ordered_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="orders_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'رقم الطلب',
                'اسم العميل',
                'البريد الإلكتروني',
                'المنتج',
                'الكمية',
                'سعر الوحدة',
                'المبلغ الإجمالي',
                'الحالة',
                'تاريخ الطلب',
                'تاريخ التأكيد',
                'تاريخ الشحن',
                'تاريخ التسليم',
                'عنوان التسليم'
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name,
                    $order->user->email,
                    $order->product->name,
                    $order->quantity,
                    number_format($order->unit_price, 2),
                    number_format($order->total_amount, 2),
                    $order->getStatusNameAttribute(),
                    $order->ordered_at->format('Y-m-d H:i'),
                    $order->confirmed_at?->format('Y-m-d H:i'),
                    $order->shipped_at?->format('Y-m-d H:i'),
                    $order->delivered_at?->format('Y-m-d H:i'),
                    $order->delivery_address
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * حذف الطلبات المحددة
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|json'
        ]);

        $ids = json_decode($request->ids, true);

        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'لم يتم تحديد أي طلبات للحذف');
        }

        try {
            DB::beginTransaction();

            // التأكد من وجود الطلبات
            $orders = ProductOrder::whereIn('id', $ids)->get();

            if ($orders->count() !== count($ids)) {
                throw new \Exception('بعض الطلبات المحددة غير موجودة');
            }

            // حذف الطلبات
            ProductOrder::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()->back()->with('success', "تم حذف {$orders->count()} طلب بنجاح");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الطلبات: ' . $e->getMessage());
        }
    }

    /**
     * طباعة تفاصيل الطلب
     */
    public function print(ProductOrder $order)
    {
        $order->load(['user', 'product']);
        
        return view('admin.orders.print', compact('order'));
    }
}
