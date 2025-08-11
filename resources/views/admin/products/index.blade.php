@extends('layouts.admin')

@section('title', 'إدارة المنتجات')
@section('page-title', 'إدارة المنتجات')
@section('page-description', 'نظام إدارة مخزون وبيع أجهزة التتبع والمنتجات اللوجستية')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_products'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي المنتجات</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['active_products'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">متاحة</div>
            </div>
            <div class="text-center bg-red-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-red-600">{{ $stats['out_of_stock'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-red-700 font-semibold">نفدت من المخزون</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['low_stock'] ?? 0 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">مخزون منخفض</div>
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
                               placeholder="ابحث بالاسم، الوصف، رقم المنتج..."
                               class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700 placeholder-slate-400">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">التصنيف</label>
                    <select name="category_id" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع التصنيفات</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-3 lg:px-4 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-slate-700" onchange="document.getElementById('filters-form').submit()">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متاح</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>نفد المخزون</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2 space-x-reverse">
                    <button type="submit" class="flex-1 lg:px-6 px-4 py-3 lg:py-4 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                        <i class="fas fa-search mr-2"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-3 lg:py-4 bg-white text-slate-700 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all">
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
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة المنتجات</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $products->count() }} من أصل {{ $products->total() }} منتج</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.products.categories.index') }}" class="px-3 lg:px-6 py-2 lg:py-3 bg-gradient-warning text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-tags mr-2"></i>
                    <span class="hidden lg:inline">إدارة التصنيفات</span>
                    <span class="lg:hidden">تصنيفات</span>
                </a>
                <button class="px-3 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-download mr-2"></i>
                    <span class="hidden lg:inline">تصدير Excel</span>
                    <span class="lg:hidden">تصدير</span>
                </button>
                <a href="{{ route('admin.products.create') }}" class="px-3 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">منتج جديد</span>
                    <span class="lg:hidden">جديد</span>
                </a>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden space-y-4">
            @forelse($products as $product)
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="flex items-start space-x-3 space-x-reverse mb-3">
                    @php
                        $images = json_decode($product->images, true) ?? [];
                        $firstImage = $images[0] ?? null;
                    @endphp

                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}"
                             class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow-lg flex-shrink-0">
                    @else
                        <div class="w-16 h-16 bg-gradient-primary rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div class="text-lg font-bold text-slate-900 truncate">{{ $product->name }}</div>
                        <div class="text-sm text-slate-600">{{ $product->sku }}</div>
                        <div class="text-lg font-black text-slate-900">{{ number_format($product->price) }} ريال</div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        @if($product->stock_quantity > 10)
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-green-700 font-bold text-sm">{{ $product->stock_quantity }} قطعة</span>
                        @elseif($product->stock_quantity > 0)
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-yellow-700 font-bold text-sm">{{ $product->stock_quantity }} قطعة</span>
                        @else
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-red-700 font-bold text-sm">نفد</span>
                        @endif
                    </div>

                    @php
                        $statusClasses = [
                            'active' => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'out_of_stock' => 'bg-red-100 text-red-800'
                        ];
                        $statusNames = [
                            'active' => 'متاح',
                            'inactive' => 'غير متاح',
                            'out_of_stock' => 'نفد المخزون'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $statusClasses[$product->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusNames[$product->status] ?? $product->status }}
                    </span>
                </div>

                <div class="flex space-x-2 space-x-reverse">
                    <a href="{{ route('admin.products.show', $product) }}"
                       class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg text-center text-sm font-semibold hover:bg-blue-600 transition-colors">
                        عرض
                    </a>

                    <button onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors">
                        مخزون
                    </button>

                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-semibold hover:bg-orange-600 transition-colors">
                        تعديل
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد منتجات</h3>
                <p class="text-slate-500 text-sm">لم يتم العثور على منتجات تطابق معايير البحث</p>
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
                                المنتج
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                التصنيف
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                السعر
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المخزون
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الحالة
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                المبيعات
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-slate-700 border-b border-gray-200">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}"
                                             class="w-16 h-16 rounded-xl object-cover mr-4 border-2 border-white shadow-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gradient-primary rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-box text-white text-xl"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="text-lg font-bold text-slate-900">{{ $product->name }}</div>
                                        <div class="text-sm text-slate-600">{{ $product->sku }}</div>
                                        @if($product->description)
                                        <div class="text-sm text-slate-500 max-w-xs truncate" title="{{ $product->description }}">
                                            {{ $product->description }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->category)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-purple-100 text-purple-800">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400">غير محدد</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ number_format($product->price) }}</div>
                                @if($product->original_price && $product->original_price > $product->price)
                                    <div class="text-sm text-slate-500 line-through">{{ number_format($product->original_price) }}</div>
                                @endif
                                <div class="text-sm text-slate-500">ريال سعودي</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->stock_quantity > 10)
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                        <span class="text-green-700 font-bold">{{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity > 0)
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                        <span class="text-yellow-700 font-bold">{{ $product->stock_quantity }}</span>
                                    @else
                                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                        <span class="text-red-700 font-bold">نفد</span>
                                    @endif
                                </div>
                                <div class="text-sm text-slate-500">قطعة</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses[$product->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$product->status] ?? $product->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-black text-slate-900">{{ $product->orders_count ?? 0 }}</div>
                                <div class="text-sm text-slate-500">طلب</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="w-8 h-8 bg-orange-500 hover:bg-orange-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>

                                    <button onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})"
                                            class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas fa-boxes text-xs"></i>
                                    </button>

                                    <button onclick="toggleStatus({{ $product->id }}, '{{ $product->status }}')"
                                            class="w-8 h-8 {{ $product->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                        <i class="fas {{ $product->status === 'active' ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-box text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد منتجات</h3>
                                    <p class="text-slate-500">لم يتم العثور على منتجات تطابق معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-6 lg:mt-8 flex flex-col lg:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-600 font-semibold">
                عرض {{ $products->firstItem() }} إلى {{ $products->lastItem() }} من أصل {{ $products->total() }} منتج
            </div>

            <div class="flex items-center space-x-2 space-x-reverse">
                @if ($products->onFirstPage())
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">السابق</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">السابق</a>
                @endif

                @foreach ($products->getUrlRange(1, min($products->lastPage(), 5)) as $page => $url)
                    @if ($page == $products->currentPage())
                        <span class="px-3 lg:px-4 py-2 bg-gradient-primary text-white rounded-lg font-bold shadow-lg text-sm lg:text-base">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-3 lg:px-4 py-2 bg-white text-slate-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold border border-gray-200 text-sm lg:text-base">التالي</a>
                @else
                    <span class="px-3 lg:px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm lg:text-base">التالي</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stock-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-lg w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-boxes text-green-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">تحديث المخزون</h3>
                <p class="text-slate-600 text-sm lg:text-base">المخزون الحالي: <span id="current-stock" class="font-bold text-blue-600"></span> قطعة</p>
            </div>

            <form id="stock-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">العملية</label>
                    <select name="action" id="stock-action" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="set">تحديد المخزون</option>
                        <option value="add">إضافة للمخزون</option>
                        <option value="subtract">خصم من المخزون</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الكمية <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_quantity" min="0" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="أدخل الكمية">
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        تحديث المخزون
                    </button>
                    <button type="button" onclick="closeStockModal()"
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

    // Stock management
    function updateStock(productId, currentStock) {
        document.getElementById('stock-modal').classList.remove('hidden');
        document.getElementById('current-stock').textContent = currentStock;
        document.getElementById('stock-form').action = `/admin/products/${productId}/stock`;
    }

    function closeStockModal() {
        document.getElementById('stock-modal').classList.add('hidden');
    }

    // Status toggle
    function toggleStatus(productId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${productId}/status`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" value="${newStatus}">
        `;
        document.body.appendChild(form);
        form.submit();
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'stock-modal') {
            closeStockModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeStockModal();
        }
    });
</script>
@endpush
@endsection
