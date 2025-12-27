<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Filter Dropdown Perusahaan (Untuk Super Admin tampil semua, untuk yang lain hanya miliknya)
        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        } else {
            $perusahaan = Perusahaan::where('id', $user->id_perusahaan)->whereNull('deleted_at')->get();
        }

        $query = Costumer::withTrashed();

        // 2. PROTEKSI ROLE: Jika bukan Super Admin, WAJIB filter berdasarkan id_perusahaan user
        if (!$user->hasRole('Super Admin')) {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }
        // Jika Super Admin dan sedang memilih filter perusahaan tertentu
        elseif ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // 3. Filter Status (Aktif / Tidak Aktif)
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'tidak_aktif') {
                $query->onlyTrashed();
            }
        } else {
            $query->whereNull('deleted_at');
        }

        // 4. Fitur Search
        $query->when($request->search, function ($q) use ($request) {
            $search = strtolower($request->search);
            $q->where(function ($inner) use ($search) {
                $inner->whereRaw('LOWER(nama_costumer) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        });

        $costumer = $query->latest()->paginate(10)->withQueryString();

        return view('pages.costumer.index', compact('costumer', 'perusahaan'));
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
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'nama_costumer' => 'required|string',
            'kode'          => 'required',
        ]);

        // Mengubah semua nilai menjadi uppercase dan menangani string kosong menjadi null
        $validated = array_map(function ($value) {
            if (is_string($value)) {
                return strtoupper(trim($value));
            }
            return $value === "" ? null : $value;
        }, $validated);

        Costumer::create($validated);

        return redirect()->back()->with('success', 'Costumer berhasil ditambahkan.');
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
        $costumer = Costumer::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'nama_costumer' => 'required|string',
            'kode' => 'required',
        ]);

        // Mengubah semua nilai menjadi uppercase dan menangani string kosong menjadi null
        $validated = array_map(function ($value) {
            if (is_string($value)) {
                return strtoupper(trim($value));
            }
            return $value === "" ? null : $value;
        }, $validated);

        $costumer->update($validated);

        return redirect()->back()->with('success', 'Costumer berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $costumer = Costumer::withTrashed()->findOrFail($id);
        $costumer->delete();

        return redirect()->back()->with('success', 'Costumer berhasil dihapus');
    }

    public function activate($id)
    {
        $costumer = Costumer::withTrashed()->findOrFail($id);

        $costumer->deleted_at = null;
        $costumer->save();

        return redirect()->back()->with('success', 'Costumer berhasil diaktifkan kembali.');
    }
}
