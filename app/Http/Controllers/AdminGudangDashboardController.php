<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;

class AdminGudangDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $id_perusahaan = $user->id_perusahaan;

        $stats = [
            'total_bahan_baku' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.jenisBarang', fn($q) => $q->where('kode', 'BB'))->count(),
            'total_barang_penolong' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.jenisBarang', fn($q) => $q->where('kode', 'BP'))->count(),
            'total_barang_produksi' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.jenisBarang', fn($q) => $q->whereIn('kode', ['FG', 'WIP', 'EC']))->count(),
            'stok_kritis' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->where('stok', '<', 10)->count(), // Contoh ambang batas 10
            'produksi_hari_ini' => Produksi::where('id_perusahaan', $id_perusahaan)
                ->whereDate('tanggal_produksi', today())->count(),
            'barang_keluar_hari_ini' => BarangKeluar::where('id_perusahaan', $id_perusahaan)
                ->whereDate('tanggal_keluar', today())->count(),
        ];

        $recent_stock_movements = DetailInventory::with(['Inventory.Barang'])
            ->whereHas('Inventory.Barang') 
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('pages.dashboard.admingudang', compact('stats', 'recent_stock_movements'));
    }
}
