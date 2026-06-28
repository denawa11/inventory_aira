# Inventory App

Inventory App adalah aplikasi manajemen stok barang berbasis web yang dikembangkan menggunakan **Laravel 12**. Aplikasi ini memudahkan pencatatan keluar masuk barang, manajemen stok, serta import data masal menggunakan file Excel.

## 🌟 Fitur Utama
- **Dashboard Statistik**: Pantau total barang, barang masuk, dan barang keluar secara real-time.
- **Manajemen Barang**: Tambah, edit, dan hapus data barang (Master Data).
- **Import Data**: Mendukung import data massal dari file Excel (`.xlsx`, `.xls`, `.csv`).
- **Transaksi**:
  - **Barang Masuk**: Mencatat stok masuk dengan sistem *rollback* otomatis jika data dihapus.
  - **Barang Keluar**: Mencatat stok keluar dengan peringatan stok minim.
- **Manajemen User**: Tambah, edit user, dan reset password (untuk Administrator).
- **History & Tracking**: Setiap transaksi (masuk/keluar) mencatat siapa admin yang melakukan tindakan tersebut.

---

## 🛠️ Persyaratan Sistem (Prerequisites)
Sebelum menjalankan project ini, pastikan sistem Anda telah menginstall:
- **PHP** >= 8.2
- **Composer** (untuk manajemen dependensi PHP)
- **MySQL** atau MariaDB
- **Laragon / XAMPP** (Sangat disarankan menggunakan Laragon untuk OS Windows)

---

## 🚀 Panduan Setup & Instalasi (Step-by-Step)

Ikuti langkah-langkah di bawah ini untuk menjalankan project dari awal hingga berjalan sempurna di komputer Anda:

### 1. Clone atau Download Project
Buka terminal/CMD dan jalankan perintah:
```bash
git clone <url-repository-anda>
cd inventory-app
```

### 2. Install Dependensi (Composer)
Unduh seluruh library dan package yang dibutuhkan oleh Laravel:
```bash
composer install
```

### 3. Setup File Konfigurasi (.env)
Duplikat file `.env.example` menjadi `.env`. Di terminal/CMD jalankan:
```bash
copy .env.example .env
```
*(Atau Anda bisa copy-paste manual file `.env.example` dan ubah namanya menjadi `.env`)*.

### 4. Generate Application Key
Jalankan perintah ini untuk membuat kunci keamanan aplikasi:
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
1. Buka aplikasi **HeidiSQL** atau **phpMyAdmin** (bawaan Laragon/XAMPP).
2. Buat database baru dengan nama `inventory_app` (atau nama lain sesuai selera).
3. Buka file `.env` di text editor (VS Code, dll), lalu sesuaikan bagian koneksi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 6. Jalankan Migrasi & Seeder Database
Perintah ini akan membuat semua struktur tabel ke dalam database Anda secara otomatis beserta data bawaan (seperti akun Admin default):
```bash
php artisan migrate --seed
```

*(Catatan: Akun default biasanya menggunakan email `admin@admin.com` dengan password `password`, kecuali telah diubah di file DatabaseSeeder).*

### 7. Jalankan Server Lokal
Nyalakan server *development* Laravel:
```bash
php artisan serve
```

Aplikasi Anda sekarang dapat diakses melalui browser di alamat:
**http://localhost:8000** atau **http://127.0.0.1:8000**

---

## 💡 Troubleshooting Umum

- **Class "ZipArchive" not found saat Import Excel**:
  Pastikan ekstensi `zip` di PHP Anda aktif. Jika menggunakan Laragon: Klik Kanan Laragon -> PHP -> Quick Settings -> centang `zip`.
- **Aplikasi terlihat error setelah ditarik (pull) dari Git**:
  Cobalah jalankan `composer install` dan `php artisan optimize:clear`.

---
*Dibuat menggunakan Laravel 12 & Bootstrap 5.*
