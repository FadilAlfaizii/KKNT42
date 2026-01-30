<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates 13 specific accounts for the village information system
     */
    public function run(): void
    {
        // Define the 13 accounts with their roles and details
        $accounts = [
            // 1. Village Leadership
            [
                'fullname' => 'Bapak Kepala Desa',
                'email' => 'kepala.desa@sindanganom.id',
                'phone_number' => '0812-3456-7890',
                'bio' => 'Kepala Desa Sindang Anom',
                'role' => 'kepala_desa', // You can change this to 'kepala_desa' after creating the role
                'password' => 'kepala123'
            ],
            
            // 2. Secretary
            [
                'fullname' => 'Sekretaris Desa',
                'email' => 'sekretaris@sindanganom.id',
                'phone_number' => '0812-3456-7891',
                'bio' => 'Sekretaris Desa',
                'role' => 'sekretaris', // Change to 'sekretaris' after role creation
                'password' => 'sekretaris123'
            ],
            
            // 3. Operator
            [
                'fullname' => 'Operator',
                'email' => 'operator1@sindanganom.id',
                'phone_number' => '0812-3456-7892',
                'bio' => 'Operator',
                'role' => 'operator', // Change to 'operator' after role creation
                'password' => 'operator123'
            ],
            

            [
                'fullname' => 'Operator',
                'email' => 'operator2@sindanganom.id',
                'phone_number' => '0812-3456-7892',
                'bio' => 'Operator',
                'role' => 'operator', // Change to 'operator' after role creation
                'password' => 'operator123'
            ],
        
            
            // 4-18. Kepala Dusun
            [
                'fullname' => 'Kepala Dusun 1',
                'email' => 'dusun01@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 01',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun1'
            ],

            [
                'fullname' => 'Kepala Dusun 2',
                'email' => 'dusun02@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 02',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun2'
            ],
             
            [
                'fullname' => 'Kepala Dusun 3',
                'email' => 'dusun03@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 03',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun3'
            ],
             
            [
                'fullname' => 'Kepala Dusun 4',
                'email' => 'dusun04@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 04',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun4'
            ],
             
            [
                'fullname' => 'Kepala Dusun 5',
                'email' => 'dusun05@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 05',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun5'
            ],
             
            [
                'fullname' => 'Kepala Dusun 6',
                'email' => 'dusun06@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 06',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun6'
            ],
             
            [
                'fullname' => 'Kepala Dusun 7',
                'email' => 'dusun07@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 07',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun7'
            ],
             
            [
                'fullname' => 'Kepala Dusun 8',
                'email' => 'dusun08@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 08',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun8'
            ],
             
            [
                'fullname' => 'Kepala Dusun 9',
                'email' => 'dusun09@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 09',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun9'
            ],
             
            [
                'fullname' => 'Kepala Dusun 10',
                'email' => 'dusun10@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 10',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun10'
            ],
             
            [
                'fullname' => 'Kepala Dusun 11',
                'email' => 'dusun11@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 11',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun11'
            ],
             
            [
                'fullname' => 'Kepala Dusun 12',
                'email' => 'dusun12@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 12',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun12'
            ],
             
            [
                'fullname' => 'Kepala Dusun 13',
                'email' => 'dusun13@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 13',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun13'
            ],
             
            [
                'fullname' => 'Kepala Dusun 14',
                'email' => 'dusun14@sindanganom.id',
                'phone_number' => '0812-3456-7896',
                'bio' => 'Kepala Dusun 14',
                'role' => 'kadus', // Change to 'rt_rw' after role creation
                'password' => 'dusun14'
            ],
             
            
            // 12. Data Manager
            [
                'fullname' => 'Pengelola Data',
                'email' => 'data@sindanganom.id',
                'phone_number' => '0812-3456-7901',
                'bio' => 'Pengelola Data Statistik Desa',
                'role' => 'pengelola_data', // Change to 'pengelola_data' after role creation
                'password' => 'data123'
            ],
            
        ];

        foreach ($accounts as $account) {
            // Get the role
            $role = DB::table('roles')->where('name', $account['role'])->first();
            
            if (!$role) {
                $this->command->warn("Role '{$account['role']}' not found. Skipping {$account['email']}");
                continue;
            }

            // Create user
            $userId = Str::uuid();
            DB::table('users')->insert([
                'id' => $userId,
                'fullname' => $account['fullname'],
                'phone_number' => $account['phone_number'],
                'bio' => $account['bio'],
                'email' => $account['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($account['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign role
            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ]);

            $this->command->info("Created: {$account['email']} (Role: {$account['role']})");
        }

        $this->command->info('✅ Successfully created 13 custom accounts!');
    }
}
