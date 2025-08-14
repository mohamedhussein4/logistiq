<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class CompleteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();

        $this->command->info('تم تفريغ جدول الإعدادات...');

        $this->createAllSettings();

        $this->command->info('تم إنشاء جميع إعدادات النظام بنجاح!');
    }

    /**
     * إنشاء الإعدادات الأساسية فقط
     */
    private function createAllSettings(): void
    {
        $settings = [
            // اسم الموقع
            [
                'key' => 'site_name',
                'value' => 'Lnik2u',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // لوجو الموقع
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // رقم الهاتف
            [
                'key' => 'site_phone',
                'value' => '+966 11 123 4567',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // الإيميل
            [
                'key' => 'site_email',
                'value' => 'info@lnik2u.sa',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // العنوان
            [
                'key' => 'site_address',
                'value' => 'الرياض، المملكة العربية السعودية',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // حقوق الملكية
            [
                'key' => 'footer_copyright',
                'value' => 'جميع الحقوق محفوظة © 2025 lnik2u',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // وصف الفوتر
            [
                'key' => 'footer_description',
                'value' => 'lnik2u منصة متطورة لربط الشركات اللوجستية بالعملاء وتسهيل عمليات الشحن والتوصيل',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // favicon
            [
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'is_public' => true
            ],

            // SEO - العنوان
            [
                'key' => 'seo_title',
                'value' => 'lnik2u - نظام ربط الشركات اللوجستية',
                'type' => 'string',
                'group' => 'seo',
                'is_public' => true
            ],

            // SEO - الوصف
            [
                'key' => 'seo_description',
                'value' => 'منصة متطورة لربط الشركات اللوجستية بالعملاء وتسهيل عمليات الشحن والتوصيل',
                'type' => 'string',
                'group' => 'seo',
                'is_public' => true
            ],

            // SEO - الكلمات المفتاحية
            [
                'key' => 'seo_keywords',
                'value' => 'شركات لوجستية، شحن، توصيل، السعودية، lnik2u',
                'type' => 'string',
                'group' => 'seo',
                'is_public' => true
            ],

            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/lnik2u',
                'type' => 'string',
                'group' => 'social',
                'is_public' => true
            ],

            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/lnik2u',
                'type' => 'string',
                'group' => 'social',
                'is_public' => true
            ],

            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/lnik2u',
                'type' => 'string',
                'group' => 'social',
                'is_public' => true
            ],

            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/lnik2u',
                'type' => 'string',
                'group' => 'social',
                'is_public' => true
            ],

            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/lnik2u',
                'type' => 'string',
                'group' => 'social',
                'is_public' => true
            ],

            [
                'key' => 'social_whatsapp',
                'value' => 'https://wa.me/966501234567',
                'type' => 'string',
                'group' => 'social',
                'is_public' => true
            ]
        ];

        // إدراج الإعدادات في قاعدة البيانات
        foreach ($settings as $setting) {
            Setting::create($setting);
            $this->command->info("✓ تم إنشاء الإعداد: {$setting['key']}");
        }
    }
}
