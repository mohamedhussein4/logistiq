<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * عرض متجر أجهزة التتبع للجمهور (بدون تسجيل دخول)
     */
    public function publicStore()
    {
        // جلب المنتجات والفئات
        $products = Product::with('category')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = ProductCategory::where('status', 'active')->get();

        return view('store.index', compact('products', 'categories'));
    }

    /**
     * عرض لوحة تحكم المستخدم
     */
    public function dashboard()
    {
        $user = Auth::user();

        // التحقق من نوع المستخدم
        if ($user->user_type !== 'regular') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // الإحصائيات الرئيسية
        $stats = [
            'total_orders' => ProductOrder::where('user_id', $user->id)->count(),
            'pending_orders' => ProductOrder::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'completed_orders' => ProductOrder::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'total_spent' => ProductOrder::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'delivered'])->sum('original_amount'),
        ];

        // آخر الطلبات مع المنتجات
        $recentOrders = ProductOrder::where('user_id', $user->id)
            ->with(['product'])
            ->latest()
            ->take(10)
            ->get();

        // السلة (من session مؤقتاً)
        $cartItems = session()->get('cart', []);
        $cartCount = array_sum(array_column($cartItems, 'quantity'));
        $cartTotal = 0;

        // تحويل cart items لتتضمن product objects
        $formattedCartItems = [];
        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $formattedCartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity']
                ];
                $cartTotal += $product->price * $item['quantity'];
            }
        }

        return view('user.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'cartItems' => $formattedCartItems,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal
        ]);
    }

    /**
     * عرض صفحة الملف الشخصي للمستخدم العادي
     */
    public function profile()
    {
        $user = Auth::user();

        // التحقق من نوع المستخدم
        if ($user->user_type !== 'regular') {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // الإحصائيات الرئيسية
        $stats = [
            'total_orders' => ProductOrder::where('user_id', $user->id)->count(),
            'pending_orders' => ProductOrder::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'completed_orders' => ProductOrder::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'total_spent' => ProductOrder::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'delivered'])->sum('original_amount'),
        ];

        // جميع الطلبات مع المنتجات
        $orders = ProductOrder::where('user_id', $user->id)
            ->with(['product'])
            ->latest()
            ->get();

        // السلة (من session مؤقتاً)
        $cartItems = session()->get('cart', []);
        $cartCount = array_sum(array_column($cartItems, 'quantity'));
        $cartTotal = 0;

        // تحويل cart items لتتضمن product objects
        $formattedCartItems = [];
        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $formattedCartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity']
                ];
                $cartTotal += $product->price * $item['quantity'];
            }
        }

        return view('user.profile', [
            'user' => $user,
            'stats' => $stats,
            'orders' => $orders,
            'cartItems' => $formattedCartItems,
            'cartCount' => $cartCount,
            'cartTotal' => $cartTotal
        ]);
    }
}
