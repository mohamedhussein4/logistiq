@extends('layouts.main')

@section('title', 'لوحة تحكم الشركة الطالبة للخدمة - Link2u')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-purple-600 to-purple-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">لوحة تحكم الشركة الطالبة للخدمة</h1>
                <p class="text-purple-100">مرحباً بك {{ $serviceCompany->user->name ?? auth()->user()->name ?? 'في لوحة إدارة الفواتير والمدفوعات' }}</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                    <div class="text-sm opacity-80">تاريخ آخر تحديث</div>
                    <div class="font-semibold">{{ date('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Stats -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Outstanding Amount -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">المبلغ المستحق</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_outstanding'] ?? 0) }} ر.س</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Paid This Month -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">المدفوع هذا الشهر</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['paid_this_month'] ?? 0) }} ر.س</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">إجمالي الفواتير</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_invoices'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Invoices -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الفواتير المعلقة</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['pending_invoices'] ?? 0 }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h3 class="text-xl font-semibold mb-6 text-gray-800">
                        <i class="fas fa-credit-card text-purple-600 ml-2"></i>
                        سداد سريع
                    </h3>

                    <form class="space-y-4" action="{{ route('service_company.quick_payment') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الفاتورة
                            </label>
                            <input type="text"
                                   name="invoice_number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="INV-2024-001"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                المبلغ (ر.س)
                            </label>
                            <input type="number"
                                   name="amount"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="1000"
                                   min="1"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                طريقة الدفع
                            </label>
                            <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                <option value="">اختر طريقة الدفع</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="online_payment">دفع إلكتروني</option>
                                <option value="check">شيك</option>
                                <option value="cash">نقداً</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                رقم المرجع
                            </label>
                            <input type="text"
                                   name="reference_number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="REF123456">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظات
                            </label>
                            <textarea rows="3"
                                      name="notes"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                      placeholder="أي ملاحظات إضافية..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-md hover:bg-purple-700 transition-colors font-medium">
                            <i class="fas fa-paper-plane ml-2"></i>
                            إرسال الدفع
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-history text-purple-600 ml-2"></i>
                            آخر الأنشطة
                        </h3>
                        <div class="flex space-x-2 space-x-reverse">
                            <select class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                                <option>آخر 30 يوم</option>
                                <option>آخر 3 أشهر</option>
                                <option>آخر 6 أشهر</option>
                                <option>العام الحالي</option>
                            </select>
                            <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-sm hover:bg-gray-200">
                                <i class="fas fa-download"></i> تصدير
                            </button>
                        </div>
                    </div>

                                        <!-- Recent Invoices Table -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-800">
                                <i class="fas fa-file-invoice text-red-600 ml-2"></i>
                                الفواتير الأخيرة
                            </h4>
                                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 sm:space-x-reverse">
                                <input type="text" id="invoices-search" placeholder="بحث..."
                                       class="px-3 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-32 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <select id="invoices-status-filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">كل الحالات</option>
                                    <option value="paid">مدفوع</option>
                                    <option value="partial">مدفوع جزئياً</option>
                                    <option value="unpaid">غير مدفوع</option>
                                    <option value="overdue">متأخر</option>
                                </select>
                            </div>
                        </div>

                                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <!-- Desktop Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table id="invoices-table" class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الشركة</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الاستحقاق</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($recentInvoices as $invoice)
                                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer"
                                            data-invoice-number="{{ $invoice->invoice_number }}"
                                            data-remaining-amount="{{ $invoice->remaining_amount }}"
                                            data-status="{{ $invoice->payment_status }}">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->logisticsCompany->name ?? 'غير محدد' }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ number_format($invoice->original_amount) }} ر.س</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                                       ($invoice->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($invoice->payment_status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ $invoice->payment_status_label }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->due_date->format('Y-m-d') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fas fa-file-invoice text-gray-400 text-3xl mb-3 block"></i>
                                                لا توجد فواتير حتى الآن
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards -->
                            <div id="invoices-mobile" class="md:hidden">
                                @forelse($recentInvoices as $invoice)
                                <div class="invoice-card border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors cursor-pointer"
                                     data-invoice-number="{{ $invoice->invoice_number }}"
                                     data-remaining-amount="{{ $invoice->remaining_amount }}"
                                     data-status="{{ $invoice->payment_status }}">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</h3>
                                            <p class="text-xs text-gray-500 mt-1">{{ $invoice->logisticsCompany->name ?? 'غير محدد' }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                               ($invoice->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                                               ($invoice->payment_status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ $invoice->payment_status_label }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-semibold text-gray-900">{{ number_format($invoice->original_amount) }} ر.س</span>
                                        <span class="text-xs text-gray-500">{{ $invoice->due_date->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fas fa-file-invoice text-gray-400 text-3xl mb-3 block"></i>
                                    <p>لا توجد فواتير حتى الآن</p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Pagination for Invoices -->
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        عرض <span id="invoices-start">1</span> إلى <span id="invoices-end">5</span> من <span id="invoices-total">{{ $recentInvoices->count() }}</span> فاتورة
                                    </div>
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <button id="invoices-prev" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <span id="invoices-pages" class="flex items-center space-x-1 space-x-reverse"></span>
                                        <button id="invoices-next" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Payments Table -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-800">
                                <i class="fas fa-money-check-alt text-green-600 ml-2"></i>
                                آخر المدفوعات
                            </h4>
                                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 sm:space-x-reverse">
                                <input type="text" id="payments-search" placeholder="بحث..."
                                       class="px-3 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-32 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <select id="payments-status-filter" class="px-3 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">كل الحالات</option>
                                    <option value="pending">معلق</option>
                                    <option value="confirmed">مؤكد</option>
                                    <option value="failed">فشل</option>
                                    <option value="cancelled">ملغي</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <!-- Desktop Table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table id="payments-table" class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الدفعة</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">طريقة الدفع</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($recentPayments as $payment)
                                        <tr class="hover:bg-gray-50 transition-colors" data-status="{{ $payment->status }}">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">#{{ $payment->id }}</td>
                                            <td class="px-4 py-3 text-sm text-purple-600">{{ $payment->invoice->invoice_number ?? 'غير محدد' }}</td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ number_format($payment->amount) }} ر.س</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->payment_method_label }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $payment->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ $payment->status_label }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                <i class="fas fa-credit-card text-gray-400 text-3xl mb-3 block"></i>
                                                لا توجد مدفوعات حتى الآن
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards -->
                            <div id="payments-mobile" class="md:hidden">
                                @forelse($recentPayments as $payment)
                                <div class="payment-card border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors"
                                     data-status="{{ $payment->status }}">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">دفعة #{{ $payment->id }}</h3>
                                            <p class="text-xs text-purple-600 mt-1">{{ $payment->invoice->invoice_number ?? 'غير محدد' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $payment->payment_method_label }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $payment->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                               ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $payment->status_label }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-semibold text-gray-900">{{ number_format($payment->amount) }} ر.س</span>
                                        <span class="text-xs text-gray-500">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fas fa-credit-card text-gray-400 text-3xl mb-3 block"></i>
                                    <p>لا توجد مدفوعات حتى الآن</p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Pagination for Payments -->
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        عرض <span id="payments-start">1</span> إلى <span id="payments-end">5</span> من <span id="payments-total">{{ $recentPayments->count() }}</span> دفعة
                                    </div>
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        <button id="payments-prev" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                        <span id="payments-pages" class="flex items-center space-x-1 space-x-reverse"></span>
                                        <button id="payments-next" class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">إجراءات سريعة</h3>
        <div class="grid md:grid-cols-4 gap-4">
            <a href="{{ route('service_company.profile') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center block">
                <i class="fas fa-user-circle text-purple-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">الملف الشخصي</p>
            </a>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-download text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">تحميل كشف حساب</p>
            </button>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-calculator text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">حاسبة الأقساط</p>
            </button>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-headset text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">الدعم الفني</p>
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Auto-calculate payment amount based on invoice
    document.querySelector('input[name="invoice_number"]').addEventListener('blur', function() {
        const invoiceNumber = this.value.trim();
        const amountInput = document.querySelector('input[name="amount"]');

        if (invoiceNumber) {
            // البحث في الفواتير المعروضة في الصفحة
            const invoiceCards = document.querySelectorAll('[data-invoice-number]');
            let found = false;

            invoiceCards.forEach(card => {
                const cardInvoiceNumber = card.getAttribute('data-invoice-number');
                if (cardInvoiceNumber === invoiceNumber) {
                    const remainingAmount = card.getAttribute('data-remaining-amount');
                    if (remainingAmount && remainingAmount > 0) {
                        amountInput.value = remainingAmount;
                        amountInput.focus();
                        found = true;

                        // إظهار رسالة تأكيد
                        showNotification('success', 'تم العثور على الفاتورة', `المبلغ المستحق: ${parseInt(remainingAmount).toLocaleString()} ر.س`);
                    } else {
                        showNotification('warning', 'فاتورة مدفوعة', 'هذه الفاتورة مدفوعة بالكامل');
                    }
                }
            });

            if (!found) {
                showNotification('info', 'لم يتم العثور على الفاتورة', 'تأكد من رقم الفاتورة أو قم بإدخال المبلغ يدوياً');
            }
        }
    });

        // دالة لإظهار الإشعارات
    function showNotification(type, title, message) {
        // يمكن استخدام مكتبة toast أو alert بسيط
        const colors = {
            success: '#10b981',
            warning: '#f59e0b',
            info: '#3b82f6'
        };

        // إنشاء toast بسيط
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg text-white shadow-lg z-50`;
        toast.style.backgroundColor = colors[type];
        toast.innerHTML = `
            <div class="font-bold">${title}</div>
            <div class="text-sm opacity-90">${message}</div>
        `;

        document.body.appendChild(toast);

        // إزالة التوست بعد 3 ثوان
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Custom DataTable Class
    class CustomDataTable {
        constructor(tableId, options = {}) {
            this.tableId = tableId;
            this.table = document.getElementById(tableId);
            this.tbody = this.table.querySelector('tbody');
            this.searchInput = document.getElementById(options.searchId);
            this.statusFilter = document.getElementById(options.statusFilterId);
            this.currentPage = 1;
            this.rowsPerPage = options.rowsPerPage || 5;
            this.filteredRows = [];
            this.allRows = [];

            this.init();
        }

        init() {
            this.getAllRows();
            this.setupEventListeners();
            this.updateTable();
        }

                getAllRows() {
            // Get rows from both desktop table and mobile cards
            const tableRows = this.tbody ? Array.from(this.tbody.querySelectorAll('tr')).filter(row =>
                !row.querySelector('td[colspan]') // استبعاد صفوف الرسائل الفارغة
            ) : [];

            const mobileCards = Array.from(document.querySelectorAll(`#${this.tableId.replace('-table', '-mobile')} .${this.tableId.includes('invoices') ? 'invoice-card' : 'payment-card'}`));

            this.allRows = tableRows;
            this.allMobileCards = mobileCards;
            this.filteredRows = [...this.allRows];
            this.filteredMobileCards = [...this.allMobileCards];
        }

        setupEventListeners() {
            if (this.searchInput) {
                this.searchInput.addEventListener('input', () => this.handleSearch());
            }

            if (this.statusFilter) {
                this.statusFilter.addEventListener('change', () => this.handleFilter());
            }

            // Pagination buttons
            const prevBtn = document.getElementById(this.tableId.replace('-table', '-prev'));
            const nextBtn = document.getElementById(this.tableId.replace('-table', '-next'));

            if (prevBtn) prevBtn.addEventListener('click', () => this.prevPage());
            if (nextBtn) nextBtn.addEventListener('click', () => this.nextPage());
        }

        handleSearch() {
            const searchTerm = this.searchInput.value.toLowerCase().trim();

            // Filter desktop table rows
            this.filteredRows = this.allRows.filter(row => {
                const text = row.textContent.toLowerCase();
                return text.includes(searchTerm);
            });

            // Filter mobile cards
            this.filteredMobileCards = this.allMobileCards.filter(card => {
                const text = card.textContent.toLowerCase();
                return text.includes(searchTerm);
            });

            this.currentPage = 1;
            this.updateTable();
        }

        handleFilter() {
            const statusValue = this.statusFilter.value;

            // Filter desktop table rows
            this.filteredRows = this.allRows.filter(row => {
                if (!statusValue) return true;
                const status = row.getAttribute('data-status');
                return status === statusValue;
            });

            // Filter mobile cards
            this.filteredMobileCards = this.allMobileCards.filter(card => {
                if (!statusValue) return true;
                const status = card.getAttribute('data-status');
                return status === statusValue;
            });

            this.currentPage = 1;
            this.updateTable();
        }

        updateTable() {
            this.hideAllRows();
            this.showCurrentPageRows();
            this.updatePagination();
            this.updateCounters();
        }

                hideAllRows() {
            // Hide desktop table rows
            this.allRows.forEach(row => row.style.display = 'none');

            // Hide mobile cards
            this.allMobileCards.forEach(card => card.style.display = 'none');

            // إظهار رسالة فارغة إذا لم توجد نتائج
            const emptyRow = this.tbody?.querySelector('tr td[colspan]');
            if (emptyRow) {
                emptyRow.parentElement.style.display = this.filteredRows.length === 0 ? '' : 'none';
            }

            // إظهار رسالة فارغة للموبايل
            const mobileContainer = document.getElementById(this.tableId.replace('-table', '-mobile'));
            const emptyMobileMessage = mobileContainer?.querySelector('.p-8.text-center');
            if (emptyMobileMessage) {
                emptyMobileMessage.style.display = this.filteredMobileCards.length === 0 ? '' : 'none';
            }
        }

                showCurrentPageRows() {
            const startIndex = (this.currentPage - 1) * this.rowsPerPage;
            const endIndex = startIndex + this.rowsPerPage;

            // Show desktop table rows
            const pageRows = this.filteredRows.slice(startIndex, endIndex);
            pageRows.forEach(row => row.style.display = '');

            // Show mobile cards
            const pageMobileCards = this.filteredMobileCards.slice(startIndex, endIndex);
            pageMobileCards.forEach(card => card.style.display = '');
        }

        updatePagination() {
            const totalFilteredItems = Math.max(this.filteredRows.length, this.filteredMobileCards.length);
            const totalPages = Math.ceil(totalFilteredItems / this.rowsPerPage);
            const prevBtn = document.getElementById(this.tableId.replace('-table', '-prev'));
            const nextBtn = document.getElementById(this.tableId.replace('-table', '-next'));
            const pagesContainer = document.getElementById(this.tableId.replace('-table', '-pages'));

            // تحديث أزرار التنقل
            if (prevBtn) prevBtn.disabled = this.currentPage <= 1;
            if (nextBtn) nextBtn.disabled = this.currentPage >= totalPages;

            // تحديث أرقام الصفحات
            if (pagesContainer) {
                pagesContainer.innerHTML = '';
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = `px-3 py-1 text-sm border rounded-md ${
                        i === this.currentPage
                            ? 'bg-purple-600 text-white border-purple-600'
                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                    }`;
                    pageBtn.textContent = i;
                    pageBtn.addEventListener('click', () => this.goToPage(i));
                    pagesContainer.appendChild(pageBtn);
                }
            }
        }

                updateCounters() {
            const prefix = this.tableId.replace('-table', '');
            const startEl = document.getElementById(prefix + '-start');
            const endEl = document.getElementById(prefix + '-end');
            const totalEl = document.getElementById(prefix + '-total');

            // Use the larger count between desktop and mobile (they should be the same)
            const totalFilteredItems = Math.max(this.filteredRows.length, this.filteredMobileCards.length);
            const startIndex = Math.min((this.currentPage - 1) * this.rowsPerPage + 1, totalFilteredItems);
            const endIndex = Math.min(this.currentPage * this.rowsPerPage, totalFilteredItems);

            if (startEl) startEl.textContent = totalFilteredItems > 0 ? startIndex : 0;
            if (endEl) endEl.textContent = endIndex;
            if (totalEl) totalEl.textContent = totalFilteredItems;
        }

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.updateTable();
            }
        }

        nextPage() {
            const totalFilteredItems = Math.max(this.filteredRows.length, this.filteredMobileCards.length);
            const totalPages = Math.ceil(totalFilteredItems / this.rowsPerPage);
            if (this.currentPage < totalPages) {
                this.currentPage++;
                this.updateTable();
            }
        }

        goToPage(page) {
            this.currentPage = page;
            this.updateTable();
        }
    }

    // Initialize DataTables when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Invoices Table
        if (document.getElementById('invoices-table')) {
            new CustomDataTable('invoices-table', {
                searchId: 'invoices-search',
                statusFilterId: 'invoices-status-filter',
                rowsPerPage: 5
            });
        }

        // Initialize Payments Table
        if (document.getElementById('payments-table')) {
            new CustomDataTable('payments-table', {
                searchId: 'payments-search',
                statusFilterId: 'payments-status-filter',
                rowsPerPage: 5
            });
        }
    });
</script>
@endpush
@endsection
