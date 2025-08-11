<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Logistics\LogisticsCompanyController;
use App\Http\Controllers\ServiceCompany\ServiceCompanyController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\ContactController;

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

// Admin routes
Route::get('/admin/clients', function () {
    return view('admin.clients');
})->name('admin.clients');

// Include routes from separate files
// Admin routes: routes/admin.php
// Logistics routes: routes/logistics.php
// Service Company routes: routes/service-company.php
// User routes: routes/user.php

// Original Laravel routes (keep for backward compatibility)
Route::get('/welcome', function () {
    return view('welcome');
});
