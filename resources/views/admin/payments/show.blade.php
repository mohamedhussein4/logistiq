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
                                    'electronic_wallet' => 'محفظة إلكترونية',
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
                                    {{ $paymentRequest->bankAccount->account_holder_name }}
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
                @elseif($paymentRequest->payment_method === 'electronic_wallet' && $paymentRequest->electronicWallet)
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">اسم المحفظة</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                    {{ $paymentRequest->electronicWallet->wallet_name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">رقم المحفظة</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-mono">
                                    {{ $paymentRequest->electronicWallet->wallet_number }}
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">اسم صاحب المحفظة</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                    {{ $paymentRequest->electronicWallet->wallet_holder_name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">نوع المحفظة</label>
                                <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                    {{ $paymentRequest->electronicWallet->wallet_type }}
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
                <h3 class="text-xl font-bold gradient-text mb-6">إثباتات الدفع</h3>

                <div class="space-y-4">
                    @foreach($paymentRequest->paymentProofs as $proof)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $proof->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($proof->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($proof->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $proof->status === 'approved' ? 'موافق عليه' :
                                       ($proof->status === 'pending' ? 'في الانتظار' :
                                       ($proof->status === 'rejected' ? 'مرفوض' : $proof->status)) }}
                                </span>
                                <span class="text-sm text-gray-600">{{ $proof->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>

                        @if($proof->file_path)
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-file-alt text-blue-600"></i>
                            <a href="{{ asset('storage/' . $proof->file_path) }}" target="_blank"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                عرض الملف
                            </a>
                            <a href="{{ asset('storage/' . $proof->file_path) }}" download
                               class="text-green-600 hover:text-green-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        @endif

                        @if($proof->notes)
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">ملاحظات:</span> {{ $proof->notes }}
                        </div>
                        @endif
                    </div>
                    @endforeach
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
