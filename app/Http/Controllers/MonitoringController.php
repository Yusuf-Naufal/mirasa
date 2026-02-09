<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $perusahaans = Auth::user()->hasRole('Super Admin')
            ? Perusahaan::orderBy('nama_perusahaan', 'asc')->get()
            : collect();

        return view('pages.monitoring.index', compact('perusahaans'));
    }

    public function data(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->query('id_perusahaan') : $user->id_perusahaan;

        // --- 1. LOW STOCK ALERT ---
        $lowStock = Inventory::with(['Barang'])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->where(function ($query) {
                $query->whereColumn('stok', '<', 'minimum_stok')
                    ->orWhere('stok', '<=', 0);
            })
            ->get();

        // --- 2. TREND CHART DATA (Last 7 Days) ---
        $labels = [];
        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('d M');

            $dataMasuk[] = (float) DetailInventory::whereDate('tanggal_masuk', $date)
                ->whereHas('Inventory', fn($q) => $idPerusahaan ? $q->where('id_perusahaan', $idPerusahaan) : $q)
                ->sum('total_harga');

            $dataKeluar[] = (float) BarangKeluar::whereDate('tanggal_keluar', $date)
                ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
                ->sum('total_harga');
        }

        // --- 3. INVENTORY GROUPING ---
        $queryInventory = Inventory::with(['Barang.jenisBarang'])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->get();

        $grouped = [
            'produksi'   => $queryInventory->filter(fn($i) => in_array($i->barang->jenisBarang->kode ?? '', ['FG', 'WIP', 'EC']))->values(),
            'bahan_baku' => $queryInventory->filter(fn($i) => ($i->barang->jenisBarang->kode ?? '') === 'BB')->values(),
            'penolong'   => $queryInventory->filter(fn($i) => ($i->barang->jenisBarang->kode ?? '') === 'BP')->values(),
        ];

        return response()->json([
            'stats' => [
                'totalMasuk' => (float) array_slice($dataMasuk, -1)[0],
                'totalKeluar' => (float) array_slice($dataKeluar, -1)[0],
            ],
            'inventory' => $grouped,
            'lowStock' => $lowStock,
            'chart' => [
                'labels' => $labels,
                'masuk' => $dataMasuk,
                'keluar' => $dataKeluar
            ]
        ]);
    }
}
