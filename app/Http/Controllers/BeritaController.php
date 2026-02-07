<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil input dari form search dan modal filter
        $search = $request->input('search');
        $status = $request->input('status');
        $is_unggulan = $request->input('is_unggulan');

        $berita = Berita::query()
            // Filter Pencarian (Gunakan 'ilike' untuk PostgreSQL agar tidak case-sensitive)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('judul', 'ilike', '%' . $search . '%')
                        ->orWhere('ringkasan', 'ilike', '%' . $search . '%');
                });
            })
            // Filter Status Aktif (Tampil di Web)
            ->when($status, function ($query, $status) {
                if ($status === 'aktif') {
                    return $query->whereRaw('status_publish = true');
                } elseif ($status === 'tidak_aktif') {
                    return $query->whereRaw('status_publish = false');
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.landing.berita.index', compact('berita'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.landing.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:255',
            'kategori'        => 'required',
            'penulis'         => 'required',
            'ringkasan'       => 'required',
            'konten'          => 'required',
            'cropped_image'   => 'required',
            'status_publish'  => 'required',
            'tanggal_publish' => 'nullable|date',
        ]);

        $status_publish = ($request->status_publish == '1' || $request->status_publish == 'on') ? 'true' : 'false';

        $data = [
            'judul'             => $request->judul,
            'penulis'           => $request->penulis,
            'kategori'          => $request->kategori,
            'ringkasan'         => $request->ringkasan,
            'konten'            => $request->konten,
            'tanggal_publish'   => $request->tanggal_publish,
            'status_publish'    => $status_publish,
        ];

        if ($request->filled('cropped_image')) {
            $data['gambar_utama'] = $this->processBase64Crop($request->cropped_image);
        }

        Berita::create($data);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diterbitkan!');
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
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);

        return view('pages.landing.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            // 'judul'           => ['required', 'string', 'max:255', Rule::unique('berita')->ignore($berita->id)],
            'kategori'        => 'required',
            'penulis'         => 'required',
            'ringkasan'       => 'required',
            'konten'          => 'required',
            'status_publish'  => 'required',
            'tanggal_publish' => 'nullable',
            'cropped_image'   => 'nullable',
        ]);

        $status_publish = ($request->status_publish == '1' || $request->status_publish == 'on') ? 'true' : 'false';

        $data = [
            'judul'             => $request->judul,
            'penulis'           => $request->penulis,
            'kategori'          => $request->kategori,
            'ringkasan'         => $request->ringkasan,
            'konten'            => $request->konten,
            'tanggal_publish'   => $request->tanggal_publish,
            'status_publish'    => $status_publish,
        ];

        // Logika Update Gambar
        if ($request->filled('cropped_image')) {
            // Hapus file lama jika ada
            if ($berita->gambar_utama && Storage::disk('public')->exists($berita->gambar_utama)) {
                Storage::disk('public')->delete($berita->gambar_utama);
            }
            // Simpan gambar baru
            $data['gambar_utama'] = $this->processBase64Crop($request->cropped_image);
        }

        $berita->update($data);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar_utama && Storage::disk('public')->exists($berita->gambar_utama)) {
            Storage::disk('public')->delete($berita->gambar_utama);
        }

        $berita->delete();
        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function processBase64Crop($base64String)
    {
        $image_parts = explode(";base64,", $base64String);
        $image_base64 = base64_decode($image_parts[1]);

        // Folder disesuaikan ke 'produk' agar konsisten
        $fileName = 'berita/' . time() . '_' . uniqid() . '.jpg';

        $srcImage = imagecreatefromstring($image_base64);
        $width = imagesx($srcImage);
        $height = imagesy($srcImage);

        $destImage = imagecreatetruecolor($width, $height);
        imagefill($destImage, 0, 0, imagecolorallocate($destImage, 255, 255, 255));
        imagecopy($destImage, $srcImage, 0, 0, 0, 0, $width, $height);

        ob_start();
        imagejpeg($destImage, null, 60);
        $compressedData = ob_get_clean();

        Storage::disk('public')->put($fileName, $compressedData);
        imagedestroy($srcImage);
        imagedestroy($destImage);

        return $fileName;
    }
}
