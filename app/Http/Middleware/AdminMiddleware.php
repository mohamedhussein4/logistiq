<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول للوصول لهذه الصفحة');
        }

        $user = Auth::user();

        // التحقق من أن المستخدم أدمن
        if ($user->user_type !== User::TYPE_ADMIN) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        // التحقق من أن الحساب نشط
        if ($user->status !== User::STATUS_ACTIVE) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'حسابك غير نشط، يرجى التواصل مع الإدارة');
        }

        return $next($request);
    }
}
