<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Rule;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Dropdown Perusahaan: Jika bukan Super Admin, hanya ambil perusahaan miliknya
        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::all();
        } else {
            $perusahaan = Perusahaan::where('id', $user->id_perusahaan)->get();
        }

        // Dropdown Jenis Barang tetap tampil semua
        $jenis = JenisBarang::all();

        // 2. Query dasar dengan eager loading
        $query = Barang::whereNull('deleted_at')->with(['perusahaan', 'jenisBarang']);

        // 3. PROTEKSI DATA: Jika selain Super Admin, kunci ke perusahaan sendiri
        if (!$user->hasRole('Super Admin')) {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }
        // Jika Super Admin dan filter perusahaan dipilih
        elseif ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // 4. Filter berdasarkan Search (Nama Barang atau Kode)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_barang) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
            });
        }

        // 5. Filter berdasarkan Jenis Barang
        if ($request->filled('id_jenis')) {
            $query->where('id_jenis', $request->id_jenis);
        }

        // 6. Eksekusi Query
        $barang = $query->orderBy('id_jenis', 'asc')
            ->paginate(10)
            ->withQueryString();

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
        $user = auth()->user();

        // Tentukan ID Perusahaan yang akan digunakan
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->id_perusahaan : $user->id_perusahaan;

        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'id_jenis'      => 'required|exists:jenis_barang,id',
            'nama_barang'   => "required|string|unique:barang,nama_barang,NULL,id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'kode'          => "required|string|unique:barang,kode,NULL,id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'satuan'        => 'required|string',
            'cropped_image' => 'nullable|string',
        ], [
            'nama_barang.unique' => 'Nama barang sudah ada di perusahaan ini.',
            'kode.unique'        => 'Kode barang sudah ada di perusahaan ini.',
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
        $user = auth()->user();

        $idPerusahaan = $user->hasRole('Super Admin') ? $request->id_perusahaan : $user->id_perusahaan;
        $jenis = JenisBarang::findOrFail($request->id_jenis);

        // 2. BUAT KODE FINAL TERLEBIH DAHULU UNTUK DIVALIDASI
        $kodeFinal = strtoupper($jenis->kode . '-' . $request->kode);

        // Masukkan kodeFinal ke dalam request agar bisa divalidasi oleh validator Laravel
        $request->merge(['kode_gabungan' => $kodeFinal]);

        $request->validate([
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'id_jenis'      => 'required|exists:jenis_barang,id',
            'nama_barang'   => "required|string|unique:barang,nama_barang,{$id},id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'kode_gabungan' => "required|string|unique:barang,kode,{$id},id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",

            'satuan'        => 'required|string',
            'cropped_image' => 'nullable|string',
        ], [
            'nama_barang.unique' => 'Nama barang sudah ada di perusahaan ini.',
            'kode_gabungan.unique' => 'Kode barang sudah ada di perusahaan ini.',
        ]);

        $data = [
            'id_perusahaan' => $idPerusahaan,
            'id_jenis'      => $request->id_jenis,
            'nama_barang'   => $request->nama_barang,
            'kode'          => $kodeFinal, // Simpan hasil gabungan
            'satuan'        => strtoupper($request->satuan),
        ];

        // 3. Logika Update Gambar
        if ($request->filled('cropped_image')) {
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
