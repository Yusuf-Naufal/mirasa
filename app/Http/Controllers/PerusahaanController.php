<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'jenis_perusahaan' => 'nullable|string',
            'kontak'           => 'nullable|string',
            'alamat'           => 'nullable|string',
            'kota'             => 'nullable|string',
            'logo_cropped'     => 'nullable|string',
        ]);

        // 1. Logika format kontak ke 62
        if (!empty($validated['kontak'])) {
            $validated['kontak'] = $this->formatKontak($validated['kontak']);
        }

        // 2. Proses Logo dari Cropper (Base64)
        if ($request->filled('logo_cropped')) {
            $validated['logo'] = $this->uploadLogo($request->logo_cropped);
        }

        unset($validated['logo_cropped']);
        // Buat data perusahaan
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
            'jenis_perusahaan' => 'required|string',
            'kontak'           => 'required|string',
            'alamat'           => 'required|string',
            'kota'             => 'nullable|string',
            'logo_cropped'     => 'nullable|string', // Ini data Base64 dari Cropper
        ]);

        // 1. Logika format kontak ke 62
        $validated['kontak'] = $this->formatKontak($validated['kontak']);

        // 2. Proses Logo Baru dari Cropper
        if ($request->filled('logo_cropped')) {
            // Hapus file fisik logo lama jika ada
            if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }

            // Upload logo baru
            $validated['logo'] = $this->uploadLogo($request->logo_cropped);
        }

        unset($validated['logo_cropped']);

        // 4. Update data ke database
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

    /**
     * Fungsi pembantu untuk memformat nomor kontak
     */
    private function formatKontak($kontak)
    {
        $kontak = preg_replace('/[^0-9]/', '', $kontak);

        if (str_starts_with($kontak, '0')) {
            return '62' . substr($kontak, 1);
        } elseif (!str_starts_with($kontak, '62')) {
            return '62' . $kontak;
        }
        return $kontak;
    }

    /**
     * Fungsi pembantu untuk upload base64 logo
     */
    private function uploadLogo($base64Data)
    {
        // Hapus header base64 (data:image/png;base64,)
        $image_service_str = explode(',', $base64Data);
        $image = base64_decode($image_service_str[1]);

        $fileName = 'logo/' . Str::random(10) . '_' . time() . '.png';
        Storage::disk('public')->put($fileName, $image);

        return $fileName;
    }
}
