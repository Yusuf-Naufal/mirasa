<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil input dari form search dan modal filter
        $search = $request->input('search');
        $status = $request->input('status'); // Mengambil 'aktif' atau 'tidak_aktif'
        $is_unggulan = $request->input('is_unggulan');

        $produk = Produk::query()
            // Filter Pencarian (Gunakan 'ilike' untuk PostgreSQL agar tidak case-sensitive)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_produk', 'ilike', '%' . $search . '%')
                        ->orWhere('deskripsi', 'ilike', '%' . $search . '%');
                });
            })
            // Filter Status Aktif (Tampil di Web)
            ->when($status, function ($query, $status) {
                if ($status === 'aktif') {
                    return $query->whereRaw('is_aktif = true');
                } elseif ($status === 'tidak_aktif') {
                    return $query->whereRaw('is_aktif = false');
                }
            })
            // Filter Produk Unggulan
            ->when($is_unggulan, function ($query, $is_unggulan) {
                if ($is_unggulan === 'ya') {
                    return $query->whereRaw('is_unggulan = true');
                } elseif ($is_unggulan === 'tidak') {
                    return $query->whereRaw('is_unggulan = false');
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Memastikan parameter search & filter terbawa saat pindah halaman

        return view('pages.landing.produk.index', compact('produk'));
    }

    public function create()
    {
        return view('pages.landing.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'   => "required|string|unique:produk,nama_produk",
            'deskripsi'     => 'required|string',
            'cropped_image' => 'required|string',
        ]);

        $is_aktif = ($request->is_aktif == '1' || $request->is_aktif == 'on') ? 'true' : 'false';
        $is_unggulan = ($request->is_unggulan == '1' || $request->is_unggulan == 'on') ? 'true' : 'false';

        $data = [
            'nama_produk' => $request->nama_produk,
            'rasa'        => $request->rasa,
            'kategori'    => $request->kategori,
            'deskripsi'   => $request->deskripsi,
            'is_aktif'    => $is_aktif,
            'is_unggulan' => $is_unggulan,
        ];

        // dd($data);

        if ($request->filled('cropped_image')) {
            $data['foto'] = $this->processBase64Crop($request->cropped_image);
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil disimpan.');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('pages.landing.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|max:255|unique:produk,nama_produk,' . $produk->id,
            'deskripsi'   => 'required|string',
        ]);

        // Konversi manual ke string 'true' atau 'false' tanpa bantuan helper boolean
        $is_aktif = ($request->is_aktif == '1' || $request->is_aktif == 'on') ? 'true' : 'false';
        $is_unggulan = ($request->is_unggulan == '1' || $request->is_unggulan == 'on') ? 'true' : 'false';

        $data = [
            'nama_produk' => $request->nama_produk,
            'rasa'        => $request->rasa,
            'kategori'    => $request->kategori,
            'deskripsi'   => $request->deskripsi,
            'is_aktif'    => $is_aktif,
            'is_unggulan' => $is_unggulan,
        ];

        if ($request->filled('cropped_image')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $this->processBase64Crop($request->cropped_image);
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function processBase64Crop($base64String)
    {
        $image_parts = explode(";base64,", $base64String);
        $image_base64 = base64_decode($image_parts[1]);

        // Folder disesuaikan ke 'produk' agar konsisten
        $fileName = 'produk/' . time() . '_' . uniqid() . '.jpg';

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
