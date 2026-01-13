<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Perusahaan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * FIX: Menambahkan method yang hilang untuk Laporan Keuangan
     * Berdasarkan alur bisnis PT Mirasa Food
     */
    public function laporanKeuangan(Request $request)
    {
        $user = auth()->user();
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        // Setup Tanggal
        $startDate = now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');
        if ($request->filled('date_range') && str_contains($request->date_range, ' to ')) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
        }

        // Hitung Nilai Aset Gudang (Bahan Baku + Bahan Penolong)
        $nilaiAsetGudang = DetailInventory::where('stok', '>', 0)
            ->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan)))
            ->sum('total_harga');

        // Hitung Total Pengeluaran (Barang Keluar untuk Produksi)
        $totalPengeluaranProduksi = BarangKeluar::whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->sum('total_harga');

        $perusahaan = Perusahaan::all();
        $dateRange = "$startDate to $endDate";

        return view('pages.laporan.keuangan', compact(
            'nilaiAsetGudang', 
            'totalPengeluaranProduksi', 
            'perusahaan', 
            'dateRange'
        ));
    }

    public function laporanProduksi(Request $request)
    {
        $user = auth()->user();

        // 1. Inisialisasi Date Range
        if ($request->filled('date_range') && str_contains($request->date_range, ' to ')) {
            $dates = explode(' to ', $request->get('date_range'));
            $startDate = $dates[0];
            $endDate = $dates[1];
        } else {
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        }

        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        // --- 3. BIAYA BAHAN BAKU ---
        $totalBiayaBB = DetailInventory::whereHas('Inventory.Barang.jenisBarang', fn($q) => $q->where('kode', 'BB'))
            ->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $idPerusahaan)))
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->sum('total_harga');

        // --- 4. BIAYA BAHAN PENOLONG & RINCIAN ---
        $dataKeluarRaw = BarangKeluar::where('jenis_keluar', 'PRODUKSI')
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->with(['DetailInventory.Inventory.Barang' => fn($q) => $q->withTrashed(), 'DetailInventory.Inventory.Barang.jenisBarang'])
            ->get();

        $totalBiayaBP = $dataKeluarRaw->filter(fn($item) => $item->DetailInventory?->Inventory?->Barang?->jenisBarang?->kode == 'BP')->sum('total_harga');

        $barangKeluar = $dataKeluarRaw->groupBy(fn($item) => $item->DetailInventory?->Inventory?->id_barang ?? 0)
            ->map(function ($group) {
                $barang = $group->first()->DetailInventory?->Inventory?->Barang;
                return [
                    'nama_barang' => $barang?->nama_barang ?? 'N/A',
                    'total_qty' => $group->sum('jumlah_keluar'),
                    'satuan' => $barang?->satuan ?? '-',
                    'total_nilai' => $group->sum('total_harga')
                ];
            });

        // --- 5. HASIL PRODUK JADI ---
        $hasilProduksi = DetailInventory::whereHas('Inventory.Barang.jenisBarang', fn($q) => $q->whereIn('kode', ['FG', 'WIP', 'EC']))
            ->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $idPerusahaan)))
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->with(['Inventory.Barang' => fn($q) => $q->withTrashed()])
            ->get()
            ->groupBy(fn($item) => $item->Inventory?->id_barang ?? 0)
            ->map(function ($group) {
                $barang = $group->first()->Inventory?->Barang;
                return [
                    'nama_barang' => $barang?->nama_barang ?? 'N/A',
                    'total_qty' => $group->sum('jumlah_diterima'),
                    'total_nilai' => $group->sum('total_harga')
                ];
            });

        return view('pages.laporan.produksi', [
            'totalBiayaBB' => $totalBiayaBB,
            'totalBiayaBP' => $totalBiayaBP,
            'barangKeluar' => $barangKeluar,
            'hasilProduksi' => $hasilProduksi,
            'perusahaan' => Perusahaan::all(),
            'dateRange' => "$startDate to $endDate"
        ]);
    }

    public function laporanGudang(Request $request)
    {
        $user = auth()->user();
        
        $startDate = now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');
        if ($request->filled('date_range') && str_contains($request->date_range, ' to ')) {
            [$startDate, $endDate] = explode(' to ', $request->date_range);
        }

        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        // Optimasi: Gunakan Eager Loading agar tidak N+1 Query (Penyebab timeout)
        $stokRaw = Inventory::with(['Barang.jenisBarang', 'Perusahaan'])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->get();

        $stokGlobalGrouped = $stokRaw->groupBy(function ($item) {
            $kode = $item->Barang?->jenisBarang?->kode;
            return match(true) {
                in_array($kode, ['FG', 'WIP', 'EC']) => 'PRODUKSI',
                $kode == 'BB' => 'BAHAN BAKU',
                $kode == 'BP' => 'BAHAN PENOLONG',
                default => 'LAINNYA',
            };
        });

        $summary = [
            'total_asset' => DetailInventory::where('stok', '>', 0)
                ->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan)))
                ->sum('total_harga'),
            'count_produksi' => $stokRaw->filter(fn($i) => in_array($i->Barang?->jenisBarang?->kode, ['FG', 'WIP', 'EC']))->count(),
            'count_bb' => $stokRaw->filter(fn($i) => $i->Barang?->jenisBarang?->kode == 'BB')->count(),
            'count_bp' => $stokRaw->filter(fn($i) => $i->Barang?->jenisBarang?->kode == 'BP')->count(),
        ];

        return view('pages.laporan.gudang', [
            'stokGlobalGrouped' => $stokGlobalGrouped,
            'stokDetail' => DetailInventory::with('Inventory.Barang')->when($idPerusahaan, fn($q) => $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan)))->latest()->take(10)->get(),
            'barangKeluar' => BarangKeluar::with('DetailInventory.Inventory.Barang')->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))->latest()->take(10)->get(),
            'perusahaan' => Perusahaan::all(),
            'dateRange' => "$startDate to $endDate",
            'summary' => $summary
        ]);
    }
}