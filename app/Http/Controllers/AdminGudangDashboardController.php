<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Pemakaian;
use App\Models\Pengeluaran;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use App\Models\KategoriPemakaian;

class AdminGudangDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $id_perusahaan = $user->id_perusahaan;

        // 1. Logika Filter
        $filterType = $request->get('filter_type', 'month');
        $selectedMonth = $request->get('month', date('m'));
        $selectedYear = $request->get('year', date('Y'));

        // 2. Inisialisasi Label & Rentang Data (Format Carbon)
        if ($filterType === 'year') {
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $range = range(1, 12);
            $format = 'n'; // Format angka bulan tanpa leading zero
        } else {
            $daysInMonth = Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
            $labels = range(1, $daysInMonth);
            $range = $labels;
            $format = 'j'; // Format tanggal tanpa leading zero
        }

        // 3. KPI Stats
        $stats = [
            'total_bahan_baku' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.JenisBarang', fn($q) => $q->where('kode', 'BB'))->count(),
            'total_barang_penolong' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.JenisBarang', fn($q) => $q->where('kode', 'BP'))->count(),
            'total_barang_produksi' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.JenisBarang', fn($q) => $q->whereIn('kode', ['FG', 'WIP', 'EC']))->count(),
            'stok_kritis' => Inventory::where('id_perusahaan', $id_perusahaan)
                ->whereColumn('stok', '<', 'minimum_stok')->count(),
            'produksi_hari_ini' => Produksi::where('id_perusahaan', $id_perusahaan)
                ->whereDate('tanggal_produksi', today())->count(),
            'barang_keluar_hari_ini' => BarangKeluar::where('id_perusahaan', $id_perusahaan)
                ->whereDate('tanggal_keluar', today())->count(),
        ];

        // --- A. Bahan Baku (Dikonversi ke KG jika ada nilai_konversi) ---
        $bbData = DetailInventory::whereHas('Inventory', function ($q) use ($id_perusahaan) {
            $q->where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.JenisBarang', fn($bj) => $bj->where('kode', 'BB'));
        })
            ->whereYear('tanggal_masuk', $selectedYear)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $selectedMonth))
            ->with('Inventory.Barang')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal_masuk)->format($format))
            ->map(fn($group) => $group->sum(
                fn($item) =>
                $item->jumlah_diterima * (float)($item->Inventory->Barang->nilai_konversi ?: 1)
            ));

        // --- 1. Ambil Data Masuk (Produksi Kotor / Gross In) ---
        $prodInRaw = DetailInventory::whereHas('Inventory', function ($q) use ($id_perusahaan) {
            $q->where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.JenisBarang', fn($bj) => $bj->whereIn('kode', ['FG', 'WIP', 'EC']));
        })
            ->whereYear('tanggal_masuk', $selectedYear)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_masuk', $selectedMonth))
            ->with('Inventory.Barang')
            ->get()
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal_masuk)->format($format));

        // --- 2. Ambil Data Afkir & Retur Daur Ulang (Produksi Keluar / Afkir Out) ---
        $prodOutAfkirRaw = BarangKeluar::whereHas('DetailInventory.Inventory', function ($q) use ($id_perusahaan) {
            $q->where('id_perusahaan', $id_perusahaan)
                ->whereHas('Barang.JenisBarang', fn($bj) => $bj->whereIn('kode', ['FG', 'WIP', 'EC']));
        })
            ->where(function ($q) {
                // Tangkap pembuangan stok gudang ATAU barang retur yang didaur ulang
                $q->where('jenis_keluar', 'AFKIR ULANG')
                    ->orWhere('jumlah_dikonversi', '>', 0);
            })
            ->whereYear('tanggal_keluar', $selectedYear)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_keluar', $selectedMonth))
            ->with('DetailInventory.Inventory.Barang')
            ->get()
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal_keluar)->format($format));

        // --- 3. Gabungkan dan Hitung Selisih (Netto) per Periode ---
        $allPeriods = $prodInRaw->keys()->concat($prodOutAfkirRaw->keys())->unique();

        $prodData = $allPeriods->mapWithKeys(function ($period) use ($prodInRaw, $prodOutAfkirRaw) {
            // Hitung Total Masuk di periode ini
            $totalMasuk = $prodInRaw->get($period, collect())->sum(function ($item) {
                $konversi = (float)($item->Inventory->Barang->nilai_konversi ?: 1);
                return $item->jumlah_diterima * $konversi;
            });

            // Hitung Total Afkir/Dikonversi di periode ini
            $totalAfkir = $prodOutAfkirRaw->get($period, collect())->sum(function ($item) {
                // PERBAIKAN: Akses barang melalui DetailInventory
                $barang = $item->DetailInventory->Inventory->Barang;
                $konversi = (float)($barang->nilai_konversi ?: 1);

                /** * LOGIKA PEMOTONGAN (Golden Rule):
                 * Jika jenisnya AFKIR (pembuangan gudang), potong seluruh jumlah_keluar.
                 * Jika selain itu (Retur Penjualan), potong hanya sebesar jumlah_dikonversi.
                 */
                $qtyPotong = ($item->jenis_keluar === 'AFKIR ULANG') ? $item->jumlah_keluar : ($item->jumlah_dikonversi ?? 0);

                return $qtyPotong * $konversi;
            });

            // Hasil Produksi Netto = Masuk - Afkir
            return [$period => max(0, $totalMasuk - $totalAfkir)];
        });

        // --- C. Operasional (Multi-dataset per Kategori) ---
        $pemakaianDatasets = KategoriPemakaian::where('id_perusahaan', $id_perusahaan)
            ->with(['Pemakaian' => function ($q) use ($selectedYear, $selectedMonth, $filterType) {
                $q->whereYear('tanggal_pemakaian', $selectedYear)
                    ->when($filterType === 'month', fn($mq) => $mq->whereMonth('tanggal_pemakaian', $selectedMonth));
            }])
            ->get()
            ->map(function ($kat, $idx) use ($range, $format) {
                $pakeColors = ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'];
                $groupedData = $kat->Pemakaian->groupBy(fn($p) => Carbon::parse($p->tanggal_pemakaian)->format($format));

                return [
                    'label' => $kat->nama_kategori,
                    'data' => collect($range)->map(fn($r) => (float)($groupedData->get($r)?->sum('jumlah') ?? 0))->values(),
                    'borderColor' => $pakeColors[$idx % count($pakeColors)],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 2,
                    'pointRadius' => 0,
                    'tension' => 0.3
                ];
            });

        // --- D. Pengeluaran (Multi-dataset per Kategori) ---
        $pengeluaranDatasets = Pengeluaran::where('id_perusahaan', $id_perusahaan)
            ->whereYear('tanggal_pengeluaran', $selectedYear)
            ->when($filterType === 'month', fn($q) => $q->whereMonth('tanggal_pengeluaran', $selectedMonth))
            ->get()
            ->groupBy('kategori')
            ->values()
            ->map(function ($items, $idx) use ($range, $format) {
                $keluarColors = ['#e11d48', '#4f46e5', '#0891b2', '#16a34a', '#d97706'];
                $groupedData = $items->groupBy(fn($i) => Carbon::parse($i->tanggal_pengeluaran)->format($format));

                return [
                    'label' => $items->first()->kategori,
                    'data' => collect($range)->map(fn($r) => (float)($groupedData->get($r)?->sum('jumlah_pengeluaran') ?? 0))->values(),
                    'borderColor' => $keluarColors[$idx % count($keluarColors)],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 2,
                    'pointRadius' => 0,
                    'tension' => 0.3
                ];
            });

        $chartData = [
            'labels' => $labels,
            'bb_masuk' => collect($range)->map(fn($r) => (float)($bbData->get($r) ?? 0))->values(),
            'hasil_produksi' => collect($range)->map(fn($r) => (float)($prodData->get($r) ?? 0))->values(),
            'pemakaian_datasets' => $pemakaianDatasets,
            'pengeluaran_datasets' => $pengeluaranDatasets,
        ];

        $recent_stock_movements = DetailInventory::with(['Inventory.Barang'])
            ->whereHas('Inventory', fn($q) => $q->where('id_perusahaan', $id_perusahaan))
            ->latest()->take(6)->get();

        return view('pages.dashboard.admingudang', compact('stats', 'recent_stock_movements', 'chartData', 'selectedMonth', 'selectedYear', 'filterType'));
    }
}
