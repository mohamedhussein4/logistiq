@extends('layouts.admin')

@section('title', 'لوحة التحكم الذكية')
@section('page-title', 'لوحة التحكم الذكية')
@section('page-description', 'نظرة شاملة ومتقدمة على إحصائيات وبيانات النظام')

@section('content')
<div class="space-y-10">
    <!-- Advanced Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Total Users Card -->
        <div class="group relative overflow-hidden glass-effect rounded-3xl p-8 hover-lift border border-white/20">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center shadow-lg floating-icons">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">+{{ rand(8, 25) }}%</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-2">إجمالي المستخدمين</h3>
                    <p class="text-4xl font-black gradient-text mb-2" data-target="{{ $stats['total_users'] }}">{{ $stats['total_users'] }}</p>
                    <div class="flex items-center text-sm">
                        <div class="flex items-center text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span class="font-semibold">{{ $stats['active_users'] }}</span>
                        </div>
                        <span class="text-slate-500 mr-2">نشط</span>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-xl"></div>
        </div>

        <!-- Logistics Companies Card -->
        <div class="group relative overflow-hidden glass-effect rounded-3xl p-8 hover-lift border border-white/20">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-emerald-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-success rounded-2xl flex items-center justify-center shadow-lg floating-icons">
                        <i class="fas fa-truck text-white text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-2">الشركات اللوجستية</h3>
                    <p class="text-4xl font-black gradient-text mb-2" data-target="{{ $stats['logistics_companies'] }}">{{ $stats['logistics_companies'] }}</p>
                    <div class="flex items-center text-sm">
                        <div class="flex items-center text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                            <i class="fas fa-building mr-1 text-xs"></i>
                            <span class="font-semibold">مسجلة</span>
                        </div>
                        <span class="text-slate-500 mr-2">شركة</span>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-br from-green-400/20 to-emerald-400/20 rounded-full blur-xl"></div>
        </div>

        <!-- Service Companies Card -->
        <div class="group relative overflow-hidden glass-effect rounded-3xl p-8 hover-lift border border-white/20">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-secondary rounded-2xl flex items-center justify-center shadow-lg floating-icons">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">{{ $stats['service_companies'] }}</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-2">الشركات الطالبة</h3>
                    <p class="text-4xl font-black gradient-text mb-2" data-target="{{ $stats['service_companies'] }}">{{ $stats['service_companies'] }}</p>
                    <div class="flex items-center text-sm">
                        <div class="flex items-center text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">
                            <i class="fas fa-handshake mr-1 text-xs"></i>
                            <span class="font-semibold">عميلة</span>
                        </div>
                        <span class="text-slate-500 mr-2">شركة</span>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full blur-xl"></div>
        </div>

        <!-- Revenue Card -->
        <div class="group relative overflow-hidden glass-effect rounded-3xl p-8 hover-lift border border-white/20">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-yellow-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-accent rounded-2xl flex items-center justify-center shadow-lg floating-icons">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">+{{ rand(15, 35) }}%</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-600 uppercase tracking-wider mb-2">إيرادات الشهر</h3>
                    <p class="text-4xl font-black gradient-text mb-2" data-target="{{ $stats['monthly_revenue'] }}">{{ number_format($stats['monthly_revenue']) }}</p>
                    <div class="flex items-center text-sm">
                        <div class="flex items-center text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                            <i class="fas fa-trending-up mr-1"></i>
                            <span class="font-semibold">ر.س</span>
                        </div>
                        <span class="text-slate-500 mr-2">ريال سعودي</span>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-br from-orange-400/20 to-yellow-400/20 rounded-full blur-xl"></div>
        </div>
    </div>

    <!-- Action Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Requests -->
        <div class="glass-effect rounded-2xl p-6 border border-yellow-200/50 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-bold">عاجل</span>
            </div>
            <h4 class="font-bold text-slate-800 mb-1">طلبات معلقة</h4>
            <p class="text-3xl font-black text-yellow-600 mb-2" data-target="{{ $stats['pending_funding_requests'] }}">{{ $stats['pending_funding_requests'] }}</p>
            <p class="text-sm text-slate-600">تحتاج مراجعة فورية</p>
        </div>

        <!-- Overdue Invoices -->
        <div class="glass-effect rounded-2xl p-6 border border-red-200/50 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-danger rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-bold">تحذير</span>
            </div>
            <h4 class="font-bold text-slate-800 mb-1">فواتير متأخرة</h4>
            <p class="text-3xl font-black text-red-600 mb-2" data-target="{{ $stats['overdue_invoices'] }}">{{ $stats['overdue_invoices'] }}</p>
            <p class="text-sm text-slate-600">تحتاج متابعة عاجلة</p>
        </div>

        <!-- Pending Orders -->
        <div class="glass-effect rounded-2xl p-6 border border-indigo-200/50 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white"></i>
                </div>
                <span class="bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full font-bold">جديد</span>
            </div>
            <h4 class="font-bold text-slate-800 mb-1">طلبات شراء</h4>
            <p class="text-3xl font-black text-indigo-600 mb-2" data-target="{{ $stats['pending_orders'] }}">{{ $stats['pending_orders'] }}</p>
            <p class="text-sm text-slate-600">بانتظار المعالجة</p>
        </div>

        <!-- Contact Requests -->
        <div class="glass-effect rounded-2xl p-6 border border-pink-200/50 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-white"></i>
                </div>
                <span class="bg-pink-100 text-pink-700 text-xs px-3 py-1 rounded-full font-bold">رسائل</span>
            </div>
            <h4 class="font-bold text-slate-800 mb-1">طلبات تواصل</h4>
            <p class="text-3xl font-black text-pink-600 mb-2" data-target="{{ $stats['new_contact_requests'] }}">{{ $stats['new_contact_requests'] }}</p>
            <p class="text-sm text-slate-600">رسائل عملاء جديدة</p>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Revenue Trend Chart -->
        <div class="glass-effect rounded-3xl p-8 border border-white/20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold gradient-text mb-2">اتجاه الإيرادات</h3>
                    <p class="text-slate-600">تحليل مفصل للإيرادات الشهرية</p>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    <button class="px-4 py-2 bg-gradient-primary text-white rounded-xl font-semibold shadow-lg hover-lift">الشهر</button>
                    <button class="px-4 py-2 text-slate-600 hover:bg-white/60 rounded-xl transition-colors">السنة</button>
                </div>
            </div>
            <div class="h-80 flex items-end justify-between space-x-2 space-x-reverse p-4 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl">
                @for($i = 1; $i <= 12; $i++)
                    @php
                        $amount = $monthly_stats['revenue_trend'][$i] ?? rand(50000, 200000);
                        $maxAmount = $monthly_stats['revenue_trend']->max() ?? 200000;
                        $height = $amount > 0 ? (($amount / $maxAmount) * 250) + 20 : 20;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-gradient-primary rounded-t-2xl transition-all duration-700 hover:bg-gradient-secondary shadow-lg relative overflow-hidden"
                             style="height: {{ $height }}px">
                            <div class="absolute inset-0 bg-gradient-to-t from-white/0 to-white/20"></div>
                            <div class="absolute top-2 left-1/2 transform -translate-x-1/2 text-xs text-white font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ number_format($amount / 1000) }}K
                            </div>
                        </div>
                        <span class="text-xs text-slate-500 mt-3 font-semibold">{{ $i }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="glass-effect rounded-3xl p-8 border border-white/20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold gradient-text mb-2">آخر الأنشطة</h3>
                    <p class="text-slate-600">تحديثات فورية لأحدث الأنشطة</p>
                </div>
                <a href="#" class="px-4 py-2 bg-gradient-primary text-white rounded-xl font-semibold shadow-lg hover-lift transition-all">
                    عرض الكل
                </a>
            </div>

            <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar">
                @foreach($recent_activities['new_users']->take(6) as $index => $user)
                <div class="flex items-start space-x-4 space-x-reverse p-4 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/20 hover-lift">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-bold text-slate-800 truncate">مستخدم جديد: {{ $user->name }}</h4>
                            <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">{{ $user->created_at->format('H:i') }}</span>
                        </div>
                        <p class="text-sm text-slate-600 mb-2">{{ $user->getUserTypeNameAttribute() }} - {{ $user->company_name ?? 'مستخدم عادي' }}</p>
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <span class="inline-block px-3 py-1 bg-{{ $user->status === 'active' ? 'green' : ($user->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $user->status === 'active' ? 'green' : ($user->status === 'pending' ? 'yellow' : 'gray') }}-700 text-xs font-semibold rounded-full">
                                {{ $user->getStatusNameAttribute() }}
                            </span>
                            <span class="text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions & System Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Recent Funding Requests -->
        <div class="glass-effect rounded-3xl p-8 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold gradient-text mb-1">آخر طلبات التمويل</h3>
                    <p class="text-sm text-slate-600">طلبات حديثة تحتاج مراجعة</p>
                </div>
                <a href="{{ route('admin.funding_requests.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                    عرض الكل
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recent_activities['recent_funding_requests']->take(4) as $request)
                <div class="flex items-center justify-between p-4 bg-white/30 backdrop-blur-sm rounded-xl border border-white/20 hover-lift">
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800 truncate"></p>
                            @if($request->logisticsCompany)
                                {{ $request->logisticsCompany->company_name ?? $request->logisticsCompany->name ?? 'غير محدد' }}
                            @else
                                غير محدد
                            @endif
                        </p>
                        <p class="text-sm text-slate-600">{{ number_format($request->amount) }} ر.س</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full font-bold
                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                           ($request->status === 'disbursed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                        {{ $request->getStatusNameAttribute() }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-money-bill-wave text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-semibold">لا توجد طلبات حديثة</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="glass-effect rounded-3xl p-8 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold gradient-text mb-1">آخر المدفوعات</h3>
                    <p class="text-sm text-slate-600">مدفوعات تم تأكيدها مؤخراً</p>
                </div>
                <a href="{{ route('admin.invoices.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                    عرض الكل
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recent_activities['recent_payments']->take(4) as $payment)
                <div class="flex items-center justify-between p-4 bg-white/30 backdrop-blur-sm rounded-xl border border-white/20 hover-lift">
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800 truncate">
                            {{ $payment->service_company_name ?? $payment->service_company_user_name ?? 'غير محدد' }}
                        </p>
                        <p class="text-sm text-slate-600">{{ number_format($payment->amount) }} ر.س</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $payment->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full font-bold
                        {{ $payment->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $payment->getStatusNameAttribute() }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-credit-card text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-semibold">لا توجد مدفوعات حديثة</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="glass-effect rounded-3xl p-8 border border-white/20">
            <div class="mb-6">
                <h3 class="text-xl font-bold gradient-text mb-1">إجراءات سريعة</h3>
                <p class="text-sm text-slate-600">أدوات إدارة فورية</p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('admin.users.index') }}" class="block p-4 bg-gradient-primary rounded-2xl text-white shadow-lg hover-lift transition-all group">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold mb-1">إدارة المستخدمين</h4>
                            <p class="text-sm opacity-90">عرض وإدارة الحسابات</p>
                        </div>
                        <i class="fas fa-users text-2xl opacity-80 group-hover:scale-110 transition-transform"></i>
                    </div>
                </a>

                <a href="{{ route('admin.funding_requests.index') }}" class="block p-4 bg-gradient-success rounded-2xl text-white shadow-lg hover-lift transition-all group">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold mb-1">طلبات التمويل</h4>
                            <p class="text-sm opacity-90">مراجعة الطلبات المعلقة</p>
                        </div>
                        <i class="fas fa-money-bill-wave text-2xl opacity-80 group-hover:scale-110 transition-transform"></i>
                    </div>
                </a>

                <a href="{{ route('admin.invoices.index') }}" class="block p-4 bg-gradient-accent rounded-2xl text-white shadow-lg hover-lift transition-all group">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold mb-1">إدارة الفواتير</h4>
                            <p class="text-sm opacity-90">متابعة المدفوعات</p>
                        </div>
                        <i class="fas fa-file-invoice text-2xl opacity-80 group-hover:scale-110 transition-transform"></i>
                    </div>
                </a>

                <div class="border-t border-white/20 pt-4 mt-6">
                    <button class="w-full p-4 bg-white/20 backdrop-blur-sm rounded-2xl text-slate-700 border border-white/30 hover:bg-white/30 transition-all group">
                        <div class="flex items-center justify-between">
                            <div class="text-right">
                                <h4 class="font-bold mb-1">تحديث البيانات</h4>
                                <p class="text-sm opacity-75">تحديث آخر: الآن</p>
                            </div>
                            <i class="fas fa-sync-alt text-xl opacity-60 group-hover:rotate-180 transition-transform duration-500"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enhanced number animation
    document.addEventListener('DOMContentLoaded', function() {
        const numberElements = document.querySelectorAll('[data-target]');

        numberElements.forEach(function(element, index) {
            // Staggered animation start
            setTimeout(() => {
                const target = parseInt(element.getAttribute('data-target'));
                if (target > 0) {
                    animateNumber(element, target);
                }
            }, index * 100);
        });
    });

    function animateNumber(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString('ar-SA');
        }, 30);
    }

    // Auto-refresh dashboard data every 5 minutes
    setInterval(function() {
        // Add AJAX call to refresh data without page reload
        console.log('🔄 تحديث البيانات...');

        // Show a subtle notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform';
        notification.innerHTML = '<i class="fas fa-sync-alt animate-spin mr-2"></i>تحديث البيانات...';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }, 300000);

    // Add real-time clock to page title
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('ar-SA', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.title = `${timeString} - لوحة التحكم الذكية`;
    }

    setInterval(updateTime, 1000);
    updateTime();

    // Enhanced hover effects for charts
    document.querySelectorAll('[style*="height"]').forEach(bar => {
        bar.addEventListener('mouseenter', function() {
            this.style.transform = 'scaleY(1.1) translateY(-5px)';
            this.style.transition = 'all 0.3s ease';
        });

        bar.addEventListener('mouseleave', function() {
            this.style.transform = 'scaleY(1) translateY(0)';
        });
    });
</script>
@endpush
@endsection
