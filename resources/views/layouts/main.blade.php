<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ \App\Models\Setting::get('seo_description', 'منصة متطورة لربط الشركات اللوجستية بالعملاء') }}">
    <meta name="keywords" content="{{ \App\Models\Setting::get('seo_keywords', 'شركات لوجستية، شحن، توصيل، السعودية') }}">
    <meta name="author" content="{{ \App\Models\Setting::get('site_name', 'Logistiq') }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ \App\Models\Setting::get('seo_title', 'Logistiq') }}">
    <meta property="og:description" content="{{ \App\Models\Setting::get('seo_description', 'منصة متطورة لربط الشركات اللوجستية بالعملاء') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    @if(\App\Models\Setting::get('site_logo'))
    <meta property="og:image" content="{{ asset('images/' . \App\Models\Setting::get('site_logo')) }}">
    @endif

    <!-- Favicon -->
    @if(\App\Models\Setting::get('site_favicon'))
    <link rel="icon" type="image/x-icon" href="{{ asset('images/' . \App\Models\Setting::get('site_favicon')) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/' . \App\Models\Setting::get('site_favicon')) }}">
    @endif

    <title>@yield('title', '{{ \App\Models\Setting::get("site_name", "Logistiq") }}')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        slate: {
                            850: '#1a202c',
                        }
                    },
                    backdropBlur: {
                        xs: '2px',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'bounce-soft': 'bounceSoft 0.6s ease-out',
                        'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'spin-slow': 'spin 3s linear infinite',
                        'spin-reverse': 'spin 2s linear infinite reverse',
                        'progress': 'progress 2s ease-in-out infinite',
                        'shimmer': 'shimmer 1.5s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        slideDown: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(-10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        scaleIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'scale(0.95)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        },
                        bounceSoft: {
                            '0%': {
                                transform: 'scale(1)'
                            },
                            '50%': {
                                transform: 'scale(1.05)'
                            },
                            '100%': {
                                transform: 'scale(1)'
                            },
                        },
                        pulseSoft: {
                            '0%, 100%': {
                                transform: 'scale(1)'
                            },
                            '50%': {
                                transform: 'scale(1.02)'
                            },
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        progress: {
                            '0%': {
                                width: '0%'
                            },
                            '50%': {
                                width: '75%'
                            },
                            '100%': {
                                width: '100%'
                            }
                        },
                        shimmer: {
                            '0%': {
                                transform: 'translateX(-100%)'
                            },
                            '100%': {
                                transform: 'translateX(100%)'
                            }
                        },
                    },
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts (Arabic Support) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Tajawal', 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #0ea5e9, #0284c7);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #0284c7, #0369a1);
        }

        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Enhanced hover effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Loading animation */
        .loading-dots::after {
            content: '';
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                content: '';
            }

            25% {
                content: '.';
            }

            50% {
                content: '..';
            }

            75% {
                content: '...';
            }

            100% {
                content: '';
            }
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom shadows */
        .shadow-soft {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        .shadow-glow {
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
        }

        /* Message Animations */
        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .animate-slide-down {
            animation: slide-down 0.5s ease-out;
        }

        .animate-slide-up {
            animation: slide-up 0.3s ease-in;
        }
    </style>
</head>

<body class="text-secondary-800 animate-fade-in">

    <!-- Messages Area -->
    @if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
    <div class="fixed top-20 left-4 right-4 z-[9999] max-w-md mx-auto">
        <!-- Success Messages -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-lg animate-slide-down" id="success-message">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-bold">تمت العملية بنجاح!</h4>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="closeMessage('success-message')" class="flex-shrink-0 ml-4 text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if(session('error') || $errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-lg animate-slide-down" id="error-message">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-bold">حدث خطأ!</h4>
                    @if(session('error'))
                        <p class="text-sm">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="text-sm mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button onclick="closeMessage('error-message')" class="flex-shrink-0 ml-4 text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Warning Messages -->
        @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg shadow-lg animate-slide-down" id="warning-message">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-500"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-bold">تحذير!</h4>
                    <p class="text-sm">{{ session('warning') }}</p>
                </div>
                <button onclick="closeMessage('warning-message')" class="flex-shrink-0 ml-4 text-yellow-500 hover:text-yellow-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Info Messages -->
        @if(session('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg shadow-lg animate-slide-down" id="info-message">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-bold">معلومة</h4>
                    <p class="text-sm">{{ session('info') }}</p>
                </div>
                <button onclick="closeMessage('info-message')" class="flex-shrink-0 ml-4 text-blue-500 hover:text-blue-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white overflow-hidden">
        <!-- Subtle Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-primary-50/30"></div>

        <!-- Main Content -->
        <div class="relative text-center animate-fade-in">
            <!-- Logo Container -->
            <div class="mb-8 animate-slide-up">
                <div class="relative inline-flex items-center justify-center">
                    <!-- Simple Logo Background -->
                    <div class="relative w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-lg flex items-center justify-center">
                        <i class="fas fa-truck text-white text-2xl"></i>
                    </div>

                    <!-- Subtle Ring Animation -->
                    <div class="absolute inset-0 w-20 h-20 rounded-2xl border-2 border-primary-300 animate-pulse-soft opacity-50"></div>
                </div>
            </div>

            <!-- Brand Name -->
            <div class="mb-8 animate-slide-up" style="animation-delay: 0.2s;">
                <h1 class="text-3xl font-bold text-primary-600 mb-2">{{ \App\Models\Setting::get('site_name', 'Logistiq') }}</h1>
                <p class="text-sm text-secondary-500">حلول التمويل اللوجستية</p>
            </div>

            <!-- Simple Loading Animation -->
            <div class="animate-slide-up" style="animation-delay: 0.4s;">
                <!-- Minimalist Loading Dots -->
                <div class="flex items-center justify-center space-x-2 space-x-reverse">
                    <div class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                    <div class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                    <div class="w-2 h-2 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Navigation -->
    <nav class="glass sticky top-0 z-50 shadow-soft border-b border-white/20 animate-slide-down">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 space-x-reverse group">
                        <img src="{{ asset('images/' . \App\Models\Setting::get('site_logo')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Logistiq') }}" width="120" class="object-contain">
                    </a>
                </div>

                <!-- Navigation Links - Center -->
                <div class="hidden md:flex items-center justify-center flex-1">
                    <div class="flex items-center space-x-6 space-x-reverse">
                        <a href="{{ route('home') }}"
                            class="relative group px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('home') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                            <span class="relative z-10">الرئيسية</span>
                            @if (request()->routeIs('home'))
                                <div
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full">
                                </div>
                            @endif
                        </a>



                        <a href="{{ route('store') }}"
                            class="relative group px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('store') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                            <span class="relative z-10">متجر الأجهزة</span>
                            @if (request()->routeIs('store'))
                                <div
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full">
                                </div>
                            @endif
                        </a>

                        <a href="#contact-section" onclick="scrollToContact(event)"
                            class="text-secondary-700 hover:text-primary-600 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-primary-50/50">
                            تواصل معنا
                        </a>
                    </div>
                </div>

                <!-- Auth Section - Right Side -->
                <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                    @auth
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 space-x-reverse text-secondary-700 hover:text-primary-600 transition-colors">
                                <i class="fas fa-user"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-secondary-200 py-2 z-50">
                                @if(auth()->user()->user_type === 'regular')
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-tachometer-alt ml-2"></i>
                                        لوحة التحكم
                                    </a>
                                @elseif(auth()->user()->user_type === 'logistics')
                                    <a href="{{ route('logistics.dashboard') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-tachometer-alt ml-2"></i>
                                        لوحة التحكم
                                    </a>
                                @elseif(auth()->user()->user_type === 'service_company')
                                    <a href="{{ route('service_company.dashboard') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-tachometer-alt ml-2"></i>
                                        لوحة التحكم
                                    </a>
                                @elseif(auth()->user()->user_type === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-cog ml-2"></i>
                                        لوحة الإدارة
                                    </a>
                                @endif

                                <!-- الملف الشخصي -->
                                @if(auth()->user()->user_type === 'logistics')
                                    <a href="{{ route('logistics.profile') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-user-circle ml-2"></i>
                                        الملف الشخصي
                                    </a>
                                @elseif(auth()->user()->user_type === 'service_company')
                                    <a href="{{ route('service_company.profile') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-user-circle ml-2"></i>
                                        الملف الشخصي
                                    </a>
                                @elseif(auth()->user()->user_type === 'regular')
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-secondary-700 hover:bg-primary-50 hover:text-primary-600">
                                        <i class="fas fa-user-circle ml-2"></i>
                                        الملف الشخصي
                                    </a>
                                @endif

                                <div class="border-t border-secondary-200 my-2"></div>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="block px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt ml-2"></i>
                                    تسجيل الخروج
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-2 rounded-lg font-medium hover:shadow-glow transition-all duration-300 hover:scale-105">
                            تسجيل الدخول
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button"
                        class="relative p-2 text-secondary-700 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-lg transition-all duration-300 hover:bg-primary-50"
                        onclick="toggleMobileMenu()">
                        <div class="w-6 h-6 flex flex-col justify-center items-center">
                            <span class="bg-current w-5 h-0.5 rounded-sm transition-all duration-300"
                                id="menu-line-1"></span>
                            <span class="bg-current w-5 h-0.5 rounded-sm mt-1 transition-all duration-300"
                                id="menu-line-2"></span>
                            <span class="bg-current w-5 h-0.5 rounded-sm mt-1 transition-all duration-300"
                                id="menu-line-3"></span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-white/20 pt-6 pb-6 animate-slide-down max-h-[80vh] overflow-y-auto">
                <div class="space-y-4 px-2">
                    <!-- Main Navigation -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-secondary-500 uppercase tracking-wider px-4 mb-3">القائمة الرئيسية</h3>

                        <a href="{{ route('home') }}"
                            class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('home') ? 'text-primary-600 font-semibold bg-gradient-to-r from-primary-50 to-primary-100 border-r-4 border-primary-500' : 'text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50' }}">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="fas fa-home text-white text-sm"></i>
                            </div>
                            <span class="font-medium">الرئيسية</span>
                            @if(request()->routeIs('home'))
                                <div class="mr-auto w-2 h-2 bg-primary-500 rounded-full"></div>
                            @endif
                        </a>

                        <a href="{{ route('store') }}"
                            class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('store') ? 'text-primary-600 font-semibold bg-gradient-to-r from-primary-50 to-primary-100 border-r-4 border-primary-500' : 'text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50' }}">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="fas fa-shopping-cart text-white text-sm"></i>
                            </div>
                            <span class="font-medium">متجر الأجهزة</span>
                            @if(request()->routeIs('store'))
                                <div class="mr-auto w-2 h-2 bg-primary-500 rounded-full"></div>
                            @endif
                        </a>

                        <a href="{{ route('home') }}#contact-section" onclick="scrollToContact(event)"
                            class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center ml-3">
                                <i class="fas fa-envelope text-white text-sm"></i>
                            </div>
                            <span class="font-medium">تواصل معنا</span>
                        </a>
                    </div>

                    @auth
                        <!-- User Profile Section -->
                        <div class="border-t border-white/20 pt-4">
                            <h3 class="text-xs font-bold text-secondary-500 uppercase tracking-wider px-4 mb-3">حسابي</h3>

                            <!-- User Info Card -->
                            <div class="mx-2 mb-4 bg-gradient-to-r from-primary-50 to-blue-50 rounded-2xl p-4 border border-primary-100">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-user text-white text-lg"></i>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-primary-800 text-lg">{{ auth()->user()->name }}</p>
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <span class="px-2 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                                @if(auth()->user()->user_type === 'regular')
                                                    <i class="fas fa-user ml-1"></i>مستخدم عادي
                                                @elseif(auth()->user()->user_type === 'logistics')
                                                    <i class="fas fa-truck ml-1"></i>شركة لوجستية
                                                @elseif(auth()->user()->user_type === 'service_company')
                                                    <i class="fas fa-building ml-1"></i>شركة طالبة
                                                @elseif(auth()->user()->user_type === 'admin')
                                                    <i class="fas fa-crown ml-1"></i>مدير النظام
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="space-y-2">
                                <!-- Dashboard Link -->
                                @if(auth()->user()->user_type === 'regular')
                                    <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-tachometer-alt text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">لوحة التحكم</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @elseif(auth()->user()->user_type === 'logistics')
                                    <a href="{{ route('logistics.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-tachometer-alt text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">لوحة التحكم</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @elseif(auth()->user()->user_type === 'service_company')
                                    <a href="{{ route('service_company.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-tachometer-alt text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">لوحة التحكم</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @elseif(auth()->user()->user_type === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-cog text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">لوحة الإدارة</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @endif

                                <!-- Profile Link -->
                                @if(auth()->user()->user_type === 'logistics')
                                    <a href="{{ route('logistics.profile') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-user-circle text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">الملف الشخصي</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @elseif(auth()->user()->user_type === 'service_company')
                                    <a href="{{ route('service_company.profile') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-user-circle text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">الملف الشخصي</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @elseif(auth()->user()->user_type === 'regular')
                                    <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-user-circle text-white text-sm"></i>
                                        </div>
                                        <span class="font-medium">الملف الشخصي</span>
                                        <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                    </a>
                                @endif
                            </div>

                            <!-- Logout Section -->
                            <div class="border-t border-white/20 pt-4 mt-4">
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-red-600 hover:text-red-700 hover:bg-gradient-to-r hover:from-red-50/50 hover:to-red-100/50 group">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-sign-out-alt text-white text-sm"></i>
                                    </div>
                                    <span class="font-medium">تسجيل الخروج</span>
                                    <i class="fas fa-chevron-left mr-auto text-red-400 group-hover:text-red-500 transition-colors"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Guest User Section -->
                        <div class="border-t border-white/20 pt-4">
                            <h3 class="text-xs font-bold text-secondary-500 uppercase tracking-wider px-4 mb-3">زائر</h3>

                            <div class="space-y-2">
                                <a href="#contact-section" onclick="scrollToContact(event)"
                                    class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-primary-100/50 group">
                                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-envelope text-white text-sm"></i>
                                    </div>
                                    <span class="font-medium">تواصل معنا</span>
                                    <i class="fas fa-chevron-left mr-auto text-secondary-400 group-hover:text-primary-500 transition-colors"></i>
                                </a>

                                <a href="{{ route('login') }}"
                                    class="mx-4 mt-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-glow transition-all duration-300 inline-flex items-center justify-center w-auto group">
                                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-sign-in-alt text-white text-sm"></i>
                                    </div>
                                    <span>تسجيل الدخول</span>
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-1/4 w-96 h-96 bg-primary-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-soft">
            </div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-primary-600 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse-soft"
                style="animation-delay: 1s;"></div>
        </div>

        <div class="relative glass-dark">
            <div class="container mx-auto px-4 py-12">
                <!-- Top Section -->
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-8">
                    <!-- Brand Section -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-3 space-x-reverse mb-6 group">
                            <div class="relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl opacity-30 group-hover:opacity-40 transition-opacity duration-300">
                                </div>
                                <div
                                    class="relative bg-gradient-to-br from-primary-500 to-primary-600 p-3 rounded-xl shadow-glow">
                                    @if(\App\Models\Setting::get('site_logo'))
                                        <img src="{{ asset('images/' . \App\Models\Setting::get('site_logo')) }}" alt="{{ \App\Models\Setting::get('site_name', 'Logistiq') }}" class="w-8 h-8 object-contain">
                                    @else
                                        <i class="fas fa-truck text-white text-2xl"></i>
                                    @endif
                                </div>
                            </div>
                            <span class="text-2xl font-bold gradient-text">{{ \App\Models\Setting::get('site_name', 'Logistiq') }}</span>
                        </div>
                        <p class="text-secondary-300 text-base leading-relaxed mb-6 max-w-md">
                            {{ \App\Models\Setting::get('footer_description', 'نقدم حلول التمويل المتقدمة للشركات اللوجستية وخدمات بيع أجهزة التتبع بأحدث التقنيات') }}
                        </p>

                        <!-- Social Links -->
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <a href="#"
                                class="group p-3 bg-secondary-800/50 hover:bg-primary-600 rounded-xl transition-all duration-300 hover:shadow-glow">
                                <i
                                    class="fab fa-twitter text-secondary-300 group-hover:text-white transition-colors"></i>
                            </a>
                            <a href="#"
                                class="group p-3 bg-secondary-800/50 hover:bg-primary-600 rounded-xl transition-all duration-300 hover:shadow-glow">
                                <i
                                    class="fab fa-linkedin text-secondary-300 group-hover:text-white transition-colors"></i>
                            </a>
                            <a href="#"
                                class="group p-3 bg-secondary-800/50 hover:bg-primary-600 rounded-xl transition-all duration-300 hover:shadow-glow">
                                <i
                                    class="fab fa-instagram text-secondary-300 group-hover:text-white transition-colors"></i>
                            </a>
                            <a href="#"
                                class="group p-3 bg-secondary-800/50 hover:bg-primary-600 rounded-xl transition-all duration-300 hover:shadow-glow">
                                <i
                                    class="fab fa-youtube text-secondary-300 group-hover:text-white transition-colors"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="animate-slide-up" style="animation-delay: 0.1s;">
                        <h3 class="text-white font-bold mb-6 relative">
                            الخدمات
                            <div
                                class="absolute bottom-0 right-0 w-8 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full">
                            </div>
                        </h3>
                        <ul class="space-y-3">

                            <li>
                                <a href="{{ route('store') }}"
                                    class="group flex items-center text-secondary-300 hover:text-primary-400 transition-all duration-300">
                                    <div
                                        class="w-1 h-1 bg-primary-500 rounded-full ml-3 group-hover:w-3 transition-all duration-300">
                                    </div>
                                    أجهزة التتبع
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="group flex items-center text-secondary-300 hover:text-primary-400 transition-all duration-300">
                                    <div
                                        class="w-1 h-1 bg-primary-500 rounded-full ml-3 group-hover:w-3 transition-all duration-300">
                                    </div>
                                    الاستشارات المالية
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Section -->
                    <div class="animate-slide-up" style="animation-delay: 0.2s;">
                        <h3 class="text-white font-bold mb-6 relative">
                            تواصل معنا
                            <div
                                class="absolute bottom-0 right-0 w-8 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full">
                            </div>
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center group">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-primary-600/20 rounded-lg ml-3 group-hover:bg-primary-600 transition-all duration-300">
                                    <i
                                        class="fas fa-envelope text-primary-400 group-hover:text-white transition-colors"></i>
                                </div>
                                <div>
                                    <p class="text-secondary-300 group-hover:text-primary-400 transition-colors">
                                        {{ \App\Models\Setting::get('site_email', 'info@logistiq.sa') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center group">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-primary-600/20 rounded-lg ml-3 group-hover:bg-primary-600 transition-all duration-300">
                                    <i
                                        class="fas fa-phone text-primary-400 group-hover:text-white transition-colors"></i>
                                </div>
                                <div>
                                    <p class="text-secondary-300 group-hover:text-primary-400 transition-colors">{{ \App\Models\Setting::get('site_phone', '+966 11 123 4567') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-primary-600/20 rounded-lg ml-3 mt-1 group-hover:bg-primary-600 transition-all duration-300">
                                    <i
                                        class="fas fa-map-marker-alt text-primary-400 group-hover:text-white transition-colors"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-secondary-300 group-hover:text-primary-400 transition-colors leading-relaxed">
                                        {{ \App\Models\Setting::get('site_address', 'الرياض، المملكة العربية السعودية') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Section -->
                    <div class="animate-slide-up" style="animation-delay: 0.3s;">
                        <h3 class="text-white font-bold mb-6 relative">
                            تابعونا
                            <div
                                class="absolute bottom-0 right-0 w-8 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full">
                            </div>
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            @if(\App\Models\Setting::get('social_facebook'))
                                <a href="{{ \App\Models\Setting::get('social_facebook') }}" target="_blank"
                                    class="group flex items-center justify-center w-12 h-12 bg-primary-600/20 rounded-lg hover:bg-blue-600 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-facebook-f text-primary-400 group-hover:text-white transition-colors text-lg"></i>
                                </a>
                            @endif

                            @if(\App\Models\Setting::get('social_twitter'))
                                <a href="{{ \App\Models\Setting::get('social_twitter') }}" target="_blank"
                                    class="group flex items-center justify-center w-12 h-12 bg-primary-600/20 rounded-lg hover:bg-blue-400 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-twitter text-primary-400 group-hover:text-white transition-colors text-lg"></i>
                                </a>
                            @endif

                            @if(\App\Models\Setting::get('social_instagram'))
                                <a href="{{ \App\Models\Setting::get('social_instagram') }}" target="_blank"
                                    class="group flex items-center justify-center w-12 h-12 bg-primary-600/20 rounded-lg hover:bg-pink-600 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-instagram text-primary-400 group-hover:text-white transition-colors text-lg"></i>
                                </a>
                            @endif

                            @if(\App\Models\Setting::get('social_linkedin'))
                                <a href="{{ \App\Models\Setting::get('social_linkedin') }}" target="_blank"
                                    class="group flex items-center justify-center w-12 h-12 bg-primary-600/20 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-linkedin-in text-primary-400 group-hover:text-white transition-colors text-lg"></i>
                                </a>
                            @endif

                            @if(\App\Models\Setting::get('social_youtube'))
                                <a href="{{ \App\Models\Setting::get('social_youtube') }}" target="_blank"
                                    class="group flex items-center justify-center w-12 h-12 bg-primary-600/20 rounded-lg hover:bg-red-600 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-youtube text-primary-400 group-hover:text-white transition-colors text-lg"></i>
                                </a>
                            @endif

                            @if(\App\Models\Setting::get('social_whatsapp'))
                                <a href="{{ \App\Models\Setting::get('social_whatsapp') }}" target="_blank"
                                    class="group flex items-center justify-center w-12 h-12 bg-primary-600/20 rounded-lg hover:bg-green-500 transition-all duration-300 transform hover:scale-110">
                                    <i class="fab fa-whatsapp text-primary-400 group-hover:text-white transition-colors text-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-secondary-700/50 my-8"></div>

                <!-- Bottom Section -->
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div
                        class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6 md:space-x-reverse mb-6 lg:mb-0">
                        <p class="text-secondary-400 text-sm font-medium">
                            {{ \App\Models\Setting::get('footer_copyright', '&copy; ' . date('Y') . ' Logistiq. جميع الحقوق محفوظة.') }}
                        </p>
                        <div class="flex items-center space-x-4 space-x-reverse text-xs">
                            <a href="#"
                                class="text-secondary-400 hover:text-primary-400 transition-all duration-300 hover:underline">سياسة
                                الخصوصية</a>
                            <span class="text-secondary-600">•</span>
                            <a href="#"
                                class="text-secondary-400 hover:text-primary-400 transition-all duration-300 hover:underline">شروط
                                الخدمة</a>
                            <span class="text-secondary-600">•</span>
                            <a href="#"
                                class="text-secondary-400 hover:text-primary-400 transition-all duration-300 hover:underline">سياسة
                                الملفات الشخصية</a>
                        </div>
                    </div>

                    <div class="flex flex-col items-center space-y-2">
                        <div class="flex items-center space-x-2 space-x-reverse text-secondary-400 text-sm">
                            <span>تمت البرمجة والتطوير بواسطة</span>
                            <i class="fas fa-heart text-red-500 animate-pulse-soft"></i>
                            <span>فالكو ويب</span>
                        </div>

                        <!-- Payment Methods -->
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-6 left-6 bg-gradient-to-r from-primary-500 to-primary-600 text-white p-3 rounded-full shadow-glow hover:shadow-xl transition-all duration-300 opacity-0 invisible hover:scale-110 z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle with animation
        let mobileMenuOpen = false;

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const line1 = document.getElementById('menu-line-1');
            const line2 = document.getElementById('menu-line-2');
            const line3 = document.getElementById('menu-line-3');

            mobileMenuOpen = !mobileMenuOpen;

            if (mobileMenuOpen) {
                menu.classList.remove('hidden');
                menu.classList.add('animate-slide-down');

                // Animate hamburger to X
                line1.style.transform = 'rotate(45deg) translate(3px, 3px)';
                line2.style.opacity = '0';
                line3.style.transform = 'rotate(-45deg) translate(3px, -3px)';
            } else {
                menu.classList.add('hidden');

                // Animate X back to hamburger
                line1.style.transform = 'rotate(0deg) translate(0px, 0px)';
                line2.style.opacity = '1';
                line3.style.transform = 'rotate(0deg) translate(0px, 0px)';
            }
        }

        // Function to close mobile menu
        function closeMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const line1 = document.getElementById('menu-line-1');
            const line2 = document.getElementById('menu-line-2');
            const line3 = document.getElementById('menu-line-3');

            if (mobileMenuOpen) {
                menu.classList.add('hidden');
                mobileMenuOpen = false;

                // Animate X back to hamburger
                line1.style.transform = 'rotate(0deg) translate(0px, 0px)';
                line2.style.opacity = '1';
                line3.style.transform = 'rotate(0deg) translate(0px, 0px)';
            }
        }

        // Back to top functionality
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');

            if (window.pageYOffset > 300) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        });

        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');

            if (window.pageYOffset > 50) {
                nav.classList.add('backdrop-blur-lg', 'bg-white/80');
            } else {
                nav.classList.remove('backdrop-blur-lg', 'bg-white/80');
            }
        });

        // Enhanced page transitions
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all elements with animation classes
            document.querySelectorAll('.animate-slide-up, .animate-scale-in, .hover-lift').forEach(el => {
                observer.observe(el);
            });

            // Close mobile menu when clicking on any link
            const mobileMenuLinks = document.querySelectorAll('#mobile-menu a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Don't close menu for logout form submission
                    if (this.getAttribute('onclick') && this.getAttribute('onclick').includes('logout-form')) {
                        return;
                    }
                    closeMobileMenu();
                });
            });
        });

        // Loading states for buttons
        function addLoadingState(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري التحميل...';
            button.disabled = true;

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus effects to inputs
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('animate-bounce-soft');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('animate-bounce-soft');
                });
            });

            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('button, .btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Enhanced modal functionality
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Animate modal content
                const modalContent = modal.querySelector('.bg-white, .glass, .glass-dark');
                if (modalContent) {
                    modalContent.classList.add('animate-scale-in');
                }
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Smooth scroll to contact section
        function scrollToContact(event) {
            event.preventDefault();
            const contactSection = document.getElementById('contact-section');

            if (contactSection) {
                contactSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Close mobile menu if open
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    toggleMobileMenu();
                }
            } else {
                // If contact section doesn't exist, go to home page
                window.location.href = '{{ route('home') }}#contact-section';
            }
        }

        // Auto-hide notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification, .alert');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.classList.add('animate-slide-up');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Preloader functionality
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('preloader');
            let isPreloaderHidden = false;

            // Function to hide preloader
            function hidePreloader() {
                if (preloader && !isPreloaderHidden) {
                    isPreloaderHidden = true;

                    // Add fade out effect
                    preloader.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                    preloader.style.opacity = '0';
                    preloader.style.transform = 'scale(0.95)';

                    setTimeout(() => {
                        preloader.style.display = 'none';
                        preloader.remove();
                    }, 800);
                }
            }

            // Hide preloader when page is fully loaded
            window.addEventListener('load', function() {
                // Add a small delay for better UX
                setTimeout(() => {
                    hidePreloader();
                }, 800);
            });

            // Fallback: Hide after 5 seconds max (in case of slow connections)
            setTimeout(() => {
                hidePreloader();
            }, 5000);

            // Optional: Hide on click (for testing purposes)
            if (preloader) {
                preloader.addEventListener('click', function() {
                    hidePreloader();
                });
            }
        });

        // Performance optimization - Lazy load images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>

    <!-- Additional CSS for ripple effect -->
    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Enhanced transitions */
        * {
            transition-property: color, background-color, border-color, transform, opacity, box-shadow;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Custom scrollbar for mobile menu */
        #mobile-menu::-webkit-scrollbar {
            width: 6px;
        }

        #mobile-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        #mobile-menu::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #0ea5e9, #0284c7);
            border-radius: 3px;
        }

        #mobile-menu::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #0284c7, #0369a1);
        }

        /* Smooth scrolling for mobile menu */
        #mobile-menu {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>

    @stack('scripts')

    <!-- Messages Management -->
    <script>
        // دالة إغلاق الرسائل
        function closeMessage(messageId) {
            const message = document.getElementById(messageId);
            if (message) {
                message.classList.remove('animate-slide-down');
                message.classList.add('animate-slide-up');
                setTimeout(() => {
                    message.remove();
                }, 300);
            }
        }

        // إغلاق رسائل النجاح تلقائياً بعد 5 ثوان
        setTimeout(() => {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                closeMessage('success-message');
            }
        }, 5000);

        // إغلاق رسائل المعلومات تلقائياً بعد 8 ثوان
        setTimeout(() => {
            const infoMessage = document.getElementById('info-message');
            if (infoMessage) {
                closeMessage('info-message');
            }
        }, 8000);

        // دالة لإنشاء رسائل جديدة من JavaScript
        window.showMessage = function(type, title, message) {
            const colors = {
                success: { bg: 'bg-green-100', border: 'border-green-400', text: 'text-green-700', icon: 'fa-check-circle', iconColor: 'text-green-500' },
                error: { bg: 'bg-red-100', border: 'border-red-400', text: 'text-red-700', icon: 'fa-exclamation-triangle', iconColor: 'text-red-500' },
                warning: { bg: 'bg-yellow-100', border: 'border-yellow-400', text: 'text-yellow-700', icon: 'fa-exclamation-circle', iconColor: 'text-yellow-500' },
                info: { bg: 'bg-blue-100', border: 'border-blue-400', text: 'text-blue-700', icon: 'fa-info-circle', iconColor: 'text-blue-500' }
            };

            const color = colors[type] || colors.info;
            const messageId = 'dynamic-message-' + Date.now();

            const messageHtml = `
                <div class="mb-4 p-4 ${color.bg} border ${color.border} ${color.text} rounded-lg shadow-lg animate-slide-down" id="${messageId}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas ${color.icon} ${color.iconColor}"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="font-bold">${title}</h4>
                            <p class="text-sm">${message}</p>
                        </div>
                        <button onclick="closeMessage('${messageId}')" class="flex-shrink-0 ml-4 ${color.iconColor} hover:opacity-70">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;

            // البحث عن منطقة الرسائل أو إنشاؤها
            let messagesContainer = document.querySelector('.fixed.top-4.left-4.right-4.z-50');
            if (!messagesContainer) {
                messagesContainer = document.createElement('div');
                messagesContainer.className = 'fixed top-4 left-4 right-4 z-50 max-w-md mx-auto';
                document.body.appendChild(messagesContainer);
            }

            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);

            // إغلاق تلقائي للرسائل الإيجابية
            if (type === 'success' || type === 'info') {
                setTimeout(() => {
                    closeMessage(messageId);
                }, type === 'success' ? 5000 : 8000);
            }
        };
    </script>
</body>

</html>
