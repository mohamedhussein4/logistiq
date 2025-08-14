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
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Balance Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Available Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الرصيد المتاح</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format(auth()->user()->available_balance ?? 0) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">الرصيد القابل للاستخدام</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-wallet text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Used Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الرصيد المستخدم</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format(auth()->user()->used_balance ?? 0) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">المبلغ المستخدم</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-minus-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Remaining Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الرصيد المتبقي</p>
                        @php
                            $remainingBalance = (auth()->user()->available_balance ?? 0) - (auth()->user()->used_balance ?? 0);
                        @endphp
                        <p class="text-2xl font-bold {{ $remainingBalance < 0 ? 'text-red-600' : 'text-blue-600' }}">
                            {{ number_format($remainingBalance) }} ر.س
                        </p>
                        <p class="text-xs text-gray-500 mt-1">المتبقي للاستخدام</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-coins text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">إجمالي الرصيد</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format(auth()->user()->total_balance ?? 0) }} ر.س</p>
                        <p class="text-xs text-gray-500 mt-1">إجمالي الرصيد الممنوح</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Total Requests -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_requests'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">العدد الكلي للطلبات</p>
                        @if(config('app.debug') && isset($stats['total_requests']))
                            <p class="text-xs text-blue-500 mt-1">🔧 Debug: {{ $stats['total_requests'] }} طلب مسجل</p>
                        @endif
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
                        <p class="text-xs text-gray-500 mt-1">في انتظار المراجعة</p>
                        @if(config('app.debug') && isset($stats['pending_requests']))
                            <p class="text-xs text-orange-500 mt-1">🔧 Debug: {{ $stats['pending_requests'] }} طلب معلق</p>
                        @endif
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

                        <!-- قسم الشركة الطالبة للخدمة -->
                        <div class="border-t pt-4">
                            <h4 class="text-md font-semibold text-gray-800 mb-4">
                                <i class="fas fa-building text-purple-600 ml-2"></i>
                                الشركة الطالبة للخدمة
                            </h4>

                            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="grid md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">اسم الشركة</label>
                                        <input type="text" name="clients[1][company_name]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="مثال: شركة التجارة المتقدمة" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">اسم المسؤول</label>
                                        <input type="text" name="clients[1][contact_person]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="مثال: أحمد محمد السعد" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">البريد الإلكتروني</label>
                                        <input type="email" name="clients[1][email]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="ahmed@company.com" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">رقم الهاتف</label>
                                        <input type="tel" name="clients[1][phone]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="0501234567" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">قيمة الخدمة المطلوبة (ر.س)</label>
                                        <input type="number" name="clients[1][amount]" id="service-amount"
                                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="50000" min="1000" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-2">تاريخ تنفيذ الخدمة</label>
                                        <input type="date" name="clients[1][due_date]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">نوع الخدمة المطلوبة</label>
                                    <select name="clients[1][service_type]" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">اختر نوع الخدمة</option>
                                        <option value="transportation">خدمات النقل والشحن</option>
                                        <option value="logistics">الخدمات اللوجستية</option>
                                        <option value="warehousing">خدمات التخزين</option>
                                        <option value="distribution">خدمات التوزيع</option>
                                        <option value="consulting">استشارات لوجستية</option>
                                        <option value="other">خدمات أخرى</option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">تفاصيل الخدمة</label>
                                    <textarea rows="3" name="clients[1][service_details]"
                                              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="اكتب تفاصيل الخدمة المطلوبة..."></textarea>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">إرفاق عقد أو اتفاقية الخدمة</label>
                                    <input type="file" name="clients[1][invoice_document]"
                                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
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
                            @if(isset($recentFundingRequests) && $recentFundingRequests->count() > 0)
                                @foreach($recentFundingRequests as $request)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-gray-800 font-medium">طلب تمويل #{{ $request->id }}</p>
                                            <p class="text-blue-600 text-sm font-semibold">{{ number_format($request->amount ?? 0) }} ر.س</p>
                                            <p class="text-gray-500 text-xs">{{ $request->created_at ? $request->created_at->diffForHumans() : 'غير محدد' }}</p>
                                        </div>
                                        <div class="text-left flex flex-col items-end space-y-2">
                                            <!-- Status Badge -->
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

                                            <!-- View Details Button -->
                                            @if(isset($request->id))
                                                <button onclick="showFundingRequestDetails({{ $request->id }})"
                                                   class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-eye text-xs ml-1"></i>
                                                    عرض التفاصيل
                                                </button>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 bg-gray-400 text-white text-xs font-medium rounded-md">
                                                    <i class="fas fa-info-circle text-xs ml-1"></i>
                                                    طلب #{{ $request->id ?? 'N/A' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="font-medium mb-2">لا توجد طلبات تمويل حتى الآن</p>
                                    <p class="text-sm mb-3">أرسل أول طلب تمويل باستخدام النموذج</p>
                                    @if(config('app.debug'))
                                        <div class="text-xs bg-gray-100 p-2 rounded mt-4">
                                            <p><strong>معلومات التشخيص:</strong></p>
                                            <p>المستخدم الحالي: {{ auth()->user()->name }} (ID: {{ auth()->user()->id }})</p>
                                            <p>نوع المستخدم: {{ auth()->user()->user_type }}</p>
                                            @if(auth()->user()->logisticsCompany)
                                                <p>ID الشركة اللوجستية: {{ auth()->user()->logisticsCompany->id }}</p>
                                                <p>اسم الشركة: {{ auth()->user()->logisticsCompany->company_name ?? 'غير محدد' }}</p>
                                            @else
                                                <p class="text-red-600">⚠️ الشركة اللوجستية غير موجودة</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
</div>
            </div>
        </div>
    </div>
</section>

<!-- Modal لعرض تفاصيل طلب التمويل -->
<div id="fundingRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b">
            <h3 class="text-lg font-semibold text-gray-900">تفاصيل طلب التمويل</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeFundingModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div id="modalContent" class="py-4 max-h-96 overflow-y-auto">
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">جارٍ تحميل البيانات...</p>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end pt-3 border-t">
            <button type="button"
                    onclick="closeFundingModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                إغلاق
            </button>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">إجراءات سريعة</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('logistics.profile') }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center block">
                <i class="fas fa-user-circle text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">الملف الشخصي</p>
            </a>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-headset text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">الدعم الفني</p>
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // File upload handling for main documents
    document.getElementById('documents').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            const fileNames = Array.from(files).map(file => file.name).slice(0, 3);
            const displayText = fileNames.join(', ') + (files.length > 3 ? ` و ${files.length - 3} ملف آخر` : '');

            // Update upload area text
            const uploadArea = e.target.closest('.border-dashed');
            const textElement = uploadArea.querySelector('p');
            if (textElement) {
                textElement.textContent = `تم اختيار ${files.length} ملف: ${displayText}`;
            }
        }
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'bg-blue-50');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('documents').files = files;
                document.getElementById('documents').dispatchEvent(new Event('change'));
            }
        });
    }

    // Sync service amount with main funding amount
    document.getElementById('service-amount').addEventListener('input', function(e) {
        const serviceAmount = parseFloat(e.target.value) || 0;
        const mainAmountInput = document.querySelector('input[name="amount"]');
        if (mainAmountInput) {
            mainAmountInput.value = serviceAmount;
        }
    });

    // Sync main amount with service amount
    document.querySelector('input[name="amount"]').addEventListener('input', function(e) {
        const mainAmount = parseFloat(e.target.value) || 0;
        const serviceAmountInput = document.getElementById('service-amount');
        if (serviceAmountInput) {
            serviceAmountInput.value = mainAmount;
        }
    });

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const amount = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
        const serviceAmount = parseFloat(document.getElementById('service-amount').value) || 0;

        if (amount !== serviceAmount && amount > 0 && serviceAmount > 0) {
            e.preventDefault();
            alert('يجب أن يكون مبلغ التمويل مطابق لقيمة الخدمة المطلوبة');
            return false;
        }

        if (amount < 1000) {
            e.preventDefault();
            alert('الحد الأدنى لمبلغ التمويل هو 1000 ريال سعودي');
            return false;
        }
    });

    // Set minimum date to today for service date
    document.addEventListener('DOMContentLoaded', function() {
        const serviceDateInput = document.querySelector('input[name="clients[1][due_date]"]');
        if (serviceDateInput) {
            const today = new Date().toISOString().split('T')[0];
            serviceDateInput.setAttribute('min', today);
        }
    });

    // Modal Functions
    function showFundingRequestDetails(requestId) {
        const modal = document.getElementById('fundingRequestModal');
        const modalContent = document.getElementById('modalContent');

        // Show modal
        modal.classList.remove('hidden');

        // Show loading
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">جارٍ تحميل البيانات...</p>
            </div>
        `;

        // Fetch data
        fetch(`/logistics/funding/${requestId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalContent.innerHTML = buildModalContent(data.data);
            } else {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                        <p class="text-red-600">حدث خطأ في تحميل البيانات</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                    <p class="text-red-600">حدث خطأ في الاتصال بالخادم</p>
                </div>
            `;
        });
    }

    function buildModalContent(data) {
        let clientsHtml = '';
        if (data.client_debts && data.client_debts.length > 0) {
            clientsHtml = data.client_debts.map(client => `
                <div class="border border-gray-200 rounded-lg p-3 mb-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                        <div><strong>الشركة:</strong> ${client.company_name}</div>
                        <div><strong>المبلغ:</strong> <span class="text-green-600 font-bold">${client.formatted_amount}</span></div>
                        <div><strong>المسؤول:</strong> ${client.contact_person}</div>
                        <div><strong>الاستحقاق:</strong> ${client.due_date}</div>
                        <div class="md:col-span-2"><strong>البريد:</strong> ${client.email}</div>
                        <div class="md:col-span-2"><strong>الهاتف:</strong> ${client.phone}</div>
                        ${client.invoice_document ? `<div class="md:col-span-2"><a href="${client.invoice_document}" target="_blank" class="text-blue-600 hover:underline"><i class="fas fa-file-pdf mr-1"></i>عرض الفاتورة</a></div>` : ''}
                    </div>
                </div>
            `).join('');
        }

        let documentsHtml = '';
        if (data.documents && data.documents.length > 0) {
            documentsHtml = `
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-900 mb-2">المستندات المرفقة:</h4>
                    <div class="space-y-2">
                        ${data.documents.map((doc, index) => `
                            <a href="${doc}" target="_blank" class="block p-2 bg-gray-50 rounded hover:bg-gray-100 text-sm">
                                <i class="fas fa-file mr-2"></i>مستند ${index + 1}
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        return `
            <div class="space-y-4">
                <!-- معلومات أساسية -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <strong class="text-gray-700">رقم الطلب:</strong>
                        <span class="block text-gray-900">#${data.id}</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">المبلغ:</strong>
                        <span class="block text-green-600 font-bold text-lg">${data.formatted_amount}</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">السبب:</strong>
                        <span class="block text-gray-900">${data.reason_text}</span>
                    </div>
                    <div>
                        <strong class="text-gray-700">الحالة:</strong>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            ${data.status_color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : ''}
                            ${data.status_color === 'green' ? 'bg-green-100 text-green-800' : ''}
                            ${data.status_color === 'red' ? 'bg-red-100 text-red-800' : ''}
                            ${data.status_color === 'blue' ? 'bg-blue-100 text-blue-800' : ''}
                            ${data.status_color === 'gray' ? 'bg-gray-100 text-gray-800' : ''}">
                            ${data.status_text}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700">تاريخ الإنشاء:</strong>
                        <span class="block text-gray-900">${data.created_at} (${data.created_at_human})</span>
                    </div>
                    ${data.description ? `
                        <div class="md:col-span-2">
                            <strong class="text-gray-700">الوصف:</strong>
                            <p class="mt-1 text-gray-900 bg-white p-2 rounded border">${data.description}</p>
                        </div>
                    ` : ''}
                </div>

                <!-- الشركات الطالبة للخدمة -->
                ${clientsHtml ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">الشركات الطالبة للخدمة (${data.client_debts.length}):</h4>
                        ${clientsHtml}
                    </div>
                ` : ''}

                ${documentsHtml}
            </div>
        `;
    }

    function closeFundingModal() {
        document.getElementById('fundingRequestModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('fundingRequestModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFundingModal();
        }
    });
</script>
@endpush
@endsection
