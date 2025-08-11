@extends('layouts.main')

@section('title', 'إدارة الشركات الطالبة للخدمة - لوحة الإدارة - Link2u')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-green-600 to-green-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">إدارة الشركات الطالبة للخدمة</h1>
                <p class="text-green-100">لوحة الإدارة - متابعة المستحقات المالية وحالات السداد</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                    <div class="text-sm opacity-80">إجمالي المستحقات</div>
                    <div class="text-2xl font-bold">٢,٤٥٠,٠٠٠ ر.س</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Overview Stats -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Outstanding -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">إجمالي المستحقات</p>
                        <p class="text-2xl font-bold text-red-600">٢,٤٥٠,٠٠٠ ر.س</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Overdue Amount -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">المبالغ المتأخرة</p>
                        <p class="text-2xl font-bold text-orange-600">٧٨٠,٠٠٠ ر.س</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Collected This Month -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">المحصل هذا الشهر</p>
                        <p class="text-2xl font-bold text-green-600">٣٢٠,٠٠٠ ر.س</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Clients -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">عدد الشركات النشطة</p>
                        <p class="text-2xl font-bold text-blue-600">١٨</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-building text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Client List -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-building text-green-600 ml-2"></i>
                            قائمة الشركات الطالبة للخدمة
                        </h3>
                        <div class="flex space-x-2 space-x-reverse">
                            <select class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                                <option>جميع الشركات</option>
                                <option>المتأخرة في السداد</option>
                                <option>المنتظمة في السداد</option>
                                <option>تحت المراقبة</option>
                            </select>
                            <button class="px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                <i class="fas fa-plus"></i> إضافة شركة
                            </button>
                        </div>
                    </div>

                    <!-- Clients Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">اسم الشركة</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">إجمالي المستحقات</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">آخر دفعة</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">تاريخ الاستحقاق</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">الحالة</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-700">العمليات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium text-sm ml-3">
                                                شأ
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">شركة الأمل للنقل</div>
                                                <div class="text-xs text-gray-500">AMT-٢٠٢٤</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-red-600">٤٥٠,٠٠٠ ر.س</td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-900">٥٠,٠٠٠ ر.س</div>
                                        <div class="text-xs text-gray-500">٢٠٢٤-٠١-١٠</div>
                                    </td>
                                    <td class="px-4 py-4 text-red-600 font-medium">٢٠٢٤-٠١-٢٥</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                            متأخر
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-1 space-x-reverse">
                                            <button class="text-blue-600 hover:text-blue-800 text-xs p-1" onclick="viewClient('AMT-2024')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800 text-xs p-1" onclick="recordPayment('AMT-2024')">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            <button class="text-orange-600 hover:text-orange-800 text-xs p-1" onclick="createPlan('AMT-2024')">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-medium text-sm ml-3">
                                                شس
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">شركة السرعة للخدمات</div>
                                                <div class="text-xs text-gray-500">SSS-٢٠٢٤</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-blue-600">٢٨٠,٠٠٠ ر.س</td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-900">٧٠,٠٠٠ ر.س</div>
                                        <div class="text-xs text-gray-500">٢٠٢٤-٠١-١٨</div>
                                    </td>
                                    <td class="px-4 py-4 text-green-600 font-medium">٢٠٢٤-٠٢-١٥</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            منتظم
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-1 space-x-reverse">
                                            <button class="text-blue-600 hover:text-blue-800 text-xs p-1" onclick="viewClient('SSS-2024')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800 text-xs p-1" onclick="recordPayment('SSS-2024')">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            <button class="text-orange-600 hover:text-orange-800 text-xs p-1" onclick="createPlan('SSS-2024')">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-medium text-sm ml-3">
                                                شن
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">شركة النخبة للتوزيع</div>
                                                <div class="text-xs text-gray-500">ENT-٢٠٢٤</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-blue-600">٦٢٠,٠٠٠ ر.س</td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-900">١٠٠,٠٠٠ ر.س</div>
                                        <div class="text-xs text-gray-500">٢٠٢٤-٠١-٢٠</div>
                                    </td>
                                    <td class="px-4 py-4 text-orange-600 font-medium">٢٠٢٤-٠٢-٠١</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                            تحت المراقبة
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-1 space-x-reverse">
                                            <button class="text-blue-600 hover:text-blue-800 text-xs p-1" onclick="viewClient('ENT-2024')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800 text-xs p-1" onclick="recordPayment('ENT-2024')">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            <button class="text-orange-600 hover:text-orange-800 text-xs p-1" onclick="createPlan('ENT-2024')">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-medium text-sm ml-3">
                                                مت
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">مؤسسة التقدم للشحن</div>
                                                <div class="text-xs text-gray-500">PFS-٢٠٢٤</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-blue-600">٣٢٥,٠٠٠ ر.س</td>
                                    <td class="px-4 py-4">
                                        <div class="text-gray-900">٦٥,٠٠٠ ر.س</div>
                                        <div class="text-xs text-gray-500">٢٠٢٤-٠١-١٥</div>
                                    </td>
                                    <td class="px-4 py-4 text-green-600 font-medium">٢٠٢٤-٠٢-٢٨</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            منتظم
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex space-x-1 space-x-reverse">
                                            <button class="text-blue-600 hover:text-blue-800 text-xs p-1" onclick="viewClient('PFS-2024')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800 text-xs p-1" onclick="recordPayment('PFS-2024')">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            <button class="text-orange-600 hover:text-orange-800 text-xs p-1" onclick="createPlan('PFS-2024')">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Payment Recording Form -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-money-check-alt text-green-600 ml-2"></i>
                            تسجيل دفعة جديدة
                        </h3>

                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اختر الشركة</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                                    <option value="">اختر شركة...</option>
                                    <option value="AMT-2024">شركة الأمل للنقل</option>
                                    <option value="SSS-2024">شركة السرعة للخدمات</option>
                                    <option value="ENT-2024">شركة النخبة للتوزيع</option>
                                    <option value="PFS-2024">مؤسسة التقدم للشحن</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ المدفوع (ر.س)</label>
                                <input type="number"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                       placeholder="مثال: 50000">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الدفع</label>
                                <input type="date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                       value="{{ date('Y-m-d') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">طريقة الدفع</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                                    <option value="bank_transfer">تحويل بنكي</option>
                                    <option value="cash">نقداً</option>
                                    <option value="check">شيك</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                                <textarea rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                                          placeholder="ملاحظات إضافية..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                                <i class="fas fa-save ml-2"></i>
                                تسجيل الدفعة
                            </button>
                        </form>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">إحصائيات سريعة</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">معدل التحصيل الشهري</span>
                                <span class="font-semibold text-green-600">٨٥%</span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <span class="text-sm text-gray-600">عدد الدفعات اليوم</span>
                                <span class="font-semibold text-blue-600">٧</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">المتوسط اليومي</span>
                                <span class="font-semibold text-purple-600">٤٥,٠٠٠ ر.س</span>
                            </div>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">تصدير التقارير</h3>

                        <div class="space-y-3">
                            <button class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-file-excel ml-2"></i>
                                تصدير Excel
                            </button>

                            <button class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors text-sm">
                                <i class="fas fa-file-pdf ml-2"></i>
                                تصدير PDF
                            </button>

                            <button class="w-full bg-gray-600 text-white py-2 rounded-md hover:bg-gray-700 transition-colors text-sm">
                                <i class="fas fa-print ml-2"></i>
                                طباعة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modals -->
<!-- Payment Plan Modal -->
<div id="paymentPlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">إنشاء خطة تقسيط</h3>
            <button onclick="closePaymentPlanModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">عدد الأقساط</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="3">٣ أقساط</option>
                    <option value="6">٦ أقساط</option>
                    <option value="12">١٢ قسط</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ بداية الأقساط</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ القسط الشهري</label>
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="يحسب تلقائياً" readonly>
            </div>

            <div class="flex space-x-3 space-x-reverse pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                    إنشاء الخطة
                </button>
                <button type="button" onclick="closePaymentPlanModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-400">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function viewClient(clientId) {
        alert('عرض تفاصيل العميل: ' + clientId);
    }

    function recordPayment(clientId) {
        alert('تسجيل دفعة للعميل: ' + clientId);
    }

    function createPlan(clientId) {
        document.getElementById('paymentPlanModal').classList.remove('hidden');
        document.getElementById('paymentPlanModal').classList.add('flex');
    }

    function closePaymentPlanModal() {
        document.getElementById('paymentPlanModal').classList.add('hidden');
        document.getElementById('paymentPlanModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.getElementById('paymentPlanModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentPlanModal();
        }
    });
</script>
@endpush
@endsection
