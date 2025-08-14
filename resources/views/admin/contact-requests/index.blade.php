@extends('layouts.admin')

@section('title', 'طلبات التواصل')
@section('page-title', 'طلبات التواصل')
@section('page-description', 'نظام إدارة رسائل ومتابعة طلبات التواصل من العملاء')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي الطلبات</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ $stats['new_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">جديدة</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['in_progress_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">قيد المتابعة</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['resolved_requests'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">تم الحل</div>
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
                               placeholder="ابحث بالاسم، البريد، أو الموضوع..."
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
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المتابعة</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.contact_requests.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
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
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">طلبات التواصل</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $contactRequests->count() }} من أصل {{ $contactRequests->total() }} طلب</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-envelope mr-2"></i>
                    <span class="hidden lg:inline">إرسال رد جماعي</span>
                    <span class="lg:hidden">رد جماعي</span>
                </button>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @forelse($contactRequests as $request)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="text-lg font-bold text-slate-900">{{ $request->name }}</div>
                        <div class="text-sm text-slate-600">{{ $request->email }}</div>
                        <div class="text-sm text-slate-500">{{ $request->subject }}</div>
                    </div>
                    @php
                        $statusClasses = [
                            'new' => 'bg-red-100 text-red-800',
                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-gray-100 text-gray-800'
                        ];
                        $statusNames = [
                            'new' => 'جديد',
                            'in_progress' => 'قيد المتابعة',
                            'resolved' => 'تم الحل',
                            'closed' => 'مغلق'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$request->status] ?? $request->status }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-sm text-slate-600 line-clamp-2">{{ $request->message }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $request->created_at->format('Y-m-d H:i') }}</div>
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.contact_requests.show', $request) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    @if($request->status === 'new')
                    <button onclick="updateStatus({{ $request->id }}, 'in_progress')"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-semibold hover:bg-yellow-600 transition-colors">
                        متابعة
                    </button>
                    @endif

                    @if($request->status === 'in_progress')
                    <button onclick="updateStatus({{ $request->id }}, 'resolved')"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                        حل
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد طلبات تواصل</h3>
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
                                المرسل
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الموضوع
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الرسالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الحالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                تاريخ الإرسال
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($contactRequests as $request)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="contact-request-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       value="{{ $request->id }}" onchange="updateSelectionCount()">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center ml-3">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $request->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $request->email }}</div>
                                        @if($request->phone)
                                        <div class="text-sm text-slate-400">{{ $request->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900 max-w-xs truncate" title="{{ $request->subject }}">
                                    {{ $request->subject }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700 max-w-md truncate" title="{{ $request->message }}">
                                    {{ $request->message }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$request->status] ?? $request->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $request->created_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-slate-500">{{ $request->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.contact_requests.show', $request) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    @if($request->status === 'new')
                                    <button onclick="updateStatus({{ $request->id }}, 'in_progress')"
                                            class="w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-clock text-xs"></i>
                                    </button>
                                    @endif

                                    @if($request->status === 'in_progress')
                                    <button onclick="updateStatus({{ $request->id }}, 'resolved')"
                                            class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                    @endif

                                    <button onclick="replyToRequest({{ $request->id }}, '{{ $request->email }}')"
                                            class="w-8 h-8 bg-purple-500 hover:bg-purple-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-reply text-xs"></i>
                                    </button>

                                    @if(in_array($request->status, ['resolved']))
                                    <button onclick="updateStatus({{ $request->id }}, 'closed')"
                                            class="w-8 h-8 bg-gray-500 hover:bg-gray-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-archive text-xs"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-envelope text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد طلبات تواصل</h3>
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
        @if($contactRequests->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $contactRequests->firstItem() }} إلى {{ $contactRequests->lastItem() }} من أصل {{ $contactRequests->total() }} طلب
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($contactRequests->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $contactRequests->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($contactRequests->getUrlRange(1, min($contactRequests->lastPage(), 5)) as $page => $url)
                    @if ($page == $contactRequests->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($contactRequests->hasMorePages())
                    <a href="{{ $contactRequests->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
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
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-edit text-green-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">تحديث حالة الطلب</h3>
                <p class="text-slate-600 text-sm lg:text-base">هل أنت متأكد من تحديث حالة طلب التواصل؟</p>
            </div>

            <form id="status-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors">
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
    function updateStatus(requestId, newStatus) {
        document.getElementById('status-modal').classList.remove('hidden');
        document.getElementById('status-form').action = `/admin/contact-requests/${requestId}/status`;

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

    // Reply to request
    function replyToRequest(requestId, email) {
        window.open(`mailto:${email}?subject=رد على طلب التواصل #${requestId}`);
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

    // Bulk delete functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.contact-request-checkbox');

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
        const checkboxes = document.querySelectorAll('.contact-request-checkbox');
        const checkedBoxes = document.querySelectorAll('.contact-request-checkbox:checked');
        const count = checkedBoxes.length;

        const selectedCountElements = document.querySelectorAll('#selected-count, #mobile-selected-count');
        selectedCountElements.forEach(element => {
            if (element) element.textContent = count;
        });

        const bulkActionsBar = document.getElementById('bulk-actions');
        const mobileBulkActionsBar = document.getElementById('mobile-bulk-actions');

        if (count > 0) {
            if (bulkActionsBar) bulkActionsBar.classList.remove('hidden');
            if (mobileBulkActionsBar) mobileBulkActionsBar.classList.remove('hidden');
        } else {
            if (bulkActionsBar) bulkActionsBar.classList.add('hidden');
            if (mobileBulkActionsBar) mobileBulkActionsBar.classList.add('hidden');
        }

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
        const checkboxes = document.querySelectorAll('.contact-request-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectionCount();
    }

    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.contact-request-checkbox:checked');

        if (checkedBoxes.length === 0) {
            alert('يرجى تحديد طلبات الاتصال المراد حذفها');
            return;
        }

        if (confirm(`هل أنت متأكد من حذف ${checkedBoxes.length} طلب اتصال؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.contact_requests.bulk-delete") }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

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
