{{-- Top Navbar --}}
<header class="sticky top-0 z-30 h-16 bg-dark-800/80 backdrop-blur-xl border-b border-dark-600/50 shrink-0">
    <div class="flex items-center justify-between h-full px-4 md:px-6">
        {{-- Left: Mobile Toggle + Search --}}
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-dark-600 transition-colors">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <div class="hidden md:flex items-center gap-2 bg-dark-700 rounded-xl px-3 py-2 border border-dark-600/50 focus-within:border-brand-500/50 focus-within:shadow-glow transition-all w-72">
                <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
                <input type="text" placeholder="Cari produk, transaksi..." class="bg-transparent text-sm text-slate-300 placeholder-slate-500 outline-none w-full">
                <kbd class="hidden lg:inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-medium text-slate-500 bg-dark-600 rounded-md border border-dark-500">⌘K</kbd>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-2">
            {{-- Quick POS --}}
            <a href="{{ route('pos') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 bg-brand-600 hover:bg-brand-500 text-white text-sm font-medium rounded-xl transition-all hover:shadow-glow">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Transaksi Baru</span>
            </a>

            <div class="w-px h-6 bg-dark-600 mx-1"></div>

            {{-- User Menu --}}
            <div class="relative">
                <button id="user-menu-btn" class="flex items-center gap-3 px-2 py-1.5 rounded-xl hover:bg-dark-600 transition-colors">
                    <div class="w-8 h-8 bg-gradient-to-br {{ auth()->user()->isAdmin() ? 'from-brand-500 to-purple-600' : 'from-emerald-500 to-teal-600' }} rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-glow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-white leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500 leading-tight capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <i data-lucide="chevron-down" class="hidden md:block w-4 h-4 text-slate-500"></i>
                </button>

                {{-- User Dropdown --}}
                <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-dark-700 border border-dark-600/50 rounded-2xl shadow-card overflow-hidden dropdown-enter">
                    <div class="px-4 py-3 border-b border-dark-600/50">
                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-1.5">
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-dark-600/50 transition-colors">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <span>Profil Saya</span>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('pengaturan') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-dark-600/50 transition-colors">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span>Pengaturan</span>
                        </a>
                        @endif
                    </div>
                    <div class="border-t border-dark-600/50 py-1.5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/5 transition-colors cursor-pointer">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
