@extends('layouts.admin')

@section('title', 'خدمات الربط')
@section('page-title', 'خدمات الربط')
@section('page-description', 'نظام إدارة خدمات ربط الشركات اللوجستية مع شركات التمويل')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_services'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي الخدمات</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['active_services'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">نشطة</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $stats['total_partnerships'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">إجمالي الشراكات</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-sm lg:text-xl font-black text-yellow-600">{{ number_format($stats['total_commission'] ?? 0) }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">إجمالي العمولات</div>
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
                               placeholder="ابحث بالاسم، النوع، أو الوصف..."
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
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>موقوف</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.linking_services.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
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
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">خدمات الربط المتاحة</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $linkingServices->count() }} من أصل {{ $linkingServices->total() }} خدمة</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
                <button onclick="openCreateModal()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">خدمة جديدة</span>
                    <span class="lg:hidden">جديد</span>
                </button>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @forelse($linkingServices as $service)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="text-lg font-bold text-slate-900">{{ $service->name }}</div>
                        <div class="text-sm text-slate-600">{{ $service->type }}</div>
                        <div class="text-lg font-black text-slate-900">{{ $service->commission_rate }}% عمولة</div>
                    </div>
                    @php
                        $statusClasses = [
                            'active' => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'suspended' => 'bg-red-100 text-red-800'
                        ];
                        $statusNames = [
                            'active' => 'نشط',
                            'inactive' => 'غير نشط',
                            'pending' => 'معلق',
                            'suspended' => 'موقوف'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$service->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$service->status] ?? $service->status }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-sm text-slate-600 line-clamp-2">{{ $service->description }}</div>
                    <div class="text-xs text-slate-500 mt-1">{{ $service->created_at->format('Y-m-d') }}</div>
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.linking_services.show', $service) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    <button onclick="editService({{ $service->id }})"
                            class="px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 transition-colors">
                        تعديل
                    </button>

                    <button onclick="toggleStatus({{ $service->id }}, '{{ $service->status }}')"
                            class="px-4 py-2 {{ $service->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg text-sm font-semibold transition-colors">
                        {{ $service->status === 'active' ? 'إيقاف' : 'تفعيل' }}
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-link text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد خدمات ربط</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على خدمات تطابق معايير البحث</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200">
            <div class="table-responsive">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الخدمة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                النوع
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                معدل العمولة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الشراكات
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الحالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                تاريخ الإنشاء
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($linkingServices as $service)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-primary rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-link text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-slate-900">{{ $service->name }}</div>
                                        @if($service->description)
                                        <div class="text-sm text-slate-600 max-w-xs truncate" title="{{ $service->description }}">
                                            {{ $service->description }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900">{{ $service->type }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ $service->commission_rate }}%</div>
                                <div class="text-sm text-slate-500">عمولة</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ $service->partnerships_count ?? 0 }}</div>
                                <div class="text-sm text-slate-500">شراكة نشطة</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$service->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$service->status] ?? $service->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $service->created_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-slate-500">{{ $service->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.linking_services.show', $service) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <button onclick="editService({{ $service->id }})"
                                            class="w-8 h-8 bg-orange-500 hover:bg-orange-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>

                                    <button onclick="toggleStatus({{ $service->id }}, '{{ $service->status }}')"
                                            class="w-8 h-8 {{ $service->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas {{ $service->status === 'active' ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                                    </button>

                                    <button onclick="deleteService({{ $service->id }})"
                                            class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-link text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد خدمات ربط</h3>
                                    <p class="text-slate-500">لم يتم العثور على خدمات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($linkingServices->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $linkingServices->firstItem() }} إلى {{ $linkingServices->lastItem() }} من أصل {{ $linkingServices->total() }} خدمة
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($linkingServices->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $linkingServices->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($linkingServices->getUrlRange(1, min($linkingServices->lastPage(), 5)) as $page => $url)
                    @if ($page == $linkingServices->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($linkingServices->hasMorePages())
                    <a href="{{ $linkingServices->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
                @else
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">التالي</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Create/Edit Service Modal -->
<div id="service-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-2xl w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-link text-blue-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2" id="modal-title">إضافة خدمة ربط جديدة</h3>
                <p class="text-slate-600 text-sm lg:text-base">املأ المعلومات أدناه لإضافة خدمة ربط جديدة</p>
            </div>

            <form id="service-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                <div id="method-field"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم الخدمة <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="أدخل اسم الخدمة">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع الخدمة <span class="text-red-500">*</span></label>
                        <select name="type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">اختر نوع الخدمة</option>
                            <option value="funding">تمويل</option>
                            <option value="logistics">لوجستية</option>
                            <option value="payment">مدفوعات</option>
                            <option value="insurance">تأمين</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="وصف موجز للخدمة"></textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">معدل العمولة (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="commission_rate" step="0.01" min="0" max="100" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                        <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                            <option value="pending">معلق</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        حفظ الخدمة
                    </button>
                    <button type="button" onclick="closeServiceModal()"
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

    // Create new service
    function openCreateModal() {
        document.getElementById('service-modal').classList.remove('hidden');
        document.getElementById('modal-title').textContent = 'إضافة خدمة ربط جديدة';
        document.getElementById('service-form').action = '{{ route("admin.linking_services.store") }}';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('service-form').reset();
    }

    // Edit service
    function editService(serviceId) {
        document.getElementById('service-modal').classList.remove('hidden');
        document.getElementById('modal-title').textContent = 'تعديل خدمة الربط';
        document.getElementById('service-form').action = `/admin/linking-services/${serviceId}`;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Here you would typically fetch and populate the form with service data
        // For now, we'll just show the modal
    }

    // Close modal
    function closeServiceModal() {
        document.getElementById('service-modal').classList.add('hidden');
    }

    // Toggle status
    function toggleStatus(serviceId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/linking-services/${serviceId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="status" value="${newStatus}">
        `;
        document.body.appendChild(form);
        form.submit();
    }

    // Delete service
    function deleteService(serviceId) {
        if (confirm('هل أنت متأكد من حذف هذه الخدمة؟')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/linking-services/${serviceId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'service-modal') {
            closeServiceModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeServiceModal();
        }
    });
</script>
@endpush
@endsection
