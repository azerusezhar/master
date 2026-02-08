<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Master Data (Template)
    Route::resource('master', MasterDataController::class)->parameters([
        'master' => 'master'
    ]);

    // CRUD Transaksi Multi-Item
    Route::resource('transaksi', TransaksiController::class);

    // Laporan & PDF
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/transaksi-pdf', [LaporanController::class, 'transaksiPdf'])->name('laporan.transaksi-pdf');
    Route::get('/laporan/nota/{transaksi}', [LaporanController::class, 'notaPdf'])->name('laporan.nota');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Hanya admin
});

Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    // Admin ATAU Petugas
});

require __DIR__.'/auth.php';
