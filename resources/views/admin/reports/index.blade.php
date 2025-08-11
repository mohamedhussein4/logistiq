@extends('layouts.admin')

@section('title', 'التقارير والإحصائيات')
@section('page-title', 'التقارير والإحصائيات')
@section('page-description', 'نظام تقارير شامل وتحليلات متقدمة لأداء المنصة')

@section('content')
<div class="space-y-6">
    <!-- Header with Quick Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ number_format($overview['total_revenue'] ?? 0) }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي الإيرادات</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $overview['total_users'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">إجمالي المستخدمين</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $overview['total_transactions'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">إجمالي المعاملات</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $overview['growth_rate'] ?? 0 }}%</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">معدل النمو</div>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <form method="GET" id="filters-form" class="space-y-4 lg:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الفترة الزمنية</label>
                    <select name="date_range" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="last_7_days" {{ request('date_range') == 'last_7_days' ? 'selected' : '' }}>آخر 7 أيام</option>
                        <option value="last_30_days" {{ request('date_range') == 'last_30_days' ? 'selected' : '' }}>آخر 30 يوم</option>
                        <option value="last_3_months" {{ request('date_range') == 'last_3_months' ? 'selected' : '' }}>آخر 3 أشهر</option>
                        <option value="last_6_months" {{ request('date_range') == 'last_6_months' ? 'selected' : '' }}>آخر 6 أشهر</option>
                        <option value="last_year" {{ request('date_range') == 'last_year' ? 'selected' : '' }}>آخر سنة</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                    </select>
                </div>

                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع التقرير</label>
                    <select name="report_type" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="financial" {{ request('report_type') == 'financial' ? 'selected' : '' }}>التقارير المالية</option>
                        <option value="users" {{ request('report_type') == 'users' ? 'selected' : '' }}>تقارير المستخدمين</option>
                        <option value="products" {{ request('report_type') == 'products' ? 'selected' : '' }}>تقارير المنتجات</option>
                        <option value="orders" {{ request('report_type') == 'orders' ? 'selected' : '' }}>تقارير الطلبات</option>
                        <option value="funding" {{ request('report_type') == 'funding' ? 'selected' : '' }}>تقارير التمويل</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-chart-bar mr-2"></i>
                        عرض التقرير
                    </button>
                    <button type="button" onclick="exportReport()" class="px-4 py-3 lg:py-4 bg-gradient-success text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Report Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Financial Report Card -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hover-lift transition-all cursor-pointer" onclick="window.location.href='{{ route('admin.reports.financial') }}'">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">التقارير المالية</h3>
                    <p class="text-slate-600 text-sm">إيرادات، مصروفات، أرباح</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-black text-green-600">{{ number_format($reports['financial']['revenue'] ?? 0) }}</div>
                    <div class="text-xs text-slate-500">الإيرادات</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-blue-600">{{ number_format($reports['financial']['profit'] ?? 0) }}</div>
                    <div class="text-xs text-slate-500">الأرباح</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">آخر تحديث: اليوم</span>
                <span class="text-green-600 font-bold">+12.5%</span>
            </div>
        </div>

        <!-- Users Report Card -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hover-lift transition-all cursor-pointer" onclick="window.location.href='{{ route('admin.reports.users') }}'">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">تقارير المستخدمين</h3>
                    <p class="text-slate-600 text-sm">نشاط، تسجيل، انخراط</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-black text-blue-600">{{ $reports['users']['total'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">إجمالي المستخدمين</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-green-600">{{ $reports['users']['active'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">نشط</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">آخر تحديث: اليوم</span>
                <span class="text-blue-600 font-bold">+8.3%</span>
            </div>
        </div>

        <!-- Products Report Card -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hover-lift transition-all cursor-pointer" onclick="window.location.href='{{ route('admin.reports.products') }}'">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">تقارير المنتجات</h3>
                    <p class="text-slate-600 text-sm">مبيعات، مخزون، أداء</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-boxes text-white text-2xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-black text-purple-600">{{ $reports['products']['total_sales'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">إجمالي المبيعات</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-orange-600">{{ $reports['products']['low_stock'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">مخزون منخفض</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">آخر تحديث: اليوم</span>
                <span class="text-purple-600 font-bold">+15.7%</span>
            </div>
        </div>

        <!-- Orders Report Card -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hover-lift transition-all cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">تقارير الطلبات</h3>
                    <p class="text-slate-600 text-sm">معدلات، حالات، أداء</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-black text-orange-600">{{ $reports['orders']['total'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">إجمالي الطلبات</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-green-600">{{ $reports['orders']['completed'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">مكتملة</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">آخر تحديث: اليوم</span>
                <span class="text-orange-600 font-bold">+22.1%</span>
            </div>
        </div>

        <!-- Funding Report Card -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hover-lift transition-all cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">تقارير التمويل</h3>
                    <p class="text-slate-600 text-sm">طلبات، موافقات، صرف</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-black text-yellow-600">{{ $reports['funding']['total_requests'] ?? 0 }}</div>
                    <div class="text-xs text-slate-500">إجمالي الطلبات</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-green-600">{{ number_format($reports['funding']['original_amount'] ?? 0) }}</div>
                    <div class="text-xs text-slate-500">إجمالي المبلغ</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">آخر تحديث: اليوم</span>
                <span class="text-yellow-600 font-bold">+18.9%</span>
            </div>
        </div>

        <!-- Analytics Card -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 hover-lift transition-all cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">تحليلات متقدمة</h3>
                    <p class="text-slate-600 text-sm">رؤى، توقعات، اتجاهات</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-black text-indigo-600">94.5%</div>
                    <div class="text-xs text-slate-500">معدل الرضا</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-purple-600">87.2%</div>
                    <div class="text-xs text-slate-500">معدل النجاح</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">آخر تحديث: اليوم</span>
                <span class="text-indigo-600 font-bold">+5.4%</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-6">إجراءات سريعة</h3>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="generateReport('daily')" class="p-4 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition-all text-center">
                <i class="fas fa-calendar-day text-blue-500 text-2xl mb-2"></i>
                <div class="text-sm font-bold text-slate-700">تقرير يومي</div>
            </button>

            <button onclick="generateReport('weekly')" class="p-4 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition-all text-center">
                <i class="fas fa-calendar-week text-green-500 text-2xl mb-2"></i>
                <div class="text-sm font-bold text-slate-700">تقرير أسبوعي</div>
            </button>

            <button onclick="generateReport('monthly')" class="p-4 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition-all text-center">
                <i class="fas fa-calendar-alt text-purple-500 text-2xl mb-2"></i>
                <div class="text-sm font-bold text-slate-700">تقرير شهري</div>
            </button>

            <button onclick="generateCustomReport()" class="p-4 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition-all text-center">
                <i class="fas fa-cog text-orange-500 text-2xl mb-2"></i>
                <div class="text-sm font-bold text-slate-700">تقرير مخصص</div>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Export report function
    function exportReport() {
        const reportType = document.querySelector('select[name="report_type"]').value;
        const dateRange = document.querySelector('select[name="date_range"]').value;

        window.open(`/admin/reports/export?type=${reportType}&range=${dateRange}`, '_blank');
    }

    // Generate quick reports
    function generateReport(period) {
        window.location.href = `/admin/reports?report_type=financial&date_range=${period}`;
    }

    // Generate custom report
    function generateCustomReport() {
        // This would open a modal for custom report configuration
        alert('سيتم إضافة واجهة التقارير المخصصة قريباً');
    }

    // Auto-refresh stats every 5 minutes
    setInterval(function() {
        // Refresh page data
        location.reload();
    }, 300000); // 5 minutes
</script>
@endpush
@endsection
