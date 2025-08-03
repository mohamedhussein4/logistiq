@extends('layouts.main')

@section('title', 'تأكيد كلمة المرور - LogistiQ')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-secondary-50 to-primary-100"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-40 h-40 bg-blue-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-primary-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-indigo-400 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-md mx-auto">
        <!-- Password Confirm Card -->
        <div class="glass rounded-3xl shadow-soft border border-primary-200/50 overflow-hidden animate-scale-in">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-blue-500 to-primary-600 p-8 text-center">
                <div class="absolute inset-0 bg-white/10"></div>
                
                <div class="relative">
                    <!-- Lock Icon -->
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 animate-bounce-soft">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-white mb-2">تأكيد كلمة المرور</h1>
                    <p class="text-primary-100">للمتابعة يرجى تأكيد كلمة المرور</p>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-4">
                        <i class="fas fa-shield-check text-blue-600 text-2xl"></i>
                    </div>
                    
                    <h2 class="text-xl font-bold text-secondary-800 mb-2">تأكيد هويتك</h2>
                    <p class="text-secondary-600 leading-relaxed text-sm">
                        لأسباب أمنية، يرجى تأكيد كلمة المرور الخاصة بك قبل المتابعة للمنطقة المحمية
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

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
                                   autocomplete="current-password"
                                   autofocus
                                   class="w-full px-4 py-3 pl-12 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('password') border-red-500 @else border-secondary-200 @enderror" 
                                   placeholder="أدخل كلمة المرور">
                            
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

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full relative overflow-hidden bg-gradient-to-r from-blue-500 to-primary-600 text-white py-3 rounded-xl font-semibold text-lg hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-check ml-2 group-hover:animate-bounce-soft"></i>
                            تأكيد كلمة المرور
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </button>

                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                        <div class="text-center pt-4 border-t border-secondary-200">
                            <p class="text-secondary-600 text-sm mb-3">
                                نسيت كلمة المرور؟
                            </p>
                            <a href="{{ route('password.request') }}" 
                               class="inline-flex items-center justify-center px-6 py-2 border-2 border-primary-200 text-primary-600 rounded-xl hover:bg-primary-50 hover:border-primary-300 transition-all duration-300 font-medium text-sm">
                                <i class="fas fa-key ml-2"></i>
                                إعادة تعيين كلمة المرور
                            </a>
                        </div>
                    @endif

                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" 
                           class="text-secondary-600 hover:text-primary-600 transition-colors text-sm">
                            العودة لتسجيل الدخول
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-8 grid grid-cols-1 gap-4">
            <!-- Why Confirm -->
            <div class="glass rounded-2xl p-4 border border-blue-200/50">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-secondary-800 text-sm mb-1">لماذا نطلب التأكيد؟</h3>
                        <p class="text-xs text-secondary-600">نحرص على حماية حسابك من الوصول غير المصرح به للمناطق الحساسة</p>
                    </div>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="glass rounded-2xl p-4 border border-primary-200/50">
                <h3 class="font-semibold text-secondary-800 mb-3 text-sm">نصائح أمنية:</h3>
                <ul class="space-y-2 text-xs text-secondary-600">
                    <li class="flex items-start">
                        <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                        تأكد من أنك في مكان آمن عند إدخال كلمة المرور
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                        لا تحفظ كلمة المرور في متصفحات عامة
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                        قم بتسجيل الخروج عند الانتهاء
                    </li>
                </ul>
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
        const passwordInput = document.getElementById('password');
        const submitButton = document.querySelector('button[type="submit"]');
        
        passwordInput.addEventListener('blur', function() {
            validateField(this);
        });
        
        passwordInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                validateField(this);
            }
        });

        // Loading state on form submit
        submitButton.closest('form').addEventListener('submit', function() {
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = `
                <span class="relative z-10 flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin ml-2"></i>
                    جاري التحقق...
                </span>
            `;
            submitButton.disabled = true;
        });

        // Auto-focus on password field
        passwordInput.focus();
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
        } else {
            field.classList.remove('border-red-500');
            field.classList.add('border-secondary-200');
        }
    }

    // Add keyboard shortcut for form submission (Enter)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            const form = document.querySelector('form');
            if (form && document.activeElement.tagName === 'INPUT') {
                e.preventDefault();
                form.submit();
            }
        }
    });
</script>
@endpush
@endsection
