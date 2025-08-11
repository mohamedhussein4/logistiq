@extends('layouts.admin')

@section('title', 'إدارة المدفوعات')
@section('page-title', 'إدارة المدفوعات')
@section('page-description', 'نظام إدارة ومتابعة جميع المعاملات المالية والمدفوعات')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_payments'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي المدفوعات</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-sm lg:text-xl font-black text-green-600">{{ number_format($stats['successful_amount'] ?? 0) }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">المبلغ المُحصل</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['pending_payments'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">معلقة</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ $stats['failed_payments'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">فاشلة</div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <form method="GET" id="filters-form" class="space-y-4 lg:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البحث</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="ابحث برقم الدفعة، العميل، أو المبلغ..."
                               class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700 placeholder-slate-400">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فاشل</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                    </select>
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الدفع</label>
                    <select name="payment_method" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الطرق</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                        <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة المدفوعات</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $payments->count() }} من أصل {{ $payments->total() }} دفعة</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">دفعة جديدة</span>
                    <span class="lg:hidden">جديد</span>
                </button>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @forelse($payments as $payment)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="text-sm font-bold text-slate-900">#{{ $payment->id }}</div>
                        <div class="text-lg font-black text-slate-900">{{ number_format($payment->amount) }} ريال</div>
                        <div class="text-sm text-slate-600">{{ $payment->user->name ?? 'غير محدد' }}</div>
                    </div>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'failed' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                            'refunded' => 'bg-purple-100 text-purple-800'
                        ];
                        $statusNames = [
                            'pending' => 'معلق',
                            'completed' => 'مكتمل',
                            'failed' => 'فاشل',
                            'cancelled' => 'ملغي',
                            'refunded' => 'مسترد'
                        ];
                        $methodClasses = [
                            'bank_transfer' => 'bg-blue-100 text-blue-800',
                            'credit_card' => 'bg-purple-100 text-purple-800',
                            'cash' => 'bg-green-100 text-green-800',
                            'check' => 'bg-orange-100 text-orange-800'
                        ];
                        $methodNames = [
                            'bank_transfer' => 'تحويل بنكي',
                            'credit_card' => 'بطاقة ائتمان',
                            'cash' => 'نقدي',
                            'check' => 'شيك'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$payment->status] ?? $payment->status }}
                    </span>
                </div>

                <div class="mb-3">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $methodClasses[$payment->payment_method] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $methodNames[$payment->payment_method] ?? $payment->payment_method }}
                    </span>
                    <div class="text-xs text-slate-500 mt-1">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
                    @if($payment->reference_number)
                    <div class="text-xs text-slate-600 mt-1">المرجع: {{ $payment->reference_number }}</div>
                    @endif
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.payments.show', $payment) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    @if($payment->status === 'pending')
                    <button onclick="updateStatus({{ $payment->id }}, 'completed')"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                        تأكيد
                    </button>

                    <button onclick="updateStatus({{ $payment->id }}, 'failed')"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition-colors">
                        رفض
                    </button>
                    @endif

                    @if($payment->status === 'completed')
                    <button onclick="updateStatus({{ $payment->id }}, 'refunded')"
                            class="px-4 py-2 bg-purple-500 text-white rounded-lg text-sm font-semibold hover:bg-purple-600 transition-colors">
                        استرداد
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد مدفوعات</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على مدفوعات تطابق معايير البحث</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200">
            <div class="table-responsive">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                رقم الدفعة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                العميل
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المبلغ
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                طريقة الدفع
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الحالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                التاريخ
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900">#{{ $payment->id }}</div>
                                @if($payment->reference_number)
                                <div class="text-xs text-slate-500">{{ $payment->reference_number }}</div>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center ml-3">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $payment->user->name ?? 'غير محدد' }}</div>
                                        @if($payment->user)
                                        <div class="text-sm text-slate-500">{{ $payment->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ number_format($payment->amount) }}</div>
                                <div class="text-sm text-slate-500">ريال سعودي</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $methodClasses[$payment->payment_method] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $methodNames[$payment->payment_method] ?? $payment->payment_method }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$payment->status] ?? $payment->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $payment->created_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-slate-500">{{ $payment->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    @if($payment->status === 'pending')
                                    <button onclick="updateStatus({{ $payment->id }}, 'completed')"
                                            class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>

                                    <button onclick="updateStatus({{ $payment->id }}, 'failed')"
                                            class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    @endif

                                    @if($payment->status === 'completed')
                                    <button onclick="updateStatus({{ $payment->id }}, 'refunded')"
                                            class="w-8 h-8 bg-purple-500 hover:bg-purple-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-undo text-xs"></i>
                                    </button>
                                    @endif

                                    <button onclick="downloadReceipt({{ $payment->id }})"
                                            class="w-8 h-8 bg-orange-500 hover:bg-orange-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-download text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-credit-card text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد مدفوعات</h3>
                                    <p class="text-slate-500">لم يتم العثور على مدفوعات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $payments->firstItem() }} إلى {{ $payments->lastItem() }} من أصل {{ $payments->total() }} دفعة
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($payments->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $payments->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($payments->getUrlRange(1, min($payments->lastPage(), 5)) as $page => $url)
                    @if ($page == $payments->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
                @else
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">التالي</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="status-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-lg w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-purple-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">تحديث حالة الدفعة</h3>
                <p class="text-slate-600 text-sm lg:text-base">هل أنت متأكد من تحديث حالة هذه الدفعة؟</p>
            </div>

            <form id="status-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        تأكيد التحديث
                    </button>
                    <button type="button" onclick="closeStatusModal()"
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
    // Auto-submit search
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filters-form').submit();
        }, 500);
    });

    // Status management
    function updateStatus(paymentId, newStatus) {
        document.getElementById('status-modal').classList.remove('hidden');
        document.getElementById('status-form').action = `/admin/payments/${paymentId}/status`;

        // Add hidden input for new status
        const existingInput = document.querySelector('input[name="status"]');
        if (existingInput) {
            existingInput.remove();
        }
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        document.getElementById('status-form').appendChild(statusInput);
    }

    function closeStatusModal() {
        document.getElementById('status-modal').classList.add('hidden');
    }

    // Download receipt
    function downloadReceipt(paymentId) {
        window.open(`/admin/payments/${paymentId}/receipt`, '_blank');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'status-modal') {
            closeStatusModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeStatusModal();
        }
    });
</script>
@endpush
@endsection
