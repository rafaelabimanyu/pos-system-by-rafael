@extends('layouts.master')

@section('title', 'Laporan')

@section('page-header')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Laporan Penjualan</h1>
        <p class="text-slate-500 mt-1">Analisis penjualan dan performa bisnis Anda.</p>
    </div>
    <div class="flex items-center gap-2">
        <x-button variant="secondary" icon="file-text" size="sm" href="{{ route('laporan.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}">Export PDF</x-button>
        <x-button variant="secondary" icon="table" size="sm" href="{{ route('laporan.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}">Export Excel</x-button>
    </div>
</div>
@endsection

@section('content')
    {{-- Date Filter --}}
    <form method="GET" action="{{ route('laporan') }}" class="mb-6">
        <x-card>
            <div class="flex flex-col sm:flex-row items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                <x-button variant="primary" icon="search" type="submit">Filter</x-button>
            </div>
        </x-card>
    </form>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Pendapatan" :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')" icon="wallet" color="brand" />
        <x-stat-card title="Total Transaksi" :value="(string) $totalTransactions" icon="receipt" color="emerald" />
        <x-stat-card title="Produk Terjual" :value="(string) $totalItemsSold" icon="package" color="amber" />
        <x-stat-card title="Rata-rata / Transaksi" :value="'Rp ' . number_format($avgTransaction, 0, ',', '.')" icon="calculator" color="purple" />
    </div>

    {{-- Daily Revenue Chart --}}
    <div class="mb-6">
        <x-card title="Pendapatan Harian" subtitle="Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}" icon="trending-up">
            @if($dailyChart->isEmpty())
                <div class="flex flex-col items-center py-12 text-slate-500">
                    <i data-lucide="bar-chart-3" class="w-12 h-12 mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Belum ada data pada periode ini</p>
                </div>
            @else
                <div id="laporan-chart"></div>
            @endif
        </x-card>
    </div>

    {{-- Transactions Table --}}
    <x-card title="Riwayat Transaksi" icon="list" :noPadding="true">
        <x-table :headers="['ID Transaksi', 'Tanggal', 'Detail Produk', 'Qty', 'Total', 'Bayar', 'Kembalian']">
            @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 text-sm font-mono font-medium text-blue-600">
                        #TRX-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-500">
                        {{ $trx->tanggal->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="space-y-0.5">
                            @foreach($trx->items as $item)
                                <p class="text-xs text-slate-500">
                                    <span class="text-slate-700">{{ $item->product->nama ?? '—' }}</span>
                                    <span class="text-slate-500">× {{ $item->qty }}</span>
                                </p>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-500">
                        {{ $trx->items->sum('qty') }} item
                    </td>
                    <td class="px-5 py-3.5 text-sm font-medium text-slate-800">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-700">
                        Rp {{ number_format($trx->bayar, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-sm text-emerald-400">
                        Rp {{ number_format($trx->kembalian, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center text-slate-500">
                            <i data-lucide="receipt" class="w-12 h-12 mb-3 opacity-30"></i>
                            <p class="font-medium">Tidak ada transaksi pada periode ini</p>
                            <p class="text-xs mt-1">Coba ubah filter tanggal</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $transactions->links('vendor.pagination.tailwind-dark') }}
            </div>
        @endif
    </x-card>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartEl = document.querySelector('#laporan-chart');
    if (!chartEl) return;

    const dailyData = @json($dailyChart);

    new ApexCharts(chartEl, {
        series: [{
            name: 'Pendapatan',
            type: 'area',
            data: dailyData.map(d => d.revenue)
        }, {
            name: 'Transaksi',
            type: 'column',
            data: dailyData.map(d => d.count)
        }],
        chart: {
            height: 320,
            background: 'transparent',
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
        },
        colors: ['#6366f1', '#34d399'],
        fill: {
            type: ['gradient', 'solid'],
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] },
            opacity: [1, 0.85],
        },
        stroke: { curve: 'smooth', width: [2.5, 0] },
        plotOptions: {
            bar: { borderRadius: 4, columnWidth: '40%' }
        },
        dataLabels: { enabled: false },
        xaxis: {
            categories: dailyData.map(d => {
                const dt = new Date(d.date);
                return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            }),
            labels: { style: { colors: '#64748b', fontSize: '11px' } },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: [{
            labels: {
                style: { colors: '#64748b', fontSize: '11px' },
                formatter: v => v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : v >= 1000 ? Math.round(v/1000)+'rb' : v
            }
        }, {
            opposite: true,
            labels: { style: { colors: '#64748b', fontSize: '11px' } }
        }],
        grid: { borderColor: '#242433', strokeDashArray: 4 },
        legend: {
            position: 'top', horizontalAlign: 'right',
            labels: { colors: '#94a3b8' }, fontSize: '12px',
            markers: { size: 5 },
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: (val, { seriesIndex }) =>
                    seriesIndex === 0 ? 'Rp ' + new Intl.NumberFormat('id-ID').format(val) : val + ' trx'
            }
        },
    }).render();
});
</script>
@endpush
