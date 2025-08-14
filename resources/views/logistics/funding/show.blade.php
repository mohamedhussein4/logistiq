@extends('layouts.main')

@section('title', 'تفاصيل طلب التمويل')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">تفاصيل طلب التمويل</h1>
                <p class="text-gray-600 mt-1">طلب رقم #{{ $fundingRequest->id }} - {{ $fundingRequest->created_at->diffForHumans() }}</p>
            </div>
            <div>
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold
                    @if($fundingRequest->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($fundingRequest->status == 'approved') bg-green-100 text-green-800
                    @elseif($fundingRequest->status == 'rejected') bg-red-100 text-red-800
                    @elseif($fundingRequest->status == 'disbursed') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    <i class="fas fa-circle text-xs ml-3
                        @if($fundingRequest->status == 'pending') text-yellow-600
                        @elseif($fundingRequest->status == 'approved') text-green-600
                        @elseif($fundingRequest->status == 'rejected') text-red-600
                        @elseif($fundingRequest->status == 'disbursed') text-blue-600
                        @else text-gray-600
                        @endif"></i>
                    @if($fundingRequest->status == 'pending') معلق
                    @elseif($fundingRequest->status == 'approved') معتمد
                    @elseif($fundingRequest->status == 'rejected') مرفوض
                    @elseif($fundingRequest->status == 'disbursed') تم الصرف
                    @elseif($fundingRequest->status == 'cancelled') ملغي
                    @else {{ $fundingRequest->status }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- معلومات أساسية -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-full ml-3">
                    <i class="fas fa-money-check-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">معلومات الطلب</h3>
                    <p class="text-sm text-gray-500">البيانات الأساسية للطلب</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">المبلغ المطلوب</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format((float)$fundingRequest->amount) }}</p>
                    <p class="text-xs text-gray-500">ريال سعودي</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">سبب التمويل</p>
                    <p class="text-lg font-semibold text-gray-900">
                        @if($fundingRequest->reason == 'operational') تشغيلية
                        @elseif($fundingRequest->reason == 'expansion') توسع
                        @elseif($fundingRequest->reason == 'equipment') معدات
                        @elseif($fundingRequest->reason == 'emergency') طارئة
                        @else أخرى
                        @endif
                    </p>
                </div>

                <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">تاريخ الإنشاء</p>
                    <p class="text-gray-900">{{ $fundingRequest->created_at->format('d/m/Y - H:i') }}</p>
                </div>

                @if($fundingRequest->description)
                <div class="col-span-2">
                    <p class="text-sm text-gray-600 mb-2">وصف إضافي</p>
                    <p class="text-gray-900 bg-blue-50 p-3 rounded-lg text-sm border-r-4 border-blue-200">
                        {{ $fundingRequest->description }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- الإجراءات والمستندات -->
        <div class="lg:col-span-2 space-y-6">
            @if($fundingRequest->status == 'pending')
            <!-- الإجراءات -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-100 p-3 rounded-full ml-3">
                        <i class="fas fa-tools text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">الإجراءات المتاحة</h3>
                        <p class="text-sm text-gray-500">يمكنك إلغاء الطلب المعلق</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logistics.funding.cancel', $fundingRequest->id) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')"
                            class="w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition duration-200 font-medium">
                        <i class="fas fa-times ml-3"></i>
                        إلغاء الطلب
                    </button>
                </form>
            </div>
            @endif

            <!-- المستندات -->
            @if($fundingRequest->documents && count($fundingRequest->documents) > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-full ml-3">
                        <i class="fas fa-paperclip text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">المستندات المرفقة</h3>
                        <p class="text-sm text-gray-500">{{ count($fundingRequest->documents) }} مستند</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($fundingRequest->documents as $index => $document)
                    <a href="{{ asset('/' . $document) }}"
                       target="_blank"
                       class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                        <i class="fas fa-file-alt text-gray-400 ml-3"></i>
                        <span class="text-sm text-gray-700 font-medium">مستند {{ $index + 1 }}</span>
                        <i class="fas fa-external-link-alt text-gray-400 mr-auto text-xs"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- بيانات الشركات الطالبة للخدمة -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center mb-6">
            <div class="bg-green-100 p-3 rounded-full ml-3">
                <i class="fas fa-building text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">الشركات الطالبة للخدمة</h3>
                <p class="text-sm text-gray-500">{{ $fundingRequest->clientDebts->count() }} شركة مرتبطة بهذا الطلب</p>
            </div>
            <div class="mr-auto">
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                    إجمالي: {{ number_format($fundingRequest->clientDebts->sum('amount')) }} ر.س
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($fundingRequest->clientDebts as $index => $debt)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                        شركة {{ $index + 1 }}
                    </span>
                    <span class="text-lg font-bold text-green-600">
                        {{ number_format((float)$debt->amount) }} ر.س
                    </span>
                </div>

                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-500">اسم الشركة:</span>
                        <p class="font-semibold text-gray-900">{{ $debt->company_name }}</p>
                    </div>

                    <div>
                        <span class="text-gray-500">الشخص المسؤول:</span>
                        <p class="text-gray-700">{{ $debt->contact_person }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-1">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 text-xs ml-3"></i>
                            <span class="text-gray-600 text-xs">{{ $debt->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 text-xs ml-3"></i>
                            <span class="text-gray-600 text-xs">{{ $debt->phone }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-gray-400 text-xs ml-3"></i>
                            <span class="text-gray-600 text-xs">{{ \Carbon\Carbon::parse($debt->due_date)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    @if($debt->invoice_document)
                    <div class="pt-2 border-t border-gray-200">
                        <a href="{{ asset('/' . $debt->invoice_document) }}"
                           target="_blank"
                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                            <i class="fas fa-file-pdf ml-1"></i>
                            عرض الفاتورة
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- معلومات إضافية وروابط -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-gray-100 p-3 rounded-full ml-3">
                    <i class="fas fa-info-circle text-gray-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">معلومات الطلب</h3>
                    <p class="text-sm text-gray-500">تفاصيل إضافية وأوقات هامة</p>
                </div>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('logistics.dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-home ml-3"></i>
                    لوحة التحكم
                </a>
                <a href="{{ route('logistics.funding.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                    <i class="fas fa-list ml-3"></i>
                    كل الطلبات
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-xs text-gray-500 mb-1">رقم الطلب</p>
                <p class="font-bold text-gray-900">#{{ $fundingRequest->id }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-xs text-gray-500 mb-1">تاريخ الإنشاء</p>
                <p class="font-bold text-gray-900">{{ $fundingRequest->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-xs text-gray-500 mb-1">آخر تحديث</p>
                <p class="font-bold text-gray-900">{{ $fundingRequest->updated_at->format('d/m/Y') }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg text-center">
                <p class="text-xs text-gray-500 mb-1">عدد الشركات</p>
                <p class="font-bold text-gray-900">{{ $fundingRequest->clientDebts->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
