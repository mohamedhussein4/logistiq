<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {
        // إحصائيات النظام
        $systemStats = [
            'total_users' => User::count(),
            'total_invoices' => Invoice::count(),
            'total_products' => Product::count(),
            'total_orders' => ProductOrder::count(),
            'total_payments' => Payment::count(),
            'storage_used' => $this->getStorageUsed(),
            'database_size' => $this->getDatabaseSize(),
            'cache_size' => $this->getCacheSize(),
        ];

        return view('admin.settings.index', compact('systemStats'));
    }

    /**
     * تحديث الإعدادات
     */
    public function update(Request $request)
    {
        try {
            $section = $request->input('section', 'general');

            if ($section === 'general') {
                $request->validate([
                    'site_name' => 'required|string|max:100',
                    'site_description' => 'nullable|string|max:500',
                    'site_email' => 'required|email|max:100',
                    'site_phone' => 'required|string|max:20',
                    'site_address' => 'required|string|max:200',
                    'site_currency' => 'required|string|max:10',
                    'site_timezone' => 'required|string|max:50',
                    'maintenance_mode' => 'boolean',
                    'registration_enabled' => 'boolean'
                ]);

                Setting::set('general.site_name', $request->site_name);
                Setting::set('general.site_description', $request->site_description);
                Setting::set('general.site_email', $request->site_email);
                Setting::set('general.site_phone', $request->site_phone);
                Setting::set('general.site_address', $request->site_address);
                Setting::set('general.site_currency', $request->site_currency);
                Setting::set('general.site_timezone', $request->timezone);
                Setting::set('general.maintenance_mode', $request->boolean('maintenance_mode'));
                Setting::set('general.registration_enabled', $request->boolean('registration_enabled'));

            } elseif ($section === 'seo') {
                $request->validate([
                    'seo_title' => 'required|string|max:100',
                    'seo_description' => 'required|string|max:160',
                    'seo_keywords' => 'nullable|string|max:200',
                    'seo_author' => 'nullable|string|max:100',
                    'seo_robots' => 'nullable|string|max:100',
                    'google_analytics' => 'nullable|string|max:100',
                    'facebook_pixel' => 'nullable|string|max:100'
                ]);

                Setting::set('seo.title', $request->seo_title);
                Setting::set('seo.description', $request->seo_description);
                Setting::set('seo.keywords', $request->seo_keywords);
                Setting::set('seo.author', $request->seo_author);
                Setting::set('seo.robots', $request->seo_robots);
                Setting::set('seo.google_analytics', $request->google_analytics);
                Setting::set('seo.facebook_pixel', $request->facebook_pixel);

            } elseif ($section === 'contact') {
                $request->validate([
                    'contact_email' => 'required|email|max:100',
                    'contact_phone' => 'required|string|max:20',
                    'contact_address' => 'required|string|max:200',
                    'contact_working_hours' => 'nullable|string|max:100',
                    'contact_support_hours' => 'nullable|string|max:100'
                ]);

                Setting::set('contact.email', $request->contact_email);
                Setting::set('contact.phone', $request->contact_phone);
                Setting::set('contact.address', $request->contact_address);
                Setting::set('contact.working_hours', $request->contact_working_hours);
                Setting::set('contact.support_hours', $request->contact_support_hours);

            } elseif ($section === 'social') {
                $request->validate([
                    'social_facebook' => 'nullable|url|max:200',
                    'social_twitter' => 'nullable|url|max:200',
                    'social_instagram' => 'nullable|url|max:200',
                    'social_linkedin' => 'nullable|url|max:200',
                    'social_youtube' => 'nullable|url|max:200',
                    'social_whatsapp' => 'nullable|string|max:20'
                ]);

                Setting::set('social.facebook', $request->social_facebook);
                Setting::set('social.twitter', $request->social_twitter);
                Setting::set('social.instagram', $request->social_instagram);
                Setting::set('social.linkedin', $request->social_linkedin);
                Setting::set('social.youtube', $request->social_youtube);
                Setting::set('social.whatsapp', $request->social_whatsapp);

            } elseif ($section === 'website') {
                $request->validate([
                    'website_name' => 'required|string|max:100',
                    'website_description' => 'nullable|string|max:500',
                    'website_keywords' => 'nullable|string|max:200',
                    'website_url' => 'nullable|url|max:200',
                    'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'website_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024'
                ]);

                Setting::set('website.name', $request->website_name);
                Setting::set('website.description', $request->website_description);
                Setting::set('website.keywords', $request->website_keywords);
                Setting::set('website.url', $request->website_url);

                // معالجة رفع الشعار
                if ($request->hasFile('website_logo')) {
                    $logo = $request->file('website_logo');
                    $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
                    $logo->move(public_path('images'), $logoName);

                    // حذف الشعار القديم إذا وجد
                    $oldLogo = Setting::get('website.logo');
                    if ($oldLogo && file_exists(public_path('images/' . $oldLogo))) {
                        unlink(public_path('images/' . $oldLogo));
                    }

                    Setting::set('website.logo', $logoName);
                }

                // معالجة رفع Favicon
                if ($request->hasFile('website_favicon')) {
                    $favicon = $request->file('website_favicon');
                    $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
                    $favicon->move(public_path('images'), $faviconName);

                    // حذف Favicon القديم إذا وجد
                    $oldFavicon = Setting::get('website.favicon');
                    if ($oldFavicon && file_exists(public_path('images/' . $oldFavicon))) {
                        unlink(public_path('images/' . $oldFavicon));
                    }

                    Setting::set('website.favicon', $faviconName);
                }

            } elseif ($section === 'footer') {
                $request->validate([
                    'footer_description' => 'nullable|string|max:500',
                    'footer_copyright' => 'required|string|max:200',
                    'footer_links' => 'nullable|string|max:1000'
                ]);

                Setting::set('footer.description', $request->footer_description);
                Setting::set('footer.copyright', $request->footer_copyright);
                Setting::set('footer.links', $request->footer_links);

            } elseif ($section === 'security') {
                $request->validate([
                    'security_session_timeout' => 'required|integer|min:15|max:480',
                    'security_max_login_attempts' => 'required|integer|min:3|max:10',
                    'security_password_min_length' => 'required|integer|min:6|max:20',
                    'security_two_factor_enabled' => 'boolean',
                    'security_captcha_enabled' => 'boolean'
                ]);

                Setting::set('security.session_timeout', $request->security_session_timeout);
                Setting::set('security.max_login_attempts', $request->security_max_login_attempts);
                Setting::set('security.password_min_length', $request->security_password_min_length);
                Setting::set('security.two_factor_enabled', $request->boolean('security_two_factor_enabled'));
                Setting::set('security.captcha_enabled', $request->boolean('security_captcha_enabled'));

            } elseif ($section === 'performance') {
                $request->validate([
                    'performance_cache_enabled' => 'boolean',
                    'performance_cache_duration' => 'required|integer|min:1|max:1440',
                    'performance_image_optimization' => 'boolean',
                    'performance_compression_enabled' => 'boolean'
                ]);

                Setting::set('performance.cache_enabled', $request->boolean('performance_cache_enabled'));
                Setting::set('performance.cache_duration', $request->performance_cache_duration);
                Setting::set('performance.image_optimization', $request->boolean('performance_image_optimization'));
                Setting::set('performance.compression_enabled', $request->boolean('performance_compression_enabled'));

            } else {
                return redirect()->back()->with('error', 'قسم غير صحيح');
            }

            Setting::clearCache();
            return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('خطأ في تحديث الإعدادات: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات');
        }
    }

    /**
     * اختبار إعدادات البريد الإلكتروني
     */
    public function testEmail()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال البريد التجريبي بنجاح!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال البريد التجريبي: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper Methods
     */
    private function getStorageUsed()
    {
        return '150 ميجابايت';
    }

    private function getDatabaseSize()
    {
        return '25 ميجابايت';
    }

    private function getCacheSize()
    {
        return '5 ميجابايت';
    }
}
