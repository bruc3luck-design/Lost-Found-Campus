<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Lost & Found Campus UIN SSC
|--------------------------------------------------------------------------
*/

// Halaman Beranda (Dapat diakses publik atau civitas, menampilkan status / laporan)
Route::get('/', function () {
    return view('home');
})->name('home');

// File rute autentikasi portal (login & logout)
require __DIR__.'/auth.php';
