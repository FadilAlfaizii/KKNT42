<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Superadmin user
        $sid = Str::uuid();
        DB::table('users')->insert([
            'id' => $sid,
            'fullname' => 'superadmin',
            'phone_number' => '08132-691577',
            "bio" => "I am the super admin",
            'email' => 'superadmin@starter-kit.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superadmin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Bind superadmin user to FilamentShield
        Artisan::call('shield:super-admin', ['--user' => $sid]);

        $roles = DB::table('roles')->whereNot('name', 'super_admin')->get();
        $dusuns = DB::table('dusuns')->orderBy('id')->get();
        
        foreach ($roles as $role) {
            // Special handling untuk KADUS - 14 akun dengan format khusus
            if ($role->name === 'kadus') {
                foreach ($dusuns as $index => $dusun) {
                    $dusunNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    $userId = Str::uuid();
                    
                    DB::table('users')->insert([
                        'id' => $userId,
                        'fullname' => 'Kadus Dusun ' . ($index + 1),
                        'phone_number' => '0813-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                        'bio' => 'Kepala Dusun ' . ($index + 1) . ' - Desa Sindang Anom',
                        'email' => 'dusun' . $dusunNumber . '@sindanganom.id',
                        'email_verified_at' => now(),
                        'password' => Hash::make('dusun' . $dusunNumber),
                        'dusun_id' => $dusun->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    DB::table('model_has_roles')->insert([
                        'role_id' => $role->id,
                        'model_type' => 'App\Models\User',
                        'model_id' => $userId,
                    ]);
                }
            }
            // Roles lain tetap pakai faker dengan jumlah lebih sedikit
            else {
                $count = ($role->name === 'farmer') ? 5 : 3; // Farmer 5, yang lain 3
                
                for ($i = 0; $i < $count; $i++) {
                    $userId = Str::uuid();
                    
                    // Farmer perlu dusun_id
                    $dusunId = null;
                    if ($role->name === 'farmer') {
                        $dusunId = $dusuns[$i % count($dusuns)]->id;
                    }
                    
                    DB::table('users')->insert([
                        'id' => $userId,
                        'fullname' => $faker->name,
                        'phone_number' => $faker->phoneNumber,
                        'bio' => $faker->sentence,
                        'email' => $faker->unique()->safeEmail,
                        'email_verified_at' => now(),
                        'password' => Hash::make('password'),
                        'dusun_id' => $dusunId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    DB::table('model_has_roles')->insert([
                        'role_id' => $role->id,
                        'model_type' => 'App\Models\User',
                        'model_id' => $userId,
                    ]);
                }
            }
        }
    }
}

