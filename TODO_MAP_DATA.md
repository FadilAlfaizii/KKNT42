# Rencana Implementasi Data Lokasi Desa Sindang Anom

## Tujuan
Menambahkan data lokasi dari file CSV ke peta interaktif

## Langkah Implementasi

### 1. Persiapan Direktori
- [ ] Buat direktori `storage/app/map-data/`

### 2. Pindahkan File CSV
- [ ] Pindahkan `Data Lokasi Desa Sindang Anom - Sheet1.csv` ke `storage/app/map-data/`

### 3. Update MapController
- [ ] Update `getLocationsFromCsv()` untuk menangani:
  - [ ] Delimiter semicolon (`;`)
  - [ ] Format angka Indonesia (hapus `.` dari ribuan)
  - [ ] Koordinat dengan benar

### 4. Testing
- [ ] Jalankan server development
- [ ] Akses `/peta-interaktif`
- [ ] Verifikasi data muncul di peta

## Catatan Teknis
- CSV menggunakan delimiter `;`
- Format latitude: `-5.294.089` perlu diubah ke `-5.294089`
- Format longitude: `105.447.983` perlu diubah ke `105.447983`

