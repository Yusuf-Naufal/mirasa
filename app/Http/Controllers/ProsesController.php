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
        $user = auth()->user();

        // 1. Dropdown Perusahaan: Hanya tampilkan perusahaan milik user jika bukan Super Admin
        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        } else {
            $perusahaan = Perusahaan::where('id', $user->id_perusahaan)
                ->whereNull('deleted_at')
                ->get();
        }

        // 2. Query dasar
        $query = Proses::withTrashed();

        // 3. PROTEKSI DATA
        if (!$user->hasRole('Super Admin')) {
            $query->where('id_perusahaan', $user->id_perusahaan);
        } elseif ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // 4. Filter berdasarkan Search (Nama Proses atau Kode)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_proses) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'tidak_aktif') {
                $query->onlyTrashed();
            }
        } else {
            $query->whereNull('deleted_at');
        }

        // 5. Eksekusi Paginate
        $proses = $query->latest()->paginate(10)->withQueryString();

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
        $proses = Proses::withTrashed()->findOrFail($id);

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

    public function activate($id)
    {
        $proses = Proses::withTrashed()->findOrFail($id);

        $proses->deleted_at = null;
        $proses->save();

        return redirect()->back()->with('success', 'Proses berhasil diaktifkan kembali.');
    }
}
