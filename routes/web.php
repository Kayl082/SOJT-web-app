<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    }

    if ($user->hasRole('vendor')) {
        return redirect()->route('vendor.dashboard');
    }

    abort(403, 'No role assigned.');
})->name('dashboard');



require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
require __DIR__.'/vendor.php';
require __DIR__.'/customer.php';
