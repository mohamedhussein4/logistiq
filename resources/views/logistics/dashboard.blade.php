@extends('layouts.main')

@section('title', 'لوحة تحكم الشركة اللوجستية - Link2u')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">لوحة تحكم الشركة اللوجستية</h1>
                <p class="text-blue-100">مرحباً بك {{ auth()->user()->name ?? 'في لوحة إدارة التمويل والمستحقات' }}</p>
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
@php
    $isOverLimit = ($stats['used_credit'] ?? 0) > ($stats['credit_limit'] ?? 100000);
    $excessAmount = $isOverLimit ? ($stats['used_credit'] ?? 0) - ($stats['credit_limit'] ?? 100000) : 0;
@endphp
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Current Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الرصيد المتاح</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($stats['available_balance'] ?? 0) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">(من الفواتير المدفوعة)</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-wallet text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Credit Limit -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الحد الائتماني</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['credit_limit'] ?? 0) }} ر.س</p>
                        @if($isOverLimit)
                            <p class="text-xs text-red-600 mt-1">(يحتاج زيادة)</p>
                        @endif
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Requests -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_requests'] ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الطلبات المعلقة</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['pending_requests'] ?? 0 }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Remaining Credit -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الائتمان المتبقي</p>
                        @php
                            $remainingCredit = max(0, $stats['credit_limit'] - $stats['used_credit']);
                        @endphp
                        <p class="text-2xl font-bold {{ $isOverLimit ? 'text-red-600' : 'text-indigo-600' }}">
                            {{ $isOverLimit ? '-' . number_format($excessAmount) : number_format($remainingCredit) }} ر.س
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $isOverLimit ? '(متجاوز الحد الائتماني)' : '(لطلب تمويل جديد)' }}
                        </p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-credit-card text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Financial Details -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">الفواتير المدفوعة</p>
                    <p class="text-lg font-bold text-green-600">
                        @php
                            $totalPaidInvoices = \App\Models\Invoice::where('logistics_company_id', auth()->id())
                                ->where('payment_status', 'paid')
                                ->sum('paid_amount');
                        @endphp
                        {{ number_format($totalPaidInvoices) }} ر.س
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">الفواتير المستحقة</p>
                    <p class="text-lg font-bold text-red-600">
                        @php
                            $totalOutstandingInvoices = \App\Models\Invoice::where('logistics_company_id', auth()->id())
                                ->whereIn('payment_status', ['unpaid', 'partial'])
                                ->sum('remaining_amount');
                        @endphp
                        {{ number_format($totalOutstandingInvoices) }} ر.س
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">الائتمان المستخدم</p>
                    <p class="text-lg font-bold text-orange-600">
                        @php
                            $usedCredit = \App\Models\FundingRequest::where('logistics_company_id', auth()->id())
                                ->whereIn('status', ['approved', 'disbursed'])
                                ->sum('amount');
                        @endphp
                        {{ number_format($usedCredit) }} ر.س
                        @if($isOverLimit)
                            <br><span class="text-xs text-red-600">(متجاوز بـ {{ number_format($excessAmount) }} ر.س)</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Credit Management Section -->
@php
    $isOverLimit = $stats['used_credit'] > $stats['credit_limit'];
    $excessAmount = $isOverLimit ? $stats['used_credit'] - $stats['credit_limit'] : 0;
@endphp

@if($isOverLimit)
<section class="py-6 bg-red-50">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        تنبيه: تجاوز الحد الائتماني
                    </h3>
                                               <p class="text-red-700 mb-4">
                               تم تجاوز الحد الائتماني بمبلغ {{ number_format($excessAmount) }} ر.س.
                               يمكنك سداد أي مبلغ من الفواتير المستحقة أدناه.
                           </p>

                                        <!-- Outstanding Invoices Selection -->
                    @php
                        $outstandingInvoices = \App\Models\Invoice::where('logistics_company_id', auth()->id())
                            ->whereIn('payment_status', ['unpaid', 'partial'])
                            ->orderBy('due_date', 'asc')
                            ->get();
                    @endphp

                    <!-- Payment Form -->
                    <form action="{{ route('logistics.credit.pay') }}" method="POST" class="space-y-4">
                        @csrf

                        @if($outstandingInvoices->count() > 0)
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-gray-800">اختر الفواتير المراد سدادها:</h4>
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <button type="button" id="select-all-invoices" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                        تحديد الكل
                                    </button>
                                    <button type="button" id="deselect-all-invoices" class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        إلغاء التحديد
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                @foreach($outstandingInvoices as $invoice)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded text-sm hover:bg-gray-100">
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <input type="checkbox"
                                               name="selected_invoices[]"
                                               value="{{ $invoice->id }}"
                                               data-amount="{{ $invoice->remaining_amount }}"
                                               class="invoice-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500"
                                               data-debug="Invoice ID: {{ $invoice->id }}, Amount: {{ $invoice->remaining_amount }}">
                                        <div>
                                            <span class="font-medium">{{ $invoice->invoice_number }}</span>
                                            <span class="text-xs text-gray-500 block">استحقاق: {{ $invoice->due_date->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                    <span class="text-red-600 font-semibold">{{ number_format($invoice->remaining_amount) }} ر.س</span>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-3 p-2 bg-blue-50 rounded text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-800">إجمالي المحدد:</span>
                                    <span id="selected-total" class="font-bold text-blue-900">0 ر.س</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ السداد</label>
                                <input type="number"
                                       name="payment_amount"
                                       id="payment-amount"
                                       value="{{ $excessAmount }}"
                                       min="1"
                                       max="{{ $stats['used_credit'] }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                       required>
                                <div class="flex items-center space-x-2 space-x-reverse mt-1">
                                    <button type="button" id="use-excess-amount" class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                        سداد المتجاوز
                                    </button>
                                    <button type="button" id="use-selected-amount" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                        سداد المحدد
                                    </button>
                                    <button type="button" id="clear-amount" class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        مسح المبلغ
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">طريقة السداد</label>
                                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">اختر طريقة السداد</option>
                                    <option value="bank_transfer">تحويل بنكي</option>
                                    <option value="check">شيك</option>
                                    <option value="cash">نقداً</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                            <div class="flex items-center">
                                <button type="submit" id="submit-payment" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    سداد الفواتير المحددة
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkboxes = document.querySelectorAll('.invoice-checkbox');
                            const selectAllBtn = document.getElementById('select-all-invoices');
                            const deselectAllBtn = document.getElementById('deselect-all-invoices');
                            const selectedTotal = document.getElementById('selected-total');
                            const paymentAmount = document.getElementById('payment-amount');
                            const submitBtn = document.getElementById('submit-payment');

                            function updateSelectedTotal() {
                                let total = 0;
                                checkboxes.forEach(checkbox => {
                                    if (checkbox.checked) {
                                        total += parseFloat(checkbox.dataset.amount);
                                    }
                                });
                                selectedTotal.textContent = total.toLocaleString() + ' ر.س';

                                // تحديث مبلغ السداد تلقائياً
                                if (total > 0) {
                                    paymentAmount.value = total;
                                }
                            }

                            // تحديث عند تغيير التحديد
                            checkboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', updateSelectedTotal);
                            });

                            // تحديد الكل
                            selectAllBtn.addEventListener('click', function() {
                                checkboxes.forEach(checkbox => {
                                    checkbox.checked = true;
                                });
                                updateSelectedTotal();
                            });

                            // إلغاء التحديد
                            deselectAllBtn.addEventListener('click', function() {
                                checkboxes.forEach(checkbox => {
                                    checkbox.checked = false;
                                });
                                updateSelectedTotal();
                            });

                            // التحقق من التحديد قبل الإرسال
                            document.querySelector('form').addEventListener('submit', function(e) {
                                const checkedBoxes = document.querySelectorAll('.invoice-checkbox:checked');
                                if (checkedBoxes.length === 0) {
                                    e.preventDefault();
                                    alert('يرجى تحديد فاتورة واحدة على الأقل');
                                    return false;
                                }
                            });

                            // سداد المتجاوز فقط
                            document.getElementById('use-excess-amount').addEventListener('click', function() {
                                paymentAmount.value = {{ $excessAmount }};
                                // تحديد الفواتير التي تغطي المتجاوز
                                let totalNeeded = {{ $excessAmount }};
                                checkboxes.forEach(checkbox => {
                                    checkbox.checked = false;
                                });

                                for (let checkbox of checkboxes) {
                                    if (totalNeeded <= 0) break;
                                    const amount = parseFloat(checkbox.dataset.amount);
                                    if (amount <= totalNeeded) {
                                        checkbox.checked = true;
                                        totalNeeded -= amount;
                                    }
                                }
                                updateSelectedTotal();
                            });

                            // سداد المحدد
                            document.getElementById('use-selected-amount').addEventListener('click', function() {
                                const checkedBoxes = document.querySelectorAll('.invoice-checkbox:checked');
                                if (checkedBoxes.length === 0) {
                                    alert('يرجى تحديد فواتير أولاً');
                                    return;
                                }
                                let total = 0;
                                checkedBoxes.forEach(checkbox => {
                                    total += parseFloat(checkbox.dataset.amount);
                                });
                                paymentAmount.value = total;
                            });

                            // مسح المبلغ
                            document.getElementById('clear-amount').addEventListener('click', function() {
                                paymentAmount.value = '';
                            });

                            // تهيئة
                            updateSelectedTotal();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Main Content -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- New Funding Request Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <h3 class="text-xl font-semibold mb-6 text-gray-800">
                        <i class="fas fa-plus-circle text-blue-600 ml-2"></i>
                        طلب تمويل جديد
                    </h3>

                    <form class="space-y-4" action="{{ route('logistics.funding.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                مبلغ التمويل المطلوب (ر.س)
                            </label>
                            <input type="number"
                                   name="amount"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="مثال: 50000"
                                   min="1000"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                سبب الطلب
                            </label>
                            <select name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">اختر سبب الطلب</option>
                                <option value="operational">تمويل العمليات التشغيلية</option>
                                <option value="expansion">التوسع في الأعمال</option>
                                <option value="equipment">شراء معدات جديدة</option>
                                <option value="emergency">طارئ</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                تفاصيل إضافية
                            </label>
                            <textarea rows="3"
                                      name="description"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="اكتب تفاصيل إضافية حول الطلب..."></textarea>
                        </div>

                        <!-- قسم العملاء المدينين -->
                        <div class="border-t pt-4">
                            <h4 class="text-md font-semibold text-gray-800 mb-4">
                                <i class="fas fa-building text-purple-600 ml-2"></i>
                                العملاء المدينون (مصدر التمويل)
                            </h4>

                            <div id="clients-container">
                                <div class="client-item border border-gray-200 rounded-lg p-4 mb-4" data-client="1">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="font-medium text-gray-700">العميل الأول</h5>
                                        <button type="button" class="text-red-500 hover:text-red-700 remove-client" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">اسم الشركة</label>
                                            <input type="text" name="clients[1][company_name]"
                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                   placeholder="مثال: شركة النقل السريع" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">اسم المسؤول</label>
                                            <input type="text" name="clients[1][contact_person]"
                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                   placeholder="مثال: أحمد محمد" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">البريد الإلكتروني</label>
                                            <input type="email" name="clients[1][email]"
                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                   placeholder="ahmed@company.com" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">رقم الهاتف</label>
                                            <input type="tel" name="clients[1][phone]"
                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                   placeholder="0501234567" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">المبلغ المستحق (ر.س)</label>
                                            <input type="number" name="clients[1][amount]"
                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 client-amount"
                                                   placeholder="30000" min="1000" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">تاريخ الاستحقاق</label>
                                            <input type="date" name="clients[1][due_date]"
                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">إرفاق الفاتورة الأصلية</label>
                                        <input type="file" name="clients[1][invoice_document]"
                                               class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                               accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="add-client" class="px-3 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                                    <i class="fas fa-plus ml-1"></i>
                                    إضافة عميل آخر
                                </button>
                                <div class="text-sm text-gray-600">
                                    إجمالي المبلغ: <span id="total-amount" class="font-semibold text-purple-600">0 ر.س</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                إرفاق مستندات
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:border-blue-400 transition-colors cursor-pointer" onclick="document.getElementById('documents').click()">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">اسحب الملفات هنا أو اضغط للاختيار</p>
                                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (الحد الأقصى 5 ميجا)</p>
                                <input type="file" id="documents" name="documents[]" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-paper-plane ml-2"></i>
                            إرسال الطلب
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-history text-blue-600 ml-2"></i>
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

                    <!-- Recent Funding Requests -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-file-invoice-dollar text-green-600 ml-2"></i>
                            آخر طلبات التمويل
                        </h4>
                        <div class="space-y-3">
                            @forelse($recentFundingRequests ?? [] as $request)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-gray-800 font-medium">طلب تمويل #{{ $request->id }}</p>
                                        <p class="text-blue-600 text-sm font-semibold">{{ number_format($request->amount ?? 0) }} ر.س</p>
                                        <p class="text-gray-500 text-xs">{{ $request->created_at ? $request->created_at->diffForHumans() : 'غير محدد' }}</p>
                                    </div>
                                    <div class="text-left">
                                        @if(($request->status ?? 'pending') == 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                معلق
                                            </span>
                                        @elseif(($request->status ?? 'pending') == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                موافق عليه
                                            </span>
                                        @elseif(($request->status ?? 'pending') == 'disbursed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                تم الصرف
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                مرفوض
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <p>لا توجد طلبات تمويل حتى الآن</p>
                                <p class="text-sm">أرسل أول طلب تمويل باستخدام النموذج</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Invoices -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-file-invoice text-purple-600 ml-2"></i>
                            آخر الفواتير
                        </h4>
                        <div class="space-y-3">
                            @forelse($recentInvoices ?? [] as $invoice)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-gray-800 font-medium">{{ $invoice->invoice_number ?? '#INV-' . $invoice->id ?? 'غير محدد' }}</p>
                                        <p class="text-blue-600 text-sm">{{ $invoice->serviceCompany->name ?? 'عميل غير محدد' }}</p>
                                        <p class="text-gray-500 text-xs">{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : 'غير محدد' }}</p>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-gray-800 font-bold">{{ number_format($invoice->original_amount ?? 0) }} ر.س</p>
                                        @if(($invoice->payment_status ?? 'unpaid') == 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                مدفوع
                                            </span>
                                        @elseif(($invoice->payment_status ?? 'unpaid') == 'partial')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                مدفوع جزئياً
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                غير مدفوع
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-file-invoice text-4xl mb-3"></i>
                                <p>لا توجد فواتير حتى الآن</p>
                                <p class="text-sm">ستظهر الفواتير هنا عند إنشائها</p>
                            </div>
                            @endforelse
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
            <a href="{{ route('logistics.profile') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center block">
                <i class="fas fa-user-circle text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">الملف الشخصي</p>
            </a>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-download text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">تحميل كشف حساب</p>
            </button>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-calculator text-purple-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">حاسبة التمويل</p>
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
    let clientCounter = 1;

    // File upload handling
    document.getElementById('documents').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            const fileList = Array.from(files).map(file => file.name).join(', ');
            alert('تم اختيار الملفات: ' + fileList);
        }
    });

    // Drag and drop file upload
    const dropZone = document.querySelector('.border-dashed');

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-blue-400');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('documents').files = files;
            const fileList = Array.from(files).map(file => file.name).join(', ');
            alert('تم إسقاط الملفات: ' + fileList);
        }
    });

    // Add new client functionality
    document.getElementById('add-client').addEventListener('click', function() {
        clientCounter++;
        const container = document.getElementById('clients-container');
        const newClient = document.createElement('div');
        newClient.className = 'client-item border border-gray-200 rounded-lg p-4 mb-4';
        newClient.setAttribute('data-client', clientCounter);

        newClient.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-medium text-gray-700">العميل ${getArabicNumber(clientCounter)}</h5>
                <button type="button" class="text-red-500 hover:text-red-700 remove-client">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">اسم الشركة</label>
                    <input type="text" name="clients[${clientCounter}][company_name]"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                           placeholder="مثال: شركة النقل السريع" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">اسم المسؤول</label>
                    <input type="text" name="clients[${clientCounter}][contact_person]"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                           placeholder="مثال: أحمد محمد" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">البريد الإلكتروني</label>
                    <input type="email" name="clients[${clientCounter}][email]"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                           placeholder="ahmed@company.com" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">رقم الهاتف</label>
                    <input type="tel" name="clients[${clientCounter}][phone]"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                           placeholder="0501234567" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">المبلغ المستحق (ر.س)</label>
                    <input type="number" name="clients[${clientCounter}][amount]"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 client-amount"
                           placeholder="20000" min="1000" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">تاريخ الاستحقاق</label>
                    <input type="date" name="clients[${clientCounter}][due_date]"
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">إرفاق الفاتورة الأصلية</label>
                <input type="file" name="clients[${clientCounter}][invoice_document]"
                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                       accept=".pdf,.jpg,.jpeg,.png">
            </div>
        `;

        container.appendChild(newClient);
        updateRemoveButtons();
        calculateTotal();
    });

    // Remove client functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-client') || e.target.closest('.remove-client')) {
            const clientItem = e.target.closest('.client-item');
            clientItem.remove();
            updateRemoveButtons();
            calculateTotal();
        }
    });

    // Calculate total amount
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('client-amount')) {
            calculateTotal();
            updateMainAmount();
        }
    });

    function calculateTotal() {
        const amounts = document.querySelectorAll('.client-amount');
        let total = 0;
        amounts.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        document.getElementById('total-amount').textContent = total.toLocaleString() + ' ر.س';
        return total;
    }

    function updateMainAmount() {
        const total = calculateTotal();
        const mainAmountInput = document.querySelector('input[name="amount"]');
        if (mainAmountInput) {
            mainAmountInput.value = total;
        }
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.client-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-client');
            if (items.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    function getArabicNumber(num) {
        const arabicNumbers = ['', 'الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس', 'السابع', 'الثامن', 'التاسع', 'العاشر'];
        return arabicNumbers[num] || `رقم ${num}`;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateRemoveButtons();
        calculateTotal();
    });
</script>
@endpush
@endsection
