<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Tiysa POS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!-- Menambahkan bg-slate-950 agar dasar background benar-benar gelap -->

<body class="antialiased bg-slate-950 text-slate-200 min-h-screen flex flex-col items-center justify-center p-6">

    {{-- Background Glow Decoration dengan opacity yang dikurangi agar tidak menabrak teks --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <!-- Overlay gelap tipis agar konten di atasnya selalu terbaca -->
        <div class="absolute inset-0 bg-slate-950/40 z-0"></div>
        <div
            class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand-600/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-600/5 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/3">
        </div>
    </div>

    <div class="w-full max-w-[440px] relative z-10">
        {{-- Header Logo & Title --}}
        <div class="text-center mb-10">
            <img src="/logo/image.png" alt="Logo Tiysa POS" class="h-20 w-auto mx-auto mb-6">
            <!-- Menggunakan text-white murni agar kontras maksimal -->
            <h1 class="text-4xl font-extrabold text-white tracking-tight drop-shadow-sm">Tiysa POS</h1>
            <p class="text-slate-300 mt-2 font-medium">Masuk ke sistem point of sale</p>
        </div>

        {{-- Main Login Card - Dipergelap agar input field terlihat menonjol --}}
        <div
            class="bg-slate-900/80 backdrop-blur-2xl border border-slate-800 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-10 mb-8">
            @if($errors->any())
                <div
                    class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm flex items-center gap-3 animate-pulse">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-200 mb-2 ml-1">Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-400 transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <!-- Input diperjelas warnanya -->
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            placeholder="admin@tiysapos.com"
                            class="block w-full pl-11 pr-4 py-4 bg-slate-800/80 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 outline-none transition-all duration-200">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-200 mb-2 ml-1">Password</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand-400 transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                            class="block w-full pl-11 pr-4 py-4 bg-slate-800/80 border border-slate-700 rounded-2xl text-white placeholder-slate-500 focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 outline-none transition-all duration-200">
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember"
                            class="w-5 h-5 bg-slate-800 border-slate-700 rounded-lg text-brand-500 focus:ring-brand-500 focus:ring-offset-0 transition-all cursor-pointer">
                        <span class="text-sm text-slate-300 group-hover:text-white transition-colors">Ingat saya di
                            perangkat ini</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-2xl transition-all shadow-[0_10px_20px_rgba(79,70,229,0.3)] hover:shadow-brand-500/40 active:scale-[0.98] cursor-pointer">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    <span>Masuk Sekarang</span>
                </button>
            </form>
        </div>

        {{-- Updated Demo Accounts Card - Menggunakan Background Solid agar Teks Terbaca --}}
        <div class="bg-slate-900/60 border border-slate-800/50 rounded-[2rem] p-6 shadow-xl backdrop-blur-md">
            <div class="flex items-center gap-3 mb-5">
                <div class="p-1.5 bg-brand-500/20 rounded-lg">
                    <i data-lucide="key-round" class="w-4 h-4 text-brand-400"></i>
                </div>
                <h2 class="text-xs font-bold text-slate-200 uppercase tracking-[0.15em]">Akun Demo Tersedia</h2>
            </div>

            <div class="grid grid-cols-1 gap-3">
                {{-- Admin Account --}}
                <div
                    class="flex items-center justify-between p-4 rounded-2xl bg-slate-800/60 border border-slate-700/50 group hover:border-brand-500/50 transition-all">
                    <div class="flex flex-col">
                        <span
                            class="text-[10px] font-black text-brand-400 uppercase tracking-wider mb-1">Administrator</span>
                        <span class="text-sm text-white font-medium">admin@tiysapos.com</span>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        <span class="text-[9px] text-slate-500 uppercase font-bold">Password</span>
                        <span class="text-xs font-mono text-slate-300 group-hover:text-white">password</span>
                    </div>
                </div>

                {{-- Cashier Account --}}
                <div
                    class="flex items-center justify-between p-4 rounded-2xl bg-slate-800/60 border border-slate-700/50 group hover:border-emerald-500/50 transition-all">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-wider mb-1">Kasir</span>
                        <span class="text-sm text-white font-medium">senja@tiysapos.com</span>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        <span class="text-[9px] text-slate-500 uppercase font-bold">Password</span>
                        <span class="text-xs font-mono text-slate-300 group-hover:text-white">password</span>
                    </div>
                </div>
            </div>

            <p
                class="text-[11px] text-slate-400 mt-5 text-center font-medium bg-slate-800/40 py-2 rounded-xl border border-slate-700/30">
                Lainnya: <span class="text-slate-200">Muthia, Melani, Dorkas, Araxsa</span>
            </p>
        </div>

        <p class="text-center text-[11px] text-slate-500 mt-10 tracking-widest uppercase font-bold opacity-80">
            &copy; {{ date('Y') }} <span class="text-brand-400">Tiysa POS</span> • Precise & Powerful
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>