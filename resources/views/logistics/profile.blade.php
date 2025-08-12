@extends('layouts.main')

@section('title', 'الملف الشخصي - Link2u')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">الملف الشخصي</h1>
                <p class="text-blue-100">إدارة بياناتك الشخصية ومعلومات حسابك</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                    <div class="text-sm opacity-80">آخر تحديث</div>
                    <div class="font-semibold">{{ auth()->user()->updated_at->format('Y-m-d') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 left-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block font-bold">يوجد أخطاء في النموذج:</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 left-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
        @endif
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Profile Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">{{ auth()->user()->name }}</h3>
                        <p class="text-gray-600">{{ auth()->user()->email }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                            <i class="fas fa-check-circle ml-1"></i>
                            حساب نشط
                        </span>
                    </div>

                    <!-- Quick Stats -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">نوع الحساب</span>
                            <span class="text-sm font-medium text-blue-600">شركة لوجستية</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">تاريخ التسجيل</span>
                            <span class="text-sm font-medium">{{ auth()->user()->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">رقم الشركة</span>
                            <span class="text-sm font-medium">{{ auth()->user()->company_registration ?? 'غير محدد' }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('logistics.dashboard') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-dashboard ml-2"></i>
                            العودة للوحة التحكم
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-user-edit text-blue-600 ml-2"></i>
                            المعلومات الشخصية
                        </h3>
                    </div>

                    <form action="{{ route('logistics.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                                <input type="text"
                                       name="name"
                                       value="{{ auth()->user()->name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email"
                                       name="email"
                                       value="{{ auth()->user()->email }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ auth()->user()->phone }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                                       placeholder="+966501234567">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة</label>
                                <input type="text"
                                       name="company_name"
                                       value="{{ auth()->user()->company_name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_name') border-red-500 @enderror"
                                       required>
                                @error('company_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم السجل التجاري</label>
                            <input type="text"
                                   name="company_registration"
                                   value="{{ auth()->user()->company_registration }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_registration') border-red-500 @enderror"
                                   placeholder="1010123456">
                            @error('company_registration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors font-medium">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-lock text-blue-600 ml-2"></i>
                            تغيير كلمة المرور
                        </h3>
                    </div>

                    <form action="{{ route('logistics.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية</label>
                            <input type="password"
                                   name="current_password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror"
                                   required>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                                <input type="password"
                                       name="password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                       required>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       required>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-yellow-400 mt-0.5 ml-2"></i>
                                <div class="text-sm text-yellow-700">
                                    <p class="font-medium">متطلبات كلمة المرور:</p>
                                    <ul class="mt-1 list-disc list-inside space-y-1">
                                        <li>8 أحرف على الأقل</li>
                                        <li>حرف كبير وحرف صغير</li>
                                        <li>رقم واحد على الأقل</li>
                                        <li>رمز خاص (!@#$%^&*)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition-colors font-medium">
                                <i class="fas fa-key ml-2"></i>
                                تحديث كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Settings -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-cog text-blue-600 ml-2"></i>
                            إعدادات الحساب
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <!-- Email Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-800">إشعارات البريد الإلكتروني</h4>
                                <p class="text-sm text-gray-600">تلقي إشعارات حول طلبات التمويل والفواتير</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- SMS Notifications -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-800">إشعارات الرسائل النصية</h4>
                                <p class="text-sm text-gray-600">تلقي رسائل نصية للعمليات المهمة</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <!-- Two Factor Authentication -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-800">المصادقة الثنائية</h4>
                                <p class="text-sm text-gray-600">حماية إضافية لحسابك</p>
                            </div>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                                <i class="fas fa-shield-alt ml-1"></i>
                                تفعيل
                            </button>
                        </div>
                    </div>
                </div>

                <!-- User Orders Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-shopping-cart text-blue-600 ml-2"></i>
                            طلبات المنتجات الحديثة
                        </h3>
                        <a href="{{ route('user.purchase.my_orders') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                            عرض الكل
                            <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>

                    @if($productOrders && $productOrders->count() > 0)
                        <div class="space-y-3">
                            @foreach($productOrders->take(5) as $order)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <h4 class="font-medium text-gray-800">{{ $order->product->name ?? 'منتج محذوف' }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $order->status === 'pending' ? 'في الانتظار' :
                                                   ($order->status === 'confirmed' ? 'مؤكد' :
                                                   ($order->status === 'cancelled' ? 'ملغي' : $order->status)) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            الكمية: {{ $order->quantity }} |
                                            السعر: {{ number_format($order->unit_price) }} ر.س |
                                            الإجمالي: {{ number_format($order->total_amount) }} ر.س
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $order->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a href="{{ route('user.purchase.order_details', $order->id) }}"
                                           class="text-blue-600 hover:text-blue-700 text-sm">
                                            <i class="fas fa-eye"></i>
                                            عرض
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                            <p>لا توجد طلبات منتجات حتى الآن</p>
                            <a href="{{ route('store') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                تصفح المنتجات
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Payment Requests Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-credit-card text-green-600 ml-2"></i>
                            طلبات الدفع الحديثة
                        </h3>
                    </div>

                    @if($paymentRequests && $paymentRequests->count() > 0)
                        <div class="space-y-3">
                            @foreach($paymentRequests->take(5) as $payment)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <h4 class="font-medium text-gray-800">{{ $payment->request_number }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status === 'approved') bg-green-100 text-green-800
                                                @elseif($payment->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $payment->status === 'pending' ? 'في الانتظار' :
                                                   ($payment->status === 'approved' ? 'موافق عليه' :
                                                   ($payment->status === 'rejected' ? 'مرفوض' : $payment->status)) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            النوع: {{ $payment->payment_type === 'product_order' ? 'طلب منتج' :
                                                     ($payment->payment_type === 'invoice' ? 'فاتورة' : $payment->payment_type) }} |
                                            المبلغ: {{ number_format($payment->amount) }} ر.س |
                                            الطريقة: {{ $payment->payment_method === 'bank_transfer' ? 'تحويل بنكي' : 'محفظة إلكترونية' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $payment->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-credit-card text-4xl mb-3"></i>
                            <p>لا توجد طلبات دفع حتى الآن</p>
                        </div>
                    @endif
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-red-700">
                            <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                            منطقة الخطر
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-red-800">إلغاء تفعيل الحساب</h4>
                                <p class="text-sm text-red-600">تجميد الحساب مؤقتاً (يمكن استعادته لاحقاً)</p>
                            </div>
                            <button class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition-colors text-sm font-medium">
                                تجميد الحساب
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div>
                                <h4 class="font-medium text-red-800">حذف الحساب نهائياً</h4>
                                <p class="text-sm text-red-600">حذف جميع البيانات نهائياً (لا يمكن التراجع)</p>
                            </div>
                            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors text-sm font-medium" onclick="confirmDelete()">
                                حذف الحساب
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('هل أنت متأكد من رغبتك في حذف الحساب نهائياً؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        if (confirm('تأكيد أخير: سيتم حذف جميع البيانات والطلبات والفواتير المرتبطة بحسابك. هل تريد المتابعة؟')) {
            // Here you would submit a delete form
            alert('تم إرسال طلب حذف الحساب. سيتم التواصل معك قريباً.');
        }
    }
}

// Auto-save notification settings
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // Here you would send an AJAX request to save the setting
        console.log('Setting updated:', this.checked);
    });
});
</script>
@endpush
@endsection
