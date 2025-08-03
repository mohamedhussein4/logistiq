<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'LogistiQ - حلول التمويل اللوجستية')</title>

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
    </style>
</head>

<body class="text-secondary-800 animate-fade-in">
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
                <h1 class="text-3xl font-bold text-primary-600 mb-2">LogistiQ</h1>
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
                        <div class="relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl opacity-20 group-hover:opacity-30 transition-opacity duration-300 animate-pulse-soft">
                            </div>
                            <div
                                class="relative bg-gradient-to-br from-primary-500 to-primary-600 p-2 rounded-xl shadow-glow group-hover:shadow-lg transition-all duration-300">
                                <i class="fas fa-truck text-white text-xl animate-float"></i>
                            </div>
                        </div>
                        <span
                            class="text-2xl font-bold gradient-text group-hover:scale-105 transition-transform duration-300">LogistiQ</span>
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

                        <a href="{{ route('logistics') }}"
                            class="relative group px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('logistics') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                            <span class="relative z-10">الشركات اللوجستية</span>
                            @if (request()->routeIs('logistics'))
                                <div
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full">
                                </div>
                            @endif
                        </a>

                        <a href="{{ route('clients') }}"
                            class="relative group px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('clients') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                            <span class="relative z-10">الشركات الطالبة</span>
                            @if (request()->routeIs('clients'))
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

                <!-- Login Button - Right Side -->
                <div class="hidden md:flex items-center">
                    <a href="{{ route('login') }}"
                        class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-2 rounded-lg font-medium hover:shadow-glow transition-all duration-300 hover:scale-105">
                        تسجيل الدخول
                    </a>
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
            <div id="mobile-menu" class="hidden md:hidden border-t border-white/20 pt-4 pb-4 animate-slide-down">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('home') }}"
                        class="px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('home') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                        <i class="fas fa-home ml-3 w-4"></i>
                        الرئيسية
                    </a>
                    <a href="{{ route('logistics') }}"
                        class="px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('logistics') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                        <i class="fas fa-truck ml-3 w-4"></i>
                        الشركات اللوجستية
                    </a>
                    <a href="{{ route('clients') }}"
                        class="px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('clients') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                        <i class="fas fa-building ml-3 w-4"></i>
                        الشركات الطالبة
                    </a>
                    <a href="{{ route('store') }}"
                        class="px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('store') ? 'text-primary-600 font-semibold bg-primary-50' : 'text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50' }}">
                        <i class="fas fa-shopping-cart ml-3 w-4"></i>
                        متجر الأجهزة
                    </a>

                    <div class="border-t border-white/20 pt-2 mt-2">
                        <a href="#contact-section" onclick="scrollToContact(event)"
                            class="px-4 py-3 rounded-lg transition-all duration-300 text-secondary-700 hover:text-primary-600 hover:bg-primary-50/50">
                            <i class="fas fa-envelope ml-3 w-4"></i>
                            تواصل معنا
                        </a>
                        <a href="{{ route('login') }}"
                            class="mx-4 mt-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white px-4 py-3 rounded-lg font-medium hover:shadow-glow transition-all duration-300 inline-flex items-center">
                            <i class="fas fa-sign-in-alt ml-3 w-4"></i>
                            تسجيل الدخول
                        </a>
                    </div>
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
                                    <i class="fas fa-truck text-white text-2xl"></i>
                                </div>
                            </div>
                            <span class="text-2xl font-bold gradient-text">LogistiQ</span>
                        </div>
                        <p class="text-secondary-300 text-base leading-relaxed mb-6 max-w-md">
                            نقدم حلول التمويل المتقدمة للشركات اللوجستية وخدمات بيع أجهزة التتبع بأحدث التقنيات
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
                                <a href="{{ route('logistics') }}"
                                    class="group flex items-center text-secondary-300 hover:text-primary-400 transition-all duration-300">
                                    <div
                                        class="w-1 h-1 bg-primary-500 rounded-full ml-3 group-hover:w-3 transition-all duration-300">
                                    </div>
                                    تمويل الشركات اللوجستية
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('clients') }}"
                                    class="group flex items-center text-secondary-300 hover:text-primary-400 transition-all duration-300">
                                    <div
                                        class="w-1 h-1 bg-primary-500 rounded-full ml-3 group-hover:w-3 transition-all duration-300">
                                    </div>
                                    إدارة المستحقات
                                </a>
                            </li>
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
                                        info@logistiq.com</p>
                                </div>
                            </div>

                            <div class="flex items-center group">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-primary-600/20 rounded-lg ml-3 group-hover:bg-primary-600 transition-all duration-300">
                                    <i
                                        class="fas fa-phone text-primary-400 group-hover:text-white transition-colors"></i>
                                </div>
                                <div>
                                    <p class="text-secondary-300 group-hover:text-primary-400 transition-colors">+966
                                        11 123 4567</p>
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
                                        الرياض، المملكة العربية السعودية</p>
                                </div>
                            </div>
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
                            &copy; {{ date('Y') }} LogistiQ. جميع الحقوق محفوظة.
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
                        <div class="flex items-center space-x-3 space-x-reverse opacity-60">
                            <div
                                class="flex items-center justify-center w-8 h-5 bg-secondary-700 rounded text-xs text-white font-bold">
                                VISA
                            </div>
                            <div
                                class="flex items-center justify-center w-8 h-5 bg-blue-600 rounded text-xs text-white font-bold">
                                MC
                            </div>
                            <div
                                class="flex items-center justify-center w-8 h-5 bg-green-600 rounded text-xs text-white font-bold">
                                مدى
                            </div>
                        </div>
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
</body>

</html>
