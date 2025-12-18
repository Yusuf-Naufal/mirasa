<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SuperAdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('internal/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::get('dashboard/super-admin', [SuperAdminDashboardController::class, 'index'])->name('super-admin.dashboard');

// CRUD PERUSAHAAN
Route::resource('perusahaan', PerusahaanController::class);
