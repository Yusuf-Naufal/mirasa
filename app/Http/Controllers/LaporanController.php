<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Perusahaan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;

class LaporanController extends Controller
{
    public function laporanProduksi(Request $request)
    {
        $user = auth()->user();

        // 1. Inisialisasi Date Range (Default: Bulan Ini)
        if ($request->filled('date_range') && str_contains($request->date_range, ' to ')) {
            $dates = explode(' to ', $request->get('date_range'));
            $startDate = $dates[0];
            $endDate = $dates[1];
        } else if ($request->filled('date_range')) {
            $startDate = $request->get('date_range');
            $endDate = $request->get('date_range');
        } else {
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        }

        $dateRange = ($startDate == $endDate) ? $startDate : "$startDate to $endDate";

        // 2. Filter Perusahaan
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        // --- 3. BIAYA BAHAN BAKU (DetailInventory - BB) ---
        $totalBiayaBB = DetailInventory::whereHas('Inventory.Barang.jenisBarang', function ($q) {
                $q->where('kode', 'BB');
            })
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $idPerusahaan));
            })
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->sum('total_harga');

        // --- 4. BIAYA BAHAN PENOLONG & RINCIAN BARANG KELUAR ---
        $dataKeluarRaw = BarangKeluar::where('jenis_keluar', 'PRODUKSI')
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            // Tambahkan withTrashed() agar barang yang dihapus tetap bisa dihitung nilainya
            ->with([
                'DetailInventory.Inventory.Barang' => fn($q) => $q->withTrashed(),
                'DetailInventory.Inventory.Barang.jenisBarang'
            ])
            ->get();

        // Hitung total Biaya Bahan Penolong (BP) - Menggunakan Null-Safe Operator
        $totalBiayaBP = $dataKeluarRaw->filter(function ($item) {
            return $item->DetailInventory?->Inventory?->Barang?->jenisBarang?->kode == 'BP';
        })->sum('total_harga');

        // Mapping untuk Tabel Rincian (Anti-Crash)
        $barangKeluar = $dataKeluarRaw->groupBy(function($item) {
                return $item->DetailInventory?->Inventory?->id_barang ?? 0;
            })
            ->map(function ($group) {
                $first = $group->first();
                $barang = $first->DetailInventory?->Inventory?->Barang;
                return [
                    'nama_barang' => $barang?->nama_barang ?? 'Barang Dihapus/Tidak Ditemukan',
                    'total_qty' => $group->sum('jumlah_keluar'),
                    'satuan' => $barang?->satuan ?? '-',
                    'total_nilai' => $group->sum('total_harga')
                ];
            });

        // --- 5. PRODUK JADI ---
        $hasilProduksi = DetailInventory::whereHas('Inventory.Barang.jenisBarang', function ($q) {
                $q->whereIn('kode', ['FG', 'WIP', 'EC']);
            })
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $idPerusahaan));
            })
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->with(['Inventory.Barang' => fn($q) => $q->withTrashed(), 'Inventory.Barang.jenisBarang'])
            ->get()
            ->groupBy(fn($item) => $item->Inventory?->id_barang ?? 0)
            ->map(function ($group) {
                $first = $group->first();
                $barang = $first->Inventory?->Barang;
                return [
                    'nama_barang' => $barang?->nama_barang ?? 'Barang Dihapus',
                    'jenis' => $barang?->jenisBarang?->kode ?? '-',
                    'total_qty' => $group->sum('jumlah_diterima'),
                    'satuan' => $barang?->satuan ?? '-',
                    'total_nilai' => $group->sum('total_harga')
                ];
            });

        $perusahaan = Perusahaan::all();

        return view('pages.laporan.produksi', compact('totalBiayaBB', 'totalBiayaBP', 'barangKeluar', 'hasilProduksi', 'perusahaan', 'dateRange'));
    }

    public function laporanGudang(Request $request)
    {
        $user = auth()->user();
        
        // Setup Tanggal
        $startDate = now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');
        if ($request->filled('date_range') && str_contains($request->date_range, ' to ')) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
        }
        $dateRange = "$startDate to $endDate";

        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        // Data Stok Global (Anti Error Null)
        $stokRaw = Inventory::with(['Barang' => fn($q) => $q->withTrashed(), 'Barang.jenisBarang', 'Perusahaan'])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->get();

        $stokGlobalGrouped = $stokRaw->groupBy(function ($item) {
            $kode = $item->Barang?->jenisBarang?->kode;
            if (in_array($kode, ['FG', 'WIP', 'EC'])) return 'PRODUKSI';
            if ($kode == 'BB') return 'BAHAN BAKU';
            if ($kode == 'BP') return 'BAHAN PENOLONG';
            return 'LAINNYA';
        });

        // Summary Statistics
        $summary = [
            'total_asset' => DetailInventory::where('stok', '>', 0)
                ->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan)))
                ->sum('total_harga'),
            'count_produksi' => $stokRaw->filter(fn($i) => in_array($i->Barang?->jenisBarang?->kode, ['FG', 'WIP', 'EC']))->count(),
            'count_bb' => $stokRaw->filter(fn($i) => $i->Barang?->jenisBarang?->kode == 'BB')->count(),
            'count_bp' => $stokRaw->filter(fn($i) => $i->Barang?->jenisBarang?->kode == 'BP')->count(),
        ];

        // Riwayat Mutasi (Limit 15)
        $stokDetail = DetailInventory::with(['Inventory.Barang' => fn($q) => $q->withTrashed()])
            ->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan)))
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->latest()
            ->take(15)
            ->get();

        $barangKeluar = BarangKeluar::with(['DetailInventory.Inventory.Barang' => fn($q) => $q->withTrashed()])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->latest()
            ->take(15)
            ->get();

        return view('pages.laporan.gudang', [
            'stokGlobalGrouped' => $stokGlobalGrouped,
            'stokDetail' => $stokDetail,
            'barangKeluar' => $barangKeluar,
            'perusahaan' => Perusahaan::all(),
            'dateRange' => $dateRange,
            'summary' => $summary
        ]);
    }
}