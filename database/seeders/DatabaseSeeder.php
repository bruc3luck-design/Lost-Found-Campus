<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeder untuk inisialisasi sistem.
     * Hanya memasukkan data master resmi (Kategori & Lokasi)
     * dan akun autentikasi civitas kampus (tanpa data barang dummy).
     */
    public function run(): void
    {
        // 1. Data master resmi: Kategori & Lokasi Kampus
        $this->call(ProductionSeeder::class);

        // 2. Akun civitas kampus untuk autentikasi portal
        // Mahasiswa 1
        User::firstOrCreate(
            ['username' => '2281050030'],
            [
                'name'     => 'Gamine',
                'role'     => 'mahasiswa',
                'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
                'prodi'    => 'TADRIS MATEMATIKA',
                'no_wa'    => '08123456789',
                'is_admin' => false,
            ]
        );

        // Mahasiswa 2
        User::firstOrCreate(
            ['username' => '2281050031'],
            [
                'name'     => 'Gamine 2',
                'role'     => 'mahasiswa',
                'fakultas' => 'SYARIAH',
                'prodi'    => 'HUKUM KELUARGA ISLAM',
                'no_wa'    => '08123450000',
                'is_admin' => false,
            ]
        );

        // Dosen Admin
        User::firstOrCreate(
            ['username' => '198810112011012019'],
            [
                'name'     => 'Administrator Lost & Found',
                'role'     => 'dosen',
                'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
                'prodi'    => 'INFORMATIKA',
                'no_wa'    => '081299887766',
                'is_admin' => true,
            ]
        );
    }
}
