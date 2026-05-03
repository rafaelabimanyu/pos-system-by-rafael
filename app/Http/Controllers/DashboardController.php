<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Stat cards
        $todayRevenue     = Transaction::whereDate('tanggal', $today)->sum('total');
        $todayTransactions = Transaction::whereDate('tanggal', $today)->count();
        $todayItemsSold   = TransactionItem::whereHas('transaction', fn($q) => $q->whereDate('tanggal', $today))->sum('qty');
        $todayAvgTransaction = $todayTransactions > 0 ? round($todayRevenue / $todayTransactions) : 0;

        // Grafik 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Transaction::whereDate('tanggal', $date->toDateString())->sum('total');
            $count   = Transaction::whereDate('tanggal', $date->toDateString())->count();
            $chartData[] = [
                'date'    => $date->translatedFormat('d M'),
                'day'     => $date->translatedFormat('D'),
                'revenue' => $revenue,
                'count'   => $count,
            ];
        }

        // Produk terlaris bulan ini
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(qty * harga) as total_revenue'))
            ->whereHas('transaction', fn($q) => $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        // Transaksi terbaru
        $recentTransactions = Transaction::with('items.product')
            ->latest()
            ->limit(8)
            ->get();

        return view('pages.dashboard', compact(
            'todayRevenue', 'todayTransactions', 'todayItemsSold', 'todayAvgTransaction',
            'chartData', 'topProducts', 'recentTransactions'
        ));
    }
}
