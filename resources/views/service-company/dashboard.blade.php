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

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                                <div class="mr-3">
                                    <h3 class="text-sm font-medium text-red-800">يوجد أخطاء:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-check-circle text-green-400 mt-0.5"></i>
                                <div class="mr-3">
                                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-red-400 mt-0.5"></i>
                                <div class="mr-3">
                                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form class="space-y-4" action="{{ route('service_company.quick_payment') }}" method="POST" enctype="multipart/form-data">
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

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">طريقة الدفع</label>
                            <div class="space-y-2">
                                <label class="flex items-center p-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="text-purple-600 focus:ring-purple-500" onchange="showPaymentAccounts('bank')" checked>
                                    <i class="fas fa-university text-purple-600 mx-2"></i>
                                    <span class="font-medium">تحويل بنكي</span>
                                </label>
                            </div>
                        </div>

                        <!-- Bank Accounts -->
                        <div id="bank-accounts" class="">
                            <label class="block text-sm font-medium text-gray-700 mb-4">
                                <i class="fas fa-university text-blue-600 ml-2"></i>
                                اختر الحساب البنكي للدفع
                            </label>

                            <div class="space-y-3">
                                @foreach($bankAccounts ?? [] as $bank)
                                <label class="block p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-blue-400 hover:shadow-md transition-all duration-200 cursor-pointer bank-option" data-account="{{ $bank->id }}">
                                    <div class="flex items-start">
                                        <input type="radio" name="payment_account_id" value="{{ $bank->id }}" class="text-blue-600 focus:ring-blue-500 w-5 h-5 mt-1 ml-4">
                                        <div class="flex-1">
                                            <!-- معلومات أساسية - ظاهرة دائماً -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <i class="fas fa-university text-blue-600 text-xl ml-3"></i>
                                                    <div>
                                                        <h3 class="font-bold text-gray-800 text-lg">{{ $bank->bank_name }}</h3>
                                                        <p class="text-sm text-gray-600">{{ $bank->account_name }}</p>
                                                    </div>
                                                </div>
                                                <button type="button" class="toggle-details text-blue-600 hover:text-blue-800 px-3 py-1 rounded-md hover:bg-blue-50 transition-colors" data-account="{{ $bank->id }}">
                                                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                                                    <span class="text-sm mr-1">التفاصيل</span>
                                                </button>
                                            </div>

                                            <!-- رقم الحساب - ظاهر دائماً -->
                                            <div class="bg-gray-50 p-3 rounded-lg">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-credit-card text-gray-500 ml-2"></i>
                                                        <span class="text-sm font-medium text-gray-700">رقم الحساب:</span>
                                                        <span class="font-mono text-sm font-bold text-gray-900 mr-2">{{ $bank->account_number }}</span>
                                                    </div>
                                                    <button type="button" class="copy-btn text-xs text-blue-600 hover:text-blue-800 px-2 py-1 rounded hover:bg-blue-100" data-copy="{{ $bank->account_number }}">
                                                        <i class="fas fa-copy ml-1"></i>
                                                        نسخ
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- التفاصيل الإضافية - مخفية في البداية -->
                                            <div class="bank-details hidden mt-4 pt-4 border-t border-gray-200" id="details-{{ $bank->id }}">
                                                <div class="grid md:grid-cols-1 gap-4">
                                                    @if($bank->iban)
                                                    <div class="bg-blue-50 p-3 rounded-lg">
                                                        <label class="text-xs font-semibold text-blue-600 uppercase tracking-wide block mb-1">رقم الآيبان</label>
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-mono text-sm font-medium text-blue-900">{{ $bank->iban }}</span>
                                                            <button type="button" class="copy-btn text-xs text-blue-600 hover:text-blue-800 px-2 py-1 rounded hover:bg-blue-200" data-copy="{{ $bank->iban }}">
                                                                <i class="fas fa-copy ml-1"></i>
                                                                نسخ
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if($bank->swift_code)
                                                    <div class="bg-green-50 p-3 rounded-lg">
                                                        <label class="text-xs font-semibold text-green-600 uppercase tracking-wide block mb-1">رمز السويفت</label>
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-mono text-sm font-medium text-green-900">{{ $bank->swift_code }}</span>
                                                            <button type="button" class="copy-btn text-xs text-green-600 hover:text-green-800 px-2 py-1 rounded hover:bg-green-200" data-copy="{{ $bank->swift_code }}">
                                                                <i class="fas fa-copy ml-1"></i>
                                                                نسخ
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if($bank->branch_name)
                                                    <div class="bg-yellow-50 p-3 rounded-lg">
                                                        <label class="text-xs font-semibold text-yellow-600 uppercase tracking-wide block mb-1">اسم الفرع</label>
                                                        <span class="text-sm font-medium text-yellow-900">{{ $bank->branch_name }}</span>
                                                    </div>
                                                    @endif

                                                    @if($bank->notes)
                                                    <div class="bg-purple-50 p-3 rounded-lg {{ !$bank->branch_name ? 'md:col-span-2' : '' }}">
                                                        <label class="text-xs font-semibold text-purple-600 uppercase tracking-wide block mb-1">ملاحظات</label>
                                                        <span class="text-sm text-purple-900">{{ $bank->notes }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach

                                @if(count($bankAccounts ?? []) === 0)
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-info-circle text-4xl mb-4 text-gray-400"></i>
                                    <p class="text-lg font-medium">لا توجد حسابات بنكية مُسجلة</p>
                                    <p class="text-sm">يرجى إضافة حساب بنكي أولاً لتتمكن من الدفع</p>
                                </div>
                                @endif
                            </div>
                        </div>



                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظات إضافية
                            </label>
                            <textarea rows="3"
                                      name="payment_notes"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                                      placeholder="اكتب ملاحظات إضافية حول عملية الدفع..."></textarea>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-file-upload text-blue-600 ml-2"></i>
                                إثبات التحويل (اختياري)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-400 transition-colors">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <div class="mb-4">
                                        <label for="payment_proof" class="cursor-pointer">
                                            <span class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors inline-block">
                                                <i class="fas fa-folder-open ml-2"></i>
                                                اختر ملف أو صورة
                                            </span>
                                            <input type="file"
                                                   id="payment_proof"
                                                   name="payment_proof"
                                                   accept="image/*,.pdf,.doc,.docx"
                                                   class="hidden"
                                                   onchange="handleFileUpload(this)">
                                        </label>
                                    </div>
                                    <div id="file-info" class="hidden">
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-check text-green-600 ml-2"></i>
                                                    <span id="file-name" class="text-sm font-medium text-green-800"></span>
                                                </div>
                                                <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="text-xs text-green-600 mt-1">
                                                <span id="file-size"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        صور مقبولة: JPG, PNG, GIF<br>
                                        مستندات مقبولة: PDF, DOC, DOCX<br>
                                        الحد الأقصى: 5 ميجابايت
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-md hover:bg-purple-700 transition-colors font-medium">
                            <i class="fas fa-paper-plane ml-2"></i>
                            إرسال طلب الدفع
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
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($recentInvoices as $invoice)
                                        <tr class="hover:bg-gray-50 transition-colors"
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
                                            <td class="px-4 py-3 text-sm">
                                                <button onclick="showInvoiceDetails({{ $invoice->id }})"
                                                        class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-800 rounded-md text-xs font-medium transition-colors">
                                                    <i class="fas fa-eye ml-1"></i>
                                                    تفاصيل الفاتورة
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
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
                                <div class="invoice-card border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors"
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
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-lg font-semibold text-gray-900">{{ number_format($invoice->original_amount) }} ر.س</span>
                                        <span class="text-xs text-gray-500">{{ $invoice->due_date->format('Y-m-d') }}</span>
                                    </div>
                                    <div class="flex justify-end">
                                        <button onclick="showInvoiceDetails({{ $invoice->id }})"
                                                class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 hover:text-blue-800 rounded-md text-xs font-medium transition-colors">
                                            <i class="fas fa-eye ml-1"></i>
                                            تفاصيل الفاتورة
                                        </button>
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

    // دالة إظهار حسابات الدفع للشركات الطالبة
    function showPaymentAccounts(type) {
        const bankDiv = document.getElementById('bank-accounts');

        if (bankDiv) {
            // إظهار قسم الحسابات البنكية
            if (type === 'bank') {
                bankDiv.classList.remove('hidden');
            }
        }
    }    // إضافة validation للنموذج
    document.addEventListener('DOMContentLoaded', function() {
        const quickPaymentForm = document.querySelector('form[action*="quick_payment"]');
        if (quickPaymentForm) {
            quickPaymentForm.addEventListener('submit', function(e) {
                // التحقق من اختيار طريقة الدفع
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    e.preventDefault();
                    alert('يرجى اختيار طريقة الدفع');
                    return false;
                }

                // التحقق من اختيار الحساب
                const paymentAccount = document.querySelector('input[name="payment_account_id"]:checked');
                if (!paymentAccount) {
                    e.preventDefault();
                    alert('يرجى اختيار الحساب للدفع');
                    return false;
                }
            });
        }
    });

    // دالة معالجة رفع الملف
    function handleFileUpload(input) {
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB بالبايت

            // التحقق من حجم الملف
            if (file.size > maxSize) {
                alert('حجم الملف كبير جداً! يجب أن يكون أقل من 5 ميجابايت');
                input.value = '';
                return;
            }

            // التحقق من نوع الملف
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                alert('نوع الملف غير مدعوم! يرجى اختيار صورة أو مستند PDF/Word');
                input.value = '';
                return;
            }

            // عرض معلومات الملف
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('hidden');
        }
    }

    // دالة حذف الملف
    function removeFile() {
        const input = document.getElementById('payment_proof');
        const fileInfo = document.getElementById('file-info');

        input.value = '';
        fileInfo.classList.add('hidden');
    }

    // دالة تنسيق حجم الملف
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // دالة عرض تفاصيل البنك
    document.addEventListener('DOMContentLoaded', function() {
        // التعامل مع أزرار التفاصيل
        const toggleButtons = document.querySelectorAll('.toggle-details');
        const bankOptions = document.querySelectorAll('.bank-option');
        const copyButtons = document.querySelectorAll('.copy-btn');

        // عند النقر على زر التفاصيل
        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const accountId = this.getAttribute('data-account');
                const detailsElement = document.getElementById('details-' + accountId);
                const icon = this.querySelector('i');

                if (detailsElement.classList.contains('hidden')) {
                    // إظهار التفاصيل
                    detailsElement.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                    this.querySelector('span').textContent = 'إخفاء';
                } else {
                    // إخفاء التفاصيل
                    detailsElement.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                    this.querySelector('span').textContent = 'التفاصيل';
                }
            });
        });

        // عند النقر على خيار البنك (للتحديد)
        bankOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                // التأكد من أن النقرة ليست على زر التفاصيل أو النسخ
                if (e.target.closest('.toggle-details') || e.target.closest('.copy-btn')) {
                    return;
                }

                // إزالة التحديد من جميع الخيارات
                bankOptions.forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-50');
                    opt.classList.add('border-gray-200');
                });

                // تحديد الخيار المختار
                this.classList.remove('border-gray-200');
                this.classList.add('border-blue-500', 'bg-blue-50');

                // تحديد الـ radio button
                const radioButton = this.querySelector('input[type="radio"]');
                if (radioButton) {
                    radioButton.checked = true;
                }
            });
        });

        // دالة نسخ النص
        copyButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const textToCopy = this.getAttribute('data-copy');

                navigator.clipboard.writeText(textToCopy).then(() => {
                    // تغيير النص مؤقتاً
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check ml-1"></i>تم';
                    this.classList.add('bg-green-100', 'text-green-800');

                    // إعادة النص الأصلي بعد ثانيتين
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.remove('bg-green-100', 'text-green-800');
                    }, 1500);
                }).catch(() => {
                    alert('حدث خطأ أثناء نسخ النص');
                });
            });
        });
    });

    // دالة عرض تفاصيل الفاتورة
    function showInvoiceDetails(invoiceId) {
        // إظهار مربع حوار تحميل
        const loadingModal = document.createElement('div');
        loadingModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center';
        loadingModal.innerHTML = `
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 ml-3"></div>
                    <span>جاري تحميل تفاصيل الفاتورة...</span>
                </div>
            </div>
        `;
        document.body.appendChild(loadingModal);

        // استدعاء API لجلب تفاصيل الفاتورة
        fetch(`/service-company/invoice/${invoiceId}/details`)
            .then(response => response.json())
            .then(data => {
                // إزالة مربع حوار التحميل
                document.body.removeChild(loadingModal);

                // إظهار تفاصيل الفاتورة
                showInvoiceModal(data);
            })
            .catch(error => {
                // إزالة مربع حوار التحميل
                document.body.removeChild(loadingModal);

                console.error('Error:', error);
                alert('حدث خطأ أثناء تحميل تفاصيل الفاتورة');
            });
    }

    // دالة إظهار مربع حوار تفاصيل الفاتورة
    function showInvoiceModal(invoice) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
        modal.innerHTML = `
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="bg-blue-600 text-white p-6 rounded-t-lg">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold">تفاصيل الفاتورة ${invoice.invoice_number}</h3>
                            <button onclick="closeInvoiceModal()" class="text-white hover:text-gray-200 text-2xl font-bold">&times;</button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-6">
                        <!-- معلومات أساسية -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-3">معلومات الفاتورة</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">رقم الفاتورة:</span>
                                        <span class="font-medium">${invoice.invoice_number}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">تاريخ الإنشاء:</span>
                                        <span class="font-medium">${invoice.created_at}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">تاريخ الاستحقاق:</span>
                                        <span class="font-medium">${invoice.due_date}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">الحالة:</span>
                                        <span class="font-medium px-2 py-1 rounded text-xs ${getStatusClass(invoice.payment_status)}">${invoice.payment_status_label}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-3">معلومات المبلغ</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">المبلغ الأصلي:</span>
                                        <span class="font-medium">${formatNumber(invoice.original_amount)} ر.س</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">المبلغ المدفوع:</span>
                                        <span class="font-medium text-green-600">${formatNumber(invoice.paid_amount)} ر.س</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-2">
                                        <span class="text-gray-600 font-semibold">المبلغ المتبقي:</span>
                                        <span class="font-bold text-red-600">${formatNumber(invoice.remaining_amount)} ر.س</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تفاصيل الشركة -->
                        ${invoice.logistics_company ? `
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                <i class="fas fa-building ml-2"></i>
                                معلومات الشركة
                            </h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">اسم الشركة:</span>
                                        <span class="font-medium">${invoice.logistics_company.name}</span>
                                    </div>
                                    ${invoice.logistics_company.contact_person ? `
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">الشخص المسؤول:</span>
                                        <span class="font-medium">${invoice.logistics_company.contact_person}</span>
                                    </div>
                                    ` : ''}
                                </div>
                                <div class="space-y-2">
                                    ${invoice.logistics_company.phone ? `
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">الهاتف:</span>
                                        <span class="font-medium">${invoice.logistics_company.phone}</span>
                                    </div>
                                    ` : ''}
                                    ${invoice.logistics_company.email ? `
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">البريد الإلكتروني:</span>
                                        <span class="font-medium">${invoice.logistics_company.email}</span>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        ` : ''}

                        <!-- الوصف أو الملاحظات -->
                        ${invoice.description ? `
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-yellow-900 mb-2 flex items-center">
                                <i class="fas fa-sticky-note ml-2"></i>
                                الوصف
                            </h4>
                            <p class="text-sm text-yellow-800">${invoice.description}</p>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end">
                        <button onclick="closeInvoiceModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.setAttribute('id', 'invoice-modal');
    }

    // دالة إغلاق مربع حوار الفاتورة
    function closeInvoiceModal() {
        const modal = document.getElementById('invoice-modal');
        if (modal) {
            document.body.removeChild(modal);
        }
    }

    // دالة مساعدة لتنسيق الأرقام
    function formatNumber(num) {
        return parseInt(num).toLocaleString('ar-SA');
    }

    // دالة مساعدة لتحديد كلاس الحالة
    function getStatusClass(status) {
        switch (status) {
            case 'paid':
                return 'bg-green-100 text-green-800';
            case 'partial':
                return 'bg-yellow-100 text-yellow-800';
            case 'overdue':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-red-100 text-red-800';
        }
    }
</script>
@endpush
@endsection
