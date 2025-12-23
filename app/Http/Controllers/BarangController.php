<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data perusahaan dan jenis untuk isi dropdown filter di view
        $perusahaan = Perusahaan::all();
        $jenis = JenisBarang::all();

        // Query dasar dengan eager loading relasi
        $query = Barang::withTrashed()->with(['perusahaan', 'jenisBarang']);

        // Filter berdasarkan Search (Username atau Name)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        }

        // Filter berdasarkan Perusahaan
        if ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // Filter berdasarkan Jenis Barang
        if ($request->filled('id_jenis')) {
            $query->where('id_jenis', $request->id_jenis);
        }

        // Order by id_jenis (Sesuai permintaan) dan paginate
        // appends(request()->query()) memastikan filter tidak hilang saat ganti halaman
        $barang = $query->orderBy('id_jenis', 'asc')->paginate(10)->withQueryString();

        return view('pages.barang.index', compact('barang', 'perusahaan', 'jenis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        $jenis = JenisBarang::get();

        return view('pages.barang.create', compact('perusahaan', 'jenis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'id_jenis'      => 'required|exists:jenis_barang,id',
            'nama_barang'   => 'required|string',
            'kode'          => 'required|string',
            'satuan'        => 'required|string',
            'cropped_image' => 'nullable|string',
        ]);

        // 1. Ambil data Jenis & Gabungkan Kode (UPPERCASE)
        $jenis = JenisBarang::findOrFail($request->id_jenis);
        $kodeFinal = strtoupper($jenis->kode . '-' . $request->kode);

        // 2. Persiapkan Data
        $data = $request->only(['id_perusahaan', 'id_jenis', 'nama_barang', 'satuan']);
        $data['kode'] = $kodeFinal;
        $data['satuan'] = strtoupper($request->satuan);

        // 4. Logika Simpan Gambar (Base64 Crop)
        if ($request->filled('cropped_image')) {
            $data['foto'] = $this->processBase64Crop($request->cropped_image);
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil disimpan.');
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
        $barang = Barang::withTrashed()->findOrFail($id);
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        $jenis = JenisBarang::get();

        return view('pages.barang.edit', compact('perusahaan', 'jenis', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'id_jenis'      => 'required|exists:jenis_barang,id',
            'nama_barang'   => 'required|string',
            'kode'          => 'required|string',
            'satuan'        => 'required|string',
            'cropped_image' => 'nullable|string',
        ]);

        // 1. Ambil data Jenis & Gabungkan Kode (UPPERCASE) sesuai format store
        $jenis = JenisBarang::findOrFail($request->id_jenis);
        $kodeFinal = strtoupper($jenis->kode . '-' . $request->kode);


        $data = $request->only(['id_perusahaan', 'id_jenis', 'nama_barang', 'satuan']);
        $data['kode'] = $kodeFinal;
        $data['satuan'] = strtoupper($request->satuan);

        // 2. Logika Update Gambar (Prioritas Base64 Crop)
        if ($request->filled('cropped_image')) {
            // Hapus foto lama jika ada
            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }
            $data['foto'] = $this->processBase64Crop($request->cropped_image);
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dipindahkan ke sampah');
    }

    private function processBase64Crop($base64String)
    {
        // Bersihkan header Base64
        $image_parts = explode(";base64,", $base64String);
        $image_base64 = base64_decode($image_parts[1]);

        // Nama file unik
        $fileName = 'barang/' . time() . '_' . uniqid() . '.jpg';

        // Inisialisasi GD Library dari string
        $srcImage = imagecreatefromstring($image_base64);

        $width = imagesx($srcImage);
        $height = imagesy($srcImage);

        // Buat kanvas baru untuk kompresi (Convert ke JPG)
        $destImage = imagecreatetruecolor($width, $height);

        // Set background putih (menghindari background hitam pada PNG transparan)
        $white = imagecolorallocate($destImage, 255, 255, 255);
        imagefill($destImage, 0, 0, $white);

        // Copy gambar ke kanvas baru
        imagecopy($destImage, $srcImage, 0, 0, 0, 0, $width, $height);

        // Gunakan output buffering untuk menangkap data JPG terkompresi
        ob_start();
        imagejpeg($destImage, null, 60); // Simpan dengan kualitas 60%
        $compressedData = ob_get_clean();

        // Simpan ke Storage
        Storage::disk('public')->put($fileName, $compressedData);

        // Bersihkan memori server
        imagedestroy($srcImage);
        imagedestroy($destImage);

        return $fileName;
    }

    private function compressManual($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $tempPath = $file->getRealPath();
        $fileName = 'barang/' . time() . '_' . uniqid() . '.jpg';

        // Buat resource gambar
        if ($extension == 'png') {
            $srcImage = imagecreatefrompng($tempPath);
            // Putihkan background transparan
            $bg = imagecreatetruecolor(imagesx($srcImage), imagesy($srcImage));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagecopy($bg, $srcImage, 0, 0, 0, 0, imagesx($srcImage), imagesy($srcImage));
            $srcImage = $bg;
        } else {
            $srcImage = imagecreatefromjpeg($tempPath);
        }

        // Resize jika lebar > 1000px
        $width = imagesx($srcImage);
        $height = imagesy($srcImage);
        $maxWidth = 1000;

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = floor($height * ($maxWidth / $width));
            $destImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        } else {
            $destImage = $srcImage;
        }

        // Tangkap data ke buffer
        ob_start();
        imagejpeg($destImage, null, 60); // Kualitas 60%
        $compressedData = ob_get_clean();

        Storage::disk('public')->put($fileName, $compressedData);

        // Free memory
        imagedestroy($srcImage);
        if ($destImage !== $srcImage) imagedestroy($destImage);

        return $fileName;
    }
}
