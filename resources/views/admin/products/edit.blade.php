@extends('layouts.admin')

@section('title', 'تعديل المنتج')
@section('page-title', 'تعديل المنتج')
@section('page-description', 'تعديل معلومات ومواصفات المنتج')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">تعديل المنتج: {{ $product->name ?? 'GPS Tracker Pro' }}</h1>
                <p class="text-slate-600">تعديل معلومات ومواصفات المنتج</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Product Form -->
    <form method="POST" action="{{ route('admin.products.update', $product->id ?? 1) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">المعلومات الأساسية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اسم المنتج <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? 'GPS Tracker Pro') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم المنتج">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم المنتج (SKU)</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('sku') border-red-500 @enderror"
                           placeholder="مثال: GPS-TRACK-001">
                    <p class="mt-1 text-xs text-slate-500">رقم المنتج الفريد</p>
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">وصف المنتج</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('description') border-red-500 @enderror"
                          placeholder="وصف مفصل للمنتج وميزاته">{{ old('description', $product->description ?? 'جهاز تتبع GPS متقدم مع ميزات احترافية للشركات اللوجستية') }}</textarea>
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
                        <option value="1" {{ old('category_id', $product->category_id ?? 1) == 1 ? 'selected' : '' }}>أجهزة تتبع GPS</option>
                        <option value="2" {{ old('category_id', $product->category_id ?? 1) == 2 ? 'selected' : '' }}>أجهزة استشعار</option>
                        <option value="3" {{ old('category_id', $product->category_id ?? 1) == 3 ? 'selected' : '' }}>ملحقات</option>
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">العلامة التجارية</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand ?? 'TrackTech') }}"
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
                        <input type="number" name="price" value="{{ old('price', $product->price ?? 599.99) }}" step="0.01" min="0" required
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
                        <input type="number" name="original_price" value="{{ old('original_price', $product->original_price ?? 699.99) }}" step="0.01" min="0"
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
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 45) }}" min="0" required
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
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 5) }}" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('min_stock') border-red-500 @enderror"
                           placeholder="5">
                    <p class="mt-1 text-xs text-slate-500">سيتم التنبيه عند الوصول لهذا الحد</p>
                    @error('min_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوزن (جرام)</label>
                    <input type="number" name="weight" value="{{ old('weight', $product->weight ?? 250) }}" step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('weight') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Current Images -->
        @php
            // تحويل images إلى array إذا كانت string
            $productImages = $product->images;
            if (is_string($productImages)) {
                $productImages = json_decode($productImages, true) ?? [];
            } elseif (is_null($productImages)) {
                $productImages = [];
            }
            $currentImages = !empty($productImages) ? $productImages : ['products/gps-tracker-1.jpg', 'products/gps-tracker-2.jpg'];
        @endphp

        @if(!empty($currentImages) && is_array($currentImages))
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold gradient-text">الصور الحالية</h3>
                <span class="text-sm text-slate-600 image-counter">{{ count($currentImages) }} صور</span>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4" id="current-images">
                @foreach($currentImages as $index => $image)
                <div class="relative group current-image">
                    <img src="{{ asset('/' . $image) }}" class="w-full h-32 object-cover rounded-xl border-2 border-gray-200">
                    <button type="button" onclick="removeCurrentImage(this, '{{ $image }}')"
                            class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-75 text-white text-xs px-2 py-2 rounded">
                        {{ $index + 1 }}
                    </div>
                    <!-- لا نحتاج existing_images لأننا نرسل remove_images بدلاً منها -->
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- New Images Upload -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">إضافة صور جديدة</h3>

            <div class="space-y-6">
                <!-- Image Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors"
                     id="image-upload-area"
                     ondrop="dropHandler(event)"
                     ondragover="dragOverHandler(event)">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <h4 class="text-lg font-semibold text-slate-700 mb-2">اسحب الصور هنا أو اضغط للرفع</h4>
                        <p class="text-sm text-slate-500 mb-4">يمكنك رفع صور إضافية (JPG, PNG, WebP)</p>
                        <input type="file" name="new_images[]" multiple accept="image/*" class="hidden" id="images-input">
                        <button type="button" onclick="document.getElementById('images-input').click()"
                                class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                            <i class="fas fa-upload mr-2"></i>
                            اختر الصور
                        </button>
                    </div>
                </div>

                <!-- New Image Preview -->
                <div id="image-preview" class="grid grid-cols-2 lg:grid-cols-5 gap-4 hidden">
                    <!-- Preview images will be added here -->
                </div>
            </div>
        </div>

        <!-- Product Specifications -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">المواصفات الفنية</h3>

            <div id="specifications-container" class="space-y-4">
                @php
                    // تحويل specifications إلى array إذا كانت string
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

                @foreach($specifications as $index => $spec)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 specification-row">
                    <input type="text" name="specifications[{{ $index }}][key]" value="{{ $spec['key'] }}"
                           class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="اسم المواصفة">
                    <div class="flex">
                        <input type="text" name="specifications[{{ $index }}][value]" value="{{ $spec['value'] }}"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="القيمة">
                        <button type="button" onclick="removeSpecification(this)"
                                class="px-4 py-3 bg-red-500 text-white rounded-l-xl hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
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
                        <option value="active" {{ old('status', $product->status ?? 'active') == 'active' ? 'selected' : '' }}>متاح</option>
                        <option value="inactive" {{ old('status', $product->status ?? 'active') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                        <option value="draft" {{ old('status', $product->status ?? 'active') == 'draft' ? 'selected' : '' }}>مسودة</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">خيارات إضافية</label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">منتج مميز</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="allow_backorder" value="1" {{ old('allow_backorder', $product->allow_backorder ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-slate-700">السماح بالطلب المسبق</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Analytics -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">إحصائيات المنتج</h3>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center bg-blue-50 rounded-xl p-4">
                    <div class="text-2xl font-black text-blue-600">{{ $product->views ?? 1240 }}</div>
                    <div class="text-sm text-blue-700 font-semibold">مشاهدة</div>
                </div>

                <div class="text-center bg-green-50 rounded-xl p-4">
                    <div class="text-2xl font-black text-green-600">{{ $product->sales ?? 89 }}</div>
                    <div class="text-sm text-green-700 font-semibold">عملية بيع</div>
                </div>

                <div class="text-center bg-purple-50 rounded-xl p-4">
                    <div class="text-2xl font-black text-purple-600">{{ number_format($product->revenue ?? 53411) }}</div>
                    <div class="text-sm text-purple-700 font-semibold">إيرادات (ريال)</div>
                </div>

                <div class="text-center bg-yellow-50 rounded-xl p-4">
                    <div class="text-2xl font-black text-yellow-600">{{ $product->rating ?? 4.8 }}/5</div>
                    <div class="text-sm text-yellow-700 font-semibold">التقييم</div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 lg:space-x-reverse">
                <button type="submit" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-save mr-2"></i>
                    حفظ التغييرات
                </button>

                <button type="button" onclick="previewProduct()" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-success text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-eye mr-2"></i>
                    معاينة المنتج
                </button>

                <a href="{{ route('admin.products.index') }}" class="flex-1 lg:flex-none px-8 py-4 bg-gray-200 text-slate-700 rounded-xl font-bold text-lg text-center hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let specificationCount = {{ count($specifications ?? []) }};

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
                            جديد ${index + 1}
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

    function removeCurrentImage(button, imagePath) {
        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
            // إضافة الصورة إلى قائمة الصور المحذوفة
            const form = button.closest('form');
            const newInput = document.createElement('input');
            newInput.type = 'hidden';
            newInput.name = 'remove_images[]';
            newInput.value = imagePath;
            form.appendChild(newInput);

            // Debug: عرض في console
            console.log('Adding to remove_images:', imagePath);
            console.log('Form inputs:', form.querySelectorAll('input[name="remove_images[]"]'));

            // حذف العنصر من DOM
            button.closest('.current-image').remove();

            // تحديث عداد الصور
            updateImageCounter();

            // إظهار رسالة تأكيد
            showMessage('تم حذف الصورة بنجاح', 'success');
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

    // Preview product
    function previewProduct() {
        window.open('/products/{{ $product->id ?? 1 }}', '_blank');
    }

    // Form submission debugging
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[method="POST"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Debug: عرض جميع البيانات قبل الإرسال
                const formData = new FormData(form);
                console.log('=== FORM SUBMISSION DEBUG ===');
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);

                // عرض remove_images
                const removeImages = form.querySelectorAll('input[name="remove_images[]"]');
                console.log('Remove images count:', removeImages.length);
                removeImages.forEach((input, index) => {
                    console.log(`Remove image ${index}:`, input.value);
                });

                // عرض existing_images
                const existingImages = form.querySelectorAll('input[name="existing_images[]"]');
                console.log('Existing images count:', existingImages.length);
                existingImages.forEach((input, index) => {
                    console.log(`Existing image ${index}:`, input.value);
                });

                // عرض new_images
                const newImages = form.querySelectorAll('input[name="new_images[]"]');
                console.log('New images count:', newImages.length);

                // عرض جميع البيانات
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }

                console.log('=== END DEBUG ===');

                // إذا لم تكن هناك صور محذوفة، إظهار تحذير
                if (removeImages.length === 0) {
                    console.warn('No images marked for removal!');
                }
            });
        }
    });

    // Show message function
    function showMessage(message, type = 'info') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-semibold shadow-lg transition-all duration-300 transform translate-x-full`;

        // تحديد اللون حسب نوع الرسالة
        switch(type) {
            case 'success':
                messageDiv.classList.add('bg-green-500');
                break;
            case 'error':
                messageDiv.classList.add('bg-red-500');
                break;
            case 'warning':
                messageDiv.classList.add('bg-yellow-500');
                break;
            default:
                messageDiv.classList.add('bg-blue-500');
        }

        messageDiv.textContent = message;
        document.body.appendChild(messageDiv);

        // إظهار الرسالة
        setTimeout(() => {
            messageDiv.classList.remove('translate-x-full');
        }, 100);

        // إخفاء الرسالة بعد 3 ثواني
        setTimeout(() => {
            messageDiv.classList.add('translate-x-full');
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 300);
        }, 3000);
    }

    // تحديث عداد الصور
    function updateImageCounter() {
        const currentImages = document.querySelectorAll('.current-image');
        const counter = document.querySelector('.image-counter');
        if (counter) {
            counter.textContent = `${currentImages.length} صور`;
        }
    }

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
