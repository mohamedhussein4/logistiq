<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RegularUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول للوصول إلى هذه الصفحة');
        }

        $user = Auth::user();

        // التحقق من نوع المستخدم - يجب أن يكون regular
        if ($user->user_type !== 'regular') {
            // إعادة توجيه حسب نوع المستخدم
            return $this->redirectBasedOnUserType($user);
        }

        // التحقق من حالة المستخدم
        if ($user->status !== 'active') {
            return redirect()->route('home')->with('warning', 'حسابك تحت المراجعة. سيتم تفعيله قريباً');
        }

        return $next($request);
    }

    /**
     * إعادة توجيه المستخدم حسب نوعه
     */
    private function redirectBasedOnUserType($user)
    {
        switch ($user->user_type) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('info', 'تم توجيهك إلى لوحة التحكم');

            case 'logistics':
                return redirect()->route('logistics.dashboard')->with('info', 'تم توجيهك إلى صفحة الشركة اللوجستية');

            case 'service_company':
                return redirect()->route('service_company.dashboard')->with('info', 'تم توجيهك إلى صفحة الشركة الطالبة للخدمة');

            default:
                return redirect()->route('home')->with('error', 'نوع المستخدم غير معروف');
        }
    }
}
