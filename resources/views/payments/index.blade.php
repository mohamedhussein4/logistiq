@extends('layouts.main')

@section('title', 'طلبات الدفع')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">طلبات الدفع</h1>
            <p class="text-indigo-100 text-lg">متابعة جميع طلبات الدفع الخاصة بك</p>
        </div>
    </div>
</section>

<!-- Payment Requests List -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            @if($paymentRequests->count() > 0)
            <div class="space-y-6">
                @foreach($paymentRequests as $paymentRequest)
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                @if($paymentRequest->payment_method === 'bank_transfer')
                                    <i class="fas fa-university text-indigo-600 text-xl"></i>
                                @elseif($paymentRequest->payment_method === 'electronic_wallet')
                                    <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                                @else
                                    <i class="fas fa-credit-card text-gray-600 text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $paymentRequest->request_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $paymentRequest->payment_type_name }} - {{ $paymentRequest->payment_method_name }}</p>
                            </div>
                        </div>

                        <div class="text-left">
                            <div class="text-lg font-bold text-indigo-600 mb-2">{{ $paymentRequest->formatted_amount }}</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($paymentRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($paymentRequest->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($paymentRequest->status === 'completed') bg-green-100 text-green-800
                                @elseif($paymentRequest->status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $paymentRequest->status_name }}
                            </span>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <span class="text-sm text-gray-500">تاريخ الطلب:</span>
                            <p class="text-sm font-medium">{{ $paymentRequest->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">آخر تحديث:</span>
                            <p class="text-sm font-medium">{{ $paymentRequest->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">إثباتات الدفع:</span>
                            <p class="text-sm font-medium">{{ $paymentRequest->paymentProofs->count() }} ملف</p>
                        </div>
                    </div>

                    @if($paymentRequest->payment_notes)
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-500">ملاحظات:</span>
                        <p class="text-sm text-gray-700 mt-1">{{ $paymentRequest->payment_notes }}</p>
                    </div>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex space-x-3 space-x-reverse">
                            <a href="{{ route('payments.show', $paymentRequest->id) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                <i class="fas fa-eye ml-1"></i>
                                عرض التفاصيل
                            </a>

                            @if($paymentRequest->isPending())
                            <button onclick="cancelPayment({{ $paymentRequest->id }})"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء
                            </button>
                            @endif
                        </div>

                        <div class="text-sm text-gray-500">
                            @if($paymentRequest->processed_at)
                                تمت المعالجة: {{ $paymentRequest->processed_at->format('d/m/Y H:i') }}
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($paymentRequests->hasPages())
            <div class="mt-8">
                {{ $paymentRequests->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-credit-card text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد طلبات دفع</h3>
                <p class="text-gray-500 mb-6">لم تقم بإنشاء أي طلبات دفع بعد</p>
                <a href="{{ route('store') }}"
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors">
                    <i class="fas fa-shopping-cart ml-2"></i>
                    تصفح المنتجات
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
function cancelPayment(paymentId) {
    if (confirm('هل أنت متأكد من إلغاء طلب الدفع؟')) {
        fetch(`/payments/${paymentId}/cancel`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection
