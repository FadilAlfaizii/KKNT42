<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiKKExtractorService
{
    private string $apiKey;
    private string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        
        if (!$this->apiKey) {
            throw new \Exception('GEMINI_API_KEY tidak ditemukan di .env file');
        }
    }

    /**
     * Extract data dari PDF Kartu Keluarga menggunakan Gemini AI
     * Output langsung dalam format ID (sudah di-mapping)
     * 
     * @param string $pdfPath Path absolut ke file PDF
     * @return array Array of person data (40 fields) dengan nilai ID
     */
    public function extractPdf(string $pdfPath): array
    {
        if (!file_exists($pdfPath)) {
            throw new \Exception("File tidak ditemukan: {$pdfPath}");
        }

        // Convert PDF ke base64
        $pdfBase64 = base64_encode(file_get_contents($pdfPath));

        // Prompt super lengkap dengan mapping IDs
        $prompt = $this->buildPrompt();

        // Request ke Gemini API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(120)->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => 'application/pdf',
                                'data' => $pdfBase64
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'topK' => 1,
                'topP' => 0.95,
                'maxOutputTokens' => 8192,
                'responseMimeType' => 'application/json'
            ]
        ]);

        if ($response->failed()) {
            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Gemini API gagal: ' . $response->body());
        }

        $result = $response->json();
        
        // Parse response
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
        
        if (!$text) {
            throw new \Exception('Tidak ada data yang diekstrak dari PDF');
        }

        $data = json_decode($text, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Gagal parsing JSON dari Gemini: ' . json_last_error_msg());
        }

        // Validate and fill missing fields
        return $this->validateAndFillData($data['persons'] ?? []);
    }

    /**
     * Build super comprehensive prompt dengan mapping IDs
     */
    private function buildPrompt(): string
    {
        return <<<PROMPT
Analisis dokumen PDF Kartu Keluarga (KK) Indonesia ini.

Jika ini BUKAN dokumen KK resmi, kembalikan: {"persons": []}

Jika YA, ekstrak data untuk SEMUA anggota keluarga dalam format JSON dengan struktur:
{
  "persons": [
    {
      "alamat": "...",
      "dusun": "...",
      "rw": "...",
      "rt": "...",
      "nama": "...",
      "no_kk": "...",
      "nik": "...",
      "sex": 1,
      "tempatlahir": "...",
      "tanggallahir": "...",
      "agama_id": 1,
      "pendidikan_kk_id": 5,
      "pendidikan_sedang_id": "-",
      "pekerjaan_id": 1,
      "status_kawin": 1,
      "kk_level": 1,
      "warganegara_id": 1,
      "ayah_nik": "-",
      "nama_ayah": "...",
      "ibu_nik": "-",
      "nama_ibu": "...",
      "golongan_darah_id": 13,
      "akta_lahir": "-",
      "dokumen_pasport": "-",
      "tanggal_akhir_paspor": "-",
      "dokumen_kitas": "-",
      "akta_perkawinan": "-",
      "tanggalperkawinan": "-",
      "akta_perceraian": "-",
      "tanggalperceraian": "-",
      "cacat_id": "-",
      "cara_kb_id": "-",
      "hamil": "-",
      "ktp_el": "-",
      "status_rekam": "-",
      "alamat_sekarang": "-",
      "status_dasar": "HIDUP",
      "suku": "-",
      "tag_id_card": "-",
      "id_asuransi": "-",
      "no_asuransi": "-"
    }
  ]
}

INSTRUKSI EKSTRAKSI (SANGAT PENTING):

1. HEADER DATA (sama untuk semua anggota):
   - alamat: Nama Desa/Kelurahan (text)
   - dusun: Alamat lengkap/Kampung (text)
   - rt: Nomor RT format 3 digit (contoh: "001", "015")
   - rw: Nomor RW format 3 digit (contoh: "001", "003")
   - no_kk: Nomor KK 16 digit (text)

2. DATA PERSONAL:
   - nama: Nama lengkap UPPERCASE (text)
   - nik: NIK 16 digit (text)
   - tempatlahir: Tempat lahir (text)
   - tanggallahir: Format DD-MM-YYYY (text, contoh: "15-08-1990")

3. MAPPING KE ID (OUTPUT BERUPA ANGKA):

   sex (Jenis Kelamin):
   - LAKI-LAKI → 1
   - PEREMPUAN → 2

   agama_id:
   - ISLAM → 1
   - KRISTEN → 2
   - KATHOLIK → 3
   - HINDU → 4
   - BUDHA → 5
   - KHONGHUCU → 6
   - Jika kosong/tidak ada → 13

   pendidikan_kk_id:
   - TIDAK/BELUM SEKOLAH → 1
   - BELUM TAMAT SD/SEDERAJAT → 2
   - TAMAT SD/SEDERAJAT → 3
   - SLTP/SEDERAJAT → 4
   - SLTA/SEDERAJAT → 5
   - DIPLOMA I/II → 6
   - AKADEMI/DIPLOMA III/S.MUDA → 7
   - DIPLOMA IV/STRATA I → 8
   - STRATA II → 9
   - STRATA III → 10
   - Jika kosong/tidak ada → 1

   pekerjaan_id:
   - TIDAK/BELUM BEKERJA → 1
   - MENGURUS RUMAH TANGGA → 2
   - PELAJAR/MAHASISWA → 3
   - PENSIUNAN → 4
   - PEWIRASWASTA → 5
   - KARYAWAN SWASTA → 6
   - KARYAWAN BUMN → 7
   - KARYAWAN BUMD → 8
   - KARYAWAN HONORER → 9
   - BURUH HARIAN LEPAS → 10
   - BURUH TANI/PERKEBUNAN → 11
   - BURUH NELAYAN/PERIKANAN → 12
   - BURUH PETERNAKAN → 13
   - PEMBANTU RUMAH TANGGA → 14
   - TUKANG CUKUR → 15
   - TUKANG LISTRIK → 16
   - TUKANG BATU → 17
   - TUKANG KAYU → 18
   - TUKANG SOL SEPATU → 19
   - TUKANG LAS/PANDAI BESI → 20
   - TUKANG JAHIT → 21
   - TUKANG GIGI → 22
   - PENATA RIAS → 23
   - PENATA BUSANA → 24
   - PENATA RAMBUT → 25
   - MEKANIK → 26
   - SENIMAN → 27
   - TABIB → 28
   - PARAJI → 29
   - PERANCANG BUSANA → 30
   - PENTERJEMAH → 31
   - IMAM MASJID → 32
   - PENDETA → 33
   - PASTOR → 34
   - WARTAWAN → 35
   - USTADZ/MUBALIGH → 36
   - JURU MASAK → 37
   - PROMOTOR ACARA → 38
   - ANGGOTA DPR RI → 39
   - ANGGOTA DPD → 40
   - ANGGOTA BPK → 41
   - PRESIDEN → 42
   - WAKIL PRESIDEN → 43
   - ANGGOTA MAHKAMAH KONSTITUSI → 44
   - ANGGOTA KABINET KEMENTERIAN → 45
   - DUTA BESAR → 46
   - GUBERNUR → 47
   - WAKIL GUBERNUR → 48
   - BUPATI → 49
   - WAKIL BUPATI → 50
   - WALIKOTA → 51
   - WAKIL WALIKOTA → 52
   - ANGGOTA DPRD PROVINSI → 53
   - ANGGOTA DPRD KABUPATEN/KOTA → 54
   - DOSEN → 55
   - GURU → 56
   - PILOT → 57
   - PENGACARA → 58
   - NOTARIS → 59
   - ARSITEK → 60
   - AKUNTAN → 61
   - KONSULTAN → 62
   - DOKTER → 63
   - BIDAN → 64
   - PERAWAT → 65
   - APOTEKER → 66
   - PSIKIATER/PSIKOLOG → 67
   - PENYIAR TELEVISI → 68
   - PENYIAR RADIO → 69
   - PELAUT → 70
   - PENELITI → 71
   - SOPIR → 72
   - PIALANG → 73
   - PARANORMAL → 74
   - PEDAGANG → 75
   - PERANGKAT DESA → 76
   - KEPALA DESA → 77
   - BIARAWATI → 78
   - WIRASWASTA → 79
   - TENTARA NASIONAL INDONESIA → 80
   - KEPOLISIAN RI → 81
   - PERDAGANGAN → 82
   - PETANI/PEKEBUN → 83
   - PETERNAK → 84
   - NELAYAN/PERIKANAN → 85
   - INDUSTRI → 86
   - KONSTRUKSI → 87
   - TRANSPORTASI → 88
   - KARYAWAN HONORER → 89
   - Jika kosong/tidak ada → 1

   status_kawin:
   - BELUM KAWIN → 1
   - KAWIN → 2
   - CERAI HIDUP → 3
   - CERAI MATI → 4
   - Jika kosong/tidak ada → 1

   kk_level (Hubungan Keluarga):
   - KEPALA KELUARGA → 1
   - SUAMI → 2
   - ISTRI → 3
   - ANAK → 4
   - MENANTU → 5
   - CUCU → 6
   - ORANGTUA → 7
   - MERTUA → 8
   - FAMILI LAIN → 9
   - PEMBANTU → 10
   - LAINNYA → 11
   - Jika kosong/tidak ada → 11

   warganegara_id:
   - WNI → 1
   - WNA → 2
   - Jika kosong/tidak ada → 1

   golongan_darah_id:
   - A → 1
   - B → 2
   - AB → 3
   - O → 4
   - A+ → 5
   - A- → 6
   - B+ → 7
   - B- → 8
   - AB+ → 9
   - AB- → 10
   - O+ → 11
   - O- → 12
   - TIDAK TAHU → 13
   - Jika kosong/tidak ada → 13

4. FIELD RELASI:
   - nama_ayah: Nama ayah (text, cari dari kolom Nama Ayah di KK)
   - nama_ibu: Nama ibu (text, cari dari kolom Nama Ibu di KK)
   - ayah_nik: NIK ayah jika tertera di KK (16 digit), jika kosong gunakan "-"
   - ibu_nik: NIK ibu jika tertera di KK (16 digit), jika kosong gunakan "-"
   
   PENTING untuk ayah_nik dan ibu_nik:
   - Periksa kolom NIK AYAH dan NIK IBU di dokumen KK
   - Jika tertera NIK yang valid (16 digit), gunakan NIK tersebut
   - Jika kosong atau tidak ada, gunakan "-"

5. FIELD DEFAULT (isi dengan nilai berikut):
   - pendidikan_sedang_id: "-"
   - akta_lahir: "-"
   - dokumen_pasport: "-"
   - tanggal_akhir_paspor: "-"
   - dokumen_kitas: "-"
   - akta_perkawinan: "-"
   - tanggalperkawinan: "-"
   - akta_perceraian: "-"
   - tanggalperceraian: "-"
   - cacat_id: "-"
   - cara_kb_id: "-"
   - hamil: "-"
   - ktp_el: "-"
   - status_rekam: "-"
   - alamat_sekarang: "-"
   - status_dasar: "HIDUP"
   - suku: "-"
   - tag_id_card: "-"
   - id_asuransi: "-"
   - no_asuransi: "-"

PENTING:
- Ekstrak SEMUA anggota keluarga yang tertera di KK
- sex, agama_id, pendidikan_kk_id, pekerjaan_id, status_kawin, kk_level, warganegara_id, golongan_darah_id HARUS berupa ANGKA (sudah di-mapping)
- Jika data tidak bisa dibaca, gunakan nilai default yang sudah ditentukan
- Format tanggal: DD-MM-YYYY
- NIK dan No. KK: 16 digit tanpa spasi
- RT/RW: 3 digit dengan leading zero jika perlu

Output HARUS valid JSON dengan struktur seperti contoh di atas.
PROMPT;
    }

    /**
     * Validate dan fill missing fields dengan default values
     */
    private function validateAndFillData(array $persons): array
    {
        $validated = [];

        foreach ($persons as $person) {
            $validated[] = [
                // Header data
                'alamat' => $person['alamat'] ?? '-',
                'dusun' => $person['dusun'] ?? '-',
                'rw' => $person['rw'] ?? '-',
                'rt' => $person['rt'] ?? '-',
                'nama' => $person['nama'] ?? '-',
                'no_kk' => $person['no_kk'] ?? '-',
                'nik' => $person['nik'] ?? '-',
                
                // Personal data dengan mapping
                'sex' => $person['sex'] ?? 1,
                'tempatlahir' => $person['tempatlahir'] ?? '-',
                'tanggallahir' => $person['tanggallahir'] ?? '-',
                'agama_id' => $person['agama_id'] ?? 13,
                'pendidikan_kk_id' => $person['pendidikan_kk_id'] ?? 1,
                'pendidikan_sedang_id' => '-',
                'pekerjaan_id' => $person['pekerjaan_id'] ?? 1,
                'status_kawin' => $person['status_kawin'] ?? 1,
                'kk_level' => $person['kk_level'] ?? 11,
                'warganegara_id' => $person['warganegara_id'] ?? 1,
                
                // Relasi
                'ayah_nik' => $person['ayah_nik'] ?? '-',
                'nama_ayah' => $person['nama_ayah'] ?? '-',
                'ibu_nik' => $person['ibu_nik'] ?? '-',
                'nama_ibu' => $person['nama_ibu'] ?? '-',
                
                // Additional fields
                'golongan_darah_id' => $person['golongan_darah_id'] ?? 13,
                'akta_lahir' => '-',
                'dokumen_pasport' => '-',
                'tanggal_akhir_paspor' => '-',
                'dokumen_kitas' => '-',
                'akta_perkawinan' => '-',
                'tanggalperkawinan' => '-',
                'akta_perceraian' => '-',
                'tanggalperceraian' => '-',
                'cacat_id' => '-',
                'cara_kb_id' => '-',
                'hamil' => '-',
                'ktp_el' => '-',
                'status_rekam' => '-',
                'alamat_sekarang' => '-',
                'status_dasar' => 'HIDUP',
                'suku' => '-',
                'tag_id_card' => '-',
                'id_asuransi' => '-',
                'no_asuransi' => '-',
            ];
        }

        return $validated;
    }
}
