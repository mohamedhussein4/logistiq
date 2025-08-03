@extends('layouts.main')

@section('title', 'إنشاء حساب جديد - LogistiQ')

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

    <div class="relative w-full max-w-lg mx-auto">
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
                    
                    <h1 class="text-3xl font-bold text-white mb-2">انضم إلى LogistiQ</h1>
                    <p class="text-primary-100">أنشئ حسابك وابدأ رحلتك معنا</p>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

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
                               autofocus
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

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        const indicators = ['strength-1', 'strength-2', 'strength-3', 'strength-4'];
        const strengthText = document.getElementById('strength-text');
        
        // Reset indicators
        indicators.forEach(id => {
            document.getElementById(id).className = 'h-1 w-full bg-secondary-200 rounded-full transition-colors duration-300';
        });
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength++;
        
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
        
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            if (confirmPasswordInput.value) {
                checkPasswordMatch();
            }
        });
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
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
@endsection
