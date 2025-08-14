@extends('layouts.admin')

@section('title', 'إعدادات النظام')
@section('page-title', 'إعدادات النظام')
@section('page-description', 'إدارة إعدادات الموقع والنظام الأساسية')

@section('content')
<div class="space-y-10">

    <!-- الإعدادات العامة -->
    <div class="glass-effect rounded-3xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black gradient-text mb-2">الإعدادات العامة</h2>
                <p class="text-slate-600">إدارة المعلومات الأساسية للموقع</p>
            </div>
            <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-cog text-white text-2xl"></i>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <input type="hidden" name="section" value="general">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- اسم الموقع -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-tag text-blue-500 ml-2"></i>
                        اسم الموقع
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="site_name"
                           value="{{ \App\Models\Setting::get('site_name', 'Logistiq') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-blue-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="اسم الموقع" required>
                </div>

                <!-- رقم الهاتف -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-phone text-green-500 ml-2"></i>
                        رقم الهاتف
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="site_phone"
                           value="{{ \App\Models\Setting::get('site_phone', '+966 11 123 4567') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-green-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="+966 11 123 4567" required>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- البريد الإلكتروني -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-envelope text-red-500 ml-2"></i>
                        البريد الإلكتروني
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="email" name="site_email"
                           value="{{ \App\Models\Setting::get('site_email', 'info@logistiq.sa') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-red-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="info@logistiq.sa" required>
                </div>

                <!-- حقوق الملكية -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-copyright text-purple-500 ml-2"></i>
                        حقوق الملكية
                    </label>
                    <input type="text" name="footer_copyright"
                           value="{{ \App\Models\Setting::get('footer_copyright', 'جميع الحقوق محفوظة © 2025 Logistiq') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-purple-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="جميع الحقوق محفوظة © 2025 Logistiq">
                </div>
            </div>

            <!-- العنوان -->
            <div class="space-y-3">
                <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                    <i class="fas fa-map-marker-alt text-orange-500 ml-2"></i>
                    العنوان الكامل
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <textarea name="site_address" rows="3"
                          class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-orange-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm resize-none"
                          placeholder="العنوان الكامل للشركة" required>{{ \App\Models\Setting::get('site_address', 'الرياض، المملكة العربية السعودية') }}</textarea>
            </div>

            <!-- وصف الفوتر -->
            <div class="space-y-3">
                <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                    <i class="fas fa-align-left text-indigo-500 ml-2"></i>
                    وصف الفوتر
                </label>
                <textarea name="footer_description" rows="3"
                          class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-indigo-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm resize-none"
                          placeholder="وصف يظهر في أسفل الموقع">{{ \App\Models\Setting::get('footer_description', '') }}</textarea>
            </div>

            <!-- رفع الملفات -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- شعار الموقع -->
                <div class="space-y-4">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-image text-blue-500 ml-2"></i>
                        شعار الموقع
                    </label>
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 hover:border-blue-500 transition-colors duration-300">
                        @if(\App\Models\Setting::get('site_logo'))
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/' . \App\Models\Setting::get('site_logo')) }}"
                                     alt="شعار الموقع"
                                     class="w-24 h-24 object-contain mx-auto rounded-lg border border-slate-200">
                                <p class="text-sm text-green-600 mt-2 font-medium">الشعار الحالي</p>
                            </div>
                        @endif
                        <input type="file" name="site_logo" accept="image/*"
                               class="w-full text-sm text-slate-500 file:ml-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:font-medium">
                        <p class="text-xs text-slate-500 mt-2 text-center">PNG, JPG, GIF حتى 2MB</p>
                    </div>
                </div>

                <!-- أيقونة الموقع -->
                <div class="space-y-4">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-star text-yellow-500 ml-2"></i>
                        أيقونة الموقع (Favicon)
                    </label>
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 hover:border-yellow-500 transition-colors duration-300">
                        @if(\App\Models\Setting::get('site_favicon'))
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/' . \App\Models\Setting::get('site_favicon')) }}"
                                     alt="أيقونة الموقع"
                                     class="w-16 h-16 object-contain mx-auto rounded-lg border border-slate-200">
                                <p class="text-sm text-green-600 mt-2 font-medium">الأيقونة الحالية</p>
                            </div>
                        @endif
                        <input type="file" name="site_favicon" accept="image/*"
                               class="w-full text-sm text-slate-500 file:ml-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 file:font-medium">
                        <p class="text-xs text-slate-500 mt-2 text-center">ICO, PNG حتى 1MB</p>
                    </div>
                </div>
            </div>

            <!-- زر الحفظ -->
            <div class="flex justify-end pt-6 border-t border-slate-200">
                <button type="submit"
                        class="px-12 py-4 bg-gradient-primary text-white font-bold rounded-2xl hover-lift transition-all duration-300 shadow-lg">
                    <i class="fas fa-save ml-2"></i>
                    حفظ الإعدادات العامة
                </button>
            </div>
        </form>
    </div>

    <!-- إعدادات SEO -->
    <div class="glass-effect rounded-3xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black gradient-text mb-2">إعدادات تحسين محركات البحث</h2>
                <p class="text-slate-600">تحسين ظهور الموقع في نتائج البحث</p>
            </div>
            <div class="w-16 h-16 bg-gradient-success rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-search text-white text-2xl"></i>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
            @csrf
            <input type="hidden" name="section" value="seo">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- عنوان SEO -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-heading text-blue-500 ml-2"></i>
                        عنوان الصفحة
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="seo_title"
                           value="{{ \App\Models\Setting::get('seo_title', 'Logistiq - نظام ربط الشركات اللوجستية') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-blue-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="عنوان الصفحة الرئيسية" required maxlength="60">
                    <p class="text-xs text-slate-500">يظهر في تبويب المتصفح ونتائج البحث</p>
                </div>

                <!-- الكلمات المفتاحية -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-tags text-green-500 ml-2"></i>
                        الكلمات المفتاحية
                    </label>
                    <input type="text" name="seo_keywords"
                           value="{{ \App\Models\Setting::get('seo_keywords', 'شركات لوجستية، شحن، توصيل، السعودية') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-green-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="كلمات مفصولة بفاصلة">
                    <p class="text-xs text-slate-500">كلمات مفتاحية مفصولة بفاصلة</p>
                </div>
            </div>

            <!-- وصف SEO -->
            <div class="space-y-3">
                <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                    <i class="fas fa-file-text text-purple-500 ml-2"></i>
                    وصف الموقع
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <textarea name="seo_description" rows="3"
                          class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-purple-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm resize-none"
                          placeholder="وصف مختصر للموقع يظهر في نتائج البحث"
                          required maxlength="160">{{ \App\Models\Setting::get('seo_description', 'منصة متطورة لربط الشركات اللوجستية بالعملاء وتسهيل عمليات الشحن والتوصيل') }}</textarea>
                <p class="text-xs text-slate-500">يظهر في نتائج محركات البحث (حد أقصى 160 حرف)</p>
            </div>

            <!-- زر الحفظ -->
            <div class="flex justify-end pt-6 border-t border-slate-200">
                <button type="submit"
                        class="px-12 py-4 bg-gradient-success text-white font-bold rounded-2xl hover-lift transition-all duration-300 shadow-lg">
                    <i class="fas fa-search ml-2"></i>
                    حفظ إعدادات SEO
                </button>
            </div>
        </form>
    </div>
</div>

<!-- قسم الوسائل الاجتماعية -->
<div class="glass-effect rounded-3xl border border-white/20 overflow-hidden shadow-2xl">
    <div class="p-8 bg-gradient-to-r from-pink-500/10 to-purple-500/10">
        <div class="flex items-center mb-2">
            <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-500 rounded-2xl flex items-center justify-center ml-4">
                <i class="fas fa-share-alt text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800">الوسائل الاجتماعية</h2>
                <p class="text-slate-600">إدارة روابط وسائل التواصل الاجتماعي</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8 mt-8">
            @csrf
            <input type="hidden" name="section" value="social">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- فيسبوك -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fab fa-facebook text-blue-600 ml-2"></i>
                        فيسبوك
                    </label>
                    <input type="url" name="social_facebook"
                           value="{{ \App\Models\Setting::get('social_facebook') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-blue-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="https://facebook.com/yourpage">
                </div>

                <!-- تويتر -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fab fa-twitter text-blue-400 ml-2"></i>
                        تويتر / X
                    </label>
                    <input type="url" name="social_twitter"
                           value="{{ \App\Models\Setting::get('social_twitter') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-blue-400 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="https://twitter.com/yourhandle">
                </div>

                <!-- إنستغرام -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fab fa-instagram text-pink-600 ml-2"></i>
                        إنستغرام
                    </label>
                    <input type="url" name="social_instagram"
                           value="{{ \App\Models\Setting::get('social_instagram') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-pink-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="https://instagram.com/youraccount">
                </div>

                <!-- لينكدإن -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fab fa-linkedin text-blue-700 ml-2"></i>
                        لينكدإن
                    </label>
                    <input type="url" name="social_linkedin"
                           value="{{ \App\Models\Setting::get('social_linkedin') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-blue-700 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="https://linkedin.com/company/yourcompany">
                </div>

                <!-- يوتيوب -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fab fa-youtube text-red-600 ml-2"></i>
                        يوتيوب
                    </label>
                    <input type="url" name="social_youtube"
                           value="{{ \App\Models\Setting::get('social_youtube') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-red-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="https://youtube.com/yourchannel">
                </div>

                <!-- واتساب -->
                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                        <i class="fab fa-whatsapp text-green-500 ml-2"></i>
                        واتساب
                    </label>
                    <input type="url" name="social_whatsapp"
                           value="{{ \App\Models\Setting::get('social_whatsapp') }}"
                           class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:border-green-500 focus:outline-none transition-all duration-300 bg-white/80 backdrop-blur-sm"
                           placeholder="https://wa.me/966501234567">
                    <p class="text-xs text-slate-500">استخدم رقم الهاتف مع رمز البلد</p>
                </div>
            </div>

            <!-- زر الحفظ -->
            <div class="flex justify-end pt-6 border-t border-slate-200">
                <button type="submit"
                        class="px-12 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold rounded-2xl hover-lift transition-all duration-300 shadow-lg">
                    <i class="fas fa-share-alt ml-2"></i>
                    حفظ روابط وسائل التواصل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
