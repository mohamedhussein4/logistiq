<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Authentication routes
Auth::routes();

// Main pages routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/logistics', function () {
    return view('logistics');
})->name('logistics');

Route::get('/clients', function () {
    return view('clients');
})->name('clients');

Route::get('/store', function () {
    return view('store');
})->name('store');

// Original Laravel routes (keep for backward compatibility)
Route::get('/welcome', function () {
    return view('welcome');
});

