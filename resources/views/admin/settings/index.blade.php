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
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $settings['app_version'] ?? '1.0.0' }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إصدار النظام</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $settings['uptime'] ?? '99.9' }}%</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">وقت التشغيل</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $settings['storage_used'] ?? '2.5' }}GB</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">مساحة مستخدمة</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $settings['backup_count'] ?? '7' }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">النسخ الاحتياطية</div>
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
            <button onclick="switchTab('email')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="email">
                <i class="fas fa-envelope mr-2"></i>
                البريد الإلكتروني
            </button>
            <button onclick="switchTab('payment')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="payment">
                <i class="fas fa-credit-card mr-2"></i>
                المدفوعات
            </button>
            <button onclick="switchTab('security')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="security">
                <i class="fas fa-shield-alt mr-2"></i>
                الأمان
            </button>
            <button onclick="switchTab('backup')" class="tab-button px-4 py-2 rounded-lg font-semibold transition-all" data-tab="backup">
                <i class="fas fa-database mr-2"></i>
                النسخ الاحتياطي
            </button>
        </div>

        <!-- General Settings Tab -->
        <div id="general-tab" class="tab-content">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم التطبيق</label>
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'Link2u' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">العملة الافتراضية</label>
                        <select name="default_currency"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="SAR" {{ ($settings['default_currency'] ?? 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                            <option value="USD" {{ ($settings['default_currency'] ?? 'SAR') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="EUR" {{ ($settings['default_currency'] ?? 'SAR') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">المنطقة الزمنية</label>
                        <select name="timezone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="Asia/Riyadh" {{ ($settings['timezone'] ?? 'Asia/Riyadh') == 'Asia/Riyadh' ? 'selected' : '' }}>الرياض</option>
                            <option value="Asia/Dubai" {{ ($settings['timezone'] ?? 'Asia/Riyadh') == 'Asia/Dubai' ? 'selected' : '' }}>دبي</option>
                            <option value="UTC" {{ ($settings['timezone'] ?? 'Asia/Riyadh') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">حد رفع الملفات</label>
                        <select name="max_upload_size"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="2MB" {{ ($settings['max_upload_size'] ?? '10MB') == '2MB' ? 'selected' : '' }}>2 ميجابايت</option>
                            <option value="5MB" {{ ($settings['max_upload_size'] ?? '10MB') == '5MB' ? 'selected' : '' }}>5 ميجابايت</option>
                            <option value="10MB" {{ ($settings['max_upload_size'] ?? '10MB') == '10MB' ? 'selected' : '' }}>10 ميجابايت</option>
                            <option value="20MB" {{ ($settings['max_upload_size'] ?? '10MB') == '20MB' ? 'selected' : '' }}>20 ميجابايت</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm font-bold text-slate-700">تفعيل وضع الصيانة</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ الإعدادات العامة
                    </button>
                </div>
            </form>
        </div>

        <!-- Email Settings Tab -->
        <div id="email-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="email">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">خادم SMTP</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="smtp.gmail.com">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">منفذ SMTP</label>
                        <input type="number" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم المستخدم</label>
                        <input type="email" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="your-email@gmail.com">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور</label>
                        <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البريد الافتراضي للإرسال</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="noreply@Link2u.com">
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex space-x-4 space-x-reverse">
                        <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                            <i class="fas fa-save mr-2"></i>
                            حفظ إعدادات البريد
                        </button>
                        <button type="button" onclick="testEmail()" class="px-6 py-3 bg-gradient-success text-white rounded-xl font-semibold hover-lift transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>
                            اختبار البريد
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Payment Settings Tab -->
        <div id="payment-tab" class="tab-content hidden">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="section" value="payment">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">معرف التاجر</label>
                        <input type="text" name="merchant_id" value="{{ $settings['merchant_id'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">مفتاح API</label>
                        <input type="password" name="payment_api_key" value="{{ $settings['payment_api_key'] ?? '' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">بوابات الدفع المفعلة</label>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="payment_methods[]" value="visa" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">فيزا</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="payment_methods[]" value="mastercard" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">ماستركارد</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="payment_methods[]" value="mada" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">مدى</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="payment_methods[]" value="applepay"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">Apple Pay</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات الدفع
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
                        <label class="block text-sm font-bold text-slate-700 mb-2">مدة انتهاء الجلسة (دقيقة)</label>
                        <select name="session_lifetime"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="30">30 دقيقة</option>
                            <option value="60" selected>60 دقيقة</option>
                            <option value="120">120 دقيقة</option>
                            <option value="240">240 دقيقة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عدد محاولات تسجيل الدخول</label>
                        <select name="max_login_attempts"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="3">3 محاولات</option>
                            <option value="5" selected>5 محاولات</option>
                            <option value="10">10 محاولات</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-4">إعدادات كلمة المرور</label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="password_require_uppercase" value="1" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">تتطلب أحرف كبيرة</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="password_require_numbers" value="1" checked
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">تتطلب أرقام</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="password_require_symbols" value="1"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">تتطلب رموز خاصة</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-save mr-2"></i>
                        حفظ إعدادات الأمان
                    </button>
                </div>
            </form>
        </div>

        <!-- Backup Settings Tab -->
        <div id="backup-tab" class="tab-content hidden">
            <div class="space-y-6">
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                    <h4 class="text-lg font-bold text-blue-800 mb-4">النسخ الاحتياطي التلقائي</h4>

                    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="section" value="backup">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-blue-700 mb-2">تكرار النسخ الاحتياطي</label>
                                <select name="backup_frequency"
                                        class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="daily" selected>يومي</option>
                                    <option value="weekly">أسبوعي</option>
                                    <option value="monthly">شهري</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-blue-700 mb-2">عدد النسخ المحفوظة</label>
                                <select name="backup_retention"
                                        class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="7" selected>7 نسخ</option>
                                    <option value="14">14 نسخة</option>
                                    <option value="30">30 نسخة</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            حفظ إعدادات النسخ الاحتياطي
                        </button>
                    </form>
                </div>

                <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                    <h4 class="text-lg font-bold text-green-800 mb-4">إجراءات النسخ الاحتياطي</h4>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <button onclick="createBackup()" class="p-4 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors text-center">
                            <i class="fas fa-database text-2xl mb-2"></i>
                            <div>إنشاء نسخة احتياطية</div>
                        </button>

                        <button onclick="downloadBackup()" class="p-4 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors text-center">
                            <i class="fas fa-download text-2xl mb-2"></i>
                            <div>تحميل آخر نسخة</div>
                        </button>

                        <button onclick="restoreBackup()" class="p-4 bg-orange-600 text-white rounded-xl font-semibold hover:bg-orange-700 transition-colors text-center">
                            <i class="fas fa-upload text-2xl mb-2"></i>
                            <div>استعادة نسخة</div>
                        </button>
                    </div>
                </div>

                <!-- Recent Backups -->
                <div class="bg-white rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">النسخ الاحتياطية الأخيرة</h4>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-semibold text-slate-800">نسخة تلقائية - {{ now()->format('Y-m-d H:i') }}</div>
                                <div class="text-sm text-slate-600">الحجم: 25.3 MB</div>
                            </div>
                            <div class="flex space-x-2 space-x-reverse">
                                <button class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition-colors">
                                    تحميل
                                </button>
                                <button class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600 transition-colors">
                                    استعادة
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-semibold text-slate-800">نسخة يدوية - {{ now()->subDay()->format('Y-m-d H:i') }}</div>
                                <div class="text-sm text-slate-600">الحجم: 24.8 MB</div>
                            </div>
                            <div class="flex space-x-2 space-x-reverse">
                                <button class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition-colors">
                                    تحميل
                                </button>
                                <button class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600 transition-colors">
                                    استعادة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching functionality
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'bg-gradient-primary', 'text-white');
            btn.classList.add('text-slate-700', 'hover:bg-gray-100');
        });

        // Show selected tab
        document.getElementById(tabName + '-tab').classList.remove('hidden');

        // Add active class to selected button
        const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
        activeBtn.classList.add('active', 'bg-gradient-primary', 'text-white');
        activeBtn.classList.remove('text-slate-700', 'hover:bg-gray-100');
    }

    // Initialize first tab as active
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('general');
    });

    // Test email function
    function testEmail() {
        if (confirm('هل تريد إرسال بريد تجريبي؟')) {
            fetch('/admin/settings/test-email', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'تم إرسال البريد التجريبي بنجاح');
            })
            .catch(error => {
                alert('حدث خطأ أثناء إرسال البريد التجريبي');
            });
        }
    }

    // Backup functions
    function createBackup() {
        if (confirm('هل تريد إنشاء نسخة احتياطية جديدة؟')) {
            fetch('/admin/settings/backup', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                alert('تم إنشاء النسخة الاحتياطية بنجاح');
                location.reload();
            })
            .catch(error => {
                alert('حدث خطأ أثناء إنشاء النسخة الاحتياطية');
            });
        }
    }

    function downloadBackup() {
        window.open('/admin/settings/backup/download', '_blank');
    }

    function restoreBackup() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.sql,.zip';
        input.onchange = function(e) {
            if (e.target.files.length > 0) {
                if (confirm('تحذير: ستؤدي عملية الاستعادة إلى استبدال جميع البيانات الحالية. هل أنت متأكد؟')) {
                    const formData = new FormData();
                    formData.append('backup_file', e.target.files[0]);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                    fetch('/admin/settings/backup/restore', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('تمت استعادة النسخة الاحتياطية بنجاح');
                        location.reload();
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء استعادة النسخة الاحتياطية');
                    });
                }
            }
        };
        input.click();
    }
</script>

<style>
    .tab-button.active {
        @apply bg-gradient-primary text-white;
    }

    .tab-button:not(.active) {
        @apply text-slate-700 hover:bg-gray-100;
    }
</style>
@endpush
@endsection
