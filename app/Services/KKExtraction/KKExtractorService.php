<?php

namespace App\Services\KKExtraction;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Simple KK Extractor Service - Following pipeline_no_gemini.py exactly
 */
class KKExtractorService
{
    protected Parser $parser;
    
    // Master mappings sesuai pipeline Python - LENGKAP
    protected array $masterMappings = [
        'sex' => [
            'LAKI-LAKI' => 1,
            'PEREMPUAN' => 2
        ],
        'agama_id' => [
            'ISLAM' => 1,
            'KRISTEN' => 2,
            'KATHOLIK' => 3,
            'HINDU' => 4,
            'BUDHA' => 5,
            'KHONGHUCU' => 6,
            'KEPERCAYAAN TERHADAP TUHAN YME / LAINNYA' => 7
        ],
        'pendidikan_kk_id' => [
            'TIDAK / BELUM SEKOLAH' => 1,
            'BELUM TAMAT SD/SEDERAJAT' => 2,
            'TAMAT SD / SEDERAJAT' => 3,
            'SLTP/SEDERAJAT' => 4,
            'SLTA / SEDERAJAT' => 5,
            'DIPLOMA I / II' => 6,
            'AKADEMI/ DIPLOMA III/S. MUDA' => 7,
            'DIPLOMA IV/ STRATA I' => 8,
            'STRATA II' => 9,
            'STRATA III' => 10
        ],
        'pendidikan_sedang_id' => [
            'BELUM MASUK TK/KELOMPOK BERMAIN' => 1,
            'SEDANG TK/KELOMPOK BERMAIN' => 2,
            'TIDAK PERNAH SEKOLAH' => 3,
            'SEDANG SD/SEDERAJAT' => 4,
            'TIDAK TAMAT SD/SEDERAJAT' => 5,
            'SEDANG SLTP/SEDERAJAT' => 6,
            'SEDANG SLTA/SEDERAJAT' => 7,
            'SEDANG  D-1/SEDERAJAT' => 8,
            'SEDANG D-2/SEDERAJAT' => 9,
            'SEDANG D-3/SEDERAJAT' => 10,
            'SEDANG  S-1/SEDERAJAT' => 11,
            'SEDANG S-2/SEDERAJAT' => 12,
            'SEDANG S-3/SEDERAJAT' => 13,
            'SEDANG SLB A/SEDERAJAT' => 14,
            'SEDANG SLB B/SEDERAJAT' => 15,
            'SEDANG SLB C/SEDERAJAT' => 16,
            'TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB' => 17,
            'TIDAK SEDANG SEKOLAH' => 18
        ],
        'pekerjaan_id' => [
            'BELUM/TIDAK BEKERJA' => 1,
            'MENGURUS RUMAH TANGGA' => 2,
            'PELAJAR/MAHASISWA' => 3,
            'PENSIUNAN' => 4,
            'PEGAWAI NEGERI SIPIL (PNS)' => 5,
            'TENTARA NASIONAL INDONESIA (TNI)' => 6,
            'KEPOLISIAN RI (POLRI)' => 7,
            'PERDAGANGAN' => 8,
            'PETANI/PEKEBUN' => 9,
            'PETERNAK' => 10,
            'NELAYAN/PERIKANAN' => 11,
            'INDUSTRI' => 12,
            'KONSTRUKSI' => 13,
            'TRANSPORTASI' => 14,
            'KARYAWAN SWASTA' => 15,
            'KARYAWAN BUMN' => 16,
            'KARYAWAN BUMD' => 17,
            'KARYAWAN HONORER' => 18,
            'BURUH HARIAN LEPAS' => 19,
            'BURUH TANI/PERKEBUNAN' => 20,
            'BURUH NELAYAN/PERIKANAN' => 21,
            'BURUH PETERNAKAN' => 22,
            'PEMBANTU RUMAH TANGGA' => 23,
            'TUKANG CUKUR' => 24,
            'TUKANG LISTRIK' => 25,
            'TUKANG BATU' => 26,
            'TUKANG KAYU' => 27,
            'TUKANG SOL SEPATU' => 28,
            'TUKANG LAS/PANDAI BESI' => 29,
            'TUKANG JAHIT' => 30,
            'TUKANG GIGI' => 31,
            'PENATA RIAS' => 32,
            'PENATA BUSANA' => 33,
            'PENATA RAMBUT' => 34,
            'MEKANIK' => 35,
            'SENIMAN' => 36,
            'TABIB' => 37,
            'PARAJI' => 38,
            'PERANCANG BUSANA' => 39,
            'PENTERJEMAH' => 40,
            'IMAM MASJID' => 41,
            'PENDETA' => 42,
            'PASTOR' => 43,
            'WARTAWAN' => 44,
            'USTADZ/MUBALIGH' => 45,
            'JURU MASAK' => 46,
            'PROMOTOR ACARA' => 47,
            'ANGGOTA DPR-RI' => 48,
            'ANGGOTA DPD' => 49,
            'ANGGOTA BPK' => 50,
            'PRESIDEN' => 51,
            'WAKIL PRESIDEN' => 52,
            'ANGGOTA MAHKAMAH KONSTITUSI' => 53,
            'ANGGOTA KABINET KEMENTERIAN' => 54,
            'DUTA BESAR' => 55,
            'GUBERNUR' => 56,
            'WAKIL GUBERNUR' => 57,
            'BUPATI' => 58,
            'WAKIL BUPATI' => 59,
            'WALIKOTA' => 60,
            'WAKIL WALIKOTA' => 61,
            'ANGGOTA DPRD PROVINSI' => 62,
            'ANGGOTA DPRD KABUPATEN/KOTA' => 63,
            'DOSEN' => 64,
            'GURU' => 65,
            'PILOT' => 66,
            'PENGACARA' => 67,
            'NOTARIS' => 68,
            'ARSITEK' => 69,
            'AKUNTAN' => 70,
            'KONSULTAN' => 71,
            'DOKTER' => 72,
            'BIDAN' => 73,
            'PERAWAT' => 74,
            'APOTEKER' => 75,
            'PSIKIATER/PSIKOLOG' => 76,
            'PENYIAR TELEVISI' => 77,
            'PENYIAR RADIO' => 78,
            'PELAUT' => 79,
            'PENELITI' => 80,
            'SOPIR' => 81,
            'PIALANG' => 82,
            'PARANORMAL' => 83,
            'PEDAGANG' => 84,
            'PERANGKAT DESA' => 85,
            'KEPALA DESA' => 86,
            'BIARAWATI' => 87,
            'WIRASWASTA' => 88,
            'LAINNYA' => 89
        ],
        'status_kawin' => [
            'BELUM KAWIN' => 1,
            'KAWIN TERCATAT' => 2,
            'KAWIN TIDAK TERCATAT' => 3,
            'CERAI HIDUP TERCATAT' => 4,
            'CERAI HIDUP TIDAK TERCATAT' => 5,
            'CERAI MATI' => 6
        ],
        'kk_level' => [
            'KEPALA KELUARGA' => 1,
            'SUAMI' => 2,
            'ISTRI' => 3,
            'ANAK' => 4,
            'MENANTU' => 5,
            'CUCU' => 6,
            'ORANGTUA' => 7,
            'MERTUA' => 8,
            'FAMILI LAIN' => 9,
            'PEMBANTU' => 10,
            'LAINNYA' => 11
        ],
        'warganegara_id' => [
            'WNI' => 1,
            'WNA' => 2,
            'DUA KEWARGANEGARAAN' => 3
        ],
        'golongan_darah_id' => [
            'A' => 1, 'B' => 2, 'AB' => 3, 'O' => 4,
            'A+' => 5, 'A-' => 6, 'B+' => 7, 'B-' => 8,
            'AB+' => 9, 'AB-' => 10, 'O+' => 11, 'O-' => 12,
            'TIDAK TAHU' => 13
        ],
        'cacat_id' => [
            'CACAT FISIK' => 1,
            'CACAT NETRA/BUTA' => 2,
            'CACAT RUNGU/WICARA' => 3,
            'CACAT MENTAL/JIWA' => 4,
            'CACAT FISIK DAN MENTAL' => 5,
            'CACAT LAINNYA' => 6,
            'TIDAK CACAT' => 7
        ],
        'cara_kb_id' => [
            'PIL' => 1,
            'IUD' => 2,
            'SUNTIK' => 3,
            'KONDOM' => 4,
            'SUSUK KB' => 5,
            'STERILISASI WANITA' => 6,
            'STERILISASI PRIA' => 7,
            'LAINNYA' => 8,
            'TIDAK MENGGUNAKAN' => 1002
        ],
        'hamil' => [
            'HAMIL' => 1,
            'TIDAK HAMIL' => 2
        ],
        'ktp_el' => [
            'BELUM' => 1,
            'KTP-EL' => 2
        ],
        'status_rekam' => [
            'BELUM WAJIB' => 1,
            'BELUM REKAM' => 2,
            'SUDAH REKAM' => 3,
            'CARD PRINTED' => 4,
            'PRINT READY RECORD' => 5,
            'CARD SHIPPED' => 6,
            'SENT FOR CARD PRINTING' => 7,
            'CARD ISSUED' => 8
        ],
        'status_dasar' => [
            'HIDUP' => 1,
            'MATI' => 2,
            'PINDAH' => 3,
            'HILANG' => 4,
            'PERGI' => 6,
            'TIDAK VALID' => 9
        ],
        'id_asuransi' => [
            'TIDAK/BELUM PUNYA' => 1,
            'BPJS PENERIMA BANTUAN IURAN' => 2,
            'BPJS NON PENERIMA BANTUAN IURAN' => 3,
            'BPJS BANTUAN DAERAH' => 4,
            'ASURANSI LAINNYA' => 99
        ]
    ];
    
    // Education keywords untuk split education/occupation
    protected array $eduKeywords = [
        "TIDAK", "BELUM", "TAMAT", "SD/SEDERAJAT", "SLTP/SEDERAJAT", 
        "SLTA/SEDERAJAT", "DIPLOMA", "AKADEMI", "STRATA", "SEDERAJAT"
    ];
    
    // Relationship keywords
    protected array $relationKeywords = [
        "KEPALA KELUARGA", "SUAMI", "ISTRI", "ANAK", "MENANTU", 
        "CUCU", "ORANGTUA", "MERTUA", "FAMILI LAIN", "PEMBANTU", "LAINNYA"
    ];

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * Extract using Manual Parser (Pure PDF)
     * Returns array with 'data' and 'summary' keys
     */
    public function extractManual(array $uploadedFiles, bool $useMapping = false): array
    {
        $allMembers = [];
        $successfulFiles = 0;
        
        foreach ($uploadedFiles as $file) {
            try {
                $filePath = $file->getRealPath();
                $members = $this->extractKKPurePdf($filePath);
                $allMembers = array_merge($allMembers, $members);
                $successfulFiles++;
            } catch (\Exception $e) {
                Log::error("Error extracting {$file->getClientOriginalName()}: " . $e->getMessage());
                continue;
            }
        }
        
        // Apply normalization BEFORE returning (so preview shows mapped values)
        $normalized = $this->normalizeData($allMembers, $useMapping);
        
        // Calculate summary statistics
        $summary = $this->calculateSummary($normalized, count($uploadedFiles), $successfulFiles, $useMapping);
        
        return [
            'data' => $normalized,
            'summary' => $summary
        ];
    }

    /**
     * Extract using Gemini AI
     * Returns array with 'data' and 'summary' keys
     */
    public function extractGemini(array $uploadedFiles, string $apiKey, bool $useMapping = false): array
    {
        $allMembers = [];
        $successfulFiles = 0;
        
        foreach ($uploadedFiles as $file) {
            try {
                $members = $this->extractWithGemini($file->getRealPath(), $apiKey);
                $allMembers = array_merge($allMembers, $members);
                $successfulFiles++;
            } catch (\Exception $e) {
                Log::error("Error extracting with Gemini {$file->getClientOriginalName()}: " . $e->getMessage());
                continue;
            }
        }
        
        // Apply normalization BEFORE returning (so preview shows mapped values)
        $normalized = $this->normalizeData($allMembers, $useMapping);
        
        // Calculate summary statistics
        $summary = $this->calculateSummary($normalized, count($uploadedFiles), $successfulFiles, $useMapping);
        
        return [
            'data' => $normalized,
            'summary' => $summary
        ];
    }

    /**
     * Main extraction logic following Python pipeline exactly
     */
    protected function extractKKPurePdf(string $pdfPath): array
    {
        Log::info("=== START EXTRACTION ===");
        Log::info("PDF Path: " . $pdfPath);
        
        $pdf = $this->parser->parseFile($pdfPath);
        $text = $pdf->getText();
        
        Log::info("PDF text length: " . strlen($text));
        Log::info("First 500 chars: " . substr($text, 0, 500));
        
        // 1. HEADER EXTRACTION
        $header = $this->extractHeader($text);
        Log::info("Header extracted", $header);
        
        // 2. TABLE PARSING
        $lines = explode("\n", $text);
        Log::info("Total lines: " . count($lines));
        
        $table1Data = [];
        $table2Data = [];
        $inTable1 = false;
        $inTable2 = false;
        
        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            
            // Skip empty lines
            if (empty($line)) {
                continue;
            }
            
            // Detect table boundaries
            if (strpos($line, '(1)') !== false && strpos($line, '(9)') !== false) {
                Log::info("Found Table 1 header at line {$lineNum}: {$line}");
                $inTable1 = true;
                $inTable2 = false;
                continue;
            }
            
            if (strpos($line, '(10)') !== false && strpos($line, '(17)') !== false) {
                Log::info("Found Table 2 header at line {$lineNum}: {$line}");
                $inTable1 = false;
                $inTable2 = true;
                continue;
            }
            
            // Skip if not in any table
            if (!$inTable1 && !$inTable2) {
                continue;
            }
            
            $parts = preg_split('/\s+/', $line);
            
            // Check if first part starts with a number (could be like "1SARLAN" instead of "1 SARLAN")
            if (empty($parts)) {
                continue;
            }
            
            $firstPart = $parts[0];
            
            // Extract row number from first part (e.g., "1SARLAN" -> "1", "SARLAN")
            if (preg_match('/^(\d+)(.*)$/', $firstPart, $matches)) {
                $rowIdx = $matches[1];
                $remainder = $matches[2];
                
                // If there's remainder, insert it back as second element
                if (!empty($remainder)) {
                    array_shift($parts); // Remove first element
                    array_unshift($parts, $rowIdx, $remainder); // Add separated parts
                }
            } else {
                continue; // Not a data row
            }
            
            Log::info("Row {$rowIdx} parts: " . implode(' | ', array_slice($parts, 0, 10)));
            
            if ($inTable1) {
                Log::info("Processing Table1 row {$rowIdx}: " . implode(' | ', $parts));
                $biodata = $this->parseTable1Row($parts);
                if ($biodata) {
                    $table1Data[$rowIdx] = $biodata;
                    Log::info("✓ Table1 row {$rowIdx} success: " . ($biodata['nama'] ?? 'no name'));
                } else {
                    Log::warning("✗ Table1 row {$rowIdx} failed");
                }
            } elseif ($inTable2) {
                Log::info("Processing Table2 row {$rowIdx}");
                $statusData = $this->parseTable2Row($parts);
                if ($statusData) {
                    $table2Data[$rowIdx] = $statusData;
                    Log::info("✓ Table2 row {$rowIdx} success");
                } else {
                    Log::warning("✗ Table2 row {$rowIdx} failed");
                }
            }
        }
        
        Log::info("Table1 rows found: " . count($table1Data));
        Log::info("Table2 rows found: " . count($table2Data));
        
        // 3. MERGE DATA
        $mergedPeople = [];
        foreach ($table1Data as $idx => $biodata) {
            $statusData = $table2Data[$idx] ?? [];
            $mergedPeople[] = array_merge($header, $biodata, $statusData);
        }
        
        Log::info("Merged people count: " . count($mergedPeople));
        
        // 4. AUTO-GENERATE PARENT NIK (using shared method)
        $mergedPeople = $this->autoDetectParentNIK($mergedPeople);
        
        Log::info("=== END EXTRACTION === Total: " . count($mergedPeople));
        
        return $mergedPeople;
    }

    /**
     * Auto-detect parent NIK by matching parent names with family members
     * Works for both manual parser and Gemini AI outputs
     */
    protected function autoDetectParentNIK(array $people): array
    {
        // Build name → NIK mapping
        $nameToNik = [];
        foreach ($people as $person) {
            if (isset($person['nama']) && isset($person['nik'])) {
                $cleanName = strtoupper(trim($person['nama']));
                $nameToNik[$cleanName] = $person['nik'];
            }
        }
        
        Log::info("Parent NIK detection: Found " . count($nameToNik) . " unique names");
        
        // Match parent names to NIKs
        foreach ($people as &$person) {
            // Father NIK detection
            $fatherName = strtoupper(trim($person['nama_ayah'] ?? ''));
            if (!empty($fatherName) && $fatherName !== '-') {
                if (isset($nameToNik[$fatherName])) {
                    $person['ayah_nik'] = $nameToNik[$fatherName];
                    Log::debug("✓ Matched father: {$fatherName} → {$nameToNik[$fatherName]}");
                } else {
                    $person['ayah_nik'] = $person['ayah_nik'] ?? '-';
                }
            } else {
                $person['ayah_nik'] = '-';
            }
            
            // Mother NIK detection
            $motherName = strtoupper(trim($person['nama_ibu'] ?? ''));
            if (!empty($motherName) && $motherName !== '-') {
                if (isset($nameToNik[$motherName])) {
                    $person['ibu_nik'] = $nameToNik[$motherName];
                    Log::debug("✓ Matched mother: {$motherName} → {$nameToNik[$motherName]}");
                } else {
                    $person['ibu_nik'] = $person['ibu_nik'] ?? '-';
                }
            } else {
                $person['ibu_nik'] = '-';
            }
        }
        
        return $people;
    }

    /**
     * Extract header (alamat, dusun, RT, RW, no_kk)
     */
    protected function extractHeader(string $text): array
    {
        $getHeaderVal = function($label) use ($text) {
            $escapedLabel = preg_quote($label, '/');
            $pattern = "/{$escapedLabel}\s*[:：]\s*(.*?)(?=Nama|Alamat|RT|Kode|Desa|Kecamatan|Kabupaten|Provinsi|\n|$)/s";
            if (preg_match($pattern, $text, $match)) {
                return trim($match[1]);
            }
            return '-';
        };
        
        $alamat = $getHeaderVal('Desa/Kelurahan');
        $dusun = $getHeaderVal('Alamat');
        $rtRwStr = $getHeaderVal('RT/RW');
        
        $rt = '-';
        $rw = '-';
        if (strpos($rtRwStr, '/') !== false) {
            $parts = array_map('trim', explode('/', $rtRwStr));
            if (count($parts) >= 2) {
                $rt = $parts[0];
                $rw = $parts[1];
            }
        }
        
        $noKk = '-';
        if (preg_match('/No\.\s*(\d{16})/', $text, $match)) {
            $noKk = $match[1];
        }
        
        return [
            'alamat' => $alamat,
            'dusun' => $dusun,
            'rt' => $rt,
            'rw' => $rw,
            'no_kk' => $noKk,
        ];
    }

    /**
     * Parse Table 1 Row (Biodata)
     */
    protected function parseTable1Row(array $parts): ?array
    {
        // Find NIK (16 digits) and date (DD-MM-YYYY)
        $nikCandidates = array_filter($parts, fn($p) => strlen($p) === 16 && ctype_digit($p));
        $dateCandidates = array_filter($parts, fn($p) => preg_match('/^\d{2}-\d{2}-\d{4}$/', $p));
        
        Log::info("parseTable1Row - NIK candidates: " . count($nikCandidates) . ", Date candidates: " . count($dateCandidates));
        
        if (empty($nikCandidates) || empty($dateCandidates)) {
            Log::warning("parseTable1Row FAILED: Missing NIK or date");
            return null;
        }
        
        $nik = reset($nikCandidates);
        $tglLahir = reset($dateCandidates);
        
        $nikPos = array_search($nik, $parts);
        $tglPos = array_search($tglLahir, $parts);
        
        // Extract nama (between row number and NIK)
        $nama = implode(' ', array_slice($parts, 1, $nikPos - 1));
        
        // Sex is right after NIK
        $sex = $parts[$nikPos + 1] ?? '-';
        
        // Tempat lahir (between sex and date)
        $tempatLahir = implode(' ', array_slice($parts, $nikPos + 2, $tglPos - $nikPos - 2));
        
        // Golongan darah check (last or last 2 words)
        $golDarah = '-';
        $endIdx = count($parts);
        if (count($parts) >= 2 && strtoupper(implode(' ', array_slice($parts, -2))) === 'TIDAK TAHU') {
            $golDarah = 'TIDAK TAHU';
            $endIdx = count($parts) - 2;
        } else {
            $golDarah = $parts[count($parts) - 1];
            $endIdx = count($parts) - 1;
        }
        
        // Middle section contains agama, pendidikan, pekerjaan
        $midParts = array_slice($parts, $tglPos + 1, $endIdx - $tglPos - 1);
        
        // Agama is right after date
        $agama = $midParts[0] ?? '-';
        
        // Split education and occupation
        $edu = [];
        $occ = [];
        $isOcc = false;
        
        for ($i = 1; $i < count($midParts); $i++) {
            $word = $midParts[$i];
            if ($isOcc) {
                $occ[] = $word;
            } else {
                $edu[] = $word;
                // Check if this is end of education
                if (strpos($word, 'SEDERAJAT') !== false || 
                    ($i + 1 < count($midParts) && !in_array($midParts[$i + 1], $this->eduKeywords))) {
                    $isOcc = true;
                }
            }
        }
        
        return [
            'nama' => $nama,
            'nik' => $nik,
            'sex' => $sex,
            'tempatlahir' => $tempatLahir,
            'tanggallahir' => $tglLahir,
            'agama_id' => $agama,
            'pendidikan_kk_id' => implode(' ', $edu),
            'pekerjaan_id' => implode(' ', $occ),
            'golongan_darah_id' => $golDarah,
        ];
    }

    /**
     * Parse Table 2 Row (Status & Parents)
     */
    protected function parseTable2Row(array $parts): ?array
    {
        // Find citizenship (WNI/WNA)
        $citIdx = -1;
        foreach (['WNI', 'WNA'] as $kw) {
            $idx = array_search($kw, $parts);
            if ($idx !== false) {
                $citIdx = $idx;
                break;
            }
        }
        
        if ($citIdx === -1) {
            return null;
        }
        
        // Extract relationship (work backwards from citizenship)
        $relParts = [];
        $relStart = $citIdx - 1;
        for ($i = $citIdx - 1; $i > 0; $i--) {
            array_unshift($relParts, $parts[$i]);
            $testRel = implode(' ', $relParts);
            if (in_array($testRel, $this->relationKeywords)) {
                $relStart = $i;
                break;
            }
        }
        
        // Status kawin section (between row number and relationship)
        $statusChunk = array_slice($parts, 1, $relStart - 1);
        $tglPerkawinan = '-';
        $statusParts = [];
        
        foreach ($statusChunk as $p) {
            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $p)) {
                $tglPerkawinan = $p;
            } else {
                $statusParts[] = $p;
            }
        }
        
        // Clean dash artifacts from status kawin
        $statusKawin = str_replace('-', '', implode(' ', $statusParts));
        
        // Parents (after citizenship)
        $parents = array_filter(array_slice($parts, $citIdx + 1), fn($p) => $p !== '-');
        $parents = array_values($parents);
        
        if (count($parents) >= 2) {
            $mid = intdiv(count($parents), 2);
            $ayah = implode(' ', array_slice($parents, 0, $mid));
            $ibu = implode(' ', array_slice($parents, $mid));
        } else {
            $ayah = $parents[0] ?? '-';
            $ibu = '-';
        }
        
        return [
            'status_kawin' => $statusKawin,
            'tanggalperkawinan' => $tglPerkawinan,
            'kk_level' => implode(' ', $relParts),
            'warganegara_id' => $parts[$citIdx],
            'nama_ayah' => $ayah,
            'nama_ibu' => $ibu,
        ];
    }

    /**
     * Build comprehensive Gemini prompt with all 41 TARGET_COLUMNS
     * Include format specifications and categorical value hints
     */
    protected function buildGeminiPrompt(): string
    {
        return <<<'PROMPT'
You are a professional data extraction AI specialized in Indonesian Family Card (Kartu Keluarga/KK) documents.

TASK: Extract ALL family members from this KK PDF and return as JSON array.

CRITICAL REQUIREMENTS:
1. Extract EVERY person listed in the KK (typically 2-7 people per KK)
2. Use EXACT field names specified below
3. Follow format rules strictly
4. Return ONLY valid JSON array, no explanations

OUTPUT FORMAT:
[
  {
    // Header fields (same for all members in this KK)
    "alamat": "string (Desa/Kelurahan name)",
    "dusun": "string (Alamat/Dusun)",
    "rw": "string (RW number, e.g., '001')",
    "rt": "string (RT number, e.g., '002')",
    "no_kk": "string (16-digit family card number)",
    
    // Person biodata (unique per person)
    "nama": "string (Full name in UPPERCASE)",
    "nik": "string (16-digit NIK, numbers only)",
    "sex": "LAKI-LAKI or PEREMPUAN (exact match)",
    "tempatlahir": "string (Birth place in UPPERCASE)",
    "tanggallahir": "DD-MM-YYYY (e.g., 23-01-1995)",
    
    // Categorical fields (use EXACT values from KK)
    "agama_id": "ISLAM|KRISTEN|KATHOLIK|HINDU|BUDHA|KHONGHUCU|KEPERCAYAAN TERHADAP TUHAN YME / LAINNYA",
    "pendidikan_kk_id": "TIDAK / BELUM SEKOLAH|BELUM TAMAT SD/SEDERAJAT|TAMAT SD / SEDERAJAT|SLTP/SEDERAJAT|SLTA / SEDERAJAT|DIPLOMA I / II|AKADEMI/ DIPLOMA III/S. MUDA|DIPLOMA IV/ STRATA I|STRATA II|STRATA III",
    "pendidikan_sedang_id": "TIDAK SEDANG SEKOLAH|BELUM MASUK TK/KELOMPOK BERMAIN|SEDANG TK/KELOMPOK BERMAIN|etc",
    "pekerjaan_id": "Common: PETANI/PEKEBUN, WIRASWASTA, MENGURUS RUMAH TANGGA, PELAJAR/MAHASISWA, BELUM/TIDAK BEKERJA, GURU, PEDAGANG",
    "status_kawin": "BELUM KAWIN|KAWIN TERCATAT|KAWIN TIDAK TERCATAT|CERAI HIDUP TERCATAT|CERAI HIDUP TIDAK TERCATAT|CERAI MATI",
    "kk_level": "KEPALA KELUARGA|SUAMI|ISTRI|ANAK|MENANTU|CUCU|ORANGTUA|MERTUA|FAMILI LAIN|PEMBANTU|LAINNYA",
    "warganegara_id": "WNI|WNA|DUA KEWARGANEGARAAN",
    "golongan_darah_id": "A|B|AB|O|A+|A-|B+|B-|AB+|AB-|O+|O-|TIDAK TAHU",
    "cacat_id": "TIDAK CACAT|CACAT FISIK|CACAT NETRA/BUTA|CACAT RUNGU/WICARA|CACAT MENTAL/JIWA|CACAT FISIK DAN MENTAL|CACAT LAINNYA",
    "cara_kb_id": "TIDAK MENGGUNAKAN|PIL|IUD|SUNTIK|KONDOM|SUSUK KB|STERILISASI WANITA|STERILISASI PRIA|LAINNYA",
    "hamil": "HAMIL|TIDAK HAMIL",
    "ktp_el": "BELUM|KTP-EL",
    "status_rekam": "BELUM WAJIB|BELUM REKAM|SUDAH REKAM|CARD PRINTED|PRINT READY RECORD|CARD SHIPPED|SENT FOR CARD PRINTING|CARD ISSUED",
    "status_dasar": "HIDUP|MATI|PINDAH|HILANG|PERGI|TIDAK VALID",
    "id_asuransi": "TIDAK/BELUM PUNYA|BPJS PENERIMA BANTUAN IURAN|BPJS NON PENERIMA BANTUAN IURAN|BPJS BANTUAN DAERAH|ASURANSI LAINNYA",
    
    // Parent information (SMART DETECTION)
    "ayah_nik": "string (16-digit NIK of father. If father name matches any person in this KK, use their NIK. Otherwise '-')",
    "nama_ayah": "string (Father's full name in UPPERCASE)",
    "ibu_nik": "string (16-digit NIK of mother. If mother name matches any person in this KK, use their NIK. Otherwise '-')",
    "nama_ibu": "string (Mother's full name in UPPERCASE)",
    
    // Document fields (use '-' if empty)
    "akta_lahir": "string or '-'",
    "dokumen_pasport": "string or '-'",
    "tanggal_akhir_paspor": "DD-MM-YYYY or '-'",
    "dokumen_kitas": "string or '-'",
    "akta_perkawinan": "string or '-'",
    "tanggalperkawinan": "DD-MM-YYYY or '-'",
    "akta_perceraian": "string or '-'",
    "tanggalperceraian": "DD-MM-YYYY or '-'",
    "alamat_sekarang": "string or '-'",
    "suku": "string or '-'",
    "tag_id_card": "string or '-'",
    "no_asuransi": "string or '-'"
  }
]

SPECIAL RULES:
1. NIK must be exactly 16 digits (no spaces, no dashes)
2. Dates must be DD-MM-YYYY format (e.g., 05-03-1990)
3. All categorical text must be UPPERCASE and match exact values from KK
4. For parent NIK: Compare parent names with all person names in this KK, if match use their NIK
5. Use '-' (single dash) for empty fields, never null or blank
6. Extract from BOTH Table 1 (biodata) and Table 2 (status/parents)

Now extract ALL family members from the provided KK PDF. Return ONLY the JSON array, no markdown formatting.
PROMPT;
    }

    /**
     * Map Gemini field names to standard TARGET_COLUMNS format
     * Handle variations in field naming from AI responses
     */
    protected function normalizeGeminiFields(array $person): array
    {
        $fieldMapper = [
            // Common Gemini variations → Standard format
            'jenis_kelamin' => 'sex',
            'kelamin' => 'sex',
            'gender' => 'sex',
            'tempat_lahir' => 'tempatlahir',
            'tanggal_lahir' => 'tanggallahir',
            'tgl_lahir' => 'tanggallahir',
            'agama' => 'agama_id',
            'pendidikan' => 'pendidikan_kk_id',
            'pekerjaan' => 'pekerjaan_id',
            'kewarganegaraan' => 'warganegara_id',
            'status_dalam_keluarga' => 'kk_level',
            'hubungan_keluarga' => 'kk_level',
            'golongan_darah' => 'golongan_darah_id',
            'gol_darah' => 'golongan_darah_id',
            'tanggal_perkawinan' => 'tanggalperkawinan',
            'tgl_perkawinan' => 'tanggalperkawinan',
            'tanggal_perceraian' => 'tanggalperceraian',
            'tgl_perceraian' => 'tanggalperceraian',
            'asuransi' => 'id_asuransi',
        ];
        
        $normalized = [];
        
        foreach ($person as $key => $value) {
            // Map field name if needed
            $standardKey = $fieldMapper[$key] ?? $key;
            $normalized[$standardKey] = $value;
        }
        
        return $normalized;
    }

    /**
     * Extract using Gemini AI - Output same format as Manual Parser
     */
    protected function extractWithGemini(string $pdfPath, string $apiKey): array
    {
        Log::info("=== GEMINI EXTRACTION START ===");
        Log::info("PDF Path: {$pdfPath}");
        
        // Check if PDF exists and readable
        if (!file_exists($pdfPath)) {
            throw new \Exception("PDF file not found: {$pdfPath}");
        }
        
        $fileSize = filesize($pdfPath);
        Log::info("PDF Size: " . round($fileSize / 1024, 2) . " KB");
        
        // Detect if PDF is text-based or image-based (scanned)
        $isScanned = $this->isScannedPDF($pdfPath);
        Log::info("PDF Type: " . ($isScanned ? "SCANNED (Image-based)" : "Text-based"));
        
        // Convert PDF to base64
        $pdfContent = file_get_contents($pdfPath);
        $base64Pdf = base64_encode($pdfContent);
        Log::info("Base64 encoded, length: " . strlen($base64Pdf));
        
        // Comprehensive prompt with all 41 TARGET_COLUMNS
        $prompt = $this->buildGeminiPrompt();
        Log::info("Prompt length: " . strlen($prompt) . " chars");
        
        // Build request with enhanced config for better OCR
        $requestBody = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => 'application/pdf',
                                'data' => $base64Pdf
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 8192
            ]
        ];
        
        Log::info("Sending request to Gemini API...");
        $response = Http::timeout(180)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", $requestBody);
        
        Log::info("Gemini API call completed");
        Log::info("Response status: " . $response->status());
        
        if (!$response->successful()) {
            Log::error("Gemini API error: " . $response->body());
            throw new \Exception("Gemini API error (" . $response->status() . "): " . $response->body());
        }
        
        $result = $response->json();
        
        // Debug: Log full response structure
        Log::info("Gemini response keys: " . json_encode(array_keys($result)));
        
        // Check for blocked content or errors
        if (isset($result['promptFeedback'])) {
            Log::warning("Gemini prompt feedback: " . json_encode($result['promptFeedback']));
        }
        
        if (!isset($result['candidates']) || empty($result['candidates'])) {
            Log::error("No candidates in Gemini response. Full response: " . json_encode($result));
            throw new \Exception("Gemini returned no candidates. Possible reasons: PDF too large, content blocked, or API issue.");
        }
        
        $candidate = $result['candidates'][0];
        Log::info("Candidate finish reason: " . ($candidate['finishReason'] ?? 'unknown'));
        
        if (!isset($candidate['content']['parts'][0]['text'])) {
            Log::error("No text in Gemini response. Candidate structure: " . json_encode($candidate));
            throw new \Exception("Gemini response has no text content. This may happen with scanned PDFs that are unclear or too complex.");
        }
        
        $text = $candidate['content']['parts'][0]['text'];
        Log::info("Gemini raw response length: " . strlen($text));
        Log::info("Response preview (first 500 chars): " . substr($text, 0, 500));
        
        // Try multiple parsing strategies
        $data = $this->parseGeminiResponse($text);
        
        if (empty($data)) {
            Log::error("Failed to parse response. Full text: " . $text);
            throw new \Exception("Gemini response could not be parsed into valid JSON. Response: " . substr($text, 0, 500));
        }
        
        // Normalize field names for each person
        $normalizedData = array_map(function($person) {
            return $this->normalizeGeminiFields($person);
        }, $data);
        
        // Apply smart parent NIK detection (same as manual parser)
        $normalizedData = $this->autoDetectParentNIK($normalizedData);
        
        Log::info("Gemini extracted " . count($normalizedData) . " persons with parent NIK detection");
        
        return $normalizedData;
    }

    /**
     * Detect if PDF is scanned (image-based) or text-based
     * Scanned PDFs need different handling in Gemini
     */
    protected function isScannedPDF(string $pdfPath): bool
    {
        try {
            $pdf = $this->parser->parseFile($pdfPath);
            $text = $pdf->getText();
            
            // Clean and count actual text
            $cleanText = trim(preg_replace('/\s+/', ' ', $text));
            $textLength = strlen($cleanText);
            
            Log::info("PDF text extraction: {$textLength} chars extracted");
            
            // Heuristic: If very little text extracted, likely scanned
            // Typical text-based KK has 500+ characters
            if ($textLength < 100) {
                Log::info("PDF appears to be scanned (too little text)");
                return true;
            }
            
            // Check text quality: high ratio of gibberish = scanned
            $alphanumericRatio = preg_match_all('/[a-zA-Z0-9]/', $cleanText) / max($textLength, 1);
            Log::info("Alphanumeric ratio: " . round($alphanumericRatio * 100, 2) . "%");
            
            if ($alphanumericRatio < 0.3) {
                Log::info("PDF appears to be scanned (low alphanumeric ratio)");
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::warning("Error detecting PDF type: " . $e->getMessage());
            // If we can't extract text, assume scanned
            return true;
        }
    }

    /**
     * Parse Gemini response with multiple strategies
     * Handles JSON, markdown-wrapped JSON, and plain text variations
     */
    protected function parseGeminiResponse(string $text): array
    {
        // Strategy 1: Direct JSON array extraction
        if (preg_match('/\[.*\]/s', $text, $matches)) {
            $jsonStr = $matches[0];
            $data = json_decode($jsonStr, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                Log::info("Gemini response parsed via Strategy 1 (direct JSON)");
                return $data;
            }
        }
        
        // Strategy 2: Extract from markdown code block
        if (preg_match('/```(?:json)?\s*(\[.*?\])\s*```/s', $text, $matches)) {
            $jsonStr = $matches[1];
            $data = json_decode($jsonStr, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                Log::info("Gemini response parsed via Strategy 2 (markdown block)");
                return $data;
            }
        }
        
        // Strategy 3: Try to extract any JSON-like structure
        $lines = explode("\n", $text);
        $jsonLines = [];
        $inArray = false;
        
        foreach ($lines as $line) {
            if (strpos($line, '[') !== false) {
                $inArray = true;
            }
            if ($inArray) {
                $jsonLines[] = $line;
            }
            if (strpos($line, ']') !== false && $inArray) {
                break;
            }
        }
        
        if (!empty($jsonLines)) {
            $jsonStr = implode("\n", $jsonLines);
            $data = json_decode($jsonStr, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                Log::info("Gemini response parsed via Strategy 3 (line extraction)");
                return $data;
            }
        }
        
        Log::error("All Gemini parsing strategies failed. Response sample: " . substr($text, 0, 500));
        return [];
    }

    /**
     * Find closest match using fuzzy string matching
     * Returns best match if similarity >= threshold, null otherwise
     */
    protected function findClosestMatch(string $value, array $validOptions, int $threshold = 85): ?array
    {
        $bestMatch = null;
        $bestSimilarity = 0;
        
        foreach ($validOptions as $option) {
            similar_text(strtoupper($value), strtoupper($option), $similarity);
            
            if ($similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestMatch = $option;
            }
        }
        
        if ($bestSimilarity >= $threshold) {
            return ['match' => $bestMatch, 'similarity' => round($bestSimilarity, 1)];
        }
        
        return null;
    }

    /**
     * Validate NIK (Nomor Induk Kependudukan) Indonesia
     * Format: PPKKSSDDMMYYXXXX
     * PP = Provinsi (2 digit), KK = Kabupaten (2 digit), SS = Kecamatan (2 digit)
     * DDMMYY = Tanggal lahir (untuk perempuan, DD + 40), XXXX = Nomor urut
     */
    protected function validateNIK(string $nik): array
    {
        $result = ['valid' => false, 'errors' => []];
        
        // Check length
        if (strlen($nik) !== 16) {
            $result['errors'][] = 'Panjang NIK harus 16 digit';
            return $result;
        }
        
        // Check numeric
        if (!ctype_digit($nik)) {
            $result['errors'][] = 'NIK harus berisi angka saja';
            return $result;
        }
        
        // Extract date parts (positions 6-11: DDMMYY)
        $dd = (int) substr($nik, 6, 2);
        $mm = (int) substr($nik, 8, 2);
        $yy = (int) substr($nik, 10, 2);
        
        // Adjust for female (day + 40)
        $isFemale = $dd > 40;
        if ($isFemale) {
            $dd -= 40;
        }
        
        // Validate date
        if ($dd < 1 || $dd > 31) {
            $result['errors'][] = 'Tanggal lahir tidak valid (DD: ' . $dd . ')';
        }
        if ($mm < 1 || $mm > 12) {
            $result['errors'][] = 'Bulan lahir tidak valid (MM: ' . $mm . ')';
        }
        
        // Full year estimation (assume 1900s for YY >= 50, 2000s for YY < 50)
        $yyyy = ($yy >= 50) ? (1900 + $yy) : (2000 + $yy);
        
        // Check if date is valid
        if (!checkdate($mm, $dd, $yyyy)) {
            $result['errors'][] = 'Tanggal tidak valid dalam kalendar';
        }
        
        $result['valid'] = empty($result['errors']);
        $result['birthdate'] = sprintf('%02d-%02d-%04d', $dd, $mm, $yyyy);
        $result['gender'] = $isFemale ? 'PEREMPUAN' : 'LAKI-LAKI';
        
        return $result;
    }

    /**
     * Normalize data - clean strings and apply mapping if needed
     * Sesuai dengan save_to_excel() di pipeline Python
     * PLUS: Track unmapped fields untuk flagging
     * PLUS: Fuzzy matching untuk typo tolerance
     */
    protected function normalizeData(array $data, bool $useMapping): array
    {
        Log::info("normalizeData called with useMapping: " . ($useMapping ? 'TRUE' : 'FALSE'));
        
        $normalized = [];
        
        foreach ($data as $person) {
            $row = [];
            $unmappedFields = []; // Track fields yang gagal di-map
            
            // Process each field
            foreach ($person as $key => $value) {
                // 1. Clean string (remove trailing dash, normalize spaces)
                if (is_string($value)) {
                    $tempVal = trim($value);
                    
                    // Single dash or empty → keep as dash
                    if ($tempVal === '-' || $tempVal === '') {
                        $cleanVal = '-';
                    } else {
                        // Remove trailing dash, normalize spaces, uppercase
                        $cleanVal = trim(preg_replace('/\s+/', ' ', rtrim($tempVal, '-')));
                        $cleanVal = strtoupper($cleanVal);
                    }
                    
                    // 2. Normalize slash spacing (SD / SEDERAJAT → SD/SEDERAJAT)
                    $cleanVal = str_replace(' / ', '/', $cleanVal);
                } else {
                    $cleanVal = $value;
                }
                
                // 3. Apply mapping if enabled (with fuzzy matching)
                if ($useMapping && isset($this->masterMappings[$key])) {
                    $mapping = $this->masterMappings[$key];
                    
                    // Normalize mapping keys (convert to uppercase, fix slashes)
                    $normalizedMap = [];
                    foreach ($mapping as $k => $v) {
                        $normalizedKey = strtoupper(str_replace(' / ', '/', $k));
                        $normalizedMap[$normalizedKey] = $v;
                    }
                    
                    // Try exact match first
                    if (isset($normalizedMap[$cleanVal]) && $cleanVal !== '-') {
                        $row[$key] = $normalizedMap[$cleanVal];
                        Log::debug("✓ Mapped {$key}: '{$cleanVal}' → {$normalizedMap[$cleanVal]}");
                    } else if ($cleanVal !== '-') {
                        // Try fuzzy matching for typo tolerance (85% similarity)
                        $fuzzyResult = $this->findClosestMatch($cleanVal, array_keys($normalizedMap), 85);
                        
                        if ($fuzzyResult) {
                            $matchedKey = $fuzzyResult['match'];
                            $similarity = $fuzzyResult['similarity'];
                            $row[$key] = $normalizedMap[$matchedKey];
                            Log::info("✓ (fuzzy {$similarity}%) {$key}: '{$cleanVal}' → '{$matchedKey}' → {$normalizedMap[$matchedKey]}");
                        } else {
                            $row[$key] = $cleanVal;
                            $unmappedFields[] = $key;
                            Log::warning("✗ No mapping for {$key}: '{$cleanVal}'");
                        }
                    } else {
                        $row[$key] = $cleanVal;
                    }
                } else {
                    $row[$key] = $cleanVal;
                }
                
                // 4. Validate NIK if this is nik field
                if ($key === 'nik' && $cleanVal !== '-') {
                    $nikValidation = $this->validateNIK($cleanVal);
                    if (!$nikValidation['valid']) {
                        $row['_nik_errors'] = implode('; ', $nikValidation['errors']);
                        Log::warning("⚠ NIK validation failed for {$cleanVal}: " . implode(', ', $nikValidation['errors']));
                    } else {
                        $row['_nik_errors'] = null;
                        Log::debug("✓ NIK valid: {$cleanVal} (Lahir: {$nikValidation['birthdate']}, {$nikValidation['gender']})");
                    }
                }
            }
            
            // Tambahkan kolom flag untuk pengecekan manual
            if ($useMapping && !empty($unmappedFields)) {
                $row['_flag_unmapped'] = true;
                $row['_unmapped_fields'] = implode(', ', $unmappedFields);
                $row['flag_cek_manual'] = 'PERLU CEK: ' . implode(', ', $unmappedFields);
            } else {
                $row['_flag_unmapped'] = false;
                $row['_unmapped_fields'] = '';
                $row['flag_cek_manual'] = 'OK';
            }
            
            $normalized[] = $row;
        }
        
        Log::info("normalizeData completed. Total rows: " . count($normalized));
        
        return $normalized;
    }

    /**
     * Calculate summary statistics for extraction results
     */
    protected function calculateSummary(array $data, int $totalFiles, int $successfulFiles, bool $useMapping): array
    {
        $totalPeople = count($data);
        $flaggedCount = 0;
        $okCount = 0;
        $flaggedFieldsCount = [];
        $nikErrorsCount = 0;
        
        foreach ($data as $person) {
            // Count flagged rows
            if ($person['_flag_unmapped'] ?? false) {
                $flaggedCount++;
                
                // Count each flagged field
                $fields = explode(', ', $person['_unmapped_fields'] ?? '');
                foreach ($fields as $field) {
                    if (!empty($field)) {
                        $flaggedFieldsCount[$field] = ($flaggedFieldsCount[$field] ?? 0) + 1;
                    }
                }
            } else {
                $okCount++;
            }
            
            // Count NIK validation errors
            if (!empty($person['_nik_errors'])) {
                $nikErrorsCount++;
            }
        }
        
        // Sort flagged fields by count (descending)
        arsort($flaggedFieldsCount);
        
        // Calculate percentages
        $okPercentage = $totalPeople > 0 ? round(($okCount / $totalPeople) * 100, 1) : 0;
        $flaggedPercentage = $totalPeople > 0 ? round(($flaggedCount / $totalPeople) * 100, 1) : 0;
        
        return [
            'total_files' => $totalFiles,
            'successful_files' => $successfulFiles,
            'failed_files' => $totalFiles - $successfulFiles,
            'total_people' => $totalPeople,
            'ok_count' => $okCount,
            'ok_percentage' => $okPercentage,
            'flagged_count' => $flaggedCount,
            'flagged_percentage' => $flaggedPercentage,
            'flagged_fields' => $flaggedFieldsCount,
            'nik_errors_count' => $nikErrorsCount,
            'mapping_enabled' => $useMapping
        ];
    }

    /**
     * Generate Excel file from extracted data
     */
    public function generateExcel(array $data, bool $useMapping = false): string
    {
        // Data sudah di-normalize saat ekstraksi, tapi bisa saja user toggle mapping setelahnya
        // Jadi kita perlu re-apply normalization dengan setting useMapping yang baru
        // TAPI: kalau data sudah berupa ID (angka), jangan normalize lagi
        
        // Check if data already contains IDs (meaning it was already normalized with mapping ON)
        $alreadyMapped = false;
        if (!empty($data)) {
            $firstRow = $data[0];
            // Check if mappable fields contain integers
            foreach (['sex', 'agama_id', 'kk_level'] as $key) {
                if (isset($firstRow[$key]) && is_int($firstRow[$key])) {
                    $alreadyMapped = true;
                    break;
                }
            }
        }
        
        // Only re-normalize if mapping state changed
        // If already mapped but useMapping=false, we can't reverse it (data integrity)
        // If not mapped and useMapping=true, we need to apply mapping
        $normalizedData = $data; // Use as-is by default
        
        if (!$alreadyMapped && $useMapping) {
            // Need to apply mapping for Excel export
            $normalizedData = $this->normalizeData($data, true);
        }
        
        // Fill missing columns dengan "-" untuk semua rows
        $targetColumns = [
            'alamat', 'dusun', 'rw', 'rt', 'nama', 'no_kk', 'nik', 'sex', 'tempatlahir',
            'tanggallahir', 'agama_id', 'pendidikan_kk_id', 'pendidikan_sedang_id',
            'pekerjaan_id', 'status_kawin', 'kk_level', 'warganegara_id', 'ayah_nik',
            'nama_ayah', 'ibu_nik', 'nama_ibu', 'golongan_darah_id', 'akta_lahir',
            'dokumen_pasport', 'tanggal_akhir_paspor', 'dokumen_kitas', 'akta_perkawinan',
            'tanggalperkawinan', 'akta_perceraian', 'tanggalperceraian', 'cacat_id',
            'cara_kb_id', 'hamil', 'ktp_el', 'status_rekam', 'alamat_sekarang',
            'status_dasar', 'suku', 'tag_id_card', 'id_asuransi', 'no_asuransi'
        ];
        
        // Ensure all rows have all target columns
        foreach ($normalizedData as &$dataRow) {
            foreach ($targetColumns as $col) {
                if (!isset($dataRow[$col])) {
                    $dataRow[$col] = '-';
                }
            }
        }
        unset($dataRow); // CRITICAL: Unset reference to avoid variable conflict
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Define columns (42 kolom: No + Flag + 41 data columns)
        $columns = [
            'No', 'Flag Cek Manual', 'alamat', 'dusun', 'rw', 'rt', 'nama', 'no_kk', 'nik', 'sex',
            'tempatlahir', 'tanggallahir', 'agama_id', 'pendidikan_kk_id', 'pendidikan_sedang_id',
            'pekerjaan_id', 'status_kawin', 'kk_level', 'warganegara_id', 'ayah_nik',
            'nama_ayah', 'ibu_nik', 'nama_ibu', 'golongan_darah_id', 'akta_lahir',
            'dokumen_pasport', 'tanggal_akhir_paspor', 'dokumen_kitas', 'akta_perkawinan',
            'tanggalperkawinan', 'akta_perceraian', 'tanggalperceraian', 'cacat_id',
            'cara_kb_id', 'hamil', 'ktp_el', 'status_rekam', 'alamat_sekarang',
            'status_dasar', 'suku', 'tag_id_card', 'id_asuransi', 'no_asuransi'
        ];
        
        // Header row
        $col = 'A';
        foreach ($columns as $column) {
            $sheet->setCellValue($col . '1', $column);
            $headerColor = ($col === 'B') ? 'FCD34D' : '4F46E5'; // Yellow for Flag column
            $sheet->getStyle($col . '1')->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $headerColor]
                ],
                'font' => ['bold' => true, 'color' => ['rgb' => ($col === 'B') ? '000000' : 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            $col++;
        }
        
        // Data rows
        $row = 2;
        foreach ($normalizedData as $index => $person) {
            // Column A: No
            $sheet->setCellValue('A' . $row, $index + 1);
            
            // Column B: Flag Cek Manual
            $flagValue = $person['flag_cek_manual'] ?? 'OK';
            $sheet->setCellValue('B' . $row, $flagValue);
            
            // Highlight row yellow if flagged
            if ($person['_flag_unmapped'] ?? false) {
                $sheet->getStyle('B' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'] // Light yellow
                    ],
                    'font' => ['bold' => true, 'color' => ['rgb' => 'D97706']]
                ]);
            } else {
                $sheet->getStyle('B' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'] // Light green
                    ],
                    'font' => ['color' => ['rgb' => '059669']]
                ]);
            }
            
            // Columns C-AQ: Data columns
            $sheet->setCellValue('C' . $row, $person['alamat'] ?? '-');
            $sheet->setCellValue('D' . $row, $person['dusun'] ?? '-');
            $sheet->setCellValue('E' . $row, $person['rw'] ?? '-');
            $sheet->setCellValue('F' . $row, $person['rt'] ?? '-');
            $sheet->setCellValue('G' . $row, $person['nama'] ?? '-');
            $sheet->setCellValue('H' . $row, $person['no_kk'] ?? '-');
            $sheet->setCellValue('I' . $row, $person['nik'] ?? '-');
            $sheet->setCellValue('J' . $row, $person['sex'] ?? '-');
            $sheet->setCellValue('K' . $row, $person['tempatlahir'] ?? '-');
            $sheet->setCellValue('L' . $row, $person['tanggallahir'] ?? '-');
            $sheet->setCellValue('M' . $row, $person['agama_id'] ?? '-');
            $sheet->setCellValue('N' . $row, $person['pendidikan_kk_id'] ?? '-');
            $sheet->setCellValue('O' . $row, $person['pendidikan_sedang_id'] ?? '-');
            $sheet->setCellValue('P' . $row, $person['pekerjaan_id'] ?? '-');
            $sheet->setCellValue('Q' . $row, $person['status_kawin'] ?? '-');
            $sheet->setCellValue('R' . $row, $person['kk_level'] ?? '-');
            $sheet->setCellValue('S' . $row, $person['warganegara_id'] ?? '-');
            $sheet->setCellValue('T' . $row, $person['ayah_nik'] ?? '-');
            $sheet->setCellValue('U' . $row, $person['nama_ayah'] ?? '-');
            $sheet->setCellValue('V' . $row, $person['ibu_nik'] ?? '-');
            $sheet->setCellValue('W' . $row, $person['nama_ibu'] ?? '-');
            $sheet->setCellValue('X' . $row, $person['golongan_darah_id'] ?? '-');
            $sheet->setCellValue('Y' . $row, $person['akta_lahir'] ?? '-');
            $sheet->setCellValue('Z' . $row, $person['dokumen_pasport'] ?? '-');
            $sheet->setCellValue('AA' . $row, $person['tanggal_akhir_paspor'] ?? '-');
            $sheet->setCellValue('AB' . $row, $person['dokumen_kitas'] ?? '-');
            $sheet->setCellValue('AC' . $row, $person['akta_perkawinan'] ?? '-');
            $sheet->setCellValue('AD' . $row, $person['tanggalperkawinan'] ?? '-');
            $sheet->setCellValue('AE' . $row, $person['akta_perceraian'] ?? '-');
            $sheet->setCellValue('AF' . $row, $person['tanggalperceraian'] ?? '-');
            $sheet->setCellValue('AG' . $row, $person['cacat_id'] ?? '-');
            $sheet->setCellValue('AH' . $row, $person['cara_kb_id'] ?? '-');
            $sheet->setCellValue('AI' . $row, $person['hamil'] ?? '-');
            $sheet->setCellValue('AJ' . $row, $person['ktp_el'] ?? '-');
            $sheet->setCellValue('AK' . $row, $person['status_rekam'] ?? '-');
            $sheet->setCellValue('AL' . $row, $person['alamat_sekarang'] ?? '-');
            $sheet->setCellValue('AM' . $row, $person['status_dasar'] ?? '-');
            $sheet->setCellValue('AN' . $row, $person['suku'] ?? '-');
            $sheet->setCellValue('AO' . $row, $person['tag_id_card'] ?? '-');
            $sheet->setCellValue('AP' . $row, $person['id_asuransi'] ?? '-');
            $sheet->setCellValue('AQ' . $row, $person['no_asuransi'] ?? '-');
            $row++;
        }
        
        // Auto-size columns (A sampai AQ = 43 kolom termasuk No + Flag)
        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Double letter columns (AA to AQ)
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension('A' . $col)->setAutoSize(true);
        }
        
        // Save to temp file
        $filename = 'Data_KK_' . date('Y-m-d_His') . '.xlsx';
        $path = storage_path('app/temp/' . $filename);
        
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        
        return $path;
    }
}
