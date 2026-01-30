<?php

namespace Database\Seeders;

use App\Models\Dusun;
use Illuminate\Database\Seeder;

class DusunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dusuns = [
            [
                'name' => 'Dusun 1',
                'code' => 'DSN1',
                'description' => 'Dusun 1 - Wilayah pertama',
                'is_active' => true,
            ],
            [
                'name' => 'Dusun 2',
                'code' => 'DSN2',
                'description' => 'Dusun 2 - Wilayah kedua',
                'is_active' => true,
            ],
            [
                'name' => 'Dusun 3',
                'code' => 'DSN3',
                'description' => 'Dusun 3 - Wilayah ketiga',
                'is_active' => true,
            ],
        ];

        foreach ($dusuns as $dusun) {
            Dusun::create($dusun);
        }
    }
}
