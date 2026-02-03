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
        // Generate 14 dusun untuk Desa Sindang Anom
        for ($i = 1; $i <= 14; $i++) {
            Dusun::create([
                'name' => 'Dusun ' . $i,
                'code' => 'DSN' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'description' => 'Dusun ' . $i . ' - Desa Sindang Anom',
                'is_active' => true,
            ]);
        }
    }
}
