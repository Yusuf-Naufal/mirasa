<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Menggunakan withTrashed agar data yang 'deleted_at' tidak null bisa ikut terbaca
        $query = Perusahaan::withTrashed();

        // 1. Filter Status (Aktif / Tidak Aktif)
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'tidak_aktif') {
                $query->onlyTrashed(); // Hanya mengambil yang deleted_at TIDAK null
            }
        } else {
            // Default: hanya tampilkan yang aktif jika filter tidak dipilih
            $query->whereNull('deleted_at');
        }

        // 2. Fitur Search (Nama atau Alamat)
        $query->when($request->search, function ($q) use ($request) {
            $search = strtolower($request->search);

            $q->where(function ($inner) use ($search) {
                $inner->whereRaw('LOWER(nama_perusahaan) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(alamat) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kontak) like ?', ["%{$search}%"]);
            });
        });

        // 3. Filter Jenis Perusahaan
        $query->when($request->jenis_perusahaan, function ($q) use ($request) {
            $q->where('jenis_perusahaan', $request->jenis_perusahaan);
        });

        $perusahaan = $query->latest()->paginate(10)->withQueryString();

        return view('pages.perusahaan.index', compact('perusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.perusahaan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_perusahaan' => 'nullable',
            'kontak'           => 'nullable',
            'alamat'           => 'nullable',
        ]);

        // Logika penambahan 62 pada kontak
        if (!empty($validated['kontak'])) {
            $kontak = $validated['kontak'];

            // Bersihkan karakter non-digit (jika ada)
            $kontak = preg_replace('/[^0-9]/', '', $kontak);

            // Jika diawali '0', ganti dengan '62'
            if (str_starts_with($kontak, '0')) {
                $kontak = '62' . substr($kontak, 1);
            }
            // Jika belum ada '62' di depan, tambahkan
            elseif (!str_starts_with($kontak, '62')) {
                $kontak = '62' . $kontak;
            }

            $validated['kontak'] = $kontak;
        }

        // Mengubah string kosong menjadi null (Opsional, Laravel biasanya punya middleware ini)
        $validated = array_map(fn($value) => $value === "" ? null : $value, $validated);

        Perusahaan::create($validated);

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);
        return view('pages.perusahaan.show', compact('perusahaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perusahaan = Perusahaan::findOrFail($id);

        // Opsional: Jika ingin menghilangkan '62' di depan saat edit agar user tidak bingung
        if (str_starts_with($perusahaan->kontak, '62')) {
            $perusahaan->kontak = substr($perusahaan->kontak, 2);
        }

        return view('pages.perusahaan.edit', compact('perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perusahaan = Perusahaan::withTrashed()->findOrFail($id);
        
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'jenis_perusahaan' => 'required',
            'kontak'           => 'required',
            'alamat'           => 'required|string',
        ]);

        // Logika penambahan 62 pada kontak
        if (!empty($validated['kontak'])) {
            $kontak = preg_replace('/[^0-9]/', '', $validated['kontak']);
            if (str_starts_with($kontak, '0')) {
                $kontak = '62' . substr($kontak, 1);
            } elseif (!str_starts_with($kontak, '62')) {
                $kontak = '62' . $kontak;
            }
            $validated['kontak'] = $kontak;
        }

        $perusahaan->update($validated);

        return redirect()->route('perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perusahaan = Perusahaan::withTrashed()->findOrFail($id);
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')->with('success', 'Perusahaan berhasil dihapus');
    }

    public function activate($id)
    {
        $perusahaan = Perusahaan::withTrashed()->findOrFail($id);

        $perusahaan->deleted_at = null;
        $perusahaan->save();

        return redirect()->back()->with('success', 'Perusahaan berhasil diaktifkan kembali.');
    }
}
