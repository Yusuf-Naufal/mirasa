<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Perusahaan;
use App\Models\PemakaianGas;
use Illuminate\Http\Request;

class GasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        $query = PemakaianGas::with(['supplier', 'perusahaan']);

        if ($isSuperAdmin) {
            if ($request->has('id_perusahaan') && $request->id_perusahaan != '') {
                $query->where('id_perusahaan', $request->id_perusahaan);
            }
        } else {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tanggal_pemakaian', 'like', "%$search%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('nama_supplier', 'like', "%$search%");
                    });
            });
        }

        // Menggunakan paginate(10) alih-alih get()
        $gas = $query->latest('tanggal_pemakaian')->paginate(10)->withQueryString();

        $supplierQuery = Supplier::where('jenis_supplier', 'Barang')->whereNull('deleted_at');

        if (!$isSuperAdmin) {
            $supplierQuery->where('id_perusahaan', $user->id_perusahaan);
        } elseif ($request->id_perusahaan) {
            $supplierQuery->where('id_perusahaan', $request->id_perusahaan);
        }

        $supplier = $supplierQuery->get();

        return view('pages.gas.index', compact('supplier', 'perusahaan', 'gas'));
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
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'tanggal_pemakaian' => 'required|date',
            'jumlah_gas' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();

        PemakaianGas::create([
            'id_perusahaan' => $user->hasRole('Super Admin') ? $request->id_perusahaan : $user->id_perusahaan,
            'id_supplier'   => $request->id_supplier,
            'tanggal_pemakaian' => $request->tanggal_pemakaian,
            'jumlah_gas'    => $request->jumlah_gas,
        ]);

        return redirect()->back()->with('success', 'Data pemakaian gas berhasil ditambahkan.');
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
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'tanggal_pemakaian' => 'required|date',
            'jumlah_gas' => 'required|numeric|min:0',
        ]);

        $gas = PemakaianGas::findOrFail($id);

        // Keamanan: Pastikan user biasa tidak bisa mengedit data perusahaan lain
        if (!auth()->user()->hasRole('Super Admin') && $gas->id_perusahaan !== auth()->user()->id_perusahaan) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        $gas->update([
            'id_supplier' => $request->id_supplier,
            'tanggal_pemakaian' => $request->tanggal_pemakaian,
            'jumlah_gas' => $request->jumlah_gas,
        ]);

        return redirect()->back()->with('success', 'Data pemakaian gas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gas = PemakaianGas::findOrFail($id);

        $gas->delete();
        return redirect()->route('gas.index')->with('success', 'Data gas barang berhasil dihapus');
    }
}
