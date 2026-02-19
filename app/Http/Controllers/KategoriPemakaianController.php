<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Models\KategoriPemakaian;
use Illuminate\Support\Facades\Auth;

class KategoriPemakaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::whereNull('deleted_at')->get(); 
            $kategoris = KategoriPemakaian::all();
        } else {
            $perusahaan = Perusahaan::where('id', $user->id_perusahaan)->get();
            $kategoris = KategoriPemakaian::where('id_perusahaan', $user->id_perusahaan)->get();
        }

        // Mengarahkan ke view sesuai struktur folder Anda
        return view('pages.pemakaian.kategori.index', compact('kategoris', 'perusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'id_perusahaan' => $user->role === 'Super Admin' ? 'required|exists:perusahaan,id' : 'nullable',
        ]);

        KategoriPemakaian::create([
            'nama_kategori' => strtoupper($request->nama_kategori),
            'satuan' => $request->satuan,
            'id_perusahaan' => $user->role === 'Super Admin' ? $request->id_perusahaan : $user->id_perusahaan,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
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
        $user = Auth::user();
        $kategori = KategoriPemakaian::findOrFail($id);
        if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $kategori->id_perusahaan) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit data ini.');
        }

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'id_perusahaan' => Auth::user()->role === 'Super Admin' ? 'required' : 'nullable',
        ]);

        $kategori->update([
            'nama_kategori' => strtoupper($request->nama_kategori),
            'satuan' => $request->satuan,
            'id_perusahaan' => Auth::user()->role === 'Super Admin' ? $request->id_perusahaan : $kategori->id_perusahaan,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari kategori termasuk yang sedang aktif
        $kategori = KategoriPemakaian::findOrFail($id);

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus (Soft Delete).');
    }
}
