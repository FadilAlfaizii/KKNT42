# Kependudukan Data Management Workflow

## Overview
The Kependudukan (Population) panel is now **VIEW-ONLY**. All data entry happens through the **"Ekstrak Kartu Keluarga"** panel, which automatically populates the Kependudukan database.

## Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────┐
│  1. Kadus uploads KK PDF in "Ekstrak Kartu Keluarga"       │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  2. Python/AI extracts data from PDF                        │
│     - Gemini AI mode (intelligent parsing)                  │
│     - Manual mode (pypdf parsing with mapping)              │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  3. User reviews extracted data in React app                │
│     - Download as Excel (optional)                          │
│     - Click "Simpan ke Database" button                     │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  4. API endpoint `/api/extract-kk/save` processes data      │
│     - Groups by NO_KK (Kartu Keluarga)                      │
│     - Creates/updates Keluarga records                      │
│     - Creates Penduduk records linked to KK                 │
│     - Auto-assigns dusun_id from authenticated user         │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  5. Data appears in Kependudukan panels (view-only)         │
│     - Penduduk: List of all residents                       │
│     - Keluarga (KK): List of all families                   │
│     - Filtered by dusun for Kadus users                     │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  6. Kades/Admin oversees all data across dusuns             │
│     - Dashboard statistics                                  │
│     - Export capabilities                                   │
│     - Cross-dusun reports                                   │
└─────────────────────────────────────────────────────────────┘
```

## Key Changes

### 1. Kependudukan Panel - Read-Only Mode

**Location:** `Admin > Kependudukan > Penduduk / Keluarga (KK)`

**Changes:**
- ✅ Removed "Create" buttons
- ✅ Removed "Edit" actions
- ✅ Removed "Delete" actions
- ✅ Only "View" action available
- ✅ Empty state message: "Data akan muncul setelah ekstraksi KK"

**Implementation:**
```php
// KeluargaResource.php & PendudukResource.php
public static function canCreate(): bool { return false; }
public static function canEdit($record): bool { return false; }
public static function canDelete($record): bool { return false; }
public static function canDeleteAny(): bool { return false; }
```

### 2. Ekstrak Kartu Keluarga Panel

**Location:** `Admin > Kependudukan > Ekstrak Kartu Keluarga` (moved to Kependudukan group)

**New Features:**
- ✅ **"Simpan ke Database" button** after extraction
- ✅ Real-time save progress indicator
- ✅ Success/error notifications
- ✅ Automatic dusun assignment from logged-in user

**Workflow:**
1. Upload PDF files (single or batch)
2. Choose extraction mode (AI or Manual)
3. Extract data
4. Review results in table
5. Click **"Simpan ke Database"**
6. Data saved with automatic dusun assignment
7. View in Kependudukan panels

### 3. API Endpoint - `/api/extract-kk/save`

**Method:** POST  
**Auth:** Required (uses authenticated user's dusun_id)

**Request Body:**
```json
{
  "data": [
    {
      "NO_KK": "1871021234567890",
      "NIK": "1871021234567890",
      "NAMA": "JOHN DOE",
      "JK": "LAKI-LAKI",
      "TEMPAT_TGL_LAHIR": "BANDAR LAMPUNG, 01-01-1990",
      "AGAMA": "ISLAM",
      "PENDIDIKAN": "SLTA/SEDERAJAT",
      "PEKERJAAN": "BELUM/TIDAK BEKERJA",
      "PERKAWINAN": "BELUM KAWIN",
      "HUB_KEL": "KEPALA KELUARGA",
      "NAMA_AYAH": "AYAH NAME",
      "NAMA_IBU": "IBU NAME",
      "ALAMAT": "Jl. Example No. 123",
      "RT": "001",
      "RW": "002"
    },
    // ... more records
  ]
}
```

**Response (Success):**
```json
{
  "success": true,
  "saved": {
    "keluarga": 5,
    "penduduk": 23,
    "skipped": 2
  },
  "errors": [],
  "message": "Berhasil menyimpan 5 KK dan 23 penduduk. 2 data dilewati (sudah ada)."
}
```

**Response (Error):**
```json
{
  "success": false,
  "error": "User tidak memiliki dusun yang ditugaskan."
}
```

### 4. Data Processing Logic

**Keluarga (Family) Creation:**
```php
Keluarga::firstOrCreate(
    ['no_kk' => $noKK],
    [
        'dusun_id' => $user->dusun_id,  // Auto from user
        'kepala_keluarga' => // First member with HUB_KEL = "KEPALA KELUARGA"
        'alamat' => // From first member's ALAMAT
        'rt' => // From first member's RT
        'rw' => // From first member's RW
        'status_kk' => 'AKTIF',
        // ... other fields with defaults
    ]
);
```

**Penduduk (Resident) Creation:**
```php
// Skip if NIK already exists
if (Penduduk::where('nik', $nik)->exists()) {
    continue; // Increment skipped counter
}

Penduduk::create([
    'keluarga_id' => $keluarga->id,
    'dusun_id' => $user->dusun_id,  // Auto from user
    'nik' => $member['NIK'],
    'nama_lengkap' => $member['NAMA'],
    'jenis_kelamin' => strtoupper($member['JK']),
    'umur' => // Auto-calculated from tanggal_lahir
    // ... all other fields from extraction
]);
```

### 5. Multi-Tenancy Enforcement

**Automatic Dusun Assignment:**
- Data saved with `dusun_id` from authenticated user
- Kadus can only save to their assigned dusun
- Users without dusun assignment are rejected (403 error)

**Access Control:**
- **Kadus:** See only their dusun's data in Kependudukan panels
- **Kades/SuperAdmin:** See all data with dusun filter/badge

## Usage Guide

### For Kadus (Kepala Dusun)

1. **Login to Admin Panel**
2. **Navigate:** `Kependudukan > Ekstrak Kartu Keluarga`
3. **Upload KK PDF files** (can be multiple)
4. **Choose extraction mode:**
   - **AI Mode:** Best for complex/handwritten KK
   - **Manual Mode:** Faster, 100% accurate for standard KK
5. **Click "Ekstrak Data"** and wait for processing
6. **Review extracted data** in the table
7. **Click "Simpan ke Database"** button
8. **Success message** appears with count summary
9. **View saved data:** Navigate to `Kependudukan > Penduduk` or `Keluarga (KK)`

### For Kades/SuperAdmin

1. **Monitor all dusuns:** Navigate to `Kependudukan > Penduduk/Keluarga`
2. **Filter by dusun:** Use dusun filter in table
3. **View statistics:** Check dashboard for aggregate data
4. **Export reports:** Use export features for analysis

## Error Handling

**Common Errors:**

1. **"User tidak memiliki dusun yang ditugaskan"**
   - **Cause:** User has no dusun_id assigned
   - **Solution:** Admin must assign user to a dusun in User management

2. **"Duplicate NIK"**
   - **Cause:** NIK already exists in database
   - **Behavior:** Skipped (not error), counted in "skipped" total

3. **"Invalid NO_KK format"**
   - **Cause:** NO_KK not 16 digits
   - **Solution:** Check extraction results before saving

## Database Schema

### keluargas Table
```
id, dusun_id, no_kk (unique), kepala_keluarga, alamat, rt, rw,
kelurahan_desa, kecamatan, kabupaten_kota, provinsi, kode_pos,
status_kk (AKTIF/TIDAK AKTIF), tanggal_terbit, keterangan,
created_at, updated_at
```

### penduduks Table
```
id, keluarga_id, dusun_id, nik (unique), nama_lengkap,
jenis_kelamin (LAKI-LAKI/PEREMPUAN), tempat_lahir, tanggal_lahir,
umur (auto-calculated), agama, pendidikan, pekerjaan,
status_perkawinan, status_dalam_keluarga, nama_ayah, nama_ibu,
golongan_darah, kewarganegaraan, no_paspor, no_akta_lahir,
status_penduduk (TETAP/PENDATANG/MENINGGAL/PINDAH),
keterangan, created_at, updated_at
```

## Benefits

✅ **Data integrity:** Single source of truth from PDF extraction  
✅ **No manual entry errors:** Direct extraction eliminates typos  
✅ **Batch processing:** Upload multiple KK files at once  
✅ **Audit trail:** Track when data was extracted and by whom  
✅ **Automatic dusun assignment:** No need to manually select dusun  
✅ **Duplicate prevention:** NIK uniqueness enforced  
✅ **Read-only enforcement:** Prevents accidental data modification  

## Future Enhancements

- [ ] Export filtered Kependudukan data to Excel
- [ ] Dashboard statistics aggregation by dusun
- [ ] Data validation reports (missing fields, anomalies)
- [ ] Bulk update/delete for admin (with audit log)
- [ ] Integration with Statistik webpage for public display
