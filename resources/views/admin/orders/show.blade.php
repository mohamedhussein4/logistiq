@extends('layouts.admin')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب')
@section('page-description', 'عرض تفاصيل طلب الشراء وإدارة حالته')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">طلب رقم #{{ $order->id ?? '12345' }}</h1>
                <p class="text-slate-600">تفاصيل طلب الشراء من {{ $order->user->name ?? 'أحمد محمد السعود' }}</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $statusNames = [
                        'pending' => 'معلق',
                        'processing' => 'قيد التجهيز',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التسليم',
                        'cancelled' => 'ملغي'
                    ];
                    $currentStatus = $order->status ?? 'pending';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$currentStatus] }}">
                    {{ $statusNames[$currentStatus] }}
                </span>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات العميل</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">اسم العميل</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $order->user->name ?? 'أحمد محمد السعود' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                <a href="mailto:{{ $order->user->email ?? 'ahmed@example.com' }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $order->user->email ?? 'ahmed@example.com' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $order->user->phone ?? '+966 50 123 4567' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الطلب</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $order->created_at->format('Y-m-d H:i') ?? '2024-01-15 10:30' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">منتجات الطلب</h3>

                @php
                    $orderItems = $order->items ?? [
                        [
                            'id' => 1,
                            'product_name' => 'جهاز تتبع GPS متقدم',
                            'product_sku' => 'GPS-PRO-001',
                            'quantity' => 2,
                            'unit_price' => 599.99,
                            'total_price' => 1199.98,
                            'product_image' => 'products/gps-tracker.jpg'
                        ],
                        [
                            'id' => 2,
                            'product_name' => 'حساس درجة الحرارة',
                            'product_sku' => 'TEMP-SENSOR-001',
                            'quantity' => 5,
                            'unit_price' => 149.99,
                            'total_price' => 749.95,
                            'product_image' => 'products/temp-sensor.jpg'
                        ]
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($orderItems as $item)
                    <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="w-16 h-16 bg-gradient-primary rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-cube text-white text-xl"></i>
                        </div>

                        <div class="flex-1 grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <div class="lg:col-span-2">
                                <h4 class="font-bold text-slate-900">{{ $item['product_name'] }}</h4>
                                <p class="text-sm text-slate-600">SKU: {{ $item['product_sku'] }}</p>
                            </div>

                            <div class="text-center">
                                <div class="text-lg font-black text-slate-900">{{ $item['quantity'] }}</div>
                                <div class="text-sm text-slate-500">الكمية</div>
                            </div>

                            <div class="text-center">
                                <div class="text-lg font-black text-slate-900">{{ number_format($item['total_price']) }} ريال</div>
                                <div class="text-sm text-slate-500">{{ number_format($item['unit_price']) }} ريال للقطعة</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 p-4 bg-white border border-gray-200 rounded-xl">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-600">المجموع الفرعي:</span>
                            <span class="font-semibold">{{ number_format($order->subtotal ?? 1949.93) }} ريال</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-600">الشحن:</span>
                            <span class="font-semibold">{{ number_format($order->shipping_cost ?? 50.00) }} ريال</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-600">ضريبة القيمة المضافة (15%):</span>
                            <span class="font-semibold">{{ number_format($order->tax_amount ?? 299.99) }} ريال</span>
                        </div>

                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="text-lg font-bold text-slate-900">المجموع الكلي:</span>
                            <span class="text-lg font-black text-slate-900">{{ number_format($order->original_amount ?? 2299.92) }} ريال</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشحن</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان التسليم</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $order->shipping_address ?? 'الرياض، حي النخيل، شارع الملك فهد، مبنى رقم 123' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الشحن</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $order->shipping_method ?? 'شحن سريع (2-3 أيام عمل)' }}
                        </div>
                    </div>
                </div>

                @if($order->tracking_number ?? 'TRK123456789')
                <div class="mt-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم التتبع</label>
                    <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 font-mono">
                        {{ $order->tracking_number ?? 'TRK123456789' }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Actions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">إجراءات الطلب</h4>

                <div class="space-y-3">
                    @if($currentStatus === 'pending')
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                            <i class="fas fa-cogs mr-2"></i>
                            بدء التجهيز
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            إلغاء الطلب
                        </button>
                    </form>
                    @endif

                    @if($currentStatus === 'processing')
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="shipped">
                        <button type="submit" class="w-full px-4 py-2 bg-purple-500 text-white rounded-xl font-semibold hover:bg-purple-600 transition-colors">
                            <i class="fas fa-shipping-fast mr-2"></i>
                            تجهيز للشحن
                        </button>
                    </form>
                    @endif

                    @if($currentStatus === 'shipped')
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id ?? 1) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            تأكيد التسليم
                        </button>
                    </form>
                    @endif

                    <button onclick="printOrder()" class="w-full px-4 py-2 bg-gray-500 text-white rounded-xl font-semibold hover:bg-gray-600 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        طباعة الطلب
                    </button>
                </div>
            </div>

            <!-- Order Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">معلومات الطلب</h4>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">رقم الطلب:</span>
                        <span class="font-semibold">#{{ $order->id ?? '12345' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">تاريخ الطلب:</span>
                        <span class="font-semibold">{{ $order->created_at->format('Y-m-d') ?? '2024-01-15' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">طريقة الدفع:</span>
                        <span class="font-semibold">{{ $order->payment_method ?? 'بطاقة ائتمان' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">حالة الدفع:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                            {{ $order->payment_status ?? 'مدفوع' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">عدد المنتجات:</span>
                        <span class="font-semibold">{{ $order->items_count ?? count($orderItems) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">تاريخ الطلب</h4>

                @php
                    $timeline = [
                        [
                            'status' => 'pending',
                            'title' => 'تم إنشاء الطلب',
                            'description' => 'تم استلام الطلب وبدء المعالجة',
                            'timestamp' => '2024-01-15 10:30',
                            'completed' => true
                        ],
                        [
                            'status' => 'processing',
                            'title' => 'قيد التجهيز',
                            'description' => 'جاري تجهيز المنتجات للشحن',
                            'timestamp' => '2024-01-15 14:20',
                            'completed' => in_array($currentStatus, ['processing', 'shipped', 'delivered'])
                        ],
                        [
                            'status' => 'shipped',
                            'title' => 'تم الشحن',
                            'description' => 'تم تسليم الطلب لشركة الشحن',
                            'timestamp' => '2024-01-16 09:15',
                            'completed' => in_array($currentStatus, ['shipped', 'delivered'])
                        ],
                        [
                            'status' => 'delivered',
                            'title' => 'تم التسليم',
                            'description' => 'وصل الطلب للعميل بنجاح',
                            'timestamp' => '2024-01-17 16:45',
                            'completed' => $currentStatus === 'delivered'
                        ]
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($timeline as $event)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $event['completed'] ? 'bg-green-500' : 'bg-gray-300' }}">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <h5 class="font-semibold text-slate-900 {{ !$event['completed'] ? 'text-gray-500' : '' }}">{{ $event['title'] }}</h5>
                            <p class="text-sm text-slate-600 {{ !$event['completed'] ? 'text-gray-400' : '' }}">{{ $event['description'] }}</p>
                            @if($event['completed'])
                            <p class="text-xs text-slate-500 mt-1">{{ $event['timestamp'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function printOrder() {
        window.open('/admin/orders/{{ $order->id ?? 1 }}/print', '_blank');
    }
</script>
@endpush
@endsection
