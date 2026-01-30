<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\PythonKKExtractorService;
use Illuminate\Support\Facades\Storage;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;

/**
 * Convert Roman numerals to Arabic numbers
 */
function romanToArabic($roman) {
    $romans = [
        'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5,
        'VI' => 6, 'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10,
        'XI' => 11, 'XII' => 12, 'XIII' => 13, 'XIV' => 14
    ];
    
    $roman = strtoupper(trim($roman));
    return $romans[$roman] ?? null;
}

/**
 * Get field value case-insensitively
 */
function getField($record, $fieldName, $default = null) {
    // Try uppercase
    if (isset($record[strtoupper($fieldName)])) {
        return $record[strtoupper($fieldName)];
    }
    // Try lowercase
    if (isset($record[strtolower($fieldName)])) {
        return $record[strtolower($fieldName)];
    }
    // Try as-is
    if (isset($record[$fieldName])) {
        return $record[$fieldName];
    }
    return $default;
}

/**
 * Normalize status_perkawinan to match database enum
 */
function normalizeStatusPerkawinan($status) {
    if (!$status || $status === '-') {
        return 'BELUM KAWIN';
    }
    
    $status = strtoupper(trim($status));
    
    // Map various formats to standard enum values
    if (stripos($status, 'BELUM') !== false || stripos($status, 'TIDAK') !== false) {
        return 'BELUM KAWIN';
    }
    if (stripos($status, 'KAWIN') !== false && stripos($status, 'CERAI') === false) {
        return 'KAWIN';
    }
    if (stripos($status, 'CERAI HIDUP') !== false || stripos($status, 'CERAI') !== false && stripos($status, 'MATI') === false) {
        return 'CERAI HIDUP';
    }
    if (stripos($status, 'CERAI MATI') !== false || stripos($status, 'MATI') !== false) {
        return 'CERAI MATI';
    }
    
    return 'BELUM KAWIN'; // Default
}

/**
 * Normalize status_dalam_keluarga to match database enum
 * Maps numeric codes to text values
 */
function normalizeStatusDalamKeluarga($status) {
    if (!$status || $status === '-' || $status === '') {
        return 'LAINNYA';
    }
    
    // Map numeric codes from KK data
    $mapping = [
        '1' => 'KEPALA KELUARGA',
        '2' => 'SUAMI',
        '3' => 'ISTRI',
        '4' => 'ANAK',
        '5' => 'MENANTU',
        '6' => 'CUCU',
        '7' => 'ORANGTUA',
        '8' => 'MERTUA',
        '9' => 'FAMILI LAIN',
        '10' => 'PEMBANTU',
        '11' => 'LAINNYA',
    ];
    
    $status = trim($status);
    
    // If numeric code
    if (isset($mapping[$status])) {
        return $mapping[$status];
    }
    
    // If already text, try to match
    $status = strtoupper($status);
    $validValues = ['KEPALA KELUARGA', 'SUAMI', 'ISTRI', 'ANAK', 'MENANTU', 'CUCU', 'ORANGTUA', 'MERTUA', 'FAMILI LAIN', 'PEMBANTU', 'LAINNYA'];
    foreach ($validValues as $valid) {
        if (stripos($status, $valid) !== false || stripos($valid, $status) !== false) {
            return $valid;
        }
    }
    
    return 'LAINNYA'; // Default
}

Route::prefix('api')->group(function () {
    /**
     * Extract KK data from uploaded PDF using Python backend
     */
    Route::post('/extract-kk', function (Request $request) {
        // Validate request
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'use_mapping' => 'sometimes|boolean'
        ]);

        try {
            $pdfFile = $request->file('pdf');
            $useMapping = $request->input('use_mapping', false);

            // Store file temporarily
            $tempPath = $pdfFile->store('temp_kk_pdfs');
            $absolutePath = Storage::path($tempPath);

            // Extract data using Python service
            $service = app(PythonKKExtractorService::class);
            $data = $service->extractFromPDF($absolutePath, $useMapping);

            // Clean up temporary file
            Storage::delete($tempPath);

            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
                'file' => $pdfFile->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            // Clean up on error
            if (isset($tempPath)) {
                Storage::delete($tempPath);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    /**
     * Check Python dependencies status
     */
    Route::get('/extract-kk/check-dependencies', function () {
        $service = app(PythonKKExtractorService::class);
        $status = $service->checkDependencies();

        return response()->json([
            'success' => true,
            'dependencies' => $status
        ]);
    });

    /**
     * Save extracted KK data to database
     */
    Route::post('/extract-kk/save', function (Request $request) {
        // First validate that data exists
        if (!$request->has('data') || !is_array($request->data)) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak valid. Format data harus berupa array.'
            ], 422);
        }

        $request->validate([
            'data' => 'required|array|min:1',
            'data.*.NO_KK' => 'nullable|string',
            'data.*.NIK' => 'nullable|string',
            'data.*.NAMA' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            
            \Log::info('Save KK Data Request', [
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'has_dusun' => $user?->dusun_id ? 'yes' : 'no',
                'dusun_id' => $user?->dusun_id,
                'roles' => $user?->roles?->pluck('name')->toArray(),
                'is_superadmin' => $user?->isSuperAdmin(),
                'can_access_all' => $user?->canAccessAllDusuns(),
                'data_count' => count($request->data),
                'first_record_keys' => array_keys($request->data[0] ?? []),
                'first_record' => $request->data[0] ?? [],
            ]);
            
            // Determine dusun_id
            $dusunId = $user ? $user->dusun_id : null;
            
            // If user is SuperAdmin or Kades (can access all dusuns), try to get dusun from data
            $canAccessAll = $user && ($user->isSuperAdmin() || $user->hasRole('kades') || $user->hasRole('super_admin'));
            
            if (!$dusunId && $canAccessAll) {
                // Try to extract dusun from the DUSUN field in data (case-insensitive)
                $dusunName = null;
                foreach ($request->data as $record) {
                    $dusunName = $record['DUSUN'] ?? $record['dusun'] ?? $record['Dusun'] ?? null;
                    if (!empty($dusunName)) {
                        break;
                    }
                }
                
                if ($dusunName) {
                    // Normalize dusun name (e.g., "DUSUN VIII" -> "VIII")
                    $searchTerm = trim(str_replace(['DUSUN', 'Dusun', 'dusun'], '', $dusunName));
                    
                    // Try to convert Roman numeral to Arabic number
                    $arabicNumber = romanToArabic($searchTerm);
                    
                    if ($arabicNumber) {
                        // Find dusun by number (e.g., "Dusun 8")
                        $dusun = \App\Models\Dusun::where('name', 'Dusun ' . $arabicNumber)
                            ->orWhere('code', 'DSN' . $arabicNumber)
                            ->first();
                    } else {
                        // Fallback: try to match by name directly
                        $dusun = \App\Models\Dusun::where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%')
                            ->first();
                    }
                    
                    if ($dusun) {
                        $dusunId = $dusun->id;
                        \Log::info('SuperAdmin/Kades saving to dusun from data', [
                            'dusun_input' => $dusunName,
                            'extracted' => $searchTerm,
                            'converted' => $arabicNumber,
                            'dusun_found' => $dusun->name,
                            'dusun_id' => $dusunId
                        ]);
                    } else {
                        \Log::warning('Could not find matching dusun', ['dusun_input' => $dusunName, 'extracted' => $searchTerm, 'arabic' => $arabicNumber]);
                    }
                } else {
                    // No DUSUN field found in data - default to Dusun 1 for SuperAdmin
                    \Log::warning('No DUSUN field in data, defaulting to Dusun 1');
                    $dusun = \App\Models\Dusun::where('name', 'Dusun 1')->first();
                    if ($dusun) {
                        $dusunId = $dusun->id;
                    }
                }
            }

            // If still no dusun_id, return error
            if (!$dusunId) {
                \Log::warning('User without dusun tried to save KK data', ['user_id' => $user?->id]);
                return response()->json([
                    'success' => false,
                    'error' => 'User tidak memiliki dusun yang ditugaskan. Hubungi administrator untuk menugaskan dusun Anda.'
                ], 403);
            }

            $saved = ['keluarga' => 0, 'penduduk' => 0, 'skipped' => 0];
            $errors = [];

            // Group by NO_KK to process families (case-insensitive)
            $groupedByKK = collect($request->data)->groupBy(function($item) {
                return getField($item, 'NO_KK') ?? getField($item, 'no_kk');
            });

            foreach ($groupedByKK as $noKK => $members) {
                try {
                    // Find or create Keluarga
                    $firstMember = $members->first();
                    $kepalaKeluarga = $members->first(function($m) {
                        $hubKel = getField($m, 'HUB_KEL', '');
                        return stripos($hubKel, 'KEPALA') !== false || stripos($hubKel, '1') !== false;
                    });
                    
                    $keluarga = Keluarga::firstOrCreate(
                        ['no_kk' => $noKK],
                        [
                            'dusun_id' => $dusunId,
                            'kepala_keluarga' => getField($kepalaKeluarga ?: $firstMember, 'NAMA'),
                            'alamat' => getField($firstMember, 'ALAMAT'),
                            'rt' => getField($firstMember, 'RT'),
                            'rw' => getField($firstMember, 'RW'),
                            'kelurahan_desa' => 'Kelurahan Gedongmeneng',
                            'kecamatan' => 'Gedongmeneng',
                            'kabupaten_kota' => 'Kota Bandar Lampung',
                            'provinsi' => 'Lampung',
                            'status_kk' => 'AKTIF',
                        ]
                    );

                    if ($keluarga->wasRecentlyCreated) {
                        $saved['keluarga']++;
                    }

                    // Save each member
                    foreach ($members as $member) {
                        try {
                            // Check if NIK already exists
                            $nik = getField($member, 'NIK');
                            if (!$nik) {
                                $errors[] = "Skipping record without NIK";
                                continue;
                            }
                            
                            $existingPenduduk = Penduduk::where('nik', $nik)->first();
                            
                            if ($existingPenduduk) {
                                $saved['skipped']++;
                                continue;
                            }

                            // Calculate age from tanggal_lahir if available
                            $tanggalLahir = null;
                            $umur = null;
                            $tempatLahir = getField($member, 'tempatlahir') ?: getField($member, 'TEMPAT_LAHIR');
                            $tglLahir = getField($member, 'tanggallahir') ?: getField($member, 'TANGGAL_LAHIR');
                            
                            if ($tglLahir) {
                                try {
                                    $tanggalLahir = \Carbon\Carbon::createFromFormat('d-m-Y', $tglLahir);
                                    $umur = $tanggalLahir->age;
                                } catch (\Exception $e) {
                                    // If parsing fails, leave as null
                                }
                            }

                            Penduduk::create([
                                'keluarga_id' => $keluarga->id,
                                'dusun_id' => $dusunId,
                                'nik' => $nik,
                                'nama_lengkap' => getField($member, 'NAMA'),
                                'jenis_kelamin' => getField($member, 'sex') == 1 ? 'LAKI-LAKI' : 'PEREMPUAN',
                                'tempat_lahir' => $tempatLahir,
                                'tanggal_lahir' => $tanggalLahir,
                                'umur' => $umur,
                                'agama' => getField($member, 'AGAMA'),
                                'pendidikan' => getField($member, 'PENDIDIKAN'),
                                'pekerjaan' => getField($member, 'PEKERJAAN') ?: 'BELUM/TIDAK BEKERJA',
                                'status_perkawinan' => normalizeStatusPerkawinan(getField($member, 'PERKAWINAN') ?: getField($member, 'status_kawin')),
                                'status_dalam_keluarga' => normalizeStatusDalamKeluarga(getField($member, 'HUB_KEL') ?: getField($member, 'kk_level')),
                                'nama_ayah' => getField($member, 'NAMA_AYAH') ?: getField($member, 'nama_ayah'),
                                'nama_ibu' => getField($member, 'NAMA_IBU') ?: getField($member, 'nama_ibu'),
                                'kewarganegaraan' => 'WNI',
                                'status_penduduk' => 'TETAP',
                            ]);

                            $saved['penduduk']++;
                        } catch (\Exception $e) {
                            $errorMsg = "NIK {$nik}: " . $e->getMessage();
                            $errors[] = $errorMsg;
                            \Log::error('Error saving Penduduk', [
                                'nik' => $nik,
                                'error' => $e->getMessage(),
                                'line' => $e->getLine(),
                                'member_data' => $member
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "KK {$noKK}: " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'saved' => $saved,
                'errors' => $errors,
                'message' => "Berhasil menyimpan {$saved['keluarga']} KK dan {$saved['penduduk']} penduduk. {$saved['skipped']} data dilewati (sudah ada)."
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in save KK', [
                'errors' => $e->errors(),
                'data_sample' => array_slice($request->data ?? [], 0, 2)
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Data tidak valid: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error saving KK data', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });
});
