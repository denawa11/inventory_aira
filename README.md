# Inventory App

Aplikasi manajemen stok barang berbasis web menggunakan Laravel 12.

## Fitur Utama
- Dashboard statistik
- Manajemen Barang (Master Data)
- Import data massal dari Excel
- Pencatatan transaksi Barang Masuk dan Barang Keluar
- Manajemen User

## Persyaratan Sistem
- XAMPP (dengan PHP >= 8.2)
- Composer
- Git

## Panduan Setup (Menggunakan XAMPP)

### 1. Clone Repository
Pastikan Anda berada di folder `htdocs` pada instalasi XAMPP Anda (misalnya `C:\xampp\htdocs`), lalu jalankan perintah:
```bash
git clone <url-repository-anda> inventory-app
cd inventory-app
```

### 2. Install Dependensi
Jalankan perintah berikut untuk mengunduh semua package yang dibutuhkan:
```bash
composer install
```

### 3. Setup Konfigurasi Environment
Salin file konfigurasi bawaan dan generate key aplikasi:
```bash
copy .env.example .env
php artisan key:generate
```

### 4. Setup Database
- Buka phpMyAdmin (http://localhost/phpmyadmin).
- Buat database baru dengan nama `inventory_app`.
- Buka file `.env` di project Anda dan pastikan konfigurasi database sudah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_app
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migration dan Seeder
Ini adalah langkah paling penting untuk membuat struktur tabel di database beserta akun admin bawaan:
```bash
php artisan migrate --seed
```
*(Akun login default: Email `admin@admin.com`, Password `password`)*

### 6. Akses Aplikasi
Gunakan server bawaan Laravel untuk menjalankan aplikasi:
```bash
php artisan serve
```
Buka browser dan akses URL: **http://localhost:8000**

*(Alternatif tanpa artisan serve, akses langsung melalui XAMPP: http://localhost/inventory-app/public)*

## Troubleshooting XAMPP
Jika Anda mendapati error `Class "ZipArchive" not found` ketika melakukan import Excel:
1. Buka XAMPP Control Panel.
2. Klik tombol **Config** pada modul Apache, pilih `php.ini`.
3. Cari baris `;extension=zip` dan hapus tanda titik koma (`;`) di depannya menjadi `extension=zip`.
4. Simpan file `php.ini` dan **Restart** modul Apache di XAMPP.
