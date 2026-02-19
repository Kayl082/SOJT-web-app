<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:vendor_owner,vendor_staff'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', fn() => inertia('vendor/Dashboard'))->name('dashboard');
    Route::get('/products', fn() => inertia('vendor/Products'))->name('products.index');
    Route::get('/inventory', fn() => inertia('vendor/Inventory'))->name('inventory');
    Route::get('/orders', fn() => inertia('vendor/Orders'))->name('orders');
    
    // Owner-only routes
    Route::middleware('role:vendor_owner')->group(function () {
        Route::get('/store-settings', fn() => inertia('vendor/StoreSettings'))->name('store.settings');
        Route::get('/staff', fn() => inertia('vendor/Staff'))->name('staff');
        Route::get('/expenses', fn() => inertia('vendor/Expenses'))->name('expenses');
        Route::get('/analytics', fn() => inertia('vendor/Analytics'))->name('analytics');
    });
});
