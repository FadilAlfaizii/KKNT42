<?php

namespace App\Services\KKExtraction;

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Dusun;
use App\Models\KKExtractionHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KKImportService
{
    /**
     * Check for duplicate NIKs in extracted data and existing database
     */
    public function checkDuplicates(array $extractedData): array
    {
        $duplicates = [
            'in_batch' => [],
            'in_database' => [],
            'summary' => [
                'total_duplicates' => 0,
                'batch_duplicates' => 0,
                'db_duplicates' => 0,
            ]
        ];

        $nikCounts = [];
        $niks = [];

        // Check duplicates within batch
        foreach ($extractedData as $index => $person) {
            $nik = $person['nik'] ?? null;
            
            if (empty($nik) || $nik === '-') {
                continue;
            }

            $niks[] = $nik;

            if (!isset($nikCounts[$nik])) {
                $nikCounts[$nik] = [];
            }
            $nikCounts[$nik][] = [
                'index' => $index,
                'nama' => $person['nama'] ?? '-',
                'no_kk' => $person['no_kk'] ?? '-',
            ];
        }

        // Find batch duplicates
        foreach ($nikCounts as $nik => $entries) {
            if (count($entries) > 1) {
                $duplicates['in_batch'][$nik] = $entries;
                $duplicates['summary']['batch_duplicates'] += count($entries);
            }
        }

        // Check duplicates in database
        if (!empty($niks)) {
            $existingNiks = Penduduk::whereIn('nik', $niks)
                ->select('nik', 'nama_lengkap', 'keluarga_id')
                ->with('keluarga:id,no_kk')
                ->get()
                ->groupBy('nik');

            foreach ($existingNiks as $nik => $penduduks) {
                $duplicates['in_database'][$nik] = $penduduks->map(function ($p) {
                    return [
                        'nama' => $p->nama_lengkap,
                        'no_kk' => $p->keluarga->no_kk ?? '-',
                    ];
                })->toArray();
                $duplicates['summary']['db_duplicates'] += count($penduduks);
            }
        }

        $duplicates['summary']['total_duplicates'] = 
            $duplicates['summary']['batch_duplicates'] + 
            $duplicates['summary']['db_duplicates'];

        return $duplicates;
    }

    /**
     * Import extracted data to database
     */
    public function importToDatabase(array $extractedData, bool $skipDuplicates = false): array
    {
        $results = [
            'success' => false,
            'imported_kk' => 0,
            'imported_penduduk' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        Log::info('KK Import Started', [
            'total_records' => count($extractedData),
            'skip_duplicates' => $skipDuplicates
        ]);

        try {
            DB::beginTransaction();

            // Group by no_kk
            $groupedByKK = [];
            foreach ($extractedData as $person) {
                $no_kk = $person['no_kk'] ?? null;
                if (empty($no_kk) || $no_kk === '-') {
                    $results['errors'][] = "Skipped: No KK for {$person['nama']}";
                    $results['skipped']++;
                    continue;
                }

                if (!isset($groupedByKK[$no_kk])) {
                    $groupedByKK[$no_kk] = [];
                }
                $groupedByKK[$no_kk][] = $person;
            }

            Log::info('KK Grouped', ['total_kk' => count($groupedByKK)]);

            // Import each KK
            foreach ($groupedByKK as $no_kk => $members) {
                try {
                    // Check if KK exists
                    $keluarga = Keluarga::where('no_kk', $no_kk)->first();
                    
                    if (!$keluarga) {
                        // Create new KK
                        $firstMember = $members[0];
                        
                        // Find or create dusun
                        $dusunName = $firstMember['dusun'] ?? 'Belum Ditentukan';
                        if (empty($dusunName) || $dusunName === '-') {
                            $dusunName = 'Belum Ditentukan';
                        }
                        $dusun = $this->findOrCreateDusun($dusunName);
                        
                        $keluarga = Keluarga::create([
                            'dusun_id' => $dusun->id,
                            'no_kk' => $no_kk,
                            'kepala_keluarga' => $this->findKepalaKeluarga($members),
                            'alamat' => $firstMember['alamat'] ?? '-',
                            'rt' => $firstMember['rt'] ?? '-',
                            'rw' => $firstMember['rw'] ?? '-',
                            'status_kk' => 'AKTIF',
                        ]);
                        
                        $results['imported_kk']++;
                        Log::info('KK Created', ['no_kk' => $no_kk, 'dusun' => $dusun->name]);
                    }

                    // Import members
                    foreach ($members as $member) {
                        $nik = $member['nik'] ?? null;
                        
                        // Skip if no NIK
                        if (empty($nik) || $nik === '-') {
                            $results['skipped']++;
                            continue;
                        }

                        // Check duplicate if needed
                        if ($skipDuplicates) {
                            $exists = Penduduk::where('nik', $nik)->exists();
                            if ($exists) {
                                $results['skipped']++;
                                continue;
                            }
                        }

                        // Import or update penduduk
                        Penduduk::updateOrCreate(
                            ['nik' => $nik],
                            [
                                'keluarga_id' => $keluarga->id,
                                'dusun_id' => $keluarga->dusun_id,
                                'nama_lengkap' => $member['nama'] ?? '-',
                                'jenis_kelamin' => $this->mapJenisKelamin($member['sex'] ?? null),
                                'tempat_lahir' => $member['tempatlahir'] ?? '-',
                                'tanggal_lahir' => $this->parseDate($member['tanggallahir'] ?? null),
                                'agama' => $this->mapAgama($member['agama_id'] ?? null),
                                'pendidikan' => $this->mapPendidikan($member['pendidikan_kk_id'] ?? null),
                                'pekerjaan' => $this->mapPekerjaan($member['pekerjaan_id'] ?? null),
                                'status_perkawinan' => $this->mapStatusKawin($member['status_kawin'] ?? null),
                                'status_dalam_keluarga' => $this->mapKKLevel($member['kk_level'] ?? null),
                                'nama_ayah' => $member['nama_ayah'] ?? '-',
                                'nama_ibu' => $member['nama_ibu'] ?? '-',
                                'golongan_darah' => $this->mapGolDarah($member['golongan_darah_id'] ?? null),
                                'kewarganegaraan' => $this->mapWarganegara($member['warganegara_id'] ?? null),
                                'status_penduduk' => 'TETAP',
                            ]
                        );
                        
                        $results['imported_penduduk']++;
                        Log::info('Penduduk Imported', ['nik' => $nik, 'nama' => $member['nama'] ?? '-']);
                    }
                } catch (\Exception $e) {
                    $results['errors'][] = "Error importing KK {$no_kk}: " . $e->getMessage();
                    Log::error("KK Import Error: {$no_kk}", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                }
            }

            DB::commit();
            $results['success'] = true;
            
            Log::info('KK Import Success', [
                'imported_kk' => $results['imported_kk'],
                'imported_penduduk' => $results['imported_penduduk'],
                'skipped' => $results['skipped']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $results['errors'][] = "Transaction failed: " . $e->getMessage();
            Log::error("KK Import Transaction Error", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

        return $results;
    }

    /**
     * Find or create Dusun
     */
    private function findOrCreateDusun(string $namaDusun): Dusun
    {
        $dusun = Dusun::where('name', $namaDusun)->first();
        
        if (!$dusun) {
            // Generate code from name
            $code = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $namaDusun), 0, 10));
            if (empty($code)) {
                $code = 'DSN' . rand(100, 999);
            }
            
            // Ensure code is unique
            $baseCode = $code;
            $counter = 1;
            while (Dusun::where('code', $code)->exists()) {
                $code = $baseCode . $counter;
                $counter++;
            }
            
            $dusun = Dusun::create([
                'name' => $namaDusun,
                'code' => $code,
                'description' => 'Auto-created from KK extraction',
                'is_active' => true,
            ]);
            
            Log::info('Dusun Created', ['name' => $namaDusun, 'code' => $code]);
        }
        
        return $dusun;
    }

    /**
     * Find Kepala Keluarga from members
     */
    private function findKepalaKeluarga(array $members): string
    {
        foreach ($members as $member) {
            $kkLevel = strtoupper($member['kk_level'] ?? '');
            if ($kkLevel === 'KEPALA KELUARGA' || $kkLevel === '1') {
                return $member['nama'] ?? 'Unknown';
            }
        }
        
        // Return first member if no kepala found
        return $members[0]['nama'] ?? 'Unknown';
    }

    /**
     * Mapping helpers
     */
    private function mapJenisKelamin($value): string
    {
        if (is_numeric($value)) {
            return $value == 1 ? 'LAKI-LAKI' : 'PEREMPUAN';
        }
        $upper = strtoupper($value ?? '');
        if (str_contains($upper, 'LAKI') || $upper === 'L') return 'LAKI-LAKI';
        if (str_contains($upper, 'PEREMPUAN') || $upper === 'P') return 'PEREMPUAN';
        return 'LAKI-LAKI';
    }

    private function mapAgama($value): string
    {
        // If value is already a descriptive string (not numeric), use it directly
        if (!is_numeric($value) && is_string($value) && !empty($value)) {
            $cleaned = trim($value);
            if ($cleaned !== '-' && strlen($cleaned) > 1) {
                return ucfirst(strtolower($cleaned));
            }
        }
        
        // If numeric ID, map to proper label
        if (is_numeric($value)) {
            $map = [
                1 => 'Islam', 2 => 'Kristen', 3 => 'Katolik',
                4 => 'Hindu', 5 => 'Buddha', 6 => 'Konghucu', 7 => 'Kepercayaan'
            ];
            return $map[$value] ?? 'Islam';
        }
        
        return 'Islam';
    }

    private function mapPendidikan($value): string
    {
        // If value is already a descriptive string (not numeric), use it directly
        if (!is_numeric($value) && is_string($value) && !empty($value)) {
            $cleaned = trim($value);
            if ($cleaned !== '-' && strlen($cleaned) > 1) {
                return $cleaned;
            }
        }
        
        // If numeric ID, map to proper label
        if (is_numeric($value)) {
            $map = [
                1 => 'Tidak/Belum Sekolah', 2 => 'Belum Tamat SD/Sederajat',
                3 => 'Tamat SD/Sederajat', 4 => 'SLTP/Sederajat',
                5 => 'SLTA/Sederajat', 6 => 'Diploma I/II',
                7 => 'Akademi/Diploma III/S.Muda', 8 => 'Diploma IV/Strata I',
                9 => 'Strata II', 10 => 'Strata III'
            ];
            return $map[$value] ?? 'Tidak/Belum Sekolah';
        }
        
        return 'Tidak/Belum Sekolah';
    }

    private function mapPekerjaan($value): string
    {
        // If value is already a descriptive string (not numeric), use it directly
        if (!is_numeric($value) && is_string($value) && !empty($value)) {
            // Clean up the string
            $cleaned = trim($value);
            // If it's not just a dash or placeholder, return it
            if ($cleaned !== '-' && strlen($cleaned) > 1) {
                return $cleaned;
            }
        }
        
        // If numeric ID, map to proper label
        if (is_numeric($value)) {
            $map = [
                1 => 'Belum/Tidak Bekerja',
                2 => 'Mengurus Rumah Tangga',
                3 => 'Pelajar/Mahasiswa',
                4 => 'Pensiunan',
                5 => 'Pegawai Negeri Sipil',
                6 => 'Tentara Nasional Indonesia',
                7 => 'Kepolisian RI',
                8 => 'Perdagangan',
                9 => 'Petani/Pekebun',
                10 => 'Peternak',
                11 => 'Nelayan/Perikanan',
                12 => 'Industri',
                13 => 'Konstruksi',
                14 => 'Transportasi',
                15 => 'Karyawan Swasta',
                16 => 'Karyawan BUMN',
                17 => 'Karyawan BUMD',
                18 => 'Karyawan Honorer',
                19 => 'Buruh Harian Lepas',
                20 => 'Buruh Tani/Perkebunan',
                21 => 'Buruh Nelayan/Perikanan',
                22 => 'Buruh Peternakan',
                23 => 'Pembantu Rumah Tangga',
                24 => 'Tukang Cukur',
                25 => 'Tukang Listrik',
                26 => 'Tukang Batu',
                27 => 'Tukang Kayu',
                28 => 'Tukang Sol Sepatu',
                29 => 'Tukang Las/Pandai Besi',
                30 => 'Tukang Jahit',
                31 => 'Tukang Gigi',
                32 => 'Penata Rias',
                33 => 'Penata Busana',
                34 => 'Penata Rambut',
                35 => 'Mekanik',
                36 => 'Seniman',
                37 => 'Tabib',
                38 => 'Paraji',
                39 => 'Perancang Busana',
                40 => 'Penterjemah',
                41 => 'Imam Masjid',
                42 => 'Pendeta',
                43 => 'Pastor',
                44 => 'Wartawan',
                45 => 'Ustadz/Mubaligh',
                46 => 'Juru Masak',
                47 => 'Promotor Acara',
                48 => 'Anggota DPR-RI',
                49 => 'Anggota DPD',
                50 => 'Anggota BPK',
                51 => 'Presiden',
                52 => 'Wakil Presiden',
                53 => 'Anggota Mahkamah Konstitusi',
                54 => 'Anggota Kabinet/Kementerian',
                55 => 'Duta Besar',
                56 => 'Gubernur',
                57 => 'Wakil Gubernur',
                58 => 'Bupati',
                59 => 'Wakil Bupati',
                60 => 'Walikota',
                61 => 'Wakil Walikota',
                62 => 'Anggota DPRD Provinsi',
                63 => 'Anggota DPRD Kabupaten/Kota',
                64 => 'Dosen',
                65 => 'Guru',
                66 => 'Pilot',
                67 => 'Pengacara',
                68 => 'Notaris',
                69 => 'Arsitek',
                70 => 'Akuntan',
                71 => 'Konsultan',
                72 => 'Dokter',
                73 => 'Bidan',
                74 => 'Perawat',
                75 => 'Apoteker',
                76 => 'Psikiater/Psikolog',
                77 => 'Penyiar Televisi',
                78 => 'Penyiar Radio',
                79 => 'Pelaut',
                80 => 'Peneliti',
                81 => 'Sopir',
                82 => 'Pialang',
                83 => 'Paranormal',
                84 => 'Pedagang',
                85 => 'Perangkat Desa',
                86 => 'Kepala Desa',
                87 => 'Biarawati',
                88 => 'Wiraswasta',
                89 => 'Lainnya',
            ];
            return $map[$value] ?? 'Lainnya';
        }
        
        return 'Belum/Tidak Bekerja';
    }

    private function mapStatusKawin($value): string
    {
        if (is_numeric($value)) {
            $map = [
                1 => 'BELUM KAWIN', 2 => 'KAWIN', 3 => 'CERAI HIDUP', 4 => 'CERAI MATI'
            ];
            return $map[$value] ?? 'BELUM KAWIN';
        }
        $upper = strtoupper($value ?? '');
        if (str_contains($upper, 'KAWIN') && !str_contains($upper, 'BELUM')) return 'KAWIN';
        if (str_contains($upper, 'CERAI')) return 'CERAI HIDUP';
        return 'BELUM KAWIN';
    }

    private function mapKKLevel($value): string
    {
        if (is_numeric($value)) {
            $map = [
                1 => 'KEPALA KELUARGA', 2 => 'SUAMI', 3 => 'ISTRI',
                4 => 'ANAK', 5 => 'MENANTU', 6 => 'CUCU', 7 => 'ORANGTUA',
                8 => 'MERTUA', 9 => 'FAMILI LAIN', 10 => 'PEMBANTU',
                11 => 'LAINNYA'
            ];
            return $map[$value] ?? 'LAINNYA';
        }
        return strtoupper($value ?? 'LAINNYA');
    }

    private function mapGolDarah($value): ?string
    {
        if (is_numeric($value)) {
            $map = [
                1 => 'A', 2 => 'B', 3 => 'AB', 4 => 'O',
                5 => 'A+', 6 => 'A-', 7 => 'B+', 8 => 'B-',
                9 => 'AB+', 10 => 'AB-', 11 => 'O+', 12 => 'O-',
                13 => 'Tidak Tahu'
            ];
            return $map[$value] ?? null;
        }
        return $value;
    }

    private function mapWarganegara($value): string
    {
        if (is_numeric($value)) {
            return $value == 1 ? 'WNI' : 'WNA';
        }
        $upper = strtoupper($value ?? '');
        return str_contains($upper, 'WNA') ? 'WNA' : 'WNI';
    }

    private function parseDate($value): ?string
    {
        if (empty($value) || $value === '-') {
            return null;
        }

        try {
            // Try DD-MM-YYYY format
            if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $value, $matches)) {
                return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
            }
            
            // Try other formats
            $date = \Carbon\Carbon::parse($value);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
