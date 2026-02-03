<?php

namespace Tests\Feature;

use App\Services\KKExtraction\KKImportService;
use App\Models\Dusun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DusunNormalizationTest extends TestCase
{
    /**
     * Test that Roman numeral dusun names are normalized to Arabic
     */
    public function test_roman_numeral_normalization(): void
    {
        // Ensure we have Arabic dusuns
        $dusun1 = Dusun::firstOrCreate(['name' => 'Dusun 1', 'code' => 'DSN1'], ['is_active' => true]);
        $dusun3 = Dusun::firstOrCreate(['name' => 'Dusun 3', 'code' => 'DSN3'], ['is_active' => true]);
        $dusun9 = Dusun::firstOrCreate(['name' => 'Dusun 9', 'code' => 'DSN9'], ['is_active' => true]);
        
        // Get import service
        $service = app(KKImportService::class);
        
        // Use reflection to access private method
        $method = new \ReflectionMethod($service, 'normalizeDusunName');
        $method->setAccessible(true);
        
        // Test cases
        $testCases = [
            'DUSUN I' => 'Dusun 1',
            ' DUSUN I' => 'Dusun 1',
            'DUSUN III' => 'Dusun 3',
            ' DUSUN III  ' => 'Dusun 3',
            'dusun ix' => 'Dusun 9',
            'Dusun 1' => 'Dusun 1',
            'dusun 5' => 'Dusun 5',
        ];
        
        foreach ($testCases as $input => $expected) {
            $result = $method->invoke($service, $input);
            $this->assertEquals(
                $expected, 
                $result, 
                "Failed: '{$input}' should normalize to '{$expected}', got '{$result}'"
            );
        }
        
        $this->assertTrue(true, 'All normalization tests passed!');
    }
    
    /**
     * Test that findOrCreateDusun reuses existing Arabic dusuns
     */
    public function test_find_or_create_with_roman_input(): void
    {
        // Create Arabic dusun first
        $dusun7 = Dusun::firstOrCreate(['name' => 'Dusun 7', 'code' => 'DSN7'], ['is_active' => true]);
        $initialCount = Dusun::count();
        
        // Get service
        $service = app(KKImportService::class);
        $method = new \ReflectionMethod($service, 'findOrCreateDusun');
        $method->setAccessible(true);
        
        // Try to create with Roman numeral
        $result = $method->invoke($service, 'DUSUN VII');
        
        // Should NOT create new dusun
        $this->assertEquals($initialCount, Dusun::count(), 'Should not create duplicate dusun');
        $this->assertEquals($dusun7->id, $result->id, 'Should return existing dusun');
        $this->assertEquals('Dusun 7', $result->name, 'Should have Arabic name');
    }
}
