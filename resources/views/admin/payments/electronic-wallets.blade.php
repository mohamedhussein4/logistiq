@extends('layouts.admin')

@section('title', 'إدارة المحافظ الإلكترونية')
@section('page-title', 'إدارة المحافظ الإلكترونية')
@section('page-description', 'نظام إدارة شامل للمحافظ الإلكترونية وطرق الدفع الرقمية')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $electronicWallets->where('status', 'active')->count() }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">المحافظ النشطة</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $electronicWallets->count() }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">إجمالي المحافظ</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $electronicWallets->where('status', 'inactive')->count() }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">غير نشطة</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $electronicWallets->unique('wallet_type')->count() }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">أنواع مختلفة</div>
            </div>
        </div>
    </div>

    <!-- Add New Electronic Wallet -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">إضافة محفظة إلكترونية جديدة</h3>
                <p class="text-slate-600 text-sm lg:text-base">أضف محفظة إلكترونية جديدة للنظام</p>
            </div>
            <button onclick="toggleAddForm()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                <i class="fas fa-plus mr-2"></i>
                <span class="hidden lg:inline">إضافة محفظة</span>
                <span class="lg:hidden">إضافة</span>
            </button>
        </div>

        <form id="add-wallet-form" action="{{ route('admin.electronic_wallets.store') }}" method="POST" class="hidden space-y-4 lg:space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                <div>
                    <label for="wallet_name" class="block text-sm font-bold text-slate-700 mb-2">اسم المحفظة *</label>
                    <input type="text" name="wallet_name" id="wallet_name" required
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700"
                           placeholder="مثال: STC Pay">
                </div>

                <div>
                    <label for="wallet_type" class="block text-sm font-bold text-slate-700 mb-2">نوع المحفظة *</label>
                    <select name="wallet_type" id="wallet_type" required
                            class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700">
                        <option value="">اختر نوع المحفظة</option>
                        <option value="stc_pay">STC Pay</option>
                        <option value="mada">مدى</option>
                        <option value="apple_pay">Apple Pay</option>
                        <option value="google_pay">Google Pay</option>
                        <option value="paypal">PayPal</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-bold text-slate-700 mb-2">رقم الحساب *</label>
                    <input type="text" name="account_number" id="account_number" required
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-slate-700"
                           placeholder="مثال: 966501234567">
                </div>

                <div>
                    <label for="account_name" class="block text-sm font-bold text-slate-700 mb-2">اسم الحساب *</label>
                    <input type="text" name="account_name" id="account_name" required
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all text-slate-700"
                           placeholder="مثال: Link2u للتجارة">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone_number" id="phone_number"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-slate-700"
                           placeholder="مثال: 966501234567">
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all text-slate-700"
                           placeholder="مثال: payments@link2u.com">
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-bold text-slate-700 mb-2">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="0"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-slate-700"
                           placeholder="0">
                </div>

                <div>
                    <label for="status" class="block text-sm font-bold text-slate-700 mb-2">الحالة *</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-slate-700">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-bold text-slate-700 mb-2">ملاحظات</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all text-slate-700"
                          placeholder="أضف أي ملاحظات إضافية..."></textarea>
            </div>

            <div class="flex space-x-3 space-x-reverse">
                <button type="button" onclick="toggleAddForm()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    إلغاء
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-success text-white rounded-xl font-semibold hover-lift transition-all">
                    <i class="fas fa-save mr-2"></i>
                    حفظ المحفظة
                </button>
            </div>
        </form>
    </div>

    <!-- Electronic Wallets List -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة المحافظ الإلكترونية</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $electronicWallets->count() }} محفظة إلكترونية</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button onclick="toggleAddForm()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">إضافة محفظة</span>
                    <span class="lg:hidden">إضافة</span>
                </button>
            </div>
        </div>

        @if($electronicWallets->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm lg:text-base">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">المحفظة</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">اسم الحساب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">رقم الحساب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">معلومات الاتصال</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الحالة</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الترتيب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($electronicWallets as $wallet)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 lg:py-4">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    @if($wallet->wallet_type === 'stc_pay')
                                        <i class="fas fa-mobile-alt text-green-600"></i>
                                    @elseif($wallet->wallet_type === 'mada')
                                        <i class="fas fa-credit-card text-blue-600"></i>
                                    @elseif($wallet->wallet_type === 'apple_pay')
                                        <i class="fab fa-apple text-gray-800"></i>
                                    @elseif($wallet->wallet_type === 'google_pay')
                                        <i class="fab fa-google text-blue-600"></i>
                                    @elseif($wallet->wallet_type === 'paypal')
                                        <i class="fab fa-paypal text-blue-600"></i>
                                    @else
                                        <i class="fas fa-wallet text-purple-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $wallet->wallet_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $wallet->wallet_type_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="font-semibold text-slate-800">{{ $wallet->account_name }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="font-mono text-sm text-slate-700">{{ $wallet->account_number }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="text-sm text-slate-700 space-y-1">
                                @if($wallet->phone_number)
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <i class="fas fa-phone text-slate-400 text-xs"></i>
                                    <span>{{ $wallet->phone_number }}</span>
                                </div>
                                @endif
                                @if($wallet->email)
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <i class="fas fa-envelope text-slate-400 text-xs"></i>
                                    <span>{{ $wallet->email }}</span>
                                </div>
                                @endif
                                @if(!$wallet->phone_number && !$wallet->email)
                                <span class="text-slate-400">غير محدد</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($wallet->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $wallet->status_name }}
                            </span>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="text-sm text-slate-600">{{ $wallet->sort_order }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="flex space-x-2 space-x-reverse">
                                <button onclick="editWallet({{ $wallet->id }})"
                                        class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                        title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($wallet->status === 'active')
                                <button onclick="toggleStatus({{ $wallet->id }}, 'inactive')"
                                        class="text-yellow-600 hover:text-yellow-800 p-1 rounded transition-colors"
                                        title="إلغاء التفعيل">
                                    <i class="fas fa-pause"></i>
                                </button>
                                @else
                                <button onclick="toggleStatus({{ $wallet->id }}, 'active')"
                                        class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                        title="تفعيل">
                                    <i class="fas fa-play"></i>
                                </button>
                                @endif
                                <button onclick="deleteWallet({{ $wallet->id }})"
                                        class="text-red-600 hover:text-red-800 p-1 rounded transition-colors"
                                        title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-mobile-alt text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد محافظ إلكترونية</h3>
            <p class="text-gray-500 mb-6">لم يتم إضافة أي محافظ إلكترونية بعد</p>
            <button onclick="toggleAddForm()"
                    class="px-6 py-3 bg-gradient-success text-white rounded-lg font-semibold hover-lift transition-all">
                <i class="fas fa-plus mr-2"></i>
                إضافة أول محفظة
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Edit Electronic Wallet Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">تعديل المحفظة الإلكترونية</h3>
            <button onclick="hideEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label for="edit_wallet_name" class="block text-sm font-medium text-gray-700 mb-2">اسم المحفظة *</label>
                    <input type="text" name="wallet_name" id="edit_wallet_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_wallet_type" class="block text-sm font-medium text-gray-700 mb-2">نوع المحفظة *</label>
                    <select name="wallet_type" id="edit_wallet_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="stc_pay">STC Pay</option>
                        <option value="mada">مدى</option>
                        <option value="apple_pay">Apple Pay</option>
                        <option value="google_pay">Google Pay</option>
                        <option value="paypal">PayPal</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <div>
                    <label for="edit_account_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الحساب *</label>
                    <input type="text" name="account_number" id="edit_account_number" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label for="edit_account_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الحساب *</label>
                    <input type="text" name="account_name" id="edit_account_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="edit_phone_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone_number" id="edit_phone_number"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="edit_email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                </div>

                <div>
                    <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="edit_sort_order" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <div>
                    <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">الحالة *</label>
                    <select name="status" id="edit_status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea name="notes" id="edit_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500"></textarea>
            </div>

            <div class="flex space-x-3 space-x-reverse pt-4">
                <button type="button" onclick="hideEditModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    إلغاء
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-save ml-2"></i>
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleAddForm() {
    const form = document.getElementById('add-wallet-form');
    form.classList.toggle('hidden');
}

function editWallet(walletId) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');

    // تحديث مسار النموذج
    form.action = `/admin/electronic-wallets/${walletId}`;

    // جلب بيانات المحفظة
    fetch(`/admin/electronic-wallets/${walletId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // ملء النموذج بالبيانات
            document.getElementById('edit_wallet_name').value = data.wallet_name || '';
            document.getElementById('edit_wallet_type').value = data.wallet_type || '';
            document.getElementById('edit_account_number').value = data.account_number || '';
            document.getElementById('edit_account_name').value = data.account_name || '';
            document.getElementById('edit_phone_number').value = data.phone_number || '';
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_sort_order').value = data.sort_order || 0;
            document.getElementById('edit_status').value = data.status || 'active';
            document.getElementById('edit_notes').value = data.notes || '';

            // إظهار النموذج
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات المحفظة:', error);
            alert('حدث خطأ في جلب بيانات المحفظة');
        });
}

function hideEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.classList.add('hidden');
}

function toggleStatus(walletId, newStatus) {
    if (confirm('هل أنت متأكد من تغيير حالة المحفظة؟')) {
        // هنا يمكن إضافة منطق تحديث الحالة
        console.log('تغيير حالة المحفظة:', walletId, 'إلى:', newStatus);
    }
}

function deleteWallet(walletId) {
    if (confirm('هل أنت متأكد من حذف هذه المحفظة الإلكترونية؟')) {
        // إنشاء نموذج حذف
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/electronic-wallets/${walletId}`;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';

        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// إغلاق النموذج عند النقر خارجه
document.addEventListener('click', function(e) {
    const modal = document.getElementById('edit-modal');
    if (e.target === modal) {
        hideEditModal();
    }
});

// إغلاق النموذج بمفتاح Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideEditModal();
    }
});
</script>
@endpush
@endsection
