@extends('layouts.main')

@section('title', 'تأكيد البريد الإلكتروني - LogistiQ')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-secondary-50 to-primary-100"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 bg-primary-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-emerald-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-primary-500 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-md mx-auto">
        <!-- Email Verification Card -->
        <div class="glass rounded-3xl shadow-soft border border-primary-200/50 overflow-hidden animate-scale-in">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-primary-500 to-emerald-600 p-8 text-center">
                <div class="absolute inset-0 bg-white/10"></div>
                
                <div class="relative">
                    <!-- Email Icon -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-3xl mb-6 animate-bounce-soft">
                        <i class="fas fa-envelope-open text-white text-3xl"></i>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-white mb-2">تأكيد البريد الإلكتروني</h1>
                    <p class="text-primary-100">نحتاج للتأكد من صحة بريدك الإلكتروني</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                @if (session('resent'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl animate-slide-down">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="mr-3">
                                <p class="text-green-800 font-medium">تم الإرسال بنجاح!</p>
                                <p class="text-green-700 text-sm">تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="text-center mb-8">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-2xl mb-4">
                            <i class="fas fa-mail-bulk text-primary-600 text-2xl"></i>
                        </div>
                    </div>
                    
                    <h2 class="text-xl font-bold text-secondary-800 mb-4">تحقق من صندوق البريد</h2>
                    
                    <p class="text-secondary-600 leading-relaxed mb-6">
                        قمنا بإرسال رابط التأكيد إلى بريدك الإلكتروني. يرجى النقر على الرابط لتأكيد حسابك والمتابعة.
                    </p>

                    <!-- Email Tips -->
                    <div class="glass rounded-2xl p-4 mb-6 text-right">
                        <h3 class="font-semibold text-secondary-800 mb-3">نصائح مهمة:</h3>
                        <ul class="space-y-2 text-sm text-secondary-600">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                                تحقق من مجلد "الرسائل غير المرغوب فيها" أو "Spam"
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                                قد يستغرق وصول البريد بضع دقائق
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 ml-2 mt-0.5 text-xs"></i>
                                تأكد من أن بريدك الإلكتروني صحيح
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Resend Form -->
                <div class="text-center">
                    <p class="text-secondary-600 text-sm mb-4">
                        لم تصلك الرسالة؟
                    </p>
                    
                    <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="relative overflow-hidden bg-gradient-to-r from-primary-500 to-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                            <span class="relative z-10 flex items-center justify-center">
                                <i class="fas fa-redo ml-2 group-hover:animate-spin"></i>
                                إرسال رابط جديد
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-emerald-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                        </button>
                    </form>
                </div>

                <!-- Alternative Actions -->
                <div class="mt-8 pt-6 border-t border-secondary-200 space-y-3">
                    <a href="{{ route('login') }}" 
                       class="block w-full text-center py-3 border-2 border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 hover:border-primary-300 transition-all duration-300 font-medium">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة لتسجيل الدخول
                    </a>
                    
                    <a href="{{ route('register') }}" 
                       class="block w-full text-center py-3 text-primary-600 hover:text-primary-700 transition-colors font-medium">
                        تسجيل بحساب مختلف
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 text-center">
            <div class="glass rounded-2xl p-6 border border-primary-200/50">
                <h3 class="font-bold text-secondary-800 mb-2">تحتاج مساعدة؟</h3>
                <p class="text-secondary-600 text-sm mb-4">
                    إذا كنت تواجه مشاكل في تأكيد بريدك الإلكتروني
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="#" class="inline-flex items-center justify-center px-4 py-2 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 transition-colors text-sm font-medium">
                        <i class="fas fa-phone ml-2"></i>
                        اتصل بنا
                    </a>
                    <a href="#" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors text-sm font-medium">
                        <i class="fas fa-chat ml-2"></i>
                        المحادثة المباشرة
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Auto-refresh after resend to check verification status
    let resendButton = document.querySelector('form[action*="verification.resend"] button');
    
    if (resendButton) {
        resendButton.addEventListener('click', function() {
            const originalText = this.innerHTML;
            
            // Show loading state
            this.innerHTML = `
                <span class="relative z-10 flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin ml-2"></i>
                    جاري الإرسال...
                </span>
            `;
            this.disabled = true;
            
            // Reset after form submission
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 3000);
        });
    }

    // Show notification if verification was successful
    if (window.location.hash === '#verified') {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-down';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                تم تأكيد البريد الإلكتروني بنجاح!
            </div>
        `;
        document.body.appendChild(notification);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
</script>
@endpush
@endsection
