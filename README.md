# Kasir Abi (Point of Sale System)

Kasir Abi adalah aplikasi kasir (Point of Sale) berbasis web yang modern, cepat, dan responsif. Dirancang untuk memudahkan pengelolaan transaksi penjualan, manajemen inventaris produk, serta memantau laporan pendapatan secara *real-time* dengan antarmuka pengguna yang elegan dan intuitif.

---

## 🛠️ Tech Stack

Proyek ini dibangun menggunakan teknologi terbaru dan terbaik untuk memastikan performa dan pengalaman pengguna yang optimal:

- **Framework:** [Laravel 11+](https://laravel.com/) (PHP)
- **Reaktivitas:** [Livewire 3](https://livewire.laravel.com/) (Dynamic Interfaces without writing JS)
- **Styling:** [Tailwind CSS v4](https://tailwindcss.com/) (Utility-first framework dengan tema Dark Mode)
- **Database:** [MySQL](https://www.mysql.com/) / MariaDB
- **Visualisasi Data:** [Chart.js](https://www.chartjs.org/) (Untuk grafik statistik pendapatan)

---

## ✨ Fitur Utama

- **📊 Dashboard Statistik:** Menampilkan ringkasan pendapatan hari ini, total transaksi, jumlah produk terjual, dan rata-rata nilai transaksi beserta grafik pendapatan yang interaktif.
- **🛒 POS Kasir (Live):** Antarmuka kasir dinamis menggunakan Livewire yang mendukung pencarian produk secara *real-time* dan kalkulasi harga instan tanpa perlu memuat ulang (refresh) halaman.
- **📦 Manajemen Produk (Inventory):** Pengelolaan data produk secara lengkap (CRUD) meliputi nama barang, stok, harga beli, harga jual, dan kategori.
- **📄 Laporan Penjualan:** Modul rekapitulasi penjualan untuk memantau riwayat transaksi dan pendapatan dalam periode tertentu.
- **👥 User Management (Role-based):** Sistem autentikasi dengan hak akses berbasis *role* (Admin & Kasir) untuk membatasi fitur berdasarkan peran pengguna.

---

## 🚀 Panduan Instalasi (Langkah demi Langkah)

Ikuti langkah-langkah berikut untuk menjalankan proyek Kasir Abi di mesin lokal Anda.

### 1. Clone Repository
```bash
git clone https://github.com/username-anda/kasir-abi.git
cd kasir-abi
```

### 2. Instalasi Dependensi PHP (Composer)
```bash
composer install
```

### 3. Instalasi Dependensi Node.js (NPM)
```bash
npm install
```

### 4. Konfigurasi Environment (.env)
Salin file konfigurasi *environment* dan sesuaikan dengan pengaturan database Anda.
```bash
cp .env.example .env
php artisan key:generate
```
Buka file `.env` dan atur koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kasir_abi
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migrasi Database
Buat tabel dan data awal di database Anda dengan perintah:
```bash
php artisan migrate
```
*(Opsional)* Jika tersedia file seeder untuk data dummy:
```bash
php artisan migrate --seed
```

### 6. Menjalankan Server Lokal
Anda perlu menjalankan server PHP dan Vite (untuk *asset compilation* Tailwind CSS) secara bersamaan. Buka dua terminal terpisah:

**Terminal 1:**
```bash
php artisan serve
```

**Terminal 2:**
```bash
npm run dev
```
Aplikasi kini dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 🗄️ Struktur Database

Aplikasi ini menggunakan beberapa tabel utama untuk menyimpan data:

- `users`: Menyimpan data pengguna aplikasi, dilengkapi kolom `role` ('admin' atau 'kasir') untuk manajemen hak akses.
- `products`: Menyimpan katalog barang, mencakup informasi nama, sisa stok, harga pembelian, dan harga jual.
- `transactions`: Mencatat *header* transaksi penjualan yang berelasi dengan pengguna (kasir), mencakup total harga, metode pembayaran, dan waktu transaksi.
- `transaction_details`: Menyimpan detail setiap barang yang terjual dalam sebuah transaksi (berelasi ke `transactions` dan `products`), mencakup jumlah (qty) dan perhitungan subtotal per item.

---

## 📖 Panduan Penggunaan (Usage Guide)

### 1. Login ke Aplikasi
- Buka `http://localhost:8000/login`
- Gunakan kredensial yang telah dibuat (atau via Seeder). 
  - *Admin* memiliki akses penuh ke Dashboard, Manajemen Produk, Laporan, dan User.
  - *Kasir* umumnya hanya memiliki akses ke menu POS Kasir dan riwayat transaksinya.

### 2. Melakukan Transaksi (POS Kasir)
1. Navigasi ke menu **POS Kasir** di *sidebar*.
2. Gunakan kolom pencarian untuk mencari produk (bisa dengan mengetikkan nama produk, atau menggunakan *barcode scanner* jika sudah diintegrasikan).
3. Klik produk yang muncul untuk memasukkannya ke dalam keranjang (Cart).
4. Sesuaikan *Quantity* (jumlah) barang di keranjang jika diperlukan. Subtotal akan dikalkulasi secara otomatis berkat Livewire.
5. Masukkan metode pembayaran, lalu klik tombol **Proses Pembayaran** (atau tombol *Checkout*) untuk menyelesaikan transaksi.

---

## 🏗️ Standar Kode & Arsitektur

- **Blade Components:** Proyek ini memecah antarmuka (UI) menjadi beberapa *Blade Components* yang *reusable* (seperti `sidebar`, `header`, `card`), sehingga kode HTML lebih bersih dan mudah dirawat.
- **Livewire Reaktivitas:** Halaman **POS Kasir** tidak menggunakan framework JavaScript terpisah (seperti Vue/React), melainkan bergantung pada **Laravel Livewire**. Ini memungkinkan interaksi dinamis (seperti update *cart*, pencarian produk, dan hitung total) langsung ditangani oleh logika PHP di sisi *backend* tanpa perlu *page reload*, memberikan rasa aplikasi SPA (Single Page Application).
- **Tailwind CSS Utility-First:** Seluruh komponen UI didesain khusus dengan kelas utilitas Tailwind CSS, menerapkan konsep desain responsif serta menggunakan pola warna *Dark Mode* untuk estetika aplikasi yang modern.

---
*Dibuat untuk sistem POS Kasir Abi. Silakan modifikasi dokumentasi ini menyesuaikan perkembangan proyek Anda.*
