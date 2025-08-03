@extends('layouts.main')

@section('title', 'تسجيل الدخول - LogistiQ')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-secondary-50 to-primary-100"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 bg-primary-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-primary-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-primary-500 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-md mx-auto">
        <!-- Auth Card -->
        <div class="glass rounded-3xl shadow-soft border border-primary-200/50 overflow-hidden animate-scale-in">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-primary-500 to-primary-600 p-8 text-center">
                <div class="absolute inset-0 bg-white/10"></div>
                
                <div class="relative">
                    <!-- Logo -->
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 animate-bounce-soft">
                        <i class="fas fa-truck text-white text-2xl"></i>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-white mb-2">مرحباً بك مجدداً</h1>
                    <p class="text-primary-100">سجل دخولك للوصول إلى حسابك</p>
                </div>
            </div>

            <!-- Form -->
            <div class="p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
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

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" 
                                   type="checkbox" 
                                   name="remember" 
                                   {{ old('remember') ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary-600 bg-white border-secondary-300 rounded focus:ring-primary-500 focus:ring-2">
                            <label for="remember" class="mr-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors cursor-pointer">
                                تذكرني
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-primary-600 hover:text-primary-700 transition-colors hover:underline">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-600 text-white py-3 rounded-xl font-semibold text-lg hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-sign-in-alt ml-2 group-hover:animate-bounce-soft"></i>
                            تسجيل الدخول
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </button>

                    <!-- Register Link -->
                    <div class="text-center pt-4 border-t border-secondary-200">
                        <p class="text-secondary-600 text-sm">
                            لا تملك حساباً؟
                            <a href="{{ route('register') }}" 
                               class="text-primary-600 hover:text-primary-700 font-semibold transition-colors hover:underline">
                                إنشاء حساب جديد
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Social Login (Optional) -->
        <div class="mt-8 text-center">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-secondary-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-secondary-50 text-secondary-500 rounded-full">أو</span>
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-2 gap-3">
                <button class="group flex items-center justify-center px-4 py-3 border border-secondary-300 rounded-xl text-sm font-medium text-secondary-700 bg-white hover:bg-secondary-50 hover:border-primary-300 transition-all duration-300 hover-lift">
                    <i class="fab fa-google text-red-500 ml-2 group-hover:animate-bounce-soft"></i>
                    Google
                </button>
                
                <button class="group flex items-center justify-center px-4 py-3 border border-secondary-300 rounded-xl text-sm font-medium text-secondary-700 bg-white hover:bg-secondary-50 hover:border-primary-300 transition-all duration-300 hover-lift">
                    <i class="fab fa-apple text-secondary-800 ml-2 group-hover:animate-bounce-soft"></i>
                    Apple
                </button>
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
        const inputs = document.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('border-red-500')) {
                    validateField(this);
                }
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
