<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seeder untuk environment produksi.
     * Hanya memasukkan data master kategori dan lokasi.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            LocationSeeder::class,
        ]);
    }
}
