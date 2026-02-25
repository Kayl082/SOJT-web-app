<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Vendor\StoreSetupController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\StaffController;
use App\Http\Controllers\Vendor\OrderController;
/*
|--------------------------------------------------------------------------
| Store Setup (Owner Only - Before Store Exists)
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'role:vendor_owner'
])
->prefix('vendor')
->name('vendor.')
->group(function () {

    Route::post('/store/setup', [StoreSetupController::class, 'store'])
        ->name('store.create');
});


/*
|--------------------------------------------------------------------------
| Vendor Routes (Requires Store + Vendor Role)
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'role:vendor_owner|vendor_staff',
    'vendor.store'
])
->prefix('vendor')
->name('vendor.')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', fn() => inertia('vendor/Dashboard'))
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */
    Route::controller(ProductController::class)->group(function () {

        Route::get('/products', 'index')
            ->middleware('permission:view products')
            ->name('products.index');

        Route::post('/products', 'store')
            ->middleware('permission:create products')
            ->name('products.store');

        Route::patch('/products/{id}', 'update')
            ->middleware('permission:update products')
            ->name('products.update');

        Route::patch('/products/{id}/stock', 'updateStock')
            ->middleware('permission:update stock')
            ->name('products.stock');

        Route::patch('/products/{id}/price', 'updatePrice')
            ->middleware('permission:modify price')
            ->name('products.price');
    });


    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */
    Route::controller(OrderController::class)->group(function () {

        Route::get('/orders', 'index')
            ->middleware('permission:view orders')
            ->name('orders.index');

        Route::get('/orders/{id}', 'show')
            ->middleware('permission:view orders')
            ->name('orders.show');

        Route::patch('/orders/{id}/status', 'updateStatus')
            ->middleware('permission:update order status')
            ->name('orders.status');
    });

    /*
    |--------------------------------------------------------------------------
    | Owner Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:vendor_owner')->group(function () {

        Route::get('/expenses', fn() => inertia('vendor/Expenses'))
            ->middleware('permission:view financials')
            ->name('expenses.index');

        Route::get('/analytics', fn() => inertia('vendor/Analytics'))
            ->middleware('permission:view financials')
            ->name('analytics.index');

        Route::controller(StaffController::class)->group(function () {

            Route::get('/staff', 'index')
                ->middleware('permission:manage staff')
                ->name('staff.index');

            Route::post('/staff', 'store')
                ->middleware('permission:manage staff')
                ->name('staff.store');

            Route::delete('/staff/{id}', 'destroy')
                ->middleware('permission:manage staff')
                ->name('staff.destroy');
        });
    });
});