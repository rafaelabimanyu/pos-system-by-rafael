<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shift;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Tampilkan halaman POS dengan produk dari database.
     */
    public function posIndex()
    {
        $products = Product::with('category')->where('stok', '>', 0)->get();
        $categories = $products->pluck('category.nama')->unique()->values();

        // Cek shift aktif
        $activeShift = null;
        if (auth()->check()) {
            $activeShift = Shift::where('user_id', auth()->id())->whereNull('ended_at')->first();
        }

        return view('pages.pos', compact('products', 'categories', 'activeShift'));
    }

    /**
     * Simpan transaksi dari POS (JSON API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.harga'      => 'required|integer|min:0',
            'bayar'              => 'required|integer|min:0',
            'payment_method'     => 'required|in:cash,transfer,qris',
        ]);

        // Cek shift aktif
        $user = auth()->user();
        $activeShift = Shift::where('user_id', $user->id)->whereNull('ended_at')->first();
        if (!$activeShift) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus memulai shift terlebih dahulu.',
            ], 422);
        }

        try {
            $transaction = DB::transaction(function () use ($validated, $user, $activeShift) {
                $subtotal = 0;

                // Validasi stok & hitung subtotal
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    if ($product->stok < $item['qty']) {
                        throw new \Exception("Stok {$product->nama} tidak cukup (sisa: {$product->stok})");
                    }
                    $subtotal += $item['harga'] * $item['qty'];
                }

                $discountGlobal = (float) \App\Models\Setting::get('discount_global', '0');
                $discountAmount = $subtotal * ($discountGlobal / 100);
                $subtotalAfterDiscount = $subtotal - $discountAmount;

                $taxEnabled = \App\Models\Setting::get('tax_enabled', '0') == '1';
                $taxPercentage = (float) \App\Models\Setting::get('tax_percentage', '11');
                $taxAmount = $taxEnabled ? $subtotalAfterDiscount * ($taxPercentage / 100) : 0;

                $total = $subtotalAfterDiscount + $taxAmount;

                // Untuk non-cash, bayar = total (exact)
                $bayar = $validated['payment_method'] !== 'cash' ? $total : $validated['bayar'];

                if ($bayar < $total) {
                    throw new \Exception('Pembayaran kurang dari total');
                }

                // Buat transaksi
                $transaction = Transaction::create([
                    'total'          => $total,
                    'bayar'          => $bayar,
                    'kembalian'      => $bayar - $total,
                    'payment_method' => $validated['payment_method'],
                    'tanggal'        => now()->toDateString(),
                    'user_id'        => $user->id,
                    'shift_id'       => $activeShift->id,
                ]);

                // Buat items & kurangi stok
                foreach ($validated['items'] as $item) {
                    $transaction->items()->create([
                        'product_id' => $item['product_id'],
                        'qty'        => $item['qty'],
                        'harga'      => $item['harga'],
                    ]);
                    Product::where('id', $item['product_id'])->decrement('stok', $item['qty']);
                }

                return $transaction;
            });

            return response()->json([
                'success'        => true,
                'message'        => 'Transaksi berhasil!',
                'transaction_id' => $transaction->id,
                'kembalian'      => $transaction->kembalian,
                'payment_method' => $transaction->payment_method,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
