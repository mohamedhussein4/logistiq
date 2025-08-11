@extends('layouts.admin')

@section('title', 'إدارة التصنيفات')
@section('page-title', 'إدارة التصنيفات')
@section('page-description', 'إدارة تصنيفات المنتجات وتنظيم المتجر')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $stats['total_categories'] ?? 8 }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">إجمالي التصنيفات</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $stats['active_categories'] ?? 6 }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">نشطة</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $stats['products_count'] ?? 125 }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">إجمالي المنتجات</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $stats['subcategories'] ?? 15 }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">تصنيفات فرعية</div>
            </div>
        </div>
    </div>

    <!-- Categories Management -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">إدارة التصنيفات</h3>
                <p class="text-slate-600 text-sm lg:text-base">تنظيم وإدارة تصنيفات المنتجات</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button onclick="openCreateModal()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-primary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">تصنيف جديد</span>
                    <span class="lg:hidden">جديد</span>
                </button>
                <button onclick="sortCategories()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-secondary text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-sort mr-2"></i>
                    <span class="hidden lg:inline">ترتيب</span>
                    <span class="lg:hidden">ترتيب</span>
                </button>
            </div>
        </div>

        <!-- Categories Tree -->
        <div class="space-y-4" id="categories-tree">
            @php
                $categories = [
                    [
                        'id' => 1,
                        'name' => 'أجهزة تتبع GPS',
                        'description' => 'أجهزة تتبع متقدمة للمركبات والشحنات',
                        'products_count' => 45,
                        'status' => 'active',
                        'image' => 'categories/gps-devices.jpg',
                        'subcategories' => [
                            ['id' => 11, 'name' => 'أجهزة تتبع السيارات', 'products_count' => 25],
                            ['id' => 12, 'name' => 'أجهزة تتبع الشاحنات', 'products_count' => 20]
                        ]
                    ],
                    [
                        'id' => 2,
                        'name' => 'أجهزة استشعار',
                        'description' => 'أجهزة استشعار ذكية للمراقبة والتحكم',
                        'products_count' => 32,
                        'status' => 'active',
                        'image' => 'categories/sensors.jpg',
                        'subcategories' => [
                            ['id' => 21, 'name' => 'استشعار الحرارة', 'products_count' => 15],
                            ['id' => 22, 'name' => 'استشعار الحركة', 'products_count' => 17]
                        ]
                    ],
                    [
                        'id' => 3,
                        'name' => 'الملحقات',
                        'description' => 'ملحقات وقطع غيار متنوعة',
                        'products_count' => 28,
                        'status' => 'active',
                        'image' => 'categories/accessories.jpg',
                        'subcategories' => [
                            ['id' => 31, 'name' => 'كابلات وأسلاك', 'products_count' => 12],
                            ['id' => 32, 'name' => 'حاملات وقواعد', 'products_count' => 16]
                        ]
                    ],
                    [
                        'id' => 4,
                        'name' => 'البرمجيات',
                        'description' => 'حلول برمجية للإدارة والمراقبة',
                        'products_count' => 20,
                        'status' => 'inactive',
                        'image' => 'categories/software.jpg',
                        'subcategories' => []
                    ]
                ];
            @endphp

            @foreach($categories as $category)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm category-item" data-id="{{ $category['id'] }}">
                <!-- Main Category -->
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <!-- Drag Handle -->
                            <div class="drag-handle cursor-move mr-4 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-grip-vertical"></i>
                            </div>

                            <!-- Category Image -->
                            <div class="w-16 h-16 bg-gradient-primary rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-folder text-white text-xl"></i>
                            </div>

                            <!-- Category Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3 space-x-reverse mb-2">
                                    <h4 class="text-lg font-bold text-slate-900">{{ $category['name'] }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $category['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $category['status'] === 'active' ? 'نشط' : 'غير نشط' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        {{ $category['products_count'] }} منتج
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 truncate">{{ $category['description'] }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2 space-x-reverse ml-4">
                            <button onclick="editCategory({{ $category['id'] }})"
                                    class="w-8 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                <i class="fas fa-edit text-xs"></i>
                            </button>

                            <button onclick="toggleCategoryStatus({{ $category['id'] }}, '{{ $category['status'] }}')"
                                    class="w-8 h-8 {{ $category['status'] === 'active' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                <i class="fas {{ $category['status'] === 'active' ? 'fa-pause' : 'fa-play' }} text-xs"></i>
                            </button>

                            <button onclick="deleteCategory({{ $category['id'] }})"
                                    class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                <i class="fas fa-trash text-xs"></i>
                            </button>

                            @if(!empty($category['subcategories']))
                            <button onclick="toggleSubcategories({{ $category['id'] }})"
                                    class="w-8 h-8 bg-purple-500 hover:bg-purple-600 text-white rounded-lg flex items-center justify-center transition-all hover-lift">
                                <i class="fas fa-chevron-down text-xs subcategory-toggle" id="toggle-{{ $category['id'] }}"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subcategories -->
                @if(!empty($category['subcategories']))
                <div class="subcategories border-t border-gray-100 bg-gray-50 p-4 hidden" id="subcategories-{{ $category['id'] }}">
                    <div class="space-y-3">
                        @foreach($category['subcategories'] as $subcategory)
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-secondary rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-folder-open text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $subcategory['name'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $subcategory['products_count'] }} منتج</div>
                                </div>
                            </div>

                            <div class="flex space-x-2 space-x-reverse">
                                <button onclick="editCategory({{ $subcategory['id'] }})"
                                        class="w-6 h-6 bg-blue-500 hover:bg-blue-600 text-white rounded flex items-center justify-center transition-colors">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button onclick="deleteCategory({{ $subcategory['id'] }})"
                                        class="w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded flex items-center justify-center transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach

                        <button onclick="addSubcategory({{ $category['id'] }})"
                                class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition-all">
                            <i class="fas fa-plus text-gray-400 mr-2"></i>
                            <span class="text-gray-600 font-semibold">إضافة تصنيف فرعي</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div id="category-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-2xl w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder text-blue-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2" id="modal-title">إضافة تصنيف جديد</h3>
                <p class="text-slate-600 text-sm lg:text-base">املأ المعلومات أدناه لإضافة تصنيف جديد</p>
            </div>

            <form id="category-form" method="POST" enctype="multipart/form-data" class="space-y-4 lg:space-y-6">
                @csrf
                <div id="method-field"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم التصنيف <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="أدخل اسم التصنيف">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">التصنيف الأساسي</label>
                        <select name="parent_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">تصنيف رئيسي</option>
                            <option value="1">أجهزة تتبع GPS</option>
                            <option value="2">أجهزة استشعار</option>
                            <option value="3">الملحقات</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="وصف موجز للتصنيف"></textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ترتيب العرض</label>
                        <input type="number" name="sort_order" min="0" value="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحالة</label>
                        <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">صورة التصنيف</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <p class="mt-1 text-xs text-slate-500">اختياري - يفضل أن تكون الصورة بحجم 300x300 بكسل</p>
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        حفظ التصنيف
                    </button>
                    <button type="button" onclick="closeCategoryModal()"
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
    // Create new category
    function openCreateModal() {
        document.getElementById('category-modal').classList.remove('hidden');
        document.getElementById('modal-title').textContent = 'إضافة تصنيف جديد';
        document.getElementById('category-form').action = '/admin/products/categories';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('category-form').reset();
    }

    // Edit category
    function editCategory(categoryId) {
        document.getElementById('category-modal').classList.remove('hidden');
        document.getElementById('modal-title').textContent = 'تعديل التصنيف';
        document.getElementById('category-form').action = `/admin/products/categories/${categoryId}`;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Here you would typically fetch and populate the form with category data
    }

    // Close modal
    function closeCategoryModal() {
        document.getElementById('category-modal').classList.add('hidden');
    }

    // Toggle subcategories
    function toggleSubcategories(categoryId) {
        const subcategories = document.getElementById(`subcategories-${categoryId}`);
        const toggle = document.getElementById(`toggle-${categoryId}`);

        if (subcategories.classList.contains('hidden')) {
            subcategories.classList.remove('hidden');
            toggle.style.transform = 'rotate(180deg)';
        } else {
            subcategories.classList.add('hidden');
            toggle.style.transform = 'rotate(0deg)';
        }
    }

    // Toggle category status
    function toggleCategoryStatus(categoryId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        if (confirm(`هل تريد ${newStatus === 'active' ? 'تفعيل' : 'إيقاف'} هذا التصنيف؟`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/categories/${categoryId}/status`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="status" value="${newStatus}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Delete category
    function deleteCategory(categoryId) {
        if (confirm('هل أنت متأكد من حذف هذا التصنيف؟ سيؤثر هذا على جميع المنتجات المرتبطة به.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/categories/${categoryId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Add subcategory
    function addSubcategory(parentId) {
        document.getElementById('category-modal').classList.remove('hidden');
        document.getElementById('modal-title').textContent = 'إضافة تصنيف فرعي';
        document.getElementById('category-form').action = '/admin/products/categories';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('category-form').reset();
        document.querySelector('select[name="parent_id"]').value = parentId;
    }

    // Sort categories
    function sortCategories() {
        alert('سيتم إضافة ميزة إعادة الترتيب بالسحب والإفلات قريباً');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'category-modal') {
            closeCategoryModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCategoryModal();
        }
    });

    // Drag and drop functionality (basic implementation)
    document.addEventListener('DOMContentLoaded', function() {
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.dataset.id);
            });

            item.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            item.addEventListener('drop', function(e) {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                console.log(`Moving category ${draggedId} to position of ${this.dataset.id}`);
                // Here you would implement the actual reordering logic
            });

            item.draggable = true;
        });
    });
</script>
@endpush
@endsection
