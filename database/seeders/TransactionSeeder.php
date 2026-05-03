<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $kasirUsers = \App\Models\User::where('role', 'kasir')->get();
        $paymentMethods = ['cash', 'qris', 'debit'];

        if ($products->isEmpty() || $kasirUsers->isEmpty()) return;

        // Generate 7 hari terakhir data transaksi
        for ($day = 6; $day >= 0; $day--) {
            $date = now()->subDays($day);
            $txCount = rand(5, 15);

            for ($t = 0; $t < $txCount; $t++) {
                $itemCount = rand(1, 8);
                $selectedProducts = $products->random($itemCount);
                $total = 0;
                $items = [];

                foreach ($selectedProducts as $product) {
                    $qty = rand(1, 3);
                    $total += $product->harga * $qty;
                    $items[] = [
                        'product_id' => $product->id,
                        'qty'        => $qty,
                        'harga'      => $product->harga,
                    ];
                }

                $bayar = (int) (ceil($total / 5000) * 5000);
                
                $randomHour = rand(8, 22);
                $randomMinute = rand(0, 59);
                $randomSecond = rand(0, 59);
                $transactionDate = $date->copy()->setTime($randomHour, $randomMinute, $randomSecond);

                $transaction = Transaction::create([
                    'total'          => $total,
                    'bayar'          => $bayar,
                    'kembalian'      => $bayar - $total,
                    'tanggal'        => $transactionDate->toDateString(),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'user_id'        => $kasirUsers->random()->id,
                    'created_at'     => $transactionDate,
                    'updated_at'     => $transactionDate,
                ]);

                foreach ($items as $item) {
                    $transaction->items()->create($item);
                }
            }
        }
    }
}
