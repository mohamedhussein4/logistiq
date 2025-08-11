@extends('layouts.admin')

@section('title', 'تفاصيل الفاتورة ' . $invoice->invoice_number)
@section('page-title', 'تفاصيل الفاتورة ' . $invoice->invoice_number)
@section('page-description', 'عرض تفاصيل شاملة للفاتورة وحالة المدفوعات')

@section('content')
<div class="space-y-8">
    <!-- Back Button & Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.invoices.index') }}"
           class="inline-flex items-center px-6 py-3 bg-white/60 text-slate-700 rounded-xl font-semibold border border-white/40 hover:bg-white/80 transition-all">
            <i class="fas fa-arrow-right mr-3"></i>
            العودة لقائمة الفواتير
        </a>

        <div class="flex space-x-3 space-x-reverse">
            @if($invoice->payment_status !== 'paid')
            <button onclick="recordPayment({{ $invoice->id }})"
                    class="px-6 py-3 bg-green-600 text-white rounded-xl font-semibold shadow-lg hover:bg-green-700 transition-all">
                <i class="fas fa-money-bill mr-2"></i>
                تسجيل دفعة
            </button>
            @endif

            <a href="{{ route('admin.invoices.download_pdf', $invoice) }}"
               class="px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold shadow-lg hover:bg-purple-700 transition-all">
                <i class="fas fa-download mr-2"></i>
                تحميل PDF
            </a>

            <a href="{{ route('admin.invoices.edit', $invoice) }}"
               class="px-6 py-3 bg-orange-600 text-white rounded-xl font-semibold shadow-lg hover:bg-orange-700 transition-all">
                <i class="fas fa-edit mr-2"></i>
                تعديل
            </a>
        </div>
    </div>

    <!-- Invoice Overview -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Invoice Details -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Invoice Header -->
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-black gradient-text">{{ $invoice->invoice_number }}</h2>
                        <p class="text-slate-600 text-lg">تاريخ الإنشاء: {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    @php
                        $statusClasses = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'sent' => 'bg-blue-100 text-blue-800',
                            'overdue' => 'bg-red-100 text-red-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                        ];
                        $statusNames = [
                            'draft' => 'مسودة',
                            'sent' => 'مرسلة',
                            'overdue' => 'متأخرة',
                            'paid' => 'مدفوعة',
                            'cancelled' => 'ملغية'
                        ];

                        $paymentStatusClasses = [
                            'unpaid' => 'bg-red-100 text-red-800',
                            'partial' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800'
                        ];
                        $paymentStatusNames = [
                            'unpaid' => 'غير مدفوعة',
                            'partial' => 'دفع جزئي',
                            'paid' => 'مدفوعة كاملة'
                        ];
                    @endphp

                    <div class="flex flex-col items-end space-y-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusNames[$invoice->status] ?? $invoice->status }}
                        </span>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $paymentStatusClasses[$invoice->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $paymentStatusNames[$invoice->payment_status] ?? $invoice->payment_status }}
                        </span>
                    </div>
                </div>

                <!-- Companies Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Service Company -->
                    <div class="bg-orange-50 rounded-2xl p-6 border border-orange-200">
                        <h4 class="text-lg font-bold text-orange-800 mb-4">
                            <i class="fas fa-building mr-2"></i>
                            الشركة الطالبة للخدمة
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <div class="text-lg font-black text-slate-900">{{ $invoice->serviceCompany->user->company_name }}</div>
                                <div class="text-sm text-slate-600">{{ $invoice->serviceCompany->user->email }}</div>
                            </div>
                            @if($invoice->serviceCompany->user->profile)
                                @if($invoice->serviceCompany->user->profile->phone)
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-phone w-4 mr-2"></i>
                                    {{ $invoice->serviceCompany->user->profile->phone }}
                                </div>
                                @endif
                                @if($invoice->serviceCompany->user->profile->address)
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    {{ $invoice->serviceCompany->user->profile->address }}
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Logistics Company -->
                    <div class="bg-green-50 rounded-2xl p-6 border border-green-200">
                        <h4 class="text-lg font-bold text-green-800 mb-4">
                            <i class="fas fa-truck mr-2"></i>
                            الشركة اللوجستية
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <div class="text-lg font-black text-slate-900">{{ $invoice->logisticsCompany->user->company_name }}</div>
                                <div class="text-sm text-slate-600">{{ $invoice->logisticsCompany->user->email }}</div>
                            </div>
                            @if($invoice->logisticsCompany->user->profile)
                                @if($invoice->logisticsCompany->user->profile->phone)
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-phone w-4 mr-2"></i>
                                    {{ $invoice->logisticsCompany->user->profile->phone }}
                                </div>
                                @endif
                                @if($invoice->logisticsCompany->user->profile->address)
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    {{ $invoice->logisticsCompany->user->profile->address }}
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center bg-blue-50 rounded-2xl p-6 border border-blue-200">
                        <div class="text-3xl font-black text-blue-600 mb-2">{{ number_format($invoice->original_amount) }}</div>
                        <div class="text-sm text-blue-700 font-semibold">المبلغ الأصلي</div>
                    </div>

                    <div class="text-center bg-green-50 rounded-2xl p-6 border border-green-200">
                        <div class="text-3xl font-black text-green-600 mb-2">{{ number_format($invoice->paid_amount) }}</div>
                        <div class="text-sm text-green-700 font-semibold">المبلغ المدفوع</div>
                    </div>

                    <div class="text-center bg-red-50 rounded-2xl p-6 border border-red-200">
                        <div class="text-3xl font-black text-red-600 mb-2">{{ number_format($invoice->remaining_amount) }}</div>
                        <div class="text-sm text-red-700 font-semibold">المبلغ المتبقي</div>
                    </div>
                </div>

                <!-- Due Date -->
                <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-slate-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-slate-900">تاريخ الاستحقاق</div>
                            <div class="text-sm text-slate-600">{{ $invoice->due_date->format('Y-m-d') }}</div>
                        </div>
                    </div>

                    <div class="text-left">
                        @if($invoice->due_date->isPast() && $invoice->payment_status !== 'paid')
                            <div class="text-red-600 font-bold">متأخرة</div>
                            <div class="text-sm text-red-500">{{ $invoice->due_date->diffForHumans() }}</div>
                        @else
                            <div class="text-green-600 font-bold">{{ $invoice->due_date->diffForHumans() }}</div>
                        @endif
                    </div>
                </div>

                @if($invoice->description)
                <div class="mt-6 p-6 bg-blue-50 rounded-2xl border border-blue-200">
                    <h4 class="text-lg font-bold text-blue-800 mb-3">
                        <i class="fas fa-sticky-note mr-2"></i>
                        وصف الفاتورة
                    </h4>
                    <p class="text-blue-700 leading-relaxed">{{ $invoice->description }}</p>
                </div>
                @endif

                @if($invoice->admin_notes)
                <div class="mt-6 p-6 bg-purple-50 rounded-2xl border border-purple-200">
                    <h4 class="text-lg font-bold text-purple-800 mb-3">
                        <i class="fas fa-user-shield mr-2"></i>
                        ملاحظات الإدارة
                    </h4>
                    <p class="text-purple-700 leading-relaxed">{{ $invoice->admin_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Payments History -->
            @if($invoice->payments && $invoice->payments->count() > 0)
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">سجل المدفوعات</h3>

                <div class="space-y-4">
                    @foreach($invoice->payments as $payment)
                    <div class="flex items-center justify-between p-6 bg-white/60 rounded-2xl border border-white/40">
                        <div class="flex items-center">
                            @php
                                $paymentStatusClasses = [
                                    'pending' => 'bg-yellow-500',
                                    'confirmed' => 'bg-green-500',
                                    'rejected' => 'bg-red-500'
                                ];
                            @endphp
                            <div class="w-12 h-12 {{ $paymentStatusClasses[$payment->status] ?? 'bg-gray-500' }} rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-money-bill text-white"></i>
                            </div>
                            <div>
                                <div class="text-lg font-bold text-slate-900">{{ number_format($payment->amount) }} ريال</div>
                                <div class="text-sm text-slate-600">{{ $payment->payment_date->format('Y-m-d H:i') }}</div>
                                @if($payment->reference_number)
                                <div class="text-sm text-slate-500">المرجع: {{ $payment->reference_number }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="text-left">
                            @php
                                $methodNames = [
                                    'bank_transfer' => 'تحويل بنكي',
                                    'online_payment' => 'دفع إلكتروني',
                                    'check' => 'شيك',
                                    'cash' => 'نقدي'
                                ];
                                $statusNames = [
                                    'pending' => 'معلق',
                                    'confirmed' => 'مؤكد',
                                    'rejected' => 'مرفوض'
                                ];
                            @endphp
                            <div class="text-sm font-semibold text-slate-700">{{ $methodNames[$payment->payment_method] ?? $payment->payment_method }}</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $paymentStatusClasses[$payment->status] ?? 'bg-gray-100' }} text-white">
                                {{ $statusNames[$payment->status] ?? $payment->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Installment Plan -->
            @if($invoice->installmentPlan)
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">خطة التقسيط</h3>

                <div class="mb-6 p-6 bg-purple-50 rounded-2xl border border-purple-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-lg font-bold text-purple-900">{{ $invoice->installmentPlan->number_of_installments }}</div>
                            <div class="text-sm text-purple-700">عدد الأقساط</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-900">{{ number_format($invoice->installmentPlan->installment_amount) }}</div>
                            <div class="text-sm text-purple-700">قيمة القسط</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-900">{{ $invoice->installmentPlan->installmentPayments->where('status', 'paid')->count() }}</div>
                            <div class="text-sm text-purple-700">مدفوع</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-900">{{ $invoice->installmentPlan->installmentPayments->where('status', 'pending')->count() }}</div>
                            <div class="text-sm text-purple-700">متبقي</div>
                        </div>
                    </div>
                </div>

                @if($invoice->installmentPlan->installmentPayments)
                <div class="space-y-3">
                    @foreach($invoice->installmentPlan->installmentPayments as $installment)
                    <div class="flex items-center justify-between p-4 bg-white/60 rounded-xl border border-white/40">
                        <div class="flex items-center">
                            @php
                                $installmentStatusClasses = [
                                    'pending' => 'bg-yellow-500',
                                    'paid' => 'bg-green-500',
                                    'overdue' => 'bg-red-500'
                                ];
                            @endphp
                            <div class="w-10 h-10 {{ $installmentStatusClasses[$installment->status] ?? 'bg-gray-500' }} rounded-xl flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">{{ $installment->installment_number }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">القسط {{ $installment->installment_number }}</div>
                                <div class="text-sm text-slate-600">استحقاق: {{ $installment->due_date->format('Y-m-d') }}</div>
                            </div>
                        </div>

                        <div class="text-left">
                            <div class="text-lg font-bold text-slate-900">{{ number_format($installment->amount) }}</div>
                            @php
                                $installmentStatusNames = [
                                    'pending' => 'معلق',
                                    'paid' => 'مدفوع',
                                    'overdue' => 'متأخر'
                                ];
                            @endphp
                            <div class="text-sm {{ $installment->status === 'paid' ? 'text-green-600' : ($installment->status === 'overdue' ? 'text-red-600' : 'text-yellow-600') }} font-semibold">
                                {{ $installmentStatusNames[$installment->status] ?? $installment->status }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Quick Stats -->
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">معلومات سريعة</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-2xl">
                        <span class="text-sm font-semibold text-blue-700">نسبة السداد</span>
                        <span class="text-lg font-black text-blue-900">{{ number_format(($invoice->paid_amount / $invoice->original_amount) * 100, 1) }}%</span>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-2xl">
                        <span class="text-sm font-semibold text-green-700">عدد الدفعات</span>
                        <span class="text-lg font-black text-green-900">{{ $invoice->payments->count() }}</span>
                    </div>

                    @if($invoice->due_date->isPast() && $invoice->payment_status !== 'paid')
                    <div class="flex justify-between items-center p-4 bg-red-50 rounded-2xl">
                        <span class="text-sm font-semibold text-red-700">متأخرة بـ</span>
                        <span class="text-lg font-black text-red-900">{{ $invoice->due_date->diffInDays() }} يوم</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-effect rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold gradient-text mb-6">إجراءات سريعة</h3>

                <div class="space-y-4">
                    <a href="{{ route('admin.users.show', $invoice->serviceCompany->user) }}"
                       class="w-full px-6 py-3 bg-orange-600 text-white rounded-xl font-semibold text-center block hover:bg-orange-700 transition-colors">
                        <i class="fas fa-building mr-2"></i>
                        ملف الشركة الطالبة
                    </a>

                    <a href="{{ route('admin.users.show', $invoice->logisticsCompany->user) }}"
                       class="w-full px-6 py-3 bg-green-600 text-white rounded-xl font-semibold text-center block hover:bg-green-700 transition-colors">
                        <i class="fas fa-truck mr-2"></i>
                        ملف الشركة اللوجستية
                    </a>

                    <a href="{{ route('admin.invoices.index', ['service_company_id' => $invoice->serviceCompany->id]) }}"
                       class="w-full px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold text-center block hover:bg-blue-700 transition-colors">
                        <i class="fas fa-history mr-2"></i>
                        فواتير الشركة
                    </a>

                    <button onclick="window.print()"
                            class="w-full px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        طباعة الفاتورة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Recording Modal -->
<div id="payment-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">تسجيل دفعة جديدة</h3>
                <p class="text-slate-600">المبلغ المتبقي: <span class="font-bold text-red-600">{{ number_format($invoice->remaining_amount) }} ريال</span></p>
            </div>

            <form id="payment-form" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">المبلغ المدفوع <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" step="0.01" max="{{ $invoice->remaining_amount }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الدفع <span class="text-red-500">*</span></label>
                        <select name="payment_method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                            <option value="">اختر طريقة الدفع</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="online_payment">دفع إلكتروني</option>
                            <option value="check">شيك</option>
                            <option value="cash">نقدي</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم المرجع</label>
                        <input type="text" name="reference_number"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                               placeholder="رقم المعاملة أو المرجع">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الدفع <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات إضافية</label>
                    <textarea name="notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                              placeholder="أي ملاحظات إضافية حول الدفعة..."></textarea>
                </div>

                <div class="flex space-x-4 space-x-reverse pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        تسجيل الدفعة
                    </button>
                    <button type="button" onclick="closePaymentModal()"
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function recordPayment(invoiceId) {
        document.getElementById('payment-modal').classList.remove('hidden');
        document.getElementById('payment-form').action = `/admin/invoices/${invoiceId}/payment`;
    }

    function closePaymentModal() {
        document.getElementById('payment-modal').classList.add('hidden');
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
        .bg-purple-600,
        .bg-orange-600 {
            background: #374151 !important;
            color: white !important;
        }

        button,
        .hover-lift {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
