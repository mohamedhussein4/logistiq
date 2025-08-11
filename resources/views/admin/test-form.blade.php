@extends('layouts.admin')

@section('title', 'نموذج تجريبي')
@section('page-title', 'نموذج تجريبي')
@section('page-description', 'مثال على نموذج مع رسائل التأكيد والأخطاء')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Test Form -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="mb-6">
            <h2 class="text-2xl font-bold gradient-text mb-2">نموذج تجريبي لنظام الرسائل</h2>
            <p class="text-slate-600">هذا النموذج يوضح كيفية عمل نظام الرسائل والتحقق من البيانات</p>
        </div>

        <form method="POST" action="{{ route('admin.test.store') }}" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        الاسم <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                           placeholder="أدخل الاسم">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        البريد الإلكتروني <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                           placeholder="example@domain.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('phone') border-red-500 @enderror"
                           placeholder="+966 50 123 4567">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        الفئة <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('category') border-red-500 @enderror">
                        <option value="">اختر الفئة</option>
                        <option value="admin" {{ old('category') === 'admin' ? 'selected' : '' }}>مدير</option>
                        <option value="user" {{ old('category') === 'user' ? 'selected' : '' }}>مستخدم</option>
                        <option value="company" {{ old('category') === 'company' ? 'selected' : '' }}>شركة</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">الوصف</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('description') border-red-500 @enderror"
                          placeholder="أدخل وصف تفصيلي...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800">خيارات إضافية</h3>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label class="mr-2 text-sm text-slate-700">نشط</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="send_notifications" value="1" {{ old('send_notifications') ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label class="mr-2 text-sm text-slate-700">إرسال الإشعارات</label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-6 border-t border-gray-200">
                <button type="submit" name="action" value="save"
                        class="w-full lg:w-auto px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                    <i class="fas fa-save mr-2"></i>
                    حفظ البيانات
                </button>

                <button type="submit" name="action" value="save_and_continue"
                        class="w-full lg:w-auto px-6 py-3 bg-gradient-secondary text-white rounded-xl font-semibold hover-lift transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    حفظ والمتابعة
                </button>

                <button type="button" onclick="testNotifications()"
                        class="w-full lg:w-auto px-6 py-3 bg-gray-500 text-white rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                    <i class="fas fa-bell mr-2"></i>
                    تجربة الإشعارات
                </button>

                <a href="{{ route('admin.dashboard') }}"
                   class="w-full lg:w-auto px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold text-center hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    <!-- Test Notifications Section -->
    <div class="mt-8 glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <h3 class="text-xl font-bold gradient-text mb-4">تجربة الإشعارات</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4">
            <button onclick="showNotification('success', 'نجح!', 'تمت العملية بنجاح')"
                    class="px-4 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                <i class="fas fa-check mr-2"></i>
                رسالة نجاح
            </button>

            <button onclick="showNotification('error', 'خطأ!', 'حدث خطأ في النظام')"
                    class="px-4 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                رسالة خطأ
            </button>

            <button onclick="showNotification('warning', 'تحذير!', 'يرجى مراجعة البيانات')"
                    class="px-4 py-3 bg-yellow-500 text-white rounded-xl font-semibold hover:bg-yellow-600 transition-colors">
                <i class="fas fa-exclamation-circle mr-2"></i>
                رسالة تحذير
            </button>

            <button onclick="showNotification('info', 'معلومة', 'معلومة مفيدة للمستخدم')"
                    class="px-4 py-3 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                <i class="fas fa-info-circle mr-2"></i>
                رسالة معلومات
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function testNotifications() {
        // محاكاة إشعارات متتالية
        setTimeout(() => showNotification('info', 'بدء المعالجة', 'جاري تجهيز البيانات...'), 100);
        setTimeout(() => showNotification('warning', 'تحذير', 'تم العثور على بعض التحذيرات'), 2000);
        setTimeout(() => showNotification('success', 'اكتمل!', 'تمت معالجة جميع البيانات بنجاح'), 4000);
    }

    // Enhance form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                }
            });

            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                } else {
                    this.classList.remove('border-green-500');
                }
            });
        });
    });
</script>
@endpush
@endsection
