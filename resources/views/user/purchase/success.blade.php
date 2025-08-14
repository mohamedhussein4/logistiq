@extends('layouts.main')

@section('title', 'تم إنشاء الطلب بنجاح')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-green-50 via-green-100 to-green-200 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto text-center">
            <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-glow animate-bounce-soft">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-secondary-800 mb-4">تم إنشاء الطلب بنجاح!</h1>
            <p class="text-secondary-600 mb-4">
                رقم الطلب: <span class="font-semibold text-primary-600">#{{ $order->id }}</span>
            </p>
            <p class="text-secondary-600">
                رقم طلب الدفع: <span class="font-semibold text-green-600">{{ $paymentRequest->request_number }}</span>
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- تفاصيل الطلب -->
            <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up">
                <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                    <i class="fas fa-receipt text-primary-600 ml-2"></i>
                    تفاصيل الطلب
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-primary-50 rounded-lg border border-primary-100">
                            <span class="text-secondary-600 flex items-center">
                                <i class="fas fa-box text-primary-600 ml-2"></i>
                                المنتج:
                            </span>
                            <span class="font-semibold text-secondary-800">{{ $order->product->name }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="text-secondary-600 flex items-center">
                                <i class="fas fa-sort-numeric-up text-primary-600 ml-2"></i>
                                الكمية:
                            </span>
                            <span class="font-semibold text-secondary-800">{{ $order->quantity }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <span class="text-secondary-600 flex items-center">
                                <i class="fas fa-tag text-primary-600 ml-2"></i>
                                سعر الوحدة:
                            </span>
                            <span class="font-semibold text-secondary-800">{{ number_format($order->price, 2) }} ريال</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-secondary-600 flex items-center">
                                <i class="fas fa-calendar text-primary-600 ml-2"></i>
                                تاريخ الطلب:
                            </span>
                            <span class="font-semibold text-secondary-800">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <span class="text-secondary-600 flex items-center">
                                <i class="fas fa-info-circle text-primary-600 ml-2"></i>
                                حالة الطلب:
                            </span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                في انتظار الدفع
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-t border-secondary-200 pt-4">
                            <span class="text-lg font-bold text-secondary-800 flex items-center">
                                <i class="fas fa-calculator text-green-600 ml-2"></i>
                                المجموع:
                            </span>
                            <span class="text-2xl font-bold text-green-600">{{ number_format($order->total_amount, 2) }} ريال</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- بيانات التحويل -->
            <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up" style="animation-delay: 0.2s;">
                <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                    <i class="fas fa-university text-primary-600 ml-2"></i>
                    بيانات التحويل
                </h2>

                @if($paymentRequest->payment_method === 'bank_transfer')
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                        @if($paymentAccount)
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-blue-500 rounded-xl flex items-center justify-center ml-4 shadow-soft">
                                <i class="fas fa-university text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-blue-800">{{ $paymentAccount->bank_name }}</h3>
                                <p class="text-blue-600">تحويل بنكي</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-gray-500 rounded-xl flex items-center justify-center ml-4 shadow-soft">
                                <i class="fas fa-credit-card text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">طريقة الدفع</h3>
                                <p class="text-gray-600">{{ $paymentRequest->payment_method === 'bank_transfer' ? 'تحويل بنكي' : 'غير محدد' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($paymentAccount)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-blue-700">اسم الحساب</label>
                                <div class="p-3 bg-white rounded-lg border border-blue-200 font-semibold text-secondary-800">{{ $paymentAccount->account_name }}</div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-blue-700">رقم الحساب</label>
                                <div class="p-3 bg-white rounded-lg border border-blue-200 font-mono text-secondary-800">{{ $paymentAccount->account_number }}</div>
                            </div>
                            @if($paymentAccount->iban)
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-blue-700">رقم IBAN</label>
                                <div class="p-3 bg-white rounded-lg border border-blue-200 font-mono text-sm text-secondary-800">{{ $paymentAccount->iban }}</div>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-600 text-center">لا توجد تفاصيل حساب متاحة</p>
                        </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- رفع إثبات التحويل -->
            <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up" style="animation-delay: 0.4s;">
                <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                    <i class="fas fa-upload text-primary-600 ml-2"></i>
                    رفع إثبات التحويل
                </h2>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-yellow-600 ml-2"></i>
                        <span class="text-yellow-800 font-medium">يرجى تحويل المبلغ المطلوب ثم رفع إثبات التحويل (صورة أو ملف PDF)</span>
                    </div>
                </div>

                <form id="proof-upload-form" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="payment_request_id" value="{{ $paymentRequest->id }}">

                    <div class="space-y-2">
                        <label for="proof_file" class="block text-sm font-bold text-secondary-700 mb-2">
                            <i class="fas fa-file text-primary-600 ml-1"></i>
                            ملف إثبات التحويل *
                        </label>
                        <div class="border-2 border-dashed border-secondary-300 rounded-xl p-8 text-center hover:border-primary-500 transition-all hover-lift cursor-pointer bg-white" onclick="document.getElementById('proof_file').click()">
                            <input type="file" name="proof_file" id="proof_file" accept="image/*,.pdf,.doc,.docx" required
                                   class="hidden" onchange="handleFileSelect(this)">
                            <div class="space-y-3">
                                <i class="fas fa-cloud-upload-alt text-primary-400 text-4xl"></i>
                                <div>
                                    <p class="text-secondary-600 font-medium">اضغط هنا لاختيار الملف</p>
                                    <p class="text-sm text-secondary-500">يُقبل: JPG, PNG, PDF, DOC, DOCX (حد أقصى: 10MB)</p>
                                </div>
                            </div>
                            <div id="file-info" class="hidden mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <p class="text-blue-800 font-medium" id="file-name"></p>
                                <p class="text-blue-600 text-sm" id="file-size"></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-bold text-secondary-700 mb-2">
                            <i class="fas fa-sticky-note text-primary-600 ml-1"></i>
                            وصف أو ملاحظات (اختياري)
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-soft"
                                  placeholder="أي ملاحظات إضافية حول التحويل..."></textarea>
                    </div>

                    <button type="submit" id="upload-btn"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-xl font-semibold transition-all hover-lift shadow-glow flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-upload ml-2"></i>
                        رفع إثبات التحويل
                    </button>
                </form>

                <!-- عرض إثباتات التحويل المرفوعة -->
                <div id="uploaded-proofs" class="mt-8">
                    @if($paymentRequest->paymentProofs->count() > 0)
                    <h3 class="text-lg font-bold text-secondary-800 mb-4">
                        <i class="fas fa-folder-open text-primary-600 ml-2"></i>
                        الملفات المرفوعة
                    </h3>
                    <div class="space-y-3">
                        @foreach($paymentRequest->paymentProofs as $proof)
                        <div class="flex items-center justify-between p-4 glass rounded-xl border border-secondary-200 hover-lift">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                    @if(in_array($proof->file_type, ['jpg', 'jpeg', 'png']))
                                        <i class="fas fa-image text-primary-600"></i>
                                    @elseif($proof->file_type === 'pdf')
                                        <i class="fas fa-file-pdf text-red-600"></i>
                                    @else
                                        <i class="fas fa-file text-secondary-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-secondary-800">{{ $proof->file_name }}</div>
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
                                <a href="{{ asset('/' . $proof->file_path) }}" target="_blank"
                                   class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-lg text-sm transition-colors hover-lift flex items-center">
                                    <i class="fas fa-eye ml-1"></i>
                                    عرض
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- إجراءات إضافية -->
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 sm:space-x-reverse">
                <a href="{{ route('store') }}"
                   class="flex-1 bg-secondary-200 hover:bg-secondary-300 text-secondary-700 px-6 py-3 rounded-xl font-semibold text-center transition-all hover-lift flex items-center justify-center">
                    <i class="fas fa-shopping-cart ml-2"></i>
                    الرجوع للمتجر
                </a>
                <a href="{{ route('user.purchase.my_orders') }}"
                   class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-6 py-3 rounded-xl font-semibold text-center transition-all hover-lift shadow-glow flex items-center justify-center">
                    <i class="fas fa-list ml-2"></i>
                    طلباتي
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function handleFileSelect(input) {
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    if (input.files.length > 0) {
        const file = input.files[0];
        fileName.textContent = file.name;
        fileSize.textContent = `الحجم: ${(file.size / 1024 / 1024).toFixed(2)} MB`;
        fileInfo.classList.remove('hidden');
        fileInfo.classList.add('animate-scale-in');
    } else {
        fileInfo.classList.add('hidden');
    }
}

document.getElementById('proof-upload-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const uploadBtn = document.getElementById('upload-btn');
    const formData = new FormData(form);

    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري الرفع...';

    fetch('{{ route('user.purchase.upload_proof') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showMessage('success', 'تم بنجاح!', 'تم رفع إثبات التحويل بنجاح!');
            setTimeout(() => location.reload(), 2000);
        } else {
            window.showMessage('error', 'خطأ!', data.error || 'خطأ غير معروف');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showMessage('error', 'خطأ!', 'حدث خطأ أثناء رفع الملف');
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-upload ml-2"></i>رفع إثبات التحويل';
    });
});
</script>
@endpush
@endsection
