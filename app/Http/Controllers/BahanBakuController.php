<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Inventory;
use App\Models\Perusahaan;
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

        // Mengambil data perusahaan untuk filter Super Admin
        $perusahaan = [];
        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        }

        // Mengambil daftar master barang (Bahan Baku) untuk dropdown filter
        $barang = Barang::whereHas('jenisBarang', function ($q) {
            $q->where('kode', 'BB');
        })
            ->when(!$user->hasRole('Super Admin'), function ($q) use ($user) {
                $q->where('id_perusahaan', $user->id_perusahaan);
            })
            ->get();

        // 1. Query dasar DetailInventory
        $query = DetailInventory::with(['Inventory.Barang.jenisBarang', 'Inventory.Perusahaan', 'supplier'])
            ->whereHas('Inventory.Barang.jenisBarang', function ($q) {
                $q->where('kode', 'BB');
            });

        // 2. Filter Berdasarkan Perusahaan
        if (!$user->hasRole('Super Admin')) {
            $query->whereHas('Inventory', function ($q) use ($user) {
                $q->where('id_perusahaan', $user->id_perusahaan);
            });
        } elseif ($request->filled('id_perusahaan')) {
            $query->whereHas('Inventory', function ($q) use ($request) {
                $q->where('id_perusahaan', $request->id_perusahaan);
            });
        }

        // 3. Filter Berdasarkan Barang Spesifik
        if ($request->filled('jenis')) {
            $query->whereHas('Inventory', function ($q) use ($request) {
                $q->where('id_barang', $request->jenis);
            });
        }

        // 4. Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('Inventory.Barang', function ($sq) use ($search) {
                    $sq->where('nama_barang', 'like', "%{$search}%");
                })->orWhereHas('supplier', function ($sq) use ($search) {
                    $sq->where('nama_supplier', 'like', "%{$search}%");
                });
            });
        }

        // 5. Filter Rentang Tanggal
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('tanggal_masuk', [$dates[0], $dates[1]]);
            } else {
                $query->whereDate('tanggal_masuk', $dates[0]);
            }
        }

        // 6. Pagination
        $bahanBakuPaginator = $query->latest('tanggal_masuk')->paginate(30)->withQueryString();

        // 7. Kelompokkan hasil pagination berdasarkan tanggal
        $listBahanBaku = $bahanBakuPaginator->getCollection()->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d');
        });

        return view('pages.bahanbaku.index', compact('listBahanBaku', 'bahanBakuPaginator', 'barang', 'perusahaan'));
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
            'id_perusahaan'   => 'required|exists:perusahaan,id',
            'id_supplier'     => 'required|exists:supplier,id',
            'id_barang'       => 'required|exists:barang,id',
            'tanggal_masuk'   => 'required|date',
            'jumlah_diterima' => 'required|numeric|min:0.01',
            'harga'           => 'required|numeric|min:0',
            'diskon'          => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            // 0. Ambil informasi barang untuk pengecekan jenis
            $barang = Barang::with('JenisBarang')->findOrFail($request->id_barang);
            $isBahanBaku = ($barang->JenisBarang->kode ?? null) === 'BB';

            // 1. Cari atau Buat Sesi Produksi otomatis
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $request->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_masuk,
            ]);

            // 2. Update atau Buat data Master Stok
            $inventory = Inventory::firstOrCreate(
                [
                    'id_perusahaan' => $request->id_perusahaan,
                    'id_barang'     => $request->id_barang
                ]
            );

            // --- LOGIKA KALKULASI DISKON ---
            $jumlah       = (float) $request->jumlah_diterima;
            $hargaSatuan  = (float) $request->harga;
            $diskonPersen = $isBahanBaku ? (float) ($request->diskon ?? 0) : 0;

            $subtotal     = $jumlah * $hargaSatuan;
            $potongan     = $subtotal * ($diskonPersen / 100);
            $totalHarga   = $subtotal - $potongan;

            // 3. Simpan Riwayat ke Detail Inventory
            $detail = DetailInventory::create([
                'id_inventory'    => $inventory->id,
                'id_supplier'     => $request->id_supplier,
                'id_produksi'     => $produksi->id,
                'tanggal_masuk'   => $request->tanggal_masuk,
                'jumlah_diterima' => $jumlah,
                'stok'            => $jumlah,
                'harga'           => $hargaSatuan,
                'diskon'          => $diskonPersen,
                'total_harga'     => $totalHarga,
                'status'          => 'Tersedia',
            ]);

            // 4. SINKRONISASI
            $produksi->syncTotals();
            $inventory->syncTotalStock();

            DB::commit();

            $pesan = $isBahanBaku && $diskonPersen > 0
                ? "Bahan baku berhasil masuk dengan diskon {$diskonPersen}%."
                : "Barang berhasil masuk gudang dan stok diperbarui.";

            return redirect()->route('bahan-baku.index')->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
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

        if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $bahanBaku->Inventory->id_perusahaan) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit data ini.');
        }

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
            'id_supplier'     => 'required|exists:supplier,id',
            'id_barang'       => 'required|exists:barang,id',
            'tanggal_masuk'   => 'required|date',
            'jumlah_diterima' => 'required|numeric|min:0.01',
            'harga'           => 'required|numeric|min:0',
            'diskon'          => 'nullable|numeric|min:0|max:100', // Tambahkan validasi diskon
        ]);

        try {
            $bahanBaku = DetailInventory::findOrFail($id);

            // --- PROTEKSI UTAMA ---
            if ($bahanBaku->BarangKeluar()->exists()) {
                return redirect()->back()->with('error', 'Gagal! Data tidak dapat diubah karena stok dari batch ini sudah ada yang keluar/terpakai.');
            }

            DB::beginTransaction();

            // 0. Ambil informasi barang BARU untuk pengecekan jenis
            $barangBaru = Barang::with('JenisBarang')->findOrFail($request->id_barang);
            $isBahanBaku = ($barangBaru->JenisBarang->kode ?? null) === 'BB';

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

            // --- LOGIKA KALKULASI DISKON ---
            $jumlah       = (float) $request->jumlah_diterima;
            $hargaSatuan  = (float) $request->harga;
            $diskonPersen = $isBahanBaku ? (float) ($request->diskon ?? 0) : 0;

            $subtotal     = $jumlah * $hargaSatuan;
            $potongan     = $subtotal * ($diskonPersen / 100);
            $totalHarga   = $subtotal - $potongan;

            // 3. Update Data DetailInventory
            $bahanBaku->update([
                'id_inventory'    => $inventoryBaru->id,
                'id_supplier'     => $request->id_supplier,
                'id_produksi'     => $produksiBaru->id,
                'tanggal_masuk'   => $request->tanggal_masuk,
                'jumlah_diterima' => $jumlah,
                'stok'            => $jumlah,
                'harga'           => $hargaSatuan,
                'diskon'          => $diskonPersen,
                'total_harga'     => $totalHarga,
            ]);

            // 4. SINKRONISASI PRODUKSI
            $produksiBaru->syncTotals();

            if ($idProduksiLama && $idProduksiLama != $produksiBaru->id) {
                $oldProd = Produksi::find($idProduksiLama);
                if ($oldProd) {
                    $oldProd->syncTotals();
                }
            }

            // 5. SINKRONISASI STOK MASTER
            $inventoryBaru->syncTotalStock();
            if ($idInventoryLama && $idInventoryLama != $inventoryBaru->id) {
                $oldInv = Inventory::find($idInventoryLama);
                if ($oldInv) {
                    $oldInv->syncTotalStock();
                }
            }

            DB::commit();
            return redirect()->route('bahan-baku.index')
                ->with('success', 'Data berhasil diperbarui' . ($isBahanBaku ? ' dengan penyesuaian diskon.' : '.'));
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
