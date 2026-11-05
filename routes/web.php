<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Lost & Found Campus UIN SSC
|--------------------------------------------------------------------------
*/

// Halaman Beranda Publik (Daftar & Pencarian Barang)
Route::get('/', [ItemController::class, 'index'])->name('home');

// Halaman Detail Barang
Route::get('/barang/{item}', [ItemController::class, 'show'])->name('items.show');

// Rute yang membutuhkan Autentikasi Civitas Kampus
Route::middleware('auth')->group(function () {
    // Pelaporan Barang
    Route::get('/lapor', [ItemController::class, 'create'])->name('items.create');
    Route::post('/lapor', [ItemController::class, 'store'])->name('items.store');

    // Manajemen Laporan Sendiri
    Route::get('/laporan-saya', [ItemController::class, 'myItems'])->name('my.items');
    Route::get('/laporan/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/laporan/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/laporan/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
});

// File rute autentikasi portal (login & logout)
require __DIR__.'/auth.php';
