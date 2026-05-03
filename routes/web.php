<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Kasir Abi - Web Routes
|--------------------------------------------------------------------------
*/

// ─── Guest Routes ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// ─── Authenticated Routes ────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Redirect root
    Route::get('/', fn() => auth()->user()->isKasir()
        ? redirect()->route('pos')
        : redirect()->route('dashboard')
    );

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Admin Only ──────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('produk', ProductController::class)->parameters(['produk' => 'product']);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
        
        // User Management
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        
        Route::get('/pengaturan', [SettingsController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan', [SettingsController::class, 'update'])->name('pengaturan.update');
        Route::post('/pengaturan/password', [SettingsController::class, 'updatePassword'])->name('pengaturan.password');
        Route::get('/pengaturan/backup', [SettingsController::class, 'backup'])->name('pengaturan.backup');
    });

    // ── Admin & Kasir ───────────────────────────────────────────────
    Route::middleware('role:admin,kasir')->group(function () {
        // POS
        Route::get('/pos', [TransactionController::class, 'posIndex'])->name('pos');
        Route::post('/pos/checkout', [TransactionController::class, 'store'])->name('pos.checkout');

        // Shift
        Route::post('/shift/start', [ShiftController::class, 'start'])->name('shift.start');
        Route::post('/shift/close', [ShiftController::class, 'close'])->name('shift.close');
        Route::get('/shift/active', [ShiftController::class, 'active'])->name('shift.active');
    });
});
