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
            // Jika user hanya pilih 1 tanggal
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
        // Gunakan eager loading 'Inventory.Barang' untuk performa
        $queryBB = DetailInventory::whereHas('Inventory.Barang.jenisBarang', function ($q) {
            $q->where('kode', 'BB');
        })
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $idPerusahaan));
            })
            ->whereBetween('tanggal_masuk', [$startDate, $endDate]);

        $totalBiayaBB = $queryBB->sum('total_harga');

        // --- 4. BIAYA BAHAN PENOLONG & RINCIAN BARANG KELUAR ---
        // Kita ambil datanya dulu untuk di-mapping ke tabel rincian
        $queryBarangKeluar = BarangKeluar::where('jenis_keluar', 'PRODUKSI')
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->where('id_perusahaan', $idPerusahaan);
            })
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->with(['DetailInventory.Inventory.Barang']);

        $dataKeluarRaw = $queryBarangKeluar->get();

        // Hitung total Biaya Bahan Penolong (BP) saja
        $totalBiayaBP = $dataKeluarRaw->filter(function ($item) {
            return optional(optional(optional($item->DetailInventory)->Inventory)->Barang->jenisBarang)->kode == 'BP';
        })->sum('total_harga');

        // Mapping untuk Tabel "Rincian Barang Dikeluarkan" (Group By Barang)
        $barangKeluar = $dataKeluarRaw->groupBy('DetailInventory.Inventory.id_barang')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'nama_barang' => $first->DetailInventory->Inventory->Barang->nama_barang,
                    'total_qty' => $group->sum('jumlah_keluar'),
                    'satuan' => $first->DetailInventory->Inventory->Barang->satuan,
                    'total_nilai' => $group->sum('total_harga')
                ];
            });

        // --- 5. PRODUK JADI (DetailInventory - FG, WIP, EC) ---
        $hasilProduksi = DetailInventory::whereHas('Inventory.Barang.jenisBarang', function ($q) {
            $q->whereIn('kode', ['FG', 'WIP', 'EC']);
        })
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $idPerusahaan));
            })
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->with(['Inventory.Barang.jenisBarang'])
            ->get()
            ->groupBy('Inventory.id_barang')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'nama_barang' => $first->Inventory->Barang->nama_barang,
                    'jenis' => $first->Inventory->Barang->jenisBarang->kode,
                    'total_qty' => $group->sum('jumlah_diterima'),
                    'satuan' => $first->Inventory->Barang->satuan,
                    'total_nilai' => $group->sum('total_harga')
                ];
            });

        $perusahaan = Perusahaan::all();

        return view('pages.laporan.produksi', compact(
            'totalBiayaBB',
            'totalBiayaBP',
            'barangKeluar',
            'hasilProduksi',
            'perusahaan',
            'dateRange'
        ));
    }

    public function laporanGudang(Request $request)
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
        $dateRange = "$startDate to $endDate";

        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        // 2. Data Stok Global (Inventory) - Eager Loading untuk performa
        $stokRaw = Inventory::with(['Barang.jenisBarang', 'Perusahaan'])
            ->when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->get();

        // Grouping untuk UI Tab
        $stokGlobalGrouped = $stokRaw->groupBy(function ($item) {
            $kode = optional(optional($item->Barang)->jenisBarang)->kode;
            if (in_array($kode, ['FG', 'WIP', 'EC'])) return 'PRODUKSI';
            if ($kode == 'BB') return 'BAHAN BAKU';
            if ($kode == 'BP') return 'BAHAN PENOLONG';
            return 'LAINNYA';
        });

        // 3. Ringkasan (Summary) - Diambil dari tabel Inventory ($stokRaw)
        // Kecuali Total Aset yang diambil dari DetailInventory karena info harga ada di sana
        $summary = [
            'total_asset' => DetailInventory::where('stok', '>', 0)
                ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                    $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan));
                })->sum('total_harga'),

            // Menghitung jumlah JENIS barang unik yang terdaftar di Inventory
            'count_produksi' => $stokRaw->filter(function ($item) {
                $kode = optional(optional($item->Barang)->jenisBarang)->kode;
                return in_array($kode, ['FG', 'WIP', 'EC']);
            })->count(),

            'count_bb' => $stokRaw->filter(function ($item) {
                return optional(optional($item->Barang)->jenisBarang)->kode == 'BB';
            })->count(),

            'count_bp' => $stokRaw->filter(function ($item) {
                return optional(optional($item->Barang)->jenisBarang)->kode == 'BP';
            })->count(),
        ];

        // 4. Data Pergerakan (Log Masuk & Keluar) Berdasarkan Filter Tanggal
        $stokDetail = DetailInventory::with(['Inventory.Barang'])
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->whereHas('Inventory', fn($sq) => $sq->where('id_perusahaan', $idPerusahaan));
            })
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->take(15)
            ->get();

        $barangKeluar = BarangKeluar::with(['DetailInventory.Inventory.Barang'])
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->where('id_perusahaan', $idPerusahaan);
            })
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->take(15)
            ->get();

        $perusahaan = Perusahaan::all();

        return view('pages.laporan.gudang', [
            'stokGlobal' => $stokRaw,
            'stokGlobalGrouped' => $stokGlobalGrouped,
            'stokDetail' => $stokDetail,
            'barangKeluar' => $barangKeluar,
            'perusahaan' => $perusahaan,
            'dateRange' => $dateRange,
            'summary' => $summary
        ]);
    }

    public function laporanKeuangan() {}
}
