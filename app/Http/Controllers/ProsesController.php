<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Proses;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        // Query dasar dengan eager loading relasi
        $query = Proses::whereNull('deleted_at');

        // Filter berdasarkan Search (Username atau Name)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_proses) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        }

        // Filter berdasarkan Perusahaan
        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        $proses = $query->paginate(10)->withQueryString();

        return view('pages.proses.index', compact('proses', 'perusahaan'));
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
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id', // pastikan tabelnya benar
            'nama_proses'   => 'required|string',
            'kode'          => 'required|string',
        ]);

        Proses::create([
            'id_perusahaan' => $request->id_perusahaan,
            'nama_proses'   => $request->nama_proses,
            'kode'          => $request->kode,
        ]);

        return redirect()->route('proses.index')->with('success', 'Data proses berhasil ditambahkan.');
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
        $proses = Proses::findOrFail($id);

        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'nama_proses'   => 'required|string',
            'kode'          => 'required|string',
        ]);

        $proses->update([
            'id_perusahaan' => $request->id_perusahaan,
            'nama_proses'   => $request->nama_proses,
            'kode'          => $request->kode,
        ]);

        return redirect()->route('proses.index')->with('success', 'Data proses berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proses = Proses::findOrFail($id);
        $proses->delete();

        return redirect()->route('proses.index')->with('success', 'Data proses berhasil dihapus.');
    }
}
