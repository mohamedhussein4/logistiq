@extends('layouts.admin')

@section('title', 'إدارة الفواتير')
@section('page-title', 'إدارة الفواتير')
@section('page-description', 'نظام إدارة ومتابعة فواتير الشركات الطالبة للخدمة')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_invoices'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي الفواتير</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['paid_invoices'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">مدفوعة</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ $stats['overdue_invoices'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">متأخرة</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-sm lg:text-xl font-black text-purple-600">{{ number_format($stats['total_outstanding'] ?? 0) }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">إجمالي المستحق</div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <form method="GET" id="filters-form" class="space-y-4 lg:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البحث</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="ابحث برقم الفاتورة، الشركة..."
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
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسلة</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
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
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة الفواتير</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $invoices->count() }} من أصل {{ $invoices->total() }} فاتورة</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
                <a href="{{ route('admin.invoices.create') }}" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">فاتورة جديدة</span>
                    <span class="lg:hidden">جديدة</span>
                </a>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            <!-- Mobile Bulk Actions -->
            <div id="mobile-bulk-actions" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <span class="text-blue-800 font-medium">تم تحديد <span id="mobile-selected-count">0</span> عنصر</span>
                        <button type="button" onclick="clearSelection()" class="text-blue-600 hover:text-blue-800 font-medium">
                            إلغاء التحديد
                        </button>
                    </div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <button type="button" onclick="bulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>

            @forelse($invoices as $invoice)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-start space-x-3 space-x-reverse">
                        <input type="checkbox" class="invoice-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1"
                               value="{{ $invoice->id }}" onchange="updateSelectionCount()">
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $invoice->invoice_number }}</div>
                            <div class="text-lg font-black text-slate-900">{{ number_format($invoice->original_amount) }} ريال</div>
                            <div class="text-sm text-slate-600">المدفوع: {{ number_format($invoice->paid_amount) }}</div>
                        </div>
                    </div>
                    @php
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
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $paymentStatusClasses[$invoice->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $paymentStatusNames[$invoice->payment_status] ?? $invoice->payment_status }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-sm font-semibold text-slate-700">{{ $invoice->serviceCompany->user->company_name }}</div>
                    <div class="text-xs text-slate-500">استحقاق: {{ $invoice->due_date->format('Y-m-d') }}</div>
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    @if($invoice->payment_status !== 'paid')
                    <button onclick="recordPayment({{ $invoice->id }})"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                        دفعة
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-invoice text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد فواتير</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على فواتير تطابق معايير البحث</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200">
            <!-- Bulk Actions -->
            <div id="bulk-actions" class="hidden bg-blue-50 border-b border-blue-200 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <span class="text-blue-800 font-medium">تم تحديد <span id="selected-count">0</span> عنصر</span>
                        <button type="button" onclick="clearSelection()" class="text-blue-600 hover:text-blue-800 font-medium">
                            إلغاء التحديد
                        </button>
                    </div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <button type="button" onclick="bulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            حذف المحدد
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                رقم الفاتورة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الشركة الطالبة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المبلغ الأصلي
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المدفوع
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المتبقي
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                حالة الدفع
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                تاريخ الاستحقاق
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="invoice-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       value="{{ $invoice->id }}" onchange="updateSelectionCount()">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center ml-3">
                                        <i class="fas fa-file-invoice text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $invoice->invoice_number }}</div>
                                        <div class="text-sm text-slate-500">{{ $invoice->created_at->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center ml-3">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $invoice->serviceCompany->user->company_name }}</div>
                                        <div class="text-sm text-slate-500">{{ $invoice->serviceCompany->user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ number_format($invoice->original_amount) }}</div>
                                <div class="text-sm text-slate-500">ريال سعودي</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-green-600">{{ number_format($invoice->paid_amount) }}</div>
                                <div class="text-sm text-slate-500">{{ number_format(($invoice->paid_amount / $invoice->original_amount) * 100, 1) }}%</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-red-600">{{ number_format($invoice->remaining_amount) }}</div>
                                <div class="text-sm text-slate-500">متبقي</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $paymentStatusClasses[$invoice->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $paymentStatusNames[$invoice->payment_status] ?? $invoice->payment_status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $invoice->due_date->format('Y-m-d') }}</div>
                                @if($invoice->due_date->isPast() && $invoice->payment_status !== 'paid')
                                    <div class="text-sm text-red-600 font-semibold">متأخرة</div>
                                @else
                                    <div class="text-sm text-slate-500">{{ $invoice->due_date->diffForHumans() }}</div>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    @if($invoice->payment_status !== 'paid')
                                    <button onclick="recordPayment({{ $invoice->id }})"
                                            class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-money-bill text-xs"></i>
                                    </button>
                                    @endif

                                    <a href="{{ route('admin.invoices.edit', $invoice) }}"
                                       class="w-8 h-8 bg-orange-500 hover:bg-orange-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-file-invoice text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد فواتير</h3>
                                    <p class="text-slate-500">لم يتم العثور على فواتير تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }} من أصل {{ $invoices->total() }} فاتورة
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($invoices->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $invoices->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($invoices->getUrlRange(1, min($invoices->lastPage(), 5)) as $page => $url)
                    @if ($page == $invoices->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($invoices->hasMorePages())
                    <a href="{{ $invoices->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
                @else
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">التالي</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Payment Recording Modal -->
<div id="payment-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-2xl w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill text-green-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">تسجيل دفعة جديدة</h3>
                <p class="text-slate-600 text-sm lg:text-base">أدخل تفاصيل الدفعة المستلمة</p>
            </div>

            <form id="payment-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">المبلغ المدفوع <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" step="0.01" required
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
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

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        تسجيل الدفعة
                    </button>
                    <button type="button" onclick="closePaymentModal()"
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

    // Payment recording
    function recordPayment(invoiceId) {
        document.getElementById('payment-modal').classList.remove('hidden');
        document.getElementById('payment-form').action = `/admin/invoices/${invoiceId}/payment`;
    }

    function closePaymentModal() {
        document.getElementById('payment-modal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'payment-modal') {
            closePaymentModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePaymentModal();
        }
    });

    // Bulk delete functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.invoice-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions');
        const mobileBulkActionsBar = document.getElementById('mobile-bulk-actions');

        // Select all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectionCount();
            });
        }
    });

    function updateSelectionCount() {
        const checkboxes = document.querySelectorAll('.invoice-checkbox');
        const checkedBoxes = document.querySelectorAll('.invoice-checkbox:checked');
        const count = checkedBoxes.length;

        // Update count displays
        const selectedCountElements = document.querySelectorAll('#selected-count, #mobile-selected-count');
        selectedCountElements.forEach(element => {
            if (element) element.textContent = count;
        });

        // Show/hide bulk actions bars
        const bulkActionsBar = document.getElementById('bulk-actions');
        const mobileBulkActionsBar = document.getElementById('mobile-bulk-actions');

        if (count > 0) {
            if (bulkActionsBar) bulkActionsBar.classList.remove('hidden');
            if (mobileBulkActionsBar) mobileBulkActionsBar.classList.remove('hidden');
        } else {
            if (bulkActionsBar) bulkActionsBar.classList.add('hidden');
            if (mobileBulkActionsBar) mobileBulkActionsBar.classList.add('hidden');
        }

        // Update select all checkbox state
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            if (count === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (count === checkboxes.length) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
                selectAllCheckbox.checked = false;
            }
        }
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.invoice-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectionCount();
    }

    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.invoice-checkbox:checked');

        if (checkedBoxes.length === 0) {
            alert('يرجى تحديد الفواتير المراد حذفها');
            return;
        }

        if (confirm(`هل أنت متأكد من حذف ${checkedBoxes.length} فاتورة؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.invoices.bulk-delete") }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add method override
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Add selected IDs
            const idsField = document.createElement('input');
            idsField.type = 'hidden';
            idsField.name = 'ids';
            idsField.value = JSON.stringify(ids);
            form.appendChild(idsField);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
