@extends('layouts.admin')

@section('title', 'إضافة منتج جديد')
@section('page-title', 'إضافة منتج جديد')
@section('page-description', 'إضافة منتج جديد لمتجر أجهزة التتبع')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">إضافة منتج جديد</h1>
                <p class="text-slate-600">املأ المعلومات أدناه لإضافة منتج جديد للمتجر</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center">
                <i class="fas fa-plus text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Product Form -->
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">المعلومات الأساسية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اسم المنتج <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم المنتج">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم المنتج (SKU)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('sku') border-red-500 @enderror"
                           placeholder="مثال: GPS-TRACK-001 (سيتم إنشاؤه تلقائياً إذا تُرك فارغاً)">
                    <p class="mt-1 text-xs text-slate-500">رقم المنتج الفريد - سيتم إنشاؤه تلقائياً إذا تُرك فارغاً</p>
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">وصف المنتج</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('description') border-red-500 @enderror"
                          placeholder="وصف مفصل للمنتج وميزاته">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">التصنيف</label>
                    <select name="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('category_id') border-red-500 @enderror">
                        <option value="">اختر التصنيف</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="1">أجهزة تتبع GPS</option>
                            <option value="2">أجهزة استشعار</option>
                            <option value="3">ملحقات</option>
                        @endif
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">العلامة التجارية</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('brand') border-red-500 @enderror"
                           placeholder="مثال: Garmin, TomTom">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات التسعير</h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">السعر <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('price') border-red-500 @enderror"
                               placeholder="0.00">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <span class="text-slate-500 font-semibold">ريال</span>
                        </div>
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">السعر الأصلي</label>
                    <div class="relative">
                        <input type="number" name="original_price" value="{{ old('original_price') }}" step="0.01" min="0"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('original_price') border-red-500 @enderror"
                               placeholder="0.00">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <span class="text-slate-500 font-semibold">ريال</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">السعر قبل الخصم (اختياري)</p>
                    @error('original_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">كمية المخزون <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('stock_quantity') border-red-500 @enderror"
                           placeholder="0">
                    @error('stock_quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحد الأدنى للمخزون</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock') }}" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('min_stock') border-red-500 @enderror"
                           placeholder="5">
                    <p class="mt-1 text-xs text-slate-500">سيتم التنبيه عند الوصول لهذا الحد</p>
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوزن (جرام)</label>
                    <input type="number" name="weight" value="{{ old('weight') }}" step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('weight') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Product Images -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">صور المنتج</h3>

            <div class="space-y-6">
                <!-- Image Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors"
                     id="image-upload-area"
                     ondrop="dropHandler(event)"
                     ondragover="dragOverHandler(event)">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <h4 class="text-lg font-semibold text-slate-700 mb-2">اسحب الصور هنا أو اضغط للرفع</h4>
                        <p class="text-sm text-slate-500 mb-4">يمكنك رفع حتى 5 صور (JPG, PNG, WebP)</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="images-input">
                        <button type="button" onclick="document.getElementById('images-input').click()"
                                class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                            <i class="fas fa-upload mr-2"></i>
                            اختر الصور
                        </button>
                    </div>
                </div>

                <!-- Image Preview -->
                <div id="image-preview" class="grid grid-cols-2 lg:grid-cols-5 gap-4 hidden">
                    <!-- Preview images will be added here -->
                </div>
            </div>
        </div>

        <!-- Product Specifications -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">المواصفات الفنية</h3>

            <div id="specifications-container" class="space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 specification-row">
                    <input type="text" name="specifications[0][key]"
                           class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="اسم المواصفة (مثال: نطاق الإرسال)">
                    <div class="flex">
                        <input type="text" name="specifications[0][value]"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="القيمة (مثال: 10 كيلومتر)">
                        <button type="button" onclick="removeSpecification(this)"
                                class="px-4 py-3 bg-red-500 text-white rounded-l-xl hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addSpecification()"
                    class="mt-4 px-6 py-3 bg-gradient-secondary text-white rounded-xl font-semibold hover-lift transition-all">
                <i class="fas fa-plus mr-2"></i>
                إضافة مواصفة
            </button>
        </div>

        <!-- Product Status -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">حالة المنتج</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                    <select name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>متاح</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">خيارات إضافية</label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">منتج مميز</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="allow_backorder" value="1" {{ old('allow_backorder') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">السماح بالطلب المسبق</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 lg:space-x-reverse">
                <button type="submit" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-save mr-2"></i>
                    حفظ المنتج
                </button>

                <button type="button" onclick="saveDraft()" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-warning text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-edit mr-2"></i>
                    حفظ كمسودة
                </button>

                <a href="{{ route('admin.products.index') }}" class="flex-1 lg:flex-none px-8 py-4 bg-gray-200 text-slate-700 rounded-xl font-bold text-lg text-center hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let specificationCount = 1;

    // Image handling
    document.getElementById('images-input').addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    function dragOverHandler(ev) {
        ev.preventDefault();
    }

    function dropHandler(ev) {
        ev.preventDefault();
        const files = ev.dataTransfer.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        preview.classList.remove('hidden');

        Array.from(files).slice(0, 5).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl border-2 border-gray-200">
                        <button type="button" onclick="removeImage(this)"
                                class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                        <div class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                            ${index + 1}
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function removeImage(button) {
        button.parentElement.remove();
        const preview = document.getElementById('image-preview');
        if (preview.children.length === 0) {
            preview.classList.add('hidden');
        }
    }

    // Specifications handling
    function addSpecification() {
        const container = document.getElementById('specifications-container');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 lg:grid-cols-2 gap-4 specification-row';
        div.innerHTML = `
            <input type="text" name="specifications[${specificationCount}][key]"
                   class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                   placeholder="اسم المواصفة">
            <div class="flex">
                <input type="text" name="specifications[${specificationCount}][value]"
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                       placeholder="القيمة">
                <button type="button" onclick="removeSpecification(this)"
                        class="px-4 py-3 bg-red-500 text-white rounded-l-xl hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
        specificationCount++;
    }

    function removeSpecification(button) {
        button.closest('.specification-row').remove();
    }

    // Save as draft
    function saveDraft() {
        const form = document.querySelector('form');
        const draftInput = document.createElement('input');
        draftInput.type = 'hidden';
        draftInput.name = 'status';
        draftInput.value = 'draft';
        form.appendChild(draftInput);
        form.submit();
    }

    // Auto-generate SKU
    document.querySelector('input[name="name"]').addEventListener('input', function(e) {
        const name = e.target.value;
        const sku = name.toUpperCase()
                       .replace(/[^A-Z0-9\s]/g, '')
                       .replace(/\s+/g, '-')
                       .substring(0, 20);

        const skuInput = document.querySelector('input[name="sku"]');
        if (!skuInput.value) {
            skuInput.value = sku + '-' + Math.random().toString(36).substr(2, 3).toUpperCase();
        }
    });

    // Calculate discount percentage
    const priceInput = document.querySelector('input[name="price"]');
    const originalPriceInput = document.querySelector('input[name="original_price"]');

    function updateDiscount() {
        const price = parseFloat(priceInput.value) || 0;
        const originalPrice = parseFloat(originalPriceInput.value) || 0;

        if (originalPrice > 0 && price > 0 && originalPrice > price) {
            const discount = ((originalPrice - price) / originalPrice * 100).toFixed(1);
            console.log(`خصم: ${discount}%`);
        }
    }

    priceInput.addEventListener('input', updateDiscount);
    originalPriceInput.addEventListener('input', updateDiscount);
</script>
@endpush
@endsection
