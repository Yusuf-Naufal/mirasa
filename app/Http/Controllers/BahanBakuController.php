<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Query dasar
        $query = DetailInventory::with(['Inventory.Barang.jenisBarang', 'Inventory.Perusahaan'])
            ->whereHas('Inventory.Barang.jenisBarang', function ($q) {
                $q->where('kode', 'BB');
            });

        // 2. Proteksi Data: Filter id_perusahaan yang ada di tabel Inventory
        if (!$user->hasRole('Super Admin')) {
            // Gunakan whereHas karena id_perusahaan milik tabel Inventory
            $query->whereHas('Inventory', function ($q) use ($user) {
                $q->where('id_perusahaan', $user->id_perusahaan);
            });
        } elseif ($request->filled('id_perusahaan')) {
            $query->whereHas('Inventory', function ($q) use ($request) {
                $q->where('id_perusahaan', $request->id_perusahaan);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('Barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            });
        }

        // Ambil data dan kelompokkan berdasarkan tanggal_masuk
        $listBahanBaku = $query->latest('tanggal_masuk')
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d');
            });

        return view('pages.bahanbaku.index', compact('listBahanBaku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        $supplier = Supplier::where('jenis_supplier', 'Bahan Baku')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BB']);
            })
            ->get();

        return view('pages.bahanbaku.create', compact('barang', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan'     => 'required|exists:perusahaan,id',
            'id_supplier'       => 'required|exists:supplier,id',
            'id_barang'         => 'required|exists:barang,id',
            'tanggal_masuk'     => 'required|date',
            'jumlah_diterima'   => 'required|numeric|min:0.01',
            'harga'             => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Cari atau Buat Sesi Produksi otomatis berdasarkan tanggal masuk
            $produksi = Produksi::firstOrCreate(
                [
                    'id_perusahaan'    => $request->id_perusahaan,
                    'tanggal_produksi' => $request->tanggal_masuk,
                ]
            );

            // 2. Update atau Buat data Master Stok di tabel Inventory
            $inventory = Inventory::where('id_perusahaan', $request->id_perusahaan)
                ->where('id_barang', $request->id_barang)
                ->first();

            if ($inventory) {
                $inventory->stok += $request->jumlah_diterima;
                $inventory->save();
            } else {
                $inventory = Inventory::create([
                    'id_perusahaan' => $request->id_perusahaan,
                    'id_barang'     => $request->id_barang,
                    'stok'          => $request->jumlah_diterima,
                    'minimum_stok'  => 0,
                ]);
            }

            // 3. Simpan Riwayat ke Detail Inventory
            DetailInventory::create([
                'id_inventory'       => $inventory->id,
                'id_supplier'        => $request->id_supplier,
                'id_produksi'        => $produksi->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'jumlah_diterima'    => $request->jumlah_diterima,
                'stok'               => $request->jumlah_diterima,
                'harga'              => $request->harga,
                'total_harga'        => $request->jumlah_diterima * $request->harga,
                'status'             => 'Tersedia',
            ]);

            DB::commit();

            return redirect()->route('bahan-baku.index')
                ->with('success', 'Bahan baku berhasil masuk gudang, stok inventory diperbarui, dan sesi produksi telah disiapkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
    public function edit($id)
    {
        $user = auth()->user();

        $bahanBaku = DetailInventory::findOrFail($id);

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BB']);
            })
            ->get();

        $supplier = Supplier::where('jenis_supplier', 'Bahan Baku')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        return view('pages.bahanbaku.edit', compact('bahanBaku', 'barang', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_supplier'      => 'required|exists:supplier,id',
            'id_barang'        => 'required|exists:barang,id',
            'tanggal_masuk'    => 'required|date',
            'jumlah_diterima'  => 'required|numeric|min:0.01',
            'harga'            => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $bahanBaku = DetailInventory::findOrFail($id);

            // SIMPAN STATE LAMA
            $idProduksiLama = $bahanBaku->id_produksi;
            $idInventoryLama = $bahanBaku->id_inventory;

            // 1. Cari atau Buat Produksi BARU berdasarkan tanggal input
            $produksiBaru = Produksi::firstOrCreate([
                'id_perusahaan'    => auth()->user()->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_masuk,
            ]);

            // 2. Cari atau Buat Inventory Master BARU (jika barang diganti)
            $inventoryBaru = Inventory::firstOrCreate([
                'id_perusahaan' => auth()->user()->id_perusahaan,
                'id_barang'     => $request->id_barang,
            ], ['stok' => 0, 'minimum_stok' => 0]);

            // 3. Update Data DetailInventory
            $bahanBaku->update([
                'id_inventory'    => $inventoryBaru->id,
                'id_supplier'     => $request->id_supplier,
                'id_produksi'     => $produksiBaru->id, // Pindah ke produksi/tanggal baru
                'tanggal_masuk'   => $request->tanggal_masuk,
                'jumlah_diterima' => $request->jumlah_diterima,
                'stok'            => $request->jumlah_diterima, // Pastikan logika stok sesuai kebutuhan bisnis Anda
                'harga'           => $request->harga,
                'total_harga'     => $request->jumlah_diterima * $request->harga,
            ]);

            // 4. SINKRONISASI PRODUKSI
            // Refresh Produksi Baru (Tambah Qty)
            $produksiBaru->syncTotals();

            // Refresh Produksi Lama (Hapus/Kurangi Qty)
            if ($idProduksiLama && $idProduksiLama != $produksiBaru->id) {
                $oldProd = Produksi::find($idProduksiLama);
                if ($oldProd) {
                    $oldProd->syncTotals();
                }
            }

            // 5. SINKRONISASI STOK MASTER (Jika barang berubah)
            $inventoryBaru->syncTotalStock();
            if ($idInventoryLama && $idInventoryLama != $inventoryBaru->id) {
                $oldInv = Inventory::find($idInventoryLama);
                if ($oldInv) {
                    $oldInv->syncTotalStock();
                }
            }

            DB::commit();
            return redirect()->route('bahan-baku.index')->with('success', 'Data berhasil diperbarui dan dipindahkan ke tanggal baru.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal Update: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanBaku = DetailInventory::findOrFail($id);
        $bahanBaku->delete();

        return redirect()->route('bahan-baku.index')->with('success', 'Data berhasil dihapus.');
    }
}
