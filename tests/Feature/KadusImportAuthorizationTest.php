<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Dusun;
use App\Services\KKExtraction\KKImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KadusImportAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'kadus']);
        Role::create(['name' => 'super_admin']);
    }

    /**
     * Test that kadus can only import their own dusun data
     */
    public function test_kadus_can_only_import_own_dusun(): void
    {
        // Create dusuns
        $dusun1 = Dusun::create(['name' => 'Dusun 1', 'code' => 'DSN1', 'is_active' => true]);
        $dusun2 = Dusun::create(['name' => 'Dusun 2', 'code' => 'DSN2', 'is_active' => true]);
        
        // Create kadus user assigned to Dusun 1
        $kadus = User::factory()->create([
            'email' => 'kadus1@test.com',
            'dusun_id' => $dusun1->id,
        ]);
        $kadus->assignRole('kadus');
        
        // Login as kadus
        $this->actingAs($kadus);
        
        // Prepare test data with mixed dusuns
        $extractedData = [
            // Data from Dusun 1 (should succeed)
            [
                'no_kk' => '1234567890123456',
                'nik' => '3201010101010001',
                'nama' => 'John Doe',
                'dusun' => 'Dusun 1',
                'alamat' => 'Jl. Test 1',
                'rt' => '001',
                'rw' => '001',
                'jenis_kelamin' => 'LAKI-LAKI',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '01-01-1990',
                'agama' => 'ISLAM',
                'status_perkawinan' => 'KAWIN',
                'shdk' => 'KEPALA KELUARGA',
                'pekerjaan' => 'WIRASWASTA',
                'kewarganegaraan' => 'WNI',
            ],
            // Data from Dusun 2 (should be rejected)
            [
                'no_kk' => '1234567890123457',
                'nik' => '3201010101010002',
                'nama' => 'Jane Doe',
                'dusun' => 'Dusun 2',
                'alamat' => 'Jl. Test 2',
                'rt' => '002',
                'rw' => '002',
                'jenis_kelamin' => 'PEREMPUAN',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '02-02-1992',
                'agama' => 'ISLAM',
                'status_perkawinan' => 'KAWIN',
                'shdk' => 'KEPALA KELUARGA',
                'pekerjaan' => 'GURU',
                'kewarganegaraan' => 'WNI',
            ],
        ];
        
        // Execute import
        $service = new KKImportService();
        $results = $service->importToDatabase($extractedData, false);
        
        // Assertions
        $this->assertTrue($results['success']);
        $this->assertEquals(1, $results['imported_kk'], 'Should import only 1 KK from own dusun');
        $this->assertEquals(1, $results['imported_penduduk'], 'Should import only 1 person from own dusun');
        $this->assertEquals(1, $results['skipped'], 'Should skip 1 person from other dusun');
        $this->assertNotEmpty($results['unauthorized_dusun'], 'Should track unauthorized dusun');
        $this->assertArrayHasKey('Dusun 2', $results['unauthorized_dusun'], 'Should list Dusun 2 as unauthorized');
        $this->assertEquals(1, $results['unauthorized_dusun']['Dusun 2'], 'Should count 1 rejected KK from Dusun 2');
    }

    /**
     * Test that super admin can import any dusun
     */
    public function test_super_admin_can_import_any_dusun(): void
    {
        // Create dusuns
        $dusun1 = Dusun::create(['name' => 'Dusun 1', 'code' => 'DSN1', 'is_active' => true]);
        $dusun2 = Dusun::create(['name' => 'Dusun 2', 'code' => 'DSN2', 'is_active' => true]);
        
        // Create super admin (no dusun_id required)
        $admin = User::factory()->create(['email' => 'admin@test.com']);
        $admin->assignRole('super_admin');
        
        // Login as admin
        $this->actingAs($admin);
        
        // Prepare data with multiple dusuns
        $extractedData = [
            [
                'no_kk' => '1234567890123456',
                'nik' => '3201010101010001',
                'nama' => 'John Doe',
                'dusun' => 'Dusun 1',
                'alamat' => 'Jl. Test 1',
                'rt' => '001',
                'rw' => '001',
                'jenis_kelamin' => 'LAKI-LAKI',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '01-01-1990',
                'agama' => 'ISLAM',
                'status_perkawinan' => 'KAWIN',
                'shdk' => 'KEPALA KELUARGA',
                'pekerjaan' => 'WIRASWASTA',
                'kewarganegaraan' => 'WNI',
            ],
            [
                'no_kk' => '1234567890123457',
                'nik' => '3201010101010002',
                'nama' => 'Jane Doe',
                'dusun' => 'Dusun 2',
                'alamat' => 'Jl. Test 2',
                'rt' => '002',
                'rw' => '002',
                'jenis_kelamin' => 'PEREMPUAN',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '02-02-1992',
                'agama' => 'ISLAM',
                'status_perkawinan' => 'KAWIN',
                'shdk' => 'KEPALA KELUARGA',
                'pekerjaan' => 'GURU',
                'kewarganegaraan' => 'WNI',
            ],
        ];
        
        // Execute import
        $service = new KKImportService();
        $results = $service->importToDatabase($extractedData, false);
        
        // Assertions - admin should import all
        $this->assertTrue($results['success']);
        $this->assertEquals(2, $results['imported_kk'], 'Admin should import all KK');
        $this->assertEquals(2, $results['imported_penduduk'], 'Admin should import all people');
        $this->assertEquals(0, $results['skipped'], 'Admin should not skip any data');
        $this->assertEmpty($results['unauthorized_dusun'], 'Admin should have no unauthorized dusun');
    }

    /**
     * Test that kadus without dusun_id cannot import
     */
    public function test_kadus_without_dusun_id_cannot_import(): void
    {
        // Create kadus without dusun_id
        $kadus = User::factory()->create([
            'email' => 'kadus_no_dusun@test.com',
            'dusun_id' => null,
        ]);
        $kadus->assignRole('kadus');
        
        $this->actingAs($kadus);
        
        $extractedData = [
            [
                'no_kk' => '1234567890123456',
                'nik' => '3201010101010001',
                'nama' => 'John Doe',
                'dusun' => 'Dusun 1',
                'alamat' => 'Jl. Test 1',
                'rt' => '001',
                'rw' => '001',
                'jenis_kelamin' => 'LAKI-LAKI',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '01-01-1990',
                'agama' => 'ISLAM',
                'status_perkawinan' => 'KAWIN',
                'shdk' => 'KEPALA KELUARGA',
                'pekerjaan' => 'WIRASWASTA',
                'kewarganegaraan' => 'WNI',
            ],
        ];
        
        $service = new KKImportService();
        $results = $service->importToDatabase($extractedData, false);
        
        // Should fail with error
        $this->assertFalse($results['success']);
        $this->assertEquals(0, $results['imported_kk']);
        $this->assertNotEmpty($results['errors']);
        $this->assertStringContainsString('belum terdaftar ke dusun', $results['errors'][0]);
    }
}
