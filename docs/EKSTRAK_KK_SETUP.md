# Ekstraksi Kartu Keluarga - Setup Guide

## ✅ Fitur yang Telah Dibuat

Fitur ekstraksi Kartu Keluarga dengan tampilan modern dan dua mode ekstraksi:

### 1. **AI (Gemini) Mode**

- Cocok untuk file KK hasil scan atau foto
- Lebih fleksibel dan akurat untuk gambar berkualitas rendah
- Memerlukan GEMINI_API_KEY

### 2. **Parser Manual Mode** ⭐ UPDATED!

- Cocok untuk file KK digital PDF dengan text layer
- **Mengikuti logika parsing dari pipeline Python Anda**
- Ekstrak SEMUA anggota keluarga (bukan hanya anak)
- Auto-generate NIK orang tua jika ada di dalam KK
- **Batch processing: Output jadi 1 tabel gabungan untuk multiple files**
- Tidak memerlukan API eksternal
- Gratis untuk digunakan

## 🆕 Update Terbaru

### Parser Manual - Python Pipeline Logic

Parser manual sekarang menggunakan logika yang **sama persis** dengan pipeline Python Anda (`pipeline_no_gemini.py`):

#### Fitur Parsing:

- ✅ **Header Extraction**: Alamat, dusun, RT, RW, No. KK, Kelurahan, Kecamatan, Provinsi, Kode Pos
- ✅ **Table 1 (Biodata)**: NIK, Nama, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Agama, Pendidikan, Pekerjaan, Golongan Darah
- ✅ **Table 2 (Status & Ortu)**: Status Kawin, Tanggal Perkawinan, Status dalam Keluarga, Kewarganegaraan, Nama Ayah, Nama Ibu
- ✅ **Auto NIK Mapping**: NIK orang tua otomatis terisi jika ada dalam KK yang sama
- ✅ **Edge Case Handling**:
    - Status kawin dengan karakter dash kebocoran
    - Pendidikan multi-word (TAMAT SD/SEDERAJAT, dll)
    - Golongan darah "TIDAK TAHU"
    - Status keluarga sesuai keywords

#### Output Structure:

```php
[
    'members' => [
        [
            'no_kk' => '3204...',
            'nik' => '3204...',
            'nama' => 'NAMA LENGKAP',
            'jenis_kelamin' => 'LAKI-LAKI',
            'tempat_lahir' => 'BANDUNG',
            'tanggal_lahir' => '15-08-1990',
            'agama' => 'ISLAM',
            'pendidikan' => 'TAMAT SD/SEDERAJAT',
            'pekerjaan' => 'PETANI/PEKEBUN',
            'status_kawin' => 'KAWIN TERCATAT',
            'status_dalam_keluarga' => 'KEPALA KELUARGA',
            'warganegara' => 'WNI',
            'nama_ayah' => 'NAMA AYAH',
            'ayah_nik' => '3204...', // Auto-generated jika ada di KK
            'nama_ibu' => 'NAMA IBU',
            'ibu_nik' => '3204...', // Auto-generated jika ada di KK
            'alamat' => 'JL. ...',
            'rt' => '001',
            'rw' => '002',
            // ... fields lainnya
        ]
    ]
]
```

### Batch Processing

- Upload multiple PDF atau 1 ZIP file
- **Output digabung jadi 1 tabel besar** untuk semudah review
- Excel export include semua kolom lengkap
- Database save otomatis group by No. KK

## 📁 File yang Dibuat

### Backend Files:

1. **app/Filament/Pages/EkstraksiKartuKeluarga.php**
    - Filament Page utama dengan Livewire
    - Form handling dan state management

2. **resources/views/filament/pages/ekstraksi-kartu-keluarga.blade.php**
    - UI modern dengan card selection untuk mode
    - Drag & drop file upload
    - Preview table hasil ekstraksi
    - Action buttons (Download Excel, Simpan ke Database)

3. **app/Services/KKExtraction/GeminiExtractorService.php**
    - Integrasi dengan Google Gemini API
    - Retry logic untuk rate limiting
    - Structured JSON output

4. **app/Services/KKExtraction/ManualParserService.php**
    - PDF text parsing dengan smalot/pdfparser
    - ZIP file support
    - Regex-based data extraction

5. **app/Services/KKExtraction/ExcelService.php**
    - Export ke Excel dengan styling
    - Maatwebsite/Excel integration

6. **app/Services/KKExtraction/DatabaseSaverService.php**
    - Simpan ke tabel Keluarga dan Penduduk
    - Transaction handling
    - Duplicate checking

### Config:

- **config/services.php** - Added Gemini API configuration

## 🚀 Cara Menggunakan

### 1. Setup Gemini API Key (Opsional)

Jika ingin menggunakan mode AI, tambahkan ke file `.env`:

```env
GEMINI_API_KEY=your_api_key_here
```

Dapatkan API key gratis di: https://aistudio.google.com/app/apikey

### 2. Install Dependencies

```bash
composer require maatwebsite/excel
composer require smalot/pdfparser
```

### 3. Akses Fitur

1. Login ke admin panel: `http://localhost:8001/admin`
2. Navigasi: **Kependudukan** → **Ekstraksi KK**
3. Atau langsung: `http://localhost:8001/admin/ekstraksi-kartu-keluarga`

### 4. Upload File

- Format: PDF atau ZIP (berisi multiple PDF)
- Maksimal: 50MB
- Metode: Click upload atau drag & drop

### 5. Pilih Mode Ekstraksi

- **AI (Gemini)**: Untuk KK scan/foto (memerlukan API key)
- **Parser Manual**: Untuk KK digital PDF

### 6. Ekstrak Data

- Klik tombol "Ekstrak Data"
- Tunggu proses selesai
- Review hasil di tabel preview

### 7. Aksi Selanjutnya

- **Download Excel**: Export ke file .xlsx
- **Simpan ke Database**: Simpan ke tabel Keluarga & Penduduk

## 🎨 Tampilan UI

Desain modern dengan:

- ✨ Card selection untuk mode ekstraksi
- 📤 Drag & drop upload area
- 💡 Info box dengan tips penggunaan
- 📊 Preview table dengan scroll horizontal
- 🎯 Action buttons yang jelas
- 🌓 Dark mode support
- 📱 Responsive design

## 📊 Data yang Diekstrak

Untuk setiap **ANAK** dalam KK:

1. No. KK
2. NIK
3. Nama Lengkap
4. Jenis Kelamin
5. Tempat Lahir
6. Tanggal Lahir
7. Umur
8. Alamat
9. RT/RW
10. Kelurahan/Desa
11. Kecamatan
12. Kabupaten/Kota
13. Provinsi
14. Kode Pos
15. Nama Ayah
16. Nama Ibu

**Catatan**: Fitur ini fokus pada ekstraksi data ANAK, bukan seluruh anggota keluarga.

## 🔧 Troubleshooting

### Mode AI tidak berfungsi

- Pastikan `GEMINI_API_KEY` sudah diset di `.env`
- Restart Laravel server setelah update `.env`
- Check logs: `storage/logs/laravel.log`

### Mode Parser Manual tidak akurat

- Pastikan PDF memiliki text layer (bukan gambar scan)
- Gunakan mode AI untuk file scan/foto

### Error saat upload

- Check size file tidak lebih dari 50MB
- Pastikan direktori `storage/app/public/exports` dan `storage/app/temp-kk-uploads` writable
- Run: `chmod -R 775 storage/app/public/exports storage/app/temp-kk-uploads`

### Error saat simpan ke database

- Check koneksi database
- Pastikan tabel `keluarga` dan `penduduk` exist
- Check logs untuk detail error

## 📝 Notes

- File temporary akan otomatis dibersihkan setelah ekstraksi
- Excel file disimpan di `storage/app/public/exports/`
- Data duplikat akan di-handle otomatis (berdasarkan No. KK dan NIK)
- Parent records (ayah/ibu) dibuat otomatis jika belum ada

## 🎯 Next Steps

Untuk pengembangan lebih lanjut:

1. Tambahkan validasi data lebih ketat
2. Tambahkan preview gambar KK sebelum ekstraksi
3. Tambahkan bulk delete hasil ekstraksi
4. Tambahkan export ke format lain (CSV, JSON)
5. Tambahkan statistik ekstraksi
