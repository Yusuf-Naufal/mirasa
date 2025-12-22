<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar supplier (Index)
     */
    public function index(Request $request)
    {
        // Ambil data dari session, default array kosong
        $suppliers = session()->get('suppliers', []);

        // Fitur Pencarian
        $search = $request->input('search');
        if ($search) {
            $suppliers = array_filter($suppliers, function($item) use ($search) {
                $namaMatch = str_contains(strtolower($item['nama'] ?? ''), strtolower($search));
                $kodeMatch = str_contains(strtolower($item['kode'] ?? ''), strtolower($search));
                return $namaMatch || $kodeMatch;
            });
        }

        // Return view utama
        return view('pages.supplier.index', compact('suppliers'));
    }

    /**
     * Pencegahan Error 500 jika route /create diakses manual
     */
    public function create()
    {
        return redirect()->route('supplier.index');
    }

    /**
     * Simpan data dari Modal Tambah
     */
    // Bagian Store (Simpan)
public function store(Request $request) 
{
    $request->validate([
        'kode'     => 'required|string',
        'nama'     => 'required|string',
        'kategori' => 'required|string'
    ]);

    $suppliers = session()->get('suppliers', []);

    $suppliers[] = [
        'kode'     => $request->kode,
        'nama'     => $request->nama,
        'kategori' => $request->kategori,
    ];

    session()->put('suppliers', $suppliers);

    return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan!');
}

    /**
     * Edit data (Jika masih menggunakan halaman terpisah, jika modal edit, gunakan logic yang sama)
     */
    public function edit($index)
    {
        $suppliers = session()->get('suppliers', []);

        if (!isset($suppliers[$index])) {
            return redirect()->route('supplier.index')->with('error', 'Data tidak ditemukan.');
        }

        $supplier = $suppliers[$index];
        return view('pages.supplier.edit', compact('supplier', 'index'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $index)
    {
        $request->validate([
            'kode'     => 'required|string',
            'nama'     => 'required|string',
            'kategori' => 'required|string'
        ]);

        $suppliers = session()->get('suppliers', []);

        if (isset($suppliers[$index])) {
            $suppliers[$index] = [
                'kode'     => $request->kode,
                'nama'     => $request->nama,
                'kategori' => $request->kategori,
            ];

            session()->put('suppliers', $suppliers);

            return redirect()->route('supplier.index')
                ->with('success', 'Supplier berhasil diperbarui!');
        }

        return redirect()->route('supplier.index')->with('error', 'Gagal memperbarui data.');
    }

    /**
     * Hapus data berdasarkan index
     */
    public function destroy($index)
    {
        $suppliers = session()->get('suppliers', []);
        
        if (isset($suppliers[$index])) {
            unset($suppliers[$index]);
            // Re-index agar urutan array [0, 1, 2] tetap rapi setelah dihapus
            session()->put('suppliers', array_values($suppliers));
            return redirect()->route('supplier.index')->with('success', 'Data berhasil dihapus.');
        }
        
        return redirect()->route('supplier.index')->with('error', 'Data gagal dihapus.');
    }

    /**
     * Bersihkan semua data supplier di session
     */
    public function destroyAll()
    {
        session()->forget('suppliers');
        return redirect()->route('supplier.index')->with('success', 'Semua data session dikosongkan.');
    }
}