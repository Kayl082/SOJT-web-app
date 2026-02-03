<?php
use App\Http\Controllers\CardiovascularController;

Route::get('/cardiovascular', [CardiovascularController::class, 'index']);
Route::get('/cardiovascular/state/{state}', [CardiovascularController::class, 'byState']);
Route::get('/cardiovascular/year/{year}', [CardiovascularController::class, 'byYear']);