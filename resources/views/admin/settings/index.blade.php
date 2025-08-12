@extends('layouts.admin')

@section('title', 'إعدادات النظام')
@section('page-title', 'إعدادات النظام')
@section('page-description', 'إدارة الإعدادات العامة والتكوينات الأساسية للمنصة')

@section('content')
<div class="space-y-6">
    <!-- Header with System Info -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ \App\Models\Setting::get('site_name', 'Logistiq') }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">اسم الموقع</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ \App\Models\Setting::get('site_currency', 'SAR') }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">العملة الأساسية</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ \App\Models\Setting::get('site_language', 'ar') }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">لغة الموقع</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ \App\Models\Setting::get('site_timezone', 'Asia/Riyadh') }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">المنطقة الزمنية</div>
            </div>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-4">
            <button onclick="switchTab('general')" class="tab-button active px-4 py-2 rounded-lg font-semibold transition-all" data-tab="general">
                <i class="fas fa-cog mr-2"></i>
                إعدادات عامة
            </button>
            <button onclick="switchTab('seo')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="seo">
                <i class="fas fa-search mr-2"></i>
                تحسين محركات البحث
            </button>
            <button onclick="switchTab('contact')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="contact">
                <i class="fas fa-phone mr-2"></i>
                معلومات التواصل
            </button>
            <button onclick="switchTab('social')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="social">
                <i class="fas fa-share-alt mr-2"></i>
                وسائل التواصل الاجتماعي
            </button>
            <button onclick="switchTab('website')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="website">
                <i class="fas fa-globe mr-2"></i>
                إعدادات الموقع
            </button>
            <button onclick="switchTab('footer')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="footer">
                <i class="fas fa-shoe-prints mr-2"></i>
                إعدادات الفوتر
            </button>
            <button onclick="switchTab('security')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="security">
                <i class="fas fa-shield-alt mr-2"></i>
                الأمان
            </button>
            <button onclick="switchTab('performance')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="performance">
                <i class="fas fa-tachometer-alt mr-2"></i>
                الأداء
            </button>
        </div>

        <!-- General Settings Tab -->
        <div id="general-tab" class="tab-content">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="general">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الموقع <span class="text-red-500">*</span></label>
                        <input type="text" name="site_name" value="{{ \App\Models\Setting::get('site_name', 'Logistiq') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="اسم الموقع">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">شعار الموقع <span class="text-red-500">*</span></label>
                        <input type="text" name="site_tagline" value="{{ \App\Models\Setting::get('site_tagline', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="شعار الموقع">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">وصف الموقع</label>
                    <textarea name="site_description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="وصف مفصل للموقع وأهدافه">{{ \App\Models\Setting::get('site_description', '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">العملة الأساسية</label>
                        <select name="site_currency" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="SAR" {{ \App\Models\Setting::get('site_currency', 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                            <option value="USD" {{ \App\Models\Setting::get('site_currency', 'SAR') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="EUR" {{ \App\Models\Setting::get('site_currency', 'SAR') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رمز العملة</label>
                        <input type="text" name="site_currency_symbol" value="{{ \App\Models\Setting::get('site_currency_symbol', 'ر.س') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="ر.س">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">المنطقة الزمنية</label>
                        <select name="site_timezone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="Asia/Riyadh" {{ \App\Models\Setting::get('site_timezone', 'Asia/Riyadh') == 'Asia/Riyadh' ? 'selected' : '' }}>الرياض (GMT+3)</option>
                            <option value="Asia/Dubai" {{ \App\Models\Setting::get('site_timezone', 'Asia/Riyadh') == 'Asia/Dubai' ? 'selected' : '' }}>دبي (GMT+4)</option>
                            <option value="UTC" {{ \App\Models\Setting::get('site_timezone', 'Asia/Riyadh') == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">لغة الموقع</label>
                        <select name="site_language" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="ar" {{ \App\Models\Setting::get('site_language', 'ar') == 'ar' ? 'selected' : '' }}>العربية</option>
                            <option value="en" {{ \App\Models\Setting::get('site_language', 'ar') == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ الإعدادات العامة
                    </button>
                </div>
            </form>
        </div>

        <!-- SEO Settings Tab -->
        <div id="seo-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="seo">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان Meta الرئيسي</label>
                        <input type="text" name="meta_title" value="{{ \App\Models\Setting::get('meta_title', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="العنوان الذي يظهر في نتائج البحث">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">مؤلف الموقع</label>
                        <input type="text" name="meta_author" value="{{ \App\Models\Setting::get('meta_author', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="اسم مؤلف أو فريق الموقع">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">وصف Meta</label>
                    <textarea name="meta_description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="الوصف الذي يظهر في نتائج البحث">{{ \App\Models\Setting::get('meta_description', '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">كلمات Meta مفتاحية</label>
                    <input type="text" name="meta_keywords" value="{{ \App\Models\Setting::get('meta_keywords', '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="كلمات مفتاحية مفصولة بفواصل">
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">تعليمات Robots</label>
                        <input type="text" name="meta_robots" value="{{ \App\Models\Setting::get('meta_robots', 'index, follow') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="index, follow">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع Open Graph</label>
                        <select name="og_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="website" {{ \App\Models\Setting::get('og_type', 'website') == 'website' ? 'selected' : '' }}>موقع إلكتروني</option>
                            <option value="article" {{ \App\Models\Setting::get('og_type', 'website') == 'article' ? 'selected' : '' }}>مقال</option>
                            <option value="product" {{ \App\Models\Setting::get('og_type', 'website') == 'product' ? 'selected' : '' }}>منتج</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات SEO
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact Settings Tab -->
        <div id="contact-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="contact">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الشركة</label>
                        <input type="text" name="contact_address" value="{{ \App\Models\Setting::get('contact_address', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="عنوان الشركة الرسمي">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                        <input type="text" name="contact_phone" value="{{ \App\Models\Setting::get('contact_phone', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="+966 11 123 4567">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم الجوال</label>
                        <input type="text" name="contact_mobile" value="{{ \App\Models\Setting::get('contact_mobile', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="+966 50 123 4567">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="contact_email" value="{{ \App\Models\Setting::get('contact_email', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="info@logistiq.sa">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">بريد الدعم الفني</label>
                        <input type="email" name="contact_support_email" value="{{ \App\Models\Setting::get('contact_support_email', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="support@logistiq.sa">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">بريد المبيعات</label>
                        <input type="email" name="contact_sales_email" value="{{ \App\Models\Setting::get('contact_sales_email', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="sales@logistiq.sa">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ساعات العمل</label>
                    <input type="text" name="contact_working_hours" value="{{ \App\Models\Setting::get('contact_working_hours', '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="الأحد - الخميس: 8:00 ص - 6:00 م | الجمعة: 9:00 ص - 1:00 م">
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ معلومات التواصل
                    </button>
                </div>
            </form>
        </div>

        <!-- Social Media Settings Tab -->
        <div id="social-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="social">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">صفحة فيسبوك</label>
                        <input type="url" name="social_facebook" value="{{ \App\Models\Setting::get('social_facebook', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="https://facebook.com/logistiq.sa">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">حساب تويتر</label>
                        <input type="url" name="social_twitter" value="{{ \App\Models\Setting::get('social_twitter', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="https://twitter.com/logistiq_sa">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">صفحة لينكد إن</label>
                        <input type="url" name="social_linkedin" value="{{ \App\Models\Setting::get('social_linkedin', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="https://linkedin.com/company/logistiq-sa">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">حساب إنستغرام</label>
                        <input type="url" name="social_instagram" value="{{ \App\Models\Setting::get('social_instagram', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="https://instagram.com/logistiq.sa">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">قناة يوتيوب</label>
                        <input type="url" name="social_youtube" value="{{ \App\Models\Setting::get('social_youtube', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="https://youtube.com/@logistiq.sa">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم واتساب</label>
                        <input type="text" name="social_whatsapp" value="{{ \App\Models\Setting::get('social_whatsapp', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="+966501234567">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات السوشيال ميديا
                    </button>
                </div>
            </form>
        </div>

        <!-- Website Settings Tab -->
        <div id="website-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="website">

                <!-- الشعار -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">شعار الموقع</label>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            @if(\App\Models\Setting::get('website.logo'))
                                <img src="{{ asset('images/' . \App\Models\Setting::get('website.logo')) }}"
                                     alt="شعار الموقع"
                                     class="max-w-full max-h-full object-contain">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file"
                                   name="website_logo"
                                   accept="image/*"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">يفضل صورة بحجم 200×200 بكسل أو أكبر</p>
                            @if(\App\Models\Setting::get('website.logo'))
                                <p class="text-xs text-green-600 mt-1">الشعار الحالي: {{ \App\Models\Setting::get('website.logo') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Favicon -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">أيقونة الموقع (Favicon)</label>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            @if(\App\Models\Setting::get('website.favicon'))
                                <img src="{{ asset('images/' . \App\Models\Setting::get('website.favicon')) }}"
                                     alt="أيقونة الموقع"
                                     class="max-w-full max-h-full object-contain">
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file"
                                   name="website_favicon"
                                   accept="image/*"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">يفضل صورة بحجم 32×32 بكسل أو 16×16 بكسل</p>
                            @if(\App\Models\Setting::get('website.favicon'))
                                <p class="text-xs text-green-600 mt-1">الأيقونة الحالية: {{ \App\Models\Setting::get('website.favicon') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الصفحة الرئيسية</label>
                        <input type="text" name="site_hero_title" value="{{ \App\Models\Setting::get('site_hero_title', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="العنوان الرئيسي في الصفحة الرئيسية">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نص زر الصفحة الرئيسية</label>
                        <input type="text" name="site_hero_button_text" value="{{ \App\Models\Setting::get('site_hero_button_text', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="ابدأ الآن">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">العنوان الفرعي للصفحة الرئيسية</label>
                    <textarea name="site_hero_subtitle" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="العنوان الفرعي في الصفحة الرئيسية">{{ \App\Models\Setting::get('site_hero_subtitle', '') }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات الموقع
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Settings Tab -->
        <div id="footer-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="footer">

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">وصف الفوتر</label>
                    <textarea name="footer_description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="الوصف الذي يظهر في أسفل الموقع">{{ \App\Models\Setting::get('footer_description', '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">حقوق الملكية</label>
                        <input type="text" name="footer_copyright" value="{{ \App\Models\Setting::get('footer_copyright', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="جميع الحقوق محفوظة © 2025 Logistiq">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الشركة في الفوتر</label>
                        <input type="text" name="footer_company_name" value="{{ \App\Models\Setting::get('footer_company_name', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Logistiq">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان قسم الخدمات</label>
                        <input type="text" name="footer_services_title" value="{{ \App\Models\Setting::get('footer_services_title', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="خدماتنا">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان قسم الشركة</label>
                        <input type="text" name="footer_company_title" value="{{ \App\Models\Setting::get('footer_company_title', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="الشركة">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان قسم الدعم</label>
                        <input type="text" name="footer_support_title" value="{{ \App\Models\Setting::get('footer_support_title', '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="الدعم">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات الفوتر
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Settings Tab -->
        <div id="security-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="security">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="security_maintenance_mode" value="1" {{ \App\Models\Setting::get('security_maintenance_mode', false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm font-bold text-slate-700">تفعيل وضع الصيانة</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="security_recaptcha_enabled" value="1" {{ \App\Models\Setting::get('security_recaptcha_enabled', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm font-bold text-slate-700">تفعيل reCAPTCHA</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحد الأقصى لمحاولات تسجيل الدخول</label>
                        <input type="number" name="security_max_login_attempts" value="{{ \App\Models\Setting::get('security_max_login_attempts', 5) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               min="1" max="10">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">مهلة انتهاء الجلسة (دقائق)</label>
                        <input type="number" name="security_session_timeout" value="{{ \App\Models\Setting::get('security_session_timeout', 120) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               min="30" max="480">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رسالة الصيانة</label>
                    <textarea name="security_maintenance_message" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="الرسالة التي تظهر عند تفعيل وضع الصيانة">{{ \App\Models\Setting::get('security_maintenance_message', '') }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات الأمان
                    </button>
                </div>
            </form>
        </div>

        <!-- Performance Settings Tab -->
        <div id="performance-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="performance">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="performance_cache_enabled" value="1" {{ \App\Models\Setting::get('performance_cache_enabled', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm font-bold text-slate-700">تفعيل التخزين المؤقت</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="performance_image_optimization" value="1" {{ \App\Models\Setting::get('performance_image_optimization', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm font-bold text-slate-700">تحسين الصور</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="performance_minify_css" value="1" {{ \App\Models\Setting::get('performance_minify_css', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm font-bold text-slate-700">تصغير ملفات CSS</span>
                        </label>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="performance_minify_js" value="1" {{ \App\Models\Setting::get('performance_minify_js', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm font-bold text-slate-700">تصغير ملفات JavaScript</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">مدة التخزين المؤقت (ثواني)</label>
                    <input type="number" name="performance_cache_duration" value="{{ \App\Models\Setting::get('performance_cache_duration', 3600) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           min="300" max="86400">
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات الأداء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Switch between tabs
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'bg-blue-500', 'text-white');
            button.classList.add('bg-gray-100', 'text-gray-700');
        });

        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.remove('hidden');

        // Add active class to selected tab button
        event.target.classList.add('active', 'bg-blue-500', 'text-white');
        event.target.classList.remove('bg-gray-100', 'text-gray-700');
    }

    // Initialize first tab as active
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.tab-button.active').classList.add('bg-blue-500', 'text-white');
        document.querySelector('.tab-button.active').classList.remove('bg-gray-100', 'text-gray-700');
    });
</script>
@endpush
@endsection


