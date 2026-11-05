<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Dompet',
            'HP',
            'Kunci',
            'Tas',
            'KTM/Dokumen',
            'Elektronik',
            'Lainnya',
        ];

        foreach ($categories as $nama) {
            Category::firstOrCreate(['nama' => $nama]);
        }
    }
}
