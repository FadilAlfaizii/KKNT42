# 🔍 NIK Validation & Advanced Filters

**Tanggal:** 2 Februari 2026  
**Status:** ✅ Implemented

## 🎯 Fitur Baru

### 1. NIK Validation (Validasi NIK Otomatis)

#### Validasi yang Diterapkan:

- ✅ **16 Digit Wajib** - NIK harus tepat 16 digit angka
- ✅ **Unique Check** - Deteksi duplikat NIK otomatis
- ✅ **Numeric Only** - Hanya menerima angka
- ✅ **Auto Gender Detection** - Jenis kelamin otomatis terdeteksi dari NIK
    - Tanggal 01-31 = Laki-laki
    - Tanggal 41-71 = Perempuan (tanggal + 40)

#### Pesan Error yang Jelas:

```php
'NIK harus tepat 16 digit'
'NIK sudah terdaftar di sistem'
'NIK hanya boleh berisi angka'
```

#### Helper Text:

- Placeholder: `3201234567890001`
- Helper: "NIK harus 16 digit angka"

---

### 2. Advanced Filters (Filter Kombinasi)

#### Standard Filters (Tetap Ada):

- **Dusun** - Filter berdasarkan lokasi dusun
- **Jenis Kelamin** - Laki-laki / Perempuan
- **Agama** - Islam, Kristen, Hindu, dll
- **Status Dalam Keluarga** - Kepala KK, Istri, Anak, dll
- **Status Dasar** - Hidup, Mati, Pindah, Hilang

#### New Advanced Filters:

1. **Pendidikan** (Multiple Select)
    - Bisa pilih beberapa jenjang sekaligus
    - Options: SD, SMP, SMA, S1, S2, S3, dll
    - Searchable

2. **Pekerjaan** (Multiple Select)
    - Filter berdasarkan jenis pekerjaan
    - Bisa kombinasi: PNS + Guru + Dosen
    - Searchable dengan 13 kategori umum

3. **Status Perkawinan** (Multiple Select)
    - Belum Kawin, Kawin, Cerai Hidup, Cerai Mati
    - Bisa pilih kombinasi status

4. **Range Umur** (Custom Range Filter)
    - Input: Umur Minimal & Umur Maksimal
    - Contoh: 17-25 tahun (usia sekolah/kuliah)
    - Support umur_manual dan tanggallahir

#### Quick Filter Presets (Toggle):

1. **Usia Produktif** - Otomatis filter 15-64 tahun
2. **Anak-anak** - Otomatis filter < 15 tahun
3. **Lansia** - Otomatis filter > 60 tahun
4. **Hanya Kepala Keluarga** - Filter kk_level = 1
5. **NIK Belum Lengkap** - Deteksi NIK kosong/kurang dari 16 digit

---

### 3. Enhanced Search (Pencarian Multi-Kolom)

#### Global Search Mencakup:

- **NIK** - Cari berdasarkan nomor induk
- **Nama** - Cari nama penduduk
- **Nama Ayah** - Cari berdasarkan nama ayah
- **Nama Ibu** - Cari berdasarkan nama ibu
- **No. KK** - Cari berdasarkan nomor kartu keluarga

**Cara Pakai:**

- Ketik di search box atas tabel
- Sistem otomatis cari di semua kolom tersebut
- Hasil real-time tanpa reload

---

## 📊 Use Cases (Contoh Penggunaan)

### Case 1: Cari Usia Produktif yang Belum Bekerja

1. Aktifkan toggle **"Usia Produktif"**
2. Filter **Pekerjaan** = "Belum/Tidak Bekerja"
3. Hasil: Penduduk 15-64 tahun yang belum kerja

### Case 2: Validasi Data NIK

1. Aktifkan toggle **"NIK Belum Lengkap"**
2. Review semua penduduk dengan NIK kosong/salah
3. Edit satu per satu untuk lengkapi NIK

### Case 3: Survey Pendidikan Anak

1. Aktifkan toggle **"Anak-anak"**
2. Filter **Pendidikan** = "Tidak/Belum Sekolah" + "SD"
3. Hasil: Anak usia sekolah dengan status pendidikan

### Case 4: Cari Penduduk Tertentu

1. Ketik NIK atau nama di search box
2. Atau cari nama ayah/ibu jika lupa nama lengkap
3. Kombinasi dengan filter dusun jika perlu

---

## 🔧 Technical Details

### NIK Auto-Extract Logic:

```php
$provinsi = substr($nik, 0, 2);    // 32 = Jawa Barat
$kabupaten = substr($nik, 2, 2);   // 01 = Bogor
$kecamatan = substr($nik, 4, 2);   // 12 = Cibinong
$tglLahir = substr($nik, 6, 6);    // DDMMYY

// Gender extraction
$tgl = (int)substr($tglLahir, 0, 2);
if ($tgl > 40) {
    $sex = '2'; // Perempuan (tgl + 40)
} else {
    $sex = '1'; // Laki-laki
}
```

### Umur Calculation (Priority):

```sql
CASE
    WHEN umur_manual IS NOT NULL THEN umur_manual
    WHEN tanggallahir IS NOT NULL THEN (YEAR(CURRENT_DATE) - YEAR(tanggallahir))
    ELSE 0
END
```

### Filter Combination:

- Semua filter bisa dikombinasikan
- Multiple select menggunakan OR logic (salah satu cocok)
- Toggle presets menggunakan AND logic dengan filter lain

---

## 🎨 UI Improvements

### Form NIK Field:

```
┌─────────────────────────────────────┐
│ NIK *                               │
│ ┌─────────────────────────────────┐ │
│ │ 3201234567890001                │ │
│ └─────────────────────────────────┘ │
│ ℹ️ NIK harus 16 digit angka         │
└─────────────────────────────────────┘
```

### Filter Layout:

```
┌─ Filters ──────────────────────────┐
│ ▼ Standard (Dusun, Sex, Agama)    │
│ ▼ Advanced (Pendidikan, Pekerjaan)│
│ ▼ Quick Presets (Toggle buttons)  │
└────────────────────────────────────┘
```

---

## ✅ Testing Checklist

- [x] Input NIK 15 digit → Error "NIK harus tepat 16 digit"
- [x] Input NIK duplikat → Error "NIK sudah terdaftar"
- [x] Input NIK dengan huruf → Error "NIK hanya boleh berisi angka"
- [x] NIK dengan tanggal 45 → Auto set perempuan
- [x] NIK dengan tanggal 15 → Auto set laki-laki
- [x] Filter kombinasi: Usia Produktif + PNS
- [x] Filter range umur: 17-25 tahun
- [x] Quick filter: NIK Belum Lengkap
- [x] Search global: Cari nama, NIK, nama ayah/ibu
- [x] Multiple select: Pilih SD + SMP + SMA sekaligus

---

## 🚀 Next Steps (Opsional)

1. **NIK Parser Service** - Extract tempat/tanggal lahir dari NIK
2. **Bulk NIK Validator** - Validasi semua NIK sekaligus
3. **Export Filtered Data** - Export hasil filter ke Excel
4. **Filter Presets Save** - Simpan kombinasi filter favorit
5. **Data Quality Dashboard** - Widget khusus validasi data

---

**Dokumentasi oleh:** GitHub Copilot  
**File Terkait:** `app/Filament/Resources/PendudukResource.php`
