# Kredensial Login Testing - Desa Sindang Anom

## Super Admin

- **Email:** superadmin@starter-kit.com
- **Password:** superadmin
- **Akses:** Semua dusun, semua permission

---

## 🎯 Kadus (14 akun - 1 untuk setiap dusun)

**Format login yang mudah diingat:**

| Dusun    | Email                  | Password |
| -------- | ---------------------- | -------- |
| Dusun 1  | dusun01@sindanganom.id | dusun01  |
| Dusun 2  | dusun02@sindanganom.id | dusun02  |
| Dusun 3  | dusun03@sindanganom.id | dusun03  |
| Dusun 4  | dusun04@sindanganom.id | dusun04  |
| Dusun 5  | dusun05@sindanganom.id | dusun05  |
| Dusun 6  | dusun06@sindanganom.id | dusun06  |
| Dusun 7  | dusun07@sindanganom.id | dusun07  |
| Dusun 8  | dusun08@sindanganom.id | dusun08  |
| Dusun 9  | dusun09@sindanganom.id | dusun09  |
| Dusun 10 | dusun10@sindanganom.id | dusun10  |
| Dusun 11 | dusun11@sindanganom.id | dusun11  |
| Dusun 12 | dusun12@sindanganom.id | dusun12  |
| Dusun 13 | dusun13@sindanganom.id | dusun13  |
| Dusun 14 | dusun14@sindanganom.id | dusun14  |

**Setiap kadus hanya bisa melihat & mengelola data dusunnya sendiri!**

---

## Roles Lainnya

### Kepala Desa (3 akun)

- **Password semua:** `password`
- **Akses:** Semua dusun
- Lihat database untuk email lengkap

### Sekretaris (3 akun)

- **Password semua:** `password`
- **Akses:** Semua dusun (view only + manage artikel)
- Lihat database untuk email lengkap

### Operator (3 akun)

- **Password semua:** `password`
- **Akses:** Semua dusun (full CRUD)
- Lihat database untuk email lengkap

### Pengelola Data (3 akun)

- **Password semua:** `password`
- **Akses:** Semua dusun (full data management)
- Lihat database untuk email lengkap

### Farmer (5 akun)

- **Password semua:** `password`
- **Akses:** Hanya dusun yang diassign (view only)
- Lihat database untuk email lengkap

---

## Total Users: 32 akun

- 1 Super Admin
- 14 Kadus (1 per dusun)
- 3 Kepala Desa
- 3 Sekretaris
- 3 Operator
- 3 Pengelola Data
- 5 Farmer

---

## Query untuk melihat semua user:

```sql
SELECT
    u.fullname,
    u.email,
    r.name as role,
    u.dusun_id,
    d.name as dusun_name
FROM users u
JOIN model_has_roles mhr ON u.id = mhr.model_id
JOIN roles r ON mhr.role_id = r.id
LEFT JOIN dusuns d ON u.dusun_id = d.id
ORDER BY r.name, u.dusun_id;
```
