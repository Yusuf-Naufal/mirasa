<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeTab = $request->get('tab', 'produksi');

        // Ambil data perusahaan untuk filter Super Admin
        $perusahaan = $user->hasRole('Super Admin') ? Perusahaan::whereNull('deleted_at')->get() : [];

        $listBarang = Barang::query()
            ->when(!$user->hasRole('Super Admin'), fn($q) => $q->where('id_perusahaan', $user->id_perusahaan))
            ->whereHas('jenisBarang', function ($q) use ($activeTab) {
                if ($activeTab === 'produksi') {
                    $q->whereIn('kode', ['FG', 'WIP', 'EC']);
                } else {
                    $q->where('kode', 'BP');
                }
            })
            ->orderBy('nama_barang', 'asc')
            ->get();

        // Query DetailInventory (Barang Masuk)
        $query = DetailInventory::with(['Inventory.Barang.jenisBarang', 'Inventory.Perusahaan', 'supplier']);

        // Filter Tab (Data yang ditampilkan di tabel)
        if ($activeTab === 'produksi') {
            $query->whereHas('Inventory.Barang.jenisBarang', fn($q) => $q->whereIn('kode', ['FG', 'WIP', 'EC']));
        } else {
            $query->whereHas('Inventory.Barang.jenisBarang', fn($q) => $q->where('kode', 'BP'));
        }

        // Filter Perusahaan
        if (!$user->hasRole('Super Admin')) {
            $query->whereHas('Inventory', fn($q) => $q->where('id_perusahaan', $user->id_perusahaan));
        } elseif ($request->filled('id_perusahaan')) {
            $query->whereHas('Inventory', fn($q) => $q->where('id_perusahaan', $request->id_perusahaan));
        }

        // Filter Barang Spesifik (DARI MODAL FILTER)
        if ($request->filled('id_barang')) {
            $query->whereHas('Inventory', fn($q) => $q->where('id_barang', $request->id_barang));
        }

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('Inventory.Barang', fn($sq) => $sq->where('nama_barang', 'like', "%{$search}%"))
                    ->orWhereHas('supplier', fn($sq) => $sq->where('nama_supplier', 'like', "%{$search}%"));
            });
        }

        // Filter Tanggal
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('tanggal_masuk', [$dates[0], $dates[1]]);
            } else {
                $query->whereDate('tanggal_masuk', $dates[0]);
            }
        }

        $barangMasukPagination = $query->latest('tanggal_masuk')->paginate(30)->withQueryString();

        $data = $barangMasukPagination->getCollection()->groupBy([
            function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d');
            },
            'inventory.id_barang'
        ]);

        return view('pages.barangmasuk.index', compact('data', 'barangMasukPagination', 'listBarang', 'perusahaan', 'activeTab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function createBp()
    {
        $user = auth()->user();

        $supplier = Supplier::where('jenis_supplier', 'Barang')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BP']);
            })
            ->get();

        return view('pages.barangmasuk.create-bp', compact('barang', 'supplier'));
    }

    public function createProduksi()
    {
        $user = auth()->user();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['FG', 'WIP', 'EC']);
            })
            ->get();

        return view('pages.barangmasuk.create-produksi', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storeProduksi(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_barang'          => 'required|exists:barang,id',
            'tanggal_masuk'      => 'required|date',
            'tanggal_exp'        => 'nullable|date',
            'jumlah_diterima'    => 'required|numeric|min:0.01',
            'harga'              => 'required|numeric|min:0',
            'tempat_penyimpanan' => 'nullable|string|max:255',
            'nomor_batch'        => 'nullable|string|max:255',
            'total_harga'        => 'required|numeric',
        ], [
            'id_barang.required'      => 'Silahkan pilih barang terlebih dahulu.',
            'jumlah_diterima.required' => 'Jumlah barang tidak boleh kosong.',
            'harga.required'          => 'Harga satuan harus diisi.',
        ]);

        try {
            // Mulai Transaksi Database
            DB::beginTransaction();

            // 2. Update atau Buat data di tabel Inventory (Master Stok)
            // Kita gunakan updateOrCreate agar jika barang & perusahaan sudah ada, stoknya ditambah
            $inventory = Inventory::where('id_perusahaan', $request->id_perusahaan)
                ->where('id_barang', $request->id_barang)
                ->first();

            if ($inventory) {
                // Jika sudah ada, tambahkan stoknya
                $inventory->stok += $request->jumlah_diterima;
                $inventory->save();
            } else {
                // Jika belum ada, buat record baru
                $inventory = Inventory::create([
                    'id_perusahaan' => $request->id_perusahaan,
                    'id_barang'     => $request->id_barang,
                    'stok'          => $request->jumlah_diterima,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'minimum_stok'  => 0,
                ]);
            }

            // 3. Simpan Riwayat ke tabel DetailInventory
            DB::table('detail_inventory')->insert([
                'id_inventory'       => $inventory->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'tanggal_exp'        => $request->tanggal_exp,
                'stok'               => $request->jumlah_diterima,
                'jumlah_diterima'    => $request->jumlah_diterima,
                'harga'              => $request->harga,
                'total_harga'        => $request->total_harga,
                'tempat_penyimpanan' => $request->tempat_penyimpanan,
                'nomor_batch'        => $request->nomor_batch,
                'status'             => 'Tersedia',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // Commit jika semua berhasil
            DB::commit();

            return redirect()->route('barang-masuk.index', ['tab' => 'produksi'])
                ->with('success', 'Data produksi berhasil disimpan dan stok telah diperbarui.');
        } catch (\Exception $e) {
            // Batalkan jika ada error
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function storeBahan(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_barang'          => 'required|exists:barang,id',
            'id_supplier'        => 'required|exists:supplier,id',
            'tanggal_masuk'      => 'required|date',
            'tempat_penyimpanan' => 'nullable|string|max:255',
            'jumlah_diterima'    => 'required|numeric|min:0',
            'jumlah_rusak'       => 'nullable|numeric|min:0',
            'stok'               => 'nullable|numeric|min:0',
            'harga'              => 'nullable|numeric|min:0',
            'total_harga'        => 'nullable|numeric|min:0',
        ], [
            'id_barang.required' => 'Silahkan pilih barang terlebih dahulu.',
            'jumlah_diterima.required' => 'Jumlah barang masuk harus diisi.',
        ]);

        try {
            DB::beginTransaction();

            $jumlahMasuk = (float) $request->jumlah_diterima;
            $jumlahRusak = (float) ($request->jumlah_rusak ?? 0);
            $stokBersih = $request->stok > 0 ? (float) $request->stok : ($jumlahMasuk - $jumlahRusak);

            // 1. Cari atau buat Produksi
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $request->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_masuk,
            ]);

            // 2. Cari atau buat Inventory (Master)
            $inventory = Inventory::firstOrCreate(
                ['id_perusahaan' => $request->id_perusahaan, 'id_barang' => $request->id_barang]
            );

            // 3. Simpan Riwayat Detail
            DetailInventory::create([
                'id_inventory'       => $inventory->id,
                'id_supplier'        => $request->id_supplier,
                'id_produksi'        => $produksi->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'jumlah_diterima'    => $jumlahMasuk,
                'jumlah_rusak'       => $jumlahRusak,
                'stok'               => $stokBersih,
                'harga'              => $request->harga ?? 0,
                'total_harga'        => $request->total_harga ?? ($jumlahMasuk * ($request->harga ?? 0)),
                'tempat_penyimpanan' => $request->tempat_penyimpanan,
                'status'             => 'Tersedia',
            ]);

            // 4. Refresh Produksi
            $produksi->syncTotals();

            // 5. Sinkronisasi Stok Master
            if ($inventory) {
                $inventory->syncTotalStock();
            }

            DB::commit();

            // Mengambil nama barang untuk pesan sukses
            $namaBarang = $inventory->barang->nama_barang ?? 'Barang';

            return redirect()->route('barang-masuk.index', ['tab' => 'penolong'])
                ->with('success', "Data {$namaBarang} berhasil masuk gudang dan stok diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data barang: ' . $e->getMessage());
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

    public function editProduksi($id)
    {
        $user = auth()->user();

        $item = DetailInventory::findOrFail($id);

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['FG', 'WIP', 'EC']);
            })
            ->get();

        $supplier = Supplier::where('jenis_supplier', 'Bahan Baku')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        return view('pages.barangmasuk.edit-produksi', compact('item', 'barang', 'supplier'));
    }

    public function editBp($id)
    {
        $user = auth()->user();

        $item = DetailInventory::findOrFail($id);

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BP']);
            })
            ->get();

        $supplier = Supplier::where('jenis_supplier', 'Barang')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        return view('pages.barangmasuk.edit-bp', compact('item', 'barang', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_barang'          => 'required|exists:barang,id',
            'id_supplier'        => 'nullable|exists:supplier,id',
            'tanggal_masuk'      => 'required|date',
            'tanggal_exp'        => 'nullable|date',
            'jumlah_diterima'    => 'required|numeric|min:0',
            'jumlah_rusak'       => 'nullable|numeric|min:0',
            'stok'               => 'nullable|numeric|min:0',
            'harga'              => 'required|numeric|min:0',
            'total_harga'        => 'nullable|numeric|min:0',
            'nomor_batch'        => 'nullable|string',
            'tempat_penyimpanan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $detail = DetailInventory::findOrFail($id);

            // 1. Simpan State Lama
            $idProduksiLama = $detail->id_produksi;
            $inventoryLama = $detail->Inventory;

            // 2. Logika Perhitungan (Tetap sama)
            $diterima = (float) $request->jumlah_diterima;
            $rusak    = (float) ($request->jumlah_rusak ?? 0);
            $stokBersih = $request->filled('stok') ? (float)$request->stok : ($diterima - $rusak);
            $harga      = (float) $request->harga;
            $totalHarga = $request->filled('total_harga') ? (float)$request->total_harga : ($stokBersih * $harga);

            // 3. Cari/Buat Grup Produksi (Tetap sama)
            $produksiBaru = Produksi::firstOrCreate([
                'id_perusahaan'    => auth()->user()->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_masuk,
            ]);

            // 4. LOGIKA UPDATE / PINDAH INVENTORY
            $inventoryTujuan = Inventory::firstOrCreate(
                [
                    'id_perusahaan' => auth()->user()->id_perusahaan,
                    'id_barang'     => $request->id_barang,
                ],
            );

            // 5. UPDATE DATA DETAIL INVENTORY
            $detail->update([
                'id_inventory'       => $inventoryTujuan->id,
                'id_supplier'        => $request->id_supplier,
                'id_produksi'        => $produksiBaru->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'tanggal_exp'        => $request->tanggal_exp,
                'jumlah_diterima'    => $diterima,
                'jumlah_rusak'       => $rusak,
                'nomor_batch'        => $request->nomor_batch,
                'stok'               => $stokBersih,
                'harga'              => $harga,
                'total_harga'        => $totalHarga,
                'tempat_penyimpanan' => $request->tempat_penyimpanan,
            ]);

            // 6. SINKRONISASI TOTAL PRODUKSI (Tetap sama)
            $produksiBaru->syncTotals();
            if ($idProduksiLama && $idProduksiLama != $produksiBaru->id) {
                $oldProd = Produksi::find($idProduksiLama);
                if ($oldProd) $oldProd->syncTotals();
            }

            // 7. SINKRONISASI STOK MASTER BARANG
            // Sinkronkan stok barang baru
            $inventoryTujuan->syncTotalStock();

            // Sinkronkan stok barang lama (karena datanya sudah pindah, stok lama harus berkurang)
            if ($inventoryLama && $inventoryLama->id != $inventoryTujuan->id) {
                $inventoryLama->syncTotalStock();
            }

            // Penentuan Tab
            $kodeJenis = $inventoryTujuan->Barang->JenisBarang->kode;
            $tab = in_array($kodeJenis, ['FG', 'WIP', 'EC']) ? 'produksi' : 'penolong';

            DB::commit();
            return redirect()->route('barang-masuk.index', ['tab' => $tab])
                ->with('success', 'Data distribusi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanBaku = DetailInventory::findOrFail($id);

        $kodeJenis = $bahanBaku->Inventory->Barang->JenisBarang->kode;
        $tab = in_array($kodeJenis, ['FG', 'WIP', 'EC']) ? 'produksi' : 'penolong';

        $bahanBaku->delete();

        return redirect()->route('barang-masuk.index', ['tab' => $tab])->with('success', 'Data berhasil dihapus.');
    }
}
