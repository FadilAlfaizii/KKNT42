# 📊 Dashboard SID - Modern UI/UX Design

## 🎯 Overview

Dashboard Sistem Informasi Desa (SID) dengan antarmuka yang sangat bersih, minimalis, dan modern. Terinspirasi dari best practices website Indonesia dan internasional.

---

## ✨ Fitur Utama

### 1. **Ringkasan Statistik** - 8 Cards Interaktif
- ✅ Total Penduduk dengan counter animation
- ✅ Jumlah KK (Kepala Keluarga)
- ✅ Wilayah RT/RW
- ✅ APBDes (Anggaran Pendapatan Belanja Desa)
- ✅ Data Laki-Laki & Perempuan
- ✅ Realisasi Anggaran
- ✅ Program Aktif
- ✅ Progress bar animation pada setiap card

### 2. **Grafik Demografi** - Data Visualization
- **Bar Chart**: Distribusi penduduk berdasarkan kelompok umur (0-10, 11-20, ..., 60+)
- **Donut Chart**: Tingkat pendidikan (Tidak Sekolah, SD, SMP, SMA, Diploma, S1, S2/S3)
- Interactive tooltips dengan persentase
- Responsive dan mobile-friendly

### 3. **Transparansi Anggaran (APBDes)**
- **Summary Cards**: Total anggaran, realisasi, dan persentase
- **Bar Chart**: Alokasi per bidang
  - Penyelenggaraan Pemerintahan
  - Pelaksanaan Pembangunan
  - Pembinaan Kemasyarakatan
  - Pemberdayaan Masyarakat
  - Penanggulangan Bencana
  - Operasional
- **Pie Chart**: Perbandingan anggaran vs realisasi vs sisa
- Real-time percentage calculation

### 4. **Peta Wilayah Interaktif**
- Integrasi dengan Leaflet.js & OpenStreetMap
- Custom markers dengan warna-warni sesuai kategori:
  - 🔵 Biru (Primary) - Kantor Desa
  - 🟢 Hijau (Nature) - Tempat Ibadah
  - 🔵 Cyan (Accent) - Pendidikan
  - 🔴 Merah - Kesehatan
  - 🟣 Purple - Fasilitas Umum
- Fullscreen mode
- Legend untuk memudahkan navigasi
- Custom popup dengan detail lokasi
- Circle overlay untuk area desa

### 5. **Quick Access Cards**
- Navigasi cepat ke halaman:
  - Tentang Desa
  - Artikel & Berita
  - Peta Interaktif
  - Statistik Lengkap
- Hover effects dengan lift animation

---

## 🎨 Design System

### Color Palette

#### Primary Blue (Profesional & Terpercaya)
```css
primary-50: #eff6ff
primary-100: #dbeafe
primary-600: #2563eb (Main)
primary-700: #1d4ed8
```

#### Accent Cyan (Modern & Fresh)
```css
accent-50: #f0f9ff
accent-600: #0284c7 (Main)
accent-700: #0369a1
```

#### Nature Green (Alami & Segar)
```css
nature-50: #f0fdf4
nature-100: #dcfce7
nature-600: #16a34a (Main)
nature-700: #15803d
```

### Typography
- **Font Family**: Inter, Manrope, sans-serif
- **Headings**: Bold, 2xl - 5xl
- **Body**: Regular, sm - base
- **Numbers**: Bold untuk emphasis

### Spacing & Layout
- **Container**: max-w-7xl (1280px)
- **Gap**: 4, 6, 12 (spacing system)
- **Border Radius**: 2xl (rounded-2xl) untuk cards
- **Padding**: 4, 6, 8, 12, 16

---

## 📦 Komponen yang Dibuat

### 1. DashboardStats.jsx
**Lokasi**: `resources/js/component/DashboardStats.jsx`

**Features**:
- 8 StatCard dengan color variants (primary, accent, nature, purple)
- Counter animation dari 0 ke nilai target
- Progress bar animation
- Shine effect on hover
- Staggered animation delay (0ms, 100ms, 200ms, ...)

**Props**:
```jsx
<DashboardStats data={{
    totalPenduduk: 5234,
    jumlahKK: 1456,
    jumlahRT: 8,
    jumlahRW: 3,
    laki: 2678,
    perempuan: 2556,
    anggaran: 2450000000,
    realisasi: 1850000000
}} />
```

### 2. DemographyChart.jsx
**Lokasi**: `resources/js/component/DemographyChart.jsx`

**Features**:
- Chart.js & react-chartjs-2 integration
- Bar chart untuk distribusi usia
- Doughnut chart untuk tingkat pendidikan
- Custom tooltips dengan formatting Indonesia
- Responsive height (h-80)

### 3. BudgetChart.jsx
**Lokasi**: `resources/js/component/BudgetChart.jsx`

**Features**:
- 3 Summary cards dengan gradient backgrounds
- Bar chart untuk alokasi per bidang
- Pie chart untuk transparansi
- Custom legends dengan nilai rupiah
- Automatic percentage calculation

### 4. VillageMap.jsx
**Lokasi**: `resources/js/component/VillageMap.jsx`

**Features**:
- React Leaflet integration
- Custom marker icons dengan divIcon
- Fullscreen toggle button
- Legend dengan color coding
- Circle overlay untuk area desa
- Interactive popups
- Navigation hint

### 5. Dashboard.jsx
**Lokasi**: `resources/js/Pages/Dashboard.jsx`

**Features**:
- Hero section dengan gradient background
- Animated floating orbs
- Real-time date & time display
- Section-by-section layout
- Footer dengan copyright
- Navbar integration

---

## 🚀 Installation & Setup

### 1. Dependencies (Sudah Terinstall)
```json
{
    "chart.js": "^4.4.7",
    "react-chartjs-2": "^5.3.0",
    "leaflet": "^1.9.4",
    "react-leaflet": "^5.0.0",
    "lucide-react": "^0.469.0"
}
```

### 2. Build Commands
```bash
# Development mode
npm run dev

# Production build
npm run build
```

### 3. Route Setup
Tambahkan di `routes/web.php`:
```php
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
```

---

## 📱 Responsive Design

### Breakpoints
- **Mobile**: < 640px (sm)
- **Tablet**: 640px - 1024px (md)
- **Desktop**: > 1024px (lg)

### Grid Layouts
```jsx
// Stats: 1 col mobile, 2 cols tablet, 4 cols desktop
grid-cols-1 md:grid-cols-2 lg:grid-cols-4

// Charts: 1 col mobile, 2 cols desktop
grid-cols-1 lg:grid-cols-2

// Quick Access: 1 col mobile, 2 cols tablet, 4 cols desktop
grid-cols-1 md:grid-cols-2 lg:grid-cols-4
```

---

## 🎭 Animations

### Fade In Up
```css
@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(30px); }
    100% { opacity: 1; transform: translateY(0); }
}
```

### Counter Animation
```javascript
// Animasi angka dari 0 ke nilai target
const duration = 2000; // 2 detik
const steps = 60; // 60 frame
```

### Float Animation
```css
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
```

---

## 📊 Data Structure

### Stats Data
```javascript
{
    totalPenduduk: 5234,      // Total penduduk
    jumlahKK: 1456,            // Kepala Keluarga
    jumlahRT: 8,               // Rukun Tetangga
    jumlahRW: 3,               // Rukun Warga
    laki: 2678,                // Laki-laki
    perempuan: 2556,           // Perempuan
    anggaran: 2450000000,      // APBDes (Rp)
    realisasi: 1850000000      // Realisasi (Rp)
}
```

### Demography Data
```javascript
// Usia
['0-10', '11-20', '21-30', '31-40', '41-50', '51-60', '60+']
data: [450, 520, 680, 550, 420, 280, 178]

// Pendidikan
['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'Diploma', 'S1', 'S2/S3']
data: [280, 1250, 980, 1450, 320, 780, 174]
```

### Budget Data
```javascript
// Alokasi per bidang (dalam juta rupiah)
{
    'Penyelenggaraan': 450,
    'Pelaksanaan Pembangunan': 820,
    'Pembinaan Masyarakat': 380,
    'Pemberdayaan': 450,
    'Penanggulangan Bencana': 150,
    'Operasional': 200
}
```

### Map Landmarks
```javascript
[
    { id: 1, name: 'Kantor Desa', position: [-6.234567, 106.789012], type: 'office' },
    { id: 2, name: 'Masjid Al-Ikhlas', position: [-6.235000, 106.788500], type: 'worship' },
    { id: 3, name: 'SDN 01', position: [-6.234000, 106.789500], type: 'education' },
    { id: 4, name: 'Puskesmas Pembantu', position: [-6.235500, 106.789800], type: 'health' },
    { id: 5, name: 'Balai RW 01', position: [-6.233800, 106.788800], type: 'community' }
]
```

---

## 🎨 Design Inspiration

Dashboard ini terinspirasi dari:
- **Pemerintah Indonesia**: Dashboard pemda modern
- **Dribbble**: Aesthetic design trends
- **Apple**: Clean & minimal interface
- **Stripe**: Professional data visualization
- **Linear**: Futuristic blue theme

---

## 🔧 Customization

### Update Koordinat Desa
Edit `VillageMap.jsx`:
```javascript
const desaCenter = [-6.234567, 106.789012]; // Ganti dengan koordinat sebenarnya
```

### Update Data Statistik
Edit `Dashboard.jsx` atau pass props:
```jsx
<DashboardStats data={yourCustomData} />
```

### Change Colors
Edit `tailwind.config.js`:
```javascript
colors: {
    primary: {
        600: '#your-color'
    }
}
```

---

## 📸 Screenshots

### Desktop View
- **Hero Section**: Gradient background dengan floating orbs
- **Stats Cards**: 4 kolom grid dengan animations
- **Charts**: Side-by-side layout
- **Map**: Full width dengan legend

### Mobile View
- **Stats**: 1 kolom stack
- **Charts**: Full width stack
- **Map**: Responsive dengan touch controls

---

## 🐛 Troubleshooting

### Leaflet Markers Tidak Muncul
```bash
# Pastikan Leaflet CSS ter-import
import 'leaflet/dist/leaflet.css';
```

### Charts Tidak Render
```bash
# Register ChartJS components
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement);
```

### Build Errors
```bash
# Clear cache
rm -rf node_modules/.vite
npm run build
```

---

## ✅ Checklist

- [x] Tailwind config dengan nature colors
- [x] DashboardStats dengan 8 cards
- [x] DemographyChart (Bar + Donut)
- [x] BudgetChart (Summary + Bar + Pie)
- [x] VillageMap dengan Leaflet
- [x] Dashboard page dengan full layout
- [x] Responsive design
- [x] Animations & transitions
- [x] Build success

---

## 📚 Tech Stack

- **React** 19.0.0
- **Inertia.js** 2.0.0
- **Chart.js** 4.4.7
- **React Chart.js 2** 5.3.0
- **Leaflet** 1.9.4
- **React Leaflet** 5.0.0
- **Lucide React** 0.469.0
- **Tailwind CSS** 3.4.1
- **Laravel** (Backend)
- **Vite** (Build tool)

---

## 🎉 Result

Dashboard SID modern dengan:
- ✨ UI/UX bersih dan minimalis
- 🎨 Palet biru profesional + hijau alami
- 📊 Visualisasi data interaktif
- 🗺️ Peta wilayah terintegrasi
- 📱 Fully responsive
- ⚡ High performance
- 🎭 Smooth animations

**Status: READY TO USE** ✅

---

## 📞 Support

Untuk pertanyaan dan dukungan:
- Email: info@desasindanganom.id
- Website: www.desasindanganom.id
- GitHub: github.com/desasindanganom/sid

---

**Dibuat dengan ❤️ untuk Desa Sindang Anom**
