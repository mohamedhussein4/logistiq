@extends('layouts.admin')

@section('title', 'سجلات النظام')
@section('page-title', 'سجلات النظام')
@section('page-description', 'عرض ومراقبة سجلات النظام والأنشطة')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ count($logs) }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي السجلات</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ collect($logs)->where('level', 'error')->count() }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">أخطاء</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ collect($logs)->where('level', 'warning')->count() }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">تحذيرات</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ collect($logs)->where('level', 'info')->count() }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">معلومات</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <form method="GET" id="filters-form" class="space-y-4 lg:space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البحث</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="ابحث في الرسائل..."
                               class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700 placeholder-slate-400">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Level Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">مستوى السجل</label>
                    <select name="level" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع المستويات</option>
                        <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>خطأ</option>
                        <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>تحذير</option>
                        <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>معلومات</option>
                        <option value="debug" {{ request('level') == 'debug' ? 'selected' : '' }}>تصحيح</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <button type="button" onclick="clearLogs()" class="px-4 py-3 lg:py-4 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-all">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">سجلات النظام</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض آخر {{ count($logs) }} سجل</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button onclick="refreshLogs()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-refresh mr-2"></i>
                    <span class="hidden lg:inline">تحديث</span>
                    <span class="lg:hidden">تحديث</span>
                </button>
                <button onclick="downloadLogs()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تحميل السجلات</span>
                    <span class="lg:hidden">تحميل</span>
                </button>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @forelse($logs as $log)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="text-sm text-slate-600 mb-1">{{ $log['timestamp']->format('Y-m-d H:i:s') }}</div>
                        <div class="text-sm text-slate-900">{{ $log['message'] }}</div>
                    </div>
                    @php
                        $levelClasses = [
                            'error' => 'bg-red-100 text-red-800',
                            'warning' => 'bg-yellow-100 text-yellow-800',
                            'info' => 'bg-blue-100 text-blue-800',
                            'debug' => 'bg-gray-100 text-gray-800'
                        ];
                        $levelNames = [
                            'error' => 'خطأ',
                            'warning' => 'تحذير',
                            'info' => 'معلومات',
                            'debug' => 'تصحيح'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $levelClasses[$log['level']] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $levelNames[$log['level']] ?? $log['level'] }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-list-alt text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد سجلات</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على سجلات تطابق معايير البحث</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200">
            <div class="table-responsive max-h-96 overflow-y-auto">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الوقت
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المستوى
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الرسالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $log['timestamp']->format('Y-m-d') }}</div>
                                <div class="text-sm text-slate-500">{{ $log['timestamp']->format('H:i:s') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $levelClasses[$log['level']] ?? 'bg-gray-100 text-gray-800' }}">
                                    @if($log['level'] === 'error')
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                    @elseif($log['level'] === 'warning')
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                    @elseif($log['level'] === 'info')
                                        <i class="fas fa-info-circle mr-1"></i>
                                    @else
                                        <i class="fas fa-bug mr-1"></i>
                                    @endif
                                    {{ $levelNames[$log['level']] ?? $log['level'] }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900 max-w-2xl" title="{{ $log['message'] }}">
                                    {{ Str::limit($log['message'], 100) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="showLogDetails({{ $index }})"
                                        class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-list-alt text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد سجلات</h3>
                                    <p class="text-slate-500">لم يتم العثور على سجلات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div id="log-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-4xl w-full shadow-2xl transform transition-all max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800">تفاصيل السجل</h3>
                <button onclick="closeLogModal()" class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>

            <div id="log-details" class="space-y-4">
                <!-- Log details will be populated here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Log data for JavaScript access
    const logsData = @json($logs);

    // Auto-submit search
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filters-form').submit();
        }, 500);
    });

    // Show log details
    function showLogDetails(index) {
        const log = logsData[index];
        const modal = document.getElementById('log-modal');
        const details = document.getElementById('log-details');

        const levelClasses = {
            'error': 'bg-red-100 text-red-800',
            'warning': 'bg-yellow-100 text-yellow-800',
            'info': 'bg-blue-100 text-blue-800',
            'debug': 'bg-gray-100 text-gray-800'
        };

        const levelNames = {
            'error': 'خطأ',
            'warning': 'تحذير',
            'info': 'معلومات',
            'debug': 'تصحيح'
        };

        details.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوقت</label>
                    <div class="p-3 bg-gray-50 rounded-lg text-slate-900">${log.timestamp}</div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">المستوى</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold ${levelClasses[log.level] || 'bg-gray-100 text-gray-800'}">
                            ${levelNames[log.level] || log.level}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">الرسالة</label>
                <div class="p-4 bg-gray-50 rounded-lg text-slate-900 whitespace-pre-wrap">${log.message}</div>
            </div>
        `;

        modal.classList.remove('hidden');
    }

    // Close log modal
    function closeLogModal() {
        document.getElementById('log-modal').classList.add('hidden');
    }

    // Refresh logs
    function refreshLogs() {
        location.reload();
    }

    // Download logs
    function downloadLogs() {
        window.open('/admin/settings/logs/download', '_blank');
    }

    // Clear logs
    function clearLogs() {
        if (confirm('هل أنت متأكد من مسح جميع السجلات؟ هذا الإجراء لا يمكن التراجع عنه.')) {
            fetch('/admin/settings/logs/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                alert('تم مسح السجلات بنجاح');
                location.reload();
            })
            .catch(error => {
                alert('حدث خطأ أثناء مسح السجلات');
            });
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'log-modal') {
            closeLogModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogModal();
        }
    });

    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (!document.getElementById('log-modal').classList.contains('hidden')) {
            return; // Don't refresh if modal is open
        }

        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Update only the logs table content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('.table-responsive');
            if (newContent) {
                document.querySelector('.table-responsive').innerHTML = newContent.innerHTML;
            }
        })
        .catch(error => {
            console.log('Failed to auto-refresh logs');
        });
    }, 30000); // 30 seconds
</script>
@endpush
@endsection
