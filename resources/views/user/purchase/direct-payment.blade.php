@extends('layouts.main')

@section('title', 'شراء منتج - ' . $product->name)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-secondary-800 mb-4">شراء المنتج</h1>
                <p class="text-secondary-600">اختر وسيلة الدفع وأكمل عملية الشراء</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- معلومات المنتج -->
                <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up">
                    <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                        <i class="fas fa-box text-primary-600 ml-2"></i>
                        تفاصيل المنتج
                    </h2>

                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-64 object-cover rounded-xl mb-6 shadow-soft">
                    @endif

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-secondary-800">{{ $product->name }}</h3>
                            <p class="text-secondary-600 mt-2">{{ $product->description }}</p>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-primary-50 rounded-xl border border-primary-100">
                            <span class="text-secondary-700 font-medium flex items-center">
                                <i class="fas fa-tag text-primary-600 ml-2"></i>
                                السعر:
                            </span>
                            <span class="text-2xl font-bold text-green-600">{{ number_format($product->price, 2) }} ريال</span>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="text-secondary-700 font-medium flex items-center">
                                <i class="fas fa-boxes text-primary-600 ml-2"></i>
                                المتوفر في المخزون:
                            </span>
                            <span class="text-xl font-semibold text-primary-600">{{ $product->stock_quantity }} قطعة</span>
                        </div>

                        @if($product->category)
                        <div class="flex justify-between items-center p-4 bg-purple-50 rounded-xl border border-purple-100">
                            <span class="text-secondary-700 font-medium flex items-center">
                                <i class="fas fa-folder text-primary-600 ml-2"></i>
                                التصنيف:
                            </span>
                            <span class="text-purple-600 font-medium">{{ $product->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- نموذج الدفع -->
                <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up" style="animation-delay: 0.2s;">
                    <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                        <i class="fas fa-credit-card text-primary-600 ml-2"></i>
                        معلومات الطلب والدفع
                    </h2>

                    <!-- عرض الأخطاء -->
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                                <h3 class="text-red-800 font-semibold">يوجد أخطاء:</h3>
                            </div>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.purchase.process', $product->id) }}" method="POST" class="space-y-6" onsubmit="return validateForm()">
                        @csrf

                        <!-- الكمية -->
                        <div class="space-y-2">
                            <label for="quantity" class="block text-sm font-bold text-secondary-700 mb-2">
                                <i class="fas fa-calculator text-primary-600 ml-1"></i>
                                الكمية المطلوبة *
                            </label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" required
                                   class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-soft"
                                   onchange="updateTotal()">
                        </div>

                        <!-- إجمالي السعر -->
                        <div class="p-4 bg-green-50 rounded-xl border border-green-100 animate-scale-in">
                            <div class="flex justify-between items-center">
                                <span class="text-secondary-700 font-medium flex items-center">
                                    <i class="fas fa-calculator text-green-600 ml-2"></i>
                                    إجمالي السعر:
                                </span>
                                <span id="total-price" class="text-2xl font-bold text-green-600">{{ number_format($product->price, 2) }} ريال</span>
                            </div>
                        </div>

                        <!-- طريقة الدفع -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-secondary-700 mb-4">
                                <i class="fas fa-credit-card text-primary-600 ml-1"></i>
                                طريقة الدفع *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-secondary-200 rounded-xl cursor-pointer hover:bg-primary-50 transition-all hover-lift group">
                                    <input type="radio" name="payment_method" value="bank_transfer" required class="ml-3 text-primary-600 focus:ring-primary-500" onchange="showPaymentAccounts('bank')">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center ml-3 group-hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-university text-primary-600"></i>
                                        </div>
                                        <span class="font-medium text-secondary-800">تحويل بنكي</span>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-secondary-200 rounded-xl cursor-pointer hover:bg-primary-50 transition-all hover-lift group">
                                    <input type="radio" name="payment_method" value="electronic_wallet" required class="ml-3 text-primary-600 focus:ring-primary-500" onchange="showPaymentAccounts('wallet')">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center ml-3 group-hover:bg-green-200 transition-colors">
                                            <i class="fas fa-mobile-alt text-green-600"></i>
                                        </div>
                                        <span class="font-medium text-secondary-800">محفظة إلكترونية</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- الحسابات البنكية -->
                        <div id="bank-accounts" class="hidden animate-slide-down">
                            <label class="block text-sm font-bold text-secondary-700 mb-3">
                                <i class="fas fa-university text-primary-600 ml-1"></i>
                                اختر الحساب البنكي
                            </label>
                            <div class="space-y-3">
                                @foreach($bankAccounts as $account)
                                <label class="flex items-center p-4 border border-secondary-200 rounded-xl cursor-pointer hover:bg-primary-50 transition-all hover-lift group">
                                    <input type="radio" name="payment_account_id" value="{{ $account->id }}" class="ml-3 text-primary-600 focus:ring-primary-500">
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-university text-primary-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-secondary-800">{{ $account->bank_name }}</div>
                                            <div class="text-sm text-secondary-600">{{ $account->account_name }}</div>
                                            <div class="text-xs text-secondary-500 font-mono">{{ $account->account_number }}</div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- المحافظ الإلكترونية -->
                        <div id="electronic-wallets" class="hidden animate-slide-down">
                            <label class="block text-sm font-bold text-secondary-700 mb-3">
                                <i class="fas fa-mobile-alt text-primary-600 ml-1"></i>
                                اختر المحفظة الإلكترونية
                            </label>
                            <div class="space-y-3">
                                @foreach($electronicWallets as $wallet)
                                <label class="flex items-center p-4 border border-secondary-200 rounded-xl cursor-pointer hover:bg-primary-50 transition-all hover-lift group">
                                    <input type="radio" name="payment_account_id" value="{{ $wallet->id }}" class="ml-3 text-primary-600 focus:ring-primary-500">
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                            <i class="fas fa-mobile-alt text-green-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-secondary-800">{{ $wallet->wallet_name }}</div>
                                            <div class="text-sm text-secondary-600">{{ $wallet->account_name }}</div>
                                            <div class="text-xs text-secondary-500 font-mono">{{ $wallet->account_number }}</div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <div class="space-y-2">
                            <label for="payment_notes" class="block text-sm font-bold text-secondary-700 mb-2">
                                <i class="fas fa-sticky-note text-primary-600 ml-1"></i>
                                ملاحظات إضافية (اختياري)
                            </label>
                            <textarea name="payment_notes" id="payment_notes" rows="3"
                                      class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-soft"
                                      placeholder="أي ملاحظات خاصة بالطلب..."></textarea>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="flex space-x-4 space-x-reverse pt-6 border-t border-secondary-200">
                            <a href="{{ route('store') }}"
                               class="flex-1 bg-secondary-200 hover:bg-secondary-300 text-secondary-700 px-6 py-3 rounded-xl font-semibold text-center transition-all hover-lift flex items-center justify-center">
                                <i class="fas fa-arrow-left ml-2"></i>
                                الرجوع للمتجر
                            </a>
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-6 py-3 rounded-xl font-semibold transition-all hover-lift shadow-glow flex items-center justify-center">
                                <i class="fas fa-shopping-cart ml-2"></i>
                                تأكيد الطلب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
const productPrice = {{ $product->price }};

function updateTotal() {
    const quantity = document.getElementById('quantity').value;
    const total = productPrice * quantity;
    const formatter = new Intl.NumberFormat('ar-SA', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('total-price').textContent = formatter.format(total) + ' ريال';
}

function showPaymentAccounts(type) {
    const bankAccounts = document.getElementById('bank-accounts');
    const electronicWallets = document.getElementById('electronic-wallets');

    if (type === 'bank') {
        bankAccounts.classList.remove('hidden');
        bankAccounts.classList.add('animate-slide-down');
        electronicWallets.classList.add('hidden');
        electronicWallets.classList.remove('animate-slide-down');

        // إلغاء تحديد المحافظ الإلكترونية
        electronicWallets.querySelectorAll('input[type="radio"]').forEach(input => input.checked = false);
    } else {
        electronicWallets.classList.remove('hidden');
        electronicWallets.classList.add('animate-slide-down');
        bankAccounts.classList.add('hidden');
        bankAccounts.classList.remove('animate-slide-down');

        // إلغاء تحديد الحسابات البنكية
        bankAccounts.querySelectorAll('input[type="radio"]').forEach(input => input.checked = false);
    }
}

function validateForm() {
    // التحقق من طريقة الدفع
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        alert('يرجى اختيار طريقة الدفع');
        return false;
    }

    // التحقق من اختيار الحساب
    const paymentAccount = document.querySelector('input[name="payment_account_id"]:checked');
    if (!paymentAccount) {
        alert('يرجى اختيار الحساب للدفع');
        return false;
    }

    // عرض رسالة التحميل
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري المعالجة...';

    return true;
}

// تحديث الإجمالي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateTotal();

    // إضافة مستمعات للأحداث
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form data:', new FormData(form));
        });
    }
});
</script>
@endpush
@endsection
