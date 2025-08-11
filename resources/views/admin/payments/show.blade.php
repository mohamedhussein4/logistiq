@extends('layouts.admin')

@section('title', 'تفاصيل الدفعة')
@section('page-title', 'تفاصيل الدفعة')
@section('page-description', 'عرض تفاصيل المعاملة المالية')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">دفعة رقم #{{ $payment->id ?? '12345' }}</h1>
                <p class="text-slate-600">تفاصيل المعاملة المالية</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-green-100 text-green-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'cancelled' => 'bg-gray-100 text-gray-800',
                        'refunded' => 'bg-purple-100 text-purple-800'
                    ];
                    $statusNames = [
                        'pending' => 'معلق',
                        'confirmed' => 'مؤكد',
                        'completed' => 'مكتمل',
                        'rejected' => 'مرفوض',
                        'failed' => 'فاشل',
                        'cancelled' => 'ملغي',
                        'refunded' => 'مسترد'
                    ];
                    $currentStatus = $payment->status ?? 'pending';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusNames[$currentStatus] ?? 'غير محدد' }}
                </span>
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الدفعة</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">المبلغ</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 text-2xl font-black">
                            {{ number_format($payment->amount ?? 2500.00) }} ريال
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الدفع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            @php
                                $methods = [
                                    'bank_transfer' => 'تحويل بنكي',
                                    'credit_card' => 'بطاقة ائتمان',
                                    'cash' => 'نقدي',
                                    'check' => 'شيك'
                                ];
                            @endphp
                            {{ $methods[$payment->payment_method ?? 'bank_transfer'] }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم المرجع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-mono">
                            {{ $payment->reference_number ?? 'PAY-2024-001234' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الدفعة</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $payment->created_at->format('Y-m-d H:i') ?? '2024-01-15 14:30' }}
                        </div>
                    </div>
                </div>

                @if($payment->description ?? 'دفعة مقابل فاتورة رقم INV-2024-001')
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوصف</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $payment->description ?? 'دفعة مقابل فاتورة رقم INV-2024-001' }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Payer Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الدافع</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الدافع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ optional($payment->invoice->serviceCompany->user)->name ?? optional($payment->invoice->logisticsCompany->user)->name ?? 'غير محدد' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ optional($payment->invoice->serviceCompany->user)->email ?? optional($payment->invoice->logisticsCompany->user)->email ?? 'غير محدد' }}
                        </div>
                    </div>
                </div>

                @php
                    $userPhone = optional($payment->invoice->serviceCompany->user)->phone ?? optional($payment->invoice->logisticsCompany->user)->phone;
                @endphp
                @if($userPhone)
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $userPhone }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Bank Details (if bank transfer) -->
            @if(($payment->payment_method ?? 'bank_transfer') === 'bank_transfer')
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">تفاصيل التحويل البنكي</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">البنك المحول منه</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $payment->bank_details->from_bank ?? 'البنك الأهلي التجاري' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم الحساب</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-mono">
                            {{ $payment->bank_details->account_number ?? '1234567890123456' }}
                        </div>
                    </div>
                </div>

                @if($payment->bank_details->swift_code ?? 'NCBKSARI')
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">رمز SWIFT</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-mono">
                        {{ $payment->bank_details->swift_code ?? 'NCBKSARI' }}
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Receipt/Proof -->
            @if($payment->receipt_file ?? 'receipts/payment_receipt_12345.pdf')
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">إيصال الدفع</h3>

                <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center ml-4">
                        <i class="fas fa-file-pdf text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-900">إيصال الدفع</h4>
                        <p class="text-sm text-slate-600">تم رفعه في {{ $payment->created_at->format('Y-m-d H:i') ?? '2024-01-15 14:30' }}</p>
                    </div>
                    <div class="flex space-x-2 space-x-reverse">
                        <a href="{{ asset('/' . ($payment->receipt_file ?? 'receipts/payment_receipt_12345.pdf')) }}" target="_blank"
                           class="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            عرض
                        </a>
                        <a href="{{ asset('/' . ($payment->receipt_file ?? 'receipts/payment_receipt_12345.pdf')) }}" download
                           class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            تحميل
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Actions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">إجراءات الدفعة</h4>

                <div class="space-y-3">
                    @if($currentStatus === 'pending')
                    <form method="POST" action="{{ route('admin.payments.update_status', $payment->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            تأكيد الدفعة
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.payments.update_status', $payment->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" onclick="return confirm('هل أنت متأكد من رفض هذه الدفعة؟')" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            رفض الدفعة
                        </button>
                    </form>
                    @endif

                    @if($currentStatus === 'confirmed')
                    <form method="POST" action="{{ route('admin.payments.update_status', $payment->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="refunded">
                        <button type="submit" onclick="return confirm('هل أنت متأكد من استرداد هذه الدفعة؟')" class="w-full px-4 py-2 bg-purple-500 text-white rounded-xl font-semibold hover:bg-purple-600 transition-colors">
                            <i class="fas fa-undo mr-2"></i>
                            استرداد الدفعة
                        </button>
                    </form>
                    @endif

                    <button onclick="printReceipt()" class="w-full px-4 py-2 bg-gray-500 text-white rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        طباعة الإيصال
                    </button>

                    <button onclick="sendReceiptEmail()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        إرسال الإيصال
                    </button>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">معلومات إضافية</h4>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">رقم الدفعة:</span>
                        <span class="font-semibold">#{{ $payment->id ?? '12345' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">تاريخ الإنشاء:</span>
                        <span class="font-semibold">{{ $payment->created_at->format('Y-m-d') ?? '2024-01-15' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">آخر تحديث:</span>
                        <span class="font-semibold">{{ $payment->updated_at->format('Y-m-d H:i') ?? '2024-01-15 14:30' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">العملة:</span>
                        <span class="font-semibold">{{ $payment->currency ?? 'SAR' }}</span>
                    </div>

                    @if($payment->fees ?? 25.00)
                    <div class="flex justify-between">
                        <span class="text-slate-600">الرسوم:</span>
                        <span class="font-semibold">{{ number_format($payment->fees ?? 25.00) }} ريال</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transaction History -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">تاريخ المعاملة</h4>

                @php
                    $history = [
                        [
                            'action' => 'created',
                            'description' => 'تم إنشاء الدفعة',
                            'timestamp' => '2024-01-15 14:30',
                            'user' => 'النظام'
                        ],
                        [
                            'action' => 'verified',
                            'description' => 'تم التحقق من الدفعة',
                            'timestamp' => '2024-01-15 14:35',
                            'user' => 'أحمد الإدارة'
                        ]
                    ];
                @endphp

                <div class="space-y-3">
                    @foreach($history as $event)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-sm font-semibold text-slate-900">{{ $event['description'] }}</span>
                            <span class="text-xs text-slate-500">{{ $event['timestamp'] }}</span>
                        </div>
                        <p class="text-xs text-slate-600">بواسطة: {{ $event['user'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function printReceipt() {
        window.open('/admin/payments/{{ $payment->id ?? 1 }}/receipt', '_blank');
    }

    function sendReceiptEmail() {
        if (confirm('هل تريد إرسال الإيصال للعميل عبر البريد الإلكتروني؟')) {
            fetch('/admin/payments/{{ $payment->id ?? 1 }}/send-receipt', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                alert('تم إرسال الإيصال بنجاح');
            })
            .catch(error => {
                alert('حدث خطأ أثناء إرسال الإيصال');
            });
        }
    }
</script>
@endpush
@endsection
