@extends('layouts.main')

@section('title', 'الملف الشخصي - ' . auth()->user()->name)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-secondary-800 mb-2">
                        مرحباً {{ auth()->user()->name }}
                    </h1>
                    <p class="text-secondary-600">إدارة حسابك وطلباتك</p>
                </div>
                <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                    <div class="glass rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['total_orders'] }}</div>
                        <div class="text-sm text-secondary-600">إجمالي الطلبات</div>
                    </div>
                    <div class="glass rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($stats['total_spent']) }} ر.س</div>
                        <div class="text-sm text-secondary-600">إجمالي المشتريات</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Tab Navigation -->
            <div class="flex flex-wrap border-b border-secondary-200 mb-8" role="tablist">
                <button class="tab-button active px-6 py-3 font-medium text-primary-600 border-b-2 border-primary-600"
                        onclick="showTab(event, 'orders')" role="tab">
                    <i class="fas fa-shopping-bag ml-2"></i>
                    طلباتي
                </button>
                <button class="tab-button px-6 py-3 font-medium text-secondary-600 hover:text-primary-600 border-b-2 border-transparent"
                        onclick="showTab(event, 'cart')" role="tab">
                    <i class="fas fa-shopping-cart ml-2"></i>
                    السلة
                    @if($cartCount > 0)
                        <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 mr-2">{{ $cartCount }}</span>
                    @endif
                </button>
                <button class="tab-button px-6 py-3 font-medium text-secondary-600 hover:text-primary-600 border-b-2 border-transparent"
                        onclick="showTab(event, 'profile')" role="tab">
                    <i class="fas fa-user ml-2"></i>
                    الملف الشخصي
                </button>
            </div>

            <!-- Orders Tab -->
            <div id="orders" class="tab-content">
                <div class="grid gap-6">
                    @forelse($orders as $order)
                    <div class="glass rounded-xl p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-semibold text-lg">طلب رقم #{{ $order->id }}</h3>
                                <p class="text-sm text-secondary-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-left">
                                <div class="text-lg font-bold text-primary-600">{{ number_format($order->original_amount) }} ر.س</div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($order->status == 'completed') bg-green-100 text-green-800
                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <img src="{{ $order->product->image ?? '/images/device-placeholder.jpg' }}"
                                     alt="{{ $order->product->name }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ $order->product->name }}</h4>
                                    <p class="text-sm text-secondary-600">الكمية: {{ $order->quantity }}</p>
                                </div>
                                <div class="text-left">
                                    <button onclick="viewOrder({{ $order->id }})"
                                            class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                        عرض التفاصيل
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="text-gray-500 text-lg mb-4">
                            <i class="fas fa-shopping-bag text-6xl mb-4"></i>
                            <p>لا توجد طلبات بعد</p>
                        </div>
                        <a href="{{ route('store') }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                            تسوق الآن
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Cart Tab -->
            <div id="cart" class="tab-content hidden">
                <div class="grid gap-6">
                    @forelse($cartItems as $item)
                    <div class="glass rounded-xl p-6">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <img src="{{ $item['product']->image ?? '/images/device-placeholder.jpg' }}"
                                 alt="{{ $item['product']->name }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg">{{ $item['product']->name }}</h4>
                                <p class="text-secondary-600 text-sm mb-2">{{ $item['product']->description }}</p>
                                <div class="text-primary-600 font-bold">{{ number_format($item['product']->price) }} ر.س</div>
                            </div>
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <button onclick="updateQuantity({{ $item['product']->id }}, -1)"
                                        class="w-8 h-8 bg-secondary-200 rounded-full flex items-center justify-center hover:bg-secondary-300">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="px-3 py-1 bg-primary-50 rounded">{{ $item['quantity'] }}</span>
                                <button onclick="updateQuantity({{ $item['product']->id }}, 1)"
                                        class="w-8 h-8 bg-secondary-200 rounded-full flex items-center justify-center hover:bg-secondary-300">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                                <button onclick="removeFromCart({{ $item['product']->id }})"
                                        class="text-red-500 hover:text-red-700 mr-4">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="text-gray-500 text-lg mb-4">
                            <i class="fas fa-shopping-cart text-6xl mb-4"></i>
                            <p>السلة فارغة</p>
                        </div>
                        <a href="{{ route('store') }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                            تسوق الآن
                        </a>
                    </div>
                    @endforelse

                    @if(count($cartItems) > 0)
                    <div class="glass rounded-xl p-6 mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">إجمالي السلة</h3>
                            <div class="text-2xl font-bold text-primary-600">{{ number_format($cartTotal) }} ر.س</div>
                        </div>
                        <button onclick="checkout()" class="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-colors font-medium">
                            إتمام الشراء
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Profile Tab -->
            <div id="profile" class="tab-content hidden">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Personal Information -->
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-lg mb-6 flex items-center">
                            <i class="fas fa-user text-primary-600 ml-3"></i>
                            المعلومات الشخصية
                        </h3>
                        <form id="profile-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">الاسم</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}"
                                       class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                       class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">رقم الهاتف</label>
                                <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                                       class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <button type="submit" class="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-colors">
                                حفظ التغييرات
                            </button>
                        </form>
                    </div>

                    <!-- Address Information -->
                    <div class="glass rounded-xl p-6">
                        <h3 class="font-semibold text-lg mb-6 flex items-center">
                            <i class="fas fa-map-marker-alt text-primary-600 ml-3"></i>
                            عنوان التوصيل
                        </h3>
                        <form id="address-form" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">المدينة</label>
                                <input type="text" name="city" value="{{ auth()->user()->userProfile->city ?? '' }}"
                                       class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">العنوان التفصيلي</label>
                                <textarea name="address" rows="3"
                                          class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">{{ auth()->user()->userProfile->address ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">الرمز البريدي</label>
                                <input type="text" name="postal_code" value="{{ auth()->user()->userProfile->postal_code ?? '' }}"
                                       class="w-full px-4 py-3 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <button type="submit" class="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-colors">
                                حفظ العنوان
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Tab functionality
function showTab(evt, tabName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.add("hidden");
    }

    tablinks = document.getElementsByClassName("tab-button");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active", "text-primary-600", "border-primary-600");
        tablinks[i].classList.add("text-secondary-600", "border-transparent");
    }

    document.getElementById(tabName).classList.remove("hidden");
    evt.currentTarget.classList.add("active", "text-primary-600", "border-primary-600");
    evt.currentTarget.classList.remove("text-secondary-600", "border-transparent");
}

// Cart functions
function updateQuantity(productId, change) {
    // Add AJAX call to update quantity
    console.log('Update quantity for product:', productId, 'Change:', change);
}

function removeFromCart(productId) {
    if (confirm('هل تريد إزالة هذا المنتج من السلة؟')) {
        // Add AJAX call to remove from cart
        console.log('Remove product from cart:', productId);
    }
}

function checkout() {
    // Add checkout functionality
    console.log('Proceeding to checkout');
}

function viewOrder(orderId) {
    // Add order details view
    console.log('View order details:', orderId);
}

// Profile update forms
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add AJAX call to update profile
    console.log('Update profile');
});

document.getElementById('address-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add AJAX call to update address
    console.log('Update address');
});
</script>

<style>
.tab-button.active {
    color: #7c3aed;
    border-color: #7c3aed;
}

.glass {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
@endsection
