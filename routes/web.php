<?php

/** @author Silva Tria Alfares - 254107023001 */
// test from alfa

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminBarangController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminAreaController;
use App\Http\Controllers\Admin\AdminBannerController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
Route::get('/cari', [BarangController::class, 'search'])->name('barang.search');

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/barang-baru', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::patch('/barang/{barang}/status', [BarangController::class, 'updateStatus'])->name('barang.updateStatus');
    Route::get('/listing-saya', [ListingController::class, 'index'])->name('listing.index');
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
    Route::patch('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/barang', [AdminBarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/{barang}', [AdminBarangController::class, 'show'])->name('barang.show');
    Route::patch('/barang/{barang}/approve', [AdminBarangController::class, 'approve'])->name('barang.approve');
    Route::patch('/barang/{barang}/reject', [AdminBarangController::class, 'reject'])->name('barang.reject');
    Route::delete('/barang/{barang}', [AdminBarangController::class, 'destroy'])->name('barang.destroy');

    Route::resource('kategori', AdminKategoriController::class)->except(['show']);
    Route::resource('area', AdminAreaController::class)->except(['show']);
    Route::resource('banner', AdminBannerController::class)->except(['show']);
});
