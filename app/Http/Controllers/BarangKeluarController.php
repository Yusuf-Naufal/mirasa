<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Proses;
use App\Models\Costumer;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\Perusahaan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // --- 1. VALIDASI AKSES AWAL ---
        // User harus punya minimal satu akses untuk melihat halaman ini
        $canProduksi = $user->can('barang-keluar.produksi');
        $canBahanBaku = $user->can('barang-keluar.bahan-baku');
        $canPenjualan = $user->can('barang-keluar.penjualan');

        if (!$canProduksi && !$canBahanBaku && !$canPenjualan) {
            abort(403, 'Anda tidak memiliki akses ke modul Barang Keluar.');
        }

        // --- 2. LOGIKA DEFAULT TAB BERDASARKAN PERMISSION ---
        // Jika tidak ada parameter tab di URL, tentukan berdasarkan permission pertama yang dimiliki
        $defaultTab = 'PRODUKSI';
        if (!$canProduksi) {
            if ($canBahanBaku) $defaultTab = 'BAHAN BAKU';
            elseif ($canPenjualan) $defaultTab = 'DISTRIBUSI';
        }

        $activeTab = $request->get('tab', $defaultTab);

        // --- 3. SECURITY CHECK: Mencegah user ganti Tab via URL manual tanpa permission ---
        if ($activeTab === 'PRODUKSI' && !$canProduksi) abort(403);
        if ($activeTab === 'BAHAN BAKU' && !$canBahanBaku) abort(403);
        if ($activeTab === 'DISTRIBUSI' && !$canPenjualan) abort(403);

        // --- 4. PARAMETER FILTER ---
        $search = $request->get('search');
        $idPerusahaanFilter = $request->get('id_perusahaan');
        $dateRange = $request->get('date_range');
        $idBarang = $request->get('id_barang');
        $perPage = 30;

        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        // --- 5. QUERY UTAMA ---
        $query = BarangKeluar::query()
            ->whereHas('DetailInventory')
            ->with(['DetailInventory.Inventory.Barang', 'Produksi', 'Costumer', 'Perusahaan', 'Proses']);

        // Filter Perusahaan (Keamanan Data)
        if ($user->hasRole('Super Admin')) {
            if ($idPerusahaanFilter) $query->where('id_perusahaan', $idPerusahaanFilter);
        } else {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }

        // --- 6. DROPDOWN BARANG & FILTER TAB ---
        $barangDropdownQuery = Barang::query();
        if (!$user->hasRole('Super Admin')) {
            $barangDropdownQuery->where('id_perusahaan', $user->id_perusahaan);
        } elseif ($idPerusahaanFilter) {
            $barangDropdownQuery->where('id_perusahaan', $idPerusahaanFilter);
        }

        if ($activeTab === 'PRODUKSI') {
            $query->where('jenis_keluar', 'PRODUKSI');
            $barangDropdownQuery->whereHas('jenisBarang', fn($q) => $q->where('kode', 'BP'));
        } else if ($activeTab === 'BAHAN BAKU') {
            $query->where('jenis_keluar', 'BAHAN BAKU');
            $barangDropdownQuery->whereHas('jenisBarang', fn($q) => $q->where('kode', 'BB'));
        } else {
            $query->whereIn('jenis_keluar', ['PENJUALAN', 'TRANSFER']);
            $barangDropdownQuery->whereHas('jenisBarang', fn($q) => $q->whereNotIn('kode', ['BP', 'BB']));
        }

        $listBarang = $barangDropdownQuery->get();

        // --- 7. FILTER TAMBAHAN (Search, Date, dll) ---
        if ($idBarang) {
            $query->whereHas('DetailInventory.Inventory', fn($q) => $q->where('id_barang', $idBarang));
        }

        if ($search) {
            $query->whereHas('DetailInventory.Inventory.Barang', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($dateRange) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) == 2) {
                $query->whereBetween('tanggal_keluar', [$dates[0], $dates[1]]);
            } else {
                $query->whereDate('tanggal_keluar', $dates[0]);
            }
        }

        // --- 8. PAGINATION & GROUPING ---
        $dataPaginated = $query->orderBy('tanggal_keluar', 'desc')->paginate($perPage)->withQueryString();

        $groupedData = $dataPaginated->getCollection()->groupBy(function ($item) use ($activeTab) {
            $tanggal = $item->tanggal_keluar ?? 'no-date';
            if ($activeTab === 'PRODUKSI' || $activeTab === 'BAHAN BAKU') {
                $barangId = $item->DetailInventory->Inventory->id_barang ?? '0';
                $prefix = ($activeTab === 'PRODUKSI') ? 'prod-' : 'bb-';
                return $prefix . $barangId . '_' . $tanggal;
            } else {
                $receiverId = ($item->jenis_keluar === 'PENJUALAN')
                    ? 'cust-' . ($item->id_costumer ?? '0')
                    : 'comp-' . ($item->id_perusahaan ?? '0');
                return $receiverId . '_' . $tanggal;
            }
        });

        $dataPaginated->setCollection($groupedData);

        return view('pages.barangkeluar.index', [
            'data' => $dataPaginated,
            'activeTab' => $activeTab,
            'perusahaan' => $perusahaan,
            'listBarang' => $listBarang,
        ]);
    }

    /**
     * Form Pengeluaran untuk Internal Produksi (Bahan Penolong - BP)
     */
    public function createProduksi()
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        $inventory = Inventory::with(['Barang', 'DetailInventory' => function ($q) {
            $q->where('stok', '>', 0)->orderBy('tanggal_masuk', 'asc');
        }])
            ->where('id_perusahaan', $id_perusahaan)
            ->whereHas('Barang.JenisBarang', fn($q) => $q->where('kode', 'BP'))
            ->where('stok', '>', 0) // Pastikan stok total master > 0
            ->get();

        $proses = Proses::where('id_perusahaan', $id_perusahaan)->get();
        return view('pages.barangkeluar.create-produksi', compact('inventory', 'proses'));
    }

    /**
     * Form Pengeluaran untuk Penjualan (FG, WIP, EC)
     */
    public function createPenjualan()
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        $perusahaan = Perusahaan::whereNull('deleted_at')
            ->where('id', '!=', $id_perusahaan)
            ->get();

        // Ambil barang jenis Finish Good (FG), WIP, dan EC
        $inventory = Inventory::with(['Barang.JenisBarang', 'DetailInventory' => function ($q) {
            $q->where('stok', '>', 0)->orderBy('tanggal_masuk', 'asc');
        }])
            ->where('id_perusahaan', $id_perusahaan)
            ->whereHas('Barang.JenisBarang', function ($q) {
                $q->whereIn('kode', ['FG', 'WIP', 'EC']);
            })
            ->whereHas('DetailInventory', function ($q) {
                $q->where('stok', '>', 0);
            })
            ->get();

        $costumer = Costumer::where('id_perusahaan', $id_perusahaan)->get();

        return view('pages.barangkeluar.create-penjualan', compact('inventory', 'costumer', 'perusahaan'));
    }

    /**
     * Form Pengeluaran untuk Internal BahanBaku (Bahan Baku - BB)
     */
    public function createBahanBaku()
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        $inventory = Inventory::with(['Barang', 'DetailInventory' => function ($q) {
            $q->where('stok', '>', 0)->orderBy('tanggal_masuk', 'asc');
        }])
            ->where('id_perusahaan', $id_perusahaan)
            ->whereHas('Barang.JenisBarang', fn($q) => $q->where('kode', 'BB'))
            ->where('stok', '>', 0)
            ->get();

        return view('pages.barangkeluar.create-bahan-baku', compact('inventory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_inventory'   => 'required|exists:inventory,id',
            'tanggal_keluar' => 'required|date',
            'jenis_keluar'   => 'required|in:PRODUKSI,PENJUALAN,TRANSFER,BAHAN BAKU',
            'jumlah_keluar'  => 'required|numeric|min:0.001',
            'id_costumer'    => 'required_if:jenis_keluar,PENJUALAN|nullable|exists:costumer,id',
            'id_tujuan'      => 'required_if:jenis_keluar,TRANSFER|nullable|exists:perusahaan,id',
            'id_proses'      => 'required_if:jenis_keluar,PRODUKSI|nullable|exists:proses,id',
            'keterangan'     => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $inventory = Inventory::findOrFail($request->id_inventory);
            $jumlahDibutuhkan = (float) $request->jumlah_keluar;
            $id_perusahaan_auth = auth()->user()->id_perusahaan;
            $jenis = $request->jenis_keluar;

            // 2. Ambil Batch Detail Inventory menggunakan metode FIFO
            $batches = DetailInventory::where('id_inventory', $inventory->id)
                ->where('stok', '>', 0)
                ->orderBy('tanggal_masuk', 'asc')
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->get();

            // 3. Cek ketersediaan stok total dari seluruh batch
            if ($batches->sum('stok') < $jumlahDibutuhkan) {
                throw new \Exception('Stok total tidak mencukupi. Tersedia: ' . $batches->sum('stok'));
            }

            // 4. Header Transaksi (Produksi)
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $id_perusahaan_auth,
                'tanggal_produksi' => $request->tanggal_keluar,
            ]);

            $sisaKebutuhan = $jumlahDibutuhkan;

            // 5. Looping Distribusi Pengambilan Batch (FIFO)
            foreach ($batches as $batch) {
                if ($sisaKebutuhan <= 0) break;

                $jumlahDiambil = min($batch->stok, $sisaKebutuhan);

                // Simpan ke tabel barang_keluar
                BarangKeluar::create([
                    'id_perusahaan'       => $id_perusahaan_auth,
                    'id_produksi'         => $produksi->id,
                    'id_costumer'         => $request->id_costumer,
                    'id_tujuan'           => $request->id_tujuan,
                    'id_proses'           => $request->id_proses,
                    'id_detail_inventory' => $batch->id,
                    'tanggal_keluar'      => $request->tanggal_keluar,
                    'jenis_keluar'        => $jenis,
                    'jumlah_keluar'       => $jumlahDiambil,
                    'harga'               => $batch->harga,
                    'total_harga'         => $jumlahDiambil * $batch->harga,
                    'no_faktur'           => $request->no_faktur,
                    'no_jalan'            => $request->no_jalan,
                    'keterangan'          => $request->keterangan,
                ]);

                $sisaKebutuhan -= $jumlahDiambil;
            }

            DB::commit();

            // Sesuaikan parameter redirect agar tab yang aktif sesuai
            return redirect()->route('barang-keluar.index', ['tab' => $jenis])
                ->with('success', "Transaksi $jenis berhasil dicatat menggunakan metode FIFO.");
        } catch (\Exception $e) {
            DB::rollBack();
            // Pastikan error dikirim kembali ke session 'error'
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_keluar' => 'required|numeric|min:0.001',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $keluar = BarangKeluar::findOrFail($id);
            if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $keluar->id_perusahaan) {
                abort(403, 'Anda tidak memiliki izin untuk mengedit data ini.');
            }

            $detail = $keluar->DetailInventory;
            $id_perusahaan = auth()->user()->id_perusahaan;

            // 1. Logika Sinkronisasi id_produksi jika Tanggal Berubah
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $id_perusahaan,
                'tanggal_produksi' => $request->tanggal_keluar,
            ]);

            // 2. Hitung selisih (Diff) stok
            $selisih = $request->jumlah_keluar - $keluar->jumlah_keluar;

            // 3. Cek apakah stok di batch mencukupi jika ada penambahan jumlah keluar
            if ($selisih > 0 && $detail->stok < $selisih) {
                return back()->with('error', 'Stok pada batch ini tidak mencukupi untuk penambahan jumlah tersebut.');
            }

            // 4. Update DetailInventory (Stok fisik)
            $detail->stok -= $selisih;
            $detail->save();

            // 5. Update Data BarangKeluar
            $keluar->update([
                'id_produksi'       => $produksi->id,
                'tanggal_keluar'    => $request->tanggal_keluar,
                'jumlah_keluar'     => $request->jumlah_keluar,
                'total_harga'       => $request->jumlah_keluar * $keluar->harga,
            ]);

            DB::commit();
            return back()->with('success', 'Data pengeluaran dan relasi produksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $keluar = BarangKeluar::findOrFail($id);

            $keluar->delete();

            DB::commit();
            return back()->with('success', 'Data berhasil dihapus dan stok telah dikembalikan ke gudang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function printGroup(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'template' => 'required|in:biasa,indofood'
        ]);

        $ppnPercent = $request->input('ppn', 11);

        // Siapkan data JSON untuk kolom keterangan
        $dataKeterangan = [
            'jenis_template'  => $request->template,
            'jenis_kendaraan' => $request->jenis_kendaraan ?? '-',
            'plat_kendaraan'  => $request->plat_kendaraan ?? '-',
            'nama_supir'      => $request->nama_supir ?? '-',
            'varietas'        => $request->varietas ?? '-',
            'ppnPercent'      => $request->ppn ?? '-',
        ];

        // Update data secara massal ke database
        \App\Models\BarangKeluar::whereIn('id', $request->ids)->update([
            'no_faktur'  => $request->no_faktur,
            'no_jalan'   => $request->no_jalan,
            'keterangan' => json_encode($dataKeterangan), // Simpan sebagai JSON
        ]);

        $items = \App\Models\BarangKeluar::with(['DetailInventory.Inventory.Barang', 'Costumer', 'Perusahaan'])
            ->whereIn('id', $request->ids)
            ->get();

        $firstItem = $items->first();
        $perusahaan = auth()->user()->perusahaan;

        // Kirim data keterangan sebagai objek agar mudah diakses di Blade
        $keterangan = (object) $dataKeterangan;

        $view = ($request->template === 'indofood') ? 'pages.print.sj-Indofood' : 'pages.print.sj-biasa';

        return view($view, compact('items', 'firstItem', 'perusahaan', 'keterangan', 'ppnPercent'));
    }
}
