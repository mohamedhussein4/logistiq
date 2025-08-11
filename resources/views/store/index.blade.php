@extends('layouts.main')

@section('title', 'متجر أجهزة التتبع - Link2u')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-purple-600 to-purple-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">متجر أجهزة التتبع</h1>
            <p class="text-purple-100 text-lg">أحدث تقنيات التتبع وإدارة الأساطيل للشركات اللوجستية</p>
        </div>
    </div>
</section>

<!-- Categories and Filter -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Categories -->
            <div class="flex flex-wrap gap-2">
                <button class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium">جميع الأجهزة</button>
                <button class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-100 border">أجهزة السيارات</button>
                <button class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-100 border">أجهزة الشاحنات</button>
                <button class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-100 border">أجهزة متقدمة</button>
                <button class="bg-white text-gray-700 px-4 py-2 rounded-md text-sm hover:bg-gray-100 border">الاكسسوارات</button>
            </div>

            <!-- Search and Sort -->
            <div class="flex gap-2">
                <div class="relative">
                    <input type="text"
                           placeholder="البحث عن جهاز..."
                           class="px-4 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option>ترتيب حسب السعر</option>
                    <option>الأحدث أولاً</option>
                    <option>الأكثر مبيعاً</option>
                    <option>التقييم</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="relative">
                    <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        @if(isset($product->image) && $product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-satellite-dish text-gray-500 text-4xl"></i>
                        @endif
                    </div>
                    <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-md text-xs font-medium">
                        متوفر
                    </div>
                    @if(isset($product->discount) && $product->discount > 0)
                    <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs font-medium">
                        عرض خاص
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ $product->description }}</p>

                    <div class="space-y-2 mb-4">
                        @if(isset($product->features) && $product->features)
                            @foreach($product->features ?? [] as $feature)
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-check text-green-600 ml-2 w-4"></i>
                                <span>{{ $feature }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-satellite text-green-600 ml-2 w-4"></i>
                                <span>GPS عالي الدقة</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-battery-full text-blue-600 ml-2 w-4"></i>
                                <span>بطارية تدوم ٣٠ يوم</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-600">
                                <i class="fas fa-shield-alt text-purple-600 ml-2 w-4"></i>
                                <span>مقاوم للماء IP67</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-2xl font-bold text-purple-600">{{ number_format($product->price) }} ر.س</span>
                            @if(isset($product->original_price) && $product->original_price > $product->price)
                            <span class="text-sm text-gray-500 line-through ml-2">{{ number_format($product->original_price) }} ر.س</span>
                            @endif
                        </div>
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($product->rating ?? 4))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 mr-1">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                    </div>

                    <button onclick="orderDevice('{{ $product->id }}')" class="w-full bg-purple-600 text-white py-2 rounded-md hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-shopping-cart ml-2"></i>
                        طلب شراء
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500 text-lg mb-4">
                    <i class="fas fa-box-open text-6xl mb-4"></i>
                    <p>لا توجد منتجات متاحة حالياً</p>
                </div>
            </div>
            @endforelse

        </div>

        <!-- Load More Button -->
        <div class="text-center mt-12">
            <button class="bg-purple-600 text-white px-8 py-3 rounded-md hover:bg-purple-700 transition-colors font-medium">
                <i class="fas fa-plus ml-2"></i>
                عرض المزيد من الأجهزة
            </button>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">لماذا تختار أجهزتنا؟</h2>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-certificate text-purple-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">جودة معتمدة</h3>
                <p class="text-gray-600 text-sm">جميع أجهزتنا معتمدة دولياً ومطابقة للمعايير</p>
            </div>

            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tools text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">دعم فني متخصص</h3>
                <p class="text-gray-600 text-sm">فريق دعم فني متاح ٢٤/٧ لمساعدتك</p>
            </div>

            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">توصيل سريع</h3>
                <p class="text-gray-600 text-sm">توصيل مجاني لجميع أنحاء المملكة خلال ٢٤ ساعة</p>
            </div>

            <div class="text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-orange-600 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">ضمان شامل</h3>
                <p class="text-gray-600 text-sm">ضمان لمدة سنتين على جميع الأجهزة</p>
            </div>
        </div>
    </div>
</section>

<!-- Order Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">طلب شراء جهاز</h3>
            <button onclick="closeOrderModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form class="space-y-4">
            <div id="selectedDevice" class="bg-gray-50 p-4 rounded-md mb-4">
                <!-- Device info will be inserted here -->
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="اسم شركتك">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اسم المسؤول</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="اسمك الكامل">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="+966 xx xxx xxxx">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="email@company.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الكمية المطلوبة</label>
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="1" min="1" value="1">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">عنوان التسليم</label>
                <textarea rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="العنوان الكامل للتسليم"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات إضافية</label>
                <textarea rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="أي ملاحظات أو متطلبات خاصة"></textarea>
            </div>

            <div class="flex space-x-3 space-x-reverse pt-4">
                <button type="submit" class="flex-1 bg-purple-600 text-white py-2 rounded-md hover:bg-purple-700 font-medium">
                    <i class="fas fa-paper-plane ml-2"></i>
                    إرسال الطلب
                </button>
                <button type="button" onclick="closeOrderModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-400">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const devices = {
        'GT06N': {
            name: 'جهاز تتبع GT06N',
            price: '٤٥٠ ر.س',
            originalPrice: '٥٠٠ ر.س'
        },
        'TK905': {
            name: 'جهاز تتبع TK905',
            price: '٦٨٠ ر.س'
        },
        'ST940': {
            name: 'جهاز تتبع ST940',
            price: '١,٢٠٠ ر.س'
        },
        'PT02': {
            name: 'جهاز تتبع شخصي PT02',
            price: '٣٢٠ ر.س'
        },
        'OBD-CABLE': {
            name: 'كابل توصيل OBD',
            price: '١٨٠ ر.س'
        }
    };

    function orderDevice(deviceId) {
        const device = devices[deviceId];
        if (!device) return;

        const selectedDeviceDiv = document.getElementById('selectedDevice');
        selectedDeviceDiv.innerHTML = `
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center ml-3">
                    <i class="fas fa-satellite-dish text-purple-600"></i>
                </div>
                <div>
                    <div class="font-medium">${device.name}</div>
                    <div class="text-purple-600 font-bold">${device.price}</div>
                </div>
            </div>
        `;

        document.getElementById('orderModal').classList.remove('hidden');
        document.getElementById('orderModal').classList.add('flex');
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
        document.getElementById('orderModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.getElementById('orderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeOrderModal();
        }
    });

    // Form submission
    document.querySelector('#orderModal form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('تم إرسال طلبك بنجاح! سيتم التواصل معك قريباً.');
        closeOrderModal();
    });
</script>
@endpush
@endsection
