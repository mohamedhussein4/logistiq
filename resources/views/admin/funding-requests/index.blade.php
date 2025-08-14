@extends('layouts.admin')

@section('title', 'إدارة طلبات التمويل')
@section('page-title', 'إدارة طلبات التمويل')
@section('page-description', 'نظام إدارة ومراجعة طلبات التمويل للشركات اللوجستية')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي الطلبات</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['pending_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">معلقة</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['approved_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">موافق عليها</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-sm lg:text-xl font-black text-purple-600">{{ number_format($stats['original_amount_disbursed'] ?? 0) }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">إجمالي المصروف</div>
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
                               placeholder="ابحث بالشركة، المبلغ، أو السبب..."
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
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                        <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>مصروف</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.funding_requests.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
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
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة طلبات التمويل</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $fundingRequests->count() }} من أصل {{ $fundingRequests->total() }} طلب</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
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

            @forelse($fundingRequests as $request)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-start space-x-3 space-x-reverse">
                        <input type="checkbox" class="funding-request-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-1"
                               value="{{ $request->id }}" onchange="updateSelectionCount()">
                        <div>
                            <div class="text-sm font-bold text-slate-900">#{{ $request->id }}</div>
                            <div class="text-lg font-black text-slate-900">{{ number_format($request->amount) }} ريال</div>
                            <div class="text-sm text-slate-600">{{ optional($request->logisticsCompany->user)->company_name ?? optional($request->logisticsCompany->user)->name ?? 'غير محدد' }}</div>
                        </div>
                    </div>
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'under_review' => 'bg-blue-100 text-blue-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'disbursed' => 'bg-purple-100 text-purple-800'
                        ];
                        $statusNames = [
                            'pending' => 'معلق',
                            'under_review' => 'قيد المراجعة',
                            'approved' => 'موافق عليه',
                            'rejected' => 'مرفوض',
                            'disbursed' => 'مصروف'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$request->status] ?? $request->status }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-xs text-slate-500">{{ $request->requested_at->format('Y-m-d H:i') }}</div>
                    @if($request->reason)
                    <div class="text-sm text-slate-600 mt-1 truncate">{{ $request->reason }}</div>
                    @endif
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.funding_requests.show', $request) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    @if($request->status === 'pending')
                    <button onclick="approveRequest({{ $request->id }})"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                        موافقة
                    </button>

                    <button onclick="rejectRequest({{ $request->id }})"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition-colors">
                        رفض
                    </button>
                    @endif

                    @if($request->status === 'approved')
                    <button onclick="disburseRequest({{ $request->id }})"
                            class="px-4 py-2 bg-purple-500 text-white rounded-lg text-sm font-semibold hover:bg-purple-600 transition-colors">
                        صرف
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد طلبات تمويل</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على طلبات تطابق معايير البحث</p>
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
                                رقم الطلب
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الشركة اللوجستية
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المبلغ المطلوب
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                السبب
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الحالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                تاريخ الطلب
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($fundingRequests as $request)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="funding-request-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       value="{{ $request->id }}" onchange="updateSelectionCount()">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900">#{{ $request->id }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center ml-3">
                                        <i class="fas fa-truck text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ optional($request->logisticsCompany->user)->company_name ?? optional($request->logisticsCompany->user)->name ?? 'غير محدد' }}</div>
                                        <div class="text-sm text-slate-500">{{ optional($request->logisticsCompany->user)->email ?? 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ number_format($request->amount) }}</div>
                                <div class="text-sm text-slate-500">ريال سعودي</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700 max-w-xs truncate" title="{{ $request->reason }}">
                                    {{ $request->reason }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$request->status] ?? $request->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $request->requested_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-slate-500">{{ $request->requested_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.funding_requests.show', $request) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <a href="{{ route('admin.funding_requests.print', $request) }}" target="_blank"
                                       class="w-8 h-8 bg-purple-500 hover:bg-purple-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>

                                    @if($request->status === 'pending')
                                    <button onclick="approveRequest({{ $request->id }})"
                                            class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>

                                    <button onclick="rejectRequest({{ $request->id }})"
                                            class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    @endif

                                    @if($request->status === 'approved')
                                    <button onclick="disburseRequest({{ $request->id }})"
                                            class="w-8 h-8 bg-purple-500 hover:bg-purple-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-money-bill-wave text-xs"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد طلبات تمويل</h3>
                                    <p class="text-slate-500">لم يتم العثور على طلبات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($fundingRequests->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $fundingRequests->firstItem() }} إلى {{ $fundingRequests->lastItem() }} من أصل {{ $fundingRequests->total() }} طلب
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($fundingRequests->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $fundingRequests->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($fundingRequests->getUrlRange(1, min($fundingRequests->lastPage(), 5)) as $page => $url)
                    @if ($page == $fundingRequests->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($fundingRequests->hasMorePages())
                    <a href="{{ $fundingRequests->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
                @else
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">التالي</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Action Modals -->
@include('admin.funding-requests.modals')

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

    // Action functions
    function approveRequest(id) {
        document.getElementById('approve-modal').classList.remove('hidden');
        document.getElementById('approve-form').action = `/admin/funding-requests/${id}/approve`;
    }

    function rejectRequest(id) {
        document.getElementById('reject-modal').classList.remove('hidden');
        document.getElementById('reject-form').action = `/admin/funding-requests/${id}/reject`;
    }

    function disburseRequest(id) {
        document.getElementById('disburse-modal').classList.remove('hidden');
        document.getElementById('disburse-form').action = `/admin/funding-requests/${id}/disburse`;
    }

    // Close modals
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Bulk delete functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.funding-request-checkbox');
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
        const checkboxes = document.querySelectorAll('.funding-request-checkbox');
        const checkedBoxes = document.querySelectorAll('.funding-request-checkbox:checked');
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
        const checkboxes = document.querySelectorAll('.funding-request-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectionCount();
    }

    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.funding-request-checkbox:checked');

        if (checkedBoxes.length === 0) {
            alert('يرجى تحديد العناصر المراد حذفها');
            return;
        }

        if (confirm(`هل أنت متأكد من حذف ${checkedBoxes.length} طلب تمويل؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.funding_requests.bulk-delete") }}';

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
