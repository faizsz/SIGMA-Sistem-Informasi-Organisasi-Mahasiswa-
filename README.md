# SIGMA — Sistem Informasi Organisasi Mahasiswa

> Platform manajemen UKM terintegrasi untuk Politeknik Negeri Semarang

![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![AdminLTE](https://img.shields.io/badge/AdminLTE-3.2-3C8DBC?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## Tentang Project

SIGMA adalah sistem informasi berbasis web yang dirancang untuk mengelola seluruh aktivitas Unit Kegiatan Mahasiswa (UKM) di Politeknik Negeri Semarang. Sistem ini mengintegrasikan proses pendaftaran anggota, manajemen kegiatan, struktur organisasi, dan dokumentasi rapat dalam satu platform terpusat.
Project ini dikembangkan sebagai bagian dari tugas akhir Program Studi D4 Teknologi Rekayasa Komputer, Politeknik Negeri Semarang.

---

## Fitur Utama

### Mahasiswa
- Melihat daftar UKM dan detail informasi tiap UKM
- Mendaftar UKM melalui proses seleksi 3 tahap
- Upload dokumen persyaratan per tahap
- Memantau status pendaftaran secara realtime
- Melihat timeline kegiatan dan struktur organisasi UKM

### Admin UKM
- Dashboard statistik anggota, kegiatan, dan pendaftar
- Manajemen profil UKM (nama, visi, misi, logo, banner)
- Review dan approval pendaftar per tahap seleksi
- Kelola struktur organisasi, divisi, dan jabatan
- Manajemen timeline kegiatan dan rapat
- Catat notulensi dan dokumentasi rapat
- Atur periode pendaftaran dengan jadwal per tahap

### Super Admin
- Manajemen data mahasiswa
- Manajemen data UKM
- Manajemen user login dan role
- Kelola periode kepengurusan
- Dashboard statistik keseluruhan sistem

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Backend | PHP 8.1 (Native, PDO) |
| Database | MySQL 8.0 |
| Admin UI | AdminLTE 3.2 + Bootstrap 4 |
| Icons | Font Awesome 6.5 |
| Alert | SweetAlert2 |
| Table | DataTables |
| Server | Apache (Laragon) |

---

## Struktur Folder

```
SIGMA/
├── index.html                          # Halaman login (entry point)
├── frontend/
│   ├── public/
│   │   ├── assets/                     # Gambar, logo, banner UKM
│   │   └── uploads/
│   │       └── dokumen_pendaftaran/    # File upload pendaftaran
│   └── src/
│       ├── pages/
│       │   ├── user/                   # Halaman mahasiswa
│       │   ├── admin-ukm/              # Halaman admin UKM
│       │   └── admin/                  # Halaman super admin (AdminLTE)
│       ├── styles/                     # CSS per halaman
│       └── utils/
│           ├── navbar/                 # Komponen navbar
│           └── footer/                 # Komponen footer
└── backend/
    ├── config/
    │   └── config.php                  # Konfigurasi database
    └── controllers/
        ├── mahasiswa/                  # API untuk mahasiswa
        ├── admin-ukm/                  # API untuk admin UKM
        └── admin/                      # API untuk super admin
```

---

## Cara Install Lokal

### Prasyarat
- [Laragon](https://laragon.org/) atau XAMPP
- PHP >= 8.1
- MySQL >= 8.0

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/faizsz/SIGMA-Sistem-Informasi-Organisasi-Mahasiswa-.git
cd SIGMA
```

**2. Setup database**

Buka phpMyAdmin atau MySQL client, buat database baru:
```sql
CREATE DATABASE sigma;
```
Lalu import file SQL:
```
backend/sigma.sql
```

**3. Konfigurasi database**

Copy file konfigurasi:
```bash
cp backend/config/config.example.php backend/config/config.php
```
Buka `backend/config/config.php` dan sesuaikan:
```php
$host     = 'localhost';
$dbname   = 'sigma';
$username = 'root';    // sesuaikan
$password = '';        // sesuaikan
```

**4. Setup Virtual Host di Laragon**

Buka `C:\laragon\etc\apache2\sites-enabled\`, buat file `sigma.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/laragon/www/SIGMA"
    ServerName sigma.test
    <Directory "C:/laragon/www/SIGMA">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Restart Laragon, lalu akses `http://sigma.test`

**5. Buat folder upload**

Pastikan folder berikut ada dan writable:
```
frontend/public/assets/profile/
frontend/public/assets/notulensi/
frontend/public/assets/dokumentasi/
frontend/public/uploads/dokumen_pendaftaran/
```

---

## Akun Demo

| Role | Username | Password |
|---|---|---|
| Mahasiswa | 43323210 | 321 |
| Admin UKM | bem | bem |
| Super Admin | admin | admin |

---

## Author

**Faiz Akmal Nurhakim**
D4 Teknologi Rekayasa Komputer
Politeknik Negeri Semarang

---

## License

Project ini dibuat untuk keperluan akademik.
