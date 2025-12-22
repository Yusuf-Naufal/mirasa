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
Route::resource('barang/jenis', JenisBarangController::class)->names('barang.jenis');
Route::resource('barang', BarangController::class)->names('barang');

// CRUD USER
Route::resource('user', UserController::class);

// CRUD SUPPLIER 
Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::get('/create', [SupplierController::class, 'create'])->name('create');
    Route::post('/store', [SupplierController::class, 'store'])->name('store');
    Route::get('/edit/{index}', [SupplierController::class, 'edit'])->name('edit');
    Route::put('/update/{index}', [SupplierController::class, 'update'])->name('update');
    Route::delete('/destroy/{index}', [SupplierController::class, 'destroy'])->name('destroy');
    Route::delete('/destroy-all', [SupplierController::class, 'destroyAll'])->name('destroyAll');
});

// CRUD PROSES
Route::resource('proses', ProsesController::class)->names('proses')->except(['show']);