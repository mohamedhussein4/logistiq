@extends('layouts.main')

@section('title', 'لوحة الشركات اللوجستية - LogistiQ')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">لوحة الشركات اللوجستية</h1>
                <p class="text-blue-100">مرحباً بك في لوحة إدارة التمويل والمستحقات</p>
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
            <!-- Current Balance -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">الرصيد المتاح للسحب</p>
                        <p class="text-2xl font-bold text-green-600">١٢٥,٠٠٠ ر.س</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-wallet text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Funded -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">إجمالي المبلغ المصروف</p>
                        <p class="text-2xl font-bold text-blue-600">٨٥٠,٠٠٠ ر.س</p>
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
                        <p class="text-sm text-gray-600 mb-1">إجمالي عدد الطلبات</p>
                        <p class="text-2xl font-bold text-purple-600">٢٣</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Last Request Status -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">حالة آخر طلب</p>
                        <p class="text-sm font-medium text-green-600">تم الصرف</p>
                        <p class="text-xs text-gray-500">٢٠٢٤-٠١-١٥</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
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

                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                مبلغ التمويل المطلوب (ر.س)
                            </label>
                            <input type="number"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="مثال: 50000"
                                   min="1000">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                سبب الطلب
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="اكتب تفاصيل إضافية حول الطلب..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                إرفاق مستندات
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">اسحب الملفات هنا أو اضغط للاختيار</p>
                                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (الحد الأقصى 5 ميجا)</p>
                                <input type="file" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-paper-plane ml-2"></i>
                            إرسال الطلب
                        </button>
                    </form>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-history text-blue-600 ml-2"></i>
                            سجل العمليات السابقة
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

                    <!-- Transaction Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">رقم الطلب</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">تاريخ الطلب</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">المبلغ</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">الحالة</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">تاريخ الصرف</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">العمليات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-blue-600">#٢٠٢٤٠١١٥</td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٤-٠١-١٥</td>
                                    <td class="px-4 py-3 font-medium">٧٥,٠٠٠ ر.س</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            تم الصرف
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٤-٠١-١٦</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-eye"></i> عرض
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-blue-600">#٢٠٢٤٠١١٠</td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٤-٠١-١٠</td>
                                    <td class="px-4 py-3 font-medium">٥٠,٠٠٠ ر.س</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            تم الصرف
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٤-٠١-١١</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-eye"></i> عرض
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-blue-600">#٢٠٢٤٠١٠٥</td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٤-٠١-٠٥</td>
                                    <td class="px-4 py-3 font-medium">١٠٠,٠٠٠ ر.س</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                            قيد المراجعة
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-eye"></i> عرض
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-blue-600">#٢٠٢٣١٢٢٨</td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٣-١٢-٢٨</td>
                                    <td class="px-4 py-3 font-medium">٦٠,٠٠٠ ر.س</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            تم الصرف
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٣-١٢-٢٩</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-eye"></i> عرض
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-blue-600">#٢٠٢٣١٢٢٠</td>
                                    <td class="px-4 py-3 text-gray-600">٢٠٢٣-١٢-٢٠</td>
                                    <td class="px-4 py-3 font-medium">٤٥,٠٠٠ ر.س</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                            مرفوض
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400">-</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-600 hover:text-blue-800 text-xs">
                                            <i class="fas fa-eye"></i> عرض
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            عرض ١-٥ من ٢٣ نتيجة
                        </div>
                        <div class="flex space-x-1 space-x-reverse">
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">السابق</button>
                            <button class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">١</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">٢</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">٣</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">التالي</button>
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
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-download text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">تحميل كشف حساب</p>
            </button>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-calculator text-green-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">حاسبة التمويل</p>
            </button>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-phone text-purple-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">تواصل مع المختص</p>
            </button>
            <button class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                <i class="fas fa-question-circle text-orange-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium">الدعم الفني</p>
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // File upload handling
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            const fileList = Array.from(files).map(file => file.name).join(', ');
            alert('تم اختيار الملفات: ' + fileList);
        }
    });

    // Drag and drop file upload
    const dropZone = document.querySelector('.border-dashed');

    dropZone.addEventListener('click', function() {
        document.querySelector('input[type="file"]').click();
    });

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
            document.querySelector('input[type="file"]').files = files;
            const fileList = Array.from(files).map(file => file.name).join(', ');
            alert('تم إسقاط الملفات: ' + fileList);
        }
    });
</script>
@endpush
@endsection
