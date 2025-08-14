@extends('layouts.admin')

@section('title', 'تفاصيل طلب الدفع')
@section('page-title', 'تفاصيل طلب الدفع')
@section('page-description', 'عرض تفاصيل طلب الدفع المالي')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">طلب دفع رقم #{{ $paymentRequest->request_number }}</h1>
                <p class="text-slate-600">تفاصيل طلب الدفع المالي</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'cancelled' => 'bg-gray-100 text-gray-800'
                    ];
                    $statusNames = [
                        'pending' => 'في الانتظار',
                        'processing' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'failed' => 'فاشل',
                        'cancelled' => 'ملغي'
                    ];
                    $currentStatus = $paymentRequest->status ?? 'pending';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusNames[$currentStatus] ?? 'غير محدد' }}
                </span>
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Request Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات طلب الدفع</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">المبلغ</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 text-2xl font-black">
                            {{ number_format($paymentRequest->amount) }} ريال
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الدفع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            @php
                                $methods = [
                                    'bank_transfer' => 'تحويل بنكي',
                                    'cash' => 'نقدي',
                                    'check' => 'شيك'
                                ];
                            @endphp
                            {{ $methods[$paymentRequest->payment_method] ?? $paymentRequest->payment_method }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع الدفع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            @php
                                $types = [
                                    'product_order' => 'طلب منتج',
                                    'invoice' => 'فاتورة',
                                    'funding_request' => 'طلب تمويل',
                                    'other' => 'أخرى'
                                ];
                            @endphp
                            {{ $types[$paymentRequest->payment_type] ?? $paymentRequest->payment_type }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الطلب</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $paymentRequest->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>

                @if($paymentRequest->payment_notes)
                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات الدفع</label>
                    <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                        {{ $paymentRequest->payment_notes }}
                    </div>
                </div>
                @endif
            </div>

            <!-- User Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات المستخدم</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">اسم المستخدم</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $paymentRequest->user->name ?? 'غير محدد' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $paymentRequest->user->email ?? 'غير محدد' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ $paymentRequest->user->phone ?? 'غير محدد' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع الحساب</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                            {{ ucfirst($paymentRequest->user->user_type ?? 'غير محدد') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Account Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات الحساب المالي</h3>

                @if($paymentRequest->payment_method === 'bank_transfer' && $paymentRequest->bankAccount)
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">اسم البنك</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                    {{ $paymentRequest->bankAccount->bank_name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">رقم الحساب</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-mono">
                                    {{ $paymentRequest->bankAccount->account_number }}
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">اسم صاحب الحساب</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                    {{ $paymentRequest->bankAccount->account_name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">رمز البنك</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                    {{ $paymentRequest->bankAccount->swift_code ?? 'غير محدد' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-3"></i>
                        <p>معلومات الحساب غير متوفرة</p>
                    </div>
                @endif
            </div>

            <!-- Payment Proofs -->
            @if($paymentRequest->paymentProofs && $paymentRequest->paymentProofs->count() > 0)
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">
                    <i class="fas fa-file-invoice mr-2"></i>
                    مستندات تأكيد الدفع
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($paymentRequest->paymentProofs as $index => $proof)
                    @php
                        $extension = $proof->file_path ? pathinfo($proof->file_path, PATHINFO_EXTENSION) : 'unknown';
                        $filename = $proof->file_path ? pathinfo($proof->file_path, PATHINFO_FILENAME) : 'ملف غير معروف';

                        // تحديد نوع الملف والأيقونة
                        $fileTypeInfo = [
                            'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => 'text-red-600', 'bg' => 'bg-red-50', 'border' => 'border-red-200'],
                            'jpg' => ['icon' => 'fas fa-file-image', 'color' => 'text-green-600', 'bg' => 'bg-green-50', 'border' => 'border-green-200'],
                            'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-green-600', 'bg' => 'bg-green-50', 'border' => 'border-green-200'],
                            'png' => ['icon' => 'fas fa-file-image', 'color' => 'text-green-600', 'bg' => 'bg-green-50', 'border' => 'border-green-200'],
                            'doc' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200'],
                            'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200'],
                            'default' => ['icon' => 'fas fa-file', 'color' => 'text-gray-600', 'bg' => 'bg-gray-50', 'border' => 'border-gray-200']
                        ];

                        $fileInfo = $fileTypeInfo[$extension] ?? $fileTypeInfo['default'];
                    @endphp

                    <div class="{{ $fileInfo['bg'] }} {{ $fileInfo['border'] }} rounded-2xl p-6 border-2 hover:shadow-lg transition-all">
                        <!-- Header مع الحالة والتاريخ -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 {{ $fileInfo['bg'] }} rounded-xl flex items-center justify-center ml-3 border-2 border-white shadow-sm">
                                    <i class="{{ $fileInfo['icon'] }} {{ $fileInfo['color'] }} text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">مستند التأكيد #{{ $index + 1 }}</h4>
                                    <p class="text-xs text-slate-600">{{ $proof->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>

                            <!-- حالة المستند -->
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $proof->status === 'approved' ? 'bg-green-100 text-green-800 border border-green-300' :
                                   ($proof->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' :
                                   ($proof->status === 'rejected' ? 'bg-red-100 text-red-800 border border-red-300' : 'bg-gray-100 text-gray-800 border border-gray-300')) }}">
                                {{ $proof->status === 'approved' ? '✅ موافق عليه' :
                                   ($proof->status === 'pending' ? '⏳ في الانتظار' :
                                   ($proof->status === 'rejected' ? '❌ مرفوض' : $proof->status)) }}
                            </span>
                        </div>

                        <!-- معلومات الملف -->
                        @if($proof->file_path)
                        <div class="mb-4">
                            <div class="bg-white rounded-xl p-3 border border-white/50">
                                <div class="text-xs text-slate-600 mb-1">اسم الملف:</div>
                                <div class="text-sm font-medium text-slate-900 truncate">{{ $filename . '.' . $extension }}</div>
                                <div class="text-xs text-slate-500 mt-1">نوع الملف: {{ strtoupper($extension) }}</div>
                            </div>
                        </div>
                        @endif

                        <!-- الملاحظات -->
                        @if($proof->notes)
                        <div class="mb-4">
                            <div class="bg-white rounded-xl p-3 border border-white/50">
                                <div class="text-xs text-slate-600 mb-1">ملاحظات:</div>
                                <div class="text-sm text-slate-800">{{ $proof->notes }}</div>
                            </div>
                        </div>
                        @endif

                        <!-- أزرار العمليات -->
                        @if($proof->file_path)
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ asset('/' . $proof->file_path) }}" target="_blank"
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-white text-slate-700 border-2 border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 hover:border-slate-400 transition-all">
                                <i class="fas fa-eye mr-2"></i>
                                عرض المستند
                            </a>

                            <a href="{{ asset('/' . $proof->file_path) }}" download
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 {{ $fileInfo['color'] }} border-2 border-current rounded-xl text-sm font-bold hover:bg-white transition-all">
                                <i class="fas fa-download mr-2"></i>
                                تحميل
                            </a>
                        </div>
                        @else
                        <div class="text-center py-4 text-gray-400">
                            <i class="fas fa-file-slash text-2xl mb-2"></i>
                            <p class="text-sm">لا يوجد ملف مرفق</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- إحصائيات المستندات -->
                <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-lg font-bold text-slate-900">{{ $paymentRequest->paymentProofs->count() }}</div>
                            <div class="text-xs text-slate-600">إجمالي المستندات</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-600">{{ $paymentRequest->paymentProofs->where('status', 'approved')->count() }}</div>
                            <div class="text-xs text-slate-600">موافق عليها</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-yellow-600">{{ $paymentRequest->paymentProofs->where('status', 'pending')->count() }}</div>
                            <div class="text-xs text-slate-600">في الانتظار</div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">
                    <i class="fas fa-file-invoice mr-2"></i>
                    مستندات تأكيد الدفع
                </h3>
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-file-upload text-4xl mb-4"></i>
                    <h4 class="text-lg font-semibold mb-2">لم يتم رفع أي مستندات</h4>
                    <p class="text-sm">لا توجد مستندات تأكيد دفع مرفقة لهذا الطلب</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Update -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">تحديث الحالة</h3>

                <form method="POST" action="{{ route('admin.payments.update_status', $paymentRequest->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الحالة الجديدة</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $paymentRequest->status === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="processing" {{ $paymentRequest->status === 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="completed" {{ $paymentRequest->status === 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="failed" {{ $paymentRequest->status === 'failed' ? 'selected' : '' }}>فاشل</option>
                            <option value="cancelled" {{ $paymentRequest->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات الإدارة</label>
                        <textarea name="admin_notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="أضف ملاحظات إدارية...">{{ $paymentRequest->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        <i class="fas fa-save ml-2"></i>
                        تحديث الحالة
                    </button>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">إحصائيات سريعة</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">رقم الطلب:</span>
                        <span class="font-semibold">#{{ $paymentRequest->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">تاريخ الطلب:</span>
                        <span class="font-semibold">{{ $paymentRequest->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">آخر تحديث:</span>
                        <span class="font-semibold">{{ $paymentRequest->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($paymentRequest->processed_at)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">تاريخ المعالجة:</span>
                        <span class="font-semibold">{{ $paymentRequest->processed_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
