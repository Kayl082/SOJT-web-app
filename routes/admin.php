<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => inertia('admin/Dashboard'))->name('dashboard');
    Route::get('/vendors', fn() => inertia('admin/Vendors'))->name('vendors.index');
    Route::get('/customers', fn() => inertia('admin/Customers'))->name('customers.index');
    Route::get('/categories', fn() => inertia('admin/Categories'))->name('categories');
    Route::get('/reports', fn() => inertia('admin/Reports'))->name('reports');
});
