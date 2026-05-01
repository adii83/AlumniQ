# AlumniQ — Sistem Pelacak Alumni

![Badge](https://img.shields.io/badge/ALUMNIQ-SISTEM%20PELACAK%20ALUMNI-c08457?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Tailwind](https://img.shields.io/badge/Tailwind-CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge)

**Dasbor administrasi untuk pelacakan dan validasi data alumni secara terpusat.**
Dibangun dengan PHP/MySQL sebagai backend dan vanilla JavaScript sebagai frontend.

---


## Fitur

- Login admin dengan autentikasi via `.env`
- Tampilan tabel alumni dengan pagination (30 baris per halaman)
- Filter berdasarkan status: Terverifikasi, Perlu Diverifikasi, Data Masih Samar
- Pencarian berdasarkan nama, NIM, fakultas, atau program studi
- Modal detail alumni dengan informasi lengkap (kontak, pekerjaan, sosial media)
- Tombol validasi untuk mengubah status alumni menjadi Terverifikasi
- Ringkasan statistik distribusi status alumni
- Mendukung dua mode: Backend (PHP/MySQL) dan fallback CSV lokal

---

## Struktur Proyek

```
UI-D4/
├── index.html
├── favicon.svg
├── .env
├── .gitignore
│
├── api/
│   ├── .htaccess
│   ├── db.php
│   ├── alumni.php
│   ├── stats.php
│   ├── validate.php
│   └── login.php
│
├── css/
│   └── styles.css
│
├── js/
│   ├── config.js
│   ├── utils.js
│   └── app.js
│
└── sql/           (diabaikan Git, hanya untuk setup lokal)
    ├── create_table.sql
    ├── import_csv.py
    └── PANDUAN_IMPORT_CSV.txt
```

---

## Persyaratan

- PHP >= 8.0
- MySQL / MariaDB
- Web server dengan dukungan `.htaccess` (Apache / LiteSpeed)
- Koneksi internet untuk CDN (Tailwind CSS, Font Awesome, Google Fonts)

---

## Instalasi

### 1. Buat Database

Jalankan file `sql/create_table.sql` di phpMyAdmin atau MySQL CLI untuk membuat tabel `alumni`.

### 2. Import Data

Ikuti panduan di `sql/PANDUAN_IMPORT_CSV.txt` untuk mengimpor data alumni dari file CSV.

### 3. Konfigurasi `.env`

Salin dan sesuaikan file `.env` di root proyek:

```env
DB_HOST=localhost
DB_NAME=nama_database
DB_USER=nama_user
DB_PASS=password_database

ADMIN_USERNAME=admin
ADMIN_PASSWORD=password_anda

APP_NAME=AlumniQ
```

> File `.env` tidak boleh diupload ke repository. Sudah terdaftar di `.gitignore`.

### 4. Upload ke Hosting

Upload semua file kecuali folder `sql/` dan file `data.csv` ke direktori public hosting.

Pastikan file `.htaccess` di dalam folder `api/` ikut terupload agar endpoint PHP tidak bisa diakses langsung dari browser.

---

## Konfigurasi Aplikasi

Pengaturan utama berada di `js/config.js`:

```js
const CONFIG = {
    ROWS_PER_PAGE: 30,
    API: {
        USE_BACKEND: true,  // false = mode CSV lokal (untuk testing)
        ...
    },
    ...
};
```

Ubah `USE_BACKEND` menjadi `false` untuk mode testing lokal tanpa database.

---

## Struktur Database

Tabel `alumni` memiliki kolom utama:

| Kolom | Keterangan |
|---|---|
| `NIM` | Nomor Induk Mahasiswa (PRIMARY KEY) |
| `Nama Lulusan` | Nama lengkap alumni |
| `Fakultas` | Nama fakultas |
| `Program Studi` | Program studi |
| `Tanggal Lulus` | Tanggal kelulusan |
| `Email` | Alamat email |
| `Nomor HP` | Nomor handphone |
| `Linkedin` | URL profil LinkedIn |
| `Tempat Bekerja (Present)` | Tempat kerja saat ini |
| `_status` | Status validasi data |
| `_validated_at` | Waktu terakhir divalidasi |

---

## Keamanan

- Kredensial admin disimpan di `.env`, tidak hardcoded di kode
- Folder `api/` diproteksi `.htaccess` agar tidak bisa dibrowse langsung
- Token sesi dibuat acak per login menggunakan `random_bytes(16)`
- File `.env` dan `data.csv` terdaftar di `.gitignore`

---

## Teknologi

| Bagian | Teknologi |
|---|---|
| Frontend | HTML, Vanilla JavaScript, Tailwind CSS (CDN) |
| Styling tambahan | Vanilla CSS (`css/styles.css`) |
| Backend | PHP 8+ |
| Database | MySQL / MariaDB |
| Library JS | PapaParse (CSV), SheetJS (XLSX) |
| Icon | Font Awesome 6 |
| Font | Inter, Playfair Display (Google Fonts) |
