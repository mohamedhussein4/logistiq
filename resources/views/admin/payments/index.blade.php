@extends('layouts.admin')

@section('title', 'إدارة طلبات الدفع')
@section('page-title', 'إدارة طلبات الدفع')
@section('page-description', 'نظام إدارة شامل لجميع طلبات الدفع وإثباتاتها')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي الطلبات</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['pending'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">معلق</div>
            </div>
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['processing'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">قيد المعالجة</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['completed'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">مكتمل</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ $stats['failed'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">فشل</div>
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
                               placeholder="ابحث برقم الطلب، اسم المستخدم..."
                               class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700 placeholder-slate-400">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Payment Type Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع الدفع</label>
                    <select name="payment_type" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="product_order" {{ request('payment_type') == 'product_order' ? 'selected' : '' }}>طلب منتج</option>
                        <option value="invoice" {{ request('payment_type') == 'invoice' ? 'selected' : '' }}>فاتورة</option>
                        <option value="funding_request" {{ request('payment_type') == 'funding_request' ? 'selected' : '' }}>طلب تمويل</option>
                        <option value="other" {{ request('payment_type') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">المستخدم</label>
                    <select name="user_id" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع المستخدمين</option>
                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->user_type_name }})
                        </option>
                        @endforeach
                    </select>
                </div>
                </div>

            <!-- Filter Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
                        <i class="fas fa-undo"></i>
                    </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة طلبات الدفع</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $paymentRequests->count() }} من أصل {{ $paymentRequests->total() }} طلب</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-primary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-sync-alt mr-2"></i>
                    <span class="hidden lg:inline">تحديث الصفحة</span>
                    <span class="lg:hidden">تحديث</span>
                </button>
            </div>
        </div>

        @if($paymentRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm lg:text-base">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">رقم الطلب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">المستخدم</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">نوع الدفع</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">المبلغ</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">طريقة الدفع</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الحالة</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">التاريخ</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($paymentRequests as $paymentRequest)
                        <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 lg:py-4">
                            <div class="font-mono text-sm">{{ $paymentRequest->request_number }}</div>
                            </td>
                        <td class="py-3 lg:py-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($paymentRequest->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                    <div class="font-semibold text-slate-800">{{ $paymentRequest->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $paymentRequest->user->user_type_name }}</div>
                                    </div>
                                </div>
                            </td>
                        <td class="py-3 lg:py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($paymentRequest->payment_type === 'product_order') bg-green-100 text-green-800
                                @elseif($paymentRequest->payment_type === 'invoice') bg-blue-100 text-blue-800
                                @elseif($paymentRequest->payment_type === 'funding_request') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $paymentRequest->payment_type_name }}
                                </span>
                            </td>
                        <td class="py-3 lg:py-4">
                            <div class="font-bold text-green-600">{{ $paymentRequest->formatted_amount }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                @if($paymentRequest->payment_method === 'bank_transfer')
                                    <i class="fas fa-university text-blue-600"></i>
                                @elseif($paymentRequest->payment_method === 'electronic_wallet')
                                    <i class="fas fa-mobile-alt text-green-600"></i>
                                @else
                                    <i class="fas fa-credit-card text-gray-600"></i>
                                @endif
                                <span class="text-sm">{{ $paymentRequest->payment_method_name }}</span>
                            </div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($paymentRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($paymentRequest->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($paymentRequest->status === 'completed') bg-green-100 text-green-800
                                @elseif($paymentRequest->status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $paymentRequest->status_name }}
                                </span>
                            </td>
                        <td class="py-3 lg:py-4">
                            <div class="text-sm text-slate-600">
                                <div>{{ $paymentRequest->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $paymentRequest->created_at->format('H:i') }}</div>
                            </div>
                            </td>
                        <td class="py-3 lg:py-4">
                                <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('admin.payments.show', $paymentRequest->id) }}"
                                   class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($paymentRequest->canBeProcessed())
                                <button onclick="showStatusModal({{ $paymentRequest->id }})"
                                        class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                        title="تحديث الحالة">
                                    <i class="fas fa-edit"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>

        <!-- Pagination -->
        @if($paymentRequests->hasPages())
        <div class="mt-6 lg:mt-8">
            {{ $paymentRequests->links() }}
            </div>
                @endif

                    @else
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-credit-card text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد طلبات دفع</h3>
            <p class="text-gray-500">لم يتم إنشاء أي طلبات دفع بعد</p>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="status-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">تحديث حالة طلب الدفع</h3>
            <button onclick="hideStatusModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
                </div>

        <form id="status-form" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة الجديدة</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="pending">معلق</option>
                    <option value="processing">قيد المعالجة</option>
                    <option value="completed">مكتمل</option>
                    <option value="failed">فشل</option>
                    <option value="cancelled">ملغي</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات الإدارة (اختياري)</label>
                <textarea name="admin_notes" id="admin_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="أضف ملاحظات حول التحديث..."></textarea>
            </div>

            <div class="flex space-x-3 space-x-reverse">
                <button type="button" onclick="hideStatusModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        إلغاء
                    </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-save ml-2"></i>
                    تحديث
                    </button>
                </div>
            </form>
    </div>
</div>

@push('scripts')
<script>
function showStatusModal(paymentId) {
    const modal = document.getElementById('status-modal');
    const form = document.getElementById('status-form');

    form.action = `/admin/payments/${paymentId}/status`;
    modal.classList.remove('hidden');
}

function hideStatusModal() {
    const modal = document.getElementById('status-modal');
    modal.classList.add('hidden');
}
</script>
@endpush
@endsection
