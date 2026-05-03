<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiysa POS - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        @include('components.sidebar')

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- HEADER -->
            @include('components.header')

            <!-- DASHBOARD CONTENT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>
