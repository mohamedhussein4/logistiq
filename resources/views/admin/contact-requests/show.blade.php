@extends('layouts.admin')

@section('title', 'تفاصيل طلب التواصل')
@section('page-title', 'تفاصيل طلب التواصل')
@section('page-description', 'عرض تفاصيل طلب التواصل والرد عليه')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">طلب التواصل #{{ $contactRequest->id ?? 4 }}</h1>
                <p class="text-slate-600">تفاصيل طلب التواصل من {{ $contactRequest->name ?? 'أحمد محمد السعود' }}</p>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                @php
                    $statusClasses = [
                        'new' => 'bg-red-100 text-red-800',
                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                        'resolved' => 'bg-green-100 text-green-800',
                        'closed' => 'bg-gray-100 text-gray-800'
                    ];
                    $statusNames = [
                        'new' => 'جديد',
                        'in_progress' => 'قيد المتابعة',
                        'resolved' => 'تم الحل',
                        'closed' => 'مغلق'
                    ];
                    $currentStatus = $contactRequest->status ?? 'new';
                @endphp
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $statusClasses[$currentStatus] }}">
                    {{ $statusNames[$currentStatus] }}
                </span>
                <a href="{{ route('admin.contact_requests.index') }}" class="px-4 py-2 bg-gray-200 text-slate-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Request Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contact Information -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">معلومات المرسل</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الاسم الكامل</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $contactRequest->name ?? 'أحمد محمد السعود' }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                <a href="mailto:{{ $contactRequest->email ?? 'ahmed.alsaud@example.com' }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $contactRequest->email ?? 'ahmed.alsaud@example.com' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">رقم الهاتف</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                @if($contactRequest->phone ?? '+966 50 123 4567')
                                    <a href="tel:{{ $contactRequest->phone ?? '+966501234567' }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contactRequest->phone ?? '+966 50 123 4567' }}
                                    </a>
                                @else
                                    <span class="text-gray-500">غير محدد</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الشركة</label>
                            <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900">
                                {{ $contactRequest->company ?? 'شركة التجارة المتقدمة' ?: 'غير محدد' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">محتوى الرسالة</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الموضوع</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 font-semibold">
                            {{ $contactRequest->subject ?? 'استفسار حول خدمات التمويل اللوجستي' }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">الرسالة</label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-900 min-h-32 whitespace-pre-wrap">{{ $contactRequest->message ?? 'أرغب في التعرف على خدمات التمويل اللوجستي التي تقدمونها وكيفية الاستفادة منها لشركتنا. نحن شركة تجارية متوسطة الحجم ونحتاج لحلول تمويلية مرنة تساعدنا على تطوير عملياتنا اللوجستية.

نرجو منكم التواصل معنا في أقرب وقت ممكن لمناقشة التفاصيل والشروط المتاحة.

شكراً لكم.' }}</div>
                    </div>
                </div>
            </div>

            <!-- Attachments (if any) -->
            @if(isset($contactRequest->attachments) && !empty($contactRequest->attachments))
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">المرفقات</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($contactRequest->attachments as $attachment)
                    <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center ml-3">
                            <i class="fas fa-file text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-slate-900">{{ $attachment['name'] }}</div>
                            <div class="text-sm text-slate-500">{{ $attachment['size'] }}</div>
                        </div>
                        <a href="{{ $attachment['url'] }}" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition-colors">
                            تحميل
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Response Form -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
                <h3 class="text-xl font-bold gradient-text mb-6">الرد على الطلب</h3>

                <form method="POST" action="{{ route('admin.contact_requests.respond', $contactRequest->id ?? 4) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">نوع الرد</label>
                        <select name="response_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="email">رد عبر البريد الإلكتروني</option>
                            <option value="phone">اتصال هاتفي</option>
                            <option value="meeting">طلب اجتماع</option>
                            <option value="internal_note">ملاحظة داخلية فقط</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">محتوى الرد</label>
                        <textarea name="response_message" rows="8" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                  placeholder="اكتب ردك هنا...">عزيزي {{ $contactRequest->name ?? 'أحمد' }}،

شكراً لتواصلكم معنا والاهتمام بخدماتنا.

نحن سعداء لإفادتكم بأن لدينا حلول تمويلية متنوعة تناسب احتياجات الشركات اللوجستية من جميع الأحجام.

سيقوم فريقنا المختص بالتواصل معكم خلال 24 ساعة لمناقشة التفاصيل وتقديم عرض مخصص لشركتكم.

مع تحياتنا،
فريق Link2u</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">تحديث حالة الطلب</label>
                        <select name="new_status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">عدم التغيير</option>
                            <option value="in_progress">نقل لقيد المتابعة</option>
                            <option value="resolved">تم الحل</option>
                            <option value="closed">إغلاق الطلب</option>
                        </select>
                    </div>

                    <div class="flex space-x-4 space-x-reverse pt-4">
                        <button type="submit" class="flex-1 lg:flex-none px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold hover-lift transition-all">
                            <i class="fas fa-paper-plane mr-2"></i>
                            إرسال الرد
                        </button>

                        <button type="button" onclick="saveDraft()" class="flex-1 lg:flex-none px-6 py-3 bg-gradient-secondary text-white rounded-xl font-semibold hover-lift transition-all">
                            <i class="fas fa-save mr-2"></i>
                            حفظ كمسودة
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">إجراءات سريعة</h4>

                <div class="space-y-3">
                    @if($currentStatus === 'new')
                    <form method="POST" action="{{ route('admin.contact_requests.update_status', $contactRequest->id ?? 4) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-xl font-semibold hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-clock mr-2"></i>
                            بدء المتابعة
                        </button>
                    </form>
                    @endif

                    @if($currentStatus === 'in_progress')
                    <form method="POST" action="{{ route('admin.contact_requests.update_status', $contactRequest->id ?? 4) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            تم الحل
                        </button>
                    </form>
                    @endif

                    <a href="mailto:{{ $contactRequest->email ?? 'ahmed.alsaud@example.com' }}" class="block w-full px-4 py-2 bg-blue-500 text-white rounded-xl font-semibold text-center hover:bg-blue-600 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        رد مباشر
                    </a>

                    @if($contactRequest->phone ?? '+966501234567')
                    <a href="tel:{{ $contactRequest->phone ?? '+966501234567' }}" class="block w-full px-4 py-2 bg-purple-500 text-white rounded-xl font-semibold text-center hover:bg-purple-600 transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        اتصال هاتفي
                    </a>
                    @endif

                    <button onclick="markImportant()" class="w-full px-4 py-2 bg-orange-500 text-white rounded-xl font-semibold hover:bg-orange-600 transition-colors">
                        <i class="fas fa-star mr-2"></i>
                        تمييز كمهم
                    </button>
                </div>
            </div>

            <!-- Request Info -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">معلومات الطلب</h4>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">تاريخ الإرسال:</span>
                        <span class="font-semibold">{{ $contactRequest->created_at->format('Y-m-d H:i') ?? '2024-01-15 10:30' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">آخر تحديث:</span>
                        <span class="font-semibold">{{ $contactRequest->updated_at->format('Y-m-d H:i') ?? '2024-01-15 10:30' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">الأولوية:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                            {{ $contactRequest->priority ?? 'متوسطة' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-600">المسؤول:</span>
                        <span class="font-semibold">{{ $contactRequest->assigned_to ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </div>

            <!-- Response History -->
            <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white/20">
                <h4 class="text-lg font-bold gradient-text mb-4">تاريخ الردود</h4>

                @php
                    $responses = $contactRequest->responses ?? [
                        [
                            'type' => 'status_change',
                            'message' => 'تم تحديث الحالة إلى: قيد المتابعة',
                            'user' => 'أحمد الإدارة',
                            'created_at' => '2024-01-15 11:00'
                        ]
                    ];
                @endphp

                @if(!empty($responses))
                    <div class="space-y-3">
                        @foreach($responses as $response)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex items-start justify-between mb-2">
                                <span class="text-sm font-semibold text-slate-900">{{ $response['user'] }}</span>
                                <span class="text-xs text-slate-500">{{ $response['created_at'] }}</span>
                            </div>
                            <p class="text-sm text-slate-700">{{ $response['message'] }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 text-center py-4">لا توجد ردود سابقة</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function saveDraft() {
        const message = document.querySelector('textarea[name="response_message"]').value;
        localStorage.setItem('contact_response_draft_{{ $contactRequest->id ?? 4 }}', message);
        alert('تم حفظ المسودة بنجاح');
    }

    function markImportant() {
        if (confirm('هل تريد تمييز هذا الطلب كمهم؟')) {
            fetch('/admin/contact-requests/{{ $contactRequest->id ?? 4 }}/important', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                alert('تم تمييز الطلب كمهم');
                location.reload();
            })
            .catch(error => {
                alert('حدث خطأ أثناء تمييز الطلب');
            });
        }
    }

    // Load draft if exists
    document.addEventListener('DOMContentLoaded', function() {
        const draft = localStorage.getItem('contact_response_draft_{{ $contactRequest->id ?? 4 }}');
        if (draft) {
            document.querySelector('textarea[name="response_message"]').value = draft;
        }
    });
</script>
@endpush
@endsection
