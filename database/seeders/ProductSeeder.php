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
        $smartphones = Category::create(['nama' => 'Smartphone & Tablet']);
        $laptops = Category::create(['nama' => 'Laptop & Computer']);
        $audio = Category::create(['nama' => 'Audio & Accessories']);
        $gadgets = Category::create(['nama' => 'Gadget & Wearables']);

        // Smartphone & Tablet
        $smartphoneProducts = [
            ['nama' => 'iPhone 15 Pro 256GB', 'harga_beli' => 18000000, 'harga' => 20000000, 'stok' => 10],
            ['nama' => 'Samsung Galaxy S24 Ultra', 'harga_beli' => 19000000, 'harga' => 21000000, 'stok' => 8],
            ['nama' => 'iPad Air 5th Gen', 'harga_beli' => 9000000, 'harga' => 10500000, 'stok' => 15],
            ['nama' => 'Xiaomi 14 Pro', 'harga_beli' => 12000000, 'harga' => 13500000, 'stok' => 12],
            ['nama' => 'Samsung Galaxy Tab S9', 'harga_beli' => 11000000, 'harga' => 12500000, 'stok' => 10],
        ];

        foreach ($smartphoneProducts as $p) {
            $smartphones->products()->create($p);
        }

        // Laptop & Computer
        $laptopProducts = [
            ['nama' => 'MacBook Pro M3 14-inch', 'harga_beli' => 28000000, 'harga' => 31000000, 'stok' => 5],
            ['nama' => 'Asus ROG Zephyrus G14', 'harga_beli' => 25000000, 'harga' => 27500000, 'stok' => 7],
            ['nama' => 'Lenovo ThinkPad X1 Carbon', 'harga_beli' => 22000000, 'harga' => 24500000, 'stok' => 6],
            ['nama' => 'Dell XPS 15', 'harga_beli' => 30000000, 'harga' => 33000000, 'stok' => 5],
            ['nama' => 'Acer Swift 3 OLED', 'harga_beli' => 12000000, 'harga' => 13500000, 'stok' => 15],
            ['nama' => 'HP Spectre x360', 'harga_beli' => 21000000, 'harga' => 23500000, 'stok' => 8],
        ];

        foreach ($laptopProducts as $p) {
            $laptops->products()->create($p);
        }

        // Audio & Accessories
        $audioProducts = [
            ['nama' => 'AirPods Pro Gen 2', 'harga_beli' => 3200000, 'harga' => 3800000, 'stok' => 20],
            ['nama' => 'Sony WH-1000XM5', 'harga_beli' => 5000000, 'harga' => 5800000, 'stok' => 12],
            ['nama' => 'Jabra Elite 85t', 'harga_beli' => 2500000, 'harga' => 3000000, 'stok' => 15],
            ['nama' => 'Logitech MX Master 3S', 'harga_beli' => 1500000, 'harga' => 1800000, 'stok' => 25],
            ['nama' => 'Keychron K2 Mechanical Keyboard', 'harga_beli' => 1200000, 'harga' => 1500000, 'stok' => 18],
        ];

        foreach ($audioProducts as $p) {
            $audio->products()->create($p);
        }

        // Gadget & Wearables
        $gadgetProducts = [
            ['nama' => 'Apple Watch Series 9', 'harga_beli' => 6500000, 'harga' => 7500000, 'stok' => 15],
            ['nama' => 'Garmin Fenix 7', 'harga_beli' => 11000000, 'harga' => 12500000, 'stok' => 10],
            ['nama' => 'Samsung Galaxy Watch 6', 'harga_beli' => 4500000, 'harga' => 5200000, 'stok' => 20],
            ['nama' => 'DJI Mini 3 Pro', 'harga_beli' => 12000000, 'harga' => 13500000, 'stok' => 8],
        ];

        foreach ($gadgetProducts as $p) {
            $gadgets->products()->create($p);
        }
    }
}
