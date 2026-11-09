<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
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
    Route::post('/laporan/{item}/dikembalikan', [ItemController::class, 'markAsReturned'])->name('items.returned');

    // Pengajuan & Proses Klaim
    Route::get('/barang/{item}/klaim', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/barang/{item}/klaim', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/laporan/{item}/klaim-masuk', [ClaimController::class, 'incoming'])->name('claims.incoming');
    Route::post('/klaim/{claim}/setujui', [ClaimController::class, 'approve'])->name('claims.approve');
    Route::post('/klaim/{claim}/tolak', [ClaimController::class, 'reject'])->name('claims.reject');

    // Halaman Riwayat Saya (Tab Laporan Saya & Klaim Saya)
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('my.history');

    // Menu Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// File rute autentikasi portal (login & logout)
require __DIR__.'/auth.php';
