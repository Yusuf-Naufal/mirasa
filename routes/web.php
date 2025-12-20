<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SuperAdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('internal/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::get('dashboard/super-admin', [SuperAdminDashboardController::class, 'index'])->name('super-admin.dashboard');

// CRUD PERUSAHAAN
Route::patch('/perusahaan/activate/{id}', [PerusahaanController::class, 'activate'])->name('perusahaan.activate');
Route::resource('perusahaan', PerusahaanController::class);

// CRUD COSTUMER
Route::patch('/costumer/activate/{id}', [CostumerController::class, 'activate'])->name('costumer.activate');
Route::resource('costumer', CostumerController::class);

// CRUD BARANG DAN JENIS BARANG
Route::prefix('barang')->name('barang.')->group(function () {
    // 1. Letakkan Jenis di atas agar tidak dianggap sebagai ID Barang
    Route::resource('jenis', JenisBarangController::class);

    // 2. Resource Barang dengan parameter manual
    Route::resource('/', BarangController::class)
        ->names('index')
        ->parameters(['' => 'barang']);
});
