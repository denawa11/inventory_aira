<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\UserController;

Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('barangs/search-suggestions', [BarangController::class, 'suggestions'])->name('barangs.suggestions');
    Route::resource('barangs', BarangController::class)->except(['show']);
    Route::get('barangs/import', [BarangController::class, 'importForm'])->name('barangs.import.form');
    Route::post('barangs/import', [BarangController::class, 'import'])->name('barangs.import');
    Route::get('barangs/import/template', [BarangController::class, 'downloadTemplate'])->name('barangs.import.template');
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password');
});

Route::patch('barangs/{barang}/increment', [BarangController::class, 'increment'])->name('barangs.increment');
Route::patch('barangs/{barang}/decrement', [BarangController::class, 'decrement'])->name('barangs.decrement');

Route::patch('barangs/{barang}/increment-opened', [BarangController::class, 'incrementOpened'])->name('barangs.increment-opened');
Route::patch('barangs/{barang}/decrement-opened', [BarangController::class, 'decrementOpened'])->name('barangs.decrement-opened');