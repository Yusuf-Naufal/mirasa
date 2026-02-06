<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Perusahaan;
use App\Models\Pengeluaran;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Versi 1: Laporan Keuangan (Simple Asset & Pengeluaran Produksi)
     */
    public function laporanKeuanganSimple(Request $request)
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

        return view('pages.laporan.keuangan-simple', compact(
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

        // Optimasi: Gunakan Eager Loading agar tidak N+1 Query
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

    /**
     * Versi 2: Laporan Keuangan (Tren & Chart)
     */
    public function laporanKeuangan(Request $request)
    public function laporanPengeluaran(Request $request)
    {
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int) $request->get('month', date('n'));
        $selectedYear = (int) $request->get('year', date('Y'));
        $idPerusahaan = $request->get('id_perusahaan');

        $kategoriList = ['OPERASIONAL', 'OFFICE', 'LIMBAH', 'KESEJAHTERAAN', 'MAINTENANCE', 'ADMINISTRASI'];

        $queryBase = Pengeluaran::query();

        // Scope Perusahaan
        if (auth()->user()->hasRole('Super Admin')) {
            if ($idPerusahaan) {
                $queryBase->where('id_perusahaan', $idPerusahaan);
            }
            $perusahaan = Perusahaan::all();
        } else {
            $perusahaan = collect();
            $queryBase->where('id_perusahaan', auth()->user()->id_perusahaan);
        }

        $queryCurrent = (clone $queryBase);
        $queryLast = (clone $queryBase);

        if ($filterType === 'month') {
            // Filter Periode Ini
            $queryCurrent->whereRaw('EXTRACT(MONTH FROM tanggal_pengeluaran) = ?', [$selectedMonth])
                ->whereRaw('EXTRACT(YEAR FROM tanggal_pengeluaran) = ?', [$selectedYear]);

            // Filter Periode Lalu (Bulan Sebelumnya)
            $lastMonthDate = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->subMonth();
            $queryLast->whereRaw('EXTRACT(MONTH FROM tanggal_pengeluaran) = ?', [$lastMonthDate->month])
                ->whereRaw('EXTRACT(YEAR FROM tanggal_pengeluaran) = ?', [$lastMonthDate->year]);

            $daysInMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
            $labels = range(1, $daysInMonth);

            $trendRaw = (clone $queryCurrent)
                ->selectRaw('EXTRACT(DAY FROM tanggal_pengeluaran) as unit, kategori, SUM(jumlah_pengeluaran) as total')
                ->groupBy('unit', 'kategori')->get();
        } else {
            // Filter Periode Ini
            $queryCurrent->whereRaw('EXTRACT(YEAR FROM tanggal_pengeluaran) = ?', [$selectedYear]);

            // Filter Periode Lalu (Tahun Sebelumnya)
            $queryLast->whereRaw('EXTRACT(YEAR FROM tanggal_pengeluaran) = ?', [$selectedYear - 1]);

            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

            $trendRaw = (clone $queryCurrent)
                ->selectRaw('EXTRACT(MONTH FROM tanggal_pengeluaran) as unit, kategori, SUM(jumlah_pengeluaran) as total')
                ->groupBy('unit', 'kategori')->get();
        }

        // Format Data untuk Line Chart
        $lineChartData = [];
        $colors = ['#1e293b', '#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'];
        foreach ($kategoriList as $index => $kat) {
            $data = [];
            $unitRange = ($filterType === 'month') ? range(1, $daysInMonth) : range(1, 12);
            foreach ($unitRange as $unit) {
                $found = $trendRaw->where('unit', $unit)->where('kategori', $kat)->first();
                $data[] = $found ? (float)$found->total : 0;
            }
            $lineChartData[] = [
                'label' => $kat,
                'data' => $data,
                'borderColor' => $colors[$index],
                'backgroundColor' => $colors[$index] . '10',
                'tension' => 0.4,
                'fill' => true,
                'pointRadius' => 2
            ];
        }

        $dataPeriodeIni = $queryCurrent->get();
        $totalBulanIni = $dataPeriodeIni->sum('jumlah_pengeluaran');
        $totalBulanLalu = $queryLast->sum('jumlah_pengeluaran');

        // Logika Selisih & Persentase
        $diff = $totalBulanIni - $totalBulanLalu;
        if ($totalBulanLalu > 0) {
            $percentage = ($diff / $totalBulanLalu) * 100;
        } else {
            $percentage = $totalBulanIni > 0 ? 100 : 0;
        }

        $chartData = $dataPeriodeIni->groupBy('kategori')->map(fn($row) => $row->sum('jumlah_pengeluaran'));

        return view('pages.laporan.pengeluaran', compact(
            'totalBulanIni',
            'totalBulanLalu',
            'percentage',
            'diff',
            'chartData',
            'perusahaan',
            'selectedMonth',
            'selectedYear',
            'filterType',
            'lineChartData',
            'labels'
        ));
    }

    public function laporanHpp(Request $request)
    {
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int) $request->get('month', date('n'));
        $selectedYear = (int) $request->get('year', date('Y'));
        $user = auth()->user();
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        if ($filterType === 'month') {
            $currentStart = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
            $currentEnd = $currentStart->copy()->endOfMonth();
            $prevStart = $currentStart->copy()->subMonth()->startOfMonth();
            $prevEnd = $currentStart->copy()->subMonth()->endOfMonth();
        } else {
            $currentStart = Carbon::create($selectedYear, 1, 1)->startOfYear();
            $currentEnd = $currentStart->copy()->endOfYear();
            $prevStart = $currentStart->copy()->subYear()->startOfYear();
            $prevEnd = $currentStart->copy()->subYear()->endOfYear();
        }

        // 1. DATA PRODUKSI (HPP)
        $queryMain = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) {
            $q->whereIn('kode', ['FG', 'WIP', 'EC']);
        })
            ->whereHas('Inventory', function ($q) use ($idPerusahaan) {
                if ($idPerusahaan) $q->where('id_perusahaan', $idPerusahaan);
            })
            ->with(['Inventory.Barang.JenisBarang']);

        $currentRaw = (clone $queryMain)->whereBetween('tanggal_masuk', [$currentStart, $currentEnd])->get();
        $prevRaw = (clone $queryMain)->whereBetween('tanggal_masuk', [$prevStart, $prevEnd])->get();

        $calculate = function ($data) {
            $vol = 0;
            $cost = 0;
            $skus = [];
            foreach ($data as $item) {
                $konversi = $item->Inventory->Barang->nilai_konversi ?? 1;
                $vol += ($item->jumlah_diterima * $konversi);
                $cost += $item->total_harga;
                $skus[] = $item->Inventory->id_barang;
            }
            return ['vol' => $vol, 'cost' => $cost, 'skus' => array_unique($skus)];
        };

        $curr = $calculate($currentRaw);
        $prev = $calculate($prevRaw);

        $summary = [
            'current_count_sku' => count($curr['skus']),
            'diff_sku' => count($curr['skus']) - count($prev['skus']),
            'current_volume' => $curr['vol'],
            'diff_volume_pct' => $prev['vol'] > 0 ? (($curr['vol'] - $prev['vol']) / $prev['vol']) * 100 : ($curr['vol'] > 0 ? 100 : 0),
            'total_cost' => $curr['cost'],
            'avg_cost_per_kg' => $curr['vol'] > 0 ? $curr['cost'] / $curr['vol'] : 0
        ];

        $rincianProduksi = $currentRaw->groupBy('Inventory.id_barang')->map(function ($items) {
            $barang = $items->first()->Inventory->Barang;
            return [
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'kode' => $barang->kode,
                'tipe' => $barang->JenisBarang->kode ?? '-',
                'total_diterima' => $items->sum('jumlah_diterima'),
                'total_qty_kg' => $items->sum(fn($i) => $i->jumlah_diterima * ($barang->nilai_konversi ?? 1)),
                'total_biaya' => $items->sum('total_harga'),
            ];
        })->sortByDesc('total_biaya')->values();

        // 2. DATA BAHAN KELUAR (BAHAN BAKU & PENOLONG)
        $bahanRaw = BarangKeluar::whereIn('jenis_keluar', ['BAHAN BAKU', 'PRODUKSI'])
            ->whereHas('DetailInventory.Inventory', function ($q) use ($idPerusahaan) {
                if ($idPerusahaan) $q->where('id_perusahaan', $idPerusahaan);
            })
            ->whereBetween('tanggal_keluar', [$currentStart, $currentEnd])
            ->with(['DetailInventory.Inventory.Barang.JenisBarang'])
            ->get();

        $prevBahanRaw = BarangKeluar::whereIn('jenis_keluar', ['BAHAN BAKU', 'PRODUKSI'])
            ->whereHas('DetailInventory.Inventory', function ($q) use ($idPerusahaan) {
                if ($idPerusahaan) $q->where('id_perusahaan', $idPerusahaan);
            })
            ->whereBetween('tanggal_keluar', [$prevStart, $prevEnd])
            ->get();

        $prevTotalHargaKeluar = $prevBahanRaw->sum('total_harga');
        $currentTotalHargaKeluar = $bahanRaw->sum('total_harga');

        $diffTotalKeluarPct = $prevTotalHargaKeluar > 0
            ? (($currentTotalHargaKeluar - $prevTotalHargaKeluar) / $prevTotalHargaKeluar) * 100
            : ($currentTotalHargaKeluar > 0 ? 100 : 0);

        $prevHargaBaku = $prevBahanRaw->where('jenis_keluar', 'BAHAN BAKU')->sum('total_harga');
        $currentHargaBaku = $bahanRaw->where('jenis_keluar', 'BAHAN BAKU')->sum('total_harga');

        $diffBakuPct = $prevHargaBaku > 0
            ? (($currentHargaBaku - $prevHargaBaku) / $prevHargaBaku) * 100
            : ($currentHargaBaku > 0 ? 100 : 0);

        $totalHargaBarangKeluar = $bahanRaw->sum('total_harga');

        $rincianBahan = $bahanRaw->groupBy('DetailInventory.Inventory.id_barang')->map(function ($items) use ($totalHargaBarangKeluar) {
            $barang = $items->first()->DetailInventory->Inventory->Barang;
            $biayaItem = $items->sum('total_harga');

            return [
                'nama_barang'  => $barang->nama_barang,
                'satuan'       => $barang->satuan,
                'kode_barang'  => $barang->kode,
                'jenis_keluar' => $items->first()->jenis_keluar,
                'total_qty'    => $items->sum('jumlah_keluar'),
                'total_kg'     => $items->sum(fn($i) => $i->jumlah_keluar * ($barang->nilai_konversi ?? 1)),
                'total_biaya'  => $biayaItem,
                'persen'       => $totalHargaBarangKeluar > 0 ? ($biayaItem / $totalHargaBarangKeluar) * 100 : 0,
            ];
        })->sortByDesc('total_biaya')->values();

        $summaryBahan = [
            'total_harga_keluar'    => $currentTotalHargaKeluar,
            'diff_total_keluar_pct' => $diffTotalKeluarPct,
            'total_harga_baku'      => $currentHargaBaku,
            'diff_baku_pct'         => $diffBakuPct,
            'total_harga_penolong'  => $bahanRaw->where('jenis_keluar', 'PRODUKSI')->sum('total_harga'),
            'total_kg_baku'         => $rincianBahan->where('jenis_keluar', 'BAHAN BAKU')->sum('total_kg'),
            'count_jenis_penolong'  => $bahanRaw->where('jenis_keluar', 'PRODUKSI')->pluck('DetailInventory.Inventory.id_barang')->unique()->count(),
        ];

        // 3. DATA PENGELUARAN
        $pengeluaranHpp = Pengeluaran::whereRaw('is_hpp = true')
            ->whereBetween('tanggal_pengeluaran', [$currentStart, $currentEnd])
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                return $q->where('id_perusahaan', $idPerusahaan);
            })
            ->get();

        $prevPengeluaranRaw = Pengeluaran::whereRaw('is_hpp = true')
            ->whereBetween('tanggal_pengeluaran', [$prevStart, $prevEnd])
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                return $q->where('id_perusahaan', $idPerusahaan);
            })->get();

        $totalBebanHpp = $pengeluaranHpp->sum('jumlah_pengeluaran');
        $prevTotalBebanHpp = $prevPengeluaranRaw->sum('jumlah_pengeluaran');

        $diffBebanHppPct = $prevTotalBebanHpp > 0
            ? (($totalBebanHpp - $prevTotalBebanHpp) / $prevTotalBebanHpp) * 100
            : ($totalBebanHpp > 0 ? 100 : 0);

        $bebanKategoriHpp = $pengeluaranHpp->groupBy('kategori')->map(function ($items, $key) use ($totalBebanHpp, $prevPengeluaranRaw) {
            $nominal = $items->sum('jumlah_pengeluaran');
            $prevNominal = $prevPengeluaranRaw->where('kategori', $key)->sum('jumlah_pengeluaran');
            $diffPct = $prevNominal > 0 ? (($nominal - $prevNominal) / $prevNominal) * 100 : ($nominal > 0 ? 100 : 0);

            return [
                'nama' => $items->first()->kategori ?? 'Umum',
                'total' => $nominal,
                'persen' => $totalBebanHpp > 0 ? ($nominal / $totalBebanHpp) * 100 : 0,
                'diff_pct' => $diffPct,
                'count' => $items->count()
            ];
        })->sortByDesc('total');

        // 4. HITUNG HPP PER KG
        $grandTotalBiayaHpp = $summaryBahan['total_harga_keluar'] + $totalBebanHpp;
        $totalVolumeProduksi = $summary['current_volume'];
        $hppPerKg = $totalVolumeProduksi > 0 ? ($grandTotalBiayaHpp / $totalVolumeProduksi) : 0;

        $grandTotalBiayaHppPrev = $prevTotalHargaKeluar + $prevTotalBebanHpp;
        $totalVolumeProduksiPrev = $prev['vol'];
        $hppPerKgPrev = $totalVolumeProduksiPrev > 0 ? ($grandTotalBiayaHppPrev / $totalVolumeProduksiPrev) : 0;

        $diffHppPct = $hppPerKgPrev > 0 ? (($hppPerKg - $hppPerKgPrev) / $hppPerKgPrev) * 100 : ($hppPerKg > 0 ? 100 : 0);

        return view('pages.laporan.hpp', compact(
            'summary',
            'rincianProduksi',
            'selectedMonth',
            'selectedYear',
            'filterType',
            'summaryBahan',
            'rincianBahan',
            'pengeluaranHpp',
            'totalBebanHpp',
            'diffBebanHppPct',
            'bebanKategoriHpp',
            'grandTotalBiayaHpp',
            'totalVolumeProduksi',
            'hppPerKg',
            'diffHppPct'
        ) + ['perusahaan' => Perusahaan::all()]);
    }
}

    public function laporanTransaksi(Request $request)
    {
        $user = auth()->user();
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int) $request->get('month', date('n'));
        $selectedYear = (int) $request->get('year', date('Y'));
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->get('id_perusahaan') : $user->id_perusahaan;

        if ($filterType === 'month') {
            $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
        } else {
            $startDate = Carbon::create($selectedYear, 1, 1)->startOfYear();
            $endDate = $startDate->copy()->endOfYear();
        }

        // --- BARANG MASUK ---
        $masukRaw = DetailInventory::with(['Supplier' => fn($q) => $q->withTrashed(), 'Inventory.Barang.JenisBarang'])
            ->whereHas('Inventory', function ($q) use ($idPerusahaan) {
                if ($idPerusahaan) $q->where('id_perusahaan', $idPerusahaan);
            })
            ->whereBetween('tanggal_masuk', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $masukPerSupplier = $masukRaw->groupBy('id_supplier')->map(function ($items) {
            $supplier = $items->first()->Supplier;
            $filteredItems = $items->filter(function ($item) {
                $kode = optional(optional($item->Inventory->Barang)->JenisBarang)->kode;
                return !in_array($kode, ['FG', 'WIP', 'EC']);
            });

            if ($filteredItems->isEmpty()) return null;

            return [
                'nama_supplier' => $supplier->nama_supplier ?? 'Tanpa Nama',
                'jenis_supplier' => $supplier->jenis_supplier,
                'total_qty' => $filteredItems->sum('jumlah_diterima'),
                'total_nilai' => $filteredItems->sum('total_harga'),
                'details' => $filteredItems
            ];
        })->filter()->groupBy('jenis_supplier');

        // --- BARANG KELUAR ---
        $keluarRaw = BarangKeluar::with(['Costumer' => fn($q) => $q->withTrashed(), 'DetailInventory.Inventory.Barang.JenisBarang'])
            ->when($idPerusahaan, function ($q) use ($idPerusahaan) {
                $q->where('id_perusahaan', $idPerusahaan);
            })
            ->whereBetween('tanggal_keluar', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereNotNull('id_costumer')
            ->get();

        $keluarPerCostumer = $keluarRaw->groupBy('id_costumer')->map(function ($items) {
            return [
                'nama_costumer' => $items->first()->Costumer->nama_costumer ?? 'Tanpa Nama',
                'total_qty' => $items->sum('jumlah_keluar'),
                'total_nilai' => $items->sum('total_harga'),
                'total_kg' => $items->sum(function ($item) {
                    $barang = optional(optional($item->DetailInventory)->Inventory)->Barang;
                    $kode = optional($barang->JenisBarang)->kode;
                    return in_array($kode, ['FG', 'WIP', 'EC']) ? ($item->jumlah_keluar * ($barang->nilai_konversi ?? 1)) : 0;
                }),
                'details' => $items 
            ];
        })->sortByDesc('total_kg');

        $perusahaan = $user->hasRole('Super Admin') ? Perusahaan::all() : collect();

        return view('pages.laporan.transaksi', compact(
            'masukRaw',
            'masukPerSupplier',
            'keluarPerCostumer',
            'perusahaan',
            'filterType',
            'selectedMonth',
            'selectedYear'
        ));
    }
}
