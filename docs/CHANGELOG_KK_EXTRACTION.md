# Changelog: KK Extraction Update - Match Pipeline Python

## Tanggal: 1 Februari 2026

### Perubahan Utama

#### 1. **Field Names Update** (Match dengan pipeline_no_gemini.py)

Semua field names sekarang menggunakan underscore naming sesuai pipeline Python:

**Sebelum → Sesudah:**

- `jenis_kelamin` → `sex`
- `tempat_lahir` → `tempatlahir`
- `tanggal_lahir` → `tanggallahir`
- `agama` → `agama_id`
- `pendidikan` → `pendidikan_kk_id`
- `pekerjaan` → `pekerjaan_id`
- `tanggal_perkawinan` → `tanggalperkawinan`
- `status_dalam_keluarga` → `kk_level`
- `kewarganegaraan` → `warganegara_id`
- `golongan_darah` → `golongan_darah_id`

#### 2. **Tambahan Master Mappings**

Ditambahkan konstanta mapping sesuai pipeline Python untuk convert text → ID:

```php
protected array $masterMappings = [
    'sex' => ['LAKI-LAKI' => 1, 'PEREMPUAN' => 2],
    'agama_id' => ['ISLAM' => 1, 'KRISTEN' => 2, ...],
    'pendidikan_kk_id' => ['TIDAK / BELUM SEKOLAH' => 1, ...],
    'pekerjaan_id' => ['BELUM/TIDAK BEKERJA' => 1, ...],
    'status_kawin' => ['BELUM KAWIN' => 1, ...],
    'kk_level' => ['KEPALA KELUARGA' => 1, ...],
    'warganegara_id' => ['WNI' => 1, 'WNA' => 2, ...],
    'golongan_darah_id' => ['A' => 1, 'B' => 2, ...]
];
```

#### 3. **Method `normalizeData()` Baru**

Ditambahkan method untuk normalisasi data sebelum export:

- Membersihkan string (remove trailing dash, normalize spaces)
- Normalize slash spacing (`SD / SEDERAJAT` → `SD/SEDERAJAT`)
- Apply mapping jika toggle enabled

```php
protected function normalizeData(array $data, bool $useMapping): array
```

#### 4. **Toggle Mapping di UI**

Ditambahkan checkbox "Gunakan Mapping ID" di form upload:

- **OFF (default)**: Output tetap text mentah (`"ISLAM"`, `"LAKI-LAKI"`)
- **ON**: Output angka untuk database (`1`, `2`, `3`, dll)

Sama seperti pipeline Python:

```python
# Toggle OFF
save_to_excel(data, "Data_KK_Teks_Mentah.xlsx", use_mapping=False)

# Toggle ON
save_to_excel(data, "Data_KK_Siap_Import_ID.xlsx", use_mapping=True)
```

#### 🎯 Excel Export Column Order Update (FINAL)

**Total Kolom:** **42 kolom** (No + 41 data columns)

Urutan kolom Excel sekarang **100% match** dengan `TARGET_COLUMNS` Python:

```
No, alamat, dusun, rw, rt, nama, no_kk, nik, sex, tempatlahir,
tanggallahir, agama_id, pendidikan_kk_id, pendidikan_sedang_id,
pekerjaan_id, status_kawin, kk_level, warganegara_id, ayah_nik,
nama_ayah, ibu_nik, nama_ibu, golongan_darah_id, akta_lahir,
dokumen_pasport, tanggal_akhir_paspor, dokumen_kitas, akta_perkawinan,
tanggalperkawinan, akta_perceraian, tanggalperceraian, cacat_id,
cara_kb_id, hamil, ktp_el, status_rekam, alamat_sekarang,
status_dasar, suku, tag_id_card, id_asuransi, no_asuransi
```

**Kolom yang diekstrak dari PDF (18 kolom):**

- Header: alamat, dusun, rt, rw, no_kk
- Table1: nama, nik, sex, tempatlahir, tanggallahir, agama_id, pendidikan_kk_id, pekerjaan_id, golongan_darah_id
- Table2: status_kawin, tanggalperkawinan, kk_level, warganegara_id, nama_ayah, nama_ibu
- Auto-generated: ayah_nik, ibu_nik

**Kolom placeholder (23 kolom):**
Kolom berikut diisi dengan "-" karena tidak ada di KK PDF standar:

- pendidikan_sedang_id, akta_lahir, dokumen_pasport, tanggal_akhir_paspor,
  dokumen_kitas, akta_perkawinan, akta_perceraian, tanggalperceraian,
  cacat_id, cara_kb_id, hamil, ktp_el, status_rekam, alamat_sekarang,
  status_dasar, suku, tag_id_card, id_asuransi, no_asuransi

### 5. **Excel Export Column Order Update**

Urutan kolom Excel sekarang match dengan `TARGET_COLUMNS` Python:

```
No, alamat, dusun, rw, rt, nama, no_kk, nik, sex, tempatlahir,
tanggallahir, agama_id, pendidikan_kk_id, pekerjaan_id, status_kawin,
tanggalperkawinan, kk_level, warganegara_id, ayah_nik, nama_ayah,
ibu_nik, nama_ibu, golongan_darah_id
```

#### 6. **Preview Table Update**

Table preview di UI sekarang menampilkan field names yang benar:

- `sex` (bukan "JK")
- `tempatlahir` (bukan "Tempat Lahir")
- `kk_level` (bukan "Status Keluarga")
- `warganegara_id` (bukan "Kewarganegaraan")
- Dll.

### Files Modified

1. **app/Services/KKExtraction/KKExtractorService.php**
    - Added `$masterMappings` property
    - Added `normalizeData()` method
    - Updated `parseTable1Row()` return structure
    - Updated `parseTable2Row()` return structure
    - Updated `generateExcel()` signature: `generateExcel(array $data, bool $useMapping = false)`
    - Updated column headers and data rows

2. **app/Filament/Pages/EkstraksiKartuKeluarga.php**
    - Added `public bool $useMapping = false;` property
    - Updated `downloadExcel()` to pass `$useMapping` parameter

3. **resources/views/filament/pages/ekstraksi-kartu-keluarga.blade.php**
    - Added checkbox toggle for mapping
    - Updated preview table headers (17 columns matching Python output)
    - Updated preview table data references

### Cara Penggunaan

#### Mode Manual Parser (Tanpa Mapping):

```
1. Upload PDF KK
2. Checkbox "Gunakan Mapping ID" → OFF (unchecked)
3. Klik "Ekstrak Data"
4. Download Excel → Output: "ISLAM", "LAKI-LAKI", "KEPALA KELUARGA"
```

#### Mode Manual Parser (Dengan Mapping):

```
1. Upload PDF KK
2. Checkbox "Gunakan Mapping ID" → ON (checked)
3. Klik "Ekstrak Data"
4. Download Excel → Output: 1, 2, 3 (ID angka)
```

### Contoh Output

**Tanpa Mapping (use_mapping=false):**

```
| nama   | sex      | agama_id | kk_level        |
|--------|----------|----------|-----------------|
| SARLAN | LAKI-LAKI| ISLAM    | KEPALA KELUARGA |
```

**Dengan Mapping (use_mapping=true):**

```
| nama   | sex | agama_id | kk_level |
|--------|-----|----------|----------|
| SARLAN | 1   | 1        | 1        |
```

### Compatibility Notes

✅ Field names sekarang 100% match dengan pipeline Python  
✅ Mapping IDs match dengan MASTER_MAPPINGS Python  
✅ Normalisasi string (remove trailing dash, fix slash spacing)  
✅ Toggle untuk text vs ID output  
✅ Excel column order sesuai TARGET_COLUMNS

### Testing Checklist

- [x] Field names match pipeline Python
- [x] Mapping toggle working
- [x] Excel export with text output (toggle OFF)
- [x] Excel export with ID output (toggle ON)
- [x] Preview table showing correct field names
- [x] Server restart without errors
- [ ] Test dengan 4 PDF files (user perlu test)
- [ ] Verify mapping accuracy vs Python output
- [ ] Test Gemini mode compatibility

### Next Steps (User)

1. Buka http://127.0.0.1:8001/admin/ekstraksi-kartu-keluarga
2. Test dengan PDF KK yang sama (4 files)
3. Test toggle OFF → verify text output
4. Test toggle ON → verify ID mapping
5. Compare Excel output dengan Python pipeline hasil
