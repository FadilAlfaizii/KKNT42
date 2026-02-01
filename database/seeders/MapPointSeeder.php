<?php

namespace Database\Seeders;

use App\Models\MapPoint;
use App\Models\Dusun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MapPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to CSV file
        $csvPath = base_path('Data Lokasi Desa Sindang Anom.csv');

        if (!File::exists($csvPath)) {
            $this->command->warn('CSV file not found: ' . $csvPath);
            return;
        }

        // Get default dusun (or first dusun)
        $defaultDusun = Dusun::first();
        
        if (!$defaultDusun) {
            $this->command->error('No dusun found. Please seed dusuns first.');
            return;
        }

        $this->command->info('Reading CSV file...');

        // Read CSV file
        $csv = File::get($csvPath);
        $lines = explode("\n", $csv);
        
        // Remove header
        array_shift($lines);

        $imported = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            // Parse CSV line (delimiter is semicolon)
            $data = str_getcsv($line, ';');

            if (count($data) < 6) {
                $skipped++;
                continue;
            }

            // Extract data
            $no = $data[0] ?? '';
            $name = $data[1] ?? '';
            $category = $data[2] ?? '';
            $address = $data[3] ?? '';
            $latitude = $data[4] ?? '';
            $longitude = $data[5] ?? '';
            $description = $data[6] ?? '';
            $imageLink = $data[7] ?? '';

            // Skip if name is empty
            if (empty($name)) {
                $skipped++;
                continue;
            }

            // Fix coordinate format (remove dots in wrong places)
            // From: -5.294.089 To: -5.294089
            $latitude = str_replace('.', '', $latitude);
            $latitude = (float) ($latitude / 1000000);
            
            $longitude = str_replace('.', '', $longitude);
            $longitude = (float) ($longitude / 1000000);

            // Validate coordinates
            if ($latitude == 0 || $longitude == 0) {
                $this->command->warn("Invalid coordinates for: {$name}");
                $skipped++;
                continue;
            }

            // Map category
            $categoryMap = [
                'UMKM' => 'UMKM',
                'Pendidikan' => 'Pendidikan',
                'Olahraga' => 'Olahraga',
                'Ibadah' => 'Ibadah',
                'Kesehatan' => 'Kesehatan',
                'Pemerintah' => 'Pemerintah',
            ];

            $mappedCategory = $categoryMap[$category] ?? 'UMKM';

            // Build description
            $fullDescription = trim($description);
            
            // Convert Google Drive link to direct image URL
            $imageUrl = null;
            if (!empty($imageLink) && str_contains($imageLink, 'drive.google.com')) {
                // Extract file ID from Google Drive link
                // From: https://drive.google.com/file/d/FILE_ID/view?usp=drive_link
                // To: https://drive.google.com/uc?export=view&id=FILE_ID
                preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $imageLink, $matches);
                if (isset($matches[1])) {
                    $fileId = $matches[1];
                    $imageUrl = "https://drive.google.com/uc?export=view&id={$fileId}";
                }
            }

            // Check if already exists
            $exists = MapPoint::where('name', $name)
                ->where('latitude', $latitude)
                ->where('longitude', $longitude)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Create map point
            try {
                MapPoint::create([
                    'name' => $name,
                    'category' => $mappedCategory,
                    'address' => $address,
                    'description' => $fullDescription,
                    'image_url' => $imageUrl,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'is_active' => true,
                    'dusun_id' => $defaultDusun->id,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $this->command->error("Failed to import: {$name} - " . $e->getMessage());
                $skipped++;
            }
        }

        $this->command->info("MapPoint seeding completed!");
        $this->command->info("Imported: {$imported}");
        $this->command->info("Skipped: {$skipped}");
    }
}
