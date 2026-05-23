<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    
    // Buku: semua user yang login bisa lihat index
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    // Member - riwayat pinjaman sendiri
    Route::get('/riwayat-pinjaman', [App\Http\Controllers\MemberController::class, 'index'])->name('member.riwayat');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        // Kategori CRUD
        Route::resource('kategori', KategoriController::class)->except(['show']);

        Route::get('/buku/import', [BukuController::class, 'importForm'])->name('buku.import.form');
        Route::post('/buku/import', [BukuController::class, 'import'])->name('buku.import');
        Route::get('/buku/template', [BukuController::class, 'downloadTemplate'])->name('buku.template');

        // Buku CRUD (admin tambah/edit/hapus)
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');

        // User management
        Route::resource('users', UserController::class)->except(['show']);

        // Peminjaman
        Route::get('/pinjam', [PinjamController::class, 'index'])->name('pinjam.index');
        Route::get('/pinjam/create', [PinjamController::class, 'create'])->name('pinjam.create');
        Route::post('/pinjam', [PinjamController::class, 'store'])->name('pinjam.store');
        Route::get('/pinjam/{pinjam}', [PinjamController::class, 'show'])->name('pinjam.show');

        // Pengembalian
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
        Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
        Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show'])->name('pengembalian.show');
        Route::put('/pengembalian/{pengembalian}/lunas', [PengembalianController::class, 'tandaiLunas'])->name('pengembalian.lunas');
        Route::put('/pengembalian/detail/{detail}/penggantian', [PengembalianController::class, 'updatePenggantian'])->name('pengembalian.penggantian');

        // Laporan
        Route::get('/laporan/pinjam', [LaporanController::class, 'pinjam'])->name('laporan.pinjam');
        Route::get('/laporan/pinjam/pdf', [LaporanController::class, 'pinjamPdf'])->name('laporan.pinjam.pdf');
        Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
        Route::get('/laporan/pengembalian/pdf', [LaporanController::class, 'pengembalianPdf'])->name('laporan.pengembalian.pdf');
        Route::get('/laporan/pinjam/excel', [LaporanController::class, 'pinjamExcel'])->name('laporan.pinjam.excel');
        Route::get('/laporan/pengembalian/excel', [LaporanController::class, 'pengembalianExcel'])->name('laporan.pengembalian.excel');
    });
});

require __DIR__.'/auth.php';
