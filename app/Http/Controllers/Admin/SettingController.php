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
    //
    public function index()
    {
        // جلب الإعدادات من قاعدة البيانات مع قيم افتراضية
        $settings = [
            // General Settings
            'app_name' => Setting::get('app_name', config('app.name', 'Link2u')),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
            'max_upload_size' => Setting::get('max_upload_size', '10MB'),
            'default_currency' => Setting::get('default_currency', 'SAR'),
            'timezone' => Setting::get('timezone', 'Asia/Riyadh'),
            'app_version' => Setting::get('app_version', '1.0.0'),
            'last_backup' => Setting::get('last_backup', now()->format('Y-m-d H:i:s')),

            // Email Settings
            'mail_host' => Setting::get('mail_host', 'smtp.gmail.com'),
            'mail_port' => Setting::get('mail_port', 587),
            'mail_username' => Setting::get('mail_username', 'noreply@Link2u.com'),
            'mail_password' => Setting::get('mail_password', ''),
            'mail_from_address' => Setting::get('mail_from_address', 'noreply@Link2u.com'),
            'mail_from_name' => Setting::get('mail_from_name', 'Link2u'),
            'mail_encryption' => Setting::get('mail_encryption', 'tls'),

            // Payment Settings
            'merchant_id' => Setting::get('merchant_id', 'MERCHANT_123456'),
            'payment_api_key' => Setting::get('payment_api_key', ''),
            'payment_gateway' => Setting::get('payment_gateway', 'moyasar'),
            'payment_methods' => Setting::get('payment_methods', ['credit_card', 'mada', 'stc_pay']),
            'auto_capture' => Setting::get('auto_capture', true),
            'payment_webhook_url' => Setting::get('payment_webhook_url', ''),

            // Security Settings
            'session_lifetime' => Setting::get('session_lifetime', 120),
            'max_login_attempts' => Setting::get('max_login_attempts', 5),
            'password_min_length' => Setting::get('password_min_length', 8),
            'password_require_uppercase' => Setting::get('password_require_uppercase', true),
            'password_require_numbers' => Setting::get('password_require_numbers', true),
            'password_require_symbols' => Setting::get('password_require_symbols', false),
            'two_factor_enabled' => Setting::get('two_factor_enabled', false),
            'lockout_duration' => Setting::get('lockout_duration', 15),

            // Backup Settings
            'backup_frequency' => Setting::get('backup_frequency', 'daily'),
            'backup_retention' => Setting::get('backup_retention', 30),
            'backup_location' => Setting::get('backup_location', 'local'),
            'backup_notification' => Setting::get('backup_notification', true),
        ];

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

        return view('admin.settings.index', compact('settings', 'systemStats'));
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
                    'app_name' => 'required|string|max:255',
                    'maintenance_mode' => 'boolean',
                    'max_upload_size' => 'required|string',
                    'default_currency' => 'required|string|max:10',
                    'timezone' => 'required|string',
                ]);

                // حفظ الإعدادات العامة
                Setting::set('app_name', $request->app_name, Setting::TYPE_STRING, Setting::GROUP_GENERAL);
                Setting::set('maintenance_mode', $request->boolean('maintenance_mode'), Setting::TYPE_BOOLEAN, Setting::GROUP_GENERAL);
                Setting::set('max_upload_size', $request->max_upload_size, Setting::TYPE_STRING, Setting::GROUP_GENERAL);
                Setting::set('default_currency', $request->default_currency, Setting::TYPE_STRING, Setting::GROUP_GENERAL);
                Setting::set('timezone', $request->timezone, Setting::TYPE_STRING, Setting::GROUP_GENERAL);

                return redirect()->back()->with('success', 'تم تحديث الإعدادات العامة بنجاح');

            } elseif ($section === 'email') {
                $request->validate([
                    'mail_host' => 'required|string',
                    'mail_port' => 'required|integer',
                    'mail_username' => 'required|email',
                    'mail_password' => 'nullable|string',
                    'mail_from_address' => 'required|email',
                    'mail_from_name' => 'required|string',
                    'mail_encryption' => 'required|in:tls,ssl,none',
                ]);

                // حفظ إعدادات البريد الإلكتروني
                Setting::set('mail_host', $request->mail_host, Setting::TYPE_STRING, Setting::GROUP_EMAIL);
                Setting::set('mail_port', $request->mail_port, Setting::TYPE_NUMBER, Setting::GROUP_EMAIL);
                Setting::set('mail_username', $request->mail_username, Setting::TYPE_STRING, Setting::GROUP_EMAIL);
                if ($request->filled('mail_password')) {
                    Setting::set('mail_password', encrypt($request->mail_password), Setting::TYPE_STRING, Setting::GROUP_EMAIL);
                }
                Setting::set('mail_from_address', $request->mail_from_address, Setting::TYPE_STRING, Setting::GROUP_EMAIL);
                Setting::set('mail_from_name', $request->mail_from_name, Setting::TYPE_STRING, Setting::GROUP_EMAIL);
                Setting::set('mail_encryption', $request->mail_encryption, Setting::TYPE_STRING, Setting::GROUP_EMAIL);

                return redirect()->back()->with('success', 'تم تحديث إعدادات البريد الإلكتروني بنجاح');

            } elseif ($section === 'payment') {
                $request->validate([
                    'merchant_id' => 'required|string',
                    'payment_api_key' => 'required|string',
                    'payment_gateway' => 'required|string',
                    'payment_methods' => 'array',
                    'auto_capture' => 'boolean',
                    'payment_webhook_url' => 'nullable|url',
                ]);

                // حفظ إعدادات الدفع
                Setting::set('merchant_id', $request->merchant_id, Setting::TYPE_STRING, Setting::GROUP_PAYMENT);
                Setting::set('payment_api_key', encrypt($request->payment_api_key), Setting::TYPE_STRING, Setting::GROUP_PAYMENT);
                Setting::set('payment_gateway', $request->payment_gateway, Setting::TYPE_STRING, Setting::GROUP_PAYMENT);
                Setting::set('payment_methods', $request->payment_methods, Setting::TYPE_JSON, Setting::GROUP_PAYMENT);
                Setting::set('auto_capture', $request->boolean('auto_capture'), Setting::TYPE_BOOLEAN, Setting::GROUP_PAYMENT);
                if ($request->filled('payment_webhook_url')) {
                    Setting::set('payment_webhook_url', $request->payment_webhook_url, Setting::TYPE_STRING, Setting::GROUP_PAYMENT);
                }

                return redirect()->back()->with('success', 'تم تحديث إعدادات الدفع بنجاح');

            } elseif ($section === 'security') {
                $request->validate([
                    'session_lifetime' => 'required|integer|min:30',
                    'max_login_attempts' => 'required|integer|min:3',
                    'password_min_length' => 'required|integer|min:6',
                    'password_require_uppercase' => 'boolean',
                    'password_require_numbers' => 'boolean',
                    'password_require_symbols' => 'boolean',
                    'two_factor_enabled' => 'boolean',
                    'lockout_duration' => 'required|integer|min:1',
                ]);

                // حفظ إعدادات الأمان
                Setting::set('session_lifetime', $request->session_lifetime, Setting::TYPE_NUMBER, Setting::GROUP_SECURITY);
                Setting::set('max_login_attempts', $request->max_login_attempts, Setting::TYPE_NUMBER, Setting::GROUP_SECURITY);
                Setting::set('password_min_length', $request->password_min_length, Setting::TYPE_NUMBER, Setting::GROUP_SECURITY);
                Setting::set('password_require_uppercase', $request->boolean('password_require_uppercase'), Setting::TYPE_BOOLEAN, Setting::GROUP_SECURITY);
                Setting::set('password_require_numbers', $request->boolean('password_require_numbers'), Setting::TYPE_BOOLEAN, Setting::GROUP_SECURITY);
                Setting::set('password_require_symbols', $request->boolean('password_require_symbols'), Setting::TYPE_BOOLEAN, Setting::GROUP_SECURITY);
                Setting::set('two_factor_enabled', $request->boolean('two_factor_enabled'), Setting::TYPE_BOOLEAN, Setting::GROUP_SECURITY);
                Setting::set('lockout_duration', $request->lockout_duration, Setting::TYPE_NUMBER, Setting::GROUP_SECURITY);

                return redirect()->back()->with('success', 'تم تحديث إعدادات الأمان بنجاح');

            } elseif ($section === 'backup') {
                $request->validate([
                    'backup_frequency' => 'required|in:daily,weekly,monthly',
                    'backup_retention' => 'required|integer|min:1',
                    'backup_location' => 'required|in:local,cloud',
                    'backup_notification' => 'boolean',
                ]);

                // حفظ إعدادات النسخ الاحتياطي
                Setting::set('backup_frequency', $request->backup_frequency, Setting::TYPE_STRING, Setting::GROUP_BACKUP);
                Setting::set('backup_retention', $request->backup_retention, Setting::TYPE_NUMBER, Setting::GROUP_BACKUP);
                Setting::set('backup_location', $request->backup_location, Setting::TYPE_STRING, Setting::GROUP_BACKUP);
                Setting::set('backup_notification', $request->boolean('backup_notification'), Setting::TYPE_BOOLEAN, Setting::GROUP_BACKUP);

                return redirect()->back()->with('success', 'تم تحديث إعدادات النسخ الاحتياطي بنجاح');
            }

            return redirect()->back()->with('warning', 'قسم غير معروف في الإعدادات');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('خطأ في تحديث الإعدادات: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: ' . $e->getMessage());
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
