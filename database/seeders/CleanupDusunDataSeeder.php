<?php

namespace Database\Seeders;

use App\Models\Dusun;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupDusunDataSeeder extends Seeder
{
    /**
     * Map of Roman to Arabic numerals
     */
    private array $romanToArabic = [
        'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5,
        'VI' => 6, 'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10,
        'XI' => 11, 'XII' => 12, 'XIII' => 13, 'XIV' => 14, 'XV' => 15,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        
        try {
            $this->info('Starting dusun cleanup...');
            
            // Step 1: Find duplicate dusuns (Roman format)
            $romanDusuns = Dusun::where('name', 'LIKE', '%DUSUN I%')
                ->orWhere('name', 'LIKE', '%DUSUN II%')
                ->orWhere('name', 'LIKE', '%DUSUN III%')
                ->orWhere('name', 'LIKE', '%DUSUN IV%')
                ->orWhere('name', 'LIKE', '%DUSUN V%')
                ->orWhere('name', 'LIKE', '%DUSUN VI%')
                ->orWhere('name', 'LIKE', '%DUSUN VII%')
                ->orWhere('name', 'LIKE', '%DUSUN VIII%')
                ->orWhere('name', 'LIKE', '%DUSUN IX%')
                ->orWhere('name', 'LIKE', '%DUSUN X%')
                ->get();

            foreach ($romanDusuns as $romanDusun) {
                $arabicNumber = $this->extractArabicNumber($romanDusun->name);
                
                if (!$arabicNumber) {
                    continue;
                }
                
                // Find corresponding Arabic dusun
                $arabicDusun = Dusun::where('name', 'Dusun ' . $arabicNumber)->first();
                
                if ($arabicDusun) {
                    $this->info("Merging '{$romanDusun->name}' (ID: {$romanDusun->id}) -> 'Dusun {$arabicNumber}' (ID: {$arabicDusun->id})");
                    
                    // Update foreign keys
                    Keluarga::where('dusun_id', $romanDusun->id)
                        ->update(['dusun_id' => $arabicDusun->id]);
                    
                    Penduduk::where('dusun_id', $romanDusun->id)
                        ->update(['dusun_id' => $arabicDusun->id]);
                    
                    User::where('dusun_id', $romanDusun->id)
                        ->update(['dusun_id' => $arabicDusun->id]);
                    
                    // Delete duplicate
                    $romanDusun->delete();
                    $this->info("✓ Deleted duplicate dusun: {$romanDusun->name}");
                } else {
                    // Rename Roman to Arabic format
                    $this->info("Renaming '{$romanDusun->name}' -> 'Dusun {$arabicNumber}'");
                    $romanDusun->update(['name' => 'Dusun ' . $arabicNumber]);
                }
            }
            
            // Step 2: Assign kadus users to their dusuns
            $this->assignKadusToProperDusun();
            
            // Step 3: Report final state
            $this->reportFinalState();
            
            DB::commit();
            $this->info('✓ Dusun cleanup completed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Cleanup failed: ' . $e->getMessage());
            Log::error('Dusun cleanup failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Extract Arabic number from Roman dusun name
     */
    private function extractArabicNumber(string $name): ?int
    {
        $name = strtoupper(trim($name));
        
        foreach ($this->romanToArabic as $roman => $arabic) {
            if (preg_match('/DUSUN\s+' . $roman . '$/i', $name)) {
                return $arabic;
            }
        }
        
        return null;
    }

    /**
     * Assign kadus users to their proper dusun
     */
    private function assignKadusToProperDusun(): void
    {
        $this->info('Assigning kadus users to dusuns...');
        
        // Pattern: dusun01@sindanganom.id -> Dusun 1
        $kadusUsers = User::where('email', 'LIKE', 'dusun%@sindanganom.id')
            ->whereNull('dusun_id')
            ->get();
        
        foreach ($kadusUsers as $user) {
            // Extract number from email (e.g., "dusun01" -> 1)
            if (preg_match('/dusun(\d+)@/', $user->email, $matches)) {
                $number = (int) $matches[1];
                $dusun = Dusun::where('name', 'Dusun ' . $number)->first();
                
                if ($dusun) {
                    $user->update(['dusun_id' => $dusun->id]);
                    $this->info("✓ Assigned {$user->email} -> Dusun {$number} (ID: {$dusun->id})");
                } else {
                    $this->warn("⚠ Dusun {$number} not found for {$user->email}");
                }
            }
        }
    }

    /**
     * Report final state after cleanup
     */
    private function reportFinalState(): void
    {
        $this->info('');
        $this->info('=== Final State Report ===');
        
        // Count dusuns
        $dusunCount = Dusun::count();
        $this->info("Total Dusuns: {$dusunCount}");
        
        // Count KK per dusun
        $dusuns = Dusun::withCount('keluargas')->get();
        foreach ($dusuns as $dusun) {
            $this->info("  - {$dusun->name}: {$dusun->keluargas_count} KK");
        }
        
        // Count kadus assignments
        $assignedKadus = User::role('kadus')->whereNotNull('dusun_id')->count();
        $unassignedKadus = User::role('kadus')->whereNull('dusun_id')->count();
        $this->info("Kadus assigned: {$assignedKadus}");
        $this->info("Kadus unassigned: {$unassignedKadus}");
    }

    private function info(string $message): void
    {
        $this->command->info($message);
        Log::info($message);
    }

    private function warn(string $message): void
    {
        $this->command->warn($message);
        Log::warning($message);
    }

    private function error(string $message): void
    {
        $this->command->error($message);
        Log::error($message);
    }
}
