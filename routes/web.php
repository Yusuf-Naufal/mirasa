<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProsesController;

Route::get('/', function () {
    return view('welcome');
});

// AUTH
Route::get('/internal/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/internal/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('logout.get');

// DASHBOARD
Route::get('dashboard/super-admin', [SuperAdminDashboardController::class, 'index'])->name('super-admin.dashboard');
Route::get('dashboard/manager', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');

// CRUD PERUSAHAAN
Route::patch('/perusahaan/activate/{id}', [PerusahaanController::class, 'activate'])->name('perusahaan.activate');
Route::resource('perusahaan', PerusahaanController::class);

// CRUD COSTUMER
Route::patch('/costumer/activate/{id}', [CostumerController::class, 'activate'])->name('costumer.activate');
Route::resource('costumer', CostumerController::class);

// CRUD BARANG DAN JENIS BARANG
Route::prefix('barang')->name('barang.')->group(function () {
    // CRUD JENIS BARANG
    Route::resource('jenis', JenisBarangController::class);

    // CRUD BARANG
    Route::get('/', [BarangController::class, 'index'])->name('index');
    Route::get('/create', [BarangController::class, 'create'])->name('create');
    Route::post('/', [BarangController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BarangController::class, 'update'])->name('update');
    Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
});

// CRUD USER
Route::resource('user', UserController::class);

// CRUD SUPLIER
Route::resource('supplier', SupplierController::class)->names('supplier')->except(['show']);

// CRUD PROSES
Route::resource('proses', ProsesController::class)->names('proses')->except(['show', 'create', 'edit']);