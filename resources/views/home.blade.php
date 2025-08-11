@extends('layouts.main')

@section('title', 'Link2u - حلول التمويل اللوجستية المتقدمة')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background with animated gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200"></div>

    <!-- Animated background shapes -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-soft"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-primary-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-soft" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-float"></div>
    </div>

    <div class="relative container mx-auto px-4 py-20">
        <div class="max-w-5xl mx-auto text-center">
            <!-- Logo animation -->
            <div class="mb-8 animate-bounce-soft">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-glow animate-float">
                    <i class="fas fa-truck text-white text-3xl"></i>
                </div>
            </div>

            <h1 class="text-4xl md:text-7xl font-bold mb-8 animate-slide-up">
                <span class="gradient-text">منصة الربط</span>
                <br>
                <span class="text-secondary-800">اللوجستية الذكية</span>
            </h1>

            <p class="text-xl md:text-2xl mb-12 text-secondary-600 leading-relaxed max-w-3xl mx-auto animate-slide-up" style="animation-delay: 0.1s;">
                نربط الشركات اللوجستية بشركات التمويل المرخصة والشركات الطالبة للخدمة<br>
                <span class="text-primary-600 font-semibold">منصة واحدة لجميع احتياجاتك اللوجستية والمالية</span>
            </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style="animation-delay: 0.2s;">
                <a href="#contact-section" onclick="scrollToContact(event)" class="group relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-600 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-glow hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <span class="relative z-10 flex items-center">
                        <i class="fas fa-comments ml-3"></i>
                        تواصل معنا الآن
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </a>

                <a href="#how-it-works" onclick="scrollToSection(event, 'how-it-works')" class="group bg-white/80 backdrop-blur-sm text-primary-600 border-2 border-primary-200 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-primary-50 hover:border-primary-300 transition-all duration-300 hover:scale-105 shadow-soft">
                    <i class="fas fa-play ml-3 group-hover:animate-bounce-soft"></i>
                    شاهد كيف نعمل
                </a>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="glass rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold gradient-text mb-2">٢٠٠+</div>
                    <div class="text-secondary-600 text-sm">شركة مربوطة</div>
                </div>
                <div class="glass rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold gradient-text mb-2">٥٠+</div>
                    <div class="text-secondary-600 text-sm">شركة تمويل مرخصة</div>
                </div>
                <div class="glass rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold gradient-text mb-2">٩٥%</div>
                    <div class="text-secondary-600 text-sm">معدل نجاح الربط</div>
                </div>
                <div class="glass rounded-2xl p-6 hover-lift">
                    <div class="text-3xl font-bold gradient-text mb-2">٢٤/٧</div>
                    <div class="text-secondary-600 text-sm">دعم مستمر</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 relative overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-32 h-32 bg-primary-400 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-primary-300 rounded-full"></div>
        <div class="absolute top-1/2 left-1/4 w-20 h-20 bg-primary-500 rounded-full"></div>
    </div>

    <div class="container mx-auto px-4 relative">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16 animate-slide-up">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl mb-6">
                    <i class="fas fa-building text-primary-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    <span class="gradient-text">من نحن؟</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full mx-auto mb-8"></div>
                <p class="text-xl text-secondary-600 leading-relaxed max-w-4xl mx-auto">
                    نحن منصة ربط ذكية متخصصة في ربط الشركات اللوجستية بشركات التمويل المرخصة والشركات الطالبة للخدمة. نعمل كوسيط تقني موثوق لتسهيل العمليات التجارية والمالية، ونوفر أيضاً حلول أجهزة التتبع المتقدمة لجميع احتياجات النقل واللوجستيات.
                </p>
            </div>

                        <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.1s;">
                    <div class="glass rounded-3xl p-8 h-full text-center border border-primary-200/50 group-hover:border-primary-300 transition-all duration-300">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 transition-opacity duration-300"></div>
                            <div class="relative bg-gradient-to-br from-primary-500 to-primary-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto shadow-glow group-hover:scale-110 transition-all duration-300">
                                <i class="fas fa-handshake text-white text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-primary-600 transition-colors">منصة ربط موثوقة</h3>
                        <p class="text-secondary-600 leading-relaxed">نربط الشركات اللوجستية بأفضل شركات التمويل المرخصة في المملكة مع ضمان الشفافية والمصداقية</p>

                        <div class="mt-6 pt-6 border-t border-primary-100">
                            <div class="flex items-center justify-center space-x-4 space-x-reverse text-sm text-primary-600">
                                <div class="flex items-center">
                                    <i class="fas fa-link ml-2"></i>
                                    <span>ربط آمن ومضمون</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.2s;">
                    <div class="glass rounded-3xl p-8 h-full text-center border border-emerald-200/50 group-hover:border-emerald-300 transition-all duration-300">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 transition-opacity duration-300"></div>
                            <div class="relative bg-gradient-to-br from-emerald-500 to-emerald-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto shadow-glow group-hover:scale-110 transition-all duration-300">
                                <i class="fas fa-rocket text-white text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-emerald-600 transition-colors">ربط سريع وفعال</h3>
                        <p class="text-secondary-600 leading-relaxed">نربط الشركات اللوجستية بأنسب شركات التمويل خلال وقت قياسي لضمان سرعة الحصول على الخدمات المطلوبة</p>

                        <div class="mt-6 pt-6 border-t border-emerald-100">
                            <div class="flex items-center justify-center space-x-4 space-x-reverse text-sm text-emerald-600">
                                <div class="flex items-center">
                                    <i class="fas fa-bolt ml-2"></i>
                                    <span>ربط فوري</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.3s;">
                    <div class="glass rounded-3xl p-8 h-full text-center border border-purple-200/50 group-hover:border-purple-300 transition-all duration-300">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 duration-300"></div>
                            <div class="relative bg-gradient-to-br from-purple-500 to-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto shadow-glow group-hover:scale-110 transition-all duration-300">
                                <i class="fas fa-chart-line text-white text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-purple-600 transition-colors">شبكة متنوعة ومتنامية</h3>
                        <p class="text-secondary-600 leading-relaxed">نوسع شبكتنا باستمرار لتشمل المزيد من شركات التمويل المرخصة وحلول التقنية المتطورة</p>

                        <div class="mt-6 pt-6 border-t border-purple-100">
                            <div class="flex items-center justify-center space-x-4 space-x-reverse text-sm text-purple-600">
                                <div class="flex items-center">
                                    <i class="fas fa-network-wired ml-2"></i>
                                    <span>شبكة متنامية</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="mt-20 glass rounded-3xl p-8 animate-slide-up" style="animation-delay: 0.4s;">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="group hover-lift">
                        <div class="text-4xl font-bold gradient-text mb-2 group-hover:scale-110 transition-transform">٥+</div>
                        <div class="text-secondary-600 text-sm">سنوات في الربط</div>
                    </div>
                    <div class="group hover-lift">
                        <div class="text-4xl font-bold gradient-text mb-2 group-hover:scale-110 transition-transform">٣٠+</div>
                        <div class="text-secondary-600 text-sm">خبير متخصص</div>
                    </div>
                    <div class="group hover-lift">
                        <div class="text-4xl font-bold gradient-text mb-2 group-hover:scale-110 transition-transform">٩٧%</div>
                        <div class="text-secondary-600 text-sm">نجاح عمليات الربط</div>
                    </div>
                    <div class="group hover-lift">
                        <div class="text-4xl font-bold gradient-text mb-2 group-hover:scale-110 transition-transform">٢ساعة</div>
                        <div class="text-secondary-600 text-sm">متوسط وقت الربط</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 bg-gradient-to-br from-secondary-50 to-primary-50 relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-40 h-40 bg-primary-300 rounded-full"></div>
        <div class="absolute bottom-20 left-20 w-32 h-32 bg-primary-400 rounded-full"></div>
    </div>

    <div class="container mx-auto px-4 relative">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16 animate-slide-up">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl mb-6">
                    <i class="fas fa-cogs text-primary-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    <span class="gradient-text">كيف نعمل؟</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full mx-auto mb-8"></div>
                <p class="text-xl text-secondary-600 leading-relaxed max-w-3xl mx-auto">
                    عملية ربط بسيطة ومدروسة لضمان أفضل خدمة ربط بين الشركات اللوجستية وشركات التمويل المرخصة
                </p>
            </div>

            <!-- Steps -->
            <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.1s;">
                    <div class="glass rounded-3xl p-8 h-full border border-primary-200/50 group-hover:border-primary-300 transition-all duration-300">
                        <div class="flex items-start space-x-6 space-x-reverse">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative bg-gradient-to-br from-primary-500 to-primary-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center font-bold text-xl shadow-glow group-hover:scale-110 transition-transform duration-300">
                                    1
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-primary-600 transition-colors">طلب الربط والخدمة</h3>
                                <p class="text-secondary-600 leading-relaxed">الشركة اللوجستية تقدم طلب للربط مع شركات التمويل المناسبة أو الشركات الطالبة للخدمة مع تقديم البيانات المطلوبة</p>
                                <div class="mt-4 flex items-center text-primary-600 text-sm font-medium">
                                    <i class="fas fa-clock ml-2"></i>
                                    خلال ساعات قليلة
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.2s;">
                    <div class="glass rounded-3xl p-8 h-full border border-emerald-200/50 group-hover:border-emerald-300 transition-all duration-300">
                        <div class="flex items-start space-x-6 space-x-reverse">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative bg-gradient-to-br from-emerald-500 to-emerald-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center font-bold text-xl shadow-glow group-hover:scale-110 transition-transform duration-300">
                                    2
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-emerald-600 transition-colors">التقييم والمطابقة</h3>
                                <p class="text-secondary-600 leading-relaxed">نقوم بتقييم احتياجات الشركة اللوجستية ومطابقتها مع أنسب شركات التمويل المرخصة في شبكتنا بناءً على المعايير المحددة</p>
                                <div class="mt-4 flex items-center text-emerald-600 text-sm font-medium">
                                    <i class="fas fa-search ml-2"></i>
                                    تقييم دقيق ومطابقة
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.3s;">
                    <div class="glass rounded-3xl p-8 h-full border border-purple-200/50 group-hover:border-purple-300 transition-all duration-300">
                        <div class="flex items-start space-x-6 space-x-reverse">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative bg-gradient-to-br from-purple-500 to-purple-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center font-bold text-xl shadow-glow group-hover:scale-110 transition-transform duration-300">
                                    3
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-purple-600 transition-colors">الربط والتسهيل</h3>
                                <p class="text-secondary-600 leading-relaxed">نقوم بربط الشركة اللوجستية مع الشريك المناسب وتسهيل عملية التواصل والتفاوض بينهما لضمان أفضل النتائج</p>
                                <div class="mt-4 flex items-center text-purple-600 text-sm font-medium">
                                    <i class="fas fa-handshake ml-2"></i>
                                    ربط مباشر
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.4s;">
                    <div class="glass rounded-3xl p-8 h-full border border-orange-200/50 group-hover:border-orange-300 transition-all duration-300">
                        <div class="flex items-start space-x-6 space-x-reverse">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl opacity-10 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative bg-gradient-to-br from-orange-500 to-orange-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center font-bold text-xl shadow-glow group-hover:scale-110 transition-transform duration-300">
                                    4
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-xl mb-4 text-secondary-800 group-hover:text-orange-600 transition-colors">المتابعة والدعم</h3>
                                <p class="text-secondary-600 leading-relaxed">نقدم الدعم الفني والإداري المستمر لجميع الأطراف لضمان نجاح الشراكة ونوفر أدوات المتابعة والتقييم</p>
                                <div class="mt-4 flex items-center text-orange-600 text-sm font-medium">
                                    <i class="fas fa-headset ml-2"></i>
                                    دعم مستمر
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Flow Animation -->
            <div class="mt-16 text-center animate-slide-up" style="animation-delay: 0.5s;">
                <div class="inline-flex items-center space-x-4 space-x-reverse bg-white/60 backdrop-blur-sm rounded-full px-8 py-4 shadow-soft">
                    <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-bold animate-pulse-soft">1</div>
                    <i class="fas fa-arrow-left text-primary-400"></i>
                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold animate-pulse-soft" style="animation-delay: 0.5s;">2</div>
                    <i class="fas fa-arrow-left text-emerald-400"></i>
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold animate-pulse-soft" style="animation-delay: 1s;">3</div>
                    <i class="fas fa-arrow-left text-purple-400"></i>
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold animate-pulse-soft" style="animation-delay: 1.5s;">4</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-20 relative">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16 animate-slide-up">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl mb-6">
                    <i class="fas fa-star text-primary-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    <span class="gradient-text">خدماتنا</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full mx-auto mb-8"></div>
                <p class="text-xl text-secondary-600 leading-relaxed max-w-3xl mx-auto">
                    نربط الشركات اللوجستية بأفضل شركات التمويل المرخصة ونوفر أجهزة تقنية حديثة لتطوير قطاع اللوجستيات
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Financing Service -->
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.1s;">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-primary-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative glass rounded-3xl p-10 h-full border border-primary-200/50 group-hover:border-primary-300 transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                                <div class="relative bg-gradient-to-br from-primary-500 to-primary-600 p-4 rounded-2xl shadow-glow">
                                    <i class="fas fa-money-bill-wave text-white text-3xl"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-secondary-800 mr-4 group-hover:text-primary-600 transition-colors">الربط مع شركات التمويل المرخصة</h3>
                        </div>

                        <p class="text-lg text-secondary-600 leading-relaxed mb-8">
                            نربط الشركات اللوجستية بأفضل شركات التمويل المرخصة في المملكة لضمان الحصول على أنسب الحلول التمويلية
                        </p>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center ml-4">
                                    <i class="fas fa-check text-primary-600"></i>
                                </div>
                                <span class="text-secondary-700">شركات تمويل مرخصة ومعتمدة</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center ml-4">
                                    <i class="fas fa-check text-primary-600"></i>
                                </div>
                                <span class="text-secondary-700">مطابقة دقيقة للاحتياجات</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center ml-4">
                                    <i class="fas fa-check text-primary-600"></i>
                                </div>
                                <span class="text-secondary-700">عمولة تنافسية على الربط</span>
                            </div>
                        </div>

                        <a href="{{ route('logistics.public') }}" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 transition-colors group-hover:translate-x-2 transform duration-300">
                            اعرف المزيد
                            <i class="fas fa-arrow-left mr-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Tracking Devices Store -->
                <div class="group relative animate-slide-up hover-lift" style="animation-delay: 0.2s;">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative glass rounded-3xl p-10 h-full border border-emerald-200/50 group-hover:border-emerald-300 transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                                <div class="relative bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-2xl shadow-glow">
                                    <i class="fas fa-satellite-dish text-white text-3xl"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-secondary-800 mr-4 group-hover:text-emerald-600 transition-colors">متجر أجهزة التتبع</h3>
                        </div>

                        <p class="text-lg text-secondary-600 leading-relaxed mb-8">
                            نوفر أحدث أجهزة التتبع وحلول إدارة الأساطيل للشركات اللوجستية من خلال متجرنا المتخصص بأحدث التقنيات العالمية
                        </p>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center ml-4">
                                    <i class="fas fa-check text-emerald-600"></i>
                                </div>
                                <span class="text-secondary-700">أجهزة تتبع متقدمة</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center ml-4">
                                    <i class="fas fa-check text-emerald-600"></i>
                                </div>
                                <span class="text-secondary-700">دعم فني متخصص</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center ml-4">
                                    <i class="fas fa-check text-emerald-600"></i>
                                </div>
                                <span class="text-secondary-700">ضمان على جميع الأجهزة</span>
                            </div>
                        </div>

                        <a href="{{ route('store') }}" class="inline-flex items-center text-emerald-600 font-semibold hover:text-emerald-700 transition-colors group-hover:translate-x-2 transform duration-300">
                            تصفح المتجر
                            <i class="fas fa-arrow-left mr-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="relative py-20 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800"></div>

    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-white rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center text-white">
            <div class="animate-slide-up">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl mb-8 animate-bounce-soft">
                    <i class="fas fa-rocket text-white text-3xl"></i>
                </div>

                <h2 class="text-4xl md:text-6xl font-bold mb-6">
                    جاهز لتطوير عملك؟
                </h2>

                <p class="text-xl md:text-2xl mb-12 opacity-90 leading-relaxed max-w-3xl mx-auto">
                    تواصل معنا اليوم للربط مع أفضل شركات التمويل المرخصة لشركتك<br>
                    <span class="text-primary-200">وابدأ رحلة النمو والتطور</span>
                </p>

                                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="#contact-section" onclick="scrollToContact(event)" class="group relative overflow-hidden bg-white text-primary-600 px-10 py-5 rounded-2xl font-bold text-lg shadow-glow hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-phone ml-3 group-hover:animate-bounce-soft"></i>
                            ابدأ الآن مجاناً
                        </span>
                        <div class="absolute inset-0 bg-primary-50 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </a>

                    <a href="#contact-section" onclick="scrollToContact(event)" class="group bg-white/20 backdrop-blur-sm text-white border-2 border-white/30 px-10 py-5 rounded-2xl font-semibold text-lg hover:bg-white/30 hover:border-white/50 transition-all duration-300 hover:scale-105">
                        <i class="fas fa-calendar ml-3 group-hover:animate-bounce-soft"></i>
                        احجز استشارة مجانية
                    </a>
                </div>

                <!-- Trust indicators -->
                <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 opacity-80">
                    <div class="text-center">
                        <div class="text-2xl font-bold mb-1">٩٥%</div>
                        <div class="text-sm text-primary-200">معدل نجاح الربط</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold mb-1">٢ ساعات</div>
                        <div class="text-sm text-primary-200">متوسط وقت الربط</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold mb-1">٢٠٠+</div>
                        <div class="text-sm text-primary-200">شركة مربوطة</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold mb-1">٥٠+</div>
                        <div class="text-sm text-primary-200">شريك تمويل</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact-section" class="py-20 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-secondary-50 via-primary-50 to-secondary-100"></div>

    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-40 h-40 bg-primary-400 rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-primary-300 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-primary-500 rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16 animate-slide-up">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-3xl mb-8 shadow-glow animate-bounce-soft">
                    <i class="fas fa-envelope text-white text-3xl"></i>
                </div>

                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    <span class="gradient-text">تواصل معنا</span>
                </h2>

                <div class="w-24 h-1 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full mx-auto mb-8"></div>

                <p class="text-xl text-secondary-600 leading-relaxed max-w-3xl mx-auto">
                    نحن هنا للإجابة على جميع استفساراتك وربط شركتك بأنسب شركات التمويل المرخصة
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <!-- Contact Form -->
                <div class="animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="glass rounded-3xl p-8 shadow-soft border border-primary-200/50">

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle ml-2"></i>
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                                <span class="absolute top-0 bottom-0 left-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </span>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if(session('error'))
                            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle ml-2"></i>
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                                <span class="absolute top-0 bottom-0 left-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </span>
                            </div>
                        @endif

                        <form class="space-y-6" action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="group">
                                    <label class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                        <i class="fas fa-building ml-2"></i>
                                        اسم الشركة
                                    </label>
                                    <input type="text"
                                           name="company_name"
                                           value="{{ old('company_name') }}"
                                           class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('company_name') border-red-500 @enderror"
                                           placeholder="اسم شركتك"
                                           required>
                                    @error('company_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                        <i class="fas fa-user ml-2"></i>
                                        اسم المسؤول
                                    </label>
                                    <input type="text"
                                           name="contact_name"
                                           value="{{ old('contact_name') }}"
                                           class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('contact_name') border-red-500 @enderror"
                                           placeholder="اسمك الكامل"
                                           required>
                                    @error('contact_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="group">
                                    <label class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                        <i class="fas fa-phone ml-2"></i>
                                        رقم الهاتف
                                    </label>
                                    <input type="tel"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('phone') border-red-500 @enderror"
                                           placeholder="+966 xx xxx xxxx"
                                           required>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                        <i class="fas fa-envelope ml-2"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('email') border-red-500 @enderror"
                                           placeholder="email@company.com"
                                           required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-cog ml-2"></i>
                                    نوع الخدمة المطلوبة
                                </label>
                                <select name="service_type" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 bg-white/80 @error('service_type') border-red-500 @enderror" required>
                                    <option value="">اختر نوع الخدمة</option>
                                    <option value="financing_link" {{ old('service_type') == 'financing_link' ? 'selected' : '' }}>الربط مع شركات التمويل</option>
                                    <option value="client_link" {{ old('service_type') == 'client_link' ? 'selected' : '' }}>الربط مع الشركات الطالبة للخدمة</option>
                                    <option value="tracking" {{ old('service_type') == 'tracking' ? 'selected' : '' }}>أجهزة التتبع</option>
                                    <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>استشارة عامة</option>
                                    <option value="partnership" {{ old('service_type') == 'partnership' ? 'selected' : '' }}>شراكة استراتيجية</option>
                                </select>
                                @error('service_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-secondary-700 mb-2 group-focus-within:text-primary-600 transition-colors">
                                    <i class="fas fa-comment ml-2"></i>
                                    الرسالة
                                </label>
                                <textarea rows="5"
                                          name="message"
                                          class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300 hover:border-primary-300 resize-none bg-white/80 @error('message') border-red-500 @enderror"
                                          placeholder="اكتب رسالتك هنا... كلما كانت التفاصيل أكثر، كان بإمكاننا خدمتك بشكل أفضل">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full relative overflow-hidden bg-gradient-to-r from-primary-500 to-primary-600 text-white py-4 rounded-xl font-semibold text-lg hover:shadow-glow transition-all duration-300 hover:scale-105 group">
                                <span class="relative z-10 flex items-center justify-center">
                                    <i class="fas fa-paper-plane ml-2 group-hover:animate-bounce-soft"></i>
                                    إرسال الطلب
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                            </button>
                        </form>

                        <!-- Privacy Notice -->
                        <div class="mt-6 pt-6 border-t border-secondary-200 text-center">
                            <div class="flex items-center justify-center text-sm text-secondary-600">
                                <i class="fas fa-shield-check text-primary-500 ml-2"></i>
                                بياناتك محمية ولن يتم مشاركتها مع أطراف خارجية
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="space-y-8">
                        <!-- Contact Details -->
                        <div class="glass rounded-3xl p-8 shadow-soft border border-primary-200/50">
                            <h3 class="text-2xl font-bold text-secondary-800 mb-6">معلومات التواصل</h3>

                            <div class="space-y-6">
                                <div class="flex items-start group">
                                    <div class="flex items-center justify-center w-12 h-12 bg-primary-100 rounded-xl ml-4 group-hover:bg-primary-200 transition-colors">
                                        <i class="fas fa-phone text-primary-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-secondary-800 mb-1">الهاتف</h4>
                                        <p class="text-secondary-600">+966 11 123 4567</p>
                                        <p class="text-secondary-500 text-sm">متاح ٢٤/٧</p>
                                    </div>
                                </div>

                                <div class="flex items-start group">
                                    <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-xl ml-4 group-hover:bg-emerald-200 transition-colors">
                                        <i class="fas fa-envelope text-emerald-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-secondary-800 mb-1">البريد الإلكتروني</h4>
                                        <p class="text-secondary-600">info@Link2u.com</p>
                                        <p class="text-secondary-500 text-sm">نرد خلال ساعة</p>
                                    </div>
                                </div>

                                <div class="flex items-start group">
                                    <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-xl ml-4 group-hover:bg-purple-200 transition-colors">
                                        <i class="fas fa-map-marker-alt text-purple-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-secondary-800 mb-1">العنوان</h4>
                                        <p class="text-secondary-600">الرياض، المملكة العربية السعودية</p>
                                        <p class="text-secondary-500 text-sm">برج الأعمال، الطابق ١٥</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="glass rounded-3xl p-8 shadow-soft border border-primary-200/50">
                            <h3 class="text-2xl font-bold text-secondary-800 mb-6">ساعات العمل</h3>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-secondary-700 font-medium">الأحد - الخميس</span>
                                    <span class="text-primary-600 font-semibold">٨:٠٠ص - ٦:٠٠م</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-secondary-700 font-medium">الجمعة</span>
                                    <span class="text-primary-600 font-semibold">٩:٠٠ص - ٢:٠٠م</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-secondary-700 font-medium">السبت</span>
                                    <span class="text-secondary-500">مغلق</span>
                                </div>
                            </div>
                        </div>

                        <!-- Response Time -->
                        <div class="glass rounded-3xl p-6 shadow-soft border border-primary-200/50 text-center">
                            <div class="text-3xl font-bold gradient-text mb-2">٢٤ ساعة</div>
                            <div class="text-secondary-600">متوسط وقت الاستجابة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Smooth scroll to any section
    function scrollToSection(event, sectionId) {
        event.preventDefault();
        const section = document.getElementById(sectionId);

        if (section) {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    // تم حذف function handleContactSubmit لأن النموذج يرسل للباك إند مباشرة

    // Enhanced form interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced form validation for contact form
        const contactForm = document.querySelector('#contact-section form');
        if (contactForm) {
            const inputs = contactForm.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });
        }

        // Add intersection observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe sections for scroll animations
        document.querySelectorAll('section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(section);
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');

        // Remove existing error state
        field.classList.remove('error', 'border-red-500', 'ring-red-500');

        let isValid = true;

        if (isRequired && !value) {
            isValid = false;
        } else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        } else if (field.type === 'tel' && value) {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
            isValid = phoneRegex.test(value);
        }

        if (!isValid) {
            field.classList.add('error', 'border-red-500');
            field.classList.remove('border-secondary-200', 'focus:ring-primary-500');

            // Add shake animation
            field.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                field.style.animation = '';
            }, 500);
        } else {
            field.classList.remove('border-red-500');
            field.classList.add('border-secondary-200');
        }

        return isValid;
    }

    // Add smooth parallax effect to hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('section[class*="min-h-screen"]');

        if (heroSection) {
            const backgroundShapes = heroSection.querySelectorAll('[class*="animate-pulse-soft"], [class*="animate-float"]');
            backgroundShapes.forEach((shape, index) => {
                const speed = 0.1 + (index * 0.05);
                shape.style.transform = `translateY(${scrolled * speed}px)`;
            });
        }
    });

    // Add shake animation keyframes
    const shakeKeyframes = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;

    const style = document.createElement('style');
    style.textContent = shakeKeyframes;
    document.head.appendChild(style);
</script>
@endpush
@endsection
