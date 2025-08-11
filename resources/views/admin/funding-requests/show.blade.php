@extends('layouts.admin')

@section('title', 'تفاصيل طلب التمويل #' . $fundingRequest->id)
@section('page-title', 'تفاصيل طلب التمويل #' . $fundingRequest->id)
@section('page-description', 'عرض تفاصيل شاملة لطلب التمويل ومعلومات الشركة')

@section('content')
<div class="space-y-8">
    <!-- Back Button & Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.funding_requests.index') }}"
           class="inline-flex items-center px-6 py-3 bg-white/60 text-slate-700 rounded-xl font-semibold border border-white/40 hover:bg-white/80 transition-all">
            <i class="fas fa-arrow-right ml-3"></i>
            العودة لقائمة الطلبات
        </a>

        <div class="flex space-x-3 space-x-reverse">
            @if($fundingRequest->status === 'pending')
            <button onclick="approveRequest({{ $fundingRequest->id }})"
                    class="px-6 py-3 bg-green-600 text-white rounded-xl font-semibold shadow-lg hover:bg-green-700 transition-all">
                <i class="fas fa-check mr-2"></i>
                موافقة
            </button>
            <button onclick="rejectRequest({{ $fundingRequest->id }})"
                    class="px-6 py-3 bg-red-600 text-white rounded-xl font-semibold shadow-lg hover:bg-red-700 transition-all">
                <i class="fas fa-times mr-2"></i>
                رفض
            </button>
            @endif

            @if($fundingRequest->status === 'approved')
            <button onclick="disburseRequest({{ $fundingRequest->id }})"
                    class="px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold shadow-lg hover:bg-purple-700 transition-all">
                <i class="fas fa-money-bill-wave mr-2"></i>
                صرف المبلغ
            </button>
            @endif
        </div>
    </div>

    <!-- Request Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Request Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Request Info Card -->
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold gradient-text">تفاصيل الطلب</h2>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'under_review' => 'bg-blue-100 text-blue-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'disbursed' => 'bg-purple-100 text-purple-800'
                        ];
                        $statusNames = [
                            'pending' => 'معلق',
                            'under_review' => 'قيد المراجعة',
                            'approved' => 'موافق عليه',
                            'rejected' => 'مرفوض',
                            'disbursed' => 'مصروف'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$fundingRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$fundingRequest->status] ?? $fundingRequest->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="flex items-center p-4 bg-blue-50 rounded-2xl">
                            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center ml-4">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm text-blue-700 font-semibold">المبلغ المطلوب</div>
                                <div class="text-2xl font-black text-blue-900">{{ number_format($fundingRequest->amount) }} ريال</div>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-green-50 rounded-2xl">
                            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center ml-4">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm text-green-700 font-semibold">تاريخ الطلب</div>
                                <div class="text-lg font-bold text-green-900">{{ $fundingRequest->requested_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @if($fundingRequest->approved_at)
                        <div class="flex items-center p-4 bg-green-50 rounded-2xl">
                            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center ml-4">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm text-green-700 font-semibold">تاريخ الموافقة</div>
                                <div class="text-lg font-bold text-green-900">{{ $fundingRequest->approved_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($fundingRequest->disbursed_at)
                        <div class="flex items-center p-4 bg-purple-50 rounded-2xl">
                            <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center ml-4">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm text-purple-700 font-semibold">تاريخ الصرف</div>
                                <div class="text-lg font-bold text-purple-900">{{ $fundingRequest->disbursed_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($fundingRequest->rejected_at)
                        <div class="flex items-center p-4 bg-red-50 rounded-2xl">
                            <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center ml-4">
                                <i class="fas fa-times text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm text-red-700 font-semibold">تاريخ الرفض</div>
                                <div class="text-lg font-bold text-red-900">{{ $fundingRequest->rejected_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($fundingRequest->reason)
                <div class="mt-6 p-6 bg-slate-50 rounded-2xl">
                    <h4 class="text-lg font-bold text-slate-800 mb-3">سبب طلب التمويل</h4>
                    <p class="text-slate-700 leading-relaxed">{{ $fundingRequest->reason }}</p>
                </div>
                @endif

                @if($fundingRequest->admin_notes)
                <div class="mt-6 p-6 bg-blue-50 rounded-2xl border border-blue-200">
                    <h4 class="text-lg font-bold text-blue-800 mb-3">
                        <i class="fas fa-sticky-note mr-2"></i>
                        ملاحظات الإدارة
                    </h4>
                    <p class="text-blue-700 leading-relaxed">{{ $fundingRequest->admin_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Recent Requests from Same Company -->
            @if($recentRequests && $recentRequests->count() > 0)
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">طلبات سابقة من نفس الشركة</h3>

                <div class="space-y-4">
                    @foreach($recentRequests as $recentRequest)
                    <div class="flex items-center justify-between p-4 bg-white/60 rounded-2xl border border-white/40">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center ml-4">
                                <i class="fas fa-file-invoice text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">#{{ $recentRequest->id }}</div>
                                <div class="text-sm text-slate-600">{{ $recentRequest->requested_at->format('Y-m-d') }}</div>
                            </div>
                        </div>

                        <div class="text-left">
                            <div class="text-lg font-bold text-slate-900">{{ number_format($recentRequest->amount) }} ريال</div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$recentRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusNames[$recentRequest->status] ?? $recentRequest->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Company Info Sidebar -->
        <div class="space-y-8">
            <!-- Company Details -->
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">معلومات الشركة</h3>

                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-primary rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-white text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900">{{ optional($fundingRequest->logisticsCompany->user)->company_name ?? optional($fundingRequest->logisticsCompany->user)->name ?? 'غير محدد' }}</h4>
                    <p class="text-slate-600">{{ optional($fundingRequest->logisticsCompany->user)->email ?? 'غير محدد' }}</p>
                </div>

                <div class="space-y-4">
                                         <div class="flex justify-between items-center p-4 bg-blue-50 rounded-2xl">
                         <span class="text-sm font-semibold text-blue-700">الرصيد المتاح</span>
                         <span class="text-lg font-black text-blue-900">
                             @php
                                 $totalPaidInvoices = \App\Models\Invoice::where('logistics_company_id', $fundingRequest->logistics_company_id)
                                     ->where('payment_status', 'paid')
                                     ->sum('paid_amount');
                                 $totalOutstandingInvoices = \App\Models\Invoice::where('logistics_company_id', $fundingRequest->logistics_company_id)
                                     ->whereIn('payment_status', ['unpaid', 'partial'])
                                     ->sum('remaining_amount');
                                 $availableBalance = $totalPaidInvoices - $totalOutstandingInvoices;
                             @endphp
                             {{ number_format($availableBalance) }}
                         </span>
                     </div>

                                         <div class="flex justify-between items-center p-4 bg-green-50 rounded-2xl">
                         <span class="text-sm font-semibold text-green-700">إجمالي الممول</span>
                         <span class="text-lg font-black text-green-900">
                             @php
                                 $totalFunded = \App\Models\FundingRequest::where('logistics_company_id', $fundingRequest->logistics_company_id)
                                     ->whereIn('status', ['approved', 'disbursed'])
                                     ->sum('amount');
                             @endphp
                             {{ number_format($totalFunded) }}
                         </span>
                     </div>

                                         <div class="flex justify-between items-center p-4 bg-purple-50 rounded-2xl">
                         <span class="text-sm font-semibold text-purple-700">عدد الطلبات</span>
                         <span class="text-lg font-black text-purple-900">
                             @php
                                 $totalRequests = \App\Models\FundingRequest::where('logistics_company_id', $fundingRequest->logistics_company_id)->count();
                             @endphp
                             {{ $totalRequests }}
                         </span>
                     </div>

                     <div class="flex justify-between items-center p-4 bg-orange-50 rounded-2xl">
                         <span class="text-sm font-semibold text-orange-700">الائتمان المتبقي</span>
                         <span class="text-lg font-black text-orange-900">
                             @php
                                 $creditLimit = 100000; // الحد الائتماني الافتراضي
                                 $usedCredit = \App\Models\FundingRequest::where('logistics_company_id', $fundingRequest->logistics_company_id)
                                     ->whereIn('status', ['approved', 'disbursed'])
                                     ->sum('amount');
                                 $remainingCredit = max(0, $creditLimit - $usedCredit); // لا يمكن أن يكون سالباً
                             @endphp
                             {{ number_format($remainingCredit) }}
                         </span>
                     </div>
                </div>

                @if(optional($fundingRequest->logisticsCompany->user)->profile)
                <div class="mt-6 pt-6 border-t border-white/20">
                    <h5 class="text-lg font-bold text-slate-800 mb-4">معلومات الاتصال</h5>
                    <div class="space-y-3">
                        @if(optional($fundingRequest->logisticsCompany->user->profile)->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone w-5 text-slate-600 ml-3"></i>
                            <span class="text-slate-700">{{ $fundingRequest->logisticsCompany->user->profile->phone }}</span>
                        </div>
                        @endif

                        @if(optional($fundingRequest->logisticsCompany->user->profile)->address)
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt w-5 text-slate-600 ml-3"></i>
                            <span class="text-slate-700">{{ $fundingRequest->logisticsCompany->user->profile->address }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">إجراءات سريعة</h3>

                <div class="space-y-4">
                    @if($fundingRequest->logisticsCompany->user)
                    <a href="{{ route('admin.users.show', $fundingRequest->logisticsCompany->user) }}"
                       class="w-full px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold text-center block hover:bg-blue-700 transition-colors">
                        <i class="fas fa-user mr-2"></i>
                        عرض ملف الشركة
                    </a>
                    @endif

                    <a href="{{ route('admin.funding_requests.index', ['company_id' => $fundingRequest->logisticsCompany->id]) }}"
                       class="w-full px-6 py-3 bg-green-600 text-white rounded-xl font-semibold text-center block hover:bg-green-700 transition-colors">
                        <i class="fas fa-history mr-2"></i>
                        طلبات الشركة
                    </a>

                    <button onclick="window.print()"
                            class="w-full px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        طباعة التفاصيل
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Debts Section -->
    @if($fundingRequest->clientDebts && count($fundingRequest->clientDebts) > 0)
    <div class="glass-effect rounded-3xl p-8 border border-white/20">
        <h3 class="text-xl font-bold gradient-text mb-6">
            <i class="fas fa-building text-purple-600 mr-2"></i>
            العملاء المدينون ({{ count($fundingRequest->clientDebts) }} عميل)
        </h3>

        <div class="space-y-4">
            @foreach($fundingRequest->clientDebts as $index => $debt)
            <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-all">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Client Info -->
                    <div class="md:col-span-2">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">{{ $debt->company_name }}</h4>
                                <p class="text-sm text-gray-600">{{ $debt->contact_person }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $debt->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($debt->status === 'account_created' ? 'bg-blue-100 text-blue-800' :
                                   ($debt->status === 'invoice_sent' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800')) }}">
                                {{ $debt->status_label }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">البريد الإلكتروني:</span>
                                <p class="font-medium">{{ $debt->email }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">رقم الهاتف:</span>
                                <p class="font-medium">{{ $debt->phone }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">تاريخ الاستحقاق:</span>
                                <p class="font-medium">{{ $debt->due_date->format('Y-m-d') }}</p>
                            </div>
                            <div>
                                @if($debt->invoice_document)
                                <a href="{{ asset('/' . $debt->invoice_document) }}" target="_blank"
                                   class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-lg text-xs hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-file-pdf mr-1"></i>
                                    الفاتورة الأصلية
                                </a>
                                @else
                                <span class="text-gray-400 text-xs">لا يوجد مرفقات</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Amount & Actions -->
                    <div class="text-center md:text-right">
                        <div class="mb-4">
                            <div class="text-sm text-gray-500 mb-1">المبلغ المستحق</div>
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($debt->amount) }} ر.س</div>
                        </div>

                        @if($fundingRequest->status === 'approved' && $debt->status === 'pending')
                        <button onclick="createClientAccount({{ $debt->id }})"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                            <i class="fas fa-user-plus mr-1"></i>
                            إنشاء الحساب
                        </button>
                        @elseif($debt->created_user_id)
                        <a href="{{ route('admin.users.show', $debt->created_user_id) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user mr-1"></i>
                            عرض الحساب
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="mt-6 p-4 bg-purple-50 rounded-2xl">
            <div class="flex items-center justify-between">
                <span class="text-lg font-semibold text-purple-800">إجمالي المبالغ المطلوبة:</span>
                <span class="text-2xl font-bold text-purple-600">{{ number_format($fundingRequest->clientDebts->sum('amount')) }} ر.س</span>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Action Modals -->
@include('admin.funding-requests.modals')

@push('scripts')
<script>
    function approveRequest(id) {
        document.getElementById('approve-modal').classList.remove('hidden');
        document.getElementById('approve-form').action = `/admin/funding-requests/${id}/approve`;
    }

    function rejectRequest(id) {
        document.getElementById('reject-modal').classList.remove('hidden');
        document.getElementById('reject-form').action = `/admin/funding-requests/${id}/reject`;
    }

    function disburseRequest(id) {
        document.getElementById('disburse-modal').classList.remove('hidden');
        document.getElementById('disburse-form').action = `/admin/funding-requests/${id}/disburse`;
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function createClientAccount(debtId) {
        if (confirm('هل أنت متأكد من إنشاء حساب لهذا العميل؟ سيتم إرسال بيانات الدخول عبر البريد الإلكتروني.')) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/client-debts/${debtId}/create-account`;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Enhanced page animations
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.glass-effect');
        cards.forEach((card, index) => {
            card.style.animation = `slideUp 0.8s ease-out ${index * 0.2}s both`;
        });
    });
</script>

<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media print {
        .glass-effect {
            background: white !important;
            border: 1px solid #e5e7eb !important;
        }

        .bg-gradient-primary,
        .bg-blue-600,
        .bg-green-600,
        .bg-purple-600 {
            background: #374151 !important;
            color: white !important;
        }
    }
</style>
@endpush
@endsection
