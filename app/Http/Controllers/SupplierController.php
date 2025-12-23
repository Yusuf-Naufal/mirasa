<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar supplier (Index)
     */
    public function index(Request $request)
    {
        // Mengambil data perusahaan dan jenis untuk isi dropdown filter di view
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        // Query dasar dengan eager loading relasi
        $query = Supplier::whereNull('deleted_at')->with('perusahaan');

        // Filter berdasarkan Search (Username atau Name)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_supplier) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        }

        // Filter berdasarkan Perusahaan
        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // Filter berdasarkan Jenis
        if ($request->filled('jenis_supplier')) {
            $query->where('jenis_supplier', $request->jenis_supplier);
        }

        $supplier = $query->paginate(10)->withQueryString();

        return view('pages.supplier.index', compact('supplier', 'perusahaan'));
    }

    /**
     * Pencegahan Error 500 jika route /create diakses manual
     */
    public function create()
    {
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        return view('pages.supplier.create', compact('perusahaan'));
    }

    /**
     * Simpan data dari Modal Tambah
     */
    // Bagian Store (Simpan)
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'id_perusahaan'  => 'required|exists:perusahaan,id',
            'nama_supplier'  => 'required|string',
            'jenis_supplier' => 'required|string',
            'kode'           => 'required|string',
        ]);

        // 2. Olah Data (Uppercase & Format Kode)
        $data = [
            'id_perusahaan'  => $validated['id_perusahaan'],
            'nama_supplier'  => $validated['nama_supplier'],
            'jenis_supplier' => $validated['jenis_supplier'],
            'kode'           => strtoupper('SUP-' . trim($validated['kode'])),
        ];

        // 3. Simpan ke Database
        Supplier::create($data);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil disimpan.');
    }

    /**
     * Edit data (Jika masih menggunakan halaman terpisah, jika modal edit, gunakan logic yang sama)
     */
    public function edit($id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        return view('pages.supplier.edit', compact('supplier', 'perusahaan'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'id_perusahaan'  => 'required|exists:perusahaan,id',
            'nama_supplier'  => 'required|string',
            'jenis_supplier' => 'required|string',
            'kode'           => 'required|string',
        ]);

        // 2. Cari Data (Termasuk yang soft deleted agar bisa diupdate)
        $supplier = Supplier::withTrashed()->findOrFail($id);

        // 3. Olah Data (Sama dengan logika Store agar Uppercase)
        $data = [
            'id_perusahaan'  => $validated['id_perusahaan'],
            'nama_supplier'  => $validated['nama_supplier'],
            'jenis_supplier' => $validated['jenis_supplier'],
            'kode'           => strtoupper('SUP-' . trim($validated['kode'])),
        ];

        // 4. Eksekusi Update
        $supplier->update($data);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui!');
    }

    /**
     * Hapus data berdasarkan index
     */
    public function destroy($id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus');
    }

}
