# Laravel Gemini Service - Dokumentasi Lengkap

## 📋 Overview

Laravel Service untuk ekstraksi data KK menggunakan Google Gemini AI yang **independen dari folder `ekstrak-pdf-kartu-keluarga`**.

**Fitur Utama:**

- ✅ Output langsung dalam format ID (sudah di-mapping)
- ✅ 40 kolom lengkap (sama dengan Python parser)
- ✅ Prompt super comprehensive dengan semua mapping rules
- ✅ Ekstrak SEMUA anggota keluarga (bukan hanya anak)
- ✅ Backend Laravel service (API key aman di server)

---

## 🗂️ Struktur File

### **1. Backend Services**

#### `app/Services/GeminiKKExtractorService.php`

**Fungsi:** Core service untuk Gemini API
**Key Methods:**

- `extractPdf(string $pdfPath): array` - Main extraction method
- `buildPrompt(): string` - Generate comprehensive prompt
- `validateAndFillData(array $persons): array` - Validate & fill defaults

**Output Format:**

```php
[
    [
        'alamat' => 'SINDANG ANOM',
        'dusun' => 'KAMPUNG CIPINANG',
        'rw' => '001',
        'rt' => '015',
        'nama' => 'BUDI SANTOSO',
        'no_kk' => '3201234567890123',
        'nik' => '3201234567890123',
        'sex' => 1,  // LAKI-LAKI
        'tempatlahir' => 'JAKARTA',
        'tanggallahir' => '15-08-1990',
        'agama_id' => 1,  // ISLAM
        'pendidikan_kk_id' => 5,  // SLTA/SEDERAJAT
        'pekerjaan_id' => 79,  // WIRASWASTA
        'status_kawin' => 2,  // KAWIN
        'kk_level' => 1,  // KEPALA KELUARGA
        'warganegara_id' => 1,  // WNI
        'golongan_darah_id' => 13,  // TIDAK TAHU
        'nama_ayah' => 'SLAMET RIYADI',
        'nama_ibu' => 'SITI AMINAH',
        // ... 40 fields total
    ],
    // ... more persons
]
```

#### `app/Http/Controllers/Api/GeminiKKExtractorController.php`

**Fungsi:** API Controller untuk Gemini extraction
**Endpoints:**

- `POST /api/extract-kk-gemini` - Extract PDF
- `GET /api/extract-kk-gemini/health` - Health check

---

### **2. Frontend Service**

#### `ekstrak-pdf-kartu-keluarga/services/geminiBackendService.ts`

**Fungsi:** Frontend service untuk call Laravel API
**Methods:**

- `extractDataFromFilesGeminiBackend()` - Upload & extract PDFs
- `checkGeminiHealth()` - Check API status

---

### **3. Routes**

#### `routes/api.php`

```php
// Gemini AI Backend
Route::post('/extract-kk-gemini', [GeminiKKExtractorController::class, 'extract']);
Route::get('/extract-kk-gemini/health', [GeminiKKExtractorController::class, 'healthCheck']);

// Python Backend (existing)
Route::post('/extract-kk', ...);
```

---

## 🔧 Setup & Configuration

### **1. Install Composer Dependencies**

```bash
# No additional packages needed - uses built-in HTTP client
composer dump-autoload
```

### **2. Set Environment Variable**

Edit `.env`:

```env
GEMINI_API_KEY=your_gemini_api_key_here
```

**Cara Dapatkan API Key:**

1. Buka https://aistudio.google.com/apikey
2. Login dengan Google Account
3. Create API Key
4. Copy & paste ke `.env`

### **3. Build Frontend**

```bash
cd ekstrak-pdf-kartu-keluarga
npm run build
cp -r dist/* ../public/ekstrak-kk/
```

### **4. Start Laravel Server**

```bash
php artisan serve
```

---

## 📖 Prompt Engineering Details

### **Comprehensive Mapping Rules**

Service ini menggunakan prompt super lengkap yang mencakup SEMUA mapping rules:

#### **1. Sex Mapping**

```
LAKI-LAKI → 1
PEREMPUAN → 2
```

#### **2. Agama Mapping**

```
ISLAM → 1
KRISTEN → 2
KATHOLIK → 3
HINDU → 4
BUDHA → 5
KHONGHUCU → 6
(kosong) → 13
```

#### **3. Pendidikan Mapping (13 nilai)**

```
TIDAK/BELUM SEKOLAH → 1
BELUM TAMAT SD/SEDERAJAT → 2
TAMAT SD/SEDERAJAT → 3
SLTP/SEDERAJAT → 4
SLTA/SEDERAJAT → 5
DIPLOMA I/II → 6
AKADEMI/DIPLOMA III/S.MUDA → 7
DIPLOMA IV/STRATA I → 8
STRATA II → 9
STRATA III → 10
(kosong) → 1
```

#### **4. Pekerjaan Mapping (89 nilai)**

```
TIDAK/BELUM BEKERJA → 1
MENGURUS RUMAH TANGGA → 2
PELAJAR/MAHASISWA → 3
PENSIUNAN → 4
PEWIRASWASTA → 5
...
WIRASWASTA → 79
TENTARA NASIONAL INDONESIA → 80
KEPOLISIAN RI → 81
PETANI/PEKEBUN → 83
(kosong) → 1
```

#### **5. Status Kawin Mapping**

```
BELUM KAWIN → 1
KAWIN → 2
CERAI HIDUP → 3
CERAI MATI → 4
(kosong) → 1
```

#### **6. KK Level Mapping**

```
KEPALA KELUARGA → 1
SUAMI → 2
ISTRI → 3
ANAK → 4
MENANTU → 5
CUCU → 6
ORANGTUA → 7
MERTUA → 8
FAMILI LAIN → 9
PEMBANTU → 10
LAINNYA → 11
(kosong) → 11
```

#### **7. Warganegara Mapping**

```
WNI → 1
WNA → 2
(kosong) → 1
```

#### **8. Golongan Darah Mapping**

```
A → 1, B → 2, AB → 3, O → 4
A+ → 5, A- → 6, B+ → 7, B- → 8
AB+ → 9, AB- → 10, O+ → 11, O- → 12
TIDAK TAHU → 13
(kosong) → 13
```

---

## 🎯 Usage Examples

### **Frontend (React)**

```typescript
import { extractDataFromFilesGeminiBackend } from "./services/geminiBackendService";

const handleExtraction = async (files: File[]) => {
    const results = await extractDataFromFilesGeminiBackend(files, (progress) =>
        console.log(progress),
    );

    // Results sudah dalam format ID
    console.log(results[0].data[0].sex); // 1 (bukan "LAKI-LAKI")
    console.log(results[0].data[0].agama_id); // 1 (bukan "ISLAM")
};
```

### **Backend (PHP)**

```php
use App\Services\GeminiKKExtractorService;

$service = app(GeminiKKExtractorService::class);
$data = $service->extractPdf('/path/to/kk.pdf');

// Output langsung array dengan ID mapping
foreach ($data as $person) {
    echo $person['nama'] . ' - ' . $person['sex']; // "BUDI SANTOSO - 1"
}
```

### **API Call (cURL)**

```bash
curl -X POST http://127.0.0.1:8000/api/extract-kk-gemini \
  -F "pdf=@/path/to/kk.pdf"
```

**Response:**

```json
{
  "success": true,
  "fileName": "kk",
  "data": [
    {
      "alamat": "SINDANG ANOM",
      "sex": 1,
      "agama_id": 1,
      "kk_level": 1,
      ...
    }
  ],
  "recordCount": 5
}
```

---

## 🔍 Perbedaan dengan Client-Side Gemini

| Aspek              | Client-Side (Old)              | Laravel Service (New)           |
| ------------------ | ------------------------------ | ------------------------------- |
| **API Key**        | ❌ Exposed di frontend bundle  | ✅ Aman di server `.env`        |
| **Output**         | ⚠️ Text values (perlu mapping) | ✅ ID values (langsung)         |
| **Kolom**          | ⚠️ 17 kolom (hanya anak)       | ✅ 40 kolom (semua anggota)     |
| **Prompt**         | ⚠️ Basic                       | ✅ Comprehensive dengan mapping |
| **Logging**        | ❌ Tidak ada                   | ✅ Laravel Log                  |
| **Error Handling** | ⚠️ Basic try-catch             | ✅ Structured exceptions        |
| **Rate Limiting**  | ❌ Sulit dikontrol             | ✅ Mudah implement              |

---

## 🚀 Performance & Limits

### **Gemini API Limits**

- **Free Tier:** 15 requests/minute, 1500 requests/day
- **Max File Size:** 10MB per PDF
- **Timeout:** 120 seconds per request

### **Recommended Settings**

```php
// In GeminiKKExtractorService.php
'generationConfig' => [
    'temperature' => 0.1,  // Low for consistency
    'topK' => 1,           // Strict selection
    'topP' => 0.95,        // High probability
    'maxOutputTokens' => 8192,
]
```

---

## 🐛 Troubleshooting

### **Error: "GEMINI_API_KEY tidak ditemukan"**

**Solusi:**

```bash
# Check .env file
cat .env | grep GEMINI

# Add if missing
echo "GEMINI_API_KEY=your_key_here" >> .env

# Clear config cache
php artisan config:clear
```

### **Error: "Gemini API gagal: 429"**

**Cause:** Rate limit exceeded
**Solusi:**

- Tunggu 1 menit
- Kurangi concurrent requests
- Upgrade ke Gemini Pro (paid plan)

### **Error: "Gagal parsing JSON"**

**Cause:** AI output tidak valid JSON
**Solusi:**

- Check logs: `storage/logs/laravel.log`
- Coba file PDF lain (lebih jelas)
- Adjust `temperature` ke 0.05 (lebih strict)

---

## 📊 Testing Checklist

### **✅ Backend Testing**

```bash
# 1. Health check
curl http://127.0.0.1:8000/api/extract-kk-gemini/health

# 2. Extract PDF
curl -X POST http://127.0.0.1:8000/api/extract-kk-gemini \
  -F "pdf=@test-kk.pdf"

# 3. Check logs
tail -f storage/logs/laravel.log
```

### **✅ Frontend Testing**

1. Buka http://127.0.0.1:8000/admin/ekstrak-kartu-keluarga
2. Pilih mode "AI Gemini"
3. Upload PDF KK
4. Verify:
    - ✅ Loading progress muncul
    - ✅ Data terextrak dengan ID (bukan text)
    - ✅ 40 kolom lengkap
    - ✅ Info box "Output Otomatis dalam Format ID" muncul

### **✅ Output Validation**

```javascript
// Di browser console setelah ekstraksi
console.log(results[0].data[0]);

// Validate:
// - sex adalah number (1 atau 2), bukan string
// - agama_id adalah number, bukan string
// - Semua mapping field adalah number
```

---

## 🔄 Migration Path

Jika sebelumnya menggunakan client-side Gemini:

### **Before (Client-Side)**

```typescript
// OLD: ekstrak-pdf-kartu-keluarga/services/geminiService.ts
const results = await extractDataFromFiles(files, updateProgress, true);
```

### **After (Laravel Backend)**

```typescript
// NEW: ekstrak-pdf-kartu-keluarga/services/geminiBackendService.ts
const results = await extractDataFromFilesGeminiBackend(files, updateProgress);
// No need applyMapping parameter - sudah otomatis!
```

---

## 📝 Maintenance Notes

### **Update Mapping Rules**

Jika ada perubahan mapping (misal: tambah agama baru):

1. Edit `GeminiKKExtractorService.php` → method `buildPrompt()`
2. Update mapping list di prompt
3. No need rebuild frontend (pure backend change)
4. Test dengan `php artisan test` (jika ada test)

### **Update Gemini Model**

Untuk ganti model (misal: `gemini-2.0-flash` → `gemini-pro`):

```php
// In GeminiKKExtractorService.php
private string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
```

---

## 🎉 Summary

**Files Created:**

- ✅ `app/Services/GeminiKKExtractorService.php` (300 lines)
- ✅ `app/Http/Controllers/Api/GeminiKKExtractorController.php` (95 lines)
- ✅ `ekstrak-pdf-kartu-keluarga/services/geminiBackendService.ts` (100 lines)

**Files Modified:**

- ✅ `routes/api.php` (+2 routes)
- ✅ `ekstrak-pdf-kartu-keluarga/App.tsx` (use backend service)

**Result:**

- ✅ **Independen** dari folder `ekstrak-pdf-kartu-keluarga`
- ✅ **Output sama** dengan parser manual (40 kolom + ID mapping)
- ✅ **Prompt lengkap** dengan semua mapping rules
- ✅ **API key aman** di backend
- ✅ **Production ready**

---

**Version:** 1.0.0  
**Last Updated:** 2026-01-30  
**Author:** AI Assistant + Your Team
