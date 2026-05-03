<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Tiysa POS — Sistem Kasir Modern untuk Bisnis Anda">

    <title>@yield('title', 'Dashboard') — Tiysa POS</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lucide Icons CDN --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="antialiased min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar Overlay (Mobile) --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden backdrop-blur-sm"></div>

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            {{-- Navbar --}}
            @include('layouts.partials.navbar')

            {{-- Page Content --}}
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                {{-- Page Header --}}
                @hasSection('page-header')
                    <div class="mb-6 md:mb-8 animate-fade-in">
                        @yield('page-header')
                    </div>
                @endif

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 flex items-center gap-3 animate-slide-in">
                        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 flex items-center gap-3 animate-slide-in">
                        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Main Content Area --}}
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            <footer class="px-6 py-4 border-t border-dark-700/50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('store_name', 'Tiysa POS') }}. All rights reserved.</p>
                    <p class="flex items-center gap-1.5">
                        Made with <span class="text-red-400">♥</span> for your business
                    </p>
                </div>
            </footer>
        </div>
    </div>

    {{-- Initialize Lucide Icons --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>

    @stack('scripts')
</body>
</html>
