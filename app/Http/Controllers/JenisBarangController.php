<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JenisBarang::query();

        // 1. Fitur Search
        $query->when($request->search, function ($q) use ($request) {
            $search = strtolower($request->search);

            $q->where(function ($inner) use ($search) {
                $inner->whereRaw('LOWER(nama_jenis) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        });

        $jenis = $query->latest()->paginate(10)->withQueryString();

        return view('pages.barang.jenis.index', compact('jenis'));
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
        $validated = $request->validate([
            'nama_jenis'    => 'required',
            'kode'          => 'required',
        ]);

        // Mengubah string kosong menjadi null (Opsional, Laravel biasanya punya middleware ini)
        $validated = array_map(fn($value) => $value === "" ? null : $value, $validated);

        JenisBarang::create($validated);

        return redirect()->route('barang.jenis.index')->with('success', 'Jenis barang berhasil ditambahkan');
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
    public function update(Request $request, string $id)
    {
        $jenis = JenisBarang::findOrFail($id);

        $validated = $request->validate([
            'nama_jenis'    => 'required',
            'kode'          => 'required',
        ]);

        $jenis->update($validated);

        return redirect()->route('barang.jenis.index')->with('success', 'Data jenis barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenis = JenisBarang::findOrFail($id);

        $jenis->delete();
        return redirect()->route('barang.jenis.index')->with('success', 'Data jenis barang berhasil dihapus');
    }
}
