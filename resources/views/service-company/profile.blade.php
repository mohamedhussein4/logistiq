@extends('layouts.main')

@section('title', 'الملف الشخصي - Link2u')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-green-600 to-green-700 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">الملف الشخصي</h1>
                <p class="text-green-100">إدارة بياناتك الشخصية ومتابعة أعمالك المالية</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                    <div class="text-sm opacity-80">آخر تحديث</div>
                    <div class="font-semibold">{{ auth()->user()->updated_at->format('Y-m-d') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-6 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Outstanding -->
            <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-6 border border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-600 mb-1">إجمالي المستحقات</p>
                        <p class="text-2xl font-bold text-red-700">{{ number_format($stats['total_outstanding'] ?? 0) }} ر.س</p>
                    </div>
                    <div class="bg-red-200 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Paid This Month -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 mb-1">مدفوع هذا الشهر</p>
                        <p class="text-2xl font-bold text-green-700">{{ number_format($stats['paid_this_month'] ?? 0) }} ر.س</p>
                    </div>
                    <div class="bg-green-200 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 mb-1">إجمالي الفواتير</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $stats['total_invoices'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-200 p-3 rounded-full">
                        <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Next Payment -->
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-600 mb-1">الدفعة القادمة</p>
                        @if(isset($stats['next_payment_due']))
                            <p class="text-lg font-bold text-yellow-700">{{ $stats['next_payment_due'] }}</p>
                            <p class="text-sm text-yellow-600">{{ number_format($stats['next_payment_amount']) }} ر.س</p>
                        @else
                            <p class="text-lg font-bold text-yellow-700">لا توجد مستحقات</p>
                        @endif
                    </div>
                    <div class="bg-yellow-200 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 left-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block font-bold">يوجد أخطاء في النموذج:</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 left-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times cursor-pointer"></i>
                </span>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Profile Info Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-building text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">{{ auth()->user()->name }}</h3>
                        <p class="text-gray-600">{{ auth()->user()->email }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                            <i class="fas fa-check-circle ml-1"></i>
                            حساب نشط
                        </span>
                    </div>

                    <!-- Quick Stats -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">نوع الحساب</span>
                            <span class="text-sm font-medium text-green-600">شركة طالبة للخدمة</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">تاريخ التسجيل</span>
                            <span class="text-sm font-medium">{{ auth()->user()->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">رقم الشركة</span>
                            <span class="text-sm font-medium">{{ auth()->user()->company_registration ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">الفواتير المدفوعة</span>
                            <span class="text-sm font-medium text-green-600">{{ $stats['paid_invoices'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">الفواتير المعلقة</span>
                            <span class="text-sm font-medium text-yellow-600">{{ $stats['pending_invoices'] ?? 0 }}</span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('service_company.dashboard') }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-dashboard ml-2"></i>
                            العودة للوحة التحكم
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Forms and Data -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-user-edit text-green-600 ml-2"></i>
                            المعلومات الشخصية
                        </h3>
                    </div>

                    <form action="{{ route('service_company.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                                <input type="text"
                                       name="name"
                                       value="{{ auth()->user()->name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror"
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email"
                                       name="email"
                                       value="{{ auth()->user()->email }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror"
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ auth()->user()->phone }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') border-red-500 @enderror"
                                       placeholder="+966501234567">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اسم الشركة</label>
                                <input type="text"
                                       name="company_name"
                                       value="{{ auth()->user()->company_name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('company_name') border-red-500 @enderror"
                                       placeholder="اسم شركتك">
                                @error('company_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم السجل التجاري</label>
                            <input type="text"
                                   name="company_registration"
                                   value="{{ auth()->user()->company_registration }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('company_registration') border-red-500 @enderror"
                                   placeholder="رقم السجل التجاري">
                            @error('company_registration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Change -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-lock text-green-600 ml-2"></i>
                            تغيير كلمة المرور
                        </h3>
                    </div>

                    <form action="{{ route('service_company.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية</label>
                            <input type="password"
                                   name="current_password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('current_password') border-red-500 @enderror"
                                   required>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                                <input type="password"
                                       name="new_password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('new_password') border-red-500 @enderror"
                                       required>
                                @error('new_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور الجديدة</label>
                                <input type="password"
                                       name="new_password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                       required>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-key ml-2"></i>
                                تحديث كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-file-invoice text-green-600 ml-2"></i>
                            الفواتير الحديثة
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($invoices->take(5) as $invoice)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-800">{{ $invoice->invoice_number }}</h4>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                {{ $invoice->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                                   ($invoice->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($invoice->payment_status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $invoice->payment_status_label }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">الشركة اللوجيستية:</span>
                                                {{ $invoice->logisticsCompany->name ?? 'غير محدد' }}
                                            </div>
                                            <div>
                                                <span class="font-medium">المبلغ:</span>
                                                {{ number_format($invoice->original_amount) }} ر.س
                                            </div>
                                            <div>
                                                <span class="font-medium">تاريخ الاستحقاق:</span>
                                                {{ $invoice->due_date->format('Y-m-d') }}
                                            </div>
                                            <div>
                                                <span class="font-medium">المبلغ المتبقي:</span>
                                                {{ number_format($invoice->remaining_amount) }} ر.س
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-file-invoice text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">لا توجد فواتير حتى الآن</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-credit-card text-green-600 ml-2"></i>
                            المدفوعات الحديثة
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($payments->take(5) as $payment)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-800">{{ $payment->invoice->invoice_number ?? 'غير محدد' }}</h4>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $payment->status_label }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">المبلغ:</span>
                                                {{ number_format($payment->amount) }} ر.س
                                            </div>
                                            <div>
                                                <span class="font-medium">طريقة الدفع:</span>
                                                {{ $payment->payment_method_label }}
                                            </div>
                                            <div>
                                                <span class="font-medium">تاريخ الدفع:</span>
                                                {{ $payment->created_at->format('Y-m-d') }}
                                            </div>
                                            <div>
                                                <span class="font-medium">رقم المرجع:</span>
                                                {{ $payment->reference_number ?? 'غير محدد' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-credit-card text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">لا توجد مدفوعات حتى الآن</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Payment Requests -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-clock text-blue-600 ml-2"></i>
                            طلبات الدفع الحديثة
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($paymentRequests->take(5) as $request)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-800">{{ $request->request_number }}</h4>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' :
                                                   ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $request->status === 'approved' ? 'مؤكد' :
                                                   ($request->status === 'pending' ? 'في الانتظار' :
                                                   ($request->status === 'rejected' ? 'مرفوض' : $request->status)) }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">نوع الدفع:</span>
                                                {{ $request->payment_type === 'invoice' ? 'فاتورة' : 'طلب آخر' }}
                                            </div>
                                            <div>
                                                <span class="font-medium">المبلغ:</span>
                                                {{ number_format($request->amount) }} ر.س
                                            </div>
                                            <div>
                                                <span class="font-medium">طريقة الدفع:</span>
                                                {{ $request->payment_method === 'bank_transfer' ? 'تحويل بنكي' : 'محفظة إلكترونية' }}
                                            </div>
                                            <div>
                                                <span class="font-medium">تاريخ الطلب:</span>
                                                {{ $request->created_at->format('Y-m-d') }}
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium">الحساب:</span>
                                            @if($request->payment_method === 'bank_transfer' && $request->bankAccount)
                                                {{ $request->bankAccount->bank_name }} - {{ $request->bankAccount->account_number }}
                                            @else
                                                غير محدد
                                            @endif
                                        </div>
                                        @if($request->payment_notes)
                                            <div class="mt-2 p-2 bg-gray-50 rounded text-sm text-gray-600">
                                                <span class="font-medium">ملاحظات:</span> {{ $request->payment_notes }}
                                            </div>
                                        @endif

                                        <!-- إثباتات التحويل -->
                                        @if($request->paymentProofs && $request->paymentProofs->count() > 0)
                                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-medium text-blue-800">
                                                        <i class="fas fa-file-check ml-1"></i>
                                                        إثباتات التحويل ({{ $request->paymentProofs->count() }})
                                                    </span>
                                                </div>
                                                <div class="space-y-2">
                                                    @foreach($request->paymentProofs as $proof)
                                                        <div class="flex items-center justify-between bg-white rounded p-2 text-xs">
                                                            <div class="flex items-center">
                                                                @php
                                                                    $extension = pathinfo($proof->file_name, PATHINFO_EXTENSION);
                                                                    $iconClass = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'fa-file-image text-green-600' :
                                                                               ($extension === 'pdf' ? 'fa-file-pdf text-red-600' : 'fa-file text-gray-600');
                                                                @endphp
                                                                <i class="fas {{ $iconClass }} ml-2"></i>
                                                                <span class="text-gray-700">{{ $proof->file_name }}</span>
                                                            </div>
                                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                                <span class="px-2 py-1 rounded-full text-xs
                                                                    {{ $proof->status === 'approved' ? 'bg-green-100 text-green-700' :
                                                                       ($proof->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                                    {{ $proof->status === 'approved' ? 'موافق عليه' :
                                                                       ($proof->status === 'pending' ? 'قيد المراجعة' : 'مرفوض') }}
                                                                </span>
                                                                <a href="{{ asset('/' . $proof->file_path) }}" target="_blank"
                                                                   class="text-blue-600 hover:text-blue-800">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-3 p-2 bg-orange-50 rounded text-xs text-orange-700">
                                                <i class="fas fa-exclamation-triangle ml-1"></i>
                                                لم يتم رفع إثبات تحويل لهذا الطلب
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-clock text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">لا توجد طلبات دفع حتى الآن</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Product Orders -->
                @if($productOrders && $productOrders->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-shopping-cart text-orange-600 ml-2"></i>
                            طلبات المنتجات الحديثة
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @foreach($productOrders->take(5) as $order)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-800">{{ $order->product->name ?? 'منتج محذوف' }}</h4>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($order->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $order->status === 'pending' ? 'في الانتظار' :
                                                   ($order->status === 'confirmed' ? 'مؤكد' :
                                                   ($order->status === 'cancelled' ? 'ملغي' : $order->status)) }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">الكمية:</span>
                                                {{ $order->quantity }}
                                            </div>
                                            <div>
                                                <span class="font-medium">السعر:</span>
                                                {{ number_format($order->unit_price) }} ر.س
                                            </div>
                                            <div>
                                                <span class="font-medium">الإجمالي:</span>
                                                {{ number_format($order->total_amount) }} ر.س
                                            </div>
                                            <div>
                                                <span class="font-medium">تاريخ الطلب:</span>
                                                {{ $order->created_at->format('Y-m-d') }}
                                            </div>
                                        </div>
                                        @if($order->notes)
                                            <div class="mt-2 p-2 bg-gray-50 rounded text-sm text-gray-600">
                                                <span class="font-medium">ملاحظات:</span> {{ $order->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Logistics Companies -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-truck text-green-600 ml-2"></i>
                            الشركات اللوجيستية المتعاملة معها
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @forelse($logisticsCompanies as $logistics)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800 mb-2">{{ $logistics->name }}</h4>
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">إجمالي الفواتير:</span>
                                                {{ $logistics->total_invoices }}
                                            </div>
                                            <div>
                                                <span class="font-medium">إجمالي المبلغ:</span>
                                                {{ number_format($logistics->original_amount) }} ر.س
                                            </div>
                                            <div>
                                                <span class="font-medium">البريد الإلكتروني:</span>
                                                {{ $logistics->email }}
                                            </div>
                                            <div>
                                                <span class="font-medium">رقم الهاتف:</span>
                                                {{ $logistics->phone ?? 'غير محدد' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-truck text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">لا توجد شركات لوجيستية متعاملة معها حتى الآن</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
