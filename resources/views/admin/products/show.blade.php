@extends('layouts.admin')

@section('title', 'عرض المنتج - ' . $product->name)

@section('content')
<div class="container mx-auto px-4 py-6 relative z-10">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 relative z-20">
        <div class="flex items-center mb-4 lg:mb-0">
            <a href="{{ route('admin.products.index') }}"
               class="ml-4 p-2 text-slate-600 hover:text-slate-900 transition-colors relative z-20">
                <i class="fas fa-arrow-right text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl lg:text-3xl font-black text-slate-900">{{ $product->name }}</h1>
                <p class="text-slate-600">عرض تفاصيل المنتج والإحصائيات</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 relative z-10">
            <a href="{{ route('admin.products.edit', $product) }}"
               class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-warning text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all relative z-20">
                <i class="fas fa-edit ml-2"></i>
                تعديل المنتج
            </a>

            <form action="{{ route('admin.products.update_status', $product) }}" method="POST" class="inline relative z-20">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $product->status === 'active' ? 'inactive' : 'active' }}">
                <button type="submit"
                        class="px-4 lg:px-6 py-2 lg:py-3 {{ $product->status === 'active' ? 'bg-gradient-danger' : 'bg-gradient-success' }} text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all relative z-10">
                    <i class="fas fa-{{ $product->status === 'active' ? 'eye-slash' : 'eye' }} ml-2"></i>
                    {{ $product->status === 'active' ? 'إخفاء' : 'تفعيل' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 relative z-10">
        <!-- المحتوى الرئيسي -->
        <div class="xl:col-span-2 space-y-6 relative z-10">
            <!-- معلومات المنتج الأساسية -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 relative z-10">
                <h2 class="text-xl font-bold gradient-text mb-6">معلومات المنتج</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">اسم المنتج</label>
                            <p class="text-slate-900 font-semibold">{{ $product->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">رمز المنتج (SKU)</label>
                            <p class="text-slate-600 font-mono">{{ $product->sku ?? 'غير محدد' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">التصنيف</label>
                            <p class="text-slate-600">{{ $product->category->name ?? 'غير محدد' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">الحالة</label>
                            @php
                                $statusClasses = [
                                    'active' => 'bg-green-100 text-green-800 border-green-200',
                                    'inactive' => 'bg-red-100 text-red-800 border-red-200',
                                    'draft' => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                ];
                                $statusNames = [
                                    'active' => 'متاح',
                                    'inactive' => 'غير متاح',
                                    'draft' => 'مسودة'
                                ];
                            @endphp
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full border {{ $statusClasses[$product->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ $statusNames[$product->status] ?? $product->status }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">السعر</label>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($product->price, 2) }} ر.س</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">المخزون</label>
                            <p class="text-slate-900 font-semibold">
                                {{ $product->stock_quantity ?? 0 }} قطعة
                                @if(($product->stock_quantity ?? 0) <= 5)
                                    <span class="text-red-500 text-sm">(مخزون منخفض)</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">منتج مميز</label>
                            <p class="text-slate-600">
                                @if($product->is_featured)
                                    <span class="text-green-600 font-semibold">نعم</span>
                                @else
                                    <span class="text-slate-400">لا</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">تاريخ الإضافة</label>
                            <p class="text-slate-600">{{ $product->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- وصف المنتج -->
            @if($product->description)
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 relative z-10">
                <h3 class="text-xl font-bold gradient-text mb-4">وصف المنتج</h3>
                <div class="prose prose-slate max-w-none">
                    <p class="text-slate-700 leading-relaxed">{{ $product->description }}</p>
                </div>
            </div>
            @endif

            <!-- صور المنتج -->
            @php
                $productImages = $product->images;
                if (is_string($productImages)) {
                    $productImages = json_decode($productImages, true) ?? [];
                } elseif (is_null($productImages)) {
                    $productImages = [];
                }
                $images = !empty($productImages) ? $productImages : ['products/gps-tracker-1.jpg', 'products/gps-tracker-2.jpg'];
            @endphp

            @if(!empty($images) && is_array($images))
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 relative z-10">
                <h3 class="text-xl font-bold gradient-text mb-4">صور المنتج</h3>
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($images as $image)
                    <div class="group cursor-pointer relative z-10" onclick="openImageModal('{{ asset('/' . $image) }}')">
                        <img src="{{ asset('/' . $image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-32 lg:h-40 object-cover rounded-xl border-2 border-gray-200 group-hover:border-blue-500 transition-all group-hover:shadow-lg">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- المواصفات الفنية -->
            @php
                $productSpecs = $product->specifications;
                if (is_string($productSpecs)) {
                    $productSpecs = json_decode($productSpecs, true) ?? [];
                } elseif (is_null($productSpecs)) {
                    $productSpecs = [];
                }
                $specifications = !empty($productSpecs) ? $productSpecs : [
                    ['key' => 'نطاق الإرسال', 'value' => '10 كيلومتر'],
                    ['key' => 'عمر البطارية', 'value' => '30 يوم'],
                    ['key' => 'مقاومة الماء', 'value' => 'IP67']
                ];
            @endphp

            @if(!empty($specifications) && is_array($specifications))
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-6 lg:p-8 border border-white/20 relative z-10">
                <h3 class="text-xl font-bold gradient-text mb-4">المواصفات الفنية</h3>
                <div class="space-y-3">
                    @foreach($specifications as $spec)
                        @if(isset($spec['key']) && isset($spec['value']))
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                            <span class="text-slate-700 font-medium">{{ $spec['key'] }}</span>
                            <span class="text-slate-900 font-semibold">{{ $spec['value'] }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- الشريط الجانبي - الإحصائيات -->
        <div class="space-y-6 relative z-10">
            <!-- إحصائيات سريعة -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-6 border border-white/20 relative z-10">
                <h3 class="text-lg font-bold gradient-text mb-4">إحصائيات المنتج</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">إجمالي الطلبات</span>
                        <span class="text-xl font-bold text-blue-600">{{ $stats['total_orders'] ?? 0 }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">الكمية المباعة</span>
                        <span class="text-xl font-bold text-green-600">{{ $stats['total_quantity_sold'] ?? 0 }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">إجمالي الإيرادات</span>
                        <span class="text-xl font-bold text-purple-600">{{ number_format($stats['total_revenue'] ?? 0, 2) }} ر.س</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">التقييم</span>
                        <div class="flex items-center">
                            <span class="text-lg font-bold text-yellow-500 ml-1">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</span>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">عدد المراجعات</span>
                        <span class="text-lg font-bold text-slate-700">{{ $stats['reviews_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- إجراءات سريعة -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-6 border border-white/20 relative z-10">
                <h3 class="text-lg font-bold gradient-text mb-4">إجراءات سريعة</h3>

                <div class="space-y-3">
                    <!-- تحديث المخزون -->
                    <form action="{{ route('admin.products.update_stock', $product) }}" method="POST" class="relative z-10">
                        @csrf
                        @method('PATCH')
                        <div class="flex gap-2">
                            <input type="number" name="stock_quantity" value="{{ $product->stock_quantity ?? 0 }}" min="0"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 relative z-10">
                                                    <button type="submit"
                                class="px-4 py-2 bg-gradient-primary text-white rounded-lg text-sm font-semibold hover-lift transition-all relative z-10">
                            <i class="fas fa-save"></i>
                        </button>
                        </div>
                        <label class="block text-xs text-slate-500 mt-1">تحديث المخزون</label>
                    </form>

                    <!-- روابط سريعة -->
                    <div class="pt-3 border-t border-gray-100">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="flex items-center justify-between p-3 text-slate-700 hover:bg-blue-50 rounded-lg transition-colors relative z-10">
                            <span class="text-sm font-medium">تعديل المنتج</span>
                            <i class="fas fa-edit text-blue-500"></i>
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center justify-between p-3 text-slate-700 hover:bg-green-50 rounded-lg transition-colors relative z-10">
                            <span class="text-sm font-medium">جميع المنتجات</span>
                            <i class="fas fa-list text-green-500"></i>
                        </a>

                        <button onclick="if(confirm('هل أنت متأكد من حذف هذا المنتج؟')) { document.getElementById('delete-form').submit(); }"
                                class="w-full flex items-center justify-between p-3 text-red-700 hover:bg-red-50 rounded-lg transition-colors relative z-10">
                            <span class="text-sm font-medium">حذف المنتج</span>
                            <i class="fas fa-trash text-red-500"></i>
                        </button>

                        <form id="delete-form" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden relative z-10">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 z-[9999] hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modal-image" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

@endsection

@section('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modal-image').src = imageSrc;
    const modal = document.getElementById('image-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('image-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// التأكد من أن الـ modal مخفي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('image-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
});

// إغلاق المودال عند الضغط خارج الصورة
document.getElementById('image-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// إغلاق المودال بمفتاح Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection
