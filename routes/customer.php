<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customer\StoreController;
use App\Http\Controllers\Customer\OrderController;

Route::middleware([
    'auth',
    'verified',
    'role:customer'
])
->prefix('customer')
->name('customer.')
->group(function () {

    Route::get('/dashboard', fn() => inertia('customer/Dashboard'))
        ->name('dashboard');

    Route::get('/stores', [StoreController::class, 'index'])
        ->name('stores');

    Route::get('/stores/{id}', [StoreController::class, 'show'])
        ->name('stores.show');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders');

    Route::get('/profile', fn() => inertia('customer/Profile'))
        ->name('profile');
});