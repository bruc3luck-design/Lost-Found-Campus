<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeder untuk environment development / testing lokal.
     */
    public function run(): void
    {
        // 1. Data master
        $this->call(ProductionSeeder::class);

        // 2. Akun Dummy Portal (Semua bernama Gamine)
        // Mahasiswa 1
        $mhs1 = User::firstOrCreate(
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
        $mhs1->update(['name' => 'Gamine']);

        // Mahasiswa 2
        $mhs2 = User::firstOrCreate(
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
        $mhs2->update(['name' => 'Gamine 2']);

        // Dosen (dijadikan Admin untuk pengujian Filament)
        $dosenAdmin = User::firstOrCreate(
            ['username' => '198810112011012019'],
            [
                'name'     => 'Gamine (Dosen Admin)',
                'role'     => 'dosen',
                'fakultas' => 'ILMU TARBIYAH DAN KEGURUAN',
                'prodi'    => 'S1 - INFORMATIKA',
                'no_wa'    => '081299887766',
                'is_admin' => true,
            ]
        );
        $dosenAdmin->update([
            'name'     => 'Gamine (Dosen Admin)',
            'is_admin' => true,
        ]);

        // 3. Contoh beberapa data laporan barang untuk development
        $catKTM = Category::where('nama', 'KTM/Dokumen')->first();
        $catHP = Category::where('nama', 'HP')->first();
        $catKunci = Category::where('nama', 'Kunci')->first();

        $locPerpus = Location::where('nama', 'Perpustakaan')->first();
        $locMasjid = Location::where('nama', 'Masjid Kampus')->first();
        $pickupSatpam = Location::where('nama', 'Pos Satpam')->first();
        $pickupPustikom = Location::where('nama', 'PUSTIKOM')->first();

        if ($catKTM && $locPerpus) {
            Item::firstOrCreate(
                ['judul' => 'KTM An. Gamine Hilang di Perpustakaan'],
                [
                    'user_id'         => $mhs1->id,
                    'tipe'            => 'hilang',
                    'category_id'     => $catKTM->id,
                    'location_id'     => $locPerpus->id,
                    'tanggal'         => now()->subDays(2)->toDateString(),
                    'status'          => 'hilang',
                    'deskripsi'       => 'Tertinggal di meja baca lantai 2 perpus dekat rak buku matematika.',
                    'disembunyikan'   => false,
                ]
            );
        }

        if ($catKunci && $locMasjid && $pickupSatpam) {
            Item::firstOrCreate(
                ['judul' => 'Ditemukan Kunci Motor Honda Gantungan Hijau'],
                [
                    'user_id'         => $mhs2->id,
                    'tipe'            => 'temuan',
                    'category_id'     => $catKunci->id,
                    'location_id'     => $locMasjid->id,
                    'pickup_point_id' => $pickupSatpam->id,
                    'tanggal'         => now()->subDay()->toDateString(),
                    'status'          => 'ditemukan',
                    'deskripsi'       => 'Ditemukan di dekat tempat wudhu pria masjid kampus, sudah dititipkan ke Pos Satpam.',
                    'disembunyikan'   => false,
                ]
            );
        }
    }
}
