<?php

namespace App\Http\Controllers;

use App\Models\Proses;
use App\Models\Costumer;
use App\Models\Produksi;
use App\Models\Inventory;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Ambil parameter filter
        $activeTab = $request->get('tab', 'produksi');
        $search = $request->get('search');
        $perPage = 15; // Anda bisa menambah jumlah per halaman karena data akan dikelompokkan

        // 2. Bangun Query Utama
        $query = BarangKeluar::where('id_perusahaan', auth()->user()->id_perusahaan)
            ->with(['DetailInventory.Inventory.Barang', 'Produksi', 'Costumer', 'Proses'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('DetailInventory.Inventory.Barang', function ($sq) use ($search) {
                    $sq->where('nama_barang', 'like', "%$search%");
                });
            });

        // 3. Filter berdasarkan Tab
        if ($activeTab === 'produksi') {
            $query->where('jenis_keluar', 'PRODUKSI');
        } else {
            $query->whereIn('jenis_keluar', ['PENJUALAN', 'TRANSFER']);
        }

        // 4. Ambil data dengan Pagination (Urutkan berdasarkan tanggal terbaru dan ID barang)
        $dataPaginated = $query->orderBy('tanggal_keluar', 'desc')
            ->orderBy('id_produksi', 'desc') // Tambahan untuk konsistensi grup produksi
            ->paginate($perPage)
            ->withQueryString();

        // 5. Transformasi Data: Kelompokkan data hasil paginasi
        // Kita mengelompokkan hasil per halaman agar tampilan rapi, namun pagination tetap berfungsi normal
        $groupedData = $dataPaginated->getCollection()->groupBy(function ($item) {
            // Kelompokkan berdasarkan tanggal dan ID Inventory (Master Barang)
            return $item->tanggal_keluar . '-' . $item->DetailInventory->id_inventory;
        });

        // 6. Masukkan kembali data yang sudah dikelompokkan ke objek paginator
        $dataPaginated->setCollection($groupedData);

        return view('pages.barangkeluar.index', [
            'data' => $dataPaginated,
            'activeTab' => $activeTab
        ]);
    }

    /**
     * Form Pengeluaran untuk Internal Produksi (Bahan Penolong - BP)
     */
    public function createProduksi()
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        // Ambil Master Inventory (Barang) yang memiliki stok > 0 dan jenis BP
        $inventory = Inventory::with(['Barang'])
            ->where('id_perusahaan', $id_perusahaan)
            ->whereHas('Barang.JenisBarang', fn($q) => $q->where('kode', 'BP'))
            ->where('stok', '>', 0)
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input - Memperbaiki 'null' menjadi 'required'
        $request->validate([
            'id_inventory' => 'required|exists:inventory,id',
            'tanggal_keluar' => 'required|date',
            // 'jenis_keluar' harus 'required' karena menentukan logika selanjutnya
            'jenis_keluar' => 'required|in:PRODUKSI,PENJUALAN,TRANSFER',
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
                    'keterangan'          => $request->keterangan,
                ]);

                $sisaKebutuhan -= $jumlahDiambil;
            }

            DB::commit();
            return redirect()->route('barang-keluar.index')
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
}
