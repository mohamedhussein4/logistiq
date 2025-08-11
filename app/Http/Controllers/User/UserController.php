<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class UserController extends Controller
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

        return view('store', compact('products', 'categories'));
    }
}
