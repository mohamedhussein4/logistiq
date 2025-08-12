<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FundingRequestController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\LinkingServiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ClientDebtController;
use App\Http\Controllers\Admin\PaymentManagementController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are the admin routes for the Link2u application.
| All routes are protected by the 'admin' middleware.
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
            Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Funding Requests Management
    Route::prefix('funding-requests')->name('funding_requests.')->group(function () {
        Route::get('/', [FundingRequestController::class, 'index'])->name('index');
        Route::get('/{fundingRequest}', [FundingRequestController::class, 'show'])->name('show');
        Route::patch('/{fundingRequest}/status', [FundingRequestController::class, 'updateStatus'])->name('update_status');
        Route::patch('/{fundingRequest}/approve', [FundingRequestController::class, 'approve'])->name('approve');
        Route::patch('/{fundingRequest}/reject', [FundingRequestController::class, 'reject'])->name('reject');
        Route::patch('/{fundingRequest}/disburse', [FundingRequestController::class, 'disburse'])->name('disburse');
        Route::get('/analytics/data', [FundingRequestController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [FundingRequestController::class, 'export'])->name('export');
    });

    // Client Debts Management
    Route::prefix('client-debts')->name('client_debts.')->group(function () {
        Route::get('/', [ClientDebtController::class, 'index'])->name('index');
        Route::get('/{clientDebt}', [ClientDebtController::class, 'show'])->name('show');
        Route::post('/{clientDebt}/create-account', [ClientDebtController::class, 'createAccount'])->name('create_account');
    });

    // Invoices Management
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::patch('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('update_status');
        Route::post('/{invoice}/payment', [InvoiceController::class, 'recordPayment'])->name('record_payment');
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('download_pdf');
        Route::post('/update-overdue', [InvoiceController::class, 'updateOverdueInvoices'])->name('update_overdue');
    });


    // Products Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::patch('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        Route::patch('/{product}/status', [ProductController::class, 'updateStatus'])->name('update_status');
        Route::patch('/{product}/stock', [ProductController::class, 'updateStock'])->name('update_stock');

        // Categories
        Route::get('/categories/manage', [ProductController::class, 'categories'])->name('categories.manage');
        Route::post('/categories', [ProductController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [ProductController::class, 'updateCategory'])->name('categories.update');
        Route::patch('/categories/{category}/status', [ProductController::class, 'updateCategoryStatus'])->name('categories.update_status');
        Route::delete('/categories/{category}', [ProductController::class, 'destroyCategory'])->name('categories.destroy');
    });

    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update_status');
        Route::patch('/{order}/ship', [OrderController::class, 'ship'])->name('ship');
        Route::patch('/{order}/deliver', [OrderController::class, 'deliver'])->name('deliver');
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/analytics/data', [OrderController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [OrderController::class, 'export'])->name('export');
    });


    // Contact Requests Management
    Route::prefix('contact-requests')->name('contact_requests.')->group(function () {
        Route::get('/', [ContactRequestController::class, 'index'])->name('index');
        Route::get('/{contactRequest}', [ContactRequestController::class, 'show'])->name('show');
        Route::patch('/{contactRequest}/status', [ContactRequestController::class, 'updateStatus'])->name('update_status');
        Route::post('/{contactRequest}/respond', [ContactRequestController::class, 'respond'])->name('respond');
        Route::delete('/{contactRequest}', [ContactRequestController::class, 'destroy'])->name('destroy');
        Route::patch('/{contactRequest}/important', [ContactRequestController::class, 'markImportant'])->name('mark_important');
        Route::get('/analytics/data', [ContactRequestController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [ContactRequestController::class, 'export'])->name('export');
        Route::get('/quick-stats', [ContactRequestController::class, 'quickStats'])->name('quick_stats');
    });

    // Linking Services Management
    Route::prefix('linking-services')->name('linking_services.')->group(function () {
        Route::get('/', [LinkingServiceController::class, 'index'])->name('index');
        Route::get('/create', [LinkingServiceController::class, 'create'])->name('create');
        Route::post('/', [LinkingServiceController::class, 'store'])->name('store');
        Route::get('/{linkingService}', [LinkingServiceController::class, 'show'])->name('show');
        Route::put('/{linkingService}', [LinkingServiceController::class, 'update'])->name('update');
        Route::delete('/{linkingService}', [LinkingServiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{linkingService}/status', [LinkingServiceController::class, 'updateStatus'])->name('update_status');
        Route::patch('/{linkingService}/commission', [LinkingServiceController::class, 'updateCommission'])->name('update_commission');
        Route::get('/analytics/data', [LinkingServiceController::class, 'analytics'])->name('analytics');
        Route::get('/export/csv', [LinkingServiceController::class, 'export'])->name('export');
    });

    // Reports & Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/financial', [ReportController::class, 'financialReport'])->name('financial');
        Route::get('/users', [ReportController::class, 'usersReport'])->name('users');
        Route::get('/products', [ReportController::class, 'productsReport'])->name('products');
        Route::get('/orders', [ReportController::class, 'ordersReport'])->name('orders');
        Route::get('/funding', [ReportController::class, 'fundingReport'])->name('funding');
        Route::get('/financial/export', [ReportController::class, 'exportFinancial'])->name('financial.export');
        Route::get('/users/export', [ReportController::class, 'exportUsers'])->name('users.export');
        Route::get('/products/export', [ReportController::class, 'exportProducts'])->name('products.export');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });

    // Settings & Configuration
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/', [SettingController::class, 'update'])->name('update');
        Route::patch('/', [SettingController::class, 'update'])->name('update');
        Route::get('/logs', [SettingController::class, 'logs'])->name('logs');
        Route::post('/logs/clear', [SettingController::class, 'clearLogs'])->name('logs.clear');
        Route::get('/logs/download', [SettingController::class, 'downloadLogs'])->name('logs.download');
        Route::post('/backup', [SettingController::class, 'createBackup'])->name('backup');
        Route::post('/cache/clear', [SettingController::class, 'clearCache'])->name('cache.clear');
        Route::post('/test-email', [SettingController::class, 'testEmail'])->name('test_email');
    });

    // Payment Management (للمعاملات فقط)
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentManagementController::class, 'index'])->name('index');
        Route::get('/{paymentRequest}', [PaymentManagementController::class, 'show'])->name('show');
        Route::patch('/{paymentRequest}/status', [PaymentManagementController::class, 'updateStatus'])->name('update_status');
        Route::post('/proofs/{proof}/review', [PaymentManagementController::class, 'reviewProof'])->name('review_proof');
    });

    // Bank Accounts Management (منفصل تماماً)
    Route::prefix('bank-accounts')->name('bank_accounts.')->group(function () {
        Route::get('/', [PaymentManagementController::class, 'bankAccounts'])->name('index');
        Route::post('/', [PaymentManagementController::class, 'storeBankAccount'])->name('store');
        Route::get('/{bankAccount}/edit', [PaymentManagementController::class, 'getBankAccount'])->name('edit');
        Route::put('/{bankAccount}', [PaymentManagementController::class, 'updateBankAccount'])->name('update');
        Route::delete('/{bankAccount}', [PaymentManagementController::class, 'destroyBankAccount'])->name('destroy');
    });

    // Electronic Wallets Management (منفصل تماماً)
    Route::prefix('electronic-wallets')->name('electronic_wallets.')->group(function () {
        Route::get('/', [PaymentManagementController::class, 'electronicWallets'])->name('index');
        Route::post('/', [PaymentManagementController::class, 'storeElectronicWallet'])->name('store');
        Route::get('/{electronicWallet}/edit', [PaymentManagementController::class, 'getElectronicWallet'])->name('edit');
        Route::put('/{electronicWallet}', [PaymentManagementController::class, 'updateElectronicWallet'])->name('update');
        Route::delete('/{electronicWallet}', [PaymentManagementController::class, 'destroyElectronicWallet'])->name('destroy');
    });


});
