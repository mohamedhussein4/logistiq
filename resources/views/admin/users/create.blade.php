@extends('layouts.admin')

@section('title', 'إضافة مستخدم جديد')
@section('page-title', 'إضافة مستخدم جديد')
@section('page-description', 'إضافة مستخدم جديد للمنصة')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">إضافة مستخدم جديد</h1>
                <p class="text-slate-600">إضافة مستخدم جديد للمنصة مع تحديد نوع الحساب والصلاحيات</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- User Form -->
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6" id="user-form">
        @csrf

        <!-- Basic Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">المعلومات الأساسية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                           placeholder="أدخل الاسم الكامل">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                           placeholder="user@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone') border-red-500 @enderror"
                           placeholder="+966 50 123 4567">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع المستخدم <span class="text-red-500">*</span></label>
                    <select name="user_type" required onchange="toggleCompanyFields(this.value)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('user_type') border-red-500 @enderror">
                        <option value="">اختر نوع المستخدم</option>
                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>مدير النظام</option>
                        <option value="logistics" {{ old('user_type') == 'logistics' ? 'selected' : '' }}>شركة لوجستية</option>
                        <option value="service_company" {{ old('user_type') == 'service_company' ? 'selected' : '' }}>شركة طالبة للخدمة</option>
                        <option value="regular" {{ old('user_type') == 'regular' ? 'selected' : '' }}>مستخدم عادي</option>
                    </select>
                    @error('user_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Company Information (for companies only) -->
        <div id="company-fields" class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hidden">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اسم الشركة <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('company_name') border-red-500 @enderror"
                           placeholder="اسم الشركة">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">السجل التجاري</label>
                    <input type="text" name="commercial_register" value="{{ old('commercial_register') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('commercial_register') border-red-500 @enderror"
                           placeholder="رقم السجل التجاري">
                    @error('commercial_register')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">العنوان</label>
                <textarea name="address" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('address') border-red-500 @enderror"
                          placeholder="عنوان الشركة الكامل">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">المدينة</label>
                    <select name="city"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('city') border-red-500 @enderror">
                        <option value="">اختر المدينة</option>
                        <option value="riyadh" {{ old('city') == 'riyadh' ? 'selected' : '' }}>الرياض</option>
                        <option value="jeddah" {{ old('city') == 'jeddah' ? 'selected' : '' }}>جدة</option>
                        <option value="dammam" {{ old('city') == 'dammam' ? 'selected' : '' }}>الدمام</option>
                        <option value="mecca" {{ old('city') == 'mecca' ? 'selected' : '' }}>مكة المكرمة</option>
                        <option value="medina" {{ old('city') == 'medina' ? 'selected' : '' }}>المدينة المنورة</option>
                        <option value="khobar" {{ old('city') == 'khobar' ? 'selected' : '' }}>الخبر</option>
                        <option value="tabuk" {{ old('city') == 'tabuk' ? 'selected' : '' }}>تبوك</option>
                        <option value="other" {{ old('city') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الرمز البريدي</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('postal_code') border-red-500 @enderror"
                           placeholder="12345">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Security Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الأمان</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('password') border-red-500 @enderror"
                               placeholder="كلمة مرور قوية" id="password">
                        <button type="button" onclick="togglePassword('password')"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="flex items-center space-x-2 space-x-reverse text-xs">
                            <div class="flex-1 bg-gray-200 rounded-full h-1">
                                <div class="h-1 rounded-full transition-all" id="password-strength"></div>
                            </div>
                            <span id="password-text" class="text-gray-500">ضعيف</span>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('password_confirmation') border-red-500 @enderror"
                               placeholder="إعادة كتابة كلمة المرور" id="password-confirmation">
                        <button type="button" onclick="togglePassword('password-confirmation')"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-confirmation-icon"></i>
                        </button>
                    </div>
                    <div class="mt-2" id="password-match">
                        <!-- Password match indicator -->
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-4">خيارات الأمان</label>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-slate-700">البريد الإلكتروني مُتحقق منه</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="force_password_change" value="1" {{ old('force_password_change') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-slate-700">إجبار المستخدم على تغيير كلمة المرور في أول تسجيل دخول</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="send_welcome_email" value="1" checked
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-slate-700">إرسال بريد ترحيبي للمستخدم</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">حالة الحساب</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">حالة الحساب</label>
                    <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>موقوف</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات المدير</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('admin_notes') border-red-500 @enderror"
                              placeholder="ملاحظات خاصة بالمدير">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- User Type Specific Fields -->
        <div id="logistics-fields" class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hidden">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة اللوجستية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الرصيد المتاح</label>
                    <div class="relative">
                        <input type="number" name="available_balance" value="{{ old('available_balance', 0) }}" step="0.01" min="0"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="0.00">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <span class="text-slate-500 font-semibold">ريال</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">حد الائتمان</label>
                    <div class="relative">
                        <input type="number" name="credit_limit" value="{{ old('credit_limit', 0) }}" step="0.01" min="0"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="0.00">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <span class="text-slate-500 font-semibold">ريال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="service-company-fields" class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hidden">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة الطالبة للخدمة</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي المستحقات</label>
                    <div class="relative">
                        <input type="number" name="total_outstanding" value="{{ old('total_outstanding', 0) }}" step="0.01" min="0"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="0.00">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <span class="text-slate-500 font-semibold">ريال</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي المدفوع</label>
                    <div class="relative">
                        <input type="number" name="total_paid" value="{{ old('total_paid', 0) }}" step="0.01" min="0"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="0.00">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <span class="text-slate-500 font-semibold">ريال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 lg:space-x-reverse">
                <button type="submit" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-save mr-2"></i>
                    إنشاء المستخدم
                </button>

                <button type="button" onclick="generateRandomPassword()" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-warning text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-key mr-2"></i>
                    توليد كلمة مرور
                </button>

                <a href="{{ route('admin.users.index') }}" class="flex-1 lg:flex-none px-8 py-4 bg-gray-200 text-slate-700 rounded-xl font-bold text-lg text-center hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle company fields based on user type
    function toggleCompanyFields(userType) {
        const companyFields = document.getElementById('company-fields');
        const logisticsFields = document.getElementById('logistics-fields');
        const serviceCompanyFields = document.getElementById('service-company-fields');

        // Hide all specific fields first
        companyFields.classList.add('hidden');
        logisticsFields.classList.add('hidden');
        serviceCompanyFields.classList.add('hidden');

        // Show relevant fields based on user type
        if (userType === 'logistics' || userType === 'service_company') {
            companyFields.classList.remove('hidden');

            if (userType === 'logistics') {
                logisticsFields.classList.remove('hidden');
            } else if (userType === 'service_company') {
                serviceCompanyFields.classList.remove('hidden');
            }
        }
    }

    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Check password strength
    function checkPasswordStrength(password) {
        let strength = 0;
        const checks = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            numbers: /\d/.test(password),
            symbols: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        strength = Object.values(checks).filter(Boolean).length;

        const strengthBar = document.getElementById('password-strength');
        const strengthText = document.getElementById('password-text');

        switch (strength) {
            case 0:
            case 1:
                strengthBar.className = 'h-1 rounded-full transition-all bg-red-500';
                strengthBar.style.width = '20%';
                strengthText.textContent = 'ضعيف جداً';
                strengthText.className = 'text-red-500';
                break;
            case 2:
                strengthBar.className = 'h-1 rounded-full transition-all bg-red-400';
                strengthBar.style.width = '40%';
                strengthText.textContent = 'ضعيف';
                strengthText.className = 'text-red-400';
                break;
            case 3:
                strengthBar.className = 'h-1 rounded-full transition-all bg-yellow-500';
                strengthBar.style.width = '60%';
                strengthText.textContent = 'متوسط';
                strengthText.className = 'text-yellow-500';
                break;
            case 4:
                strengthBar.className = 'h-1 rounded-full transition-all bg-blue-500';
                strengthBar.style.width = '80%';
                strengthText.textContent = 'جيد';
                strengthText.className = 'text-blue-500';
                break;
            case 5:
                strengthBar.className = 'h-1 rounded-full transition-all bg-green-500';
                strengthBar.style.width = '100%';
                strengthText.textContent = 'ممتاز';
                strengthText.className = 'text-green-500';
                break;
        }

        return strength;
    }

    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password-confirmation').value;
        const matchIndicator = document.getElementById('password-match');

        if (confirmation.length > 0) {
            if (password === confirmation) {
                matchIndicator.innerHTML = '<span class="text-green-600 text-xs"><i class="fas fa-check mr-1"></i>كلمات المرور متطابقة</span>';
            } else {
                matchIndicator.innerHTML = '<span class="text-red-600 text-xs"><i class="fas fa-times mr-1"></i>كلمات المرور غير متطابقة</span>';
            }
        } else {
            matchIndicator.innerHTML = '';
        }
    }

    // Generate random password
    function generateRandomPassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';

        // Ensure we have at least one from each category
        password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)]; // Uppercase
        password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)]; // Lowercase
        password += '0123456789'[Math.floor(Math.random() * 10)]; // Number
        password += '!@#$%^&*'[Math.floor(Math.random() * 8)]; // Symbol

        // Fill the rest randomly
        for (let i = 4; i < 12; i++) {
            password += chars[Math.floor(Math.random() * chars.length)];
        }

        // Shuffle the password
        password = password.split('').sort(() => Math.random() - 0.5).join('');

        // Set the password and confirmation
        document.getElementById('password').value = password;
        document.getElementById('password-confirmation').value = password;

        // Update strength indicator
        checkPasswordStrength(password);
        checkPasswordMatch();

        alert('تم توليد كلمة مرور قوية وتعبئتها في الحقول');
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmationInput = document.getElementById('password-confirmation');

        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        confirmationInput.addEventListener('input', checkPasswordMatch);

        // Auto-generate email from name and company
        const nameInput = document.querySelector('input[name="name"]');
        const companyInput = document.querySelector('input[name="company_name"]');
        const emailInput = document.querySelector('input[name="email"]');

        function generateEmail() {
            if (nameInput.value && companyInput.value && !emailInput.value) {
                const name = nameInput.value.toLowerCase().replace(/\s+/g, '.');
                const company = companyInput.value.toLowerCase().replace(/\s+/g, '').replace(/[^a-z0-9]/g, '');
                emailInput.value = `${name}@${company}.com`;
            }
        }

        nameInput.addEventListener('blur', generateEmail);
        companyInput.addEventListener('blur', generateEmail);
    });

    // Form validation before submit
    document.getElementById('user-form').addEventListener('submit', function(e) {
        const userType = document.querySelector('select[name="user_type"]').value;
        const companyName = document.querySelector('input[name="company_name"]').value;

        // Check if company fields are required but empty
        if ((userType === 'logistics' || userType === 'service_company') && !companyName.trim()) {
            e.preventDefault();
            alert('يجب إدخال اسم الشركة للشركات اللوجستية والطالبة للخدمة');
            return false;
        }

        // Check password strength
        const password = document.getElementById('password').value;
        const strength = checkPasswordStrength(password);

        if (strength < 3) {
            if (!confirm('كلمة المرور ضعيفة. هل تريد المتابعة؟')) {
                e.preventDefault();
                return false;
            }
        }

        // Check password match
        const confirmation = document.getElementById('password-confirmation').value;
        if (password !== confirmation) {
            e.preventDefault();
            alert('كلمات المرور غير متطابقة');
            return false;
        }
    });
</script>
@endpush
@endsection
