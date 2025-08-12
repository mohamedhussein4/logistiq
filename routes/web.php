<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Logistics\LogisticsCompanyController;
use App\Http\Controllers\ServiceCompany\ServiceCompanyController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\PurchaseController;

// Authentication routes
Auth::routes();
Route::get('/home', [HomeController::class, 'index']);

// Main pages routes - ربط بالباك اند
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/logistics-public', [LogisticsCompanyController::class, 'publicPage'])->name('logistics.public');
Route::get('/clients', [ServiceCompanyController::class, 'publicPage'])->name('clients');
Route::get('/store', [UserDashboardController::class, 'publicStore'])->name('store');

// Contact form route
Route::post('/contact', [\App\Http\Controllers\User\ContactController::class, 'store'])->name('contact.store');

// Direct Purchase routes - مسارات الشراء المباشر (منظمة)
Route::prefix('user/purchase')->name('user.purchase.')->middleware(['auth'])->group(function () {
    Route::get('/product/{productId}', [PurchaseController::class, 'showDirectPurchase'])->name('product');
    Route::post('/product/{productId}/payment', [PurchaseController::class, 'processDirectPurchase'])->name('process');
    Route::post('/upload-proof', [PurchaseController::class, 'uploadPurchaseProof'])->name('upload_proof');
    Route::get('/success/{orderId}', [PurchaseController::class, 'purchaseSuccess'])->name('success');
    Route::get('/my-orders', [PurchaseController::class, 'myOrders'])->name('my_orders');
    Route::get('/order/{orderId}', [PurchaseController::class, 'showOrder'])->name('order_details');
});

// Payment routes - مسارات نظام الدفع
Route::prefix('payments')->name('payments.')->middleware(['auth'])->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::get('/product/{orderId}', [PaymentController::class, 'showProductPayment'])->name('product');
    Route::get('/invoice/{invoiceId}', [PaymentController::class, 'showInvoicePayment'])->name('invoice');
    Route::post('/', [PaymentController::class, 'store'])->name('store');
    Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
    Route::post('/{id}/proof', [PaymentController::class, 'uploadProof'])->name('upload_proof');
    Route::patch('/{id}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/accounts/data', [PaymentController::class, 'getPaymentAccounts'])->name('accounts.data');
});

// Admin routes
Route::get('/admin/clients', function () {
    return view('admin.clients');
})->name('admin.clients');



// Include routes from separate files
// Logistics routes: routes/logistics.php
// Service Company routes: routes/service-company.php
// User routes: routes/user.php

// Original Laravel routes (keep for backward compatibility)
Route::get('/welcome', function () {
    return view('welcome');
});
