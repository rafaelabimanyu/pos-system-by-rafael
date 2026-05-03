@extends('layouts.master')
@section('title', 'POS Kasir')

@section('content')
<div x-data="posApp()" x-init="init()" class="flex flex-col lg:flex-row gap-4 h-[calc(100vh-8rem)]">

    {{-- Toast --}}
    <div x-show="toast.show" x-transition:enter="toast-enter" x-transition:leave="toast-leave"
         class="fixed top-20 right-6 z-50 px-4 py-3 rounded-xl shadow-card flex items-center gap-3 text-sm font-medium"
         :class="toast.type==='success'?'bg-emerald-500/15 border border-emerald-500/30 text-emerald-400':toast.type==='warning'?'bg-amber-500/15 border border-amber-500/30 text-amber-400':'bg-red-500/15 border border-red-500/30 text-red-400'">
        <span x-text="toast.message"></span>
    </div>

    {{-- Mulai Shift Overlay --}}
    <div x-show="!activeShift" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-dark-900/80 backdrop-blur-sm">
        <div class="bg-dark-800 border border-dark-600/50 rounded-2xl p-6 w-full max-w-md shadow-card text-center">
            <div class="w-16 h-16 bg-brand-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="clock" class="w-8 h-8 text-brand-400"></i>
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Mulai Shift Kasir</h2>
            <p class="text-sm text-slate-400 mb-6">Anda harus memulai shift sebelum dapat melakukan transaksi.</p>
            
            <div class="text-left mb-6">
                <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Saldo Awal (Uang di Laci)</label>
                <div class="flex items-center gap-2 bg-dark-700 border border-dark-600/50 rounded-xl px-4 py-3 focus-within:border-brand-500/50 transition-all">
                    <span class="text-slate-500 font-medium">Rp</span>
                    <input x-model.number="openingCash" type="number" min="0" class="bg-transparent text-white outline-none w-full font-semibold">
                </div>
            </div>

            <button @click="startShift()" :disabled="processing" class="w-full py-3.5 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl shadow-glow transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                <i data-lucide="play-circle" class="w-5 h-5"></i>
                <span x-text="processing ? 'Memproses...' : 'Mulai Shift Sekarang'"></span>
            </button>
        </div>
    </div>

    {{-- Tutup Shift Modal --}}
    <div x-show="showCloseShiftModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-dark-900/80 backdrop-blur-sm">
        <div class="bg-dark-800 border border-dark-600/50 rounded-2xl p-6 w-full max-w-md shadow-card">
            <h2 class="text-lg font-bold text-white mb-4">Tutup Shift</h2>
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Saldo Akhir Fisik (Uang di Laci)</label>
                    <div class="flex items-center gap-2 bg-dark-700 border border-dark-600/50 rounded-xl px-4 py-2.5">
                        <span class="text-slate-500">Rp</span>
                        <input x-model.number="closingCash" type="number" min="0" class="bg-transparent text-white outline-none w-full">
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="showCloseShiftModal = false" class="flex-1 py-2.5 bg-dark-700 hover:bg-dark-600 text-slate-300 rounded-xl transition-colors font-medium">Batal</button>
                <button @click="closeShift()" :disabled="processing" class="flex-1 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/20 rounded-xl transition-all font-medium flex justify-center items-center gap-2 disabled:opacity-50">
                    <i data-lucide="power" class="w-4 h-4"></i> Akhiri Shift
                </button>
            </div>
        </div>
    </div>

    {{-- LEFT: Products --}}
    <div class="flex-1 flex flex-col min-h-0">
        {{-- Shift Info Bar --}}
        <div x-show="activeShift" class="flex items-center justify-between bg-dark-700 border border-dark-600/50 rounded-xl p-3 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div>
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Kasir Aktif</p>
                    <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                </div>
                <div class="w-px h-8 bg-dark-600/50 mx-2"></div>
                <div>
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Waktu Mulai</p>
                    <p class="text-sm font-medium text-emerald-400" x-text="activeShift ? formatTime(activeShift.started_at) : ''"></p>
                </div>
            </div>
            <button @click="showCloseShiftModal = true" class="px-3 py-1.5 bg-dark-600 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 text-slate-300 hover:text-red-400 rounded-lg text-xs font-medium transition-all flex items-center gap-1.5">
                <i data-lucide="power" class="w-3.5 h-3.5"></i> Tutup Shift
            </button>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1 flex items-center gap-2 bg-dark-700 rounded-xl px-4 py-3 border border-dark-600/50 focus-within:border-brand-500/50 transition-all">
                <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
                <input x-model="search" type="text" placeholder="Cari produk..." class="bg-transparent text-sm text-slate-300 placeholder-slate-500 outline-none w-full">
            </div>
            <div class="flex gap-2 flex-wrap">
                <template x-for="cat in allCategories" :key="cat">
                    <button @click="activeCategory=cat" class="px-4 py-2.5 text-sm font-medium rounded-xl transition-all cursor-pointer" :class="activeCategory===cat?'bg-brand-600 text-white shadow-glow':'bg-dark-700 text-slate-400 hover:text-white border border-dark-600/50'">
                        <span x-text="cat"></span>
                    </button>
                </template>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto pr-1">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                <template x-for="p in filteredProducts" :key="p.id">
                    <button @click="addToCart(p)" class="bg-dark-700 border border-dark-600/50 rounded-2xl p-4 text-left hover:border-brand-500/30 hover:shadow-glow transition-all group cursor-pointer relative overflow-hidden">
                        <div x-show="getCartQty(p.id)>0" class="absolute top-2 right-2 w-6 h-6 bg-brand-600 rounded-full flex items-center justify-center text-[11px] font-bold text-white shadow-glow"><span x-text="getCartQty(p.id)"></span></div>
                        <template x-if="p.gambar">
                            <img :src="'/storage/'+p.gambar" class="w-14 h-14 rounded-xl object-cover mb-3 group-hover:scale-110 transition-transform">
                        </template>
                        <template x-if="!p.gambar">
                            <div class="w-14 h-14 bg-dark-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform"><i data-lucide="package" class="w-6 h-6 text-slate-500"></i></div>
                        </template>
                        <h3 class="text-sm font-semibold text-white truncate group-hover:text-brand-300 transition-colors" x-text="p.name"></h3>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="p.category"></p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-sm font-bold text-brand-400" x-text="formatRp(p.price)"></p>
                            <p class="text-[10px] text-slate-500" x-text="'Stok: '+p.stok"></p>
                        </div>
                    </button>
                </template>
            </div>
            <div x-show="filteredProducts.length===0" class="flex flex-col items-center justify-center py-20 text-slate-500">
                <i data-lucide="search-x" class="w-12 h-12 mb-3 opacity-40"></i>
                <p class="text-sm">Produk tidak ditemukan</p>
            </div>
        </div>
    </div>

    {{-- RIGHT: Cart --}}
    <div class="w-full lg:w-[400px] shrink-0 flex flex-col bg-dark-700 border border-dark-600/50 rounded-2xl shadow-soft overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-dark-600/40">
            <div class="flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-brand-400"></i>
                <h3 class="font-semibold text-white">Keranjang</h3>
                <span x-show="cart.length>0" class="px-2 py-0.5 text-[10px] font-bold bg-brand-500/20 text-brand-400 rounded-full" x-text="totalItems"></span>
            </div>
            <button x-show="cart.length>0" @click="voidTransaction()" class="text-xs text-red-400 hover:text-red-300 font-medium cursor-pointer">Void / Reset</button>
        </div>
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            <template x-if="cart.length===0">
                <div class="flex flex-col items-center justify-center h-full text-slate-500 py-12">
                    <i data-lucide="shopping-bag" class="w-12 h-12 mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Keranjang kosong</p>
                    <p class="text-xs mt-1">Klik produk untuk menambahkan</p>
                </div>
            </template>
            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center gap-3 p-3 bg-dark-600/40 rounded-xl group cart-item-enter hover:bg-dark-600/60 transition-colors">
                    <div class="w-10 h-10 bg-dark-600 rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="package" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate" x-text="item.name"></p>
                        <p class="text-xs text-slate-500" x-text="formatRp(item.price)"></p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click="decrementQty(item.id)" class="w-7 h-7 rounded-lg bg-dark-500 hover:bg-dark-400 text-slate-400 hover:text-white flex items-center justify-center text-sm cursor-pointer">−</button>
                        <span class="w-8 text-center text-sm font-semibold text-white" x-text="item.qty" :id="'qty-'+item.id"></span>
                        <button @click="incrementQty(item.id)" class="w-7 h-7 rounded-lg bg-dark-500 hover:bg-dark-400 text-slate-400 hover:text-white flex items-center justify-center text-sm cursor-pointer">+</button>
                    </div>
                    <p class="text-sm font-semibold text-white w-[85px] text-right" x-text="formatRp(item.price*item.qty)"></p>
                    <button @click="removeItem(item.id)" class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-300 cursor-pointer ml-1"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            </template>
        </div>
        <div class="border-t border-dark-600/40 p-4 space-y-2.5" x-show="cart.length>0" x-transition>
            <div class="flex justify-between text-sm"><span class="text-slate-500">Subtotal</span><span class="text-slate-300" x-text="formatRp(subtotal)"></span></div>
            <div x-show="discountGlobal > 0" class="flex justify-between text-sm"><span class="text-amber-500">Diskon (<span x-text="discountGlobal"></span>%)</span><span class="text-amber-400" x-text="'- ' + formatRp(discountAmount)"></span></div>
            <div x-show="taxEnabled" class="flex justify-between text-sm"><span class="text-slate-500">Pajak (<span x-text="taxPercentage"></span>%)</span><span class="text-slate-300" x-text="'+ ' + formatRp(taxAmount)"></span></div>
            <div class="h-px bg-dark-600/50"></div>
            <div class="flex justify-between items-center"><span class="text-base font-semibold text-white">Total</span><span class="text-xl font-bold text-white" x-text="formatRp(total)"></span></div>
            <div class="mt-3 p-3 bg-dark-800/70 rounded-xl border border-dark-600/50 space-y-3">
                <label class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pembayaran</label>
                <div class="flex items-center gap-2 bg-dark-700 rounded-xl px-3 py-2.5 border border-dark-600/50 focus-within:border-brand-500/50 transition-all">
                    <span class="text-sm text-slate-500 font-medium">Rp</span>
                    <input x-model.number="payAmount" type="number" placeholder="0" min="0" class="bg-transparent text-lg font-bold text-white outline-none w-full payment-input">
                </div>
                <div class="grid grid-cols-4 gap-1.5">
                    <template x-for="amt in quickAmounts" :key="amt">
                        <button @click="payAmount=amt" class="py-1.5 text-xs font-medium rounded-lg bg-dark-600 hover:bg-dark-500 text-slate-400 hover:text-white border border-dark-500 transition-colors cursor-pointer" x-text="formatShort(amt)"></button>
                    </template>
                </div>
                <div x-show="payAmount>0" class="flex justify-between items-center p-2.5 rounded-lg" :class="change>=0?'bg-emerald-500/10 border border-emerald-500/20':'bg-red-500/10 border border-red-500/20'">
                    <span class="text-xs font-medium" :class="change>=0?'text-emerald-400':'text-red-400'" x-text="change>=0?'Kembalian':'Kurang'"></span>
                    <span class="text-sm font-bold" :class="change>=0?'text-emerald-400':'text-red-400'" x-text="formatRp(Math.abs(change))"></span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <button @click="holdTransaction()" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/20 rounded-xl text-sm font-medium transition-all cursor-pointer"><i data-lucide="pause-circle" class="w-4 h-4"></i> Hold</button>
                <button @click="processPayment()" :disabled="!canPay || processing" class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all cursor-pointer" :class="canPay&&!processing?'bg-brand-600 hover:bg-brand-500 text-white shadow-glow':'bg-dark-600 text-slate-500 cursor-not-allowed'">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> <span x-text="processing?'Memproses...':'Bayar'"></span>
                </button>
            </div>
        </div>
        <div x-show="heldTransactions.length>0" class="border-t border-dark-600/40 px-4 py-3">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Ditahan (<span x-text="heldTransactions.length"></span>)</p>
            <div class="space-y-1.5 max-h-28 overflow-y-auto">
                <template x-for="(held,idx) in heldTransactions" :key="idx">
                    <button @click="resumeHeld(idx)" class="w-full flex items-center justify-between p-2.5 bg-dark-600/40 hover:bg-dark-600/70 rounded-lg text-xs transition-colors cursor-pointer group">
                        <div class="flex items-center gap-2"><i data-lucide="clock" class="w-3.5 h-3.5 text-amber-400"></i><span class="text-slate-300" x-text="held.items+' item'"></span></div>
                        <span class="font-medium text-white" x-text="formatRp(held.total)"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function posApp() {
    return {
        search: '', activeCategory: 'Semua', cart: [], payAmount: 0,
        heldTransactions: [], processing: false,
        toast: { show: false, message: '', type: 'success' },
        activeShift: @json($activeShift),
        openingCash: 0, closingCash: 0, showCloseShiftModal: false,

        // Products from database
        @php
            $mappedProducts = $products->map(fn($p) => [
                'id' => $p->id, 'name' => $p->nama, 'price' => $p->harga,
                'category' => $p->category->nama, 'stok' => $p->stok,
                'gambar' => $p->gambar,
            ]);
        @endphp
        products: @json($mappedProducts),

        dbCategories: @json($categories),

        // Settings
        taxEnabled: {{ \App\Models\Setting::get('tax_enabled', '0') == '1' ? 'true' : 'false' }},
        taxPercentage: {{ (float) \App\Models\Setting::get('tax_percentage', '11') }},
        discountGlobal: {{ (float) \App\Models\Setting::get('discount_global', '0') }},

        get allCategories() { return ['Semua', ...this.dbCategories]; },

        init() {
            this.$watch('cart', () => { this.$nextTick(() => lucide.createIcons()); }, { deep: true });
            this.$watch('heldTransactions', () => { this.$nextTick(() => lucide.createIcons()); }, { deep: true });
        },

        get filteredProducts() {
            return this.products.filter(p => {
                const matchCat = this.activeCategory === 'Semua' || p.category === this.activeCategory;
                const matchSearch = !this.search || p.name.toLowerCase().includes(this.search.toLowerCase());
                return matchCat && matchSearch;
            });
        },
        get subtotal() { return this.cart.reduce((s, i) => s + i.price * i.qty, 0); },
        get discountAmount() { return this.subtotal * (this.discountGlobal / 100); },
        get subtotalAfterDiscount() { return this.subtotal - this.discountAmount; },
        get taxAmount() { return this.taxEnabled ? this.subtotalAfterDiscount * (this.taxPercentage / 100) : 0; },
        get total() { return this.subtotalAfterDiscount + this.taxAmount; },
        get totalItems() { return this.cart.reduce((s, i) => s + i.qty, 0); },
        get change() { return this.payAmount - this.total; },
        get canPay() { return this.activeShift && this.cart.length > 0 && this.payAmount >= this.total && this.total > 0; },
        get quickAmounts() {
            if (this.total <= 0) return [10000, 20000, 50000, 100000];
            const t = this.total, r = v => Math.ceil(v/1000)*1000;
            return [...new Set([r(t), r(t)+5000, r(t)+10000, Math.ceil(t/50000)*50000])].slice(0,4);
        },

        getCartQty(id) { const i = this.cart.find(x => x.id === id); return i ? i.qty : 0; },

        addToCart(product) {
            const e = this.cart.find(i => i.id === product.id);
            if (e) { e.qty++; this.animateQty(product.id); }
            else { this.cart.push({ ...product, qty: 1 }); }
        },
        incrementQty(id) { const i = this.cart.find(x => x.id === id); if (i) { i.qty++; this.animateQty(id); } },
        decrementQty(id) { const i = this.cart.find(x => x.id === id); if (i) { i.qty > 1 ? (i.qty--, this.animateQty(id)) : this.removeItem(id); } },
        removeItem(id) { this.cart = this.cart.filter(i => i.id !== id); },
        animateQty(id) { const el = document.getElementById('qty-'+id); if (el) { el.classList.remove('qty-bump'); void el.offsetWidth; el.classList.add('qty-bump'); } },

        voidTransaction() { this.cart = []; this.payAmount = 0; this.showToast('Transaksi direset', 'warning'); },

        holdTransaction() {
            if (!this.cart.length) return;
            this.heldTransactions.push({ cart: JSON.parse(JSON.stringify(this.cart)), items: this.totalItems, total: this.total });
            this.cart = []; this.payAmount = 0;
            this.showToast('Transaksi ditahan', 'success');
        },
        resumeHeld(idx) {
            if (this.cart.length) this.holdTransaction();
            const h = this.heldTransactions.splice(idx, 1)[0];
            this.cart = h.cart; this.payAmount = 0;
            this.showToast('Transaksi dilanjutkan', 'success');
        },

        formatTime(val) {
            if(!val) return '';
            if(val.length <= 5) return val; // already H:i format from AJAX
            const d = new Date(val);
            return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
        },

        async startShift() {
            this.processing = true;
            try {
                const res = await fetch('{{ route("shift.start") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ opening_cash: this.openingCash })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.showToast(data.message, 'success');
                    this.activeShift = data.shift;
                } else {
                    const errorMsg = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal memulai shift');
                    this.showToast(errorMsg, 'error');
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan jaringan', 'error');
            }
            this.processing = false;
        },

        async closeShift() {
            this.processing = true;
            try {
                const res = await fetch('{{ route("shift.close") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ closing_cash: this.closingCash, notes: '' })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.showToast(data.message, 'success');
                    this.activeShift = null;
                    this.showCloseShiftModal = false;
                    this.openingCash = 0;
                    this.closingCash = 0;
                    alert(`SHIFT DITUTUP\n\nTotal Pendapatan: Rp ${new Intl.NumberFormat('id-ID').format(data.recap.total_revenue)}\nSelisih Kas: Rp ${new Intl.NumberFormat('id-ID').format(data.recap.difference)}`);
                } else {
                    const errorMsg = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal menutup shift');
                    this.showToast(errorMsg, 'error');
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan jaringan', 'error');
            }
            this.processing = false;
        },

        async processPayment() {
            if (!this.canPay || this.processing) return;
            this.processing = true;
            try {
                const res = await fetch('{{ route("pos.checkout") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content 
                    },
                    body: JSON.stringify({
                        items: this.cart.map(i => ({ product_id: i.id, qty: i.qty, harga: i.price })),
                        bayar: this.payAmount,
                        payment_method: 'cash'
                    })
                });
                
                const data = await res.json();
                
                if (res.ok && data.success) {
                    this.showToast('Pembayaran berhasil! Kembalian: ' + this.formatRp(data.kembalian), 'success');
                    // Update stok lokal
                    this.cart.forEach(ci => {
                        const p = this.products.find(x => x.id === ci.id);
                        if (p) p.stok -= ci.qty;
                    });
                    this.cart = []; this.payAmount = 0;
                } else {
                    // Handle Laravel Validation errors or custom backend errors
                    const errorMsg = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal memproses');
                    this.showToast(errorMsg, 'error');
                }
            } catch (e) { 
                console.error(e);
                this.showToast('Terjadi kesalahan: ' + e.message, 'error'); 
            }
            this.processing = false;
        },

        formatRp(v) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(v); },
        formatShort(v) { return v >= 1000000 ? (v/1000000)+'jt' : v >= 1000 ? (v/1000)+'rb' : v; },
        showToast(msg, type='success') { this.toast = { show: true, message: msg, type }; setTimeout(() => this.toast.show = false, 2500); },
    };
}
</script>
@endpush
