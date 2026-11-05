<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['nama' => 'Gedung Rektorat', 'keterangan' => 'Lantai 1-5 Gedung Rektorat Utama', 'is_pickup_point' => false],
            ['nama' => 'Perpustakaan', 'keterangan' => 'Gedung Perpustakaan Pusat', 'is_pickup_point' => false],
            ['nama' => 'Masjid Kampus', 'keterangan' => 'Masjid Kampus UIN SSC', 'is_pickup_point' => false],
            ['nama' => 'Kantin', 'keterangan' => 'Kantin Utama Kampus', 'is_pickup_point' => false],
            ['nama' => 'Gedung PUSTIKOM', 'keterangan' => 'Gedung Pusat TIK', 'is_pickup_point' => false],
            ['nama' => 'Lapangan', 'keterangan' => 'Lapangan Olahraga dan Upacara', 'is_pickup_point' => false],
            ['nama' => 'Parkiran', 'keterangan' => 'Area Parkir Motor & Mobil', 'is_pickup_point' => false],
            ['nama' => 'Ruang Kelas', 'keterangan' => 'Ruang Perkuliahan Fakultas', 'is_pickup_point' => false],
            ['nama' => 'Pos Satpam', 'keterangan' => 'Pos Keamanan Gerbang Utama', 'is_pickup_point' => true],
            ['nama' => 'PUSTIKOM', 'keterangan' => 'Ruang Layanan PUSTIKOM Lantai 1', 'is_pickup_point' => true],
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate(
                ['nama' => $loc['nama']],
                [
                    'keterangan'      => $loc['keterangan'],
                    'is_pickup_point' => $loc['is_pickup_point'],
                ]
            );
        }
    }
}
