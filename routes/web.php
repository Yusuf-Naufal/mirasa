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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/internal/login', [AuthController::class, 'showLoginForm'])->name('Formlogin');
Route::post('/internal/login', [AuthController::class, 'login'])->name('login');

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
    // 1. Letakkan Jenis di atas agar tidak dianggap sebagai ID Barang
    Route::resource('jenis', JenisBarangController::class);

    // 2. Resource Barang dengan parameter manual
    Route::resource('/', BarangController::class)
        ->names('index')
        ->parameters(['' => 'barang']);
});

// CRUD USER
Route::resource('user', UserController::class);
