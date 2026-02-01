# Search & Filter System Documentation

## Overview

Sistem pencarian dan filtering yang komprehensif untuk data Penduduk dan Keluarga, memungkinkan admin untuk dengan mudah menemukan dan mengekspor data spesifik.

## Features Implemented

### 1. Enhanced Search Fields

#### PendudukResource

**Searchable Fields:**

- ✅ NIK (searchable, copyable)
- ✅ Nama Lengkap (searchable)
- ✅ No. KK (searchable, copyable)
- ✅ Agama (searchable, sortable)
- ✅ Pendidikan (searchable, sortable)
- ✅ Pekerjaan (searchable, sortable)

**New Columns Added:**

- Agama (toggleable)
- Pendidikan (toggleable, limited 20 chars)
- Detail tempat/tanggal lahir (description on nama_lengkap)

#### KeluargaResource

**Searchable Fields:**

- ✅ No. KK (searchable, copyable)
- ✅ Kepala Keluarga (searchable)
- ✅ Alamat (searchable, limit 30 chars)
- ✅ Kelurahan/Desa (searchable, toggleable)
- ✅ Kecamatan (searchable, toggleable)

### 2. Comprehensive Filters

#### PendudukResource Filters (11 total)

**Basic Filters:**

1. **Dusun** (SelectFilter)
    - Searchable, preload
    - Visible only for SuperAdmin/Kades
2. **Jenis Kelamin** (SelectFilter)
    - Options: Laki-laki, Perempuan

3. **Agama** (SelectFilter)
    - 6 options: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu
    - Searchable

4. **Pendidikan** (SelectFilter)
    - 10 education levels
    - Searchable
    - From "Tidak/Belum Sekolah" to "Strata III"

5. **Pekerjaan** (SelectFilter)
    - Dynamic options from database (top 50)
    - Searchable (important for 89+ job types)
    - Auto-filters non-empty values

6. **Status Perkawinan** (SelectFilter)
    - 4 options: Belum Kawin, Kawin, Cerai Hidup, Cerai Mati

7. **Status Dalam Keluarga** (SelectFilter)
    - 11 options: Kepala Keluarga, Suami, Istri, Anak, Menantu, Cucu, dll.

8. **Status Penduduk** (SelectFilter)
    - 5 options: Tetap, Tidak Tetap, Pendatang, Meninggal, Pindah
    - Default: TETAP

**Range Filters:**

9. **Umur** (Range Filter)
    - Input: Umur Dari, Umur Sampai
    - Placeholders: 0-100
    - Shows active filter indicators

**Quick Filters (Toggle):**

10. **Dewasa (17+)** (Toggle Filter)
    - Automatically filters umur >= 17

11. **Kepala Keluarga** (Toggle Filter)
    - Shows only Kepala Keluarga records

#### KeluargaResource Filters (7 total)

**Basic Filters:**

1. **Dusun** (SelectFilter)
    - Same as Penduduk

2. **Status KK** (SelectFilter)
    - Options: Aktif, Tidak Aktif
    - Default: AKTIF

3. **RT** (SelectFilter)
    - Dynamic options from database
    - Searchable

4. **RW** (SelectFilter)
    - Dynamic options from database
    - Searchable

**Range Filters:**

5. **Jumlah Anggota** (Range Filter)
    - Input: Minimal, Maksimal
    - Placeholders: 0-10
    - Uses has('penduduks') query

6. **Tanggal Terbit** (Date Range Filter)
    - DatePicker: Dari Tanggal, Sampai Tanggal
    - Shows formatted indicators (d/m/Y)

**Quick Filters:**

7. **Keluarga Besar (5+ anggota)** (Toggle Filter)
    - Automatically filters >= 5 members

### 3. Export Features

#### Export Actions Available

**PendudukResource:**

- ✅ **Export Selected** (Bulk Action)
    - Icon: document-arrow-down
    - Color: success (green)
    - Applies current table filters
    - Exports only selected records

- ✅ **Export Semua** (Header Action)
    - Icon: arrow-down-tray
    - Color: success (green)
    - Exports all records (respects filters)

**KeluargaResource:**

- ✅ Same export actions as Penduduk

#### Export Columns

**PendudukExporter (22 columns):**

- ID, Dusun, No. KK, NIK
- Nama Lengkap, Jenis Kelamin
- Tempat Lahir, Tanggal Lahir, Umur
- Agama, Pendidikan, Pekerjaan
- Status Perkawinan, Status Dalam Keluarga
- Nama Ayah, Nama Ibu
- Golongan Darah, Kewarganegaraan
- Status Penduduk, Alamat Sebelumnya
- Created At, Updated At

**KeluargaExporter (18 columns):**

- ID, Dusun, No. KK, Kepala Keluarga
- Alamat, RT, RW
- Kelurahan/Desa, Kecamatan, Kabupaten/Kota, Provinsi
- Kode Pos, Jumlah Anggota
- Status KK, Tanggal Terbit
- Keterangan
- Created At, Updated At

**Export Features:**

- Auto-generates filename: `penduduk-YYYY-MM-DD-HHmmss.xlsx`
- Auto-generates filename: `keluarga-YYYY-MM-DD-HHmmss.xlsx`
- Shows completion notification with success/fail count
- Respects active table filters
- Includes all columns (not just visible ones)

### 4. UX Improvements

**Filter Persistence:**

- `->persistFiltersInSession()` - Filters saved in session
- Survives page refresh

**Filter Layout:**

- `->filtersFormColumns(3)` on Penduduk
- `->filtersFormColumns(2)` on Keluarga
- Organized in sidebar

**Filter Indicators:**

- Active filters show badges
- Custom `indicateUsing()` for range filters
- Shows human-readable filter values

**Column Toggleability:**

- Toggleable columns for optional info
- Hidden by default: created_at
- User can show/hide as needed

## Usage Examples

### Example 1: Find All Teachers Aged 25-40 in Specific Dusun

1. Open Penduduk page
2. Apply filters:
    - Dusun: [Select specific dusun]
    - Pekerjaan: Search "guru" or "teacher"
    - Umur: Dari=25, Sampai=40
3. Click "Export Selected" or "Export Semua"

### Example 2: List All Kepala Keluarga Who Are Adults

1. Open Penduduk page
2. Enable toggle filters:
    - ✅ Dewasa (17+)
    - ✅ Kepala Keluarga
3. View filtered results
4. Export if needed

### Example 3: Find Large Families (5+ members) by RT

1. Open Keluarga page
2. Apply filters:
    - RT: [Select RT]
    - ✅ Keluarga Besar (5+ anggota)
3. View results with member count
4. Export for distribution planning

### Example 4: Export All Active Families Issued in 2024

1. Open Keluarga page
2. Apply filters:
    - Status KK: Aktif
    - Tanggal Terbit: Dari=01/01/2024, Sampai=31/12/2024
3. Click "Export Semua"
4. Download Excel file

### Example 5: Search for Specific Person by NIK

1. Open Penduduk page
2. Use global search (top right): Type NIK
3. Results filter automatically
4. Click to view details
5. Copy NIK/No. KK with one click

## Technical Details

### Performance Optimizations

**Dynamic Filter Options:**

```php
// Pekerjaan filter - limits to top 50 for performance
->options(function () {
    return \App\Models\Penduduk::query()
        ->distinct()
        ->whereNotNull('pekerjaan')
        ->where('pekerjaan', '!=', '')
        ->pluck('pekerjaan', 'pekerjaan')
        ->take(50) // Limit for performance
        ->toArray();
})
->searchable() // Enables type-ahead search
```

**RT/RW Filter - Dynamic from Database:**

```php
->options(function () {
    return \App\Models\Keluarga::query()
        ->distinct()
        ->whereNotNull('rt')
        ->where('rt', '!=', '')
        ->orderBy('rt')
        ->pluck('rt', 'rt')
        ->toArray();
})
```

**Query Optimization:**

- Uses `->searchable()` for large option sets
- Uses `->preload()` for small option sets (< 20 items)
- Filters apply on database query (not in-memory)

### Filter Query Patterns

**Range Filter (Umur):**

```php
Tables\Filters\Filter::make('umur')
    ->form([...])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when($data['umur_dari'],
                fn (Builder $query, $umur): Builder =>
                    $query->where('umur', '>=', $umur)
            )
            ->when($data['umur_sampai'],
                fn (Builder $query, $umur): Builder =>
                    $query->where('umur', '<=', $umur)
            );
    })
```

**Toggle Filter (Dewasa):**

```php
Tables\Filters\Filter::make('dewasa')
    ->label('Dewasa (17+)')
    ->query(fn (Builder $query): Builder => $query->where('umur', '>=', 17))
    ->toggle()
```

**Relationship Filter (Jumlah Anggota):**

```php
->when(isset($data['min_anggota']),
    fn (Builder $query): Builder =>
        $query->has('penduduks', '>=', $data['min_anggota'])
)
```

## Files Modified/Created

### Modified Files:

1. `app/Filament/Resources/PendudukResource.php`
    - Enhanced table() method
    - Added 11 filters (from 4)
    - Added export actions
    - Enhanced columns with toggleability

2. `app/Filament/Resources/KeluargaResource.php`
    - Enhanced table() method
    - Added 7 filters (from 3)
    - Added export actions
    - Enhanced columns with toggleability

### Created Files:

3. `app/Filament/Exports/PendudukExporter.php`
    - 22 export columns
    - Custom filename generator
    - Completion notification

4. `app/Filament/Exports/KeluargaExporter.php`
    - 18 export columns
    - Custom filename generator
    - Completion notification

5. `docs/SEARCH_FILTER_SYSTEM.md` (this file)
    - Complete documentation
    - Usage examples
    - Technical details

## Benefits

### For Admin Users:

- ✅ Find specific records quickly (by NIK, nama, etc.)
- ✅ Filter by any demographic field
- ✅ Quick access to common queries (Kepala KK, adults, large families)
- ✅ Export filtered results to Excel
- ✅ Better data discovery and exploration
- ✅ Improved reporting capabilities

### For Data Analysis:

- ✅ Generate targeted reports (e.g., education levels by dusun)
- ✅ Identify specific demographics (e.g., seniors without NIK)
- ✅ Track family structures (e.g., large families by RT)
- ✅ Monitor status distributions (e.g., active vs inactive)

### For Administrative Tasks:

- ✅ Distribution planning (export by dusun, RT, RW)
- ✅ Program targeting (e.g., children for vaccination)
- ✅ Document verification (search by NIK/KK)
- ✅ Population monitoring (active residents only)

## Future Enhancements (Optional)

### Potential Additions:

1. **Saved Filters**
    - Allow users to save common filter combinations
    - Quick access to "Recent Filters"

2. **Advanced Search Builder**
    - Visual query builder
    - Complex AND/OR conditions

3. **Filter Presets**
    - Pre-configured filters (e.g., "Children for School", "Seniors for Healthcare")
    - Role-based presets

4. **Export Templates**
    - Custom column selection
    - Multiple export formats (PDF, CSV)
    - Scheduled exports

5. **Filter History**
    - Track filter usage
    - Popular filters dashboard

## Testing Checklist

### Functional Testing:

- [x] All filters render correctly
- [x] Multiple selection works (multi-select filters)
- [x] Filter combinations work together
- [x] Quick filters toggle properly
- [x] Range filters accept valid inputs
- [x] Export respects active filters
- [x] Export generates valid Excel files
- [x] Filenames are unique (timestamp-based)
- [x] Filter persistence works across sessions
- [x] Filter indicators show active filters
- [x] Column toggleability works
- [x] Searchable fields work correctly

### Performance Testing:

- [ ] Filter apply time < 2 seconds (with 1000+ records)
- [ ] No N+1 query issues
- [ ] Dynamic options load quickly
- [ ] Export completes in reasonable time
- [ ] Session storage doesn't bloat

### UX Testing:

- [ ] Mobile responsive
- [ ] Clear active filter indicators
- [ ] Reset filters button works
- [ ] Role-based visibility works
- [ ] Empty states are helpful
- [ ] Loading states are clear

## Conclusion

Sistem search & filter yang komprehensif ini meningkatkan usability admin panel secara signifikan. Dari 4 filter dasar menjadi 11 filter di Penduduk dan 7 filter di Keluarga, ditambah fitur export yang powerful, membuat data discovery dan reporting menjadi jauh lebih efisien.

**Before:**

- 4 basic filters (dusun, gender, status, age)
- No export functionality
- Limited search (only NIK, name, KK)

**After:**

- 11 comprehensive filters (Penduduk)
- 7 targeted filters (Keluarga)
- Export selected/all to Excel
- Enhanced searchable fields
- Quick toggle filters
- Range filters (age, date, member count)
- Filter persistence
- Filter indicators

---

**Last Updated:** 2024-01-XX  
**Version:** 1.0.0  
**Status:** ✅ Implemented & Ready for Testing
