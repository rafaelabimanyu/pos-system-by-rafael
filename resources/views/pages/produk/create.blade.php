@extends('layouts.master')

@section('title', 'Tambah Produk')

@section('page-header')
<div class="flex items-center gap-4">
    <x-button variant="ghost" icon="arrow-left" size="sm" href="{{ route('produk.index') }}">Kembali</x-button>
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Produk</h1>
        <p class="text-slate-500 mt-0.5 text-sm">Tambahkan produk baru ke inventaris.</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <x-card title="Informasi Produk" icon="package">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Produk <span class="text-red-400">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                    class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:shadow-sm transition-all"
                    placeholder="Contoh: Es Kopi Susu">
                @error('nama') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Harga & Stok --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="harga" class="block text-sm font-medium text-slate-700 mb-1.5">Harga (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0"
                        class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                        placeholder="20000">
                    @error('harga') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="stok" class="block text-sm font-medium text-slate-700 mb-1.5">Stok <span class="text-red-400">*</span></label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" required min="0"
                        class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                        placeholder="100">
                    @error('stok') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Kategori --}}
            <div>
                <label for="kategori_id" class="block text-sm font-medium text-slate-700 mb-1.5">Kategori <span class="text-red-400">*</span></label>
                <select name="kategori_id" id="kategori_id" required
                    class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Gambar --}}
            <div x-data="{ preview: null }">
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Gambar Produk</label>
                <div class="flex items-start gap-4">
                    {{-- Preview --}}
                    <div class="w-24 h-24 bg-white border border-slate-300 rounded-xl flex items-center justify-center overflow-hidden shrink-0">
                        <template x-if="preview">
                            <img :src="preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!preview">
                            <i data-lucide="image" class="w-8 h-8 text-slate-600"></i>
                        </template>
                    </div>
                    <div class="flex-1">
                        <label class="block w-full cursor-pointer">
                            <input type="file" name="gambar" accept="image/*" class="hidden"
                                @change="const file = $event.target.files[0]; if(file) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(file); }">
                            <div class="flex items-center justify-center gap-2 px-4 py-3 bg-white border border-dashed border-slate-300 rounded-xl text-sm text-slate-500 hover:text-slate-800 hover:border-brand-500/50 transition-all">
                                <i data-lucide="upload" class="w-4 h-4"></i>
                                <span>Pilih gambar</span>
                            </div>
                        </label>
                        <p class="text-xs text-slate-500 mt-1.5">JPG, PNG, WebP. Maks 2MB.</p>
                        @error('gambar') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-200">
                <x-button variant="secondary" href="{{ route('produk.index') }}">Batal</x-button>
                <x-button variant="primary" icon="save" type="submit">Simpan Produk</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
