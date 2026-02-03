# 📚 Dokumentasi Sistem Informasi Desa Sindanganom

## 📖 Dokumentasi Aktif

### Panduan Utama

- **[MULTI_TENANCY_GUIDE.md](MULTI_TENANCY_GUIDE.md)** - Panduan lengkap sistem multi-tenancy (Dusun-based access control)
- **[KEPENDUDUKAN_WORKFLOW_GUIDE.md](KEPENDUDUKAN_WORKFLOW_GUIDE.md)** - Workflow lengkap manajemen kependudukan
- **[FITUR_MANAJEMEN_KK.md](FITUR_MANAJEMEN_KK.md)** - Panduan fitur manajemen Kartu Keluarga

### Panduan Ekstraksi & Import

- **[EKSTRAK_KK_SETUP.md](EKSTRAK_KK_SETUP.md)** - Setup ekstraksi Kartu Keluarga dari PDF/gambar
- **[EKSTRAK_KK_INTEGRATION.md](EKSTRAK_KK_INTEGRATION.md)** - Integrasi dengan OCR Google Vision API
- **[IMPLEMENTASI_IMPORT_HISTORY.md](IMPLEMENTASI_IMPORT_HISTORY.md)** - Sistem tracking history import data
- **[MAP_POINTS_IMPORT.md](MAP_POINTS_IMPORT.md)** - Import data titik peta dari CSV

### Panduan UI/UX

- **[BLUE_THEME_GUIDE.md](BLUE_THEME_GUIDE.md)** - Spesifikasi tema warna dashboard (Blue theme)
- **[DASHBOARD_GUIDE.md](DASHBOARD_GUIDE.md)** - Panduan lengkap dashboard dan komponennya
- **[PERUBAHAN_DASHBOARD.md](PERUBAHAN_DASHBOARD.md)** - Log perubahan dashboard
- **[UI_IMPROVEMENTS_LOG.md](UI_IMPROVEMENTS_LOG.md)** - Log improvement UI

### Fitur Teknis

- **[SEARCH_FILTER_SYSTEM.md](SEARCH_FILTER_SYSTEM.md)** - Sistem pencarian dan filter advanced
- **[NIK_VALIDATION_ADVANCED_FILTERS.md](NIK_VALIDATION_ADVANCED_FILTERS.md)** - Validasi NIK dan filter lanjutan
- **[STATISTIK_DASHBOARD_CHANGELOG.md](STATISTIK_DASHBOARD_CHANGELOG.md)** - Changelog halaman statistik
- **[PROJECT_OPTIMIZATION_SUMMARY.md](PROJECT_OPTIMIZATION_SUMMARY.md)** - Summary optimasi project

## 📂 Dokumentasi Arsip

File-file changelog lama dan fix dokumentasi dipindahkan ke folder **[archive/](archive/)** untuk menjaga struktur tetap bersih.

## 🚀 Quick Start

### 1. Setup Project

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
```

### 2. Login Credentials

**Super Admin:**

- Email: `superadmin@starter-kit.com`
- Password: `superadmin`

**Kadus (14 accounts):**

- Email: `dusun01@sindanganom.id` hingga `dusun14@sindanganom.id`
- Password: `dusun01` hingga `dusun14`

### 3. Ekstraksi KK

1. Setup Google Vision API (lihat `EKSTRAK_KK_SETUP.md`)
2. Upload file KK (PDF/gambar) di `/admin/ekstraksi-kartu-keluarga`
3. Review dan simpan data hasil ekstraksi

### 4. Import Data Peta

1. Siapkan file CSV dengan format: `name,type,latitude,longitude,description`
2. Import via `/admin/map-points`

## 🎨 Theme Colors

### Public Homepage (Forest Green)

- Primary: `#16a34a` (forest-600)
- Accent: `#22c55e` (forest-500)

### Dashboard/Admin (Modern Blue)

- Primary: `#2563eb` (blue-600)
- Accent: `#0284c7` (cyan-600)

## 📊 Database Schema

### Key Tables

- **penduduks** - Data penduduk dengan schema baru (nama_lengkap, jenis_kelamin, tanggal_lahir)
- **keluargas** - Data Kartu Keluarga
- **map_points** - Titik lokasi peta interaktif
- **dusuns** - Data dusun (14 dusun)
- **users** - User dengan role-based permissions

## 🔐 Roles & Permissions

| Role           | Permissions | Access Level     |
| -------------- | ----------- | ---------------- |
| Super Admin    | 157         | Full access      |
| Kepala Desa    | 151         | Near full access |
| Pengelola Data | 63          | Data management  |
| Operator       | 56          | Operations       |
| Sekretaris     | 20          | Secretary tasks  |
| Kadus          | 16          | Dusun-specific   |
| Farmer         | 6           | Limited access   |

## 🛠️ Tech Stack

- **Backend:** Laravel 11.38.2, Filament 3, SQLite
- **Frontend:** React 19, Inertia.js, TailwindCSS
- **Maps:** Leaflet, Filament Map Picker
- **Charts:** Chart.js
- **OCR:** Google Cloud Vision API

## 📝 License

MIT License - KKN Sindanganom 2026
