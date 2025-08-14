<?php

use App\Http\Controllers\User\ServiceCompanyRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceCompany\ServiceCompanyController;
/*
|--------------------------------------------------------------------------
| Service Company Routes
|--------------------------------------------------------------------------
|
| هذه المسارات مخصصة للشركات الطالبة للخدمة
|
*/
Route::prefix('service-company')->name('service-company.')->group(function () {

    Route::get('/register', [ServiceCompanyRegistrationController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/register', [ServiceCompanyRegistrationController::class, 'register'])->name('register');
});

Route::middleware(['auth', 'service_company'])->prefix('service-company')->name('service_company.')->group(function () {


    // الصفحة الرئيسية للشركة الطالبة للخدمة
    Route::get('/', [ServiceCompanyController::class, 'dashboard'])->name('dashboard');

    // دفع سريع من الصفحة الرئيسية
    Route::post('/quick-payment', [ServiceCompanyController::class, 'quickPayment'])->name('quick_payment');

    // صفحة الملف الشخصي
    Route::get('/profile', [ServiceCompanyController::class, 'profile'])->name('profile');
    Route::put('/profile', [ServiceCompanyController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [ServiceCompanyController::class, 'updatePassword'])->name('password.update');

    // إدارة الفواتير المستحقة
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'invoices'])->name('index');
        Route::get('/{id}', [ServiceCompanyController::class, 'showInvoice'])->name('show');
        Route::post('/{id}/pay', [ServiceCompanyController::class, 'payInvoice'])->name('pay');
        Route::post('/{id}/installment', [ServiceCompanyController::class, 'requestInstallment'])->name('installment');
        Route::get('/{id}/payment-history', [ServiceCompanyController::class, 'paymentHistory'])->name('payment_history');
    });

    // API لجلب تفاصيل الفاتورة
    Route::get('/invoice/{id}/details', [ServiceCompanyController::class, 'getInvoiceDetails'])->name('invoice.details');

    // إدارة المدفوعات
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'payments'])->name('index');
        Route::get('/{id}', [ServiceCompanyController::class, 'showPayment'])->name('show');
        Route::get('/history', [ServiceCompanyController::class, 'paymentHistory'])->name('history');
        Route::post('/confirm', [ServiceCompanyController::class, 'confirmPayment'])->name('confirm');
    });

    // إدارة خطط التقسيط
    Route::prefix('installments')->name('installments.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'installments'])->name('index');
        Route::get('/{id}', [ServiceCompanyController::class, 'showInstallment'])->name('show');
        Route::post('/{id}/pay', [ServiceCompanyController::class, 'payInstallment'])->name('pay');
        Route::get('/create/{invoice_id}', [ServiceCompanyController::class, 'createInstallmentPlan'])->name('create');
        Route::post('/store', [ServiceCompanyController::class, 'storeInstallmentPlan'])->name('store');
    });

    // إدارة الشركات اللوجستية (الموردين)
    Route::prefix('logistics')->name('logistics.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'logisticsCompanies'])->name('index');
        Route::get('/{id}', [ServiceCompanyController::class, 'showLogisticsCompany'])->name('show');
        Route::get('/{id}/invoices', [ServiceCompanyController::class, 'logisticsInvoices'])->name('invoices');
        Route::get('/{id}/services', [ServiceCompanyController::class, 'logisticsServices'])->name('services');
    });

    // الحساب المالي والرصيد
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'finance'])->name('index');
        Route::get('/balance', [ServiceCompanyController::class, 'balance'])->name('balance');
        Route::get('/outstanding', [ServiceCompanyController::class, 'outstandingPayments'])->name('outstanding');
        Route::get('/transactions', [ServiceCompanyController::class, 'transactions'])->name('transactions');
    });

    // إدارة التقارير
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'reports'])->name('index');
        Route::get('/financial', [ServiceCompanyController::class, 'financialReport'])->name('financial');
        Route::get('/payments', [ServiceCompanyController::class, 'paymentsReport'])->name('payments');
        Route::get('/export/{type}', [ServiceCompanyController::class, 'exportReport'])->name('export');
    });

    // إدارة المنتجات (أجهزة التتبع)
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'products'])->name('index');
        Route::get('/{id}', [ServiceCompanyController::class, 'showProduct'])->name('show');
        Route::post('/{id}/order', [ServiceCompanyController::class, 'orderProduct'])->name('order');
    });

    // إدارة الطلبات
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'orders'])->name('index');
        Route::get('/{id}', [ServiceCompanyController::class, 'showOrder'])->name('show');
        Route::post('/{id}/cancel', [ServiceCompanyController::class, 'cancelOrder'])->name('cancel');
    });

    // الدعم والتواصل
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'support'])->name('index');
        Route::get('/tickets', [ServiceCompanyController::class, 'supportTickets'])->name('tickets');
        Route::post('/tickets', [ServiceCompanyController::class, 'createSupportTicket'])->name('tickets.create');
        Route::get('/tickets/{id}', [ServiceCompanyController::class, 'showSupportTicket'])->name('tickets.show');
        Route::post('/tickets/{id}/reply', [ServiceCompanyController::class, 'replySupportTicket'])->name('tickets.reply');
    });

    // إعدادات الشركة
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'settings'])->name('index');
        Route::put('/', [ServiceCompanyController::class, 'updateSettings'])->name('update');
        Route::get('/profile', [ServiceCompanyController::class, 'profile'])->name('profile');
        Route::put('/profile', [ServiceCompanyController::class, 'updateProfile'])->name('profile.update');
    });

    // الإشعارات
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [ServiceCompanyController::class, 'notifications'])->name('index');
        Route::post('/{id}/mark-read', [ServiceCompanyController::class, 'markNotificationAsRead'])->name('mark_read');
        Route::post('/mark-all-read', [ServiceCompanyController::class, 'markAllNotificationsAsRead'])->name('mark_all_read');
    });

});
