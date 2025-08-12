@extends('layouts.main')

@section('title', 'طلباتي')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-secondary-800 mb-4">طلباتي</h1>
                <p class="text-secondary-600">تابع حالة طلباتك ومدفوعاتك</p>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="glass rounded-xl p-4 text-center hover-lift">
                    <div class="text-2xl font-bold text-primary-600">{{ $orders->total() }}</div>
                    <div class="text-sm text-secondary-600">إجمالي الطلبات</div>
                </div>
                <div class="glass rounded-xl p-4 text-center hover-lift">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $orders->where('status', 'delivered')->count() }}
                    </div>
                    <div class="text-sm text-secondary-600">مكتملة</div>
                </div>
                <div class="glass rounded-xl p-4 text-center hover-lift">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $orders->whereIn('status', ['pending_payment', 'processing'])->count() }}
                    </div>
                    <div class="text-sm text-secondary-600">قيد المعالجة</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                    <div class="glass rounded-2xl shadow-soft hover-lift p-6 transition-all animate-slide-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <!-- معلومات الطلب -->
                            <div class="flex-1">
                                <div class="flex items-center mb-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center ml-4 shadow-soft">
                                        <i class="fas fa-shopping-bag text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-secondary-800">{{ $order->product->name }}</h3>
                                        <p class="text-secondary-600">طلب رقم: <span class="font-semibold text-primary-600">#{{ $order->id }}</span></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                    <div class="bg-primary-50 p-3 rounded-lg border border-primary-100">
                                        <span class="text-sm text-secondary-600 flex items-center">
                                            <i class="fas fa-sort-numeric-up text-primary-600 ml-1"></i>
                                            الكمية:
                                        </span>
                                        <div class="font-semibold text-secondary-800">{{ $order->quantity }}</div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg border border-green-100">
                                        <span class="text-sm text-secondary-600 flex items-center">
                                            <i class="fas fa-calculator text-green-600 ml-1"></i>
                                            المجموع:
                                        </span>
                                        <div class="font-semibold text-green-600">{{ number_format($order->total_amount, 2) }} ريال</div>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                                        <span class="text-sm text-secondary-600 flex items-center">
                                            <i class="fas fa-calendar text-primary-600 ml-1"></i>
                                            التاريخ:
                                        </span>
                                        <div class="font-semibold text-secondary-800">{{ $order->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded-lg border border-purple-100">
                                        <span class="text-sm text-secondary-600 flex items-center">
                                            <i class="fas fa-info-circle text-primary-600 ml-1"></i>
                                            الحالة:
                                        </span>
                                        <div>
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
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusNames[$order->status] ?? $order->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- حالة الدفع -->
                                @if($order->paymentRequests->count() > 0)
                                    @php $paymentRequest = $order->paymentRequests->first(); @endphp
                                    <div class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="text-sm text-secondary-600 ml-2 flex items-center">
                                            <i class="fas fa-credit-card text-primary-600 ml-1"></i>
                                            حالة الدفع:
                                        </span>
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
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $paymentStatusClasses[$paymentRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $paymentStatusNames[$paymentRequest->status] ?? $paymentRequest->status }}
                                        </span>
                                        <span class="text-xs text-secondary-500 mr-2">({{ $paymentRequest->request_number }})</span>
                                    </div>
                                @endif
                            </div>

                            <!-- إجراءات -->
                            <div class="flex space-x-3 space-x-reverse mt-4 lg:mt-0">
                                <a href="{{ route('user.purchase.order_details', $order->id) }}"
                                   class="bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-all hover-lift shadow-soft">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض التفاصيل
                                </a>

                                @if($order->status === 'pending_payment' && $order->paymentRequests->count() > 0)
                                    @php $paymentRequest = $order->paymentRequests->first(); @endphp
                                    @if($paymentRequest->status === 'pending')
                                        <a href="{{ route('user.purchase.success', $order->id) }}"
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all hover-lift shadow-soft">
                                            <i class="fas fa-upload ml-1"></i>
                                            رفع إثبات الدفع
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- لا توجد طلبات -->
                <div class="text-center py-16 glass rounded-2xl shadow-soft">
                    <div class="w-32 h-32 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center mx-auto mb-8 animate-float">
                        <i class="fas fa-shopping-bag text-primary-600 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-secondary-600 mb-4">لا توجد طلبات بعد</h3>
                    <p class="text-secondary-500 mb-8 max-w-md mx-auto">لم تقم بإنشاء أي طلبات حتى الآن. ابدأ بتصفح منتجاتنا واختر ما يناسبك.</p>
                    <a href="{{ route('store') }}"
                       class="inline-flex items-center bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-8 py-4 rounded-xl font-semibold transition-all hover-lift shadow-glow">
                        <i class="fas fa-shopping-cart ml-2"></i>
                        تصفح المنتجات
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
