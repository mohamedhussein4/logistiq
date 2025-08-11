@extends('layouts.admin')

@section('title', 'التقارير المالية')
@section('page-title', 'التقارير المالية')
@section('page-description', 'تقارير مفصلة عن الإيرادات والمصروفات والأرباح')

@section('content')
<div class="space-y-6">
    <!-- Financial Overview -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ number_format($financial['total_revenue'] ?? 250000) }} ريال</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">إجمالي الإيرادات</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ number_format($financial['total_expenses'] ?? 75000) }} ريال</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">إجمالي المصروفات</div>
            </div>
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ number_format($financial['net_profit'] ?? 175000) }} ريال</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">صافي الربح</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $financial['profit_margin'] ?? 70 }}%</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">هامش الربح</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">تطور الإيرادات الشهرية</h3>
            <div class="h-64 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl flex items-center justify-center">
                <div class="text-center text-green-600">
                    <i class="fas fa-chart-line text-4xl mb-4"></i>
                    <div class="text-lg font-bold">رسم بياني للإيرادات</div>
                    <div class="text-sm">سيتم إضافة المخططات قريباً</div>
                </div>
            </div>
        </div>

        <!-- Expenses Breakdown -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">توزيع المصروفات</h3>
            <div class="h-64 bg-gradient-to-br from-red-50 to-pink-50 rounded-xl flex items-center justify-center">
                <div class="text-center text-red-600">
                    <i class="fas fa-chart-pie text-4xl mb-4"></i>
                    <div class="text-lg font-bold">مخطط دائري للمصروفات</div>
                    <div class="text-sm">سيتم إضافة المخططات قريباً</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Sources -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-6">مصادر الإيرادات</h3>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold text-slate-800">عمولات التمويل</h4>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-blue-600 mb-2">{{ number_format($revenue_sources['funding_commission'] ?? 150000) }} ريال</div>
                <div class="text-sm text-slate-600">60% من إجمالي الإيرادات</div>
                <div class="mt-4 bg-blue-100 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 60%"></div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold text-slate-800">مبيعات المنتجات</h4>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-green-600 mb-2">{{ number_format($revenue_sources['product_sales'] ?? 75000) }} ريال</div>
                <div class="text-sm text-slate-600">30% من إجمالي الإيرادات</div>
                <div class="mt-4 bg-green-100 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-bold text-slate-800">خدمات أخرى</h4>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-purple-600 mb-2">{{ number_format($revenue_sources['other_services'] ?? 25000) }} ريال</div>
                <div class="text-sm text-slate-600">10% من إجمالي الإيرادات</div>
                <div class="mt-4 bg-purple-100 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: 10%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Financial Data -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">البيانات المالية الشهرية</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض آخر 6 أشهر</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button onclick="exportFinancial('excel')" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-file-excel mr-2"></i>
                    Excel
                </button>
                <button onclick="exportFinancial('pdf')" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-file-pdf mr-2"></i>
                    PDF
                </button>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">الشهر</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">الإيرادات</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">المصروفات</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">صافي الربح</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">النمو</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @php
                        $months = ['يناير 2024', 'فبراير 2024', 'مارس 2024', 'أبريل 2024', 'مايو 2024', 'يونيو 2024'];
                        $revenues = [45000, 52000, 48000, 65000, 58000, 72000];
                        $expenses = [15000, 17000, 16000, 20000, 18000, 22000];
                    @endphp

                    @foreach($months as $index => $month)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-900">{{ $month }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-black text-green-600">{{ number_format($revenues[$index]) }}</div>
                            <div class="text-sm text-slate-500">ريال</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-black text-red-600">{{ number_format($expenses[$index]) }}</div>
                            <div class="text-sm text-slate-500">ريال</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-black text-blue-600">{{ number_format($revenues[$index] - $expenses[$index]) }}</div>
                            <div class="text-sm text-slate-500">ريال</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $growth = $index > 0 ? (($revenues[$index] - $revenues[$index-1]) / $revenues[$index-1]) * 100 : 0;
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $growth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $growth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                {{ number_format(abs($growth), 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @foreach($months as $index => $month)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-bold text-slate-900">{{ $month }}</h4>
                    @php
                        $growth = $index > 0 ? (($revenues[$index] - $revenues[$index-1]) / $revenues[$index-1]) * 100 : 0;
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $growth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas fa-{{ $growth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ number_format(abs($growth), 1) }}%
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-lg font-black text-green-600">{{ number_format($revenues[$index]) }}</div>
                        <div class="text-xs text-slate-500">إيرادات</div>
                    </div>
                    <div>
                        <div class="text-lg font-black text-red-600">{{ number_format($expenses[$index]) }}</div>
                        <div class="text-xs text-slate-500">مصروفات</div>
                    </div>
                    <div>
                        <div class="text-lg font-black text-blue-600">{{ number_format($revenues[$index] - $expenses[$index]) }}</div>
                        <div class="text-xs text-slate-500">ربح</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportFinancial(type) {
        window.open(`/admin/reports/financial/export?type=${type}`, '_blank');
    }
</script>
@endpush
@endsection
