@extends('layouts.admin')

@section('title', 'تعديل المستخدم')
@section('page-title', 'تعديل المستخدم')
@section('page-description', 'تعديل بيانات المستخدم')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">تعديل المستخدم: {{ $user->name }}</h1>
                <p class="text-slate-600">تعديل بيانات ومعلومات المستخدم</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' :
                       ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                       ($user->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ $user->status === 'active' ? 'نشط' : ($user->status === 'pending' ? 'معلق' : ($user->status === 'suspended' ? 'موقوف' : $user->status)) }}
                </span>
                <a href="{{ route('admin.users.show', $user) }}" class="px-4 py-2 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    عرض التفاصيل
                </a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- User Edit Form -->
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6" id="user-form">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">المعلومات الأساسية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                           placeholder="أدخل الاسم الكامل">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                           placeholder="user@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone') border-red-500 @enderror"
                           placeholder="+966 50 123 4567">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع المستخدم <span class="text-red-500">*</span></label>
                    <select name="user_type" required onchange="toggleCompanyFields(this.value)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('user_type') border-red-500 @enderror">
                        <option value="">اختر نوع المستخدم</option>
                        <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>مدير النظام</option>
                        <option value="logistics" {{ old('user_type', $user->user_type) == 'logistics' ? 'selected' : '' }}>شركة لوجستية</option>
                        <option value="service_company" {{ old('user_type', $user->user_type) == 'service_company' ? 'selected' : '' }}>شركة طالبة للخدمة</option>
                        <option value="regular" {{ old('user_type', $user->user_type) == 'regular' ? 'selected' : '' }}>مستخدم عادي</option>
                    </select>
                    @error('user_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>موقوف</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ التسجيل</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $user->created_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Information (for companies only) -->
        <div id="company-fields" class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20 {{ in_array($user->user_type, ['logistics', 'service_company']) ? '' : 'hidden' }}">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اسم الشركة <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('company_name') border-red-500 @enderror"
                           placeholder="اسم الشركة">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">السجل التجاري</label>
                    <input type="text" name="company_registration" value="{{ old('company_registration', $user->company_registration) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('company_registration') border-red-500 @enderror"
                           placeholder="رقم السجل التجاري">
                    @error('company_registration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">عنوان الشركة</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('address') border-red-500 @enderror"
                              placeholder="عنوان الشركة">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">شخص الاتصال</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $user->contact_person) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('contact_person') border-red-500 @enderror"
                           placeholder="اسم شخص الاتصال">
                    @error('contact_person')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Service Company Specific Fields -->
            <div id="service-company-fields" class="mt-6 {{ $user->user_type === 'service_company' ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الرقم الضريبي</label>
                        <input type="text" name="tax_number" value="{{ old('tax_number', $user->serviceCompany->tax_number ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('tax_number') border-red-500 @enderror"
                               placeholder="الرقم الضريبي للشركة">
                        @error('tax_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحساب البنكي</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $user->serviceCompany->bank_account ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('bank_account') border-red-500 @enderror"
                               placeholder="رقم الحساب البنكي">
                        @error('bank_account')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Logistics Company Specific Fields -->
            <div id="logistics-company-fields" class="mt-6 {{ $user->user_type === 'logistics' ? '' : 'hidden' }}">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">نوع الشركة اللوجيستية</label>
                    <select name="company_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('company_type') border-red-500 @enderror">
                        <option value="">اختر نوع الشركة</option>
                        <option value="transport" {{ old('company_type', $user->logisticsCompany->company_type ?? '') == 'transport' ? 'selected' : '' }}>شحن وتوصيل</option>
                        <option value="warehouse" {{ old('company_type', $user->logisticsCompany->company_type ?? '') == 'warehouse' ? 'selected' : '' }}>مستودعات</option>
                        <option value="freight" {{ old('company_type', $user->logisticsCompany->company_type ?? '') == 'freight' ? 'selected' : '' }}>شحن بحري/جوي</option>
                        <option value="mixed" {{ old('company_type', $user->logisticsCompany->company_type ?? '') == 'mixed' ? 'selected' : '' }}>خدمات متنوعة</option>
                    </select>
                    @error('company_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">كلمة المرور</h3>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 ml-2"></i>
                    <div class="text-blue-800">
                        <p class="font-semibold mb-1">ملاحظة</p>
                        <p class="text-sm">اتركه فارغاً إذا كنت لا تريد تغيير كلمة المرور الحالية</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور الجديدة</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('password') border-red-500 @enderror"
                           placeholder="أدخل كلمة مرور جديدة (اختياري)">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="أعد كتابة كلمة المرور الجديدة">
                </div>
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">ملاحظات الإدارة</h3>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات إضافية</label>
                <textarea name="admin_notes" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('admin_notes') border-red-500 @enderror"
                          placeholder="أضف ملاحظات إدارية حول هذا المستخدم...">{{ old('admin_notes', $user->admin_notes) }}</textarea>
                @error('admin_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Logistics Company Balance (for logistics users only) -->
        @if($user->user_type === 'logistics' && $user->logisticsCompany)
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة اللوجستية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الرصيد المتاح (ريال)</label>
                    <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                        <span class="text-green-900 text-xl font-bold">{{ number_format($user->logisticsCompany->available_balance) }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي التمويل (ريال)</label>
                    <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <span class="text-blue-900 text-xl font-bold">{{ number_format($user->logisticsCompany->total_funded) }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحد الائتماني (ريال)</label>
                    <input type="number" name="credit_limit" value="{{ old('credit_limit', $user->logisticsCompany->credit_limit) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('credit_limit') border-red-500 @enderror"
                           placeholder="0" min="0" step="100">
                    @error('credit_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">إضافة/خصم من الرصيد</label>
                <div class="flex gap-4">
                    <input type="number" name="balance_adjustment" value="{{ old('balance_adjustment') }}"
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="أدخل المبلغ (موجب للإضافة، سالب للخصم)" step="0.01">
                    <select name="adjustment_type" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="add">إضافة (+)</option>
                        <option value="subtract">خصم (-)</option>
                    </select>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    سيتم تسجيل هذه العملية في سجل معاملات الرصيد
                </p>
            </div>
        </div>
        @endif

        <!-- Service Company Information (for service companies only) -->
        @if($user->user_type === 'service_company' && $user->serviceCompany)
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة الطالبة للخدمة</h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي المستحقات (ريال)</label>
                    <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                        <span class="text-red-900 text-xl font-bold">{{ number_format($user->serviceCompany->total_outstanding) }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي المدفوع (ريال)</label>
                    <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl">
                        <span class="text-green-900 text-xl font-bold">{{ number_format($user->serviceCompany->total_paid) }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الحد الائتماني (ريال)</label>
                    <input type="number" name="service_credit_limit" value="{{ old('service_credit_limit', $user->serviceCompany->credit_limit) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('service_credit_limit') border-red-500 @enderror"
                           placeholder="0" min="0" step="100">
                    @error('service_credit_limit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">حالة الدفع</label>
                <select name="payment_status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('payment_status') border-red-500 @enderror">
                    <option value="regular" {{ old('payment_status', $user->serviceCompany->payment_status) == 'regular' ? 'selected' : '' }}>منتظم</option>
                    <option value="overdue" {{ old('payment_status', $user->serviceCompany->payment_status) == 'overdue' ? 'selected' : '' }}>متأخر</option>
                    <option value="under_review" {{ old('payment_status', $user->serviceCompany->payment_status) == 'under_review' ? 'selected' : '' }}>تحت المراقبة</option>
                </select>
                @error('payment_status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    تأكد من صحة البيانات قبل الحفظ
                </div>

                <div class="flex space-x-4 space-x-reverse">
                    <button type="button" onclick="window.history.back()"
                            class="px-6 py-3 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        إلغاء
                    </button>

                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-save mr-2"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleCompanyFields(userType) {
        const companyFields = document.getElementById('company-fields');
        const serviceCompanyFields = document.getElementById('service-company-fields');
        const logisticsCompanyFields = document.getElementById('logistics-company-fields');
        const companyNameInput = document.querySelector('input[name="company_name"]');

        // إخفاء جميع الحقول أولاً
        companyFields.classList.add('hidden');
        if (serviceCompanyFields) serviceCompanyFields.classList.add('hidden');
        if (logisticsCompanyFields) logisticsCompanyFields.classList.add('hidden');
        companyNameInput.required = false;

        // إظهار الحقول حسب نوع المستخدم
        if (userType === 'logistics' || userType === 'service_company') {
            companyFields.classList.remove('hidden');
            companyNameInput.required = true;

            if (userType === 'service_company' && serviceCompanyFields) {
                serviceCompanyFields.classList.remove('hidden');
            }

            if (userType === 'logistics' && logisticsCompanyFields) {
                logisticsCompanyFields.classList.remove('hidden');
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.querySelector('select[name="user_type"]');
        toggleCompanyFields(userTypeSelect.value);

        // إضافة event listener لتغيير نوع المستخدم
        userTypeSelect.addEventListener('change', function() {
            toggleCompanyFields(this.value);
        });
    });

    // Form submission handling
    document.getElementById('user-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>جاري الحفظ...';

        // إعادة تفعيل الزر بعد 10 ثوان كإجراء احتياطي
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>حفظ التغييرات';
        }, 10000);
    });

    // تأكيد عند تغيير نوع المستخدم
    document.querySelector('select[name="user_type"]').addEventListener('change', function() {
        const originalType = '{{ $user->user_type }}';
        if (this.value !== originalType && originalType !== '') {
            if (!confirm('تغيير نوع المستخدم قد يؤثر على البيانات المرتبطة. هل تريد المتابعة؟')) {
                this.value = originalType;
                toggleCompanyFields(originalType);
                return;
            }
        }
        toggleCompanyFields(this.value);
    });
</script>

<style>
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>
@endsection
