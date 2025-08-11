<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistics\LogisticsController;
use App\Http\Controllers\Logistics\LogisticsCompanyController;

/*
|--------------------------------------------------------------------------
| Logistics Company Routes
|--------------------------------------------------------------------------
|
| هذه المسارات مخصصة للشركات اللوجستية
|
*/

Route::middleware(['auth', 'logistics'])->prefix('logistics')->name('logistics.')->group(function () {

    // الصفحة الرئيسية للشركة اللوجستية
    Route::get('/', [LogisticsController::class, 'dashboard'])->name('dashboard');

    // صفحة الملف الشخصي
    Route::get('/profile', [LogisticsCompanyController::class, 'profile'])->name('profile');
    Route::put('/profile', [LogisticsCompanyController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [LogisticsCompanyController::class, 'updatePassword'])->name('password.update');

    // إدارة طلبات التمويل
    Route::prefix('funding')->name('funding.')->group(function () {
        Route::get('/', [LogisticsController::class, 'fundingRequests'])->name('index');
        Route::get('/create', [LogisticsController::class, 'createFundingRequest'])->name('create');
        Route::post('/', [LogisticsController::class, 'storeFundingRequest'])->name('store');
        Route::get('/{id}', [LogisticsController::class, 'showFundingRequest'])->name('show');
        Route::post('/{id}/cancel', [LogisticsController::class, 'cancelFundingRequest'])->name('cancel');
    });

    // إدارة الائتمان
    Route::post('/credit/pay', [LogisticsController::class, 'payCreditExcess'])->name('credit.pay');

    // إدارة الرصيد والمعاملات المالية
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [LogisticsController::class, 'finance'])->name('index');
        Route::get('/transactions', [LogisticsController::class, 'transactions'])->name('transactions');
        Route::get('/balance', [LogisticsController::class, 'balance'])->name('balance');
        Route::post('/withdraw', [LogisticsController::class, 'withdraw'])->name('withdraw');
    });

    // إدارة الفواتير
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [LogisticsController::class, 'invoices'])->name('index');
        Route::get('/create', [LogisticsController::class, 'createInvoice'])->name('create');
        Route::post('/', [LogisticsController::class, 'storeInvoice'])->name('store');
        Route::get('/{id}', [LogisticsController::class, 'showInvoice'])->name('show');
        Route::put('/{id}', [LogisticsController::class, 'updateInvoice'])->name('update');
        Route::delete('/{id}', [LogisticsController::class, 'deleteInvoice'])->name('delete');
    });

    // إدارة الشركات الطالبة للخدمة (العملاء)
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [LogisticsController::class, 'clients'])->name('index');
        Route::get('/{id}', [LogisticsController::class, 'showClient'])->name('show');
        Route::get('/{id}/invoices', [LogisticsController::class, 'clientInvoices'])->name('invoices');
        Route::get('/{id}/transactions', [LogisticsController::class, 'clientTransactions'])->name('transactions');
    });

    // إدارة التقارير
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [LogisticsController::class, 'reports'])->name('index');
        Route::get('/financial', [LogisticsController::class, 'financialReport'])->name('financial');
        Route::get('/clients', [LogisticsController::class, 'clientsReport'])->name('clients');
        Route::get('/export/{type}', [LogisticsController::class, 'exportReport'])->name('export');
    });

    // إدارة المنتجات (أجهزة التتبع)
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [LogisticsController::class, 'products'])->name('index');
        Route::get('/{id}', [LogisticsController::class, 'showProduct'])->name('show');
        Route::post('/{id}/order', [LogisticsController::class, 'orderProduct'])->name('order');
    });

    // إدارة الطلبات
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [LogisticsController::class, 'orders'])->name('index');
        Route::get('/{id}', [LogisticsController::class, 'showOrder'])->name('show');
        Route::post('/{id}/cancel', [LogisticsController::class, 'cancelOrder'])->name('cancel');
    });

    // إعدادات الشركة
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [LogisticsController::class, 'settings'])->name('index');
        Route::put('/', [LogisticsController::class, 'updateSettings'])->name('update');
        Route::get('/profile', [LogisticsController::class, 'profile'])->name('profile');
        Route::put('/profile', [LogisticsController::class, 'updateProfile'])->name('profile.update');
    });

    // الإشعارات
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [LogisticsController::class, 'notifications'])->name('index');
        Route::post('/{id}/mark-read', [LogisticsController::class, 'markNotificationAsRead'])->name('mark_read');
        Route::post('/mark-all-read', [LogisticsController::class, 'markAllNotificationsAsRead'])->name('mark_all_read');
    });
});
