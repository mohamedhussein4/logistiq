@extends('layouts.admin')

@section('title', 'إنشاء فاتورة جديدة')
@section('page-title', 'إنشاء فاتورة جديدة')
@section('page-description', 'إنشاء فاتورة جديدة للشركات الطالبة للخدمة')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-2">إنشاء فاتورة جديدة</h1>
                <p class="text-slate-600">إنشاء فاتورة جديدة للشركات الطالبة للخدمة</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-invoice text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Invoice Form -->
    <form method="POST" action="{{ route('admin.invoices.store') }}" class="space-y-6">
        @csrf

        <!-- Invoice Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات الفاتورة</h3>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">رقم الفاتورة <span class="text-red-500">*</span></label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number', 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('invoice_number') border-red-500 @enderror"
                           placeholder="INV-2024-0001">
                    @error('invoice_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الفاتورة <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('invoice_date') border-red-500 @enderror">
                    @error('invoice_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الاستحقاق <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات العميل</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اختر العميل <span class="text-red-500">*</span></label>
                    <select name="service_company_id" required onchange="loadClientData(this.value)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('service_company_id') border-red-500 @enderror">
                        <option value="">اختر شركة طالبة للخدمة</option>
                        <option value="1" {{ old('service_company_id') == 1 ? 'selected' : '' }}>شركة التجارة المتقدمة</option>
                        <option value="2" {{ old('service_company_id') == 2 ? 'selected' : '' }}>مؤسسة الأعمال الحديثة</option>
                        <option value="3" {{ old('service_company_id') == 3 ? 'selected' : '' }}>شركة الشحن السريع</option>
                        <option value="4" {{ old('service_company_id') == 4 ? 'selected' : '' }}>مجموعة التوزيع الذكي</option>
                    </select>
                    @error('service_company_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الشركة اللوجستية المرتبطة</label>
                    <select name="logistics_company_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('logistics_company_id') border-red-500 @enderror">
                        <option value="">اختر الشركة اللوجستية</option>
                        <option value="1" {{ old('logistics_company_id') == 1 ? 'selected' : '' }}>شركة النقل السريع</option>
                        <option value="2" {{ old('logistics_company_id') == 2 ? 'selected' : '' }}>مؤسسة الشحن المتطور</option>
                        <option value="3" {{ old('logistics_company_id') == 3 ? 'selected' : '' }}>شركة التوصيل الفوري</option>
                    </select>
                    @error('logistics_company_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Client Details Preview -->
            <div id="client-details" class="mt-6 p-4 bg-gray-50 rounded-xl hidden">
                <h4 class="font-bold text-slate-700 mb-3">تفاصيل العميل</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
                    <div><strong>اسم الشركة:</strong> <span id="client-name">-</span></div>
                    <div><strong>البريد الإلكتروني:</strong> <span id="client-email">-</span></div>
                    <div><strong>رقم الهاتف:</strong> <span id="client-phone">-</span></div>
                    <div><strong>العنوان:</strong> <span id="client-address">-</span></div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold gradient-text">بنود الفاتورة</h3>
                <button type="button" onclick="addInvoiceItem()"
                        class="px-4 py-2 bg-gradient-secondary text-white rounded-xl font-semibold hover-lift transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    إضافة بند
                </button>
            </div>

            <div id="invoice-items" class="space-y-4">
                <!-- Invoice Item Template -->
                <div class="invoice-item border border-gray-200 rounded-xl p-4">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">الوصف <span class="text-red-500">*</span></label>
                            <input type="text" name="items[0][description]" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                   placeholder="وصف الخدمة أو المنتج">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الكمية <span class="text-red-500">*</span></label>
                            <input type="number" name="items[0][quantity]" min="1" value="1" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all item-quantity"
                                   onchange="calculateItemTotal(this)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">السعر (ريال) <span class="text-red-500">*</span></label>
                            <input type="number" name="items[0][unit_price]" step="0.01" min="0" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all item-price"
                                   onchange="calculateItemTotal(this)">
                        </div>

                        <div class="flex items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-slate-700 mb-2">المجموع</label>
                                <div class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-lg font-bold text-slate-900 item-total">
                                    0.00 ريال
                                </div>
                            </div>
                            <button type="button" onclick="removeInvoiceItem(this)"
                                    class="mr-2 w-10 h-10 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">ملخص الفاتورة</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Calculations -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="font-semibold text-slate-700">المجموع الفرعي:</span>
                        <span class="text-lg font-bold text-slate-900" id="subtotal">0.00 ريال</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="font-semibold text-slate-700">ضريبة القيمة المضافة (15%):</span>
                        <span class="text-lg font-bold text-slate-900" id="tax-amount">0.00 ريال</span>
                    </div>

                    <div class="flex justify-between items-center p-4 bg-gradient-primary rounded-xl text-white">
                        <span class="font-bold text-lg">المجموع الكلي:</span>
                        <span class="text-xl font-black" id="total-amount">0.00 ريال</span>
                    </div>

                    <input type="hidden" name="subtotal" id="subtotal-input" value="0">
                    <input type="hidden" name="tax_amount" id="tax-input" value="0">
                    <input type="hidden" name="original_amount" id="total-input" value="0">
                </div>

                <!-- Payment Terms -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">شروط الدفع</label>
                        <select name="payment_terms"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="net_30" {{ old('payment_terms') == 'net_30' ? 'selected' : '' }}>مطلوب خلال 30 يوم</option>
                            <option value="net_15" {{ old('payment_terms') == 'net_15' ? 'selected' : '' }}>مطلوب خلال 15 يوم</option>
                            <option value="net_7" {{ old('payment_terms') == 'net_7' ? 'selected' : '' }}>مطلوب خلال 7 أيام</option>
                            <option value="immediate" {{ old('payment_terms') == 'immediate' ? 'selected' : '' }}>مطلوب فوراً</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">طريقة الدفع المفضلة</label>
                        <select name="preferred_payment_method"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="bank_transfer" {{ old('preferred_payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="check" {{ old('preferred_payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                            <option value="cash" {{ old('preferred_payment_method') == 'cash' ? 'selected' : '' }}>نقداً</option>
                            <option value="online" {{ old('preferred_payment_method') == 'online' ? 'selected' : '' }}>دفع إلكتروني</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">حالة الفاتورة</label>
                        <select name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="sent" {{ old('status', 'sent') == 'sent' ? 'selected' : '' }}>مرسلة</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <h3 class="text-xl font-bold gradient-text mb-6">معلومات إضافية</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات للعميل</label>
                    <textarea name="notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="أي ملاحظات أو تعليمات للعميل">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات داخلية</label>
                    <textarea name="internal_notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                              placeholder="ملاحظات للاستخدام الداخلي فقط">{{ old('internal_notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="glass-effect rounded-2xl lg:rounded-3xl p-4 lg:p-8 border border-white/20">
            <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4 lg:space-x-reverse">
                <button type="submit" name="action" value="save" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-primary text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-save mr-2"></i>
                    حفظ الفاتورة
                </button>

                <button type="submit" name="action" value="save_and_send" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-success text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-paper-plane mr-2"></i>
                    حفظ وإرسال
                </button>

                <button type="button" onclick="previewInvoice()" class="flex-1 lg:flex-none px-8 py-4 bg-gradient-warning text-white rounded-xl font-bold text-lg hover-lift transition-all">
                    <i class="fas fa-eye mr-2"></i>
                    معاينة
                </button>

                <a href="{{ route('admin.invoices.index') }}" class="flex-1 lg:flex-none px-8 py-4 bg-gray-200 text-slate-700 rounded-xl font-bold text-lg text-center hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    إلغاء
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let itemCount = 1;

    // Client data
    const clientsData = {
        1: { name: 'شركة التجارة المتقدمة', email: 'info@advanced-trade.com', phone: '+966 11 123 4567', address: 'الرياض، المملكة العربية السعودية' },
        2: { name: 'مؤسسة الأعمال الحديثة', email: 'contact@modern-business.com', phone: '+966 11 234 5678', address: 'جدة، المملكة العربية السعودية' },
        3: { name: 'شركة الشحن السريع', email: 'info@fast-shipping.com', phone: '+966 11 345 6789', address: 'الدمام، المملكة العربية السعودية' },
        4: { name: 'مجموعة التوزيع الذكي', email: 'info@smart-distribution.com', phone: '+966 11 456 7890', address: 'مكة المكرمة، المملكة العربية السعودية' }
    };

    // Load client data
    function loadClientData(clientId) {
        const clientDetails = document.getElementById('client-details');

        if (clientId && clientsData[clientId]) {
            const client = clientsData[clientId];
            document.getElementById('client-name').textContent = client.name;
            document.getElementById('client-email').textContent = client.email;
            document.getElementById('client-phone').textContent = client.phone;
            document.getElementById('client-address').textContent = client.address;
            clientDetails.classList.remove('hidden');
        } else {
            clientDetails.classList.add('hidden');
        }
    }

    // Add invoice item
    function addInvoiceItem() {
        const container = document.getElementById('invoice-items');
        const div = document.createElement('div');
        div.className = 'invoice-item border border-gray-200 rounded-xl p-4';
        div.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">الوصف <span class="text-red-500">*</span></label>
                    <input type="text" name="items[${itemCount}][description]" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="وصف الخدمة أو المنتج">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">الكمية <span class="text-red-500">*</span></label>
                    <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all item-quantity"
                           onchange="calculateItemTotal(this)">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">السعر (ريال) <span class="text-red-500">*</span></label>
                    <input type="number" name="items[${itemCount}][unit_price]" step="0.01" min="0" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all item-price"
                           onchange="calculateItemTotal(this)">
                </div>

                <div class="flex items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-slate-700 mb-2">المجموع</label>
                        <div class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-lg font-bold text-slate-900 item-total">
                            0.00 ريال
                        </div>
                    </div>
                    <button type="button" onclick="removeInvoiceItem(this)"
                            class="mr-2 w-10 h-10 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
        itemCount++;
    }

    // Remove invoice item
    function removeInvoiceItem(button) {
        if (document.querySelectorAll('.invoice-item').length > 1) {
            button.closest('.invoice-item').remove();
            calculateTotals();
        } else {
            alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
        }
    }

    // Calculate item total
    function calculateItemTotal(input) {
        const item = input.closest('.invoice-item');
        const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(item.querySelector('.item-price').value) || 0;
        const total = quantity * price;

        item.querySelector('.item-total').textContent = total.toFixed(2) + ' ريال';
        calculateTotals();
    }

    // Calculate all totals
    function calculateTotals() {
        let subtotal = 0;

        document.querySelectorAll('.invoice-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(item.querySelector('.item-price').value) || 0;
            subtotal += quantity * price;
        });

        const taxRate = 0.15; // 15% VAT
        const taxAmount = subtotal * taxRate;
        const totalAmount = subtotal + taxAmount;

        // Update display
        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' ريال';
        document.getElementById('tax-amount').textContent = taxAmount.toFixed(2) + ' ريال';
        document.getElementById('total-amount').textContent = totalAmount.toFixed(2) + ' ريال';

        // Update hidden inputs
        document.getElementById('subtotal-input').value = subtotal.toFixed(2);
        document.getElementById('tax-input').value = taxAmount.toFixed(2);
        document.getElementById('total-input').value = totalAmount.toFixed(2);
    }

    // Preview invoice
    function previewInvoice() {
        alert('سيتم إضافة ميزة المعاينة قريباً');
    }

    // Auto-generate invoice number
    function generateInvoiceNumber() {
        const year = new Date().getFullYear();
        const random = Math.floor(Math.random() * 9999) + 1;
        return `INV-${year}-${random.toString().padStart(4, '0')}`;
    }

    // Initialize calculations on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotals();

        // Set focus to first input
        document.querySelector('input[name="invoice_number"]').focus();
    });

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const items = document.querySelectorAll('.invoice-item');
        let hasValidItems = false;

        items.forEach(item => {
            const description = item.querySelector('input[name*="[description]"]').value.trim();
            const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(item.querySelector('.item-price').value) || 0;

            if (description && quantity > 0 && price > 0) {
                hasValidItems = true;
            }
        });

        if (!hasValidItems) {
            e.preventDefault();
            alert('يجب إضافة بند واحد صحيح على الأقل للفاتورة');
            return false;
        }

        // Final calculation before submit
        calculateTotals();
    });
</script>
@endpush
@endsection
