<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * إرسال طلب تواصل جديد
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'service_type' => 'required|string|in:financing_link,client_link,tracking,consultation,partnership',
            'message' => 'nullable|string|max:2000',
        ], [
            'company_name.required' => 'اسم الشركة مطلوب',
            'company_name.max' => 'اسم الشركة لا يجب أن يتجاوز 255 حرف',
            'contact_name.required' => 'اسم المسؤول مطلوب',
            'contact_name.max' => 'اسم المسؤول لا يجب أن يتجاوز 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 رقم',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف',
            'service_type.required' => 'نوع الخدمة مطلوب',
            'service_type.in' => 'نوع الخدمة المحدد غير صحيح',
            'message.max' => 'الرسالة لا يجب أن تتجاوز 2000 حرف',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'يرجى تصحيح الأخطاء في النموذج');
        }

        try {
            // حفظ طلب التواصل في قاعدة البيانات
            ContactRequest::create([
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'service_type' => $request->service_type,
                'message' => $request->message,
                'status' => 'new',
            ]);

            // إرسال رسالة نجاح مع العودة للصفحة
            return redirect()->back()
                ->with('success', 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً خلال 24 ساعة.')
                ->with('contact_submitted', true);

        } catch (\Exception $e) {
            // تسجيل الخطأ في السجلات للتشخيص
            Log::error('Contact form submission error: ' . $e->getMessage(), [
                'request_data' => $request->except(['_token']),
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى أو التواصل معنا هاتفياً. (كود الخطأ: ' . substr(md5($e->getMessage()), 0, 8) . ')');
        }
    }

}
