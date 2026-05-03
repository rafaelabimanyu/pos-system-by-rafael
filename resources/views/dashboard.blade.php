@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-slate-800">Ringkasan Hari Ini</h2>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
        + Transaksi Baru
    </button>
</div>

<!-- 4 Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <p class="text-sm font-medium text-slate-500 mb-1">Pendapatan</p>
        <h3 class="text-2xl font-bold text-slate-800">Rp 4.500.000</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <p class="text-sm font-medium text-slate-500 mb-1">Total Transaksi</p>
        <h3 class="text-2xl font-bold text-slate-800">124</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <p class="text-sm font-medium text-slate-500 mb-1">Produk Terjual</p>
        <h3 class="text-2xl font-bold text-slate-800">432 Item</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <p class="text-sm font-medium text-slate-500 mb-1">Rata-rata Transaksi</p>
        <h3 class="text-2xl font-bold text-slate-800">Rp 36.290</h3>
    </div>
</div>
@endsection
