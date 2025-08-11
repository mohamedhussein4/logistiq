<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - Link2u Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'arabic': ['Tajawal', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    },
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Tajawal', Arial, sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .gradient-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Mobile Menu Animation */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Sidebar for desktop */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            transform: translateX(100%);
        }

        /* Custom Scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .custom-scrollbar::-webkit-scrollbar-corner {
            background: transparent;
        }

        /* Message Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
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
            animation: slideDown 0.5s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-in;
        }

        /* Form Loading State */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #0ea5e9;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Table responsive */
        .table-responsive {
            min-width: 100%;
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .table-responsive table {
                min-width: 800px;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen font-arabic">
    <!-- Background Pattern -->
    <div class="fixed inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.15) 1px, transparent 0); background-size: 20px 20px;"></div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 right-0 h-screen w-80 glass-effect z-50 transform translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
        <div class="p-6 border-b border-white/20">
            <!-- Logo -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-truck text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-black gradient-text">Link2u</h1>
                        <p class="text-sm text-slate-600">لوحة التحكم</p>
                    </div>
                </div>

                <!-- Close button for mobile -->
                <button id="close-sidebar" class="lg:hidden w-8 h-8 flex items-center justify-center text-slate-600 hover:text-slate-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-6 custom-scrollbar overflow-y-auto" style="max-height: calc(100vh - 200px);">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">لوحة المعلومات</span>
                </a>

                <!-- Users Management -->
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-gradient-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">إدارة المستخدمين</span>
                </a>

                <!-- Funding Requests -->
                <a href="{{ route('admin.funding_requests.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.funding_requests.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-gradient-success rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">طلبات التمويل</span>
                </a>

                <!-- Invoices -->
                <a href="{{ route('admin.invoices.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.invoices.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-gradient-warning rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">إدارة الفواتير</span>
                </a>

                <!-- Products -->
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.products.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-box text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">إدارة المنتجات</span>
                </a>

                <!-- Orders -->
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.orders.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">إدارة الطلبات</span>
                </a>

                <!-- Payments -->
                <a href="{{ route('admin.payments.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.payments.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-credit-card text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">إدارة المدفوعات</span>
                </a>

                <!-- Contact Requests -->
                <a href="{{ route('admin.contact_requests.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.contact_requests.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-pink-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">طلبات التواصل</span>
                </a>

                <!-- Linking Services -->
                <a href="{{ route('admin.linking_services.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.linking_services.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-link text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">خدمات الربط</span>
                </a>

                <!-- Reports -->
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.reports.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-cyan-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">التقارير والتحليلات</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center space-x-4 space-x-reverse p-4 rounded-2xl hover:bg-white/30 transition-all group {{ request()->routeIs('admin.settings.*') ? 'bg-white/40 shadow-lg' : '' }}">
                    <div class="w-10 h-10 bg-slate-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-slate-900">الإعدادات</span>
                </a>
            </div>
        </nav>

        <!-- User Profile -->
        <div class="p-6 border-t border-white/20 flex-shrink-0">
            <div class="flex items-center space-x-4 space-x-reverse p-4 bg-white/20 rounded-2xl">
                <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-white"></i>
                </div>
                <div class="flex-1">
                    <div class="font-bold text-slate-800">مدير النظام</div>
                    <div class="text-sm text-slate-600">admin@Link2u.com</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white hover:bg-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:mr-80 min-h-screen flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white/60 backdrop-blur-sm border-b border-white/20 p-4 lg:p-6">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 bg-white/60 rounded-xl flex items-center justify-center text-slate-600 hover:text-slate-800 border border-white/40">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Page Title -->
                <div class="hidden lg:block">
                    <h1 class="text-2xl lg:text-3xl font-black gradient-text">@yield('page-title', 'لوحة التحكم')</h1>
                    <p class="text-slate-600 text-sm lg:text-base">@yield('page-description', 'إدارة ومتابعة جميع عمليات النظام')</p>
                </div>

                <!-- Quick Actions -->
                <div class="flex items-center space-x-3 space-x-reverse">
                    <!-- Notifications -->
                    <button class="relative w-10 h-10 bg-white/60 rounded-xl flex items-center justify-center text-slate-600 hover:text-slate-800 border border-white/40 hover-lift">
                        <i class="fas fa-bell"></i>
                        <span class="absolute -top-1 -left-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                    </button>

                    <!-- Profile -->
                    <div class="hidden lg:flex items-center space-x-3 space-x-reverse bg-white/60 rounded-xl px-4 py-2 border border-white/40">
                        <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <span class="font-semibold text-slate-700">مدير النظام</span>
                    </div>
                </div>
            </div>

            <!-- Mobile Page Title -->
            <div class="lg:hidden mt-4">
                <h1 class="text-xl font-black gradient-text">@yield('page-title', 'لوحة التحكم')</h1>
                <p class="text-slate-600 text-sm">@yield('page-description', 'إدارة ومتابعة جميع عمليات النظام')</p>
            </div>
        </header>

        <!-- Messages Area -->
        @if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
        <div class="p-4 lg:p-8 pb-0">
            <!-- Success Messages -->
            @if(session('success'))
            <div class="mb-4 p-4 lg:p-6 glass-effect rounded-2xl border border-green-200 bg-green-50/60 text-green-800 animate-slide-down" id="success-message">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">تمت العملية بنجاح!</h4>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button onclick="closeMessage('success-message')" class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center text-white hover:bg-green-700 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Error Messages -->
            @if(session('error') || $errors->any())
            <div class="mb-4 p-4 lg:p-6 glass-effect rounded-2xl border border-red-200 bg-red-50/60 text-red-800 animate-slide-down" id="error-message">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">حدث خطأ!</h4>
                        @if(session('error'))
                            <p>{{ session('error') }}</p>
                        @endif
                        @if($errors->any())
                            <ul class="list-disc list-inside space-y-1 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <button onclick="closeMessage('error-message')" class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center text-white hover:bg-red-700 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Warning Messages -->
            @if(session('warning'))
            <div class="mb-4 p-4 lg:p-6 glass-effect rounded-2xl border border-yellow-200 bg-yellow-50/60 text-yellow-800 animate-slide-down" id="warning-message">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-circle text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">تحذير!</h4>
                        <p>{{ session('warning') }}</p>
                    </div>
                    <button onclick="closeMessage('warning-message')" class="w-6 h-6 bg-yellow-600 rounded-full flex items-center justify-center text-white hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Info Messages -->
            @if(session('info'))
            <div class="mb-4 p-4 lg:p-6 glass-effect rounded-2xl border border-blue-200 bg-blue-50/60 text-blue-800 animate-slide-down" id="info-message">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">معلومة</h4>
                        <p>{{ session('info') }}</p>
                    </div>
                    <button onclick="closeMessage('info-message')" class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Content Area -->
        <div class="flex-1 p-4 lg:p-8">
            @yield('content')
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');
        const closeSidebar = document.getElementById('close-sidebar');

        function openMobileMenu() {
            sidebar.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            sidebar.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        mobileMenuBtn?.addEventListener('click', openMobileMenu);
        closeSidebar?.addEventListener('click', closeMobileMenu);
        overlay?.addEventListener('click', closeMobileMenu);

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Messages Management
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

        // Auto-hide success messages after 5 seconds
        setTimeout(() => {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                closeMessage('success-message');
            }
        }, 5000);

        // Auto-hide info messages after 8 seconds
        setTimeout(() => {
            const infoMessage = document.getElementById('info-message');
            if (infoMessage) {
                closeMessage('info-message');
            }
        }, 8000);

        // Form submission handling with loading states
        function handleFormSubmission() {
            const forms = document.querySelectorAll('form:not(.no-loading)');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Prevent double submission
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        // Disable submit button
                        submitButton.disabled = true;

                        // Store original content
                        const originalContent = submitButton.innerHTML;

                        // Show loading state
                        submitButton.innerHTML = '<div class="spinner border-2 border-white border-t-transparent rounded-full w-4 h-4 animate-spin inline-block mr-2"></div>جاري المعالجة...';

                        // Add loading overlay to form
                        const formRect = form.getBoundingClientRect();
                        if (form.style.position !== 'relative') {
                            form.style.position = 'relative';
                        }

                        const overlay = document.createElement('div');
                        overlay.className = 'loading-overlay';
                        overlay.innerHTML = '<div class="spinner"></div>';
                        form.appendChild(overlay);

                        // Re-enable after 10 seconds (fallback)
                        setTimeout(() => {
                            if (submitButton.disabled) {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalContent;
                                if (overlay.parentNode) {
                                    overlay.remove();
                                }
                            }
                        }, 10000);
                    }
                });
            });
        }

        // Initialize form handling when DOM is loaded
        document.addEventListener('DOMContentLoaded', handleFormSubmission);

        // Global AJAX setup for Laravel
        window.axios = window.axios || {};
        if (typeof axios !== 'undefined') {
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // Show notification function for JavaScript usage
        window.showNotification = function(type, title, message) {
            const container = document.querySelector('.p-4.lg\\:p-8 .pb-0') || document.body;

            const colors = {
                success: { bg: 'bg-green-50/60', border: 'border-green-200', text: 'text-green-800', icon: 'fa-check', iconBg: 'bg-green-500', btnBg: 'bg-green-600', btnHover: 'hover:bg-green-700' },
                error: { bg: 'bg-red-50/60', border: 'border-red-200', text: 'text-red-800', icon: 'fa-exclamation-triangle', iconBg: 'bg-red-500', btnBg: 'bg-red-600', btnHover: 'hover:bg-red-700' },
                warning: { bg: 'bg-yellow-50/60', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'fa-exclamation-circle', iconBg: 'bg-yellow-500', btnBg: 'bg-yellow-600', btnHover: 'hover:bg-yellow-700' },
                info: { bg: 'bg-blue-50/60', border: 'border-blue-200', text: 'text-blue-800', icon: 'fa-info-circle', iconBg: 'bg-blue-500', btnBg: 'bg-blue-600', btnHover: 'hover:bg-blue-700' }
            };

            const color = colors[type] || colors.info;
            const messageId = 'notification-' + Date.now();

            const notification = document.createElement('div');
            notification.id = messageId;
            notification.className = `mb-4 p-4 lg:p-6 glass-effect rounded-2xl border ${color.border} ${color.bg} ${color.text} animate-slide-down`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 ${color.iconBg} rounded-lg flex items-center justify-center mr-3">
                        <i class="fas ${color.icon} text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold mb-1">${title}</h4>
                        <p>${message}</p>
                    </div>
                    <button onclick="closeMessage('${messageId}')" class="w-6 h-6 ${color.btnBg} rounded-full flex items-center justify-center text-white ${color.btnHover} transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            `;

            container.appendChild(notification);

            // Auto-hide success and info after 5 seconds
            if (type === 'success' || type === 'info') {
                setTimeout(() => {
                    closeMessage(messageId);
                }, 5000);
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
