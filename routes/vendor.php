<?php

use App\Http\Controllers\Vendor\StoreSetupController;
use Illuminate\Support\Facades\Route;

// Store setup (POST only - modal in dashboard)
Route::middleware(['auth', 'verified', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::post('/store/setup', [StoreSetupController::class, 'store'])->name('store.create');
});

// Vendor routes (requires store)
Route::middleware(['auth', 'verified', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', fn() => inertia('vendor/Dashboard'))->name('dashboard');
    Route::get('/products', fn() => inertia('vendor/Products'))->name('products.index');
    Route::get('/inventory', fn() => inertia('vendor/Inventory'))->name('inventory');
    Route::get('/orders', fn() => inertia('vendor/Orders'))->name('orders');
    Route::get('/store-settings', fn() => inertia('vendor/StoreSettings'))->name('store.settings');
    Route::get('/staff', fn() => inertia('vendor/Staff'))->name('staff');
    Route::get('/expenses', fn() => inertia('vendor/Expenses'))->name('expenses');
    Route::get('/analytics', fn() => inertia('vendor/Analytics'))->name('analytics');
});
