@extends('layouts.main')

@section('title', 'تسجيل شركة طالبة للخدمة')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">مرحباً بكم في منصة {{ \App\Models\Setting::get('app_name', 'Link2U') }}</h1>
            <p class="text-gray-600">إنشاء حساب جديد للشركة الطالبة للخدمة</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- معلومات الطلب -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-3 rounded-full ml-4">
                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">تفاصيل الطلب</h3>
                            <p class="text-sm text-gray-500">معلومات طلب التمويل المرتبط بحسابكم</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم شركتكم</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $clientDebt->company_name }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">الشخص المسؤول</label>
                            <p class="text-gray-900">{{ $clientDebt->contact_person }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                                <p class="text-gray-900">{{ $clientDebt->email }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                                <p class="text-gray-900">{{ $clientDebt->phone }}</p>
                            </div>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <label class="block text-sm font-medium text-green-700 mb-1">المبلغ المستحق</label>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($clientDebt->amount) }} ر.س</p>
                        </div>

                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                            <label class="block text-sm font-medium text-orange-700 mb-1">تاريخ الاستحقاق</label>
                            <p class="text-lg font-semibold text-orange-600">
                                {{ \Carbon\Carbon::parse($clientDebt->due_date)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-blue-600 mt-1 ml-3"></i>
                            <div>
                                <h4 class="font-medium text-blue-900 mb-1">معلومة مهمة</h4>
                                <p class="text-sm text-blue-700">
                                    بإنشاء حسابكم، ستتمكنون من مراجعة تفاصيل الفاتورة وإتمام عملية السداد بسهولة.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- نموذج التسجيل -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-3 rounded-full ml-4">
                            <i class="fas fa-user-plus text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">إنشاء حساب جديد</h3>
                            <p class="text-sm text-gray-500">يرجى ملء البيانات المطلوبة بدقة</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                                <h4 class="font-medium text-red-800">يوجد أخطاء في البيانات:</h4>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('service-company.register') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="client_debt_id" value="{{ $clientDebt->id }}">

                        <!-- كلمة المرور -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock ml-2 text-gray-400"></i>
                                كلمة المرور
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock ml-2 text-gray-400"></i>
                                تأكيد كلمة المرور
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <!-- عنوان الشركة -->
                        <div>
                            <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt ml-2 text-gray-400"></i>
                                عنوان الشركة *
                            </label>
                            <textarea id="company_address"
                                      name="company_address"
                                      rows="3"
                                      required
                                      placeholder="يرجى إدخال العنوان الكامل للشركة"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('company_address') border-red-300 @enderror">{{ old('company_address') }}</textarea>
                            @error('company_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- السجل التجاري -->
                        <div>
                            <label for="commercial_register" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-certificate ml-2 text-gray-400"></i>
                                رقم السجل التجاري
                            </label>
                            <input type="text"
                                   id="commercial_register"
                                   name="commercial_register"
                                   placeholder="اختياري"
                                   value="{{ old('commercial_register') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('commercial_register') border-red-300 @enderror">
                            @error('commercial_register')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الرقم الضريبي -->
                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-receipt ml-2 text-gray-400"></i>
                                الرقم الضريبي
                            </label>
                            <input type="text"
                                   id="tax_number"
                                   name="tax_number"
                                   placeholder="اختياري"
                                   value="{{ old('tax_number') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tax_number') border-red-300 @enderror">
                            @error('tax_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الحساب البنكي -->
                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-university ml-2 text-gray-400"></i>
                                رقم الحساب البنكي
                            </label>
                            <input type="text"
                                   id="bank_account"
                                   name="bank_account"
                                   placeholder="اختياري - للمدفوعات المستقبلية"
                                   value="{{ old('bank_account') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('bank_account') border-red-300 @enderror">
                            @error('bank_account')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الموافقة على الشروط والأحكام -->
                        <div class="flex items-start">
                            <input type="checkbox"
                                   id="agree_terms"
                                   name="agree_terms"
                                   required
                                   class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 @error('agree_terms') border-red-300 @enderror">
                            <label for="agree_terms" class="ml-2 text-sm text-gray-700">
                                أوافق على
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">الشروط والأحكام</a>
                                و
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">سياسة الخصوصية</a>
                                الخاصة بمنصة LogistiQ
                            </label>
                            @error('agree_terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- زر التسجيل -->
                        <div>
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-4 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-user-check ml-2"></i>
                                إنشاء الحساب والمتابعة
                            </button>
                        </div>
                    </form>

                    <!-- معلومات إضافية -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-600 mt-1 ml-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-1">أمان وحماية</h4>
                                <p class="text-sm text-gray-600">
                                    جميع بياناتكم محمية ومشفرة بأحدث تقنيات الأمان، ولن يتم مشاركتها مع أطراف خارجية.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الدعم -->
            <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">هل تحتاجون للمساعدة؟</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-phone text-blue-600 ml-2"></i>
                            <span class="text-gray-700">{{ \App\Models\Setting::get('contact_phone', '+966 11 123 4567') }}</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-600 ml-2"></i>
                            <span class="text-gray-700">{{ \App\Models\Setting::get('contact_support_email', config('mail.from.address')) }}</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-clock text-blue-600 ml-2"></i>
                            <span class="text-gray-700">{{ \App\Models\Setting::get('contact_working_hours', 'السبت - الخميس 8 ص - 6 م') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
