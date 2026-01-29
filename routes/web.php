<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\ProsesController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\KategoriPemakaianController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\AdminGudangDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sj-indofood', function () {
    return view('pages.print.sj-indofood');
});

Route::get('/sj-biasa', function () {
    return view('pages.print.sj-biasa');
});


Route::get('/beranda', function () {
    return view('pages.beranda');
})->name('beranda');

// AUTH
Route::get('/internal/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/internal/login', [AuthController::class, 'login']);
Route::post('/internal/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/internal/logout', [AuthController::class, 'logout'])->name('logout.get');

// DASHBOARD
Route::get('dashboard/super-admin', [SuperAdminDashboardController::class, 'index'])->name('super-admin.dashboard');
Route::get('dashboard/manager', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
Route::get('dashboard/admin-gudang', [AdminGudangDashboardController::class, 'index'])->name('admin-gudang.dashboard');

// CRUD PERUSAHAAN
Route::patch('/perusahaan/activate/{id}', [PerusahaanController::class, 'activate'])->name('perusahaan.activate');
Route::resource('perusahaan', PerusahaanController::class);

// CRUD COSTUMER
Route::patch('/costumer/activate/{id}', [CostumerController::class, 'activate'])->name('costumer.activate');
Route::get('/costumer/download-template', [CostumerController::class, 'downloadTemplate'])->name('costumer.download-template');
Route::post('/costumer/import', [CostumerController::class, 'import'])->name('costumer.import');
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
    Route::patch('/activate/{id}', [BarangController::class, 'activate'])->name('activate');
    Route::get('/download-template', [BarangController::class, 'downloadTemplate'])->name('download-template');
    Route::post('/import', [BarangController::class, 'import'])->name('import');
});

// CRUD USER
Route::resource('user', UserController::class);

// CRUD SUPLIER
Route::patch('/supplier/activate/{id}', [SupplierController::class, 'activate'])->name('supplier.activate');
Route::get('/supplier/download-template', [SupplierController::class, 'downloadTemplate'])->name('supplier.download-template');
Route::post('/supplier/import', [SupplierController::class, 'import'])->name('supplier.import');
Route::resource('supplier', SupplierController::class)->names('supplier')->except(['show']);

// CRUD PROSES
Route::patch('/proses/activate/{id}', [ProsesController::class, 'activate'])->name('proses.activate');
Route::resource('proses', ProsesController::class)->names('proses')->except(['show', 'create', 'edit']);

// CRUD INVENTORY
Route::get('inventory/create-produksi', [InventoryController::class, 'createProduksi'])->name('inventory.create-produksi');
Route::get('inventory/create-bp', [InventoryController::class, 'createBp'])->name('inventory.create-bp');
Route::get('inventory/create-bb', [InventoryController::class, 'createBb'])->name('inventory.create-bb');
Route::get('inventory/riwayat/{id}', [InventoryController::class, 'allRiwayat'])->name('inventory.riwayat');
Route::post('inventory/create-produksi', [InventoryController::class, 'storeProduksi'])->name('inventory.store-produksi');
Route::post('inventory/create-bahan', [InventoryController::class, 'storeBahan'])->name('inventory.store-bahan');
Route::patch('/inventory/{id}/update-minimum', [InventoryController::class, 'updateMinimum'])->name('inventory.update-minimum');
Route::patch('/inventory-details/{id}', [InventoryController::class, 'updateDetail'])->name('inventory-details.update');
Route::patch('/inventory/quick-update', [InventoryController::class, 'quickUpdate'])->name('inventory.quick-update');
Route::resource('inventory', InventoryController::class);

// CRUD BAHAN BAKU
Route::resource('bahan-baku', BahanBakuController::class);

// CRUD PEMAKAIAN GAS
Route::resource('kategori-pemakaian', KategoriPemakaianController::class);
Route::resource('pemakaian', PemakaianController::class);

// CRUD PENGELUARAN
Route::get('pengeluaran/create-operasional', [PengeluaranController::class, 'createOperasional'])->name('pengeluaran.create-operasional');
Route::get('pengeluaran/create-office', [PengeluaranController::class, 'createOffice'])->name('pengeluaran.create-office');
Route::get('pengeluaran/create-limbah', [PengeluaranController::class, 'createPengolahanLimbah'])->name('pengeluaran.create-limbah');
Route::get('pengeluaran/create-kesejahteraan', [PengeluaranController::class, 'createGajiKaryawan'])->name('pengeluaran.create-kesejahteraan');
Route::get('pengeluaran/create-maintenance', [PengeluaranController::class, 'createMaintenance'])->name('pengeluaran.create-maintenance');
Route::get('pengeluaran/create-admnisitrasi', [PengeluaranController::class, 'createAdministrasi'])->name('pengeluaran.create-admnisitrasi');

// CRUD PENGELUARAN
Route::resource('pengeluaran', PengeluaranController::class)->except(['create']);

// CRUD BARANG MASUK
Route::get('barang-masuk/create-produksi', [BarangMasukController::class, 'createProduksi'])->name('barang-masuk.create-produksi');
Route::get('barang-masuk/create-bp', [BarangMasukController::class, 'createBp'])->name('barang-masuk.create-bp');
Route::get('barang-masuk/edit-produksi/{id}', [BarangMasukController::class, 'editProduksi'])->name('barang-masuk.edit-produksi');
Route::get('barang-masuk/edit-bp/{id}', [BarangMasukController::class, 'editBp'])->name('barang-masuk.edit-bp');
Route::put('/barang-masuk/{id}', [BarangMasukController::class, 'update'])->name('barang-masuk.update');
Route::post('barang-masuk/create-produksi', [BarangMasukController::class, 'storeProduksi'])->name('barang-masuk.store-produksi');
Route::post('barang-masuk/create-bahan', [BarangMasukController::class, 'storeBahan'])->name('barang-masuk.store-bahan');
Route::resource('barang-masuk', BarangMasukController::class)->except(['update']);

// CRUD PRODUKSI
Route::put('/produksi/detail/{id}', [ProduksiController::class, 'updateDetail'])->name('produksi.update_detail');
Route::resource('produksi', ProduksiController::class);

// CRUD BARANG KELUAR
Route::get('barang-keluar/create-produksi', [BarangKeluarController::class, 'createProduksi'])->name('barang-keluar.create-produksi');
Route::get('barang-keluar/create-bahan-baku', [BarangKeluarController::class, 'createBahanBaku'])->name('barang-keluar.create-bahan-baku');
Route::get('barang-keluar/create-penjualan', [BarangKeluarController::class, 'createPenjualan'])->name('barang-keluar.create-penjualan');
Route::get('/barang-keluar/print-group', [BarangKeluarController::class, 'printGroup'])->name('barang-keluar.print-group');
Route::resource('barang-keluar', BarangKeluarController::class)->except(['create']);

// LAPORAN
Route::get('laporan-produksi', [LaporanController::class, 'laporanProduksi'])->name('laporan-produksi');
Route::get('laporan-pengeluaran', [LaporanController::class, 'laporanPengeluaran'])->name('laporan-pengeluaran');
Route::get('laporan-gudang', [LaporanController::class, 'laporanGudang'])->name('laporan-gudang');
Route::get('laporan-hpp', [LaporanController::class, 'laporanHpp'])->name('laporan-hpp');
Route::get('laporan-transaksi', [LaporanController::class, 'laporanTransaksi'])->name('laporan-transaksi');

// GRAFIK
Route::get('grafik-bahan-baku', [GrafikController::class, 'grafikBahanBaku'])->name('grafik.bahan-baku');
Route::get('grafik-produksi', [GrafikController::class, 'grafikProduksi'])->name('grafik.produksi');
Route::get('grafik-pemakaian', [GrafikController::class, 'grafikPemakaian'])->name('grafik.pemakaian');
Route::get('grafik-hpp', [GrafikController::class, 'grafikHpp'])->name('grafik.hpp');
Route::get('grafik-transaksi', [GrafikController::class, 'grafikTransaksi'])->name('grafik.transaksi');

// LOG ACTIVITY
Route::get('logs', [LogActivityController::class, 'index'])->name('logs.index');
