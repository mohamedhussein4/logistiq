<!-- Approve Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-lg w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">الموافقة على طلب التمويل</h3>
                <p class="text-slate-600 text-sm lg:text-base">هل أنت متأكد من الموافقة على هذا الطلب؟</p>
            </div>

            <form id="approve-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات إضافية</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm lg:text-base"
                              placeholder="أضف ملاحظات إضافية (اختياري)"></textarea>
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        موافقة
                    </button>
                    <button type="button" onclick="closeModal('approve-modal')"
                            class="w-full lg:flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-lg w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times text-red-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">رفض طلب التمويل</h3>
                <p class="text-slate-600 text-sm lg:text-base">يرجى تحديد سبب الرفض</p>
            </div>

            <form id="reject-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">سبب الرفض <span class="text-red-500">*</span></label>
                    <textarea name="admin_notes" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm lg:text-base"
                              placeholder="اكتب سبب رفض الطلب..."></textarea>
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        رفض الطلب
                    </button>
                    <button type="button" onclick="closeModal('reject-modal')"
                            class="w-full lg:flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disburse Modal -->
<div id="disburse-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl lg:rounded-3xl p-6 lg:p-8 max-w-lg w-full shadow-2xl transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 lg:w-20 h-16 lg:h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl lg:text-2xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-slate-800 mb-2">صرف المبلغ</h3>
                <p class="text-slate-600 text-sm lg:text-base">تأكيد صرف المبلغ وإضافته لرصيد الشركة</p>
            </div>

            <form id="disburse-form" method="POST" class="space-y-4 lg:space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات الصرف</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm lg:text-base"
                              placeholder="أضف ملاحظات حول عملية الصرف (اختياري)"></textarea>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-purple-600 mt-1 mr-3 flex-shrink-0"></i>
                        <div class="text-sm text-purple-800">
                            <p class="font-semibold mb-1">تنبيه هام</p>
                            <p>سيتم إضافة المبلغ تلقائياً لرصيد الشركة اللوجستية</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row space-y-3 lg:space-y-0 lg:space-x-4 lg:space-x-reverse pt-4">
                    <button type="submit" class="w-full lg:flex-1 px-6 py-3 bg-purple-600 text-white rounded-xl font-semibold hover:bg-purple-700 transition-colors">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        تأكيد الصرف
                    </button>
                    <button type="button" onclick="closeModal('disburse-modal')"
                            class="w-full lg:flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.id === 'approve-modal' || e.target.id === 'reject-modal' || e.target.id === 'disburse-modal') {
            closeModal(e.target.id);
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = ['approve-modal', 'reject-modal', 'disburse-modal'];
            modals.forEach(modal => {
                if (!document.getElementById(modal).classList.contains('hidden')) {
                    closeModal(modal);
                }
            });
        }
    });
</script>
