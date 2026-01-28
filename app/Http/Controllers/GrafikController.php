<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Pemakaian;
use App\Models\Perusahaan;
use App\Models\Pengeluaran;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailProduksi;
use App\Models\DetailInventory;
use App\Models\KategoriPemakaian;
use Illuminate\Support\Facades\DB;

class GrafikController extends Controller
{
    private function getCompanyId(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            // Jika request id_perusahaan ada isinya, gunakan itu. Jika kosong (pilih Semua), return null.
            return $request->filled('id_perusahaan') ? $request->id_perusahaan : null;
        }

        return $user->id_perusahaan;
    }

    private function getDaftarPerusahaan()
    {
        return auth()->user()->hasRole('Super Admin') ? Perusahaan::whereNull('deleted_at')->get() : null;
    }

    // --- BAHAN BAKU ---
    public function grafikBahanBaku(Request $request)
    {
        $id_perusahaan = $this->getCompanyId($request);
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        $trenNilai = $this->getTrenNilai($id_perusahaan, $filterType, $selectedMonth, $selectedYear);
        $volumeBarang = $this->getVolumePerBarang($id_perusahaan, $selectedMonth, $selectedYear, $filterType);
        $trenVolume = $this->getTrenVolume($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.bahan-baku', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'daftarPerusahaan' => $this->getDaftarPerusahaan(),
            'selectedIdPerusahaan' => $id_perusahaan
        ], $trenNilai, $volumeBarang, $trenVolume));
    }

    private function getTrenNilai($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, Carbon::create($year, $month)->daysInMonth);

        $masuk = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) use ($id_perusahaan) {
            $q->where('kode', 'BB')
                ->when($id_perusahaan, fn($query) => $query->where('inventory.id_perusahaan', $id_perusahaan));
        })
            ->whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(total_harga) as total"))
            ->groupBy('period')->pluck('total', 'period');

        $keluar = BarangKeluar::when($id_perusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $id_perusahaan))
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
        $masuk = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) use ($id_perusahaan) {
            $q->where('kode', 'BB')
                ->when($id_perusahaan, fn($query) => $query->where('inventory.id_perusahaan', $id_perusahaan));
        })
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->select('barang.nama_barang', 'barang.satuan', DB::raw('SUM(detail_inventory.jumlah_diterima) as qty'))
            ->groupBy('barang.nama_barang', 'barang.satuan')
            ->get()->keyBy('nama_barang');

        $keluar = BarangKeluar::when($id_perusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $id_perusahaan))
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

        return ['items' => $items, 'itemLabels' => array_column($items, 'name'), 'itemMasukQty' => array_column($items, 'masuk'), 'itemKeluarQty' => array_column($items, 'keluar')];
    }

    private function getTrenVolume($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, Carbon::create($year, $month)->daysInMonth);

        // Ambil daftar barang. Jika "Semua", ambil semua barang BB dari semua perusahaan.
        $daftarBarang = Barang::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->whereHas('JenisBarang', fn($q) => $q->where('kode', 'BB'))
            ->get();

        $datasetsVolume = [];
        foreach ($daftarBarang as $barang) {
            $masuk = DetailInventory::where('id_inventory', function ($query) use ($barang) {
                $query->select('id')->from('inventory')->where('id_barang', $barang->id);
            })
                ->whereYear('tanggal_masuk', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
                ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(jumlah_diterima) as total"))
                ->groupBy('period')->pluck('total', 'period');

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

            if (array_sum($dataMasuk) > 0 || array_sum($dataKeluar) > 0) {
                $datasetsVolume[] = ['label' => $barang->nama_barang . ' (In)', 'data' => $dataMasuk, 'satuan' => $barang->satuan, 'borderColor' => $this->getRandomColor($barang->id, 'in'), 'type' => 'in'];
                $datasetsVolume[] = ['label' => $barang->nama_barang . ' (Out)', 'data' => $dataKeluar, 'satuan' => $barang->satuan, 'borderColor' => $this->getRandomColor($barang->id, 'out'), 'type' => 'out'];
            }
        }

        return ['datasetsVolume' => $datasetsVolume];
    }

    // --- PRODUKSI ---
    public function grafikProduksi(Request $request)
    {
        $id_perusahaan = $this->getCompanyId($request);
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        $trenProduksi = $this->getTrenProduksiHarian($id_perusahaan, $filterType, $selectedMonth, $selectedYear);
        $summary = $this->getSummaryProduksi($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.produksi', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'daftarPerusahaan' => $this->getDaftarPerusahaan(),
            'selectedIdPerusahaan' => $id_perusahaan
        ], $trenProduksi, $summary));
    }

    private function getSummaryProduksi($id_perusahaan, $filterType, $month, $year)
    {
        $kategoriProduksi = ['FG', 'WIP', 'EC'];

        $countPerKategori = DB::table('detail_inventory')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($id_perusahaan, fn($q) => $q->where('inventory.id_perusahaan', $id_perusahaan))
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->whereNull('barang.deleted_at')
            ->select('jenis_barang.kode', DB::raw('COUNT(DISTINCT barang.id) as total'))
            ->groupBy('jenis_barang.kode')
            ->pluck('total', 'kode');

        $totalBerat = DetailInventory::query()
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($id_perusahaan, fn($q) => $q->where('inventory.id_perusahaan', $id_perusahaan))
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->sum(DB::raw("detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)"));

        $topProduk = DetailInventory::query()
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($id_perusahaan, fn($q) => $q->where('inventory.id_perusahaan', $id_perusahaan))
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->select('barang.nama_barang', 'barang.satuan', DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as total_qty"))
            ->groupBy('barang.nama_barang', 'barang.satuan')
            ->orderByDesc('total_qty')->take(5)->get();

        $bbKeluar = BarangKeluar::when($id_perusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $id_perusahaan))
            ->whereHas('DetailInventory.Inventory.Barang', function ($q) {
                $q->where('jenis', 'Utama')->whereHas('JenisBarang', fn($q2) => $q2->where('kode', 'BB'));
            })
            ->whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->sum('jumlah_keluar');

        $hasilGrading = DetailProduksi::whereHas('Produksi', function ($q) use ($id_perusahaan, $year, $month, $filterType) {
            $q->when($id_perusahaan, fn($query) => $query->where('id_perusahaan', $id_perusahaan))
                ->whereYear('tanggal_produksi', $year)
                ->when($filterType === 'month', fn($query) => $query->whereMonth('tanggal_produksi', $month));
        })
            ->whereHas('Barang', function ($q) {
                $q->where('jenis', 'Utama')->whereHas('JenisBarang', fn($q2) => $q2->where('kode', 'BB'));
            })
            ->select('id_barang', DB::raw('SUM(total_bb_diterima) as total_bb_diterima'), DB::raw('SUM(total_kupas) as total_kupas'), DB::raw('SUM(total_a) as total_a'), DB::raw('SUM(total_s) as total_s'), DB::raw('SUM(total_j) as total_j'))
            ->groupBy('id_barang')->get();

        $totalPenyusutan = $bbKeluar > $totalBerat ? ($bbKeluar - $totalBerat) : 0;
        $persentaseRendemen = $bbKeluar > 0 ? ($totalBerat / $bbKeluar) * 100 : 0;
        $persentaseLoss = $bbKeluar > 0 ? ($totalPenyusutan / $bbKeluar) * 100 : 0;

        return [
            'countPerKategori' => $countPerKategori,
            'totalBerat' => $totalBerat,
            'topProduk' => $topProduk,
            'bbKeluar' => $bbKeluar,
            'hasilGrading' => $hasilGrading,
            'grading' => (object)['total_kupas' => $hasilGrading->sum('total_kupas'), 'total_a' => $hasilGrading->sum('total_a'), 'total_s' => $hasilGrading->sum('total_s'), 'total_j' => $hasilGrading->sum('total_j')],
            'totalPenyusutan' => $totalPenyusutan,
            'persentaseRendemen' => $persentaseRendemen,
            'persentaseLoss' => $persentaseLoss,
            'comparisonData' => ['labels' => ['Bahan Baku (Input)', 'Hasil Produksi (Output)', 'Loss'], 'values' => [(float)$bbKeluar, (float)$totalBerat, (float)$totalPenyusutan]]
        ];
    }

    private function getTrenProduksiHarian($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, Carbon::create($year, $month)->daysInMonth);
        $kategoriProduksi = ['FG', 'WIP', 'EC'];

        $daftarBarang = Barang::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->whereHas('JenisBarang', fn($q) => $q->whereIn('kode', $kategoriProduksi))->get();

        $datasetsProduksi = [];
        foreach ($daftarBarang as $barang) {
            $data = DetailInventory::where('id_inventory', function ($query) use ($barang) {
                $query->select('id')->from('inventory')->where('id_barang', $barang->id);
            })
                ->whereYear('tanggal_masuk', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
                ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(jumlah_diterima) as total"))
                ->groupBy('period')->pluck('total', 'period');

            $dailyData = [];
            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);
                $dailyData[] = (float)($data[$key] ?? 0);
            }

            if (array_sum($dailyData) > 0) {
                $datasetsProduksi[] = ['label' => $barang->nama_barang, 'data' => $dailyData, 'satuan' => $barang->satuan, 'borderColor' => $this->getRandomColor($barang->id, 'in'), 'backgroundColor' => 'transparent'];
            }
        }
        return ['labels' => $range, 'datasetsProduksi' => $datasetsProduksi];
    }

    // --- PEMAKAIAN ---
    public function grafikPemakaian(Request $request)
    {
        $id_perusahaan = $this->getCompanyId($request);
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        $summaryData = $this->getSummaryPemakaianData($id_perusahaan, $filterType, $selectedMonth, $selectedYear);
        $chartData = $this->getChartPemakaianData($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.pemakaian', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'daftarPerusahaan' => $this->getDaftarPerusahaan(),
            'selectedIdPerusahaan' => $id_perusahaan
        ], $summaryData, $chartData));
    }

    private function getSummaryPemakaianData($id_perusahaan, $filterType, $month, $year)
    {
        $categories = KategoriPemakaian::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->withSum(['Pemakaian' => function ($q) use ($year, $month, $filterType) {
                $q->whereYear('tanggal_pemakaian', $year)->when($filterType === 'month', fn($q2) => $q2->whereMonth('tanggal_pemakaian', $month));
            }], 'total_harga')
            ->withSum(['Pemakaian' => function ($q) use ($year, $month, $filterType) {
                $q->whereYear('tanggal_pemakaian', $year)->when($filterType === 'month', fn($q2) => $q2->whereMonth('tanggal_pemakaian', $month));
            }], 'jumlah')->get();

        $comparison = $categories->map(function ($cat) use ($id_perusahaan, $year, $month, $filterType) {
            $biayaKeluar = Pengeluaran::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
                ->where('sub_kategori', $cat->nama_kategori)->whereYear('tanggal_pengeluaran', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))->sum('jumlah_pengeluaran');

            return ['nama' => $cat->nama_kategori, 'satuan' => $cat->satuan, 'jumlah' => $cat->pemakaian_sum_jumlah ?? 0, 'biaya_pakai' => $cat->pemakaian_sum_total_harga ?? 0, 'biaya_keluar' => $biayaKeluar];
        });

        $totalOperasional = Pengeluaran::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->where('kategori', 'OPERASIONAL')->whereYear('tanggal_pengeluaran', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))->sum('jumlah_pengeluaran');

        return ['dataSummary' => $comparison, 'totalOperasional' => $totalOperasional, 'totalSemuaPemakaian' => $categories->sum('pemakaian_sum_total_harga')];
    }

    private function getChartPemakaianData($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'month') ? 'DD' : 'MM';
        $range = ($filterType === 'month') ? range(1, Carbon::create($year, $month)->daysInMonth) : range(1, 12);

        $categories = KategoriPemakaian::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))->get();
        $dsBiaya = [];
        $dsJumlah = [];

        foreach ($categories as $cat) {
            $raw = Pemakaian::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
                ->where('id_kategori', $cat->id)->whereYear('tanggal_pemakaian', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pemakaian', $month))
                ->select(DB::raw("TO_CHAR(tanggal_pemakaian, '$format') as pd"), DB::raw("SUM(total_harga) as val"), DB::raw("SUM(jumlah) as qty"))
                ->groupBy('pd')->get()->keyBy('pd');

            $valPoints = [];
            $qtyPoints = [];
            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);
                $valPoints[] = (float)($raw[$key]->val ?? 0);
                $qtyPoints[] = (float)($raw[$key]->qty ?? 0);
            }

            if (array_sum($valPoints) > 0) {
                $color = $this->getRandomColor($cat->id, 'in');
                $dsBiaya[] = ['label' => $cat->nama_kategori, 'data' => $valPoints, 'borderColor' => $color, 'tension' => 0.4, 'pointRadius' => 2];
                $dsJumlah[] = ['label' => $cat->nama_kategori, 'data' => $qtyPoints, 'satuan' => $cat->satuan, 'borderColor' => $color, 'tension' => 0.4, 'pointRadius' => 2];
            }
        }
        return ['labels' => ($filterType === 'month' ? $range : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']), 'datasetsBiaya' => $dsBiaya, 'datasetsJumlah' => $dsJumlah];
    }

    // --- HPP ---
    public function grafikHpp(Request $request)
    {
        $id_perusahaan = $this->getCompanyId($request);
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        $trendHpp = $this->getTrendHppHarian($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        return view('pages.grafik.hpp', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'daftarPerusahaan' => $this->getDaftarPerusahaan(),
            'selectedIdPerusahaan' => $id_perusahaan
        ], $trendHpp));
    }

    private function getTrendHppHarian($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'month') ? 'DD' : 'MM';
        $range = ($filterType === 'month') ? range(1, Carbon::create($year, $month)->daysInMonth) : range(1, 12);
        $labels = ($filterType === 'month' ? $range : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']);

        $biayaBahan = BarangKeluar::whereIn('jenis_keluar', ['BAHAN BAKU', 'PRODUKSI'])
            ->when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->whereYear('tanggal_keluar', $year)->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->select(DB::raw("TO_CHAR(tanggal_keluar, '$format') as period"), DB::raw("SUM(total_harga) as total"))
            ->groupBy('period')->pluck('total', 'period');

        $biayaOpsHarian = Pengeluaran::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->whereRaw('is_hpp = true')->where('kategori', '!=', 'OPERASIONAL')
            ->whereYear('tanggal_pengeluaran', $year)->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))
            ->select(DB::raw("TO_CHAR(tanggal_pengeluaran, '$format') as period"), DB::raw("SUM(jumlah_pengeluaran) as total"))
            ->groupBy('period')->pluck('total', 'period');

        $totalOpsBulanan = Pengeluaran::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->where('kategori', 'OPERASIONAL')->whereYear('tanggal_pengeluaran', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))->sum('jumlah_pengeluaran');

        $volumeProduksi = DetailInventory::whereHas('Inventory.Barang.JenisBarang', fn($q) => $q->whereIn('kode', ['FG', 'WIP', 'EC']))
            ->whereHas('Inventory', fn($q) => $q->when($id_perusahaan, fn($query) => $query->where('id_perusahaan', $id_perusahaan)))
            ->whereYear('tanggal_masuk', $year)->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->select(DB::raw("TO_CHAR(detail_inventory.tanggal_masuk, '$format') as period"), DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as vol"))
            ->groupBy('period')->pluck('vol', 'period');

        $totalVolPeriode = array_sum($volumeProduksi->toArray());
        $chartHpp = [];
        $chartVol = [];
        $rincianHarian = [];

        foreach ($range as $index => $r) {
            $key = str_pad($r, 2, '0', STR_PAD_LEFT);
            $vol = (float)($volumeProduksi[$key] ?? 0);
            $opsAlokasi = ($totalOpsBulanan > 0) ? ($totalVolPeriode > 0 ? ($vol > 0 ? ($vol / $totalVolPeriode) * $totalOpsBulanan : $totalOpsBulanan / count($range)) : $totalOpsBulanan / count($range)) : 0;

            $bahan = (float)($biayaBahan[$key] ?? 0);
            $opsLain = (float)($biayaOpsHarian[$key] ?? 0);
            $totalBiaya = $bahan + $opsLain + $opsAlokasi;
            $hpp = $vol > 0 ? round($totalBiaya / $vol, 2) : $totalBiaya;

            $chartHpp[] = $hpp;
            $chartVol[] = $vol;
            $rincianHarian[] = ['label' => $labels[$index], 'biaya_bahan' => $bahan, 'biaya_ops' => ($opsLain + $opsAlokasi), 'total_biaya' => $totalBiaya, 'volume' => $vol, 'hpp' => $hpp];
        }

        return ['labels' => $labels, 'chartHpp' => $chartHpp, 'chartVol' => $chartVol, 'rincianHarian' => $rincianHarian, 'avgHpp' => count(array_filter($chartHpp)) > 0 ? array_sum($chartHpp) / count(array_filter($chartHpp)) : 0];
    }

    private function getRandomColor($id, $type)
    {
        $hue = ($id * 137.508) % 360;
        return $type === 'in' ? "hsla($hue, 70%, 50%, 1)" : "hsla($hue, 40%, 40%, 0.8)";
    }
}
