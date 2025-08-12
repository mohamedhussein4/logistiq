@extends('layouts.admin')

@section('title', 'إدارة الحسابات البنكية')
@section('page-title', 'إدارة الحسابات البنكية')
@section('page-description', 'نظام إدارة شامل للحسابات البنكية وبيانات التحويل')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <div class="text-center bg-blue-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-blue-600">{{ $bankAccounts->where('status', 'active')->count() }}</div>
                <div class="text-xs lg:text-sm text-blue-700 font-semibold">الحسابات النشطة</div>
            </div>
            <div class="text-center bg-green-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-green-600">{{ $bankAccounts->count() }}</div>
                <div class="text-xs lg:text-sm text-green-700 font-semibold">إجمالي الحسابات</div>
            </div>
            <div class="text-center bg-yellow-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-yellow-600">{{ $bankAccounts->where('status', 'inactive')->count() }}</div>
                <div class="text-xs lg:text-sm text-yellow-700 font-semibold">غير نشطة</div>
            </div>
            <div class="text-center bg-purple-50 rounded-xl lg:rounded-2xl p-3 lg:p-4">
                <div class="text-lg lg:text-2xl font-black text-purple-600">{{ $bankAccounts->where('iban', '!=', null)->count() }}</div>
                <div class="text-xs lg:text-sm text-purple-700 font-semibold">مع IBAN</div>
            </div>
        </div>
    </div>

    <!-- Add New Bank Account -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">إضافة حساب بنكي جديد</h3>
                <p class="text-slate-600 text-sm lg:text-base">أضف حساب بنكي جديد للنظام</p>
            </div>
            <button onclick="toggleAddForm()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                <i class="fas fa-plus mr-2"></i>
                <span class="hidden lg:inline">إضافة حساب</span>
                <span class="lg:hidden">إضافة</span>
            </button>
        </div>

        <form id="add-bank-account-form" action="{{ route('admin.bank_accounts.store') }}" method="POST" class="hidden space-y-4 lg:space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                <div>
                    <label for="bank_name" class="block text-sm font-bold text-slate-700 mb-2">اسم البنك *</label>
                    <input type="text" name="bank_name" id="bank_name" required
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700"
                           placeholder="مثال: البنك الأهلي السعودي">
                </div>

                <div>
                    <label for="account_name" class="block text-sm font-bold text-slate-700 mb-2">اسم الحساب *</label>
                    <input type="text" name="account_name" id="account_name" required
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-slate-700"
                           placeholder="مثال: Link2u للتجارة">
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-bold text-slate-700 mb-2">رقم الحساب *</label>
                    <input type="text" name="account_number" id="account_number" required
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-slate-700"
                           placeholder="مثال: 1234567890">
                </div>

                <div>
                    <label for="iban" class="block text-sm font-bold text-slate-700 mb-2">رقم IBAN</label>
                    <input type="text" name="iban" id="iban"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all text-slate-700"
                           placeholder="مثال: SA0380000000608010167519">
                </div>

                <div>
                    <label for="swift_code" class="block text-sm font-bold text-slate-700 mb-2">رمز SWIFT</label>
                    <input type="text" name="swift_code" id="swift_code"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-slate-700"
                           placeholder="مثال: NCBJSARI">
                </div>

                <div>
                    <label for="branch_name" class="block text-sm font-bold text-slate-700 mb-2">اسم الفرع</label>
                    <input type="text" name="branch_name" id="branch_name"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all text-slate-700"
                           placeholder="مثال: الفرع الرئيسي - الرياض">
                </div>

                <div>
                    <label for="branch_code" class="block text-sm font-bold text-slate-700 mb-2">رمز الفرع</label>
                    <input type="text" name="branch_code" id="branch_code"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-slate-700"
                           placeholder="مثال: 001">
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-bold text-slate-700 mb-2">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="0"
                           class="w-full px-4 py-3 lg:px-6 lg:py-4 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all text-slate-700"
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
                    حفظ الحساب
                </button>
            </div>
        </form>
    </div>

    <!-- Bank Accounts List -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-0 mb-6 lg:mb-8">
            <div>
                <h3 class="text-xl lg:text-2xl font-bold gradient-text mb-2">قائمة الحسابات البنكية</h3>
                <p class="text-slate-600 text-sm lg:text-base">عرض {{ $bankAccounts->count() }} حساب بنكي</p>
            </div>

            <div class="flex space-x-2 space-x-reverse">
                <button onclick="toggleAddForm()" class="px-4 lg:px-6 py-2 lg:py-3 bg-gradient-success text-white rounded-lg lg:rounded-xl font-semibold text-sm lg:text-base hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden lg:inline">إضافة حساب</span>
                    <span class="lg:hidden">إضافة</span>
                </button>
            </div>
        </div>

        @if($bankAccounts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm lg:text-base">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">البنك</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">اسم الحساب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">رقم الحساب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">IBAN</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الفرع</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الحالة</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الترتيب</th>
                        <th class="text-right py-3 lg:py-4 font-semibold text-slate-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($bankAccounts as $account)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 lg:py-4">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-university text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $account->bank_name }}</div>
                                    @if($account->swift_code)
                                    <div class="text-xs text-slate-500">{{ $account->swift_code }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="font-semibold text-slate-800">{{ $account->account_name }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="font-mono text-sm text-slate-700">{{ $account->account_number }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            @if($account->iban)
                            <div class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $account->iban }}</div>
                            @else
                            <span class="text-slate-400 text-sm">غير محدد</span>
                            @endif
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="text-sm text-slate-700">
                                @if($account->branch_name)
                                <div>{{ $account->branch_name }}</div>
                                @if($account->branch_code)
                                <div class="text-xs text-slate-500">كود: {{ $account->branch_code }}</div>
                                @endif
                                @else
                                <span class="text-slate-400">غير محدد</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($account->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $account->status_name }}
                            </span>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="text-sm text-slate-600">{{ $account->sort_order }}</div>
                        </td>
                        <td class="py-3 lg:py-4">
                            <div class="flex space-x-2 space-x-reverse">
                                <button onclick="editBankAccount({{ $account->id }})"
                                        class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors"
                                        title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($account->status === 'active')
                                <button onclick="toggleStatus({{ $account->id }}, 'inactive')"
                                        class="text-yellow-600 hover:text-yellow-800 p-1 rounded transition-colors"
                                        title="إلغاء التفعيل">
                                    <i class="fas fa-pause"></i>
                                </button>
                                @else
                                <button onclick="toggleStatus({{ $account->id }}, 'active')"
                                        class="text-green-600 hover:text-green-800 p-1 rounded transition-colors"
                                        title="تفعيل">
                                    <i class="fas fa-play"></i>
                                </button>
                                @endif
                                <button onclick="deleteBankAccount({{ $account->id }})"
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
                <i class="fas fa-university text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد حسابات بنكية</h3>
            <p class="text-gray-500 mb-6">لم يتم إضافة أي حسابات بنكية بعد</p>
            <button onclick="toggleAddForm()"
                    class="px-6 py-3 bg-gradient-success text-white rounded-lg font-semibold hover-lift transition-all">
                <i class="fas fa-plus mr-2"></i>
                إضافة أول حساب
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Edit Bank Account Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">تعديل الحساب البنكي</h3>
            <button onclick="hideEditModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label for="edit_bank_name" class="block text-sm font-medium text-gray-700 mb-2">اسم البنك *</label>
                    <input type="text" name="bank_name" id="edit_bank_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="edit_account_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الحساب *</label>
                    <input type="text" name="account_name" id="edit_account_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="edit_account_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الحساب *</label>
                    <input type="text" name="account_number" id="edit_account_number" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label for="edit_iban" class="block text-sm font-medium text-gray-700 mb-2">رقم IBAN</label>
                    <input type="text" name="iban" id="edit_iban"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="edit_swift_code" class="block text-sm font-medium text-gray-700 mb-2">رمز SWIFT</label>
                    <input type="text" name="swift_code" id="edit_swift_code"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="edit_branch_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الفرع</label>
                    <input type="text" name="branch_name" id="edit_branch_name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                </div>

                <div>
                    <label for="edit_branch_code" class="block text-sm font-medium text-gray-700 mb-2">رمز الفرع</label>
                    <input type="text" name="branch_code" id="edit_branch_code"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <div>
                    <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="edit_sort_order" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
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
    const form = document.getElementById('add-bank-account-form');
    form.classList.toggle('hidden');
}

function editBankAccount(accountId) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');

    // تحديث مسار النموذج
    form.action = `/admin/bank-accounts/${accountId}`;

    // جلب بيانات الحساب
    fetch(`/admin/bank-accounts/${accountId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // ملء النموذج بالبيانات
            document.getElementById('edit_bank_name').value = data.bank_name || '';
            document.getElementById('edit_account_name').value = data.account_name || '';
            document.getElementById('edit_account_number').value = data.account_number || '';
            document.getElementById('edit_iban').value = data.iban || '';
            document.getElementById('edit_swift_code').value = data.swift_code || '';
            document.getElementById('edit_branch_name').value = data.branch_name || '';
            document.getElementById('edit_branch_code').value = data.branch_code || '';
            document.getElementById('edit_sort_order').value = data.sort_order || 0;
            document.getElementById('edit_status').value = data.status || 'active';
            document.getElementById('edit_notes').value = data.notes || '';

            // إظهار النموذج
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات الحساب:', error);
            alert('حدث خطأ في جلب بيانات الحساب');
        });
}

function hideEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.classList.add('hidden');
}

function toggleStatus(accountId, newStatus) {
    if (confirm('هل أنت متأكد من تغيير حالة الحساب؟')) {
        // هنا يمكن إضافة منطق تحديث الحالة
        console.log('تغيير حالة الحساب:', accountId, 'إلى:', newStatus);
    }
}

function deleteBankAccount(accountId) {
    if (confirm('هل أنت متأكد من حذف هذا الحساب البنكي؟')) {
        // إنشاء نموذج حذف
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/bank-accounts/${accountId}`;

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
