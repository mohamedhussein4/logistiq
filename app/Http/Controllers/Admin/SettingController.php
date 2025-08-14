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

class SettingController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {
        return view('admin.settings.index');
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
                    'site_phone' => 'required|string|max:20',
                    'site_email' => 'required|email|max:100',
                    'site_address' => 'required|string|max:500',
                    'footer_copyright' => 'nullable|string|max:200',
                    'footer_description' => 'nullable|string|max:500',
                ]);

                Setting::set('site_name', $request->site_name);
                Setting::set('site_phone', $request->site_phone);
                Setting::set('site_email', $request->site_email);
                Setting::set('site_address', $request->site_address);
                Setting::set('footer_copyright', $request->footer_copyright);
                Setting::set('footer_description', $request->footer_description);

                // معالجة رفع الشعار
                if ($request->hasFile('site_logo')) {
                    $logo = $request->file('site_logo');
                    $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
                    $logo->move(public_path('images'), $logoName);

                    // حذف الشعار القديم إذا وجد
                    $oldLogo = Setting::get('site_logo');
                    if ($oldLogo && file_exists(public_path('images/' . $oldLogo))) {
                        unlink(public_path('images/' . $oldLogo));
                    }

                    Setting::set('site_logo', $logoName);
                }

                // معالجة رفع Favicon
                if ($request->hasFile('site_favicon')) {
                    $favicon = $request->file('site_favicon');
                    $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
                    $favicon->move(public_path('images'), $faviconName);

                    // حذف Favicon القديم إذا وجد
                    $oldFavicon = Setting::get('site_favicon');
                    if ($oldFavicon && file_exists(public_path('images/' . $oldFavicon))) {
                        unlink(public_path('images/' . $oldFavicon));
                    }

                    Setting::set('site_favicon', $faviconName);
                }

            } elseif ($section === 'seo') {
                $request->validate([
                    'seo_title' => 'required|string|max:100',
                    'seo_description' => 'required|string|max:160',
                    'seo_keywords' => 'nullable|string|max:200',
                ]);

                Setting::set('seo_title', $request->seo_title);
                Setting::set('seo_description', $request->seo_description);
                Setting::set('seo_keywords', $request->seo_keywords);

            } elseif ($section === 'social') {
                $request->validate([
                    'social_facebook' => 'nullable|url|max:500',
                    'social_twitter' => 'nullable|url|max:500',
                    'social_instagram' => 'nullable|url|max:500',
                    'social_linkedin' => 'nullable|url|max:500',
                    'social_youtube' => 'nullable|url|max:500',
                    'social_whatsapp' => 'nullable|string|max:500',
                ]);

                Setting::set('social_facebook', $request->social_facebook);
                Setting::set('social_twitter', $request->social_twitter);
                Setting::set('social_instagram', $request->social_instagram);
                Setting::set('social_linkedin', $request->social_linkedin);
                Setting::set('social_youtube', $request->social_youtube);
                Setting::set('social_whatsapp', $request->social_whatsapp);
            }

            return redirect()->route('admin.settings.index')->with('success', 'تم حفظ الإعدادات بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حفظ الإعدادات: ' . $e->getMessage())->withInput();
        }
    }
}
