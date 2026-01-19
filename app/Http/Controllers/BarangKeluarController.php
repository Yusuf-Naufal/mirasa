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
        // 1. Ambil parameter filter
        $activeTab = $request->get('tab', 'PRODUKSI');
        $search = $request->get('search');
        $idPerusahaanFilter = $request->get('id_perusahaan');
        $dateRange = $request->get('date_range');
        $idBarang = $request->get('id_barang');
        $perPage = 30;

        $user = auth()->user();
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        // 2. Bangun Query Utama BarangKeluar
        $query = BarangKeluar::query()
            ->whereHas('DetailInventory')
            ->with(['DetailInventory.Inventory.Barang', 'Produksi', 'Costumer', 'Perusahaan', 'Proses']);

        // 3. Filter Keamanan & Perusahaan
        if ($user->hasRole('Super Admin')) {
            if ($idPerusahaanFilter) {
                $query->where('id_perusahaan', $idPerusahaanFilter);
            }
        } else {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }

        // 4. Bangun Query Dropdown Barang (Dinamis sesuai Tab)
        $barangDropdownQuery = Barang::query();
        if ($user->hasRole('Super Admin')) {
            if ($idPerusahaanFilter) {
                $barangDropdownQuery->where('id_perusahaan', $idPerusahaanFilter);
            }
        } else {
            $barangDropdownQuery->where('id_perusahaan', $user->id_perusahaan);
        }

        // 5. Logika Filter berdasarkan Tab
        if ($activeTab === 'PRODUKSI') {
            $query->where('jenis_keluar', 'PRODUKSI');
            $barangDropdownQuery->whereHas('jenisBarang', fn($q) => $q->where('kode', 'BP'));
        } else if ($activeTab === 'BAHAN BAKU') {
            $query->where('jenis_keluar', 'BAHAN BAKU');
            $barangDropdownQuery->whereHas('jenisBarang', fn($q) => $q->where('kode', 'BB'));
        } else {
            // Tab Distribusi (Penjualan/Transfer)
            $query->whereIn('jenis_keluar', ['PENJUALAN', 'TRANSFER']);
            $barangDropdownQuery->whereHas('jenisBarang', function ($q) {
                $q->whereNotIn('kode', ['BP', 'BB']);
            });
        }

        $listBarang = $barangDropdownQuery->get();

        // 6. Terapkan Filter Tambahan
        if ($idBarang) {
            $query->whereHas('DetailInventory.Inventory', function ($q) use ($idBarang) {
                $q->where('id_barang', $idBarang);
            });
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

        // 7. Pagination & Grouping (Disesuaikan berdasarkan Tipe Tab)
        $dataPaginated = $query->orderBy('tanggal_keluar', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $groupedData = $dataPaginated->getCollection()->groupBy(function ($item) use ($activeTab) {
            $tanggal = $item->tanggal_keluar ?? 'no-date';

            if ($activeTab === 'PRODUKSI') {
                /**
                 * Grouping PRODUKSI: Berdasarkan ID Barang + Tanggal
                 * Karena produksi biasanya dipantau per jenis barang yang dihasilkan di hari tersebut
                 */
                $barangId = $item->DetailInventory->Inventory->id_barang ?? '0';
                return 'prod-' . $barangId . '_' . $tanggal;
            } else if ($activeTab === 'BAHAN BAKU') {
                /**
                 * Grouping BAHAN BAKU: Berdasarkan ID Barang + Tanggal
                 * Agar terlihat total pemakaian bahan baku tertentu dalam satu hari
                 */
                $barangId = $item->DetailInventory->Inventory->id_barang ?? '0';
                return 'bb-' . $barangId . '_' . $tanggal;
            } else {
                /**
                 * Grouping DISTRIBUSI (PENJUALAN & TRANSFER): Berdasarkan Penerima + Tanggal
                 * Penjualan grouping ke Customer, Transfer grouping ke Perusahaan/Gudang Cabang
                 */
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
        // 1. Validasi Input - Memperbaiki 'null' menjadi 'required'
        $request->validate([
            'id_inventory' => 'required|exists:inventory,id',
            'tanggal_keluar' => 'required|date',
            'jenis_keluar' => 'required|in:PRODUKSI,PENJUALAN,TRANSFER,BAHAN BAKU',
            'jumlah_keluar' => 'required|numeric|min:0.001',
            'keterangan' => 'nullable|string',

        ]);

        try {
            DB::beginTransaction();

            $inventory = Inventory::findOrFail($request->id_inventory);
            $jumlahDibutuhkan = (float) $request->jumlah_keluar;
            $id_perusahaan_auth = auth()->user()->id_perusahaan;
            $jenis = $request->jenis_keluar;

            // 2. Buat "Header" Transaksi (Model Produksi)
            // Header ini digunakan untuk mengelompokkan pengeluaran di hari yang sama
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $id_perusahaan_auth,
                'tanggal_produksi' => $request->tanggal_keluar,
            ]);

            // 3. Ambil Batch Detail Inventory menggunakan metode FIFO
            $batches = DetailInventory::where('id_inventory', $inventory->id)
                ->where('stok', '>', 0)
                ->orderBy('tanggal_masuk', 'asc') 
                ->orderBy('created_at', 'asc')   
                ->get();

            // 4. Cek ketersediaan stok total dari seluruh batch
            if ($batches->sum('stok') < $jumlahDibutuhkan) {
                return back()->withInput()->with('error', 'Stok total batch tidak mencukupi untuk permintaan ini.');
            }

            $sisaKebutuhan = $jumlahDibutuhkan;

            // 5. Looping Distribusi Pengambilan Batch (FIFO)
            foreach ($batches as $batch) {
                if ($sisaKebutuhan <= 0) break;

                $jumlahDiambil = min($batch->stok, $sisaKebutuhan);

                // Simpan ke tabel barang_keluar
                // Note: Model BarangKeluar::booted() akan memotong stok di DetailInventory secara otomatis
                BarangKeluar::create([
                    'id_perusahaan'       => $id_perusahaan_auth,
                    'id_produksi'         => $produksi->id,
                    'id_costumer'         => $request->id_costumer ?? null,
                    'id_tujuan'           => $request->id_tujuan ?? null,
                    'id_proses'           => $request->id_proses ?? null,
                    'id_detail_inventory' => $batch->id,
                    'tanggal_keluar'      => $request->tanggal_keluar,
                    'jenis_keluar'        => $jenis,
                    'jumlah_keluar'       => $jumlahDiambil,
                    'harga'               => $batch->harga,
                    'total_harga'         => $jumlahDiambil * $batch->harga,
                    'no_faktur'           => $request->no_faktur ?? null,
                    'no_jalan'           => $request->no_jalan ?? null,
                ]);

                $sisaKebutuhan -= $jumlahDiambil;
            }

            DB::commit();
            return redirect()->route('barang-keluar.index', ['tab' => $jenis])
                ->with('success', "Transaksi $jenis berhasil dicatat menggunakan metode FIFO.");
        } catch (\Exception $e) {
            DB::rollBack();
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

            $keluar = BarangKeluar::findOrFail($id);
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
