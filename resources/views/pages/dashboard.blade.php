@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-header')
<div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-white">Dashboard</h1>
        <div class="mt-2 text-slate-400">
            <p class="font-semibold text-white">{{ \App\Models\Setting::get('store_name', 'Kasir Abi') }}</p>
            <p class="text-sm mt-0.5"><i data-lucide="map-pin" class="w-3.5 h-3.5 inline-block mr-1"></i>{{ \App\Models\Setting::get('store_address', 'Jl. Contoh No. 123, Jakarta') }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <x-button variant="primary" icon="plus" size="sm" href="{{ route('pos') }}">Transaksi Baru</x-button>
    </div>
</div>
@endsection

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5 mb-6">
        <x-stat-card title="Pendapatan Hari Ini" :value="'Rp ' . number_format($todayRevenue, 0, ',', '.')" icon="wallet" color="brand" />
        <x-stat-card title="Total Transaksi" :value="(string) $todayTransactions" icon="shopping-cart" color="emerald" />
        <x-stat-card title="Produk Terjual" :value="(string) $todayItemsSold" icon="package" color="amber" />
        <x-stat-card title="Rata-rata Transaksi" :value="'Rp ' . number_format($todayAvgTransaction, 0, ',', '.')" icon="bar-chart-3" color="purple" />
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">
        {{-- Revenue Chart --}}
        <div class="xl:col-span-2">
            <x-card title="Grafik Pendapatan" subtitle="7 hari terakhir" icon="trending-up">
                <div id="revenue-chart"></div>
            </x-card>
        </div>

        {{-- Top Products --}}
        <div>
            <x-card title="Produk Terlaris" subtitle="Bulan ini" icon="crown">
                @if($topProducts->isEmpty())
                    <div class="flex flex-col items-center py-8 text-slate-500">
                        <i data-lucide="package-open" class="w-10 h-10 mb-2 opacity-30"></i>
                        <p class="text-sm">Belum ada data penjualan</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($topProducts as $i => $item)
                            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-dark-600/50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold shrink-0
                                    {{ $i === 0 ? 'bg-amber-500/10 text-amber-400' : ($i === 1 ? 'bg-slate-400/10 text-slate-400' : ($i === 2 ? 'bg-orange-500/10 text-orange-400' : 'bg-dark-600 text-slate-500')) }}">
                                    {{ $i + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors truncate">
                                        {{ $item->product->nama ?? 'Produk Dihapus' }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ number_format($item->total_qty) }} terjual</p>
                                </div>
                                <p class="text-xs font-medium text-slate-400">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <x-card title="Transaksi Terbaru" subtitle="8 transaksi terakhir" icon="receipt" :noPadding="true">
        <x-slot name="action">
            <x-button variant="ghost" size="sm" icon="arrow-right" href="{{ route('laporan') }}">Lihat Semua</x-button>
        </x-slot>

        <x-table :headers="['ID', 'Tanggal', 'Item', 'Total', 'Bayar', 'Kembalian']">
            @forelse($recentTransactions as $trx)
                <tr class="hover:bg-dark-600/30 transition-colors">
                    <td class="px-5 py-3.5 text-sm font-mono font-medium text-brand-400">#TRX-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-3.5 text-sm text-slate-400">{{ $trx->tanggal->format('d M Y') }}</td>
                    <td class="px-5 py-3.5 text-sm text-slate-400">{{ $trx->items->sum('qty') }} item</td>
                    <td class="px-5 py-3.5 text-sm font-medium text-white">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-sm text-slate-300">Rp {{ number_format($trx->bayar, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-sm text-emerald-400">Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center text-slate-500">
                            <i data-lucide="receipt" class="w-12 h-12 mb-3 opacity-30"></i>
                            <p class="font-medium">Belum ada transaksi</p>
                            <p class="text-xs mt-1">Mulai transaksi pertama di POS</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>
    </x-card>
@endsection

@push('scripts')
{{-- ApexCharts CDN --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);

    const options = {
        series: [{
            name: 'Pendapatan',
            data: chartData.map(d => d.revenue)
        }, {
            name: 'Transaksi',
            data: chartData.map(d => d.count)
        }],
        chart: {
            type: 'area',
            height: 300,
            background: 'transparent',
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
        },
        colors: ['#6366f1', '#34d399'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: chartData.map(d => d.date),
            labels: { style: { colors: '#64748b', fontSize: '12px' } },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: [{
            labels: {
                style: { colors: '#64748b', fontSize: '12px' },
                formatter: v => v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v >= 1000 ? (v/1000)+'rb' : v
            }
        }, {
            opposite: true,
            labels: { style: { colors: '#64748b', fontSize: '12px' } }
        }],
        grid: {
            borderColor: '#242433',
            strokeDashArray: 4,
            padding: { left: 8, right: 8 }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            labels: { colors: '#94a3b8' },
            fontSize: '12px',
            markers: { size: 5, shape: 'circle' },
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val, { seriesIndex }) {
                    return seriesIndex === 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(val) : val + ' transaksi';
                }
            }
        },
        responsive: [{
            breakpoint: 640,
            options: { chart: { height: 220 } }
        }]
    };

    new ApexCharts(document.querySelector('#revenue-chart'), options).render();
});
</script>
@endpush
