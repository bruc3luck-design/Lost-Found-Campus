<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'tampilkanForm'])->name('login');
    Route::post('login', [LoginController::class, 'proses'])->middleware('throttle:5,1');
});

Route::post('logout', [LoginController::class, 'keluar'])
    ->middleware('auth')
    ->name('logout');
