@extends('layouts.main')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between mb-8">
                <div class="text-center md:text-right mb-4 md:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-secondary-800 mb-2">تفاصيل الطلب #{{ $order->id }}</h1>
                    <p class="text-secondary-600">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <a href="{{ route('user.purchase.my_orders') }}" 
                   class="bg-secondary-200 hover:bg-secondary-300 text-secondary-700 px-6 py-3 rounded-xl font-medium transition-all hover-lift flex items-center">
                    <i class="fas fa-arrow-left ml-2"></i>
                    العودة للطلبات
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- معلومات الطلب -->
            <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up">
                <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                    <i class="fas fa-shopping-bag text-primary-600 ml-2"></i>
                    معلومات الطلب
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- معلومات المنتج -->
                    <div class="space-y-4">
                        <div class="flex items-center bg-primary-50 p-4 rounded-xl border border-primary-100">
                            @if($order->product->image)
                                <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg ml-4 shadow-soft">
                            @else
                                <div class="w-20 h-20 bg-secondary-200 rounded-lg flex items-center justify-center ml-4">
                                    <i class="fas fa-box text-secondary-400 text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-xl font-semibold text-secondary-800">{{ $order->product->name }}</h3>
                                @if($order->product->category)
                                    <p class="text-secondary-600 flex items-center mt-1">
                                        <i class="fas fa-folder text-primary-600 ml-1"></i>
                                        {{ $order->product->category->name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($order->product->description)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <span class="text-sm font-medium text-secondary-700 flex items-center mb-2">
                                    <i class="fas fa-info-circle text-primary-600 ml-1"></i>
                                    وصف المنتج:
                                </span>
                                <p class="text-secondary-600">{{ $order->product->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- تفاصيل الطلب -->
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <span class="text-sm font-medium text-blue-700 flex items-center">
                                    <i class="fas fa-sort-numeric-up text-primary-600 ml-1"></i>
                                    الكمية:
                                </span>
                                <div class="text-lg font-semibold text-secondary-800">{{ $order->quantity }}</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
                                <span class="text-sm font-medium text-purple-700 flex items-center">
                                    <i class="fas fa-tag text-primary-600 ml-1"></i>
                                    سعر الوحدة:
                                </span>
                                <div class="text-lg font-semibold text-secondary-800">{{ number_format($order->price, 2) }} ريال</div>
                            </div>
                        </div>
                        
                        <div class="border-t border-secondary-200 pt-4">
                            <div class="flex justify-between items-center p-4 bg-green-50 rounded-xl border border-green-100">
                                <span class="text-lg font-bold text-secondary-800 flex items-center">
                                    <i class="fas fa-calculator text-green-600 ml-2"></i>
                                    المجموع:
                                </span>
                                <span class="text-2xl font-bold text-green-600">{{ number_format($order->total_amount, 2) }} ريال</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <span class="text-sm font-medium text-secondary-700 flex items-center">
                                    <i class="fas fa-calendar text-primary-600 ml-1"></i>
                                    تاريخ الطلب:
                                </span>
                                <div class="font-semibold text-secondary-800">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            
                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                <span class="text-sm font-medium text-yellow-700 flex items-center">
                                    <i class="fas fa-info-circle text-primary-600 ml-1"></i>
                                    حالة الطلب:
                                </span>
                                <div class="mt-1">
                                    @php
                                        $statusClasses = [
                                            'pending_payment' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusNames = [
                                            'pending_payment' => 'في انتظار الدفع',
                                            'processing' => 'قيد المعالجة',
                                            'shipped' => 'تم الشحن',
                                            'delivered' => 'تم التسليم',
                                            'cancelled' => 'ملغي',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusNames[$order->status] ?? $order->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($order->notes)
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <span class="text-sm font-medium text-blue-700 flex items-center">
                                <i class="fas fa-sticky-note text-primary-600 ml-1"></i>
                                ملاحظات:
                            </span>
                            <p class="text-secondary-600 mt-1">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- معلومات الدفع -->
            @if($order->paymentRequests->count() > 0)
                @php $paymentRequest = $order->paymentRequests->first(); @endphp
                <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up" style="animation-delay: 0.2s;">
                    <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                        <i class="fas fa-credit-card text-primary-600 ml-2"></i>
                        معلومات الدفع
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-primary-50 p-4 rounded-xl border border-primary-100">
                                <span class="text-sm font-medium text-primary-700 flex items-center">
                                    <i class="fas fa-receipt text-primary-600 ml-1"></i>
                                    رقم طلب الدفع:
                                </span>
                                <div class="font-semibold text-primary-600">{{ $paymentRequest->request_number }}</div>
                            </div>
                            
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <span class="text-sm font-medium text-blue-700 flex items-center">
                                    <i class="fas fa-credit-card text-primary-600 ml-1"></i>
                                    طريقة الدفع:
                                </span>
                                <div class="font-semibold text-secondary-800">
                                    @if($paymentRequest->payment_method === 'bank_transfer')
                                        <i class="fas fa-university text-primary-600 ml-1"></i>
                                        تحويل بنكي
                                    @else
                                        <i class="fas fa-mobile-alt text-green-600 ml-1"></i>
                                        محفظة إلكترونية
                                    @endif
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                                <span class="text-sm font-medium text-green-700 flex items-center">
                                    <i class="fas fa-money-bill text-primary-600 ml-1"></i>
                                    المبلغ:
                                </span>
                                <div class="text-lg font-bold text-green-600">{{ number_format($paymentRequest->amount, 2) }} ريال</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                <span class="text-sm font-medium text-yellow-700 flex items-center">
                                    <i class="fas fa-info-circle text-primary-600 ml-1"></i>
                                    حالة الدفع:
                                </span>
                                <div class="mt-1">
                                    @php
                                        $paymentStatusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $paymentStatusNames = [
                                            'pending' => 'في انتظار الدفع',
                                            'processing' => 'قيد المراجعة',
                                            'completed' => 'مكتمل',
                                            'failed' => 'فشل',
                                            'cancelled' => 'ملغي',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatusClasses[$paymentRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $paymentStatusNames[$paymentRequest->status] ?? $paymentRequest->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <span class="text-sm font-medium text-secondary-700 flex items-center">
                                    <i class="fas fa-calendar text-primary-600 ml-1"></i>
                                    تاريخ الإنشاء:
                                </span>
                                <div class="font-semibold text-secondary-800">{{ $paymentRequest->created_at->format('Y-m-d H:i') }}</div>
                            </div>

                            @if($paymentRequest->processed_at)
                            <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
                                <span class="text-sm font-medium text-purple-700 flex items-center">
                                    <i class="fas fa-check text-primary-600 ml-1"></i>
                                    تاريخ المعالجة:
                                </span>
                                <div class="font-semibold text-secondary-800">{{ $paymentRequest->processed_at->format('Y-m-d H:i') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($paymentRequest->payment_notes)
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <span class="text-sm font-medium text-blue-700 flex items-center">
                            <i class="fas fa-sticky-note text-primary-600 ml-1"></i>
                            ملاحظات الدفع:
                        </span>
                        <p class="text-secondary-600 mt-1">{{ $paymentRequest->payment_notes }}</p>
                    </div>
                    @endif

                    @if($paymentRequest->admin_notes)
                    <div class="mt-4 p-4 bg-orange-50 rounded-xl border border-orange-100">
                        <span class="text-sm font-medium text-orange-700 flex items-center">
                            <i class="fas fa-user-shield text-primary-600 ml-1"></i>
                            ملاحظات الإدارة:
                        </span>
                        <p class="text-orange-600 mt-1">{{ $paymentRequest->admin_notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- إثباتات الدفع -->
                @if($paymentRequest->paymentProofs->count() > 0)
                <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up" style="animation-delay: 0.4s;">
                    <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                        <i class="fas fa-folder-open text-primary-600 ml-2"></i>
                        إثباتات الدفع المرفوعة
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($paymentRequest->paymentProofs as $proof)
                        <div class="border border-secondary-200 rounded-xl p-4 hover-lift transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 space-x-reverse">
                                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                                        @if(in_array($proof->file_type, ['jpg', 'jpeg', 'png']))
                                            <i class="fas fa-image text-primary-600 text-xl"></i>
                                        @elseif($proof->file_type === 'pdf')
                                            <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                        @else
                                            <i class="fas fa-file text-secondary-600 text-xl"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-secondary-800">{{ $proof->file_name }}</div>
                                        <div class="text-sm text-secondary-600">
                                            {{ $proof->created_at->format('Y-m-d H:i') }} 
                                            • {{ number_format($proof->file_size / 1024, 1) }} KB
                                        </div>
                                        @if($proof->description)
                                            <div class="text-sm text-secondary-600 mt-1">{{ $proof->description }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($proof->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($proof->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        @if($proof->status === 'pending') في انتظار المراجعة
                                        @elseif($proof->status === 'approved') مقبول
                                        @else مرفوض
                                        @endif
                                    </span>
                                    <a href="{{ asset('storage/' . $proof->file_path) }}" target="_blank"
                                       class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-lg text-sm transition-all hover-lift flex items-center">
                                        <i class="fas fa-eye ml-1"></i>
                                        عرض
                                    </a>
                                </div>
                            </div>
                            
                            @if($proof->status === 'rejected' && $proof->rejection_reason)
                            <div class="mt-3 p-3 bg-red-50 rounded-lg border border-red-100">
                                <span class="text-sm font-medium text-red-700 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 ml-1"></i>
                                    سبب الرفض:
                                </span>
                                <p class="text-red-600 text-sm mt-1">{{ $proof->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- إجراءات سريعة -->
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 sm:space-x-reverse">
                    @if($paymentRequest->status === 'pending')
                        <a href="{{ route('user.purchase.success', $order->id) }}" 
                           class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all hover-lift shadow-glow flex items-center justify-center">
                            <i class="fas fa-upload ml-2"></i>
                            رفع إثبات الدفع
                        </a>
                    @endif
                    
                    <a href="{{ route('user.purchase.my_orders') }}" 
                       class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-6 py-3 rounded-xl font-semibold transition-all hover-lift shadow-glow flex items-center justify-center">
                        <i class="fas fa-list ml-2"></i>
                        طلباتي
                    </a>
                    
                    <a href="{{ route('store') }}" 
                       class="flex-1 bg-secondary-200 hover:bg-secondary-300 text-secondary-700 px-6 py-3 rounded-xl font-semibold transition-all hover-lift flex items-center justify-center">
                        <i class="fas fa-shopping-cart ml-2"></i>
                        المتجر
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection