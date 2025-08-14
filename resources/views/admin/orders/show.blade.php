@extends('layouts.admin')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب')
@section('page-description', 'عرض تفاصيل طلب الشراء وإدارة حالته')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">طلب رقم #{{ $order->id }}</h1>
                <p class="text-slate-600">تفاصيل طلب الشراء من {{ $order->user->name }}</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'processing' => 'bg-indigo-100 text-indigo-800',
                        'shipped' => 'bg-purple-100 text-purple-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $statusNames = [
                        'pending' => 'معلق',
                        'confirmed' => 'مؤكد',
                        'processing' => 'قيد التجهيز',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التسليم',
                        'cancelled' => 'ملغي'
                    ];
                    $currentStatus = $order->status;
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
                                {{ $order->user->name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                <a href="mailto:{{ $order->user->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $order->user->email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $order->user->phone ?? 'غير محدد' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الطلب</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">منتجات الطلب</h3>

                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="w-16 h-16 bg-gradient-primary rounded-lg flex items-center justify-center ml-4 flex-shrink-0">
                            @if($order->product->image_path)
                                <img src="{{ asset($order->product->image_path) }}" alt="{{ $order->product->name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i class="fas fa-cube text-white text-xl"></i>
                            @endif
                        </div>

                        <div class="flex-1 grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <div class="lg:col-span-2">
                                <h4 class="font-bold text-slate-900">{{ $order->product->name }}</h4>
                                <p class="text-sm text-slate-600">SKU: {{ $order->product->sku ?? 'غير محدد' }}</p>
                                @if($order->product->category)
                                    <p class="text-sm text-slate-500">التصنيف: {{ $order->product->category->name }}</p>
                                @endif
                            </div>

                            <div class="text-center">
                                <div class="text-lg font-black text-slate-900">{{ $order->quantity }}</div>
                                <div class="text-sm text-slate-500">الكمية</div>
                            </div>

                            <div class="text-center">
                                <div class="text-lg font-black text-slate-900">{{ number_format($order->total_amount, 2) }} ريال</div>
                                <div class="text-sm text-slate-500">{{ number_format($order->unit_price, 2) }} ريال للقطعة</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mt-6 p-4 bg-white border border-gray-200 rounded-xl">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-600">المجموع الفرعي:</span>
                            <span class="font-semibold">{{ number_format($order->total_amount, 2) }} ريال</span>
                        </div>

                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="text-lg font-bold text-slate-900">المجموع الكلي:</span>
                            <span class="text-lg font-black text-slate-900">{{ number_format($order->total_amount, 2) }} ريال</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الشحن</h3>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">عنوان التسليم</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $order->delivery_address ?? 'لم يتم تحديد عنوان التسليم' }}
                        </div>
                    </div>

                    @if($order->notes)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات الطلب</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @php
                $paymentRequest = $order->paymentRequests->first();
            @endphp
            @if($paymentRequest)
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الدفع</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم طلب الدفع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-mono">
                            {{ $paymentRequest->request_number }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الدفع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $paymentRequest->payment_method === 'bank_transfer' ? 'تحويل بنكي' : 'محفظة إلكترونية' }}
                        </div>
                    </div>
                </div>

                @if($paymentRequest->bankAccount)
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">تفاصيل الحساب البنكي</label>
                    <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="text-slate-900">
                            <strong>{{ $paymentRequest->bankAccount->bank_name }}</strong><br>
                            رقم الحساب: {{ $paymentRequest->bankAccount->account_number }}<br>
                            @if($paymentRequest->bankAccount->iban)
                                IBAN: {{ $paymentRequest->bankAccount->iban }}<br>
                            @endif
                            اسم صاحب الحساب: {{ $paymentRequest->bankAccount->account_holder }}
                        </div>
                    </div>
                </div>
                @endif

                @if($paymentRequest->paymentProofs->count() > 0)
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">إثباتات الدفع</label>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($paymentRequest->paymentProofs as $proof)
                        <div class="border border-gray-200 rounded-xl p-4 bg-white">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-slate-900">إثبات الدفع #{{ $proof->id }}</h5>
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $proof->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($proof->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $proof->status === 'approved' ? 'موافق عليه' :
                                       ($proof->status === 'rejected' ? 'مرفوض' : 'قيد المراجعة') }}
                                </span>
                            </div>

                            @if(in_array(pathinfo($proof->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <div class="mb-3">
                                    <img src="{{ asset($proof->file_path) }}"
                                         alt="إثبات الدفع"
                                         class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                         onclick="window.open('{{ asset($proof->file_path) }}', '_blank')">
                                </div>
                            @else
                                <div class="mb-3 p-4 bg-gray-100 rounded-lg text-center">
                                    <i class="fas fa-file-pdf text-red-500 text-2xl mb-2"></i>
                                    <p class="text-sm text-slate-600">{{ $proof->file_name }}</p>
                                </div>
                            @endif

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">اسم الملف:</span>
                                    <span class="font-medium">{{ $proof->file_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">الحجم:</span>
                                    <span class="font-medium">{{ number_format($proof->file_size / 1024, 2) }} KB</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">تاريخ الرفع:</span>
                                    <span class="font-medium">{{ $proof->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>

                            <div class="mt-3 flex space-x-2 space-x-reverse">
                                <a href="{{ asset($proof->file_path) }}"
                                   target="_blank"
                                   class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-colors text-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    عرض
                                </a>
                                <a href="{{ asset($proof->file_path) }}"
                                   download="{{ $proof->file_name }}"
                                   class="flex-1 px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition-colors text-center">
                                    <i class="fas fa-download mr-1"></i>
                                    تحميل
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-receipt text-gray-400 text-3xl mb-3"></i>
                    <p class="text-slate-500">لم يتم رفع إثبات دفع بعد</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Actions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">إجراءات الطلب</h4>

                <div class="space-y-3">
                    @if($currentStatus === 'pending')
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            تأكيد الطلب
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')" class="w-full px-4 py-2 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            إلغاء الطلب
                        </button>
                    </form>
                    @endif

                    @if($currentStatus === 'confirmed')
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-500 text-white rounded-xl font-semibold hover:bg-indigo-600 transition-colors">
                            <i class="fas fa-cogs mr-2"></i>
                            بدء التجهيز
                        </button>
                    </form>
                    @endif

                    @if($currentStatus === 'processing')
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id) }}" class="w-full">
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
                    <form method="POST" action="{{ route('admin.orders.update_status', $order->id) }}" class="w-full">
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
                        <span class="font-semibold">#{{ $order->id }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">تاريخ الطلب:</span>
                        <span class="font-semibold">{{ $order->created_at->format('Y-m-d') }}</span>
                    </div>

                    @if($paymentRequest)
                    <div class="flex justify-between">
                        <span class="text-slate-600">طريقة الدفع:</span>
                        <span class="font-semibold">{{ $paymentRequest->payment_method === 'bank_transfer' ? 'تحويل بنكي' : 'محفظة إلكترونية' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">حالة الدفع:</span>
                        @php
                            $paymentStatusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800'
                            ];
                            $paymentStatusNames = [
                                'pending' => 'قيد الانتظار',
                                'completed' => 'مكتمل',
                                'failed' => 'فاشل'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $paymentStatusClasses[$paymentRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $paymentStatusNames[$paymentRequest->status] ?? $paymentRequest->status }}
                        </span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-slate-600">الكمية:</span>
                        <span class="font-semibold">{{ $order->quantity }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">المنتج:</span>
                        <span class="font-semibold">{{ $order->product->name }}</span>
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
                            'timestamp' => $order->created_at,
                            'completed' => true
                        ],
                        [
                            'status' => 'confirmed',
                            'title' => 'تم تأكيد الطلب',
                            'description' => 'تم تأكيد الطلب من قبل الإدارة',
                            'timestamp' => $order->updated_at,
                            'completed' => in_array($currentStatus, ['confirmed', 'processing', 'shipped', 'delivered'])
                        ],
                        [
                            'status' => 'processing',
                            'title' => 'قيد التجهيز',
                            'description' => 'جاري تجهيز المنتجات للشحن',
                            'timestamp' => $order->updated_at,
                            'completed' => in_array($currentStatus, ['processing', 'shipped', 'delivered'])
                        ],
                        [
                            'status' => 'shipped',
                            'title' => 'تم الشحن',
                            'description' => 'تم تسليم الطلب لشركة الشحن',
                            'timestamp' => $order->updated_at,
                            'completed' => in_array($currentStatus, ['shipped', 'delivered'])
                        ],
                        [
                            'status' => 'delivered',
                            'title' => 'تم التسليم',
                            'description' => 'وصل الطلب للعميل بنجاح',
                            'timestamp' => $order->updated_at,
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
                            <p class="text-xs text-slate-500 mt-1">{{ $event['timestamp']->format('Y-m-d H:i') }}</p>
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
        window.open('/admin/orders/{{ $order->id }}/print', '_blank');
    }

    // Preview payment proof in modal
    function previewPaymentProof(imageUrl, fileName) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="max-w-4xl max-h-full bg-white rounded-lg overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">${fileName}</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img src="${imageUrl}" alt="إثبات الدفع" class="max-w-full max-h-[70vh] mx-auto">
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Close on click outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
</script>
@endpush
@endsection
