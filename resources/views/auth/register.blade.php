@extends('layouts.main')

@section('title', 'تسجيل حساب جديد - لوجستيك')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-secondary-50 to-primary-100"></div>

    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-40 h-40 bg-emerald-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-primary-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-purple-400 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-4xl mx-auto">
        <!-- Auth Card -->
        <div class="glass rounded-3xl shadow-soft border border-primary-200/50 overflow-hidden animate-scale-in">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-emerald-500 to-primary-600 p-8 text-center">
                <div class="absolute inset-0 bg-white/10"></div>

                <div class="relative">
                    <!-- Logo -->
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 animate-bounce-soft">
                        <i class="fas fa-user-plus text-white text-2xl"></i>
                    </div>

                    <h1 class="text-3xl font-bold text-white mb-2">انضم إلى لوجستيك</h1>
                    <p class="text-primary-100">أنشئ حسابك وابدأ رحلتك معنا</p>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- نوع المستخدم -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-secondary-700 mb-4 group-focus-within:text-primary-600 transition-colors">
                            <i class="fas fa-users ml-2"></i>
                            نوع الحساب
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="relative flex cursor-pointer rounded-xl border border-secondary-200 bg-white/80 p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:border-primary-300 hover:-translate-y-1">
                                <input type="radio" name="user_type" value="logistics" class="sr-only" required>
                                <span class="flex flex-1 flex-col items-center text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fas fa-truck text-white text-2xl"></i>
                                    </div>
                                    <span class="block text-lg font-bold text-secondary-800 mb-2">شركة لوجستية</span>
                                    <span class="text-sm text-secondary-600 leading-relaxed">
                                        طلب تمويل وخدمات لوجستية متقدمة
                                    </span>
                                </span>
                                <span class="pointer-events-none absolute -inset-px rounded-xl border-2 opacity-0" aria-hidden="true"></span>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border border-secondary-200 bg-white/80 p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:border-emerald-300 hover:-translate-y-1">
                                <input type="radio" name="user_type" value="regular" class="sr-only" required checked {{ old('user_type') == 'logistics' ? '' : 'checked' }}>
                                <span class="flex flex-1 flex-col items-center text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fas fa-user text-white text-2xl"></i>
                                    </div>
                                    <span class="block text-lg font-bold text-secondary-800 mb-2">مستخدم عادي</span>
                                    <span class="text-sm text-secondary-600 leading-relaxed">
                                        تسوق من المتجر والاستفادة من الخدمات
                                    </span>
                                </span>
                                <span class="pointer-events-none absolute -inset-px rounded-xl border-2 opacity-0" aria-hidden="true"></span>
                            </label>
                        </div>

                        <!-- ملاحظة للشركات الطالبة للخدمة -->
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-amber-600 mt-1 ml-2"></i>
                                <div class="text-amber-800">
                                    <p class="font-semibold mb-1">للشركات الطالبة للخدمة</p>
                                    <p class="text-sm">يتم التسجيل عن طريق دعوة خاصة من الإدارة. للاستفسار تواصل معنا</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- البيانات الأساسية -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div class="group">
                            <label for="name" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                <i class="fas fa-user ml-2"></i>
                                الاسم الكامل
                            </label>
                            <input id="name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autocomplete="name"
                                   class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('name') border-red-500 @else border-secondary-200 @enderror"
                                   placeholder="أدخل اسمك الكامل">

                            @error('name')
                                <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                    <i class="fas fa-exclamation-circle ml-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="group">
                            <label for="email" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                <i class="fas fa-envelope ml-2"></i>
                                البريد الإلكتروني
                            </label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('email') border-red-500 @else border-secondary-200 @enderror"
                                   placeholder="أدخل بريدك الإلكتروني">

                            @error('email')
                                <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                    <i class="fas fa-exclamation-circle ml-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="group">
                            <label for="phone" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                <i class="fas fa-phone ml-2"></i>
                                رقم الهاتف
                            </label>
                            <input id="phone"
                                   type="tel"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   required
                                   class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('phone') border-red-500 @else border-secondary-200 @enderror"
                                   placeholder="05xxxxxxxx">

                            @error('phone')
                                <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                    <i class="fas fa-exclamation-circle ml-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="group">
                            <label for="password" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                <i class="fas fa-lock ml-2"></i>
                                كلمة المرور
                            </label>
                            <div class="relative">
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       class="w-full px-4 py-3 pl-12 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('password') border-red-500 @else border-secondary-200 @enderror"
                                       placeholder="أدخل كلمة مرور قوية">

                                <button type="button"
                                        onclick="togglePassword('password')"
                                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-400 hover:text-primary-600 transition-colors">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                    <i class="fas fa-exclamation-circle ml-2"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="group">
                            <label for="password-confirm" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                <i class="fas fa-lock ml-2"></i>
                                تأكيد كلمة المرور
                            </label>
                            <div class="relative">
                                <input id="password-confirm"
                                       type="password"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       class="w-full px-4 py-3 pl-12 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80"
                                       placeholder="أعد إدخال كلمة المرور">

                                <button type="button"
                                        onclick="togglePassword('password-confirm')"
                                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-400 hover:text-primary-600 transition-colors">
                                    <i class="fas fa-eye" id="password-confirm-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- بيانات الشركة اللوجستية (تظهر عند اختيار شركة لوجستية) -->
                    <div id="logistics-fields" class="space-y-6" style="display: none;">
                        <div class="border-t border-secondary-200 pt-6">
                            <h3 class="text-xl font-semibold text-secondary-800 mb-6 flex items-center">
                                <i class="fas fa-building text-primary-600 ml-2"></i>
                                بيانات الشركة اللوجستية
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label for="company_name" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-building ml-2"></i>
                                    اسم الشركة
                                </label>
                                <input id="company_name"
                                       type="text"
                                       name="company_name"
                                       value="{{ old('company_name') }}"
                                       class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('company_name') border-red-500 @else border-secondary-200 @enderror"
                                       placeholder="اسم الشركة اللوجستية">

                                @error('company_name')
                                    <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                        <i class="fas fa-exclamation-circle ml-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="company_license" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-certificate ml-2"></i>
                                    رقم الترخيص التجاري
                                </label>
                                <input id="company_license"
                                       type="text"
                                       name="company_license"
                                       value="{{ old('company_license') }}"
                                       class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('company_license') border-red-500 @else border-secondary-200 @enderror"
                                       placeholder="رقم الترخيص التجاري">

                                @error('company_license')
                                    <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                        <i class="fas fa-exclamation-circle ml-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group md:col-span-2">
                                <label for="company_address" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-map-marker-alt ml-2"></i>
                                    عنوان الشركة
                                </label>
                                <textarea id="company_address"
                                          name="company_address"
                                          rows="3"
                                          class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('company_address') border-red-500 @else border-secondary-200 @enderror"
                                          placeholder="العنوان التفصيلي للشركة">{{ old('company_address') }}</textarea>

                                @error('company_address')
                                    <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                        <i class="fas fa-exclamation-circle ml-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="contact_person" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-user-tie ml-2"></i>
                                    الشخص المسؤول
                                </label>
                                <input id="contact_person"
                                       type="text"
                                       name="contact_person"
                                       value="{{ old('contact_person') }}"
                                       class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('contact_person') border-red-500 @else border-secondary-200 @enderror"
                                       placeholder="اسم الشخص المسؤول عن التواصل">

                                @error('contact_person')
                                    <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                        <i class="fas fa-exclamation-circle ml-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="company_type" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-industry ml-2"></i>
                                    نوع النشاط اللوجيستي
                                </label>
                                <select id="company_type"
                                        name="company_type"
                                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('company_type') border-red-500 @else border-secondary-200 @enderror">
                                    <option value="">اختر نوع النشاط</option>
                                    <option value="transport" {{ old('company_type') == 'transport' ? 'selected' : '' }}>شحن وتوصيل</option>
                                    <option value="warehouse" {{ old('company_type') == 'warehouse' ? 'selected' : '' }}>مستودعات وتخزين</option>
                                    <option value="freight" {{ old('company_type') == 'freight' ? 'selected' : '' }}>شحن بحري وجوي</option>
                                    <option value="mixed" {{ old('company_type') == 'mixed' ? 'selected' : '' }}>خدمات متنوعة</option>
                                </select>

                                @error('company_type')
                                    <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                        <i class="fas fa-exclamation-circle ml-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- رسائل الخطأ العامة -->
                    @error('user_type')
                        <div class="text-red-600 text-sm flex items-center animate-slide-down">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Terms & Conditions -->
                    <div class="flex items-start space-x-3 space-x-reverse">
                        <input id="terms"
                               type="checkbox"
                               required
                               class="w-4 h-4 text-primary-600 bg-white border-secondary-300 rounded focus:ring-primary-500 focus:ring-2 mt-0.5">
                        <label for="terms" class="text-sm text-secondary-600 cursor-pointer">
                            أوافق على
                            <a href="#" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline">شروط الخدمة</a>
                            و
                            <a href="#" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline">سياسة الخصوصية</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full relative overflow-hidden bg-gradient-to-r from-emerald-500 to-primary-600 text-white py-3 rounded-xl font-semibold text-lg hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-user-plus ml-2 group-hover:animate-bounce-soft"></i>
                            إنشاء الحساب
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </button>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-secondary-200">
                        <p class="text-secondary-600 text-sm">
                            تملك حساباً بالفعل؟
                            <a href="{{ route('login') }}"
                               class="text-primary-600 hover:text-primary-700 font-semibold transition-colors hover:underline">
                                تسجيل الدخول
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Features -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass rounded-2xl p-4 text-center border border-primary-200/50">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-shield-check text-primary-600"></i>
                </div>
                <h3 class="font-semibold text-secondary-800 text-sm mb-1">آمن ومحمي</h3>
                <p class="text-xs text-secondary-600">بياناتك محمية بأعلى معايير الأمان</p>
            </div>

            <div class="glass rounded-2xl p-4 text-center border border-emerald-200/50">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-bolt text-emerald-600"></i>
                </div>
                <h3 class="font-semibold text-secondary-800 text-sm mb-1">سريع وسهل</h3>
                <p class="text-xs text-secondary-600">تسجيل سريع في دقائق معدودة</p>
            </div>

            <div class="glass rounded-2xl p-4 text-center border border-purple-200/50">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-headset text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-secondary-800 text-sm mb-1">دعم مستمر</h3>
                <p class="text-xs text-secondary-600">فريق دعم متاح ٢٤/٧ لمساعدتك</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(inputId + '-eye');

        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    }

    // Enhanced form validation
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
        const logisticsFields = document.getElementById('logistics-fields');
        const logisticsInputs = logisticsFields.querySelectorAll('input, textarea');

        function toggleLogisticsFields() {
            const selectedType = document.querySelector('input[name="user_type"]:checked');

            if (selectedType && selectedType.value === 'logistics') {
                logisticsFields.style.display = 'block';
                logisticsInputs.forEach(input => {
                    input.required = true;
                });
            } else {
                logisticsFields.style.display = 'none';
                logisticsInputs.forEach(input => {
                    input.required = false;
                });
            }
        }

        // إضافة event listeners للراديو buttons
        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', toggleLogisticsFields);
        });

        // تشغيل الدالة عند تحميل الصفحة
        toggleLogisticsFields();

        // إضافة تأثيرات بصرية للراديو buttons
        userTypeRadios.forEach(radio => {
            const label = radio.closest('label');

            radio.addEventListener('change', function() {
                // إزالة التحديد من جميع labels
                document.querySelectorAll('input[name="user_type"]').forEach(r => {
                    r.closest('label').classList.remove('ring-2', 'ring-primary-500', 'border-primary-500');
                    r.closest('label').classList.add('border-secondary-200');
                });

                // إضافة التحديد للـ label المختار
                if (this.checked) {
                    label.classList.add('ring-2', 'ring-primary-500', 'border-primary-500');
                    label.classList.remove('border-secondary-200');
                }
            });
        });

        const inputs = document.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });

    function validateField(field) {
        const value = field.value.trim();

        if (!value) {
            field.classList.add('border-red-500');
            field.classList.remove('border-secondary-200');

            // Add shake animation
            field.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                field.style.animation = '';
            }, 500);
        } else if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('border-red-500');
                field.classList.remove('border-secondary-200');
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-secondary-200');
            }
        } else {
            field.classList.remove('border-red-500');
            field.classList.add('border-secondary-200');
        }
    }
</script>
@endpush
@push('styles')
<style>
    .radio-card input[type="radio"]:checked + span .pointer-events-none {
        border-color: #0ea5e9;
        border-width: 2px;
        opacity: 1;
    }

    .radio-card input[type="radio"]:checked + span {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-color: #0ea5e9;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.2);
    }

    .radio-card:hover {
        transform: translateY(-2px);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .gradient-border {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2px;
        border-radius: 0.75rem;
    }

    .gradient-border-inner {
        background: white;
        border-radius: 0.625rem;
    }
</style>
@endpush

@endsection
