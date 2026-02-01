# Map Points Import Documentation

## Overview

Data lokasi Desa Sindang Anom dari CSV telah diintegrasikan dengan peta interaktif website. Data akan otomatis ter-import saat setup awal database. **Gambar dari Google Drive akan ditampilkan langsung di peta interaktif**.

## CSV Data Source

**File:** `Data Lokasi Desa Sindang Anom.csv`
**Location:** Project root directory
**Total Locations:** 55 lokasi

## Data Structure

### CSV Columns:

1. **No** - Nomor urut (1-55)
2. **Nama Lokasi** - Nama tempat/lokasi
3. **Kategori** - Jenis lokasi (UMKM, Pendidikan, Ibadah, Olahraga, Kesehatan)
4. **Alamat Lengkap** - Alamat lengkap dengan kode Plus (PC4X+959, dll)
5. **Latitude** - Koordinat lintang (format: -5.294.089)
6. **Longitude** - Koordinat bujur (format: 105.447.983)
7. **Deskripsi/Keterangan** - Informasi tambahan
8. **Link Image** - Google Drive link untuk gambar lokasi

### Database Table: `map_points`

**Fields:**

- `id` - Primary key
- `name` - Nama lokasi (dari CSV: Nama Lokasi)
- `category` - Kategori (UMKM, Pendidikan, Olahraga, Ibadah, Kesehatan, Pemerintah)
- `address` - Alamat lengkap (dari CSV: Alamat Lengkap)
- `description` - Deskripsi (dari CSV: Deskripsi/Keterangan)
- `image_url` - **Direct image URL** dari Google Drive (auto-converted)
- `latitude` - DECIMAL(10,8) - Koordinat lintang
- `longitude` - DECIMAL(11,8) - Koordinat bujur
- `is_active` - BOOLEAN - Status aktif (default: true)
- `dusun_id` - Foreign key ke tabel dusuns
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Categories Breakdown

From the imported data (55 locations):

- **UMKM**: 27 locations (48.2%)
    - Bengkel, Warung, Toko, Cell, Laundry, Fotokopi, Cafe, dll
- **Ibadah**: 13 locations (23.6%)
    - Masjid, Musholla, Gereja
- **Pendidikan**: 7 locations (12.7%)
    - SDN, PAUD, TPQ, Pondok Pesantren
- **Olahraga**: 5 locations (9.1%)
    - Lapangan Bola, Lapangan Voli, Kolam Renang
- **Kesehatan**: 1 location (1.8%)
    - Pustu (Puskesmas Pembantu)
- **Pemerintah**: 2 locations (3.6%)
    - Kantor Desa, Balai Desa

## Coordinate Format Fix

### Original CSV Format (WRONG):

```
Latitude: -5.294.089
Longitude: 105.447.983
```

### Fixed Format (CORRECT):

```
Latitude: -5.294089
Longitude: 105.447983
```

**Conversion Logic:**

```php
// Remove all dots
$latitude = str_replace('.', '', $latitude); // -5294089
// Divide by 1,000,000 to get decimal
$latitude = (float) ($latitude / 1000000); // -5.294089
```

## Google Drive Image Display

### Link Conversion

Google Drive share links are automatically converted to direct image URLs:

**Original Share Link (from CSV):**

```
https://drive.google.com/file/d/1ZsJKdUm_BhMSkn30bzW6b3wS9eAcD49p/view?usp=drive_link
```

**Converted Direct Image URL (in database):**

```
https://drive.google.com/uc?export=view&id=1ZsJKdUm_BhMSkn30bzW6b3wS9eAcD49p
```

**Frontend Display (with thumbnail):**

```javascript
// PetaInteraktif.jsx uses thumbnail API for better performance
const imageUrl = `https://drive.google.com/thumbnail?id=${fileId}&sz=w400`;
```

### Conversion Logic in Seeder:

```php
// Extract file ID from Google Drive link
preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $imageLink, $matches);
if (isset($matches[1])) {
    $fileId = $matches[1];
    $imageUrl = "https://drive.google.com/uc?export=view&id={$fileId}";
}
```

### Image Display Features:

- ✅ **Automatic conversion** from share link to direct image
- ✅ **Thumbnail optimization** (400px width) for faster loading
- ✅ **Fallback handling** if image fails to load
- ✅ **Responsive display** in popup (full width, 160px height)
- ✅ **Lazy loading** only when marker is clicked

### Statistics:

- **Total Locations:** 55
- **With Images:** 50 locations (90.9%)
- **Without Images:** 5 locations (9.1%)

## Import Process

### MapPointSeeder.php

**Location:** `database/seeders/MapPointSeeder.php`

**Features:**

1. ✅ Reads CSV from project root
2. ✅ Parses semicolon-delimited data
3. ✅ Fixes coordinate format (removes extra dots)
4. ✅ Validates coordinates (skip if 0,0)
5. ✅ Maps categories to database enum
6. ✅ Combines description with image link
7. ✅ Checks for duplicates (by name + coordinates)
8. ✅ Assigns to default dusun
9. ✅ Provides import statistics

**Sample Output:**

```
Reading CSV file...
MapPoint seeding completed!
Imported: 55
Skipped: 0
```

## Setup Instructions

### For Fresh Installation:

1. **Ensure CSV file exists:**

    ```bash
    ls -la "Data Lokasi Desa Sindang Anom.csv"
    ```

2. **Run migrations:**

    ```bash
    php artisan migrate:fresh
    ```

3. **Seed database (includes MapPoints):**
    ```bash
    php artisan db:seed
    ```
    This will automatically call `MapPointSeeder` which imports all 55 locations.

### For Manual Import Only:

```bash
php artisan db:seed --class=MapPointSeeder
```

### To Re-import (Update existing):

1. Delete existing map points:

    ```bash
    php artisan tinker
    >>> App\Models\MapPoint::truncate();
    ```

2. Re-run seeder:
    ```bash
    php artisan db:seed --class=MapPointSeeder
    ```

## Viewing on Website

### Admin Panel (Filament):

1. Login as SuperAdmin/Kades: `/admin`
2. Navigate to: **Peta Interaktif** (sidebar)
3. View all 55 locations in table
4. Edit/Update/Delete locations
5. Add new locations with map picker

### Public Frontend (React):

1. Visit: `/peta-interaktif`
2. Interactive Leaflet map shows all active locations
3. Click marker to see:
    - Nama Lokasi
    - Kategori (with color badge)
    - Alamat
    - Deskripsi + Link Image
4. Filter by category (buttons)
5. Zoom in/out with mouse wheel

### MapController Endpoint:

**Route:** `GET /api/map-points`
**Response:**

```json
[
    {
        "id": 1,
        "name": "Bengkel Mobil",
        "category": "UMKM",
        "address": "PC4X+959 Sindang Anom, Kabupaten Lampung Timur, Lampung",
        "description": "Link Gambar: https://drive.google.com/file/d/...",
        "latitude": -5.294089,
        "longitude": 105.447983,
        "is_active": true,
        "dusun_id": 1
    }
    // ... 54 more locations
]
```

## Sample Data Examples

### Example 1: UMKM (Bengkel Mobil)

```csv
1;Bengkel Mobil;UMKM;PC4X+959 Sindang Anom, Kabupaten Lampung Timur, Lampung;-5.294.089;105.447.983;;https://drive.google.com/file/d/...
```

**Maps to:**

- Name: "Bengkel Mobil"
- Category: UMKM
- Latitude: -5.294089
- Longitude: 105.447983

### Example 2: Pendidikan (SDN 1 Sindang Anom)

```csv
15;SDN 1 Sindang Anom;Pendidikan;PC3W+2W8 Sindang Anom, East Lampung Regency, Lampung;-5.297.474;105.447.336;;https://drive.google.com/file/d/...
```

**Maps to:**

- Name: "SDN 1 Sindang Anom"
- Category: Pendidikan
- Latitude: -5.297474
- Longitude: 105.447336

### Example 3: Ibadah (Masjid AR-RAHMAN)

```csv
24;MASJID AR-RAHMAN;Ibadah;PC4Q+6Q7 Sindang Anom, East Lampung Regency, Lampung;-5.294.460;105.439.411;;https://drive.google.com/file/d/...
```

**Maps to:**

- Name: "MASJID AR-RAHMAN"
- Category: Ibadah
- Latitude: -5.294460
- Longitude: 105.439411

## Troubleshooting

### Issue: CSV not found

**Error:** `CSV file not found: /path/to/Data Lokasi Desa Sindang Anom.csv`
**Solution:**

1. Check file exists in project root
2. Verify filename matches exactly (case-sensitive)
3. Run: `ls -la "Data Lokasi Desa Sindang Anom.csv"`

### Issue: No dusun found

**Error:** `No dusun found. Please seed dusuns first.`
**Solution:**

1. Run DusunSeeder first: `php artisan db:seed --class=DusunSeeder`
2. Or run full seed: `php artisan db:seed`

### Issue: Invalid coordinates

**Warning:** `Invalid coordinates for: Location Name`
**Cause:** Latitude or Longitude is 0 after conversion
**Solution:**

1. Check CSV format for that row
2. Ensure coordinates have proper format: `-5.294.089`

### Issue: Duplicates not importing

**Behavior:** Seeder skips existing locations
**Reason:** By design - prevents duplicate data
**Solution:**

1. Truncate table first if you want to re-import
2. Or modify seeder to update instead of skip

## Sharing with Team

### Setup Instructions for Team Members:

1. **Clone repository:**

    ```bash
    git clone <repository-url>
    cd KKNT42
    ```

2. **Install dependencies:**

    ```bash
    composer install
    npm install
    ```

3. **Setup environment:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure database in `.env`:**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=kknt42
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Run migrations and seeders:**

    ```bash
    php artisan migrate:fresh --seed
    ```

    ✅ This automatically imports all 55 map points!

6. **Build assets:**

    ```bash
    npm run build
    ```

7. **Start server:**

    ```bash
    php artisan serve
    ```

8. **Access:**
    - Admin: http://localhost:8000/admin
    - Public: http://localhost:8000/peta-interaktif

### Important Files to Include:

When sharing with team, ensure these files are included:

1. ✅ `Data Lokasi Desa Sindang Anom.csv` (in project root)
2. ✅ `database/seeders/MapPointSeeder.php`
3. ✅ `database/seeders/DatabaseSeeder.php` (updated)
4. ✅ `app/Models/MapPoint.php`
5. ✅ `database/migrations/*_create_map_points_table.php`

### Git Tracking:

Add to `.gitignore` if needed:

```gitignore
# Keep CSV in repo for team
# !Data Lokasi Desa Sindang Anom.csv
```

Or explicitly track:

```bash
git add "Data Lokasi Desa Sindang Anom.csv"
git add database/seeders/MapPointSeeder.php
git commit -m "Add MapPoint seeder for 55 locations"
git push
```

## Statistics Summary

**Total Locations:** 55
**Import Success Rate:** 100% (55/55)
**Automatic Setup:** ✅ Yes (via DatabaseSeeder)
**Manual Re-import:** ✅ Supported
**Duplicate Prevention:** ✅ Yes (by name + coordinates)
**Coordinate Validation:** ✅ Yes (skip if invalid)
**Default Status:** All active (`is_active = true`)
**Default Dusun:** Assigned to first dusun in database

## Category Distribution by Type

```
UMKM (27):          ████████████████████████████████████████████ 48.2%
Ibadah (13):        ███████████████████████ 23.6%
Pendidikan (7):     █████████████ 12.7%
Olahraga (5):       █████████ 9.1%
Pemerintah (2):     ████ 3.6%
Kesehatan (1):      ██ 1.8%
```

## Notable Locations

**Educational Facilities:**

- SDN 1 Sindang Anom
- Pondok Pesantren Mathla'ul Falah
- PAUD AL HIDAYAH
- PAUD KB.DHARMA LESTARI
- TPQ AL-Hikmah

**Religious Sites:**

- Masjid AR-RAHMAN
- Musholla Al-Amin Wahui II
- Musholla Nurul Falah
- Gereja

**Sports Facilities:**

- Lapangan Sepak Bola (2 locations)
- Lapangan Voli
- Kolam Renang Pingwin

**Health Services:**

- Pustu ILP (Puskesmas Pembantu)

**Commerce:**

- Pasar (Market)
- Multiple warung, toko, bengkel, cell shops

---

**Last Updated:** 2026-02-01
**Version:** 1.0.0
**Status:** ✅ Fully Implemented & Tested
**Import Result:** 55/55 locations successfully imported
