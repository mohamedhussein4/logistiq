@extends('layouts.main')

@section('title', 'شراء منتج - ' . $product->name)

@push('styles')
<style>
    /* تحسينات للـ drag and drop */
    .border-dashed {
        transition: all 0.3s ease;
    }

    .border-dashed:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.2);
    }

    /* تحسين الأنيميشن */
    .animate-slide-down {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* تحسين معاينة الصورة */
    #preview-image {
        transition: all 0.3s ease;
    }

    #preview-image:hover {
        transform: scale(1.05);
    }

    /* تحسين الألوان للحالات المختلفة */
    .file-upload-success {
        border-color: #10b981 !important;
        background-color: #d1fae5 !important;
    }

    .file-upload-error {
        border-color: #ef4444 !important;
        background-color: #fee2e2 !important;
    }
</style>
@endpush

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-secondary-800 mb-4">شراء المنتج</h1>
                <p class="text-secondary-600">اختر وسيلة الدفع وأكمل عملية الشراء</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- معلومات المنتج -->
                <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up">
                    <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                        <i class="fas fa-box text-primary-600 ml-2"></i>
                        تفاصيل المنتج
                    </h2>

                    @if($product->image)
                        <img src="{{ asset('/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-64 object-cover rounded-xl mb-6 shadow-soft">
                    @endif

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-secondary-800">{{ $product->name }}</h3>
                            <p class="text-secondary-600 mt-2">{{ $product->description }}</p>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-primary-50 rounded-xl border border-primary-100">
                            <span class="text-secondary-700 font-medium flex items-center">
                                <i class="fas fa-tag text-primary-600 ml-2"></i>
                                السعر:
                            </span>
                            <span class="text-2xl font-bold text-green-600">{{ number_format($product->price, 2) }} ريال</span>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="text-secondary-700 font-medium flex items-center">
                                <i class="fas fa-boxes text-primary-600 ml-2"></i>
                                المتوفر في المخزون:
                            </span>
                            <span class="text-xl font-semibold text-primary-600">{{ $product->stock_quantity }} قطعة</span>
                        </div>

                        @if($product->category)
                        <div class="flex justify-between items-center p-4 bg-purple-50 rounded-xl border border-purple-100">
                            <span class="text-secondary-700 font-medium flex items-center">
                                <i class="fas fa-folder text-primary-600 ml-2"></i>
                                التصنيف:
                            </span>
                            <span class="text-purple-600 font-medium">{{ $product->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- نموذج الدفع -->
                <div class="glass rounded-2xl shadow-soft hover-lift p-6 animate-slide-up" style="animation-delay: 0.2s;">
                    <h2 class="text-2xl font-bold text-secondary-800 mb-6">
                        <i class="fas fa-credit-card text-primary-600 ml-2"></i>
                        معلومات الطلب والدفع
                    </h2>

                    <!-- عرض الأخطاء -->
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                                <h3 class="text-red-800 font-semibold">يوجد أخطاء:</h3>
                            </div>
                            <ul class="text-red-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.purchase.process', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateForm()">
                        @csrf

                        <!-- الكمية -->
                        <div class="space-y-2">
                            <label for="quantity" class="block text-sm font-bold text-secondary-700 mb-2">
                                <i class="fas fa-calculator text-primary-600 ml-1"></i>
                                الكمية المطلوبة *
                            </label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" required
                                   class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-soft"
                                   onchange="updateTotal()">
                        </div>

                        <!-- عنوان التوصيل -->
                        <div class="space-y-2">
                            <label for="delivery_address" class="block text-sm font-bold text-secondary-700 mb-2">
                                <i class="fas fa-map-marker-alt text-primary-600 ml-1"></i>
                                عنوان التوصيل *
                            </label>
                            <textarea name="delivery_address" id="delivery_address" rows="3" required
                                      class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-soft resize-none"
                                      placeholder="أدخل العنوان المفصل لتوصيل المنتج (المنطقة، الشارع، رقم المبنى، إلخ...)"></textarea>
                            <p class="text-xs text-secondary-500 mt-1">
                                <i class="fas fa-info-circle ml-1"></i>
                                يرجى إدخال عنوان مفصل وواضح لضمان التوصيل الصحيح
                            </p>
                        </div>

                        <!-- إجمالي السعر -->
                        <div class="p-4 bg-green-50 rounded-xl border border-green-100 animate-scale-in">
                            <div class="flex justify-between items-center">
                                <span class="text-secondary-700 font-medium flex items-center">
                                    <i class="fas fa-calculator text-green-600 ml-2"></i>
                                    إجمالي السعر:
                                </span>
                                <span id="total-price" class="text-2xl font-bold text-green-600">{{ number_format($product->price, 2) }} ريال</span>
                            </div>
                        </div>

                        <!-- طريقة الدفع -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-secondary-700 mb-4">
                                <i class="fas fa-credit-card text-primary-600 ml-1"></i>
                                طريقة الدفع *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-secondary-200 rounded-xl cursor-pointer hover:bg-primary-50 transition-all hover-lift group">
                                    <input type="radio" name="payment_method" value="bank_transfer" required class="ml-3 text-primary-600 focus:ring-primary-500" onchange="showBankAccounts()">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center ml-3 group-hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-university text-primary-600"></i>
                                        </div>
                                        <span class="font-medium text-secondary-800">تحويل بنكي</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- الحسابات البنكية -->
                        <div id="bank-accounts" class="animate-slide-down">
                            <label class="block text-sm font-bold text-secondary-700 mb-3">
                                <i class="fas fa-university text-primary-600 ml-1"></i>
                                اختر الحساب البنكي *
                            </label>
                            <div class="space-y-3">
                                @foreach($bankAccounts as $account)
                                <label class="flex items-start p-4 border border-secondary-200 rounded-xl cursor-pointer hover:bg-primary-50 transition-all hover-lift group">
                                    <input type="radio" name="payment_account_id" value="{{ $account->id }}" required class="ml-3 text-primary-600 focus:ring-primary-500 mt-1" onchange="showBankDetails({{ $account->id }})">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-3 space-x-reverse">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors flex-shrink-0">
                                                <i class="fas fa-university text-primary-600 text-lg"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-secondary-800 text-lg mb-1">{{ $account->bank_name }}</div>
                                                <div class="text-sm text-secondary-700 font-medium mb-1">{{ $account->account_name }}</div>
                                                <div class="text-sm text-secondary-600 font-mono bg-gray-50 px-2 py-1 rounded inline-block">{{ $account->account_number }}</div>
                                                @if($account->iban)
                                                <div class="text-xs text-secondary-500 mt-1">
                                                    <strong>IBAN:</strong> {{ $account->iban }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- تفاصيل إضافية للحساب -->
                                        <div id="bank-details-{{ $account->id }}" class="hidden mt-4 p-3 bg-blue-50 rounded-lg border-r-4 border-blue-400">
                                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                                <i class="fas fa-info-circle text-blue-600 ml-1"></i>
                                                تفاصيل التحويل
                                            </h4>
                                            <div class="text-sm space-y-2">
                                                <div class="flex justify-between">
                                                    <span class="text-blue-700 font-medium">اسم البنك:</span>
                                                    <span class="text-blue-800 font-semibold">{{ $account->bank_name }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-blue-700 font-medium">اسم المستفيد:</span>
                                                    <span class="text-blue-800 font-semibold">{{ $account->account_name }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-blue-700 font-medium">رقم الحساب:</span>
                                                    <span class="text-blue-800 font-mono font-bold bg-white px-2 py-1 rounded">{{ $account->account_number }}</span>
                                                </div>
                                                @if($account->iban)
                                                <div class="flex justify-between">
                                                    <span class="text-blue-700 font-medium">رقم الآيبان:</span>
                                                    <span class="text-blue-800 font-mono font-bold bg-white px-2 py-1 rounded">{{ $account->iban }}</span>
                                                </div>
                                                @endif
                                                @if($account->swift_code)
                                                <div class="flex justify-between">
                                                    <span class="text-blue-700 font-medium">رمز السويفت:</span>
                                                    <span class="text-blue-800 font-mono font-bold bg-white px-2 py-1 rounded">{{ $account->swift_code }}</span>
                                                </div>
                                                @endif
                                                <div class="mt-3 p-2 bg-white rounded border border-blue-200">
                                                    <p class="text-blue-700 text-xs leading-relaxed">
                                                        <i class="fas fa-lightbulb text-yellow-500 ml-1"></i>
                                                        <strong>تعليمات:</strong> بعد إجراء التحويل، قم برفع صورة من إثبات التحويل لتأكيد العملية.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- قسم رفع إثبات الدفع -->
                        <div id="payment-proof-section" class="hidden space-y-4 p-5 bg-green-50 border-2 border-green-200 rounded-xl">
                            <h4 class="font-semibold text-green-800 mb-4 flex items-center">
                                <i class="fas fa-upload text-green-600 ml-2"></i>
                                رفع إثبات الدفع
                            </h4>

                            <!-- منطقة رفع الملف -->
                            <div class="border-2 border-dashed border-green-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors">
                                <div class="space-y-3">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-green-500"></i>
                                    <div>
                                        <label for="payment_proof" class="cursor-pointer">
                                            <span class="text-green-700 font-medium">اضغط لرفع صورة إثبات التحويل</span>
                                            <p class="text-sm text-green-600 mt-1">أو اسحب الملف هنا</p>
                                        </label>
                                        <input type="file" id="payment_proof" name="payment_proof"
                                               accept="image/*,.pdf"
                                               class="hidden"
                                               onchange="handleFileUpload(this)">
                                    </div>
                                    <p class="text-xs text-green-600">
                                        الأنواع المدعومة: JPG, PNG, PDF | الحد الأقصى: 5MB
                                    </p>
                                </div>
                            </div>

                            <!-- معاينة الملف المرفوع -->
                            <div id="file-preview" class="hidden">
                                <div class="bg-white border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <i class="fas fa-file-image text-green-600 text-xl"></i>
                                            <div>
                                                <p id="file-name" class="font-medium text-gray-900"></p>
                                                <p id="file-size" class="text-sm text-gray-600"></p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeFile()"
                                                class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <!-- معاينة الصورة -->
                                    <div id="image-preview" class="hidden mt-3">
                                        <img id="preview-image" src="" alt="معاينة"
                                             class="max-w-full h-40 object-contain rounded border">
                                    </div>
                                </div>
                            </div>

                            <!-- تنبيه هام -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 ml-2 mt-1"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-semibold mb-1">تنبيه هام:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>تأكد من وضوح صورة إثبات التحويل</li>
                                            <li>يجب أن تظهر تفاصيل التحويل كاملة (المبلغ، التاريخ، الحساب المحول إليه)</li>
                                            <li>سيتم مراجعة الطلب خلال 24 ساعة من رفع الإثبات</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <div class="space-y-2">
                            <label for="payment_notes" class="block text-sm font-bold text-secondary-700 mb-2">
                                <i class="fas fa-sticky-note text-primary-600 ml-1"></i>
                                ملاحظات إضافية (اختياري)
                            </label>
                            <textarea name="payment_notes" id="payment_notes" rows="3"
                                      class="w-full px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-soft"
                                      placeholder="أي ملاحظات خاصة بالطلب..."></textarea>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="flex space-x-4 space-x-reverse pt-6 border-t border-secondary-200">
                            <a href="{{ route('store') }}"
                               class="flex-1 bg-secondary-200 hover:bg-secondary-300 text-secondary-700 px-6 py-3 rounded-xl font-semibold text-center transition-all hover-lift flex items-center justify-center">
                                <i class="fas fa-arrow-left ml-2"></i>
                                الرجوع للمتجر
                            </a>
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white px-6 py-3 rounded-xl font-semibold transition-all hover-lift shadow-glow flex items-center justify-center">
                                <i class="fas fa-shopping-cart ml-2"></i>
                                تأكيد الطلب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
const productPrice = {{ $product->price }};

function updateTotal() {
    const quantity = document.getElementById('quantity').value;
    const total = productPrice * quantity;
    const formatter = new Intl.NumberFormat('ar-SA', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('total-price').textContent = formatter.format(total) + ' ريال';
}

function showBankAccounts() {
    const bankAccounts = document.getElementById('bank-accounts');
    const proofSection = document.getElementById('payment-proof-section');

    bankAccounts.classList.remove('hidden');
    bankAccounts.classList.add('animate-slide-down');

    // إظهار قسم رفع إثبات الدفع
    proofSection.classList.remove('hidden');
    proofSection.classList.add('animate-slide-down');
}

function showBankDetails(accountId) {
    // إخفاء جميع تفاصيل البنوك
    const allDetails = document.querySelectorAll('[id^="bank-details-"]');
    allDetails.forEach(detail => {
        detail.classList.add('hidden');
    });

    // إظهار تفاصيل البنك المحدد
    const selectedDetails = document.getElementById(`bank-details-${accountId}`);
    if (selectedDetails) {
        selectedDetails.classList.remove('hidden');
        selectedDetails.classList.add('animate-slide-down');

        // تحريك الصفحة لإظهار التفاصيل
        setTimeout(() => {
            selectedDetails.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 100);
    }
}

function validateForm() {
    // التحقق من عنوان التوصيل
    const deliveryAddress = document.getElementById('delivery_address');
    if (!deliveryAddress.value.trim()) {
        alert('يرجى إدخال عنوان التوصيل');
        deliveryAddress.focus();
        return false;
    }

    if (deliveryAddress.value.trim().length < 10) {
        alert('يرجى إدخال عنوان تفصيلي أكثر (10 أحرف على الأقل)');
        deliveryAddress.focus();
        return false;
    }

    // التحقق من طريقة الدفع
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        alert('يرجى اختيار طريقة الدفع');
        return false;
    }

    // التحقق من اختيار الحساب البنكي
    const paymentAccount = document.querySelector('input[name="payment_account_id"]:checked');
    if (!paymentAccount) {
        alert('يرجى اختيار الحساب البنكي للتحويل');
        return false;
    }

    // التحقق من رفع إثبات الدفع
    const paymentProof = document.getElementById('payment_proof');
    if (!paymentProof.files || paymentProof.files.length === 0) {
        alert('يرجى رفع صورة إثبات التحويل البنكي');
        document.getElementById('payment-proof-section').scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        return false;
    }

    // عرض رسالة التحميل
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري معالجة الطلب...';

    return true;
}

// إدارة رفع ملف إثبات الدفع
function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;

    const dropZone = document.querySelector('.border-dashed');

    // التحقق من حجم الملف (5MB)
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (file.size > maxSize) {
        showUploadError('حجم الملف كبير جداً. الحد الأقصى المسموح: 5MB');
        input.value = '';
        return;
    }

    // التحقق من نوع الملف
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    if (!allowedTypes.includes(file.type)) {
        showUploadError('نوع الملف غير مدعوم. الأنواع المدعومة: JPG, PNG, PDF');
        input.value = '';
        return;
    }

    // إظهار نجاح الرفع
    showUploadSuccess();

    // عرض معاينة الملف
    showFilePreview(file);
}

function showUploadError(message) {
    const dropZone = document.querySelector('.border-dashed');
    dropZone.classList.add('file-upload-error');

    // إزالة كلاس الخطأ بعد 3 ثوان
    setTimeout(() => {
        dropZone.classList.remove('file-upload-error');
    }, 3000);

    alert(message);
}

function showUploadSuccess() {
    const dropZone = document.querySelector('.border-dashed');
    dropZone.classList.add('file-upload-success');

    // إزالة كلاس النجاح بعد 2 ثوان
    setTimeout(() => {
        dropZone.classList.remove('file-upload-success');
    }, 2000);
}

function showFilePreview(file) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');

    // إظهار معلومات الملف
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);

    // إظهار المعاينة
    preview.classList.remove('hidden');

    // معاينة الصورة إذا كانت من نوع صورة
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        // إخفاء معاينة الصورة للملفات الأخرى (PDF)
        imagePreview.classList.add('hidden');

        // تغيير الأيقونة للملفات PDF
        const icon = preview.querySelector('i');
        icon.className = 'fas fa-file-pdf text-red-600 text-xl';
    }
}

function removeFile() {
    const input = document.getElementById('payment_proof');
    const preview = document.getElementById('file-preview');
    const imagePreview = document.getElementById('image-preview');

    input.value = '';
    preview.classList.add('hidden');
    imagePreview.classList.add('hidden');

    // إعادة تعيين الأيقونة
    const icon = preview.querySelector('i');
    icon.className = 'fas fa-file-image text-green-600 text-xl';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// تحديث الإجمالي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateTotal();

    // إظهار قسم البنوك تلقائياً إذا كان التحويل البنكي هو الخيار الوحيد
    const bankTransferRadio = document.querySelector('input[value="bank_transfer"]');
    if (bankTransferRadio) {
        bankTransferRadio.checked = true;
        showBankAccounts();
    }

    // إضافة مستمع للنموذج
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form data:', new FormData(form));
        });
    }

    // إعداد drag and drop لرفع الملفات
    const dropZone = document.querySelector('.border-dashed');
    const fileInput = document.getElementById('payment_proof');

    if (dropZone && fileInput) {
        // منع السلوك الافتراضي للـ drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // تمييز منطقة الإفلات عند السحب
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // التعامل مع إفلات الملفات
        dropZone.addEventListener('drop', handleDrop, false);
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        dropZone.classList.add('border-green-400', 'bg-green-100');
    }

    function unhighlight() {
        dropZone.classList.remove('border-green-400', 'bg-green-100');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            handleFileUpload(fileInput);
        }
    }
});
</script>
@endpush
@endsection
