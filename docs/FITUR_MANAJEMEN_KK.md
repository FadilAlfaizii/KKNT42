# Fitur Manajemen Data Keluarga dan Penduduk

## 📋 Overview

Fitur ini memungkinkan perangkat desa untuk mengelola data Kartu Keluarga (KK) dan anggota penduduk secara manual melalui admin panel, tanpa harus melalui proses ekstraksi PDF.

## ✨ Fitur yang Ditambahkan

### 1. **CRUD Kartu Keluarga**

Akses: **Admin Panel → Kependudukan → Keluarga (KK)**

#### Fitur Dasar:

- ✅ **Create**: Tambah KK baru secara manual
- ✅ **Read**: Lihat detail KK dan daftar anggota
- ✅ **Update**: Edit data KK (alamat, status, dll)
- ✅ **Delete**: Hapus KK (khusus SuperAdmin & Kades)

#### Quick Actions (di Table):

1. **👁️ View**: Lihat detail KK
2. **✏️ Edit**: Edit data KK
3. **➕ Tambah Anggota**: Langsung ke form tambah penduduk dengan KK pre-selected
4. **👤 Ganti Kepala KK**:
    - Pilih anggota keluarga yang sudah ada
    - Otomatis update kepala keluarga
    - Dengan konfirmasi

---

### 2. **CRUD Penduduk**

Akses: **Admin Panel → Kependudukan → Penduduk**

#### Fitur Dasar:

- ✅ **Create**: Tambah penduduk baru ke KK tertentu
- ✅ **Read**: Lihat detail penduduk
- ✅ **Update**: Edit data penduduk
- ✅ **Delete**: Hapus penduduk (khusus SuperAdmin & Kades)

#### Quick Actions (di Table):

1. **👁️ View**: Lihat detail penduduk
2. **✏️ Edit**: Edit data penduduk
3. **🔄 Pindah KK**:
    - Pindahkan anggota ke KK lain
    - Input alasan/keterangan (menikah, pindah, dll)
    - Otomatis update dusun_id mengikuti KK baru
    - History tercatat di keterangan

---

## 🎯 Use Cases & Workflow

### Use Case 1: Tambah Anggota Baru ke KK Existing

**Scenario**: Kelahiran bayi / Anggota baru pindah masuk

**Cara 1 - Dari Menu Keluarga:**

1. Buka **Keluarga (KK)** → Cari KK yang dituju
2. Klik tombol **"Tambah Anggota"** di row KK tersebut
3. Form penduduk terbuka dengan KK sudah otomatis terisi
4. Isi data penduduk → **Simpan**

**Cara 2 - Dari Menu Penduduk:**

1. Buka **Penduduk** → Klik **"New Penduduk"**
2. Pilih KK dari dropdown
3. Isi data penduduk → **Simpan**

**Otomatis:**

- Umur dihitung otomatis dari tanggal lahir
- dusun_id otomatis mengikuti KK

---

### Use Case 2: Anggota Menikah & Buat KK Baru

**Scenario**: Anak menikah dan perlu KK baru

**Step 1 - Buat KK Baru:**

1. **Keluarga (KK)** → **"New Keluarga"**
2. Isi data KK baru (No KK, Kepala Keluarga, Alamat, dll)
3. **Simpan**

**Step 2 - Pindahkan Anggota:**

1. **Penduduk** → Cari anggota yang menikah
2. Klik **"Pindah KK"**
3. Pilih KK baru yang sudah dibuat
4. Isi keterangan: "Menikah - membuat KK baru"
5. **Konfirmasi**

**Hasil:**

- Anggota berpindah ke KK baru
- History tercatat di kolom keterangan
- KK lama jumlah anggotanya berkurang

---

### Use Case 3: Ganti Kepala Keluarga

**Scenario**: Kepala keluarga meninggal / berganti

**Workflow:**

1. **Keluarga (KK)** → Cari KK yang perlu diganti kepala
2. Klik **"Ganti Kepala KK"**
3. Pilih anggota keluarga dari dropdown (hanya anggota di KK itu)
4. **Konfirmasi**

**Hasil:**

- Field `kepala_keluarga` di table keluargas ter-update
- Notifikasi sukses muncul

**Note:**

- Ini hanya mengubah nama di field `kepala_keluarga`
- Untuk mengubah `status_dalam_keluarga` penduduk, edit manual di menu Penduduk

---

### Use Case 4: Anggota Pindah Alamat ke RT/RW Lain

**Scenario**: Anggota keluarga pindah ke KK tetangga/saudara

**Workflow:**

1. **Penduduk** → Cari anggota yang pindah
2. Klik **"Pindah KK"**
3. Search & pilih KK tujuan (bisa filter by RT/RW)
4. Isi keterangan: "Pindah alamat ke rumah saudara"
5. **Konfirmasi**

**Otomatis:**

- `keluarga_id` berubah ke KK baru
- `dusun_id` ikut berubah sesuai KK baru
- History tercatat dengan timestamp

---

### Use Case 5: Update Data Penduduk (Perubahan Status)

**Scenario**: Update pekerjaan, pendidikan, status kawin, dll

**Workflow:**

1. **Penduduk** → Cari penduduk
2. Klik **icon Edit (✏️)**
3. Update field yang perlu diubah:
    - Pekerjaan: Dari "Pelajar" → "Karyawan Swasta"
    - Status Kawin: Dari "Belum Kawin" → "Kawin"
    - Pendidikan: Update ke jenjang baru
4. **Simpan**

**Otomatis:**

- Jika tanggal lahir diubah, umur auto-recalculate

---

## 🔐 Permission & Access Control

### Role-Based Access:

| Action          | SuperAdmin | Kades | Sekdes | Kadus           |
| --------------- | ---------- | ----- | ------ | --------------- |
| View List       | ✅         | ✅    | ✅     | ✅ (dusun only) |
| View Detail     | ✅         | ✅    | ✅     | ✅ (dusun only) |
| Create          | ✅         | ✅    | ✅     | ✅              |
| Edit            | ✅         | ✅    | ✅     | ✅              |
| Delete          | ✅         | ✅    | ❌     | ❌              |
| Bulk Delete     | ✅         | ✅    | ❌     | ❌              |
| Tambah Anggota  | ✅         | ✅    | ✅     | ✅              |
| Pindah KK       | ✅         | ✅    | ✅     | ✅              |
| Ganti Kepala KK | ✅         | ✅    | ✅     | ✅              |

### Dusun-Based Filtering:

- **Kadus**: Hanya bisa akses data dusunnya sendiri
- **SuperAdmin/Kades/Sekdes**: Akses semua dusun

---

## 📝 Field Auto-Calculation

### Penduduk Model:

1. **Umur**: Auto-calculate dari `tanggal_lahir`
    - Saat Create: Dihitung di `CreatePenduduk::mutateFormDataBeforeCreate()`
    - Saat Edit: Dihitung di `EditPenduduk::mutateFormDataBeforeSave()`

2. **Dusun ID**: Auto-sync dengan KK
    - Via `reactive()` form field
    - Saat pindah KK: Otomatis ikut KK baru

---

## 🎨 UI/UX Enhancements

### Table Actions:

```
[View 👁️] [Edit ✏️] [Tambah Anggota ➕] [Ganti Kepala 👤]
```

### Modal Confirmations:

- **Pindah KK**: Form dengan alasan + confirmation
- **Ganti Kepala**: Dropdown + confirmation
- **Delete**: Standard Filament confirmation

### Notifications:

- ✅ Success: "Berhasil pindah KK"
- ✅ Success: "Kepala keluarga diperbarui"
- ❌ Error: Jika ada masalah

---

## 🔄 History Tracking

### Keterangan Field:

Setiap perubahan penting tercatat di `keterangan` field dengan format:

```
[History Lama] | Pindah dari KK 1234567890 ke 0987654321 (Menikah) pada 02/02/2026
```

**Contoh Real:**

```
Pindah dari KK 3201234567890123 ke 3201234567890456 (Menikah - membuat KK baru) pada 02/02/2026
```

---

## 🚀 Quick Reference

### Keyboard Shortcuts (Filament Default):

- `Ctrl/Cmd + K`: Global search
- `Ctrl/Cmd + /`: Command palette
- `/`: Focus search bar

### Best Practices:

1. **Selalu isi keterangan** saat pindah KK (untuk audit trail)
2. **Cek jumlah anggota** setelah mutasi KK
3. **Backup data** sebelum bulk delete
4. **Verifikasi NIK** untuk hindari duplikasi

---

## 🛠️ Technical Details

### Modified Files:

1. `app/Filament/Resources/KeluargaResource.php`
    - Enable create/edit
    - Add quick actions: Tambah Anggota, Ganti Kepala

2. `app/Filament/Resources/PendudukResource.php`
    - Enable create/edit
    - Add quick action: Pindah KK
    - Default keluarga_id from URL query

3. `app/Filament/Resources/KeluargaResource/Pages/CreateKeluarga.php`
    - Standard create page

4. `app/Filament/Resources/KeluargaResource/Pages/EditKeluarga.php`
    - Standard edit page

5. `app/Filament/Resources/PendudukResource/Pages/CreatePenduduk.php`
    - Auto-calculate umur
    - Handle redirect from Keluarga

6. `app/Filament/Resources/PendudukResource/Pages/EditPenduduk.php`
    - Auto-update umur on edit

### Database Relations:

```
Keluarga (1) ─── (Many) Penduduk
    │
    └─── penduduks(): HasMany

Penduduk (Many) ─── (1) Keluarga
    │
    └─── keluarga(): BelongsTo
```

---

## 📞 Support & Troubleshooting

### Common Issues:

**Q: Tombol "Tambah Anggota" tidak muncul di KK?**
A: Pastikan user punya akses edit (bukan read-only)

**Q: Tidak bisa pindah KK antar dusun?**
A: User Kadus dibatasi ke dusun sendiri. Gunakan akun SuperAdmin/Kades untuk lintas dusun.

**Q: Umur tidak ter-update otomatis?**
A: Umur hanya di-calculate saat save. Untuk update massal, gunakan console command.

**Q: History keterangan terlalu panjang?**
A: Field `keterangan` type TEXT bisa tampung banyak. Pertimbangkan migrasi ke table terpisah jika perlu audit lengkap.

---

## 🎉 Summary

Fitur ini memberikan **fleksibilitas penuh** untuk perangkat desa mengelola data kependudukan tanpa harus selalu melalui ekstraksi PDF. Cocok untuk:

- ✅ Perubahan data cepat (pekerjaan, status kawin)
- ✅ Mutasi anggota keluarga (menikah, pindah)
- ✅ Update rutin (kelahiran, kematian)
- ✅ Koreksi data existing

**Workflow tetap smooth** dan **history tercatat** untuk audit.
