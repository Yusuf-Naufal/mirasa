<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Pemakaian;
use App\Models\Pengeluaran;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailProduksi;
use App\Models\DetailInventory;
use App\Models\KategoriPemakaian;
use Illuminate\Support\Facades\DB;

class GrafikController extends Controller
{
    // FOR BAHAN BAKU
    public function grafikBahanBaku(Request $request)
    {
        $user = auth()->user();
        $id_perusahaan = $user->id_perusahaan;

        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        // 1. Tren Nilai (Rupiah)
        $trenNilai = $this->getTrenNilai($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        // 2. Volume Per Barang (Summary Sidebar) - SEKARANG DENGAN SATUAN
        $volumeBarang = $this->getVolumePerBarang($id_perusahaan, $selectedMonth, $selectedYear, $filterType);

        // 3. Tren Volume Harian (Line Chart Volume)
        $trenVolume = $this->getTrenVolume($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.bahan-baku', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ], $trenNilai, $volumeBarang, $trenVolume));
    }

    private function getTrenNilai($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year')
            ? range(1, 12)
            : range(1, Carbon::create($year, $month)->daysInMonth);

        // Masuk
        $masuk = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) use ($id_perusahaan) {
            $q->where('inventory.id_perusahaan', $id_perusahaan)->where('kode', 'BB');
        })
            ->whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(total_harga) as total"))
            ->groupBy('period')->pluck('total', 'period');

        // Keluar
        $keluar = BarangKeluar::where('barang_keluar.id_perusahaan', $id_perusahaan)
            ->whereHas('DetailInventory.Inventory.Barang.JenisBarang', function ($q) {
                $q->where('kode', 'BB');
            })
            ->whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->select(DB::raw("TO_CHAR(tanggal_keluar, '$format') as period"), DB::raw("SUM(total_harga) as total"))
            ->groupBy('period')->pluck('total', 'period');

        $chartMasuk = [];
        $chartKeluar = [];
        foreach ($range as $r) {
            $key = str_pad($r, 2, '0', STR_PAD_LEFT);
            $chartMasuk[] = (float)($masuk[$key] ?? 0);
            $chartKeluar[] = (float)($keluar[$key] ?? 0);
        }

        return [
            'labels' => $range,
            'chartMasuk' => $chartMasuk,
            'chartKeluar' => $chartKeluar,
            'totalMasuk' => array_sum($chartMasuk),
            'totalKeluar' => array_sum($chartKeluar),
        ];
    }

    private function getVolumePerBarang($id_perusahaan, $month, $year, $filterType)
    {
        // Masuk per Barang + Satuan
        $masuk = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) use ($id_perusahaan) {
            $q->where('inventory.id_perusahaan', $id_perusahaan)->where('kode', 'BB');
        })
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->select('barang.nama_barang', 'barang.satuan', DB::raw('SUM(detail_inventory.jumlah_diterima) as qty'))
            ->groupBy('barang.nama_barang', 'barang.satuan')
            ->get()->keyBy('nama_barang');

        // Keluar per Barang + Satuan
        $keluar = BarangKeluar::where('barang_keluar.id_perusahaan', $id_perusahaan)
            ->whereHas('DetailInventory.Inventory.Barang.JenisBarang', function ($q) {
                $q->where('kode', 'BB');
            })
            ->join('detail_inventory', 'barang_keluar.id_detail_inventory', '=', 'detail_inventory.id')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->select('barang.nama_barang', 'barang.satuan', DB::raw('SUM(barang_keluar.jumlah_keluar) as qty'))
            ->groupBy('barang.nama_barang', 'barang.satuan')
            ->get()->keyBy('nama_barang');

        $itemLabels = array_unique(array_merge($masuk->keys()->toArray(), $keluar->keys()->toArray()));

        $items = [];
        foreach ($itemLabels as $name) {
            $items[] = [
                'name' => $name,
                'satuan' => $masuk[$name]->satuan ?? $keluar[$name]->satuan ?? '-',
                'masuk' => (float)($masuk[$name]->qty ?? 0),
                'keluar' => (float)($keluar[$name]->qty ?? 0),
            ];
        }

        return [
            'items' => $items,
            // Untuk kemudahan di view, kita pisahkan lagi
            'itemLabels' => array_column($items, 'name'),
            'itemMasukQty' => array_column($items, 'masuk'),
            'itemKeluarQty' => array_column($items, 'keluar'),
        ];
    }

    private function getTrenVolume($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year')
            ? range(1, 12)
            : range(1, Carbon::create($year, $month)->daysInMonth);

        // Ambil semua daftar barang dengan kode BB untuk id_perusahaan ini
        $daftarBarang = Barang::where('id_perusahaan', $id_perusahaan)
            ->whereHas('JenisBarang', fn($q) => $q->where('kode', 'BB'))
            ->get();

        $datasetsVolume = [];

        foreach ($daftarBarang as $barang) {
            // Query Masuk per Hari untuk barang spesifik ini
            $masuk = DetailInventory::where('id_inventory', function ($query) use ($barang) {
                $query->select('id')->from('inventory')->where('id_barang', $barang->id);
            })
                ->whereYear('tanggal_masuk', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
                ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(jumlah_diterima) as total"))
                ->groupBy('period')->pluck('total', 'period');

            // Query Keluar per Hari untuk barang spesifik ini
            $keluar = BarangKeluar::whereHas('DetailInventory.Inventory', fn($q) => $q->where('id_barang', $barang->id))
                ->whereYear('tanggal_keluar', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
                ->select(DB::raw("TO_CHAR(tanggal_keluar, '$format') as period"), DB::raw("SUM(jumlah_keluar) as total"))
                ->groupBy('period')->pluck('total', 'period');

            $dataMasuk = [];
            $dataKeluar = [];
            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);
                $dataMasuk[] = (float)($masuk[$key] ?? 0);
                $dataKeluar[] = (float)($keluar[$key] ?? 0);
            }

            // Simpan sebagai dataset terpisah
            $datasetsVolume[] = [
                'label' => $barang->nama_barang . ' (In)',
                'data' => $dataMasuk,
                'satuan' => $barang->satuan, //
                'borderColor' => $this->getRandomColor($barang->id, 'in'),
                'type' => 'in'
            ];
            $datasetsVolume[] = [
                'label' => $barang->nama_barang . ' (Out)',
                'data' => $dataKeluar,
                'satuan' => $barang->satuan, //
                'borderColor' => $this->getRandomColor($barang->id, 'out'),
                'type' => 'out'
            ];
        }

        return [
            'datasetsVolume' => $datasetsVolume
        ];
    }

    // FOR PRODUKSI
    public function grafikProduksi(Request $request)
    {
        $user = auth()->user();
        $id_perusahaan = $user->id_perusahaan;

        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        // 1. Ambil Tren Nilai Harian (Konversi KG)
        $trenProduksi = $this->getTrenProduksiHarian($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        // 2. Data Ringkasan (Summary)
        $summary = $this->getSummaryProduksi($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.produksi', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ], $trenProduksi, $summary));
    }

    private function getSummaryProduksi($id_perusahaan, $filterType, $month, $year)
    {
        $kategoriProduksi = ['FG', 'WIP', 'EC'];

        // 1. Menghitung jumlah jenis barang per kategori (Active Items)
        $countPerKategori = DB::table('detail_inventory')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->where('inventory.id_perusahaan', $id_perusahaan)
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->whereNull('barang.deleted_at')
            ->select('jenis_barang.kode', DB::raw('COUNT(DISTINCT barang.id) as total'))
            ->groupBy('jenis_barang.kode')
            ->pluck('total', 'kode');

        // 2. Total Berat (KG) Stock Masuk
        $totalBerat = DetailInventory::query()
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->where('inventory.id_perusahaan', $id_perusahaan)
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->sum(DB::raw("detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)"));

        // 3. Top Produk
        $topProduk = DetailInventory::query()
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->where('inventory.id_perusahaan', $id_perusahaan)
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->select(
                'barang.nama_barang',
                'barang.satuan',
                DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as total_qty")
            )
            ->groupBy('barang.nama_barang', 'barang.satuan')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 4. Input: Bahan Baku Keluar (BB) dengan kriteria jenis 'Utama'
        $bbKeluar = BarangKeluar::where('barang_keluar.id_perusahaan', $id_perusahaan)
            ->whereHas('DetailInventory.Inventory.Barang', function ($q) {
                $q->where('jenis', 'Utama') // Filter barang->jenis == Utama
                    ->whereHas('JenisBarang', function ($q2) {
                        $q2->where('kode', 'BB'); // Filter barang->jenisBarang->kode == BB
                    });
            })
            ->whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->sum('jumlah_keluar');

        // 5. Output & Grading: Detail Hasil Produksi (Filter Utama & Kode BB)
        $hasilGrading = DetailProduksi::whereHas('Produksi', function ($q) use ($id_perusahaan, $year, $month, $filterType) {
            $q->where('id_perusahaan', $id_perusahaan)
                ->whereYear('tanggal_produksi', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_produksi', $month));
        })
            ->whereHas('Barang', function ($q) {
                $q->where('jenis', 'Utama')
                    ->whereHas('JenisBarang', function ($q2) {
                        $q2->where('kode', 'BB');
                    });
            })
            ->with('Barang')
            ->select(
                'id_barang',
                DB::raw('SUM(total_bb_diterima) as total_bb_diterima'),
                DB::raw('SUM(total_kupas) as total_kupas'),
                DB::raw('SUM(total_a) as total_a'),
                DB::raw('SUM(total_s) as total_s'),
                DB::raw('SUM(total_j) as total_j')
            )
            ->groupBy('id_barang')
            ->get();

        // 6. Kalkulasi Rendemen & Loss yang Benar
        // Input: Bahan Baku Keluar (BB)
        // Output: Total Berat (FG, WIP, EC) yang sudah dikonversi ke KG (dari poin 2)
        $totalOutputProduksi = $totalBerat; // Menggunakan variabel $totalBerat dari poin 2
        $totalPenyusutan = $bbKeluar > $totalOutputProduksi ? ($bbKeluar - $totalOutputProduksi) : 0;

        $persentaseRendemen = $bbKeluar > 0 ? ($totalOutputProduksi / $bbKeluar) * 100 : 0;
        $persentaseLoss = $bbKeluar > 0 ? ($totalPenyusutan / $bbKeluar) * 100 : 0;

        // Ringkasan untuk Widget (Summary Total)
        $summaryGrading = (object)[
            'total_kupas' => $hasilGrading->sum('total_kupas'),
            'total_a' => $hasilGrading->sum('total_a'),
            'total_s' => $hasilGrading->sum('total_s'),
            'total_j' => $hasilGrading->sum('total_j'),
        ];

        return [
            'countPerKategori' => $countPerKategori,
            'totalBerat' => $totalOutputProduksi,
            'topProduk' => $topProduk,
            'bbKeluar' => $bbKeluar,
            'hasilGrading' => $hasilGrading,
            'grading' => $summaryGrading,
            'totalPenyusutan' => $totalPenyusutan,
            'persentaseRendemen' => $persentaseRendemen,
            'persentaseLoss' => $persentaseLoss,
            'comparisonData' => [
                'labels' => ['Bahan Baku (Input)', 'Hasil Produksi (Output)', 'Loss'],
                'values' => [(float)$bbKeluar, (float)$totalOutputProduksi, (float)$totalPenyusutan]
            ]
        ];
    }

    private function getTrenProduksiHarian($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, \Carbon\Carbon::create($year, $month)->daysInMonth);
        $kategoriProduksi = ['FG', 'WIP', 'EC'];

        $daftarBarang = Barang::where('id_perusahaan', $id_perusahaan)
            ->whereHas('JenisBarang', fn($q) => $q->whereIn('kode', $kategoriProduksi))
            ->get();

        $datasetsProduksi = [];
        foreach ($daftarBarang as $barang) {
            $data = DetailInventory::where('id_inventory', function ($query) use ($barang) {
                $query->select('id')->from('inventory')->where('id_barang', $barang->id);
            })
                ->whereYear('tanggal_masuk', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
                ->select(
                    DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"),
                    DB::raw("SUM(jumlah_diterima) as total") // Hanya sum jumlah_diterima
                )
                ->groupBy('period')
                ->pluck('total', 'period');

            $dailyData = [];
            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);
                $dailyData[] = (float)($data[$key] ?? 0);
            }

            if (array_sum($dailyData) > 0) {
                $datasetsProduksi[] = [
                    'label' => $barang->nama_barang,
                    'data' => $dailyData,
                    'satuan' => $barang->satuan,
                    'borderColor' => $this->getRandomColor($barang->id, 'in'),
                    'backgroundColor' => 'transparent'
                ];
            }
        }

        return [
            'labels' => $range,
            'datasetsProduksi' => $datasetsProduksi
        ];
    }

    private function getRandomColor($id, $type)
    {
        $hue = ($id * 137.508) % 360;
        return $type === 'in' ? "hsla($hue, 70%, 50%, 1)" : "hsla($hue, 40%, 40%, 0.8)";
    }

    // FOR PEMAKAIAN
    public function grafikPemakaian(Request $request)
    {
        $user = auth()->user();
        $id_perusahaan = $user->id_perusahaan;

        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        // Ambil Summary & Perbandingan (Poin 1, 2, 5, 6)
        $summaryData = $this->getSummaryPemakaianData($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        // Ambil Data Grafik Tren (Poin 3 & 4)
        $chartData = $this->getChartPemakaianData($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.pemakaian', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ], $summaryData, $chartData));
    }

    private function getSummaryPemakaianData($id_perusahaan, $filterType, $month, $year)
    {
        // Ambil kategori dan hitung sum pemakaian
        $categories = KategoriPemakaian::where('id_perusahaan', $id_perusahaan)
            ->withSum(['Pemakaian' => function ($q) use ($year, $month, $filterType) {
                $q->whereYear('tanggal_pemakaian', $year)
                    ->when($filterType === 'month', fn($q2) => $q2->whereMonth('tanggal_pemakaian', $month));
            }], 'total_harga')
            ->withSum(['Pemakaian' => function ($q) use ($year, $month, $filterType) {
                $q->whereYear('tanggal_pemakaian', $year)
                    ->when($filterType === 'month', fn($q2) => $q2->whereMonth('tanggal_pemakaian', $month));
            }], 'jumlah')
            ->get();

        // Bandingkan biaya pemakaian dengan pengeluaran berdasarkan sub_kategori (Poin 6)
        $comparison = $categories->map(function ($cat) use ($id_perusahaan, $year, $month, $filterType) {
            $biayaKeluar = Pengeluaran::where('id_perusahaan', $id_perusahaan)
                ->where('sub_kategori', $cat->nama_kategori)
                ->whereYear('tanggal_pengeluaran', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))
                ->sum('jumlah_pengeluaran');

            return [
                'nama' => $cat->nama_kategori,
                'satuan' => $cat->satuan,
                'jumlah' => $cat->pemakaian_sum_jumlah ?? 0,
                'biaya_pakai' => $cat->pemakaian_sum_total_harga ?? 0,
                'biaya_keluar' => $biayaKeluar
            ];
        });

        $totalOperasional = Pengeluaran::where('id_perusahaan', $id_perusahaan)
            ->where('kategori', 'OPERASIONAL')
            ->whereYear('tanggal_pengeluaran', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))
            ->sum('jumlah_pengeluaran');

        return [
            'dataSummary' => $comparison,
            'totalOperasional' => $totalOperasional,
            'totalSemuaPemakaian' => $categories->sum('pemakaian_sum_total_harga')
        ];
    }

    private function getChartPemakaianData($id_perusahaan, $filterType, $month, $year)
    {
        if ($filterType === 'month') {
            $days = \Carbon\Carbon::create($year, $month)->daysInMonth;
            $range = range(1, $days);
            $format = 'DD';
        } else {
            $range = range(1, 12);
            $format = 'MM';
        }

        $categories = KategoriPemakaian::where('id_perusahaan', $id_perusahaan)->get();
        $dsBiaya = [];
        $dsJumlah = [];

        foreach ($categories as $cat) {
            $raw = Pemakaian::where('id_perusahaan', $id_perusahaan)
                ->where('id_kategori', $cat->id)
                ->whereYear('tanggal_pemakaian', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pemakaian', $month))
                ->select(
                    \DB::raw("TO_CHAR(tanggal_pemakaian, '$format') as pd"),
                    \DB::raw("SUM(total_harga) as val"),
                    \DB::raw("SUM(jumlah) as qty")
                )
                ->groupBy('pd')->get()->keyBy('pd');

            $valPoints = [];
            $qtyPoints = [];

            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);
                $valPoints[] = (float)($raw[$key]->val ?? 0);
                $qtyPoints[] = (float)($raw[$key]->qty ?? 0);
            }

            if (array_sum($valPoints) > 0) {
                $color = $this->getRandomColor($cat->id, 'in'); // Gunakan helper warna Anda
                $dsBiaya[] = [
                    'label' => $cat->nama_kategori,
                    'data' => $valPoints,
                    'borderColor' => $color,
                    'tension' => 0.4,
                    'pointRadius' => 2
                ];
                $dsJumlah[] = [
                    'label' => $cat->nama_kategori,
                    'data' => $qtyPoints,
                    'satuan' => $cat->satuan,
                    'borderColor' => $color,
                    'tension' => 0.4,
                    'pointRadius' => 2
                ];
            }
        }

        return [
            'labels' => $filterType === 'month' ? $range : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'datasetsBiaya' => $dsBiaya,
            'datasetsJumlah' => $dsJumlah
        ];
    }

    // FOR HPP
    public function grafikHpp(Request $request)
    {
        $user = auth()->user();
        $id_perusahaan = $user->id_perusahaan;

        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        $trendHpp = $this->getTrendHppHarian($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.hpp', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ], $trendHpp));
    }

    private function getTrendHppHarian($id_perusahaan, $filterType, $month, $year)
    {
        if ($filterType === 'month') {
            $days = Carbon::create($year, $month)->daysInMonth;
            $range = range(1, $days);
            $format = 'DD';
            $labels = $range;
        } else {
            $range = range(1, 12);
            $format = 'MM';
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        }

        // 1. Ambil Biaya Bahan
        $biayaBahan = BarangKeluar::whereIn('jenis_keluar', ['BAHAN BAKU', 'PRODUKSI'])
            ->where('id_perusahaan', $id_perusahaan)
            ->whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->select(DB::raw("TO_CHAR(tanggal_keluar, '$format') as period"), DB::raw("SUM(total_harga) as total"))
            ->groupBy('period')->pluck('total', 'period');

        // 2. Ambil Biaya Operasional (Harian/Insidentil)
        $biayaOpsHarian = Pengeluaran::where('id_perusahaan', $id_perusahaan)
            ->whereRaw('is_hpp = true')
            ->where('kategori', '!=', 'OPERASIONAL')
            ->whereYear('tanggal_pengeluaran', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))
            ->select(DB::raw("TO_CHAR(tanggal_pengeluaran, '$format') as period"), DB::raw("SUM(jumlah_pengeluaran) as total"))
            ->groupBy('period')->pluck('total', 'period');

        // 3. Ambil Total Biaya Kategori "OPERASIONAL" (Untuk disebar)
        $totalOpsBulanan = Pengeluaran::where('id_perusahaan', $id_perusahaan)
            ->where('kategori', 'OPERASIONAL')
            ->whereYear('tanggal_pengeluaran', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))
            ->sum('jumlah_pengeluaran');

        // 4. Ambil Volume Produksi
        $volumeProduksi = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) {
            $q->whereIn('kode', ['FG', 'WIP', 'EC']);
        })
            ->whereHas('Inventory', fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->select(
                DB::raw("TO_CHAR(detail_inventory.tanggal_masuk, '$format') as period"),
                DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as vol")
            )
            ->groupBy('period')->pluck('vol', 'period');

        // --- LOGIKA ALOKASI ---
        $totalVolPeriode = array_sum($volumeProduksi->toArray());
        $jumlahTitikData = count($range); // Jumlah hari atau bulan

        $chartHpp = [];
        $chartVol = [];
        $rincianHarian = [];

        foreach ($range as $index => $r) {
            $key = str_pad($r, 2, '0', STR_PAD_LEFT);
            $vol = (float)($volumeProduksi[$key] ?? 0);

            // Alokasi Biaya Operasional Tetap
            $opsAlokasi = 0;
            if ($totalOpsBulanan > 0) {
                if ($totalVolPeriode > 0) {
                    // Jika ada produksi di periode ini, alokasikan berdasarkan porsi volume
                    // Namun, jika hari ini volume 0, biaya operasional tetap harus dibagi rata 
                    // agar HPP tetap terhitung (sesuai permintaan user)
                    if ($vol > 0) {
                        $opsAlokasi = ($vol / $totalVolPeriode) * $totalOpsBulanan;
                    } else {
                        // Biaya operasional tetap dibagi rata ke seluruh hari jika volume 0
                        // agar tidak terjadi gap data yang drastis
                        $opsAlokasi = $totalOpsBulanan / $jumlahTitikData;
                    }
                } else {
                    // Jika total volume sebulan nol, bagi rata ke semua hari
                    $opsAlokasi = $totalOpsBulanan / $jumlahTitikData;
                }
            }

            $bahan = (float)($biayaBahan[$key] ?? 0);
            $opsLain = (float)($biayaOpsHarian[$key] ?? 0);
            $totalOps = $opsLain + $opsAlokasi;
            $totalBiaya = $bahan + $totalOps;

            // Hitung HPP: Jika vol 0, gunakan 1 sebagai pembagi agar angka biaya muncul sebagai HPP 
            // atau tetap hitung apa adanya jika ingin melihat "Beban Biaya"
            $hpp = $vol > 0 ? round($totalBiaya / $vol, 2) : $totalBiaya;

            $chartHpp[] = $hpp;
            $chartVol[] = $vol;

            $rincianHarian[] = [
                'label' => $filterType === 'month' ? "Tgl $r" : $labels[$index],
                'biaya_bahan' => $bahan,
                'biaya_ops' => $totalOps,
                'total_biaya' => $totalBiaya,
                'volume' => $vol,
                'hpp' => $hpp
            ];
        }

        return [
            'labels' => $labels,
            'chartHpp' => $chartHpp,
            'chartVol' => $chartVol,
            'rincianHarian' => $rincianHarian,
            'avgHpp' => count(array_filter($chartHpp)) > 0 ? array_sum($chartHpp) / count(array_filter($chartHpp)) : 0
        ];
    }
}
