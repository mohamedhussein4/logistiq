<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // إزالة middleware auth للصفحة الرئيسية العامة
    }

    /**
     * عرض الصفحة الرئيسية للجمهور
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * عرض لوحة التحكم للمستخدمين المسجلين
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $this->middleware('auth');
        return view('home');
    }
}
