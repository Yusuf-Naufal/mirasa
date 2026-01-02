<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use App\Models\DetailProduksi;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produksi::where('id_perusahaan', auth()->user()->id_perusahaan)
            ->with(['BahanBaku.barang', 'BarangKeluar.DetailInventory.Inventory.Barang'])
            ->latest('tanggal_produksi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tanggal_produksi', 'like', "%$search%")
                    ->orWhereHas('barangKeluar.DetailInventory.Inventory.Barang', function ($sq) use ($search) {
                        $sq->where('nama_barang', 'like', "%$search%");
                    });
            });
        }

        $produksis = $query->paginate(10);
        return view('pages.produksi.index', compact('produksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produksi = Produksi::with([
            'bahanBaku.barang',
            'bahanBaku.supplier',
            'barangKeluar.DetailInventory.Inventory.Barang',
            'barangKeluar.Proses'
        ])
            ->where('id_perusahaan', auth()->user()->id_perusahaan)
            ->findOrFail($id);

        return view('pages.produksi.show', compact('produksi'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateDetail(Request $request, $id)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'total_kupas' => 'required|numeric|min:0',
            'total_a'     => 'required|numeric|min:0',
            'total_s'     => 'required|numeric|min:0',
            'total_j'     => 'required|numeric|min:0',
        ]);

        try {
            // 2. Cari data detail produksi berdasarkan ID
            $detail = DetailProduksi::findOrFail($id);

            // 3. Update data
            $detail->update([
                'total_kupas' => $request->total_kupas,
                'total_a'     => $request->total_a,
                'total_s'     => $request->total_s,
                'total_j'     => $request->total_j,
            ]);

            // 4. Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Data hasil produksi berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error jika terjadi kegagalan sistem
            Log::error("Gagal update detail produksi ID {$id}: " . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
