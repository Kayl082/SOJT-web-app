<?php

use App\Http\Controllers\Vendor\StoreSetupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/

// Group 1: Vendor authenticated but may NOT have store yet
Route::middleware(['auth', 'verified', 'role:vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {

        // Dashboard (shows modal if no store)
        Route::get('/dashboard', fn() => inertia('vendor/Dashboard'))
            ->name('dashboard');

        // Store setup (POST only)
        Route::post('/store/setup', [StoreSetupController::class, 'store'])
            ->name('store.create');
    });


// Group 2: Vendor MUST have store + tenant resolved
Route::middleware([
        'auth',
        'verified',
        'role:vendor',
        'vendor.has.store',
        'store.tenant',
    ])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {

        Route::get('/products', fn() => inertia('vendor/Products'))->name('products.index');
        Route::get('/inventory', fn() => inertia('vendor/Inventory'))->name('inventory');
        Route::get('/orders', fn() => inertia('vendor/Orders'))->name('orders');
        Route::get('/store-settings', fn() => inertia('vendor/StoreSettings'))->name('store.settings');
        Route::get('/staff', fn() => inertia('vendor/Staff'))->name('staff');
        Route::get('/expenses', fn() => inertia('vendor/Expenses'))->name('expenses');
        Route::get('/analytics', fn() => inertia('vendor/Analytics'))->name('analytics');
    });