@extends('layouts.admin')

@section('title', 'تفاصيل خدمة الربط')
@section('page-title', 'تفاصيل خدمة الربط')
@section('page-description', 'عرض تفاصيل خدمة الربط وإدارة الشراكات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">{{ $linkingService->name ?? 'خدمة التمويل اللوجستي المتقدم' }}</h1>
                <p class="text-slate-600">{{ $linkingService->description ?? 'خدمة ربط متقدمة للشركات اللوجستية مع شركات التمويل المرخصة' }}</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                @php
                    $statusClasses = [
                        'active' => 'bg-green-100 text-green-800',
                        'inactive' => 'bg-gray-100 text-gray-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'completed' => 'bg-blue-100 text-blue-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'suspended' => 'bg-red-100 text-red-800'
                    ];
                    $statusNames = [
                        'active' => 'نشط',
                        'inactive' => 'غير نشط',
                        'pending' => 'معلق',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                        'suspended' => 'موقوف'
                    ];
                    $currentStatus = $linkingService->status ?? 'active';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$currentStatus] }}">
                    {{ $statusNames[$currentStatus] }}
                </span>
                <a href="{{ route('admin.linking_services.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Service Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الخدمة</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الخدمة</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-semibold">
                            {{ $linkingService->name ?? 'خدمة التمويل اللوجستي المتقدم' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع الخدمة</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            @php
                                $serviceTypes = [
                                    'funding' => 'تمويل',
                                    'logistics' => 'لوجستية',
                                    'payment' => 'مدفوعات',
                                    'insurance' => 'تأمين'
                                ];
                            @endphp
                            {{ $serviceTypes[$linkingService->type ?? 'funding'] }}
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">وصف الخدمة</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 min-h-24">
                        {{ $linkingService->description ?? 'خدمة ربط متقدمة تهدف إلى ربط الشركات اللوجستية بشركات التمويل المرخصة لتوفير حلول تمويلية مرنة ومبتكرة. تتضمن الخدمة تقييم المخاطر، إدارة التدفق النقدي، وضمان الدفعات.' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">معدل العمولة</label>
                        <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-2xl font-black text-center">
                            {{ $linkingService->commission_rate ?? '2.5' }}%
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحد الأدنى للمبلغ</label>
                        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-900 font-bold text-center">
                            {{ number_format($linkingService->min_amount ?? 10000) }} ريال
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحد الأقصى للمبلغ</label>
                        <div class="px-4 py-3 bg-purple-50 border border-purple-200 rounded-xl text-purple-900 font-bold text-center">
                            {{ number_format($linkingService->max_amount ?? 1000000) }} ريال
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partners -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">الشركاء المرتبطين</h3>

                @php
                    $partners = $linkingService->partners ?? [
                        [
                            'id' => 1,
                            'name' => 'بنك الراجحي',
                            'type' => 'funding_company',
                            'status' => 'active',
                            'partnership_since' => '2023-01-15',
                            'total_transactions' => 145,
                            'original_amount' => 2500000
                        ],
                        [
                            'id' => 2,
                            'name' => 'شركة النقل السريع',
                            'type' => 'logistics_company',
                            'status' => 'active',
                            'partnership_since' => '2023-03-20',
                            'total_transactions' => 89,
                            'original_amount' => 1800000
                        ],
                        [
                            'id' => 3,
                            'name' => 'البنك الأهلي التجاري',
                            'type' => 'funding_company',
                            'status' => 'active',
                            'partnership_since' => '2023-06-10',
                            'total_transactions' => 67,
                            'original_amount' => 1200000
                        ]
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($partners as $partner)
                    <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center ml-4 {{ $partner['type'] === 'funding_company' ? 'bg-green-500' : 'bg-blue-500' }}">
                            <i class="fas {{ $partner['type'] === 'funding_company' ? 'fa-university' : 'fa-truck' }} text-white"></i>
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900">{{ $partner['name'] }}</h4>
                            <p class="text-sm text-slate-600">{{ $partner['type'] === 'funding_company' ? 'شركة تمويل' : 'شركة لوجستية' }}</p>
                            <p class="text-xs text-slate-500">شريك منذ: {{ $partner['partnership_since'] }}</p>
                        </div>

                        <div class="text-center ml-4">
                            <div class="text-lg font-black text-slate-900">{{ $partner['total_transactions'] }}</div>
                            <div class="text-xs text-slate-500">معاملة</div>
                        </div>

                        <div class="text-center">
                            <div class="text-lg font-black text-slate-900">{{ number_format($partner['original_amount']) }}</div>
                            <div class="text-xs text-slate-500">ريال</div>
                        </div>
                    </div>
                    @endforeach

                    <button onclick="addNewPartner()" class="w-full p-4 border-2 border-dashed border-gray-300 rounded-xl text-center hover:border-blue-500 hover:bg-blue-50 transition-all">
                        <i class="fas fa-plus text-gray-400 mr-2"></i>
                        <span class="text-gray-600 font-semibold">إضافة شريك جديد</span>
                    </button>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">المعاملات الأخيرة</h3>

                @php
                    $recentTransactions = [
                        [
                            'id' => 'TXN-2024-001',
                            'amount' => 75000,
                            'commission' => 1875,
                            'date' => '2024-01-15',
                            'status' => 'completed',
                            'logistics_company' => 'شركة النقل السريع',
                            'funding_company' => 'بنك الراجحي'
                        ],
                        [
                            'id' => 'TXN-2024-002',
                            'amount' => 120000,
                            'commission' => 3000,
                            'date' => '2024-01-14',
                            'status' => 'completed',
                            'logistics_company' => 'مؤسسة الشحن المتطور',
                            'funding_company' => 'البنك الأهلي التجاري'
                        ]
                    ];
                @endphp

                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-sm font-bold text-slate-700">رقم المعاملة</th>
                                <th class="px-4 py-3 text-right text-sm font-bold text-slate-700">المبلغ</th>
                                <th class="px-4 py-3 text-right text-sm font-bold text-slate-700">العمولة</th>
                                <th class="px-4 py-3 text-right text-sm font-bold text-slate-700">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $transaction['id'] }}</div>
                                    <div class="text-sm text-slate-600">{{ $transaction['logistics_company'] }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-900">{{ number_format($transaction['amount']) }} ريال</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-green-600">{{ number_format($transaction['commission']) }} ريال</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-slate-900">{{ $transaction['date'] }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Service Actions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">إجراءات الخدمة</h4>

                <div class="space-y-3">
                    @if($currentStatus === 'active')
                    <form method="POST" action="{{ route('admin.linking_services.update_status', $linkingService->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="inactive">
                        <button type="submit" onclick="return confirm('هل تريد إيقاف هذه الخدمة؟')" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-xl font-semibold hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-pause mr-2"></i>
                            إيقاف الخدمة
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.linking_services.update_status', $linkingService->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            تفعيل الخدمة
                        </button>
                    </form>
                    @endif

                    {{-- <a href="{{ route('admin.linking_services.edit', $linkingService->id ?? 1) }}" class="block w-full px-4 py-2 bg-blue-500 text-white rounded-xl font-semibold text-center hover:bg-blue-600 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        تعديل الخدمة
                    </a> --}}

                    <button onclick="updateCommission()" class="w-full px-4 py-2 bg-purple-500 text-white rounded-xl font-semibold hover:bg-purple-600 transition-colors">
                        <i class="fas fa-percentage mr-2"></i>
                        تحديث العمولة
                    </button>

                    <button onclick="generateReport()" class="w-full px-4 py-2 bg-gray-500 text-white rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>
                        تقرير الأداء
                    </button>
                </div>
            </div>

            <!-- Service Statistics -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">إحصائيات الخدمة</h4>

                <div class="space-y-4">
                    <div class="text-center bg-blue-50 rounded-xl p-4">
                        <div class="text-2xl font-black text-blue-600">{{ $linkingService->total_partnerships ?? 8 }}</div>
                        <div class="text-sm text-blue-700 font-semibold">إجمالي الشراكات</div>
                    </div>

                    <div class="text-center bg-green-50 rounded-xl p-4">
                        <div class="text-2xl font-black text-green-600">{{ $linkingService->total_transactions ?? 301 }}</div>
                        <div class="text-sm text-green-700 font-semibold">إجمالي المعاملات</div>
                    </div>

                    <div class="text-center bg-purple-50 rounded-xl p-4">
                        <div class="text-lg font-black text-purple-600">{{ number_format($linkingService->total_volume ?? 5500000) }}</div>
                        <div class="text-sm text-purple-700 font-semibold">حجم المعاملات (ريال)</div>
                    </div>

                    <div class="text-center bg-yellow-50 rounded-xl p-4">
                        <div class="text-lg font-black text-yellow-600">{{ number_format($linkingService->total_commission ?? 137500) }}</div>
                        <div class="text-sm text-yellow-700 font-semibold">إجمالي العمولات (ريال)</div>
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">معلومات الخدمة</h4>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">تاريخ الإنشاء:</span>
                        <span class="font-semibold">{{ $linkingService->created_at->format('Y-m-d') ?? '2023-01-15' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">آخر تحديث:</span>
                        <span class="font-semibold">{{ $linkingService->updated_at->format('Y-m-d') ?? '2024-01-15' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">المدة المتوسطة:</span>
                        <span class="font-semibold">{{ $linkingService->average_duration ?? '7' }} أيام</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">معدل النجاح:</span>
                        <span class="font-semibold text-green-600">{{ $linkingService->success_rate ?? '94.5' }}%</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">التقييم:</span>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-yellow-500 text-xs {{ $i <= ($linkingService->rating ?? 4.8) ? '' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="font-semibold mr-1">{{ $linkingService->rating ?? '4.8' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commission Update Modal -->
<div id="commission-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-md w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-percentage text-purple-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">تحديث معدل العمولة</h3>
                <p class="text-slate-600 text-sm lg:text-base">أدخل معدل العمولة الجديد للخدمة</p>
            </div>

            <form method="POST" action="{{ route('admin.linking_services.update_commission', $linkingService->id ?? 1) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">معدل العمولة الحالي</label>
                    <div class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-slate-900 font-bold">
                        {{ $linkingService->commission_rate ?? '2.5' }}%
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">معدل العمولة الجديد <span class="text-red-500">*</span></label>
                    <input type="number" name="commission_rate" step="0.01" min="0" max="100" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                           placeholder="2.50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">سبب التغيير</label>
                    <textarea name="reason" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                              placeholder="اختياري - سبب تحديث العمولة"></textarea>
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        تحديث العمولة
                    </button>
                    <button type="button" onclick="closeCommissionModal()"
                            class="w-full lg:flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateCommission() {
        document.getElementById('commission-modal').classList.remove('hidden');
    }

    function closeCommissionModal() {
        document.getElementById('commission-modal').classList.add('hidden');
    }

    function addNewPartner() {
        alert('سيتم إضافة ميزة إضافة الشركاء قريباً');
    }

    function generateReport() {
        window.open('/admin/linking-services/{{ $linkingService->id ?? 1 }}/report', '_blank');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'commission-modal') {
            closeCommissionModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCommissionModal();
        }
    });
</script>
@endpush
@endsection
