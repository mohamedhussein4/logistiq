@extends('layouts.main')

@section('title', 'إعادة تعيين كلمة المرور - LogistiQ')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-secondary-50 to-primary-100"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-40 h-40 bg-orange-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-primary-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-yellow-400 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-md mx-auto">
        <!-- Password Reset Card -->
        <div class="glass rounded-3xl shadow-soft border border-primary-200/50 overflow-hidden animate-scale-in">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-orange-500 to-primary-600 p-8 text-center">
                <div class="absolute inset-0 bg-white/10"></div>
                
                <div class="relative">
                    <!-- Lock Icon -->
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 animate-bounce-soft">
                        <i class="fas fa-key text-white text-2xl"></i>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-white mb-2">نسيت كلمة المرور؟</h1>
                    <p class="text-primary-100">لا تقلق، سنساعدك في إعادة تعيينها</p>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl animate-slide-down">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-green-800 font-medium">تم الإرسال بنجاح!</p>
                                <p class="text-green-700 text-sm">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-2xl mb-4">
                        <i class="fas fa-envelope text-orange-600 text-2xl"></i>
                    </div>
                    
                    <p class="text-secondary-600 leading-relaxed">
                        أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور
                    </p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

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
                               autofocus
                               class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('email') border-red-500 @else border-secondary-200 @enderror" 
                               placeholder="أدخل بريدك الإلكتروني">

                        @error('email')
                            <div class="mt-2 text-red-600 text-sm flex items-center animate-slide-down">
                                <i class="fas fa-exclamation-circle ml-2"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full relative overflow-hidden bg-gradient-to-r from-orange-500 to-primary-600 text-white py-3 rounded-xl font-semibold text-lg hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-paper-plane ml-2 group-hover:animate-bounce-soft"></i>
                            إرسال رابط الإعادة
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center pt-4 border-t border-secondary-200">
                        <p class="text-secondary-600 text-sm">
                            تذكرت كلمة المرور؟
                            <a href="{{ route('login') }}" 
                               class="text-primary-600 hover:text-primary-700 font-semibold transition-colors hover:underline">
                                تسجيل الدخول
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="mt-8 grid grid-cols-1 gap-4">
            <!-- Security Info -->
            <div class="glass rounded-2xl p-4 border border-orange-200/50">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shield-alt text-orange-600"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-secondary-800 text-sm mb-1">آمن ومحمي</h3>
                        <p class="text-xs text-secondary-600">رابط إعادة التعيين صالح لمدة ساعة واحدة فقط لضمان الأمان</p>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="glass rounded-2xl p-4 border border-primary-200/50">
                <h3 class="font-semibold text-secondary-800 mb-3 text-sm">نصائح مهمة:</h3>
                <ul class="space-y-2 text-xs text-secondary-600">
                    <li class="flex items-start">
                        <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                        تحقق من مجلد "الرسائل غير المرغوب فيها"
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                        استخدم كلمة مرور قوية تحتوي على أحرف وأرقام
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                        لا تشارك رابط إعادة التعيين مع أحد
                    </li>
                </ul>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 text-center">
            <div class="glass rounded-2xl p-6 border border-primary-200/50">
                <h3 class="font-bold text-secondary-800 mb-2">تحتاج مساعدة؟</h3>
                <p class="text-secondary-600 text-sm mb-4">
                    لا تتذكر البريد الإلكتروني المسجل أو تواجه مشاكل؟
                </p>
                
                <a href="#contact-section" 
                   onclick="window.location.href='{{ route('home') }}#contact-section'"
                   class="inline-flex items-center justify-center px-6 py-2 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 transition-colors text-sm font-medium">
                    <i class="fas fa-headset ml-2"></i>
                    تواصل مع الدعم الفني
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Enhanced form validation
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const submitButton = document.querySelector('button[type="submit"]');
        
        emailInput.addEventListener('blur', function() {
            validateEmail(this);
        });
        
        emailInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                validateEmail(this);
            }
        });

        // Loading state on form submit
        submitButton.closest('form').addEventListener('submit', function() {
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = `
                <span class="relative z-10 flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin ml-2"></i>
                    جاري الإرسال...
                </span>
            `;
            submitButton.disabled = true;
        });
    });

    function validateEmail(field) {
        const value = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!value) {
            field.classList.add('border-red-500');
            field.classList.remove('border-secondary-200');
            
            // Add shake animation
            field.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                field.style.animation = '';
            }, 500);
        } else if (!emailRegex.test(value)) {
            field.classList.add('border-red-500');
            field.classList.remove('border-secondary-200');
        } else {
            field.classList.remove('border-red-500');
            field.classList.add('border-secondary-200');
        }
    }
</script>
@endpush
@endsection
