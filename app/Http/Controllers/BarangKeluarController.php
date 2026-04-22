<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Costumer;
use App\Models\DetailInventory;
use App\Models\Inventory;
use App\Models\Perusahaan;
use App\Models\Produksi;
use App\Models\Proses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            elseif ($canPenjualan) $defaultTab = 'PENJUALAN';
        }

        $activeTab = $request->get('tab', $defaultTab);

        // --- 3. SECURITY CHECK: Mencegah user ganti Tab via URL manual tanpa permission ---
        if ($activeTab === 'PRODUKSI' && !$canProduksi) abort(403);
        if ($activeTab === 'BAHAN BAKU' && !$canBahanBaku) abort(403);
        if ($activeTab === 'PENJUALAN' && !$canPenjualan) abort(403);

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
            $query->where('jenis_keluar', 'PENJUALAN');
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
            'id_costumer'    => 'nullable|exists:costumer,id',
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
                $detail = new BarangKeluar([
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

                $detail->keterangan_transaksi = 'Barang Keluar';
                $detail->save();

                $produksi->syncTotals();
            }

            DB::commit();

            // Sesuaikan parameter redirect agar tab yang aktif sesuai
            return redirect()->route('barang-keluar.index', ['tab' => $jenis])
                ->with('success', "Transaksi $jenis berhasil dicatat menggunakan metode FIFO.");
        } catch (\Exception $e) {
            DB::rollBack();

            // dd([
            //     'Pesan Error' => $e->getMessage(),
            //     'File' => $e->getFile(),
            //     'Baris' => $e->getLine()
            // ]);

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

        DB::beginTransaction();
        try {
            $user = auth()->user();

            // Gunakan lockForUpdate agar data tidak berubah saat diproses
            $keluar = BarangKeluar::lockForUpdate()->findOrFail($id);

            if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $keluar->id_perusahaan) {
                abort(403, 'Anda tidak memiliki izin untuk mengedit data ini.');
            }

            // 1. PROTEKSI: Cek batas minimum karena Daur Ulang (Afkir Ulang)
            if ($request->jumlah_keluar < $keluar->jumlah_dikonversi) {
                return back()->with('error', "Gagal! Barang ini sudah didaur ulang sebanyak {$keluar->jumlah_dikonversi}. Jumlah tidak boleh lebih kecil dari itu.");
            }

            // 2. Ambil Detail Inventory dengan Lock untuk cek stok
            $detail = DetailInventory::lockForUpdate()->findOrFail($keluar->id_detail_inventory);

            // 3. Hitung selisih (Diff)
            $selisih = $request->jumlah_keluar - $keluar->jumlah_keluar;

            // 4. Cek apakah stok di batch mencukupi (hanya jika ada penambahan jumlah)
            if ($selisih > 0 && $detail->stok < $selisih) {
                return back()->with('error', "Stok tidak mencukupi. Sisa stok di batch ini hanya {$detail->stok}, Anda mencoba menambah pengeluaran sebesar {$selisih}.");
            }

            // 5. Logika Sinkronisasi id_produksi (Grup per tanggal)
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $keluar->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_keluar,
            ]);

            // 6. Update Data
            $keluar->update([
                'id_produksi'    => $produksi->id,
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah_keluar'  => $request->jumlah_keluar,
                'keterangan'     => $request->keterangan,
                'total_harga'    => $request->jumlah_keluar * $keluar->harga,
            ]);

            DB::commit();
            return back()->with('success', 'Data pengeluaran berhasil diperbarui.');
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

            // PROTEKSI: Cek apakah sudah ada yang didaur ulang
            if ($keluar->jumlah_dikonversi > 0) {
                return back()->with('error', "Akses Ditolak! Barang ini sudah didaur ulang sebanyak {$keluar->jumlah_dikonversi}.");
            }

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
        BarangKeluar::whereIn('id', $request->ids)->update([
            'no_faktur'  => $request->no_faktur,
            'no_jalan'   => $request->no_jalan,
            'keterangan' => json_encode($dataKeterangan), // Simpan sebagai JSON
        ]);

        $items = BarangKeluar::with(['DetailInventory.Inventory.Barang', 'Costumer', 'Perusahaan'])
            ->whereIn('id', $request->ids)
            ->get();

        $firstItem = $items->first();
        $perusahaan = auth()->user()->perusahaan;

        // Kirim data keterangan sebagai objek agar mudah diakses di Blade
        $keterangan = (object) $dataKeterangan;

        $view = ($request->template === 'indofood') ? 'pages.print.sj-Indofood' : 'pages.print.sj-biasa';

        return view($view, compact('items', 'firstItem', 'perusahaan', 'keterangan', 'ppnPercent'));
    }

    public function showAfkirUlang($id)
    {
        $afkirAsal = BarangKeluar::with(['DetailInventory.Inventory.Barang'])->findOrFail($id);

        // Hitung Sisa untuk dilempar ke View
        $jumlahSudahDikonversi = $afkirAsal->jumlah_dikonversi ?? 0;
        $sisaBisaDikonversi = $afkirAsal->jumlah_keluar - $jumlahSudahDikonversi;

        // Cegah admin masuk ke halaman form jika stok afkir sudah habis di-recycle
        if ($sisaBisaDikonversi <= 0) {
            return back()->with('error', 'Barang afkir ini sudah habis dikonversi secara keseluruhan.');
        }

        $barangTujuan = Barang::whereHas('JenisBarang', function ($q) {
            $q->whereIn('kode', ['FG', 'WIP', 'EC']);
        })
            ->where('id_perusahaan', Auth::user()->id_perusahaan)
            ->where('id', '!=', $afkirAsal->DetailInventory->Inventory->id_barang)
            ->get();

        // Lempar variabel $sisaBisaDikonversi ke view
        return view('pages.barangkeluar.afkir-ulang', compact('afkirAsal', 'barangTujuan', 'sisaBisaDikonversi'));
    }

    public function eksekusiAfkirUlang(Request $request, $id)
    {
        $request->validate([
            'jumlah_afkir_dikonversi' => 'required|numeric|min:0.1',
            'id_barang_tujuan'        => 'required|exists:barang,id',
            'jumlah_hasil_konversi'   => 'required|numeric|min:0.1',
            'harga'                   => 'required|numeric|min:0',
            'tanggal_masuk'           => 'required|date',
            'tanggal_exp'             => 'nullable|date',
            'nomor_batch'             => 'nullable|string|max:255',
            'tempat_penyimpanan'      => 'nullable|string|max:255', // Tambahan validasi
        ]);

        DB::beginTransaction();
        try {
            // 1. MENCEGAH RACE CONDITION
            // lockForUpdate() memaksa request lain untuk antre jika mengakses ID yang sama
            $afkirAsal = BarangKeluar::with('DetailInventory.Inventory.Barang')
                ->lockForUpdate()
                ->findOrFail($id);

            // 2. MENGHITUNG SISA AFKIR YANG BISA DIDAUR ULANG
            $jumlahSudahDikonversi = $afkirAsal->jumlah_dikonversi ?? 0;
            $sisaBisaDikonversi = $afkirAsal->jumlah_keluar - $jumlahSudahDikonversi;

            // 3. VALIDASI SISA STOK (Lapis Keamanan Backend)
            if ($sisaBisaDikonversi <= 0) {
                return back()->with('error', 'Semua barang dari catatan afkir ini sudah habis didaur ulang.');
            }

            if ($request->jumlah_afkir_dikonversi > $sisaBisaDikonversi) {
                return back()->with('error', "Jumlah ditolak! Anda mencoba mendaur ulang {$request->jumlah_afkir_dikonversi}, padahal sisa yang belum didaur ulang hanya {$sisaBisaDikonversi}.");
            }

            // 4. MEMBUAT BARANG MASUK (HASIL KONVERSI)
            $inventoryTujuan = Inventory::firstOrCreate([
                'id_perusahaan' => $afkirAsal->id_perusahaan,
                'id_barang'     => $request->id_barang_tujuan,
            ]);

            // Beri penanda unik jika batch tidak diisi
            $batchBaru = $request->nomor_batch ?? ('RCY-' . ($afkirAsal->DetailInventory->Inventory->Barang->kode ?? 'UNK') . '-' . now()->format('ymdHi'));

            $hasilKonversi = new DetailInventory([
                'id_inventory'       => $inventoryTujuan->id,
                'id_produksi'        => $afkirAsal->DetailInventory->id_produksi,
                'nomor_batch'        => $batchBaru,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'tanggal_exp'        => $request->tanggal_exp ?? $afkirAsal->DetailInventory->tanggal_exp,
                'jumlah_diterima'    => $request->jumlah_hasil_konversi,
                'stok'               => $request->jumlah_hasil_konversi,
                'harga'              => $request->harga,
                'total_harga'        => $request->jumlah_hasil_konversi * $request->harga,
                'tempat_penyimpanan' => $request->tempat_penyimpanan ?? $afkirAsal->DetailInventory->tempat_penyimpanan,
                'status'             => 'Konversi',
            ]);

            // Keterangan sangat detail untuk kebutuhan Audit Keuangan
            $hasilKonversi->keterangan_transaksi = "Afkir Ulang";
            $hasilKonversi->save();

            // 5. UPDATE PENCATATAN DI SUMBER ASAL (Update Tracker)
            $afkirAsal->jumlah_dikonversi = $jumlahSudahDikonversi + $request->jumlah_afkir_dikonversi;

            // (Opsional) Jika Anda menggunakan kolom status di BarangKeluar
            // if ($afkirAsal->jumlah_dikonversi >= $afkirAsal->jumlah_keluar) {
            //     $afkirAsal->status = 'DIDAUR ULANG FULL';
            // }

            $afkirAsal->save();

            DB::commit();

            // Pesan Sukses yang informatif
            $sisaSekarang = $sisaBisaDikonversi - $request->jumlah_afkir_dikonversi;
            $pesan = $sisaSekarang > 0
                ? "Berhasil didaur ulang sebagian. Sisa yang masih bisa didaur ulang: {$sisaSekarang}."
                : "Sempurna! Seluruh kuantitas pada catatan afkir ini telah habis didaur ulang.";

            return redirect()->route('barang-keluar.index',['tab' => 'PENJUALAN',])->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
