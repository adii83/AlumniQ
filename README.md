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

Tabel: `alumni` — Engine: InnoDB, Charset: utf8mb4_unicode_ci

| Kolom                         | Tipe               | Keterangan                                                            |
| ----------------------------- | ------------------ | --------------------------------------------------------------------- |
| `id`                          | INT AUTO_INCREMENT | Primary key internal                                                  |
| `NIM`                         | VARCHAR(50) UNIQUE | Nomor Induk Mahasiswa                                                 |
| `Nama Lulusan`                | VARCHAR(255)       | Nama lengkap alumni                                                   |
| `Fakultas`                    | VARCHAR(255)       | Nama fakultas                                                         |
| `Program Studi`               | VARCHAR(255)       | Program studi                                                         |
| `Tanggal Lulus`               | VARCHAR(100)       | Tanggal kelulusan                                                     |
| `Tahun Masuk`                 | VARCHAR(10)        | Tahun masuk kuliah                                                    |
| `Nomor HP`                    | VARCHAR(100)       | Nomor handphone                                                       |
| `Email`                       | VARCHAR(255)       | Alamat email                                                          |
| `Alamat Bekerja`              | VARCHAR(500)       | Kota/alamat tempat bekerja                                            |
| `Tempat Bekerja (Present)`    | VARCHAR(500)       | Nama perusahaan saat ini                                              |
| `Posisi Jabatan (Present)`    | VARCHAR(255)       | Jabatan saat ini                                                      |
| `Status Pekerjaan (Present)`  | VARCHAR(100)       | Jenis pekerjaan saat ini                                              |
| `Sosmed Kantor (Present)`     | VARCHAR(500)       | LinkedIn perusahaan saat ini                                          |
| `Tempat Bekerja (Terakhir)`   | VARCHAR(500)       | Nama perusahaan terakhir                                              |
| `Posisi Jabatan (Terakhir)`   | VARCHAR(255)       | Jabatan terakhir                                                      |
| `Status Pekerjaan (Terakhir)` | VARCHAR(100)       | Jenis pekerjaan terakhir                                              |
| `Sosmed Kantor (Terakhir)`    | VARCHAR(500)       | LinkedIn perusahaan terakhir                                          |
| `Linkedin`                    | VARCHAR(500)       | URL profil LinkedIn alumni                                            |
| `Instagram`                   | VARCHAR(255)       | Username Instagram                                                    |
| `TikTok`                      | VARCHAR(255)       | Username TikTok                                                       |
| `Facebook`                    | VARCHAR(255)       | Username Facebook                                                     |
| `_status`                     | VARCHAR(50)        | Status validasi (`Tervalidasi` / `Perlu Divalidasi` / `Data Abu-Abu`) |
| `_validated_at`               | DATETIME NULL      | Waktu terakhir divalidasi                                             |
| `created_at`                  | TIMESTAMP          | Waktu data dimasukkan ke sistem                                       |

Index yang dibuat: `idx_nim`, `idx_status`, `idx_nama`, `idx_fakultas`.

---

## Keamanan

- Kredensial admin disimpan di `.env`, tidak hardcoded di kode
- Folder `api/` diproteksi `.htaccess` agar tidak bisa dibrowse langsung
- Token sesi dibuat acak per login menggunakan `random_bytes(16)`
- File `.env` dan `data.csv` terdaftar di `.gitignore`

---

## Teknologi

| Bagian           | Teknologi                                    |
| ---------------- | -------------------------------------------- |
| Frontend         | HTML, Vanilla JavaScript, Tailwind CSS (CDN) |
| Styling tambahan | Vanilla CSS (`css/styles.css`)               |
| Backend          | PHP 8+                                       |
| Database         | MySQL / MariaDB                              |
| Library JS       | PapaParse (CSV), SheetJS (XLSX)              |
| Icon             | Font Awesome 6                               |
| Font             | Inter, Playfair Display (Google Fonts)       |
