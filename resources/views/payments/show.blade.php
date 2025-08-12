@extends('layouts.main')

@section('title', 'تفاصيل طلب الدفع - ' . $paymentRequest->request_number)

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-purple-600 to-purple-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">تفاصيل طلب الدفع</h1>
            <p class="text-purple-100 text-lg">متابعة حالة طلب الدفع وإثباتات الدفع</p>
        </div>
    </div>
</section>

<!-- Payment Request Details -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Payment Request Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">معلومات طلب الدفع</h2>
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($paymentRequest->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($paymentRequest->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($paymentRequest->status === 'completed') bg-green-100 text-green-800
                            @elseif($paymentRequest->status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $paymentRequest->status_name }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $paymentRequest->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">تفاصيل الدفع</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">رقم الطلب:</span>
                                <span class="font-semibold">{{ $paymentRequest->request_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">نوع الدفع:</span>
                                <span class="font-semibold">{{ $paymentRequest->payment_type_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">طريقة الدفع:</span>
                                <span class="font-semibold">{{ $paymentRequest->payment_method_name }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-green-600">
                                <span>المبلغ:</span>
                                <span>{{ $paymentRequest->formatted_amount }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">معلومات الحساب</h3>
                        <div class="space-y-3">
                            @if($paymentRequest->bankAccount)
                            <div>
                                <span class="text-gray-600">البنك:</span>
                                <p class="font-semibold mt-1">{{ $paymentRequest->bankAccount->bank_name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">اسم الحساب:</span>
                                <p class="font-semibold mt-1">{{ $paymentRequest->bankAccount->account_name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">رقم الحساب:</span>
                                <p class="font-semibold mt-1">{{ $paymentRequest->bankAccount->account_number }}</p>
                            </div>
                            @if($paymentRequest->bankAccount->iban)
                            <div>
                                <span class="text-gray-600">IBAN:</span>
                                <p class="font-semibold mt-1 font-mono text-sm">{{ $paymentRequest->bankAccount->iban }}</p>
                            </div>
                            @endif
                            @elseif($paymentRequest->electronicWallet)
                            <div>
                                <span class="text-gray-600">المحفظة:</span>
                                <p class="font-semibold mt-1">{{ $paymentRequest->electronicWallet->wallet_name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">اسم الحساب:</span>
                                <p class="font-semibold mt-1">{{ $paymentRequest->electronicWallet->account_name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">رقم الحساب:</span>
                                <p class="font-semibold mt-1">{{ $paymentRequest->electronicWallet->account_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($paymentRequest->payment_notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">ملاحظات الدفع</h3>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $paymentRequest->payment_notes }}</p>
                </div>
                @endif

                @if($paymentRequest->admin_notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">ملاحظات الإدارة</h3>
                    <p class="text-gray-700 bg-blue-50 p-4 rounded-lg">{{ $paymentRequest->admin_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Payment Proofs -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">إثباتات الدفع</h2>
                    @if($paymentRequest->isPending())
                    <button onclick="document.getElementById('upload-proof-modal').classList.remove('hidden')"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-upload ml-2"></i>
                        رفع إثبات جديد
                    </button>
                    @endif
                </div>

                @if($paymentRequest->paymentProofs->count() > 0)
                <div class="space-y-4">
                    @foreach($paymentRequest->paymentProofs as $proof)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if(str_contains($proof->file_type, 'image'))
                                        <i class="fas fa-image text-gray-600"></i>
                                    @elseif(str_contains($proof->file_type, 'pdf'))
                                        <i class="fas fa-file-pdf text-red-600"></i>
                                    @else
                                        <i class="fas fa-file text-gray-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $proof->file_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $proof->file_type_name }} - {{ $proof->formatted_file_size }}</p>
                                    <p class="text-xs text-gray-500">{{ $proof->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 space-x-reverse">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($proof->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($proof->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $proof->status_name }}
                                </span>

                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ $proof->file_url }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($proof->isPending())
                                    <button onclick="cancelProof({{ $proof->id }})"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($proof->description)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-sm text-gray-600">{{ $proof->description }}</p>
                        </div>
                        @endif

                        @if($proof->rejection_reason)
                        <div class="mt-3 pt-3 border-t border-red-100 bg-red-50 p-3 rounded-lg">
                            <p class="text-sm text-red-700">
                                <strong>سبب الرفض:</strong> {{ $proof->rejection_reason }}
                            </p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-file-upload text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">لم يتم رفع أي إثبات دفع بعد</p>
                    @if($paymentRequest->isPending())
                    <button onclick="document.getElementById('upload-proof-modal').classList.remove('hidden')"
                            class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-upload ml-2"></i>
                        رفع إثبات الدفع
                    </button>
                    @endif
                </div>
                @endif
            </div>

            <!-- Actions -->
            @if($paymentRequest->isPending())
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">الإجراءات</h2>

                <div class="flex space-x-4 space-x-reverse">
                    <button onclick="cancelPayment()"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-times ml-2"></i>
                        إلغاء طلب الدفع
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Upload Proof Modal -->
<div id="upload-proof-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">رفع إثبات الدفع</h3>
            <button onclick="document.getElementById('upload-proof-modal').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('payments.upload_proof', $paymentRequest->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="proof_file" class="block text-sm font-medium text-gray-700 mb-2">اختر الملف</label>
                <input type="file" name="proof_file" id="proof_file"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       accept="image/*,.pdf,.doc,.docx" required>
                <p class="text-xs text-gray-500 mt-1">الملفات المدعومة: صور، PDF، Word (الحد الأقصى: 10MB)</p>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الملف (اختياري)</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="أضف وصفاً للملف..."></textarea>
            </div>

            <div class="flex space-x-3 space-x-reverse">
                <button type="button" onclick="document.getElementById('upload-proof-modal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    إلغاء
                </button>
                <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-upload ml-2"></i>
                    رفع الملف
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function cancelPayment() {
    if (confirm('هل أنت متأكد من إلغاء طلب الدفع؟')) {
        fetch(`{{ route('payments.cancel', $paymentRequest->id) }}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.href = '{{ route("payments.index") }}';
            }
        });
    }
}

function cancelProof(proofId) {
    if (confirm('هل أنت متأكد من حذف إثبات الدفع؟')) {
        // يمكن إضافة منطق حذف إثبات الدفع هنا
        console.log('حذف إثبات الدفع:', proofId);
    }
}
</script>
@endpush
@endsection
