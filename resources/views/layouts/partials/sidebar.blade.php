{{-- Sidebar Navigation --}}
<aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-sm">

    {{-- Logo Section --}}
    <div class="flex items-center gap-3 px-5 h-16 border-b border-slate-200 shrink-0 bg-white">
        <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-sm">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">{{ \App\Models\Setting::get('store_name', 'Tiysa POS') }}</h1>
            <p class="text-[10px] text-slate-500 -mt-0.5 font-medium uppercase tracking-wider">Point of Sale</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto bg-white">
        {{-- Main Menu --}}
        <p class="px-3 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Menu Utama</p>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('dashboard') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i data-lucide="layout-dashboard" class="sidebar-icon w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i>
            <span class="sidebar-text transition-colors">Dashboard</span>
        </a>
        @endif

        <a href="{{ route('pos') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('pos') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i data-lucide="monitor" class="sidebar-icon w-5 h-5 {{ request()->routeIs('pos') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i>
            <span class="sidebar-text transition-colors">POS Kasir</span>
            <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-blue-100 text-blue-600 rounded-full">LIVE</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('produk.index') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('produk.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i data-lucide="package" class="sidebar-icon w-5 h-5 {{ request()->routeIs('produk.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i>
            <span class="sidebar-text transition-colors">Produk</span>
        </a>

        {{-- Reports --}}
        <p class="px-3 mt-6 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Laporan & Data</p>

        <a href="{{ route('laporan') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('laporan') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i data-lucide="bar-chart-3" class="sidebar-icon w-5 h-5 {{ request()->routeIs('laporan') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i>
            <span class="sidebar-text transition-colors">Laporan</span>
        </a>

        {{-- Settings --}}
        <p class="px-3 mt-6 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Sistem</p>

        <a href="{{ route('users') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('users') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i data-lucide="users" class="sidebar-icon w-5 h-5 {{ request()->routeIs('users') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i>
            <span class="sidebar-text transition-colors">User Management</span>
        </a>

        <a href="{{ route('pengaturan') }}"
           class="sidebar-link group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 {{ request()->routeIs('pengaturan') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i data-lucide="settings" class="sidebar-icon w-5 h-5 {{ request()->routeIs('pengaturan') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }} transition-colors"></i>
            <span class="sidebar-text transition-colors">Pengaturan</span>
        </a>
        @endif
    </nav>

    {{-- Sidebar Footer --}}
    <div class="p-3 border-t border-slate-200 shrink-0 bg-white">
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 bg-emerald-500 rounded-full pulse-dot"></div>
                <span class="text-xs font-medium text-emerald-600">Online</span>
            </div>
            <p class="text-xs text-slate-800 font-bold">{{ auth()->user()->name }}</p>
            <p class="text-[10px] text-slate-500 capitalize">{{ auth()->user()->role }}</p>
        </div>
    </div>
</aside>
