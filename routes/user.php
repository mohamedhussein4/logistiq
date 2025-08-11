<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserDashboardController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| هذه المسارات مخصصة للمستخدمين العاديين
|
*/

Route::middleware(['auth', 'regular_user'])->prefix('user')->name('user.')->group(function () {

        // الصفحة الرئيسية للمستخدم
    Route::get('/', [UserDashboardController::class, 'dashboard'])->name('dashboard');

    // صفحة الملف الشخصي
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');

    // متجر أجهزة التتبع
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', [UserController::class, 'store'])->name('index');
        Route::get('/products', [UserController::class, 'products'])->name('products');
        Route::get('/products/{id}', [UserController::class, 'showProduct'])->name('products.show');
        Route::post('/products/{id}/add-to-cart', [UserController::class, 'addToCart'])->name('products.add_to_cart');
        Route::get('/categories', [UserController::class, 'categories'])->name('categories');
        Route::get('/categories/{id}', [UserController::class, 'categoryProducts'])->name('categories.products');
    });

    // إدارة العربة (Cart)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [UserController::class, 'cart'])->name('index');
        Route::post('/add/{id}', [UserController::class, 'addToCart'])->name('add');
        Route::put('/update/{id}', [UserController::class, 'updateCart'])->name('update');
        Route::delete('/remove/{id}', [UserController::class, 'removeFromCart'])->name('remove');
        Route::delete('/clear', [UserController::class, 'clearCart'])->name('clear');
        Route::get('/count', [UserController::class, 'cartCount'])->name('count');
    });

    // إدارة الطلبات
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [UserController::class, 'orders'])->name('index');
        Route::get('/create', [UserController::class, 'createOrder'])->name('create');
        Route::post('/', [UserController::class, 'storeOrder'])->name('store');
        Route::get('/{id}', [UserController::class, 'showOrder'])->name('show');
        Route::post('/{id}/cancel', [UserController::class, 'cancelOrder'])->name('cancel');
        Route::get('/{id}/track', [UserController::class, 'trackOrder'])->name('track');
        Route::get('/{id}/invoice', [UserController::class, 'orderInvoice'])->name('invoice');
    });

    // إدارة المدفوعات
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [UserController::class, 'payments'])->name('index');
        Route::get('/{id}', [UserController::class, 'showPayment'])->name('show');
        Route::post('/process', [UserController::class, 'processPayment'])->name('process');
        Route::get('/success', [UserController::class, 'paymentSuccess'])->name('success');
        Route::get('/failed', [UserController::class, 'paymentFailed'])->name('failed');
    });

    // إدارة العناوين
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [UserController::class, 'addresses'])->name('index');
        Route::get('/create', [UserController::class, 'createAddress'])->name('create');
        Route::post('/', [UserController::class, 'storeAddress'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'editAddress'])->name('edit');
        Route::put('/{id}', [UserController::class, 'updateAddress'])->name('update');
        Route::delete('/{id}', [UserController::class, 'deleteAddress'])->name('delete');
        Route::post('/{id}/set-default', [UserController::class, 'setDefaultAddress'])->name('set_default');
    });

    // قائمة الأمنيات (Wishlist)
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [UserController::class, 'wishlist'])->name('index');
        Route::post('/add/{id}', [UserController::class, 'addToWishlist'])->name('add');
        Route::delete('/remove/{id}', [UserController::class, 'removeFromWishlist'])->name('remove');
        Route::post('/move-to-cart/{id}', [UserController::class, 'moveToCart'])->name('move_to_cart');
    });

    // الدعم والتواصل
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [UserController::class, 'support'])->name('index');
        Route::get('/tickets', [UserController::class, 'supportTickets'])->name('tickets');
        Route::post('/tickets', [UserController::class, 'createSupportTicket'])->name('tickets.create');
        Route::get('/tickets/{id}', [UserController::class, 'showSupportTicket'])->name('tickets.show');
        Route::post('/tickets/{id}/reply', [UserController::class, 'replySupportTicket'])->name('tickets.reply');
        Route::get('/faq', [UserController::class, 'faq'])->name('faq');
    });

    // الملف الشخصي والإعدادات
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('index');
        Route::get('/edit', [UserController::class, 'editProfile'])->name('edit');
        Route::put('/', [UserController::class, 'updateProfile'])->name('update');
        Route::get('/security', [UserController::class, 'security'])->name('security');
        Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
        Route::get('/preferences', [UserController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [UserController::class, 'updatePreferences'])->name('preferences.update');
    });

    // الإشعارات
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [UserController::class, 'notifications'])->name('index');
        Route::post('/{id}/mark-read', [UserController::class, 'markNotificationAsRead'])->name('mark_read');
        Route::post('/mark-all-read', [UserController::class, 'markAllNotificationsAsRead'])->name('mark_all_read');
        Route::get('/settings', [UserController::class, 'notificationSettings'])->name('settings');
        Route::put('/settings', [UserController::class, 'updateNotificationSettings'])->name('settings.update');
    });
});
