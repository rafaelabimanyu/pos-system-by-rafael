<div align="center">
  <img src="https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/shopping-bag.svg" width="80" alt="Tiysa POS Logo">
  
  # 🚀 Tiysa POS
  
  **Sistem Point of Sale (POS) Generasi Terbaru**

  [![Laravel 11](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
  [![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
</div>

---

## 🌌 Visi Proyek

**Tiysa POS** adalah sistem Point of Sale (POS) generasi terbaru yang dirancang dengan fokus utama pada efisiensi transaksi, antarmuka *dark mode* yang elegan, dan performa tinggi. Dibangun untuk memberikan pengalaman pengguna yang profesional, futuristik, dan responsif.

![Tiysa POS Dashboard](image_fc4e18.png)

---

## ⚡ Fitur Utama

- 🔐 **Modern Auth UI**: Antarmuka login dengan desain kaca (*glassmorphism*) yang elegan dan *glowing effects*.
- 🛡️ **RBAC (Role-Based Access Control)**: Pemisahan hak akses yang jelas antara *Administrator* dan *Kasir*.
- 📱 **Responsive Design**: Tampilan yang menyesuaikan dengan mulus di berbagai perangkat (Desktop, Tablet, Mobile).
- 🛒 **Manajemen Transaksi Cepat**: Alur kerja kasir yang dioptimalkan untuk kecepatan dan kemudahan.
- 🎨 **Dark Mode Penuh**: Mengurangi kelelahan mata sekaligus memberikan tampilan premium berkat integrasi *Tailwind CSS*.

---

## 🛠️ Tech Stack

| Kategori | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Core** | Laravel 11 (PHP 8.2+) | *Framework backend* modern, aman, dan berkinerja tinggi. |
| **Frontend** | Tailwind CSS | *Utility-first* CSS untuk gaya yang kustom dan implementasi *Dark Mode*. |
| **Icons** | Lucide Icons | Kumpulan ikon yang minimalis, modern, dan ringan. |
| **Asset Bundler** | Vite | *Build tool* generasi berikutnya untuk proses *bundling* instan. |
| **Database** | MySQL / MariaDB | Relasional database yang kuat dan dapat diandalkan. |

---

## 🔑 Kredensial Akun Demo

Sistem dilengkapi dengan data *dummy* agar Anda bisa langsung mencoba fitur-fiturnya. Semua akun menggunakan sandi *default* yang sama.

| Peran (*Role*) | Alamat Email | Keterangan |
| :--- | :--- | :--- |
| **Administrator** | `admin@tiysapos.com` | Akses penuh ke sistem, manajemen produk & staf. |
| **Kasir** | `senja@tiysapos.com` | Kasir 1 |
| **Kasir** | `muthia@tiysapos.com` | Kasir 2 |
| **Kasir** | `melani@tiysapos.com` | Kasir 3 |
| **Kasir** | `dorkas@tiysapos.com` | Kasir 4 |
| **Kasir** | `araxsa@tiysapos.com` | Kasir 5 |

> **🗝️ Default Password:** `password` (untuk semua akun di atas).

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menginstal dan menjalankan **Tiysa POS** di lingkungan lokal Anda.

### 1. Kloning Repositori
```bash
git clone https://github.com/username/tiysapos.git
cd tiysapos
```

### 2. Instalasi Dependensi PHP & Node.js
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env` lalu sesuaikan kredensial database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi & Seeding Database
Perintah ini akan membuat struktur tabel baru dan mengisinya dengan data *dummy* Minimarket beserta akun demo.
```bash
php artisan migrate:fresh --seed
```

### 5. Kompilasi Aset Frontend (Vite)
```bash
npm run dev
# atau untuk build produksi: npm run build
```

### 6. Jalankan Local Server
Buka terminal baru dan jalankan:
```bash
php artisan serve
```
Aplikasi dapat diakses di: `http://localhost:8000`

---

<div align="center">
  <p>Dibuat dengan 💜 untuk efisiensi transaksi kelas dunia.</p>
</div>
