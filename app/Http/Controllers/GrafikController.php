<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Costumer;
use App\Models\Produksi;
use App\Models\Supplier;
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
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GrafikController extends Controller implements HasMiddleware
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

    public function index()
    {
        $user = auth()->user();

        // Definisikan daftar permission dan rute tujuannya secara berurutan
        $accessMap = [
            'grafik.bahan-baku' => 'grafik.bahan-baku',
            'grafik.produksi'   => 'grafik.produksi',
            'grafik.pemakaian'  => 'grafik.pemakaian',
            'grafik.hpp'        => 'grafik.hpp',
            'grafik.transaksi'  => 'grafik.transaksi',
        ];

        foreach ($accessMap as $permission => $routeName) {
            if ($user->can($permission)) {
                return redirect()->route($routeName);
            }
        }

        // Jika user tidak memiliki satu pun permission di atas
        abort(403, 'Anda tidak memiliki akses ke halaman grafik manapun.');
    }

    public static function middleware(): array
    {
        return [
            // Pasang middleware permission untuk masing-masing method
            new Middleware('permission:grafik.bahan-baku', only: ['grafikBahanBaku']),
            new Middleware('permission:grafik.produksi', only: ['grafikProduksi']),
            new Middleware('permission:grafik.pemakaian', only: ['grafikPemakaian']),
            new Middleware('permission:grafik.hpp', only: ['grafikHpp']),
            new Middleware('permission:grafik.transaksi', only: ['grafikTransaksi']),
        ];
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

        // 1. Hitung Jumlah SKU per Kategori (Tetap)
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

        // ========================================================================
        // 2. MENGHITUNG VOLUME PRODUKSI NETTO & TOP 5 PRODUK
        // ========================================================================

        // A. Ambil total penerimaan kotor per barang (Gross In)
        $grossIn = DetailInventory::query()
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($id_perusahaan, fn($q) => $q->where('inventory.id_perusahaan', $id_perusahaan))
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->select(
                'barang.id',
                'barang.nama_barang',
                'barang.satuan',
                DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as total_in")
            )
            ->groupBy('barang.id', 'barang.nama_barang', 'barang.satuan')
            ->get()
            ->keyBy('id');

        // B. Ambil total pengeluaran KHUSUS yang didaur ulang (Afkir Retur & Afkir Gudang)
        $afkirOut = BarangKeluar::query()
            ->join('detail_inventory', 'barang_keluar.id_detail_inventory', '=', 'detail_inventory.id')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($id_perusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $id_perusahaan))
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->where(function ($q) {
                $q->where('barang_keluar.jumlah_dikonversi', '>', 0)
                    ->orWhere('barang_keluar.jenis_keluar', 'AFKIR ULANG');
            })
            ->whereYear('barang_keluar.tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('barang_keluar.tanggal_keluar', $month))
            ->select(
                'barang.id',
                DB::raw("SUM(
                    (CASE 
                        WHEN barang_keluar.jenis_keluar = 'AFKIR ULANG' THEN barang_keluar.jumlah_keluar 
                        ELSE COALESCE(barang_keluar.jumlah_dikonversi, 0) 
                    END) * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)
                ) as total_out")
            )
            ->groupBy('barang.id')
            ->pluck('total_out', 'id');

        // C. Hitung Netto Total Berat Keseluruhan Produksi
        $totalGrossIn = $grossIn->sum('total_in');
        $totalAfkirOut = $afkirOut->sum();
        // Total Berat = Masuk - Keluar (Jika minus, set 0)
        $totalBerat = max(0, $totalGrossIn - $totalAfkirOut);

        // D. Hitung Netto Per Produk untuk Ranking Top 5
        $topProduk = $grossIn->map(function ($item, $id) use ($afkirOut) {
            $out = $afkirOut->get($id, 0); // Ambil jumlah keluarnya jika ada
            return (object)[
                'nama_barang' => $item->nama_barang,
                'satuan'      => $item->satuan,
                'total_qty'   => max(0, $item->total_in - $out) // Kurangi gross dengan afkir
            ];
        })->filter(function ($item) {
            return $item->total_qty > 0; // Sembunyikan barang yang stok volumenya habis karena diafkir semua
        })->sortByDesc('total_qty')
            ->take(5)
            ->values(); // Reset key array

        // ========================================================================
        // 3. DATA BAHAN BAKU & GRADING (Tetap Sama)
        // ========================================================================
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
            'countPerKategori'   => $countPerKategori,
            'totalBerat'         => $totalBerat,
            'topProduk'          => $topProduk,
            'bbKeluar'           => $bbKeluar,
            'hasilGrading'       => $hasilGrading,
            'grading'            => (object)['total_kupas' => $hasilGrading->sum('total_kupas'), 'total_a' => $hasilGrading->sum('total_a'), 'total_s' => $hasilGrading->sum('total_s'), 'total_j' => $hasilGrading->sum('total_j')],
            'totalPenyusutan'    => $totalPenyusutan,
            'persentaseRendemen' => $persentaseRendemen,
            'persentaseLoss'     => $persentaseLoss,
            'comparisonData'     => ['labels' => ['Bahan Baku (Input)', 'Hasil Produksi (Output)', 'Loss'], 'values' => [(float)$bbKeluar, (float)$totalBerat, (float)$totalPenyusutan]]
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
            // 1. AMBIL BARANG MASUK (Produksi Kotor)
            $dataMasuk = DetailInventory::where('id_inventory', function ($query) use ($barang) {
                $query->select('id')->from('inventory')->where('id_barang', $barang->id);
            })
                ->whereYear('tanggal_masuk', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
                ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(jumlah_diterima) as total"))
                ->groupBy('period')->pluck('total', 'period');

            // 2. AMBIL BARANG KELUAR (Khusus Afkir Gudang & Retur Daur Ulang)
            $dataAfkir = BarangKeluar::whereHas('DetailInventory.Inventory', fn($q) => $q->where('id_barang', $barang->id))
                ->where(function ($q) {
                    $q->where('jumlah_dikonversi', '>', 0)
                        ->orWhere('jenis_keluar', 'AFKIR ULANG');
                })
                ->when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
                ->whereYear('tanggal_keluar', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
                ->select(
                    DB::raw("TO_CHAR(tanggal_keluar, '$format') as period"),
                    DB::raw("SUM(CASE WHEN jenis_keluar = 'AFKIR ULANG' THEN jumlah_keluar ELSE COALESCE(jumlah_dikonversi, 0) END) as total_afkir")
                )
                ->groupBy('period')->pluck('total_afkir', 'period');

            // 3. GABUNGKAN MENJADI PRODUKSI NETTO HARIAN/BULANAN
            $dailyData = [];
            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);

                $masuk = (float)($dataMasuk[$key] ?? 0);
                $afkir = (float)($dataAfkir[$key] ?? 0);

                // Produksi Bersih = Masuk Kotor - Afkir
                $dailyData[] = $masuk - $afkir;
            }

            // Validasi: Tampilkan di grafik jika ada pergerakan (masuk/keluar)
            if (array_sum(array_map('abs', $dailyData)) > 0) {
                $datasetsProduksi[] = [
                    'label' => $barang->nama_barang,
                    'data' => $dailyData,
                    'satuan' => $barang->satuan,
                    'borderColor' => $this->getRandomColor($barang->id, 'in'),
                    'backgroundColor' => 'transparent'
                ];
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
        // 1. Persiapkan format tanggal dan range (X-Axis)
        $format = ($filterType === 'month') ? 'DD' : 'MM';
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $range = ($filterType === 'month') ? range(1, $daysInMonth) : range(1, 12);

        // 2. Ambil semua kategori yang relevan
        $categories = KategoriPemakaian::when($id_perusahaan, function ($q) use ($id_perusahaan) {
            return $q->where('id_perusahaan', $id_perusahaan);
        })->get();

        $dsBiaya = [];
        $dsJumlah = [];

        // 3. Loop setiap kategori untuk mengambil data pemakaian
        foreach ($categories as $cat) {
            $raw = Pemakaian::when($id_perusahaan, function ($q) use ($id_perusahaan) {
                return $q->where('id_perusahaan', $id_perusahaan);
            })
                ->where('id_kategori', $cat->id)
                ->whereYear('tanggal_pemakaian', $year)
                ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pemakaian', $month))
                ->select(
                    DB::raw("TO_CHAR(tanggal_pemakaian, '$format') as pd"),
                    DB::raw("SUM(total_harga) as val"),
                    DB::raw("SUM(jumlah) as qty")
                )
                ->groupBy('pd')
                ->get()
                ->keyBy('pd');

            $valPoints = [];
            $qtyPoints = [];

            // 4. Map data ke dalam range (mengisi 0 jika data tidak ada di tanggal tersebut)
            foreach ($range as $r) {
                $key = str_pad($r, 2, '0', STR_PAD_LEFT);
                $valPoints[] = (float)($raw[$key]->val ?? 0);
                $qtyPoints[] = (float)($raw[$key]->qty ?? 0);
            }

            /**
             * PERBAIKAN LOGIKA: 
             * Gunakan operator OR (||) agar jika salah satu array memiliki isi (> 0), 
             * maka dataset akan tetap ditampilkan ke chart.
             */
            if (array_sum($valPoints) > 0 || array_sum($qtyPoints) > 0) {
                $color = $this->getRandomColor($cat->id, 'in');

                // Dataset untuk Chart Biaya
                $dsBiaya[] = [
                    'label'       => $cat->nama_kategori,
                    'data'        => $valPoints,
                    'borderColor' => $color,
                    'backgroundColor' => $color, // Opsional: untuk titik/fill
                    'tension'     => 0.4,
                    'pointRadius' => 2
                ];

                // Dataset untuk Chart Jumlah (Quantity)
                $dsJumlah[] = [
                    'label'       => $cat->nama_kategori,
                    'data'        => $qtyPoints,
                    'satuan'      => $cat->satuan ?? '-',
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'tension'     => 0.4,
                    'pointRadius' => 2
                ];
            }
        }

        // 5. Return hasil akhir untuk dikirim ke View/Frontend
        return [
            'labels' => ($filterType === 'month'
                ? $range
                : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
            ),
            'datasetsBiaya'  => $dsBiaya,
            'datasetsJumlah' => $dsJumlah
        ];
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
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $range = ($filterType === 'month') ? range(1, $daysInMonth) : range(1, 12);
        $labels = ($filterType === 'month' ? $range : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']);
        $paddedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

        // --- A. BIAYA BAHAN BAKU (BB) & BIAYA PRODUKSI (BP) ---
        $barangKeluar = BarangKeluar::join('detail_inventory', 'barang_keluar.id_detail_inventory', '=', 'detail_inventory.id')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($id_perusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $id_perusahaan))
            ->whereYear('barang_keluar.tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('barang_keluar.tanggal_keluar', $month))
            ->whereIn('jenis_barang.kode', ['BB', 'BP'])
            ->select(
                DB::raw("TO_CHAR(barang_keluar.tanggal_keluar, '$format') as period"),
                DB::raw("SUM(CASE WHEN jenis_barang.kode = 'BB' THEN barang_keluar.total_harga ELSE 0 END) as biaya_bb"),
                DB::raw("SUM(CASE WHEN jenis_barang.kode = 'BP' THEN barang_keluar.total_harga ELSE 0 END) as biaya_bp")
            )
            ->groupBy('period')->get()->keyBy('period');

        // --- B. DATA PEMBANTU UNTUK ALOKASI (PEMAKAIAN) ---
        $totalPemakaianPeriode = Pemakaian::join('kategori_pemakaian', 'pemakaian.id_kategori', '=', 'kategori_pemakaian.id')
            ->when($id_perusahaan, fn($q) => $q->where('pemakaian.id_perusahaan', $id_perusahaan))
            ->whereYear('pemakaian.tanggal_pemakaian', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('pemakaian.tanggal_pemakaian', $month))
            ->select('kategori_pemakaian.nama_kategori', DB::raw("SUM(pemakaian.jumlah) as total_qty"))
            ->groupBy('kategori_pemakaian.nama_kategori')->pluck('total_qty', 'nama_kategori');

        $pemakaianHarian = Pemakaian::join('kategori_pemakaian', 'pemakaian.id_kategori', '=', 'kategori_pemakaian.id')
            ->select(DB::raw("TO_CHAR(pemakaian.tanggal_pemakaian, '$format') as period"), 'kategori_pemakaian.nama_kategori', DB::raw("SUM(pemakaian.jumlah) as qty"))
            ->whereYear('pemakaian.tanggal_pemakaian', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('pemakaian.tanggal_pemakaian', $month))
            ->groupBy('period', 'kategori_pemakaian.nama_kategori')->get()->groupBy('nama_kategori');

        // --- C. LOGIKA HARI KERJA (KECUALI MINGGU) ---
        $hariKerjaBulanan = [];
        if ($filterType === 'month') {
            foreach ($range as $r) {
                $date = Carbon::createFromDate($year, $month, $r);
                if (!$date->isSunday()) {
                    $hariKerjaBulanan[] = str_pad($r, 2, '0', STR_PAD_LEFT);
                }
            }
        }

        // --- D. BIAYA PENGELUARAN (OPERASIONAL & LAINNYA) ---
        $biayaOpsDinamis = [];
        $biayaHppLainDinamis = [];

        $semuaPengeluaran = Pengeluaran::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->whereRaw('is_hpp = true')
            ->whereYear('tanggal_pengeluaran', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $month))
            ->get();

        foreach ($semuaPengeluaran as $p) {
            $isOperasional = ($p->kategori === 'OPERASIONAL');
            $targetArray = $isOperasional ? 'biayaOpsDinamis' : 'biayaHppLainDinamis';
            $bulanInput = $p->tanggal_pengeluaran->format('m');
            $tglInput = $p->tanggal_pengeluaran->format('d');

            if ($p->metode_alokasi === 'SPREAD') {
                if ($filterType === 'month') {
                    // --- LOGIKA FILTER BULAN ---
                    $totalQtyPakai = $totalPemakaianPeriode[$p->sub_kategori] ?? 0;

                    if ($totalQtyPakai > 0) {
                        // 1. SPREAD PROPORSIONAL (Berdasarkan Pemakaian misal Listrik/Gas)
                        $rincianPakai = $pemakaianHarian[$p->sub_kategori] ?? [];
                        foreach ($rincianPakai as $pakai) {
                            $alokasi = ($pakai->qty / $totalQtyPakai) * $p->jumlah_pengeluaran;
                            ${$targetArray}[$pakai->period] = (${$targetArray}[$pakai->period] ?? 0) + $alokasi;
                        }
                    } else {
                        // 2. SPREAD FLAT (Bagi Rata ke Hari Kerja, misal Gaji/Sewa)
                        $divisor = count($hariKerjaBulanan);
                        $biayaPerHari = $divisor > 0 ? ($p->jumlah_pengeluaran / $divisor) : 0;
                        foreach ($hariKerjaBulanan as $key) {
                            ${$targetArray}[$key] = (${$targetArray}[$key] ?? 0) + $biayaPerHari;
                        }
                    }
                } else {
                    // --- LOGIKA FILTER TAHUN ---
                    // Tetap di bulan transaksi tersebut
                    ${$targetArray}[$bulanInput] = (${$targetArray}[$bulanInput] ?? 0) + $p->jumlah_pengeluaran;
                }
            } else {
                // --- LOGIKA FIXED ---
                $key = ($filterType === 'month') ? $tglInput : $bulanInput;
                ${$targetArray}[$key] = (${$targetArray}[$key] ?? 0) + $p->jumlah_pengeluaran;
            }
        }

        // --- E. VOLUME PRODUKSI (DENGAN PENYESUAIAN AFKIR) ---

        // 1. Ambil Volume Masuk
        $volumeMasuk = DetailInventory::join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->whereIn('jenis_barang.kode', ['FG', 'WIP', 'EC'])
            ->when($id_perusahaan, fn($q) => $q->where('inventory.id_perusahaan', $id_perusahaan))
            ->whereYear('detail_inventory.tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('detail_inventory.tanggal_masuk', $month))
            ->select(
                DB::raw("TO_CHAR(detail_inventory.tanggal_masuk, '$format') as period"),
                DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as vol")
            )
            ->groupBy('period')->pluck('vol', 'period');

        // 2. Ambil Volume Keluar (Khusus Afkir yang Didaur Ulang / Afkir Gudang)
        $volumeKeluarAfkir = BarangKeluar::join('detail_inventory', 'barang_keluar.id_detail_inventory', '=', 'detail_inventory.id')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->whereIn('jenis_barang.kode', ['FG', 'WIP', 'EC'])
            // PERBAIKAN: Tangkap keduanya
            ->where(function ($q) {
                $q->where('barang_keluar.jumlah_dikonversi', '>', 0)
                    ->orWhere('barang_keluar.jenis_keluar', 'AFKIR ULANG');
            })
            ->when($id_perusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $id_perusahaan))
            ->whereYear('barang_keluar.tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('barang_keluar.tanggal_keluar', $month))
            ->select(
                DB::raw("TO_CHAR(barang_keluar.tanggal_keluar, '$format') as period"),
                // PERBAIKAN: Gunakan logika CASE WHEN
                DB::raw("SUM(
                    (CASE 
                        WHEN barang_keluar.jumlah_dikonversi > 0 THEN barang_keluar.jumlah_dikonversi 
                        ELSE barang_keluar.jumlah_keluar 
                    END) * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)
                ) as vol")
            )
            ->groupBy('period')->pluck('vol', 'period');

        // 3. Kalkulasi Netto
        $volumeProduksi = [];
        foreach ($range as $r) {
            $key = str_pad($r, 2, '0', STR_PAD_LEFT);
            $masuk = (float)($volumeMasuk[$key] ?? 0);
            $keluarAfkir = (float)($volumeKeluarAfkir[$key] ?? 0);

            // Netto = Masuk - Keluar Afkir
            $volumeProduksi[$key] = $masuk - $keluarAfkir;
        }

        // --- F. PENGGABUNGAN DATA ---
        $rincianHarian = [];
        $chartHpp = [];
        $chartVol = [];

        foreach ($range as $index => $r) {
            $key = str_pad($r, 2, '0', STR_PAD_LEFT);

            $vol     = (float)($volumeProduksi[$key] ?? 0);
            $bb      = (float)($barangKeluar[$key]->biaya_bb ?? 0);
            $bp      = (float)($barangKeluar[$key]->biaya_bp ?? 0);
            $ops     = (float)($biayaOpsDinamis[$key] ?? 0);
            $hppLain = (float)($biayaHppLainDinamis[$key] ?? 0);

            $totalBiaya = $bb + $bp + $ops + $hppLain;
            $hpp = $vol > 0 ? round($totalBiaya / $vol, 2) : 0;

            $chartHpp[] = $hpp;
            $chartVol[] = $vol;

            $rincianHarian[] = [
                'label'       => $labels[$index],
                'tgl_raw'     => ($filterType === 'month') ? "$year-$paddedMonth-$key" : null,
                'biaya_bb'    => $bb,
                'biaya_bp'    => $bp,
                'biaya_ops'   => $ops,
                'biaya_lain'  => $hppLain,
                'total_biaya' => $totalBiaya,
                'volume'      => $vol,
                'hpp'         => $hpp
            ];
        }

        return [
            'labels'        => $labels,
            'chartHpp'      => $chartHpp,
            'chartVol'      => $chartVol,
            'rincianHarian' => $rincianHarian,
            'avgHpp'        => count(array_filter($chartHpp)) > 0 ? array_sum($chartHpp) / count(array_filter($chartHpp)) : 0
        ];
    }

    // --- TRANSAKSI ---
    public function grafikTransaksi(Request $request)
    {
        $id_perusahaan = $this->getCompanyId($request);
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = (int)$request->get('month', date('m'));
        $selectedYear = (int)$request->get('year', date('Y'));

        $filterSupplier = $request->get('id_supplier');
        $filterCostumer = $request->get('id_costumer');

        // 1. Tren Nilai Transaksi (Rp)
        $trenNilai = $this->getTrenTransaksi($id_perusahaan, $filterType, $selectedMonth, $selectedYear);

        // 2. Data Masuk - Dipisah berdasarkan Jenis Supplier
        $masukBB = $this->getMasukTrendDataByJenis($id_perusahaan, $filterType, $selectedMonth, $selectedYear, $filterSupplier, 'Bahan Baku');
        $masukBarang = $this->getMasukTrendDataByJenis($id_perusahaan, $filterType, $selectedMonth, $selectedYear, $filterSupplier, 'Barang');

        // 3. Data Keluar
        $keluarData = $this->getKeluarTrendData($id_perusahaan, $filterType, $selectedMonth, $selectedYear, $filterCostumer);

        // Data Filter UI
        $listSuppliers = Supplier::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))->get();
        $listCostumers = Costumer::when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))->get();

        return view('pages.grafik.transaksi', array_merge([
            'filterType' => $filterType,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'daftarPerusahaan' => $this->getDaftarPerusahaan(),
            'selectedIdPerusahaan' => $id_perusahaan,
            'listSuppliers' => $listSuppliers,
            'listCostumers' => $listCostumers,
            'filterSupplier' => $filterSupplier,
            'filterCostumer' => $filterCostumer,
            'masukBB' => $masukBB,
            'masukBarang' => $masukBarang,
        ], $trenNilai, $keluarData));
    }

    private function getTrenTransaksi($id_perusahaan, $filterType, $month, $year)
    {
        $format = ($filterType === 'year') ? 'MM' : 'DD';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, Carbon::create($year, $month)->daysInMonth);

        $masukHarian = DetailInventory::whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->when($id_perusahaan, fn($q) => $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $id_perusahaan)))
            ->select(DB::raw("TO_CHAR(tanggal_masuk, '$format') as period"), DB::raw("SUM(total_harga) as total"))
            ->groupBy('period')->pluck('total', 'period');

        $keluarHarian = BarangKeluar::whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            // PERBAIKAN: Hitung Total Rupiah Netto ((Keluar - Retur) * Harga)
            ->select(
                DB::raw("TO_CHAR(tanggal_keluar, '$format') as period"),
                DB::raw("SUM((jumlah_keluar - COALESCE(jumlah_dikonversi, 0)) * harga) as total")
            )
            ->groupBy('period')->pluck('total', 'period');

        $chartMasuk = [];
        $chartKeluar = [];
        foreach ($range as $r) {
            $key = str_pad($r, 2, '0', STR_PAD_LEFT);
            $chartMasuk[] = (float)($masukHarian[$key] ?? 0);
            $chartKeluar[] = (float)($keluarHarian[$key] ?? 0);
        }

        return ['labels' => $range, 'chartMasuk' => $chartMasuk, 'chartKeluar' => $chartKeluar];
    }

    private function getMasukTrendDataByJenis($id_perusahaan, $filterType, $month, $year, $filterSupplier, $jenis)
    {
        $format = ($filterType === 'year') ? 'm' : 'd';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, Carbon::create($year, $month)->daysInMonth);

        $data = DetailInventory::with(['Inventory.Barang', 'Supplier'])
            ->whereYear('tanggal_masuk', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $month))
            ->whereHas('Supplier', fn($q) => $q->where('jenis_supplier', $jenis))
            ->when($id_perusahaan, fn($q) => $q->whereHas('Inventory', fn($i) => $i->where('id_perusahaan', $id_perusahaan)))
            ->when($filterSupplier, fn($q) => $q->where('id_supplier', $filterSupplier))
            ->get();

        $trend = $data->groupBy('id_supplier')->map(function ($items, $id) use ($range, $format) {
            $points = [];
            foreach ($range as $r) {
                $points[] = (float)$items->filter(fn($i) => (int)Carbon::parse($i->tanggal_masuk)->format($format) === $r)->sum('jumlah_diterima');
            }

            // PERBAIKAN: Mengambil SEMUA rincian barang unik dalam satu supplier
            $barangRincian = $items->groupBy('Inventory.id_barang')->map(function ($b) {
                $brg = $b->first()->Inventory->Barang;
                return [
                    'nama' => $brg->nama_barang ?? 'Unknown',
                    'total' => $b->sum('jumlah_diterima'),
                    'satuan' => $brg->satuan ?? 'Unit'
                ];
            })->values()->toArray();

            return [
                'label' => optional($items->first()->Supplier)->nama_supplier ?? 'Tanpa Nama',
                'data' => $points,
                'total_volume' => $items->sum('jumlah_diterima'),
                'barang' => $barangRincian,
                'borderColor' => $this->getRandomColor($id, 'in')
            ];
        })->filter(fn($item) => $item['total_volume'] > 0);

        return [
            'chart' => $trend->values()->toArray(),
            'table' => $trend
        ];
    }

    private function getKeluarTrendData($id_perusahaan, $filterType, $month, $year, $filterCostumer)
    {
        $format = ($filterType === 'year') ? 'm' : 'd';
        $range = ($filterType === 'year') ? range(1, 12) : range(1, Carbon::create($year, $month)->daysInMonth);

        $data = BarangKeluar::with(['DetailInventory.Inventory.Barang.JenisBarang', 'Costumer'])
            ->whereYear('tanggal_keluar', $year)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $month))
            ->when($id_perusahaan, fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->when($filterCostumer, fn($q) => $q->where('id_costumer', $filterCostumer))
            ->get()
            ->filter(function ($item) {
                $kode = optional(optional(optional($item->DetailInventory)->Inventory)->Barang->JenisBarang)->kode;
                return in_array($kode, ['FG', 'WIP', 'EC']);
            });

        $trend = $data->groupBy('id_costumer')->map(function ($items, $id) use ($range, $format) {
            $points = [];
            foreach ($range as $r) {
                // PERBAIKAN: Hitung titik grafik berdasarkan Netto (Keluar - Dikonversi)
                $points[] = (float)$items->filter(fn($i) => (int)Carbon::parse($i->tanggal_keluar)->format($format) === $r)
                    ->sum(fn($item) => $item->jumlah_keluar - ($item->jumlah_dikonversi ?? 0));
            }

            // PERBAIKAN: Rincian barang unik dalam satu costumer (Netto)
            $barangRincian = $items->groupBy('DetailInventory.Inventory.id_barang')->map(function ($b) {
                $brg = $b->first()->DetailInventory->Inventory->Barang;
                return [
                    'nama' => $brg->nama_barang ?? 'Unknown',
                    'total' => $b->sum('jumlah_keluar') - $b->sum('jumlah_dikonversi'), // NETTO
                    'satuan' => $brg->satuan ?? 'Unit'
                ];
            })->values()->toArray();

            return [
                'label' => optional($items->first()->Costumer)->nama_costumer ?? 'Tanpa Nama',
                'data' => $points,
                // PERBAIKAN: Total Keseluruhan Volume (Netto)
                'total_volume' => $items->sum('jumlah_keluar') - $items->sum('jumlah_dikonversi'),
                'barang' => $barangRincian,
                'borderColor' => $this->getRandomColor($id, 'out')
            ];
        })->filter(fn($item) => $item['total_volume'] > 0); // Sembunyikan jika volume 0 karena di-retur semua

        return [
            'dsCostumerTrend' => $trend->values()->toArray(),
            'rincianCostumerTable' => $trend
        ];
    }

    private function getRandomColor($id, $type)
    {
        $hue = (int) ((int) $id * 137.508) % 360;

        return $type === 'in' ? "hsla($hue, 70%, 50%, 1)" : "hsla($hue, 40%, 40%, 0.8)";
    }
}
