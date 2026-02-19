<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Perusahaan;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use App\Imports\BarangImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Validators\ValidationException;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Dropdown Perusahaan
        if ($user->hasRole('Super Admin')) {
            $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        } else {
            $perusahaan = Perusahaan::where('id', $user->id_perusahaan)->get();
        }

        $jenis = JenisBarang::all();

        // 2. Inisialisasi Query berdasarkan Status
        if ($request->status == 'tidak_aktif') {
            $query = Barang::onlyTrashed();
        } elseif ($request->status == 'semua') {
            $query = Barang::withTrashed();
        } else {
            $query = Barang::query();
        }

        // Load relasi
        $query->with(['perusahaan', 'jenisBarang']);

        // 3. PROTEKSI DATA & FILTER PERUSAHAAN
        if (!$user->hasRole('Super Admin')) {
            $query->where('id_perusahaan', $user->id_perusahaan);
        } elseif ($request->filled('id_perusahaan')) {
            $query->where('id_perusahaan', $request->id_perusahaan);
        }

        // 4. Filter berdasarkan Search
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
        $barang = $query->latest()
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
            'id_perusahaan'     => 'required|exists:perusahaan,id',
            'id_jenis'          => 'required|exists:jenis_barang,id',
            'nama_barang'       => "required|string|unique:barang,nama_barang,NULL,id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'kode'              => "required|string|unique:barang,kode,NULL,id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'satuan'            => 'required|string',
            'nilai_konversi'    => 'nullable',
            'jenis'             => 'nullable',
            'isi_bungkus'       => 'nullable',
            'cropped_image'     => 'nullable|string',
        ], [
            'nama_barang.unique' => 'Nama barang sudah ada di perusahaan ini.',
            'kode.unique'        => 'Kode barang sudah ada di perusahaan ini.',
        ]);

        // 1. Ambil data Jenis & Gabungkan Kode (UPPERCASE)
        $jenis = JenisBarang::findOrFail($request->id_jenis);
        $kodeFinal = strtoupper($jenis->kode . '-' . $request->kode);

        // 2. Persiapkan Data
        $data = $request->only(['id_perusahaan', 'id_jenis', 'nama_barang', 'satuan', 'nilai_konversi', 'isi_bungkus', 'jenis']);
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
        $user = auth()->user();
        $barang = Barang::withTrashed()->findOrFail($id);
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();
        $jenis = JenisBarang::get();

        if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $barang->id_perusahaan) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit barang dari perusahaan lain.');
        }

        return view('pages.barang.edit', compact('perusahaan', 'jenis', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);
        $user = auth()->user();

        if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $barang->id_perusahaan) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah data barang dari perusahaan lain.');
        }

        $idPerusahaan = $user->hasRole('Super Admin') ? $request->id_perusahaan : $user->id_perusahaan;
        $jenis = JenisBarang::findOrFail($request->id_jenis);

        // 2. BUAT KODE FINAL TERLEBIH DAHULU UNTUK DIVALIDASI
        $kodeFinal = strtoupper($jenis->kode . '-' . $request->kode);

        // Masukkan kodeFinal ke dalam request agar bisa divalidasi oleh validator Laravel
        $request->merge(['kode_gabungan' => $kodeFinal]);

        $request->validate([
            'id_perusahaan'     => 'required|exists:perusahaan,id',
            'id_jenis'          => 'required|exists:jenis_barang,id',
            'nama_barang'       => "required|string|unique:barang,nama_barang,{$id},id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'kode_gabungan'     => "required|string|unique:barang,kode,{$id},id,id_perusahaan,{$idPerusahaan},deleted_at,NULL",
            'nilai_konversi'    => 'nullable',
            'jenis'             => 'nullable',
            'isi_bungkus'       => 'nullable',
            'satuan'            => 'required|string',
            'cropped_image'     => 'nullable|string',
        ], [
            'nama_barang.unique' => 'Nama barang sudah ada di perusahaan ini.',
            'kode_gabungan.unique' => 'Kode barang sudah ada di perusahaan ini.',
        ]);

        $data = [
            'id_perusahaan'     => $idPerusahaan,
            'id_jenis'          => $request->id_jenis,
            'nama_barang'       => $request->nama_barang,
            'nilai_konversi'    => $request->nilai_konversi,
            'isi_bungkus'       => $request->isi_bungkus,
            'jenis'             => $request->jenis ?? null,
            'kode'              => $kodeFinal,
            'satuan'            => strtoupper($request->satuan),
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

    public function activate($id)
    {
        // 1. Cari data yang akan di-restore (termasuk yang sedang terhapus)
        $barang = Barang::withTrashed()->findOrFail($id);

        // 2. Cek apakah ada barang AKTIF lain dengan Nama & Kode yang sama di perusahaan yang sama
        $isDuplicate = Barang::where('id_perusahaan', $barang->id_perusahaan)
            ->where(function ($q) use ($barang) {
                $q->where('nama_barang', $barang->nama_barang)
                    ->orWhere('kode', $barang->kode);
            })
            ->where('id', '!=', $id)
            ->exists();

        // 3. Jika ditemukan duplikat, batalkan proses dan kirim pesan error
        if ($isDuplicate) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan! Nama atau Kode barang tersebut sudah digunakan oleh barang aktif lain di perusahaan ini.');
        }

        // 4. Proses Restore
        $barang->restore();

        return redirect()->back()->with('success', 'Barang berhasil diaktifkan kembali.');
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

    public function downloadTemplate()
    {
        return Excel::download(new class implements WithHeadings, WithEvents, WithStyles, WithTitle {

            public function title(): string
            {
                return 'Template Import Barang';
            }

            public function headings(): array
            {
                return [
                    'nama_barang',
                    'kode_barang',
                    'satuan',
                    'kategori_sistem',
                    'sub_kategori_bb',
                    'nilai_konversi',
                    'isi_bungkus'
                ];
            }

            public function styles(Worksheet $sheet)
            {
                return [
                    // Style untuk Header (Baris 1)
                    1 => [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '4F46E5'],
                        ],
                        'alignment' => ['horizontal' => 'center']
                    ],
                ];
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
                        $rowCount = 100;

                        // 1. Setup Kolom Otomatis
                        foreach (range('A', 'G') as $col) {
                            $sheet->getColumnDimension($col)->setAutoSize(true);
                        }

                        // 2. Dropdown Kategori Sistem (D)
                        $validationKategori = $sheet->getCell('D2')->getDataValidation();
                        $validationKategori->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_STOP)
                            ->setAllowBlank(false)
                            ->setShowDropDown(true)
                            ->setShowInputMessage(true)
                            ->setPromptTitle('Pilih Kategori')
                            ->setPrompt('Pilih salah satu: FG, WIP, EC, BB, BP')
                            ->setFormula1('"FG,WIP,EC,BB,BP"');

                        // 3. Dropdown Sub Kategori BB (E)
                        $validationBB = $sheet->getCell('E2')->getDataValidation();
                        $validationBB->setType(DataValidation::TYPE_LIST)
                            ->setShowInputMessage(true)
                            ->setPromptTitle('Khusus BB')
                            ->setPrompt('Isi Utama/Pendukung jika kategori adalah BB')
                            ->setFormula1('"Utama,Pendukung"');

                        // 4. Input Message untuk Konversi (F & G)
                        $msgKonversi = $sheet->getCell('F2')->getDataValidation();
                        $msgKonversi->setType(DataValidation::TYPE_WHOLE)
                            ->setShowInputMessage(true)
                            ->setPromptTitle('Info Konversi')
                            ->setPrompt('Wajib isi angka jika kategori FG/WIP/EC. Selain itu biarkan kosong.');

                        // Terapkan ke baris selanjutnya
                        for ($i = 2; $i <= $rowCount; $i++) {
                            $sheet->getCell("D$i")->setDataValidation(clone $validationKategori);
                            $sheet->getCell("E$i")->setDataValidation(clone $validationBB);
                            $sheet->getCell("F$i")->setDataValidation(clone $msgKonversi);
                            $sheet->getCell("G$i")->setDataValidation(clone $msgKonversi);

                            // Beri border tipis untuk area input agar user tahu batasnya
                            $sheet->getStyle("A$i:G$i")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        }
                    },
                ];
            }
        }, 'template_barang_v2.xlsx');
    }

    public function import(Request $request)
    {
        // 1. Validasi awal
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // Maks 10MB
        ]);

        try {
            // 2. Pastikan file benar-benar ada dan tidak rusak saat upload
            if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
                return back()->with('error', 'File tidak ditemukan atau rusak. Silakan coba unggah ulang.');
            }

            $import = new BarangImport;

            // 3. Gunakan path asli secara eksplisit
            Excel::import($import, $request->file('file')->getRealPath());

            $berhasil = $import->getRowCount();
            $gagal = $import->failures()->count();

            if ($gagal > 0) {
                $details = collect($import->failures())->map(function ($failure) {
                    return "<li class='mb-1'><b>Baris " . $failure->row() . ":</b> " . implode(", ", $failure->errors()) . "</li>";
                })->implode('');

                return back()->with('error_import', [
                    'title' => "Hasil Import: $berhasil Berhasil, $gagal Gagal",
                    'success_count' => $berhasil,
                    'fail_count' => $gagal,
                    'html' => "<ul class='text-left text-xs list-none p-0'>" . $details . "</ul>"
                ]);
            }

            return back()->with('success', "Berhasil mengimpor $berhasil data barang.");
        } catch (\Exception $e) {
            // Menangkap error jika path kosong atau file tidak terbaca
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
