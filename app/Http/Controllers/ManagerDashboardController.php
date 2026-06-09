<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\DetailInventory;
use App\Models\DetailProduksi;
use App\Models\Pengeluaran;
use App\Models\Produksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $idPerusahaan = $user->hasRole('Super Admin') ? null : $user->id_perusahaan;

        // Tambahkan inisialisasi waktu agar query tidak error
        $month = now()->month;
        $year = now()->year;
        $kategoriProduksi = ['FG', 'WIP', 'EC'];

        // --- DATA BASIC ---
        $totalProducts = Barang::when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))->count();

        // --- LOGIKA VOLUME PRODUKSI (DENGAN KONVERSI) ---
        $volumeProduksiMap = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) use ($kategoriProduksi) {
            $q->whereIn('kode', $kategoriProduksi);
        })
            ->whereHas('Inventory', function ($q) use ($idPerusahaan) {
                $q->when($idPerusahaan, fn($query) => $query->where('id_perusahaan', $idPerusahaan));
            })
            ->whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year)
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->select(DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as vol"))
            ->first();

        $totalVolumeProduksi = $volumeProduksiMap->vol ?? 0;

        $jenisBarangDiproduksi = DetailInventory::whereHas('Inventory.Barang.JenisBarang', function ($q) use ($kategoriProduksi) {
            $q->whereIn('kode', $kategoriProduksi);
        })
            ->whereHas('Inventory', function ($q) use ($idPerusahaan) {
                $q->when($idPerusahaan, fn($query) => $query->where('id_perusahaan', $idPerusahaan));
            })
            ->whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year)
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->distinct('inventory.id_barang')
            ->count('inventory.id_barang');

        // --- TOTAL BAHAN BAKU UTAMA MASUK ---
        $totalBahanBakuMasuk = DetailInventory::whereHas('Inventory.Barang', function ($q) {
            $q->where('jenis', 'Utama');
        })
            ->whereHas('Inventory.Barang.JenisBarang', function ($q) {
                $q->where('kode', 'BB');
            })
            ->whereHas('Inventory', function ($q) use ($idPerusahaan) {
                $q->when($idPerusahaan, fn($query) => $query->where('id_perusahaan', $idPerusahaan));
            })
            ->whereMonth('tanggal_masuk', $month)
            ->whereYear('tanggal_masuk', $year)
            ->sum('jumlah_diterima');

        $totalPengeluaran = Pengeluaran::when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->whereMonth('tanggal_pengeluaran', $month)
            ->whereYear('tanggal_pengeluaran', $year)
            ->sum('jumlah_pengeluaran');

        // --- DATA RENDEMEN (OUTPUT VS INPUT BB) ---
        $bbKeluar = BarangKeluar::when($idPerusahaan, fn($q) => $q->where('barang_keluar.id_perusahaan', $idPerusahaan))
            ->whereHas('DetailInventory.Inventory.Barang', function ($q) {
                $q->where('jenis', 'Utama')->whereHas('JenisBarang', fn($q2) => $q2->where('kode', 'BB'));
            })
            ->whereYear('tanggal_keluar', $year)
            ->whereMonth('tanggal_keluar', $month)
            ->sum('jumlah_keluar');

        $topProduk = DetailInventory::query()
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->join('barang', 'inventory.id_barang', '=', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($idPerusahaan, fn($q) => $q->where('inventory.id_perusahaan', $idPerusahaan))
            ->whereIn('jenis_barang.kode', ['FG', 'WIP', 'EC'])
            ->whereYear('detail_inventory.tanggal_masuk', now()->year)
            ->whereMonth('detail_inventory.tanggal_masuk', now()->month)
            ->select(
                'barang.nama_barang',
                'barang.satuan',
                DB::raw("SUM(detail_inventory.jumlah_diterima) as qty_asli"),
                DB::raw("SUM(detail_inventory.jumlah_diterima * COALESCE(NULLIF(barang.nilai_konversi, '')::numeric, 1)) as total_qty")
            )
            ->groupBy('barang.nama_barang', 'barang.satuan')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $persentaseRendemen = $bbKeluar > 0 ? ($totalVolumeProduksi / $bbKeluar) * 100 : 0;
        $totalPenyusutan = $bbKeluar > $totalVolumeProduksi ? ($bbKeluar - $totalVolumeProduksi) : 0;
        $persentaseLoss = $bbKeluar > 0 ? ($totalPenyusutan / $bbKeluar) * 100 : 0;

        // --- DATA GRADING ---
        $hasilGradingRaw = DetailProduksi::whereHas('Produksi', function ($q) use ($idPerusahaan, $year, $month) {
            $q->when($idPerusahaan, fn($query) => $query->where('id_perusahaan', $idPerusahaan))
                ->whereYear('tanggal_produksi', $year)
                ->whereMonth('tanggal_produksi', $month);
        })
            ->whereHas('Barang', function ($q) {
                $q->where('jenis', 'Utama')->whereHas('JenisBarang', fn($q2) => $q2->where('kode', 'BB'));
            })
            ->select(
                DB::raw('SUM(total_kupas) as kupas'),
                DB::raw('SUM(total_a) as a'),
                DB::raw('SUM(total_s) as s'),
                DB::raw('SUM(total_j) as j')
            )->first();

        // --- TREND TRANSAKSI ---
        $last7Days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));
        $trendKeluar = BarangKeluar::when($idPerusahaan, fn($q) => $q->where('id_perusahaan', $idPerusahaan))
            ->where('tanggal_keluar', '>=', now()->subDays(6))
            ->select(DB::raw('DATE(tanggal_keluar) as date'), DB::raw('SUM(jumlah_keluar) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = $last7Days->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->toArray();
        $chartData = $last7Days->map(fn($date) => $trendKeluar[$date] ?? 0)->toArray();

        $jenisStats = DB::table('barang')
            ->join('jenis_barang', 'barang.id_jenis', '=', 'jenis_barang.id')
            ->when($idPerusahaan, fn($q) => $q->where('barang.id_perusahaan', $idPerusahaan))
            ->whereIn('jenis_barang.kode', $kategoriProduksi)
            ->select('jenis_barang.kode', DB::raw('count(*) as total'))
            ->groupBy('jenis_barang.kode')
            ->pluck('total', 'kode');

        return view('pages.dashboard.manager', [
            'totalProducts'         => $totalProducts,
            'topProduk'             => $topProduk,
            'totalPengeluaran'      => $totalPengeluaran,
            'totalVolumeProduksi'   => $totalVolumeProduksi,
            'totalBahanBakuMasuk'   => $totalBahanBakuMasuk,
            'jenisBarangDiproduksi' => $jenisBarangDiproduksi,
            'persentaseRendemen'    => $persentaseRendemen,
            'persentaseLoss'        => $persentaseLoss,
            'totalPenyusutan'       => $totalPenyusutan,
            'hasilGradingRaw'       => $hasilGradingRaw,
            'chartLabels'           => $chartLabels,
            'chartData'             => $chartData,
            'countFG'               => $jenisStats['FG'] ?? 0,
            'countWIP'              => $jenisStats['WIP'] ?? 0,
            'countEC'               => $jenisStats['EC'] ?? 0,
        ]);
    }
}
