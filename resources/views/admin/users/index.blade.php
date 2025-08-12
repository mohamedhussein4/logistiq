@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')
@section('page-title', 'إدارة المستخدمين')
@section('page-description', 'نظام إدارة شامل لجميع المستخدمين والشركات المسجلة')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي المستخدمين</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['active_users'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">نشط</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['pending_users'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">معلق</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ $stats['suspended_users'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">محظور</div>
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
                               placeholder="ابحث بالاسم، البريد، الشركة..."
                               class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700 placeholder-slate-400">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- User Type Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع المستخدم</label>
                    <select name="user_type" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>مدير النظام</option>
                        <option value="logistics" {{ request('user_type') == 'logistics' ? 'selected' : '' }}>شركة لوجستية</option>
                        <option value="service_company" {{ request('user_type') == 'service_company' ? 'selected' : '' }}>شركة طالبة للخدمة</option>
                        <option value="regular" {{ request('user_type') == 'regular' ? 'selected' : '' }}>مستخدم عادي</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>محظور</option>
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-end space-x-2 space-x-reverse">
                <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                    <i class="fas fa-search mr-2"></i>
                    بحث
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة المستخدمين</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $users->count() }} من أصل {{ $users->total() }} مستخدم</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
                <a href="{{ route('admin.users.create') }}" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">مستخدم جديد</span>
                    <span class="lg:hidden">جديد</span>
                </a>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @forelse($users as $user)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <!-- User Avatar -->
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                            {{ $user->user_type === 'admin' ? 'bg-gradient-to-br from-purple-500 to-pink-500' :
                               ($user->user_type === 'logistics' ? 'bg-gradient-to-br from-blue-500 to-cyan-500' :
                               ($user->user_type === 'service_company' ? 'bg-gradient-to-br from-green-500 to-emerald-500' : 'bg-gradient-to-br from-gray-500 to-slate-500')) }}">
                            <i class="text-white text-sm
                                {{ $user->user_type === 'admin' ? 'fas fa-crown' :
                                   ($user->user_type === 'logistics' ? 'fas fa-truck' :
                                   ($user->user_type === 'service_company' ? 'fas fa-building' : 'fas fa-user')) }}">
                            </i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="text-lg font-bold text-slate-900 truncate">{{ $user->name }}</div>
                            <div class="text-sm text-slate-600 truncate">{{ $user->email }}</div>
                            @if($user->company_name)
                            <div class="text-sm text-slate-500 truncate">{{ $user->company_name }}</div>
                            @endif
                        </div>
                    </div>

                    @php
                        $statusClasses = [
                            'active' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'suspended' => 'bg-red-100 text-red-800',
                        ];
                        $statusNames = [
                            'active' => 'نشط',
                            'pending' => 'معلق',
                            'suspended' => 'محظور',
                        ];
                        $typeClasses = [
                            'admin' => 'bg-purple-100 text-purple-800',
                            'logistics' => 'bg-blue-100 text-blue-800',
                            'service_company' => 'bg-green-100 text-green-800',
                            'user' => 'bg-gray-100 text-gray-800',
                        ];
                        $typeNames = [
                            'admin' => 'مدير',
                            'logistics' => 'لوجستية',
                            'service_company' => 'طالبة خدمة',
                            'user' => 'مستخدم',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$user->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$user->status] ?? $user->status }}
                    </span>
                </div>

                <div class="mb-3">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $typeClasses[$user->user_type] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $typeNames[$user->user_type] ?? $user->user_type }}
                    </span>
                    @if($user->phone)
                    <div class="text-sm text-slate-600 mt-1">{{ $user->phone }}</div>
                    @endif
                    <div class="text-xs text-slate-500 mt-1">انضم {{ $user->created_at->format('Y-m-d') }}</div>
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    @if($user->user_type !== 'admin')
                    <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')"
                            class="px-4 py-2 {{ $user->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg text-sm font-semibold transition-colors">
                        {{ $user->status === 'active' ? 'تعليق' : 'تفعيل' }}
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد مستخدمين</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على مستخدمين يطابقون معايير البحث</p>
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
                                المستخدم
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                النوع
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                معلومات الاتصال
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الحالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                تاريخ الانضمام
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <!-- User Avatar -->
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center ml-4
                                        {{ $user->user_type === 'admin' ? 'bg-gradient-to-br from-purple-500 to-pink-500' :
                                           ($user->user_type === 'logistics' ? 'bg-gradient-to-br from-blue-500 to-cyan-500' :
                                           ($user->user_type === 'service_company' ? 'bg-gradient-to-br from-green-500 to-emerald-500' : 'bg-gradient-to-br from-gray-500 to-slate-500')) }}">
                                        <i class="text-white text-sm
                                            {{ $user->user_type === 'admin' ? 'fas fa-crown' :
                                               ($user->user_type === 'logistics' ? 'fas fa-truck' :
                                               ($user->user_type === 'service_company' ? 'fas fa-building' : 'fas fa-user')) }}">
                                        </i>
                                    </div>

                                    <div>
                                        <div class="text-lg font-bold text-slate-900">{{ $user->name }}</div>
                                        @if($user->company_name)
                                        <div class="text-sm text-slate-600">{{ $user->company_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $typeClasses[$user->user_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $typeNames[$user->user_type] ?? $user->user_type }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $user->email }}</div>
                                @if($user->phone)
                                <div class="text-sm text-slate-500">{{ $user->phone }}</div>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$user->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$user->status] ?? $user->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $user->created_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-slate-500">{{ $user->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    @if($user->user_type !== 'admin')
                                    <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')"
                                            class="w-8 h-8 {{ $user->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas {{ $user->status === 'active' ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                                    </button>

                                    <button onclick="resetPassword({{ $user->id }})"
                                            class="w-8 h-8 bg-orange-500 hover:bg-orange-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-key text-xs"></i>
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
                                        <i class="fas fa-users text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد مستخدمين</h3>
                                    <p class="text-slate-500">لم يتم العثور على مستخدمين يطابقون معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $users->firstItem() }} إلى {{ $users->lastItem() }} من أصل {{ $users->total() }} مستخدم
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($users->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($users->getUrlRange(1, min($users->lastPage(), 5)) as $page => $url)
                    @if ($page == $users->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
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
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-edit text-orange-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">تحديث حالة المستخدم</h3>
                <p class="text-slate-600 text-sm lg:text-base">هل أنت متأكد من تغيير حالة هذا المستخدم؟</p>
            </div>

            <form id="status-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-orange-600 text-white rounded-xl font-semibold hover:bg-orange-700 transition-colors">
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
    function toggleStatus(userId, currentStatus) {
        document.getElementById('status-modal').classList.remove('hidden');
        const newStatus = currentStatus === 'active' ? 'suspended' : 'active';
        document.getElementById('status-form').action = `/admin/users/${userId}/status`;

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

    // Reset password function
    function resetPassword(userId) {
        if (confirm('هل أنت متأكد من إعادة تعيين كلمة المرور؟')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}/reset-password`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="POST">
            `;
            document.body.appendChild(form);
            form.submit();
        }
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
</script>
@endpush
@endsection
