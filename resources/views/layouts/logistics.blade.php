<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة تحكم الشركة اللوجستية') - Link2u</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts (Tajawal) -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Tajawal', sans-serif; }

        /* Glassmorphism Effects */
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation { animation: float 3s ease-in-out infinite; }

        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .slide-in { animation: slideIn 0.5s ease-out; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(45deg, #3b82f6, #8b5cf6); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(45deg, #2563eb, #7c3aed); }

        /* Gradient Backgrounds */
        .bg-logistics-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-card-gradient {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }

        /* Hover Effects */
        .hover-scale { transition: all 0.3s ease; }
        .hover-scale:hover { transform: scale(1.05); }

        .hover-glow { transition: all 0.3s ease; }
        .hover-glow:hover { box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .mobile-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .mobile-sidebar.open {
                transform: translateX(0);
            }
        }
    </style>

    @yield('extra_css')
</head>
<body class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 min-h-screen">

    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 right-0 h-full w-72 glass-effect z-50 mobile-sidebar lg:translate-x-0">
        <!-- Logo & Company Info -->
        <div class="p-6 border-b border-white/20">
            <div class="flex items-center space-x-reverse space-x-3 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-truck text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg">{{ Auth::user()->logisticsCompany->company_name ?? 'الشركة اللوجستية' }}</h2>
                    <p class="text-blue-200 text-sm">لوحة التحكم</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/10 rounded-lg p-3 text-center">
                    <div class="text-green-400 font-bold text-lg">{{ number_format(Auth::user()->logisticsCompany->available_balance ?? 0) }}</div>
                    <div class="text-white text-xs">الرصيد المتاح</div>
                </div>
                <div class="bg-white/10 rounded-lg p-3 text-center">
                    <div class="text-blue-400 font-bold text-lg">{{ Auth::user()->logisticsCompany->pending_requests_count ?? 0 }}</div>
                    <div class="text-white text-xs">طلبات معلقة</div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto p-4">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('logistics.dashboard') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.dashboard') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-tachometer-alt text-blue-400 group-hover:scale-110 transition-transform"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>

                <!-- Funding Requests -->
                <li>
                    <a href="{{ route('logistics.funding.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.funding.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-money-bill-wave text-green-400 group-hover:scale-110 transition-transform"></i>
                        <span>طلبات التمويل</span>
                        @if(Auth::user()->logisticsCompany->pending_requests_count ?? 0 > 0)
                            <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 mr-auto">
                                {{ Auth::user()->logisticsCompany->pending_requests_count }}
                            </span>
                        @endif
                    </a>
                </li>

                <!-- Finance -->
                <li>
                    <a href="{{ route('logistics.finance.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.finance.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-chart-line text-yellow-400 group-hover:scale-110 transition-transform"></i>
                        <span>الحالة المالية</span>
                    </a>
                </li>

                <!-- Invoices -->
                <li>
                    <a href="{{ route('logistics.invoices.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.invoices.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-file-invoice text-purple-400 group-hover:scale-110 transition-transform"></i>
                        <span>الفواتير</span>
                    </a>
                </li>

                <!-- Clients -->
                <li>
                    <a href="{{ route('logistics.clients.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.clients.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-building text-indigo-400 group-hover:scale-110 transition-transform"></i>
                        <span>العملاء</span>
                    </a>
                </li>

                <!-- Products -->
                <li>
                    <a href="{{ route('logistics.products.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.products.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-box text-orange-400 group-hover:scale-110 transition-transform"></i>
                        <span>المنتجات</span>
                    </a>
                </li>

                <!-- Orders -->
                <li>
                    <a href="{{ route('logistics.orders.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.orders.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-shopping-cart text-pink-400 group-hover:scale-110 transition-transform"></i>
                        <span>الطلبات</span>
                    </a>
                </li>

                <!-- Reports -->
                <li>
                    <a href="{{ route('logistics.reports.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.reports.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-chart-bar text-teal-400 group-hover:scale-110 transition-transform"></i>
                        <span>التقارير</span>
                    </a>
                </li>

                <!-- Settings -->
                <li>
                    <a href="{{ route('logistics.settings.index') }}"
                       class="flex items-center space-x-reverse space-x-3 p-3 rounded-xl text-white hover:bg-white/20 transition-all duration-300 group {{ request()->routeIs('logistics.settings.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-cog text-gray-400 group-hover:scale-110 transition-transform"></i>
                        <span>الإعدادات</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-white/20">
            <div class="flex items-center space-x-reverse space-x-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-white font-medium text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-blue-200 text-xs">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-reverse space-x-2 p-2 rounded-lg text-white hover:bg-red-500/20 transition-all duration-300">
                    <i class="fas fa-sign-out-alt text-red-400"></i>
                    <span>تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:mr-72 min-h-screen">
        <!-- Top Header -->
        <header class="glass-effect p-4 lg:p-6 flex items-center justify-between">
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden text-white hover:text-blue-300 transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Page Title -->
            <div class="flex-1 lg:flex-none">
                <h1 class="text-white text-xl lg:text-2xl font-bold">@yield('page_title', 'لوحة التحكم')</h1>
                <p class="text-blue-200 text-sm">@yield('page_description', 'إدارة شركتك اللوجستية')</p>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center space-x-reverse space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button class="text-white hover:text-blue-300 transition-colors relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-2 -left-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="hidden lg:flex items-center space-x-reverse space-x-2">
                    <a href="{{ route('logistics.funding.create') }}"
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center space-x-reverse space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>طلب تمويل</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Messages Area -->
        @if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
        <div class="p-4 lg:p-6 pb-0">
            @if(session('success'))
            <div class="mb-4 p-4 glass-effect rounded-xl border border-green-200 bg-green-50/10 text-green-300 slide-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 ml-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 glass-effect rounded-xl border border-red-200 bg-red-50/10 text-red-300 slide-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 ml-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="mb-4 p-4 glass-effect rounded-xl border border-yellow-200 bg-yellow-50/10 text-yellow-300 slide-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-400 ml-3"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 glass-effect rounded-xl border border-red-200 bg-red-50/10 text-red-300 slide-in">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-400 ml-3 mt-1"></i>
                    <div>
                        <p class="font-medium mb-2">يرجى تصحيح الأخطاء التالية:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Page Content -->
        <div class="p-4 lg:p-8">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');

        mobileMenuBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
        });

        // Auto-hide messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.slide-in');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transform = 'translateX(100%)';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);

        // Add loading states for forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري المعالجة...';
                }
            });
        });
    </script>

    @yield('extra_js')
</body>
</html>
