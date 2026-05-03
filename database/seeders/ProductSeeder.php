<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Buat kategori
        $kebutuhanPokok = Category::create(['nama' => 'Kebutuhan Pokok']);
        $makananMinuman = Category::create(['nama' => 'Makanan & Minuman']);
        $perawatanPribadi = Category::create(['nama' => 'Perawatan Pribadi']);
        $rumahTangga = Category::create(['nama' => 'Rumah Tangga']);

        // Kebutuhan Pokok
        $kebutuhanPokokProducts = [
            ['nama' => 'Beras Premium 5kg', 'harga_beli' => 60000, 'harga' => 65000, 'stok' => 50],
            ['nama' => 'Minyak Goreng 2L', 'harga_beli' => 30000, 'harga' => 34000, 'stok' => 40],
            ['nama' => 'Gula Pasir 1kg', 'harga_beli' => 14000, 'harga' => 16000, 'stok' => 100],
            ['nama' => 'Telur Ayam 1kg', 'harga_beli' => 26000, 'harga' => 29000, 'stok' => 30],
            ['nama' => 'Garam Dapur', 'harga_beli' => 2000, 'harga' => 3500, 'stok' => 200],
        ];

        foreach ($kebutuhanPokokProducts as $p) {
            $kebutuhanPokok->products()->create($p);
        }

        // Makanan & Minuman
        $makananMinumanProducts = [
            ['nama' => 'Mie Instan Goreng', 'harga_beli' => 2500, 'harga' => 3000, 'stok' => 200],
            ['nama' => 'Mie Instan Rebus', 'harga_beli' => 2500, 'harga' => 3000, 'stok' => 200],
            ['nama' => 'Air Mineral 600ml', 'harga_beli' => 2000, 'harga' => 3500, 'stok' => 150],
            ['nama' => 'Minuman Bersoda', 'harga_beli' => 4500, 'harga' => 6000, 'stok' => 80],
            ['nama' => 'Keripik Kentang', 'harga_beli' => 8000, 'harga' => 10500, 'stok' => 60],
            ['nama' => 'Cokelat Batangan', 'harga_beli' => 12000, 'harga' => 15000, 'stok' => 45],
            ['nama' => 'Susu UHT 250ml', 'harga_beli' => 4500, 'harga' => 6000, 'stok' => 120],
        ];

        foreach ($makananMinumanProducts as $p) {
            $makananMinuman->products()->create($p);
        }

        // Perawatan Pribadi
        $perawatanPribadiProducts = [
            ['nama' => 'Sabun Mandi Cair', 'harga_beli' => 18000, 'harga' => 22000, 'stok' => 40],
            ['nama' => 'Shampo Anti-Dandruff', 'harga_beli' => 20000, 'harga' => 24000, 'stok' => 35],
            ['nama' => 'Pasta Gigi', 'harga_beli' => 10000, 'harga' => 13000, 'stok' => 60],
            ['nama' => 'Sikat Gigi', 'harga_beli' => 5000, 'harga' => 8000, 'stok' => 80],
            ['nama' => 'Sabun Cuci Muka', 'harga_beli' => 25000, 'harga' => 30000, 'stok' => 25],
        ];

        foreach ($perawatanPribadiProducts as $p) {
            $perawatanPribadi->products()->create($p);
        }

        // Rumah Tangga
        $rumahTanggaProducts = [
            ['nama' => 'Deterjen Bubuk 800g', 'harga_beli' => 15000, 'harga' => 19000, 'stok' => 50],
            ['nama' => 'Cairan Pencuci Piring', 'harga_beli' => 12000, 'harga' => 15000, 'stok' => 60],
            ['nama' => 'Tisu Wajah', 'harga_beli' => 8000, 'harga' => 11000, 'stok' => 100],
            ['nama' => 'Pembersih Lantai', 'harga_beli' => 10000, 'harga' => 13500, 'stok' => 40],
        ];

        foreach ($rumahTanggaProducts as $p) {
            $rumahTangga->products()->create($p);
        }
    }
}
