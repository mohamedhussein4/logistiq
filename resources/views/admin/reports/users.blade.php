@extends('layouts.admin')

@section('title', 'تقارير المستخدمين')
@section('page-title', 'تقارير المستخدمين')
@section('page-description', 'تقارير مفصلة عن نشاط وإحصائيات المستخدمين')

@section('content')
<div class="space-y-6">
    <!-- Users Overview -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $users_stats['total'] ?? 1250 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي المستخدمين</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $users_stats['active'] ?? 1125 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">مستخدمين نشطين</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $users_stats['new_this_month'] ?? 85 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">جديد هذا الشهر</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $users_stats['engagement_rate'] ?? 87 }}%</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">معدل التفاعل</div>
            </div>
        </div>
    </div>

    <!-- User Types Distribution -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-6">توزيع أنواع المستخدمين</h3>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-crown text-white text-xl"></i>
                </div>
                <div class="text-2xl font-black text-purple-600 mb-2">{{ $user_types['admin'] ?? 3 }}</div>
                <div class="text-sm text-slate-600 mb-2">مديرو النظام</div>
                <div class="text-xs text-purple-500 font-semibold">0.2%</div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-white text-xl"></i>
                </div>
                <div class="text-2xl font-black text-blue-600 mb-2">{{ $user_types['logistics'] ?? 145 }}</div>
                <div class="text-sm text-slate-600 mb-2">شركات لوجستية</div>
                <div class="text-xs text-blue-500 font-semibold">11.6%</div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div class="text-2xl font-black text-green-600 mb-2">{{ $user_types['service_company'] ?? 287 }}</div>
                <div class="text-sm text-slate-600 mb-2">شركات طالبة</div>
                <div class="text-xs text-green-500 font-semibold">23.0%</div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div class="text-2xl font-black text-orange-600 mb-2">{{ $user_types['regular'] ?? 815 }}</div>
                <div class="text-sm text-slate-600 mb-2">مستخدمين عاديين</div>
                <div class="text-xs text-orange-500 font-semibold">65.2%</div>
            </div>
        </div>
    </div>

    <!-- Registration Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Registrations -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">التسجيلات الشهرية</h3>
            <div class="h-64 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl flex items-center justify-center">
                <div class="text-center text-blue-600">
                    <i class="fas fa-chart-bar text-4xl mb-4"></i>
                    <div class="text-lg font-bold">رسم بياني للتسجيلات</div>
                    <div class="text-sm">سيتم إضافة المخططات قريباً</div>
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">نشاط المستخدمين</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full ml-3"></div>
                        <span class="text-sm font-semibold text-slate-700">نشط اليوم</span>
                    </div>
                    <span class="text-lg font-black text-green-600">{{ $activity['today'] ?? 425 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full ml-3"></div>
                        <span class="text-sm font-semibold text-slate-700">نشط هذا الأسبوع</span>
                    </div>
                    <span class="text-lg font-black text-blue-600">{{ $activity['week'] ?? 892 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full ml-3"></div>
                        <span class="text-sm font-semibold text-slate-700">نشط هذا الشهر</span>
                    </div>
                    <span class="text-lg font-black text-purple-600">{{ $activity['month'] ?? 1125 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-orange-500 rounded-full ml-3"></div>
                        <span class="text-sm font-semibold text-slate-700">متوسط الجلسة</span>
                    </div>
                    <span class="text-lg font-black text-orange-600">{{ $activity['avg_session'] ?? '24' }} دقيقة</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Users Table -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">أكثر المستخدمين نشاطاً</h3>
                <p class="text-slate-600 text-sm lg:text-base">أفضل 10 مستخدمين حسب النشاط</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button onclick="exportUsers('excel')" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-file-excel mr-2"></i>
                    Excel
                </button>
                <button onclick="exportUsers('pdf')" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
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
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">المستخدم</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">النوع</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">عدد الجلسات</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">آخر نشاط</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">التقييم</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @php
                        $top_users = [
                            ['name' => 'شركة النقل السريع', 'type' => 'logistics', 'sessions' => 145, 'last_active' => '2 دقيقة', 'score' => 98],
                            ['name' => 'مؤسسة التجارة الذكية', 'type' => 'service_company', 'sessions' => 132, 'last_active' => '5 دقائق', 'score' => 95],
                            ['name' => 'أحمد محمد السعيد', 'type' => 'user', 'sessions' => 89, 'last_active' => '10 دقائق', 'score' => 87],
                            ['name' => 'شركة الشحن المتقدم', 'type' => 'logistics', 'sessions' => 76, 'last_active' => '15 دقيقة', 'score' => 82],
                            ['name' => 'فاطمة علي الزهراني', 'type' => 'user', 'sessions' => 65, 'last_active' => '20 دقيقة', 'score' => 78],
                        ];

                        $type_icons = [
                            'logistics' => 'fa-truck',
                            'service_company' => 'fa-building',
                            'user' => 'fa-user',
                            'admin' => 'fa-crown'
                        ];

                        $type_names = [
                            'logistics' => 'شركة لوجستية',
                            'service_company' => 'شركة طالبة',
                            'user' => 'مستخدم',
                            'admin' => 'مدير'
                        ];

                        $type_colors = [
                            'logistics' => 'text-blue-600',
                            'service_company' => 'text-green-600',
                            'user' => 'text-orange-600',
                            'admin' => 'text-purple-600'
                        ];
                    @endphp

                    @foreach($top_users as $index => $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center ml-3">
                                    <i class="fas {{ $type_icons[$user['type']] }} text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $user['name'] }}</div>
                                    <div class="text-sm text-slate-500">#{{ $index + 1 }} في التصنيف</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-100 {{ $type_colors[$user['type']] }}">
                                {{ $type_names[$user['type']] }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-black text-slate-900">{{ $user['sessions'] }}</div>
                            <div class="text-sm text-slate-500">جلسة</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900">منذ {{ $user['last_active'] }}</div>
                            <div class="flex items-center mt-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-green-600 font-semibold">متصل</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 ml-3">
                                    <div class="h-2 rounded-full {{ $user['score'] >= 90 ? 'bg-green-500' : ($user['score'] >= 70 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                         style="width: {{ $user['score'] }}%"></div>
                                </div>
                                <span class="text-sm font-bold {{ $user['score'] >= 90 ? 'text-green-600' : ($user['score'] >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $user['score'] }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @foreach($top_users as $index => $user)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center ml-3">
                            <i class="fas {{ $type_icons[$user['type']] }} text-white text-sm"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">{{ $user['name'] }}</div>
                            <div class="text-sm text-slate-500">{{ $type_names[$user['type']] }}</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-black text-slate-900">{{ $user['sessions'] }}</div>
                        <div class="text-xs text-slate-500">جلسة</div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600">منذ {{ $user['last_active'] }}</div>
                    <div class="flex items-center">
                        <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2 w-16">
                            <div class="h-2 rounded-full {{ $user['score'] >= 90 ? 'bg-green-500' : ($user['score'] >= 70 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                 style="width: {{ $user['score'] }}%"></div>
                        </div>
                        <span class="text-sm font-bold {{ $user['score'] >= 90 ? 'text-green-600' : ($user['score'] >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $user['score'] }}%
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportUsers(type) {
        window.open(`/admin/reports/users/export?type=${type}`, '_blank');
    }
</script>
@endpush
@endsection
