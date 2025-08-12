<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class WebsiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إعدادات عامة للموقع
        $this->createGeneralSettings();

        // إعدادات SEO
        $this->createSEOSettings();

        // إعدادات التواصل
        $this->createContactSettings();

        // إعدادات السوشيال ميديا
        $this->createSocialMediaSettings();

        // إعدادات الفوتر
        $this->createFooterSettings();

        // إعدادات الموقع
        $this->createWebsiteSettings();

        // إعدادات الأمان
        $this->createSecuritySettings();

        // إعدادات الأداء
        $this->createPerformanceSettings();

        $this->command->info('تم إنشاء إعدادات الموقع بنجاح!');
    }

    /**
     * إنشاء الإعدادات العامة
     */
    private function createGeneralSettings(): void
    {
        $generalSettings = [
            [
                'key' => 'site_name',
                'value' => 'Logistiq - نظام ربط الشركات اللوجستية',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'اسم الموقع',
                'description' => 'اسم الموقع الرئيسي الذي يظهر في العنوان والشعار',
                'is_public' => true,
                'sort_order' => 1
            ],
            [
                'key' => 'site_tagline',
                'value' => 'ربط الشركات اللوجستية مع شركات التمويل بكل سهولة وأمان',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'شعار الموقع',
                'description' => 'وصف مختصر للموقع يظهر تحت العنوان',
                'is_public' => true,
                'sort_order' => 2
            ],
            [
                'key' => 'site_description',
                'value' => 'منصة متكاملة لربط الشركات اللوجستية مع شركات التمويل والخدمات، تقدم حلول شاملة لإدارة العمليات المالية واللوجستية',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'وصف الموقع',
                'description' => 'وصف مفصل للموقع وأهدافه',
                'is_public' => true,
                'sort_order' => 3
            ],
            [
                'key' => 'site_keywords',
                'value' => 'شركات لوجستية, تمويل, خدمات مالية, شحن, نقل, مستودعات, توزيع, السعودية',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'كلمات مفتاحية',
                'description' => 'الكلمات المفتاحية الأساسية للموقع',
                'is_public' => true,
                'sort_order' => 4
            ],
            [
                'key' => 'site_language',
                'value' => 'ar',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'لغة الموقع',
                'description' => 'اللغة الأساسية للموقع',
                'is_public' => true,
                'sort_order' => 5
            ],
            [
                'key' => 'site_timezone',
                'value' => 'Asia/Riyadh',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'المنطقة الزمنية',
                'description' => 'المنطقة الزمنية للموقع',
                'is_public' => false,
                'sort_order' => 6
            ],
            [
                'key' => 'site_currency',
                'value' => 'SAR',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'العملة',
                'description' => 'العملة الأساسية للموقع',
                'is_public' => true,
                'sort_order' => 7
            ],
            [
                'key' => 'site_currency_symbol',
                'value' => 'ر.س',
                'type' => Setting::TYPE_STRING,
                'group' => Setting::GROUP_GENERAL,
                'label' => 'رمز العملة',
                'description' => 'رمز العملة المستخدم في العرض',
                'is_public' => true,
                'sort_order' => 8
            ]
        ];

        foreach ($generalSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات SEO
     */
    private function createSEOSettings(): void
    {
        $seoSettings = [
            [
                'key' => 'meta_title',
                'value' => 'Logistiq - نظام ربط الشركات اللوجستية مع شركات التمويل',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'عنوان Meta الرئيسي',
                'description' => 'العنوان الذي يظهر في نتائج البحث',
                'is_public' => true,
                'sort_order' => 10
            ],
            [
                'key' => 'meta_description',
                'value' => 'منصة متكاملة لربط الشركات اللوجستية مع شركات التمويل والخدمات. حلول شاملة لإدارة العمليات المالية واللوجستية في المملكة العربية السعودية',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'وصف Meta',
                'description' => 'الوصف الذي يظهر في نتائج البحث',
                'is_public' => true,
                'sort_order' => 11
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'شركات لوجستية, تمويل, خدمات مالية, شحن, نقل, مستودعات, توزيع, السعودية, الرياض, جدة, الدمام',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'كلمات Meta مفتاحية',
                'description' => 'الكلمات المفتاحية لتحسين محركات البحث',
                'is_public' => true,
                'sort_order' => 12
            ],
            [
                'key' => 'meta_author',
                'value' => 'Logistiq Team',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'مؤلف الموقع',
                'description' => 'اسم مؤلف أو فريق الموقع',
                'is_public' => true,
                'sort_order' => 13
            ],
            [
                'key' => 'meta_robots',
                'value' => 'index, follow',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'تعليمات Robots',
                'description' => 'تعليمات لمحركات البحث',
                'is_public' => true,
                'sort_order' => 14
            ],
            [
                'key' => 'og_title',
                'value' => 'Logistiq - نظام ربط الشركات اللوجستية',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'عنوان Open Graph',
                'description' => 'العنوان الذي يظهر عند مشاركة الموقع على وسائل التواصل',
                'is_public' => true,
                'sort_order' => 15
            ],
            [
                'key' => 'og_description',
                'value' => 'منصة متكاملة لربط الشركات اللوجستية مع شركات التمويل والخدمات',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'وصف Open Graph',
                'description' => 'الوصف الذي يظهر عند مشاركة الموقع على وسائل التواصل',
                'is_public' => true,
                'sort_order' => 16
            ],
            [
                'key' => 'og_type',
                'value' => 'website',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'نوع Open Graph',
                'description' => 'نوع المحتوى للموقع',
                'is_public' => true,
                'sort_order' => 17
            ],
            [
                'key' => 'twitter_card',
                'value' => 'summary_large_image',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'نوع Twitter Card',
                'description' => 'نوع البطاقة التي تظهر عند مشاركة الموقع على تويتر',
                'is_public' => true,
                'sort_order' => 18
            ],
            [
                'key' => 'twitter_creator',
                'value' => '@logistiq_sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'seo',
                'label' => 'حساب تويتر',
                'description' => 'حساب تويتر الرسمي للموقع',
                'is_public' => true,
                'sort_order' => 19
            ]
        ];

        foreach ($seoSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات التواصل
     */
    private function createContactSettings(): void
    {
        $contactSettings = [
            [
                'key' => 'contact_address',
                'value' => 'الرياض، المملكة العربية السعودية',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'عنوان الشركة',
                'description' => 'العنوان الرسمي للشركة',
                'is_public' => true,
                'sort_order' => 20
            ],
            [
                'key' => 'contact_phone',
                'value' => '+966 11 123 4567',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'رقم الهاتف',
                'description' => 'رقم الهاتف الرسمي للشركة',
                'is_public' => true,
                'sort_order' => 21
            ],
            [
                'key' => 'contact_mobile',
                'value' => '+966 50 123 4567',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'رقم الجوال',
                'description' => 'رقم الجوال للتواصل المباشر',
                'is_public' => true,
                'sort_order' => 22
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@logistiq.sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'البريد الإلكتروني',
                'description' => 'البريد الإلكتروني الرسمي للشركة',
                'is_public' => true,
                'sort_order' => 23
            ],
            [
                'key' => 'contact_support_email',
                'value' => 'support@logistiq.sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'بريد الدعم الفني',
                'description' => 'البريد الإلكتروني للدعم الفني',
                'is_public' => true,
                'sort_order' => 24
            ],
            [
                'key' => 'contact_sales_email',
                'value' => 'sales@logistiq.sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'بريد المبيعات',
                'description' => 'البريد الإلكتروني لقسم المبيعات',
                'is_public' => true,
                'sort_order' => 25
            ],
            [
                'key' => 'contact_working_hours',
                'value' => 'الأحد - الخميس: 8:00 ص - 6:00 م | الجمعة: 9:00 ص - 1:00 م',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'ساعات العمل',
                'description' => 'ساعات العمل الرسمية للشركة',
                'is_public' => true,
                'sort_order' => 26
            ],
            [
                'key' => 'contact_timezone',
                'value' => 'GMT+3',
                'type' => Setting::TYPE_STRING,
                'group' => 'contact',
                'label' => 'المنطقة الزمنية',
                'description' => 'المنطقة الزمنية للشركة',
                'is_public' => true,
                'sort_order' => 27
            ]
        ];

        foreach ($contactSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات السوشيال ميديا
     */
    private function createSocialMediaSettings(): void
    {
        $socialSettings = [
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/logistiq.sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'صفحة فيسبوك',
                'description' => 'رابط الصفحة الرسمية على فيسبوك',
                'is_public' => true,
                'sort_order' => 30
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/logistiq_sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'حساب تويتر',
                'description' => 'رابط الحساب الرسمي على تويتر',
                'is_public' => true,
                'sort_order' => 31
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/logistiq-sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'صفحة لينكد إن',
                'description' => 'رابط الصفحة الرسمية على لينكد إن',
                'is_public' => true,
                'sort_order' => 32
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/logistiq.sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'حساب إنستغرام',
                'description' => 'رابط الحساب الرسمي على إنستغرام',
                'is_public' => true,
                'sort_order' => 33
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@logistiq.sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'قناة يوتيوب',
                'description' => 'رابط القناة الرسمية على يوتيوب',
                'is_public' => true,
                'sort_order' => 34
            ],
            [
                'key' => 'social_whatsapp',
                'value' => '+966501234567',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'رقم واتساب',
                'description' => 'رقم واتساب للتواصل المباشر',
                'is_public' => true,
                'sort_order' => 35
            ],
            [
                'key' => 'social_telegram',
                'value' => 'https://t.me/logistiq_sa',
                'type' => Setting::TYPE_STRING,
                'group' => 'social',
                'label' => 'قناة تليجرام',
                'description' => 'رابط القناة الرسمية على تليجرام',
                'is_public' => true,
                'sort_order' => 36
            ]
        ];

        foreach ($socialSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات الفوتر
     */
    private function createFooterSettings(): void
    {
        $footerSettings = [
            [
                'key' => 'footer_description',
                'value' => 'منصة متكاملة لربط الشركات اللوجستية مع شركات التمويل والخدمات، نقدم حلول شاملة لإدارة العمليات المالية واللوجستية بكل كفاءة وأمان',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'وصف الفوتر',
                'description' => 'الوصف الذي يظهر في أسفل الموقع',
                'is_public' => true,
                'sort_order' => 40
            ],
            [
                'key' => 'footer_copyright',
                'value' => 'جميع الحقوق محفوظة © 2025 Logistiq. تم التطوير بواسطة فريق Logistiq',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'حقوق الملكية',
                'description' => 'نص حقوق الملكية في أسفل الموقع',
                'is_public' => true,
                'sort_order' => 41
            ],
            [
                'key' => 'footer_company_name',
                'value' => 'Logistiq',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'اسم الشركة في الفوتر',
                'description' => 'اسم الشركة كما يظهر في الفوتر',
                'is_public' => true,
                'sort_order' => 42
            ],
            [
                'key' => 'footer_established_year',
                'value' => '2025',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'سنة التأسيس',
                'description' => 'سنة تأسيس الشركة',
                'is_public' => true,
                'sort_order' => 43
            ],
            [
                'key' => 'footer_services_title',
                'value' => 'خدماتنا',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'عنوان قسم الخدمات',
                'description' => 'عنوان قسم الخدمات في الفوتر',
                'is_public' => true,
                'sort_order' => 44
            ],
            [
                'key' => 'footer_company_title',
                'value' => 'الشركة',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'عنوان قسم الشركة',
                'description' => 'عنوان قسم الشركة في الفوتر',
                'is_public' => true,
                'sort_order' => 45
            ],
            [
                'key' => 'footer_support_title',
                'value' => 'الدعم',
                'type' => Setting::TYPE_STRING,
                'group' => 'footer',
                'label' => 'عنوان قسم الدعم',
                'description' => 'عنوان قسم الدعم في الفوتر',
                'is_public' => true,
                'sort_order' => 46
            ]
        ];

        foreach ($footerSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات الموقع
     */
    private function createWebsiteSettings(): void
    {
        $websiteSettings = [
            [
                'key' => 'site_logo',
                'value' => '/images/logo.png',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'شعار الموقع',
                'description' => 'مسار شعار الموقع الرئيسي',
                'is_public' => true,
                'sort_order' => 50
            ],
            [
                'key' => 'site_logo_white',
                'value' => '/images/logo-white.png',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'شعار الموقع الأبيض',
                'description' => 'مسار شعار الموقع باللون الأبيض',
                'is_public' => true,
                'sort_order' => 51
            ],
            [
                'key' => 'site_favicon',
                'value' => '/favicon.ico',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'أيقونة الموقع',
                'description' => 'مسار أيقونة الموقع (Favicon)',
                'is_public' => true,
                'sort_order' => 52
            ],
            [
                'key' => 'site_hero_title',
                'value' => 'ربط الشركات اللوجستية مع شركات التمويل',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'عنوان الصفحة الرئيسية',
                'description' => 'العنوان الرئيسي في الصفحة الرئيسية',
                'is_public' => true,
                'sort_order' => 53
            ],
            [
                'key' => 'site_hero_subtitle',
                'value' => 'منصة متكاملة تقدم حلول شاملة لإدارة العمليات المالية واللوجستية بكل كفاءة وأمان',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'العنوان الفرعي للصفحة الرئيسية',
                'description' => 'العنوان الفرعي في الصفحة الرئيسية',
                'is_public' => true,
                'sort_order' => 54
            ],
            [
                'key' => 'site_hero_button_text',
                'value' => 'ابدأ الآن',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'نص زر الصفحة الرئيسية',
                'description' => 'نص الزر الرئيسي في الصفحة الرئيسية',
                'is_public' => true,
                'sort_order' => 55
            ],
            [
                'key' => 'site_hero_button_url',
                'value' => '/register',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'رابط زر الصفحة الرئيسية',
                'description' => 'الرابط الذي يذهب إليه الزر الرئيسي',
                'is_public' => true,
                'sort_order' => 56
            ],
            [
                'key' => 'site_features_title',
                'value' => 'مميزات منصة Logistiq',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'عنوان قسم المميزات',
                'description' => 'عنوان قسم المميزات في الصفحة الرئيسية',
                'is_public' => true,
                'sort_order' => 57
            ],
            [
                'key' => 'site_features_subtitle',
                'value' => 'اكتشف كيف يمكن لمنصتنا أن تساعد شركتك في النمو والتطور',
                'type' => Setting::TYPE_STRING,
                'group' => 'website',
                'label' => 'العنوان الفرعي لقسم المميزات',
                'description' => 'العنوان الفرعي لقسم المميزات',
                'is_public' => true,
                'sort_order' => 58
            ]
        ];

        foreach ($websiteSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات الأمان
     */
    private function createSecuritySettings(): void
    {
        $securitySettings = [
            [
                'key' => 'security_maintenance_mode',
                'value' => '0',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => 'security',
                'label' => 'وضع الصيانة',
                'description' => 'تفعيل وضع الصيانة للموقع',
                'is_public' => false,
                'sort_order' => 60
            ],
            [
                'key' => 'security_maintenance_message',
                'value' => 'الموقع قيد الصيانة حالياً، نعتذر عن الإزعاج وسنعود قريباً',
                'type' => Setting::TYPE_STRING,
                'group' => 'security',
                'label' => 'رسالة الصيانة',
                'description' => 'الرسالة التي تظهر عند تفعيل وضع الصيانة',
                'is_public' => false,
                'sort_order' => 61
            ],
            [
                'key' => 'security_recaptcha_enabled',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => 'security',
                'label' => 'تفعيل reCAPTCHA',
                'description' => 'تفعيل حماية reCAPTCHA للنماذج',
                'is_public' => false,
                'sort_order' => 62
            ],
            [
                'key' => 'security_max_login_attempts',
                'value' => '5',
                'type' => Setting::TYPE_NUMBER,
                'group' => 'security',
                'label' => 'الحد الأقصى لمحاولات تسجيل الدخول',
                'description' => 'عدد محاولات تسجيل الدخول المسموحة قبل الحظر',
                'is_public' => false,
                'sort_order' => 63
            ],
            [
                'key' => 'security_session_timeout',
                'value' => '120',
                'type' => Setting::TYPE_NUMBER,
                'group' => 'security',
                'label' => 'مهلة انتهاء الجلسة (دقائق)',
                'description' => 'الوقت بالدقائق قبل انتهاء صلاحية الجلسة',
                'is_public' => false,
                'sort_order' => 64
            ]
        ];

        foreach ($securitySettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * إنشاء إعدادات الأداء
     */
    private function createPerformanceSettings(): void
    {
        $performanceSettings = [
            [
                'key' => 'performance_cache_enabled',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => 'performance',
                'label' => 'تفعيل التخزين المؤقت',
                'description' => 'تفعيل نظام التخزين المؤقت لتحسين الأداء',
                'is_public' => false,
                'sort_order' => 70
            ],
            [
                'key' => 'performance_cache_duration',
                'value' => '3600',
                'type' => Setting::TYPE_NUMBER,
                'group' => 'performance',
                'label' => 'مدة التخزين المؤقت (ثواني)',
                'description' => 'مدة التخزين المؤقت بالثواني',
                'is_public' => false,
                'sort_order' => 71
            ],
            [
                'key' => 'performance_image_optimization',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => 'performance',
                'label' => 'تحسين الصور',
                'description' => 'تفعيل تحسين الصور تلقائياً',
                'is_public' => false,
                'sort_order' => 72
            ],
            [
                'key' => 'performance_minify_css',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => 'performance',
                'label' => 'تصغير ملفات CSS',
                'description' => 'تصغير ملفات CSS لتحسين الأداء',
                'is_public' => false,
                'sort_order' => 73
            ],
            [
                'key' => 'performance_minify_js',
                'value' => '1',
                'type' => Setting::TYPE_BOOLEAN,
                'group' => 'performance',
                'label' => 'تصغير ملفات JavaScript',
                'description' => 'تصغير ملفات JavaScript لتحسين الأداء',
                'is_public' => false,
                'sort_order' => 74
            ]
        ];

        foreach ($performanceSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
