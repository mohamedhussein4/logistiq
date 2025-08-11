@extends('layouts.main')

@section('title', 'تعيين كلمة مرور جديدة - Link2u')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-secondary-50 to-primary-100"></div>

    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 bg-green-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-primary-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-emerald-400 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-lg mx-auto">
        <!-- Password Reset Card -->
        <div class="glass rounded-3xl shadow-soft border border-primary-200/50 overflow-hidden animate-scale-in">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-green-500 to-primary-600 p-8 text-center">
                <div class="absolute inset-0 bg-white/10"></div>

                <div class="relative">
                    <!-- Shield Icon -->
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 animate-bounce-soft">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>

                    <h1 class="text-3xl font-bold text-white mb-2">كلمة مرور جديدة</h1>
                    <p class="text-primary-100">قم بتعيين كلمة مرور قوية لحسابك</p>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-4">
                        <i class="fas fa-lock text-green-600 text-2xl"></i>
                    </div>

                    <p class="text-secondary-600 leading-relaxed">
                        اختر كلمة مرور قوية وآمنة لحماية حسابك
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                            <i class="fas fa-envelope ml-2"></i>
                            البريد الإلكتروني
                        </label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ $email ?? old('email') }}"
                               required
                               autocomplete="email"
                               autofocus
                               class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('email') border-red-500 @else border-secondary-200 @enderror"
                               placeholder="تأكيد البريد الإلكتروني">

                        @error('email')
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
                            كلمة المرور الجديدة
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

                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex space-x-1 space-x-reverse">
                                <div id="strength-1" class="h-1 w-full bg-secondary-200 rounded-full transition-colors duration-300"></div>
                                <div id="strength-2" class="h-1 w-full bg-secondary-200 rounded-full transition-colors duration-300"></div>
                                <div id="strength-3" class="h-1 w-full bg-secondary-200 rounded-full transition-colors duration-300"></div>
                                <div id="strength-4" class="h-1 w-full bg-secondary-200 rounded-full transition-colors duration-300"></div>
                            </div>
                            <p id="strength-text" class="text-xs text-secondary-500 mt-1">قوة كلمة المرور</p>
                        </div>
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
                        <div id="password-match" class="mt-1 text-xs hidden">
                            <span id="match-text"></span>
                        </div>
                    </div>

                    <!-- Security Requirements -->
                    <div class="glass rounded-2xl p-4 bg-green-50/50">
                        <h3 class="font-semibold text-secondary-800 mb-3 text-sm">متطلبات كلمة المرور الآمنة:</h3>
                        <ul class="space-y-2 text-xs text-secondary-600">
                            <li id="req-length" class="flex items-center">
                                <i class="fas fa-circle text-secondary-300 ml-2 text-xs"></i>
                                <span>على الأقل 8 أحرف</span>
                            </li>
                            <li id="req-lowercase" class="flex items-center">
                                <i class="fas fa-circle text-secondary-300 ml-2 text-xs"></i>
                                <span>حرف صغير واحد على الأقل (a-z)</span>
                            </li>
                            <li id="req-uppercase" class="flex items-center">
                                <i class="fas fa-circle text-secondary-300 ml-2 text-xs"></i>
                                <span>حرف كبير واحد على الأقل (A-Z)</span>
                            </li>
                            <li id="req-number" class="flex items-center">
                                <i class="fas fa-circle text-secondary-300 ml-2 text-xs"></i>
                                <span>رقم واحد على الأقل (0-9)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full relative overflow-hidden bg-gradient-to-r from-green-500 to-primary-600 text-white py-3 rounded-xl font-semibold text-lg hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-check ml-2 group-hover:animate-bounce-soft"></i>
                            تحديث كلمة المرور
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center pt-4 border-t border-secondary-200">
                        <p class="text-secondary-600 text-sm">
                            تم تحديث كلمة المرور؟
                            <a href="{{ route('login') }}"
                               class="text-primary-600 hover:text-primary-700 font-semibold transition-colors hover:underline">
                                تسجيل الدخول الآن
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="mt-8 grid grid-cols-1 gap-4">
            <div class="glass rounded-2xl p-4 border border-green-200/50">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shield-check text-green-600"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-secondary-800 text-sm mb-1">نصائح للأمان</h3>
                        <p class="text-xs text-secondary-600">لا تشارك كلمة المرور مع أحد واحفظها في مكان آمن</p>
                    </div>
                </div>
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

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        const indicators = ['strength-1', 'strength-2', 'strength-3', 'strength-4'];
        const strengthText = document.getElementById('strength-text');
        const requirements = {
            length: document.getElementById('req-length'),
            lowercase: document.getElementById('req-lowercase'),
            uppercase: document.getElementById('req-uppercase'),
            number: document.getElementById('req-number')
        };

        // Reset indicators and requirements
        indicators.forEach(id => {
            document.getElementById(id).className = 'h-1 w-full bg-secondary-200 rounded-full transition-colors duration-300';
        });

        Object.values(requirements).forEach(req => {
            req.querySelector('i').className = 'fas fa-circle text-secondary-300 ml-2 text-xs';
        });

        // Check requirements
        if (password.length >= 8) {
            strength++;
            requirements.length.querySelector('i').className = 'fas fa-check text-green-600 ml-2 text-xs';
        }
        if (/[a-z]/.test(password)) {
            strength++;
            requirements.lowercase.querySelector('i').className = 'fas fa-check text-green-600 ml-2 text-xs';
        }
        if (/[A-Z]/.test(password)) {
            strength++;
            requirements.uppercase.querySelector('i').className = 'fas fa-check text-green-600 ml-2 text-xs';
        }
        if (/[0-9]/.test(password)) {
            strength++;
            requirements.number.querySelector('i').className = 'fas fa-check text-green-600 ml-2 text-xs';
        }

        // Update strength indicators
        for (let i = 0; i < strength; i++) {
            const indicator = document.getElementById(indicators[i]);
            if (i === 0) indicator.className = 'h-1 w-full bg-red-500 rounded-full transition-colors duration-300';
            else if (i === 1) indicator.className = 'h-1 w-full bg-yellow-500 rounded-full transition-colors duration-300';
            else if (i === 2) indicator.className = 'h-1 w-full bg-blue-500 rounded-full transition-colors duration-300';
            else indicator.className = 'h-1 w-full bg-green-500 rounded-full transition-colors duration-300';
        }

        const strengthLabels = ['ضعيفة جداً', 'ضعيفة', 'متوسطة', 'قوية'];
        const strengthColors = ['text-red-600', 'text-yellow-600', 'text-blue-600', 'text-green-600'];

        if (password.length > 0) {
            strengthText.textContent = strengthLabels[strength - 1] || 'ضعيفة جداً';
            strengthText.className = `text-xs mt-1 ${strengthColors[strength - 1] || 'text-red-600'}`;
        } else {
            strengthText.textContent = 'قوة كلمة المرور';
            strengthText.className = 'text-xs text-secondary-500 mt-1';
        }
    }

    // Password confirmation checker
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password-confirm').value;
        const matchDiv = document.getElementById('password-match');
        const matchText = document.getElementById('match-text');

        if (confirmPassword.length > 0) {
            matchDiv.classList.remove('hidden');
            if (password === confirmPassword) {
                matchText.innerHTML = '<i class="fas fa-check text-green-600 ml-1"></i><span class="text-green-600">كلمات المرور متطابقة</span>';
            } else {
                matchText.innerHTML = '<i class="fas fa-times text-red-600 ml-1"></i><span class="text-red-600">كلمات المرور غير متطابقة</span>';
            }
        } else {
            matchDiv.classList.add('hidden');
        }
    }

    // Enhanced form validation
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password-confirm');
        const submitButton = document.querySelector('button[type="submit"]');

        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            if (confirmPasswordInput.value) {
                checkPasswordMatch();
            }
        });

        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Loading state on form submit
        submitButton.closest('form').addEventListener('submit', function() {
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = `
                <span class="relative z-10 flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin ml-2"></i>
                    جاري التحديث...
                </span>
            `;
            submitButton.disabled = true;
        });
    });
</script>
@endpush
@endsection
