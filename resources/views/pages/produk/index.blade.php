@extends('layouts.master')

@section('title', 'Produk')

@section('page-header')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Produk</h1>
        <p class="text-slate-500 mt-1">Kelola semua produk dan inventaris toko Anda.</p>
    </div>
    <x-button variant="primary" icon="plus" size="sm" href="{{ route('produk.create') }}">Tambah Produk</x-button>
</div>
@endsection

@section('content')
<x-card :noPadding="true">
    {{-- Filters --}}
    <form method="GET" action="{{ route('produk.index') }}" class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3">
        <div class="flex-1 flex items-center gap-2 bg-white rounded-xl px-3 py-2 border border-slate-300 focus-within:border-brand-500/50 transition-all">
            <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="bg-transparent text-sm text-slate-700 placeholder-slate-500 outline-none w-full">
        </div>
        <select name="kategori" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm text-slate-700 outline-none">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-3 py-2 text-sm text-slate-700 outline-none">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>Stok Rendah</option>
            <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Habis</option>
        </select>
        <button type="submit" class="hidden">Cari</button>
    </form>

    <x-table :headers="['Produk', 'Kategori', 'Harga', 'Stok', 'Status', 'Aksi']">
        @forelse($products as $product)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-10 h-10 rounded-xl object-cover border border-slate-300">
                        @else
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center">
                                <i data-lucide="package" class="w-5 h-5 text-slate-500"></i>
                            </div>
                        @endif
                        <span class="text-sm font-medium text-slate-800">{{ $product->nama }}</span>
                    </div>
                </td>
                <td class="px-5 py-3.5">
                    <x-badge color="brand">{{ $product->category->nama }}</x-badge>
                </td>
                <td class="px-5 py-3.5 text-sm font-medium text-slate-800">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </td>
                <td class="px-5 py-3.5 text-sm {{ $product->stok < 5 ? 'text-red-400 font-semibold' : 'text-slate-700' }}">
                    {{ $product->stok }}
                </td>
                <td class="px-5 py-3.5">
                    @if($product->stok === 0)
                        <x-badge color="red">Habis</x-badge>
                    @elseif($product->stok < 5)
                        <x-badge color="amber">Low Stock</x-badge>
                    @else
                        <x-badge color="emerald">Tersedia</x-badge>
                    @endif
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-1">
                        <x-button variant="ghost" size="sm" icon="pencil" href="{{ route('produk.edit', $product) }}"></x-button>
                        <form action="{{ route('produk.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <x-button variant="ghost" size="sm" icon="trash-2" type="submit"></x-button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center">
                    <div class="flex flex-col items-center text-slate-500">
                        <i data-lucide="package-open" class="w-12 h-12 mb-3 opacity-30"></i>
                        <p class="font-medium">Belum ada produk</p>
                        <p class="text-xs mt-1">Tambahkan produk pertama Anda</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-table>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="px-5 py-4 border-t border-slate-200">
            {{ $products->links('vendor.pagination.tailwind-dark') }}
        </div>
    @endif
</x-card>
@endsection
