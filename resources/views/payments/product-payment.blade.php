@extends('layouts.main')

@section('title', 'دفع طلب المنتج - ' . $order->product->name)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-green-600 to-green-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">دفع طلب المنتج</h1>
            <p class="text-green-100 text-lg">إكمال عملية الدفع لطلب المنتج</p>
        </div>
    </div>
</section>

<!-- Order Details -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">تفاصيل الطلب</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">معلومات المنتج</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">اسم المنتج:</span>
                                <span class="font-semibold">{{ $order->product->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">الكمية:</span>
                                <span class="font-semibold">{{ $order->quantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">سعر الوحدة:</span>
                                <span class="font-semibold">{{ number_format($order->unit_price) }} ر.س</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-green-600">
                                <span>إجمالي المبلغ:</span>
                                <span>{{ number_format($order->total_amount) }} ر.س</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">معلومات التوصيل</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-gray-600">عنوان التوصيل:</span>
                                <p class="font-semibold mt-1">{{ $order->delivery_address }}</p>
                            </div>
                            @if($order->notes)
                            <div>
                                <span class="text-gray-600">ملاحظات:</span>
                                <p class="font-semibold mt-1">{{ $order->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">اختر طريقة الدفع</h2>

                <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="payment_type" value="product_order">
                    <input type="hidden" name="related_id" value="{{ $order->id }}">
                    <input type="hidden" name="amount" value="{{ $order->total_amount }}">

                    <!-- Payment Method Selection -->
                    <div class="mb-6">
                        <label class="block text-lg font-semibold text-gray-700 mb-4">طريقة الدفع</label>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Bank Transfer -->
                            <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer payment-method" data-method="bank_transfer">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" required>
                                    <div>
                                        <div class="flex items-center">
                                            <i class="fas fa-university text-blue-600 text-xl ml-2"></i>
                                            <span class="font-semibold text-gray-800">تحويل بنكي</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">تحويل مباشر إلى الحساب البنكي</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Electronic Wallet -->
                            <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer payment-method" data-method="electronic_wallet">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" value="electronic_wallet" class="mr-3" required>
                                    <div>
                                        <div class="flex items-center">
                                            <i class="fas fa-mobile-alt text-green-600 text-xl ml-2"></i>
                                            <span class="font-semibold text-gray-800">محفظة إلكترونية</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">دفع عبر المحافظ الإلكترونية</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Account Selection -->
                    <div id="payment-account-section" class="mb-6 hidden">
                        <label class="block text-lg font-semibold text-gray-700 mb-4">اختر الحساب</label>

                        <!-- Bank Accounts -->
                        <div id="bank-accounts" class="hidden">
                            <div class="grid md:grid-cols-2 gap-4">
                                @foreach($bankAccounts as $account)
                                <div class="border border-gray-200 rounded-lg p-4 cursor-pointer account-option" data-id="{{ $account->id }}" data-type="bank_account">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_account_id" value="{{ $account->id }}" class="mr-3">
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $account->bank_name }}</div>
                                            <div class="text-sm text-gray-600">{{ $account->account_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $account->account_number }}</div>
                                            @if($account->iban)
                                            <div class="text-xs text-gray-400 mt-1">{{ $account->iban }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Electronic Wallets -->
                        <div id="electronic-wallets" class="hidden">
                            <div class="grid md:grid-cols-2 gap-4">
                                @foreach($electronicWallets as $wallet)
                                <div class="border border-gray-200 rounded-lg p-4 cursor-pointer account-option" data-id="{{ $wallet->id }}" data-type="electronic_wallet">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_account_id" value="{{ $wallet->id }}" class="mr-3">
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $wallet->wallet_name }}</div>
                                            <div class="text-sm text-gray-600">{{ $wallet->account_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $wallet->account_number }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Payment Notes -->
                    <div class="mb-6">
                        <label for="payment_notes" class="block text-lg font-semibold text-gray-700 mb-2">ملاحظات الدفع</label>
                        <textarea name="payment_notes" id="payment_notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="أضف أي ملاحظات إضافية حول الدفع..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                            <i class="fas fa-credit-card ml-2"></i>
                            إنشاء طلب الدفع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentAccountSection = document.getElementById('payment-account-section');
    const bankAccounts = document.getElementById('bank-accounts');
    const electronicWallets = document.getElementById('electronic-wallets');
    const accountOptions = document.querySelectorAll('.account-option');

    // إخفاء قسم اختيار الحساب في البداية
    paymentAccountSection.classList.add('hidden');

    // إضافة مستمع الأحداث لطرق الدفع
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // إزالة التحديد من جميع الطرق
            paymentMethods.forEach(m => m.classList.remove('border-green-500', 'bg-green-50'));
            paymentMethods.forEach(m => m.querySelector('input').checked = false);

            // تحديد الطريقة المختارة
            this.classList.add('border-green-500', 'bg-green-50');
            this.querySelector('input').checked = true;

            // إظهار قسم اختيار الحساب
            paymentAccountSection.classList.remove('hidden');

            // إظهار الحسابات المناسبة
            const selectedMethod = this.dataset.method;
            if (selectedMethod === 'bank_transfer') {
                bankAccounts.classList.remove('hidden');
                electronicWallets.classList.add('hidden');
            } else if (selectedMethod === 'electronic_wallet') {
                bankAccounts.classList.add('hidden');
                electronicWallets.classList.remove('hidden');
            }
        });
    });

    // إضافة مستمع الأحداث لخيارات الحساب
    accountOptions.forEach(option => {
        option.addEventListener('click', function() {
            // إزالة التحديد من جميع الخيارات
            accountOptions.forEach(o => o.classList.remove('border-green-500', 'bg-green-50'));
            accountOptions.forEach(o => o.querySelector('input').checked = false);

            // تحديد الخيار المختار
            this.classList.add('border-green-500', 'bg-green-50');
            this.querySelector('input').checked = true;

            // إضافة نوع الحساب
            const accountType = this.dataset.type;
            const accountId = this.dataset.id;

            // إضافة حقول مخفية
            let typeField = document.querySelector('input[name="payment_account_type"]');
            if (!typeField) {
                typeField = document.createElement('input');
                typeField.type = 'hidden';
                typeField.name = 'payment_account_type';
                document.getElementById('payment-form').appendChild(typeField);
            }
            typeField.value = accountType;
        });
    });
});
</script>
@endpush
@endsection
