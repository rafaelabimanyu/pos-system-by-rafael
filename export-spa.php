<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure docs directory exists
if (!is_dir('docs')) {
    mkdir('docs', 0755, true);
}

// Helper to copy directory
function copy_dir($src, $dst) {
    if (!is_dir($src)) return;
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_dir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Helper to fix asset paths
function fixAssets($html) {
    $html = str_replace('http://localhost/', './', $html);
    $html = str_replace('../build/', './build/', $html);
    $html = str_replace('/build/', './build/', $html);
    return $html;
}

$errors = new \Illuminate\Support\ViewErrorBag();
\Illuminate\Support\Facades\View::share('errors', $errors);

// 1. Dashboard
$dashboardData = [
    'todayRevenue' => 0,
    'todayTransactions' => 0,
    'todayItemsSold' => 0,
    'todayAvgTransaction' => 0,
    'topProducts' => collect([]),
    'recentTransactions' => collect([]),
    'chartData' => []
];
$html = view('pages.dashboard', $dashboardData)->render();
file_put_contents('docs/dashboard.html', fixAssets($html));

// 2. POS
$posData = [
    'products' => collect([]), // JS will handle it
    'categories' => collect([]),
    'activeShift' => (object)['id' => 1]
];
$html = view('pages.pos', $posData)->render();
file_put_contents('docs/pos.html', fixAssets($html));

// 3. Produk
$emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
$produkData = [
    'products' => $emptyPaginator,
    'categories' => collect([]),
    'totalProducts' => 0,
    'lowStockProducts' => 0,
    'totalValue' => 0
];
if(view()->exists('pages.produk.index')){
    $html = view('pages.produk.index', $produkData)->render();
    file_put_contents('docs/produk.html', fixAssets($html));
}

// 4. Laporan
$laporanData = [
    'transactions' => $emptyPaginator,
    'stats' => (object)['revenue' => 0, 'count' => 0, 'items' => 0, 'avg' => 0],
    'dates' => ['start' => null, 'end' => null],
    'startDate' => date('Y-m-d'),
    'endDate' => date('Y-m-d'),
    'totalRevenue' => 0,
    'totalTransactions' => 0,
    'totalItemsSold' => 0,
    'avgTransaction' => 0,
    'products' => collect([]),
    'dailyChart' => collect([]),
    'topProducts' => collect([])
];
if(view()->exists('pages.laporan')){
    $html = view('pages.laporan', $laporanData)->render();
    file_put_contents('docs/laporan.html', fixAssets($html));
}

// 5. Users
$usersData = [
    'users' => collect([])
];
if(view()->exists('pages.users')){
    $html = view('pages.users', $usersData)->render();
    file_put_contents('docs/users.html', fixAssets($html));
}

// 6. Pengaturan
$pengaturanData = [
    'settings' => [
        'store_name' => 'Tiysa POS',
        'store_address' => 'Jakarta',
        'store_phone' => '08123456789'
    ]
];
if(view()->exists('pages.pengaturan')){
    $html = view('pages.pengaturan', $pengaturanData)->render();
    file_put_contents('docs/pengaturan.html', fixAssets($html));
}

// 7. Salin index.html (Login)
if (file_exists('index.html')) {
    $indexHtml = file_get_contents('index.html');
    file_put_contents('docs/index.html', fixAssets($indexHtml));
}

// 8. Salin Asset (CSS/JS)
copy_dir('public/build', 'docs/build');
copy_dir('public/js', 'docs/js');

echo "Semua file HTML berhasil di-export ke folder docs/!\n";
