@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم')
@section('page-title', 'تفاصيل المستخدم')
@section('page-description', 'عرض تفاصيل المستخدم المحدد')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">مستخدم: {{ $user->name }}</h1>
                <p class="text-slate-600">تفاصيل المستخدم وحسابه</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' :
                       ($user->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                       ($user->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ ucfirst($user->status) }}
                </span>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">المعلومات الأساسية</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الاسم الكامل</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $user->phone ?? 'غير محدد' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع الحساب</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            @php
                                $userTypes = [
                                    'admin' => 'مدير',
                                    'logistics' => 'شركة لوجيستية',
                                    'service_company' => 'شركة طالبة للخدمة',
                                    'regular' => 'مستخدم عادي'
                                ];
                            @endphp
                            {{ $userTypes[$user->user_type] ?? $user->user_type }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ التسجيل</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $user->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">آخر تحديث</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $user->updated_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>

                @if($user->company_name)
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">اسم الشركة</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $user->company_name }}
                    </div>
                </div>
                @endif

                @if($user->company_registration)
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم السجل التجاري</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $user->company_registration }}
                    </div>
                </div>
                @endif

                @if($user->admin_notes)
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات الإدارة</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $user->admin_notes }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Company Information -->
            @if($user->user_type === 'logistics' && $user->logisticsCompany)
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة اللوجيستية</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الرصيد المتاح</label>
                        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-900 text-xl font-bold">
                            {{ number_format($user->logisticsCompany->available_balance) }} ريال
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي التمويل</label>
                        <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-xl font-bold">
                            {{ number_format($user->logisticsCompany->total_funded) }} ريال
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي الطلبات</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $user->logisticsCompany->total_requests }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحد الائتماني</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ number_format($user->logisticsCompany->credit_limit ?? 0) }} ريال
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($user->user_type === 'service_company' && $user->serviceCompany)
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشركة الطالبة للخدمة</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي المستحقات</label>
                        <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-900 text-xl font-bold">
                            {{ number_format($user->serviceCompany->total_outstanding) }} ريال
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">إجمالي المدفوع</label>
                        <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-900 text-xl font-bold">
                            {{ number_format($user->serviceCompany->total_paid) }} ريال
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">حالة الدفع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            @php
                                $paymentStatuses = [
                                    'regular' => 'منتظم',
                                    'overdue' => 'متأخر',
                                    'under_review' => 'تحت المراقبة'
                                ];
                            @endphp
                            {{ $paymentStatuses[$user->serviceCompany->payment_status] ?? $user->serviceCompany->payment_status }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحد الائتماني</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ number_format($user->serviceCompany->credit_limit ?? 0) }} ريال
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Product Orders -->
            @if($user->productOrders && $user->productOrders->count() > 0)
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">طلبات المنتجات</h3>

                <div class="space-y-4">
                    @foreach($user->productOrders->take(5) as $order)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-800">{{ $order->product->name ?? 'منتج محذوف' }}</h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($order->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $order->status === 'pending' ? 'في الانتظار' :
                                           ($order->status === 'confirmed' ? 'مؤكد' :
                                           ($order->status === 'cancelled' ? 'ملغي' : $order->status)) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">الكمية:</span> {{ $order->quantity }}
                                    </div>
                                    <div>
                                        <span class="font-medium">السعر:</span> {{ number_format($order->unit_price) }} ر.س
                                    </div>
                                    <div>
                                        <span class="font-medium">الإجمالي:</span> {{ number_format($order->total_amount) }} ر.س
                                    </div>
                                    <div>
                                        <span class="font-medium">التاريخ:</span> {{ $order->created_at->format('Y-m-d') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- User Actions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">إجراءات المستخدم</h3>

                <div class="space-y-3">
                    <!-- Status Update -->
                    <form method="POST" action="{{ route('admin.users.update_status', $user->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">تحديث الحالة</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>معلق</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                            <i class="fas fa-save ml-2"></i>
                            تحديث الحالة
                        </button>
                    </form>

                    <!-- Edit User -->
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل المستخدم
                    </a>

                    <!-- Delete User -->
                    @if(!$user->isAdmin())
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="w-full" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            <i class="fas fa-trash ml-2"></i>
                            حذف المستخدم
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">إحصائيات سريعة</h3>

                <div class="space-y-4">
                    @if($user->user_type === 'logistics' && isset($stats['total_funding_requests']))
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">طلبات التمويل:</span>
                        <span class="font-semibold">{{ $stats['total_funding_requests'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">الطلبات الموافق عليها:</span>
                        <span class="font-semibold">{{ $stats['approved_requests'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">إجمالي التمويل:</span>
                        <span class="font-semibold">{{ number_format($stats['total_funded']) }} ر.س</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">الرصيد المتاح:</span>
                        <span class="font-semibold">{{ number_format($stats['available_balance']) }} ر.س</span>
                    </div>
                    @endif

                    @if($user->user_type === 'service_company' && isset($stats['total_invoices']))
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">إجمالي الفواتير:</span>
                        <span class="font-semibold">{{ $stats['total_invoices'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">الفواتير المتأخرة:</span>
                        <span class="font-semibold">{{ $stats['overdue_invoices'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">إجمالي المستحقات:</span>
                        <span class="font-semibold">{{ number_format($stats['total_outstanding']) }} ر.س</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">إجمالي المدفوع:</span>
                        <span class="font-semibold">{{ number_format($stats['total_paid']) }} ر.س</span>
                    </div>
                    @endif

                    @if($user->productOrders)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">طلبات المنتجات:</span>
                        <span class="font-semibold">{{ $user->productOrders->count() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
