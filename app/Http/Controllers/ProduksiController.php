<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;

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
}
