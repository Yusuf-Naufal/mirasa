<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pemakaian;
use App\Models\Pengeluaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KategoriPemakaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Filter
        $search = $request->get('search');
        $id_perusahaan = $request->get('id_perusahaan');
        $is_hpp = $request->get('is_hpp');
        $date_range = $request->get('date_range');

        // Default bulan/tahun
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $kategoriList = ['OPERASIONAL', 'OFFICE', 'LIMBAH', 'KESEJAHTERAAN', 'MAINTENANCE', 'ADMINISTRASI'];

        // 2. Base Query
        $queryAll = Pengeluaran::query();

        // KEAMANAN: Filter Perusahaan harus menjadi scope utama
        $queryAll->where(function ($q) use ($id_perusahaan) {
            if (auth()->user()->hasRole('Super Admin')) {
                if ($id_perusahaan) {
                    $q->where('id_perusahaan', $id_perusahaan);
                }
            } else {
                $q->where('id_perusahaan', auth()->user()->id_perusahaan);
            }
        });

        // Filter Pencarian (Dibungkus closure agar logic OR tidak merusak filter AND lainnya)
        if ($search) {
            $searchUpper = strtoupper($search);
            $queryAll->where(function ($q) use ($searchUpper) {
                $q->where('nama_pengeluaran', 'LIKE', "%{$searchUpper}%")
                    ->orWhere('sub_kategori', 'LIKE', "%{$searchUpper}%")
                    ->orWhere('keterangan', 'LIKE', "%{$searchUpper}%")
                    ->orWhere('jumlah_pengeluaran', 'LIKE', "%{$searchUpper}%");
            });
        }

        // Filter HPP
        if ($is_hpp !== null && $is_hpp !== '') {
            $queryAll->where('is_hpp', $is_hpp);
        }

        // Filter Tanggal
        if ($date_range) {
            $dates = explode(' to ', $date_range);
            if (count($dates) == 2) {
                $queryAll->whereBetween('tanggal_pengeluaran', [$dates[0], $dates[1]]);
            } else {
                $queryAll->where('tanggal_pengeluaran', $dates[0]);
            }
        } else {
            $queryAll->whereMonth('tanggal_pengeluaran', $month)
                ->whereYear('tanggal_pengeluaran', $year);
        }

        // Eksekusi data untuk perhitungan total (tanpa pagination)
        $allData = $queryAll->get();
        $totalPengeluaran = $allData->sum('jumlah_pengeluaran');

        // 3. Looping Kategori
        $perKategori = [];
        foreach ($kategoriList as $kat) {
            $pageName = 'page_' . strtolower(str_replace(' ', '_', $kat));

            // Gunakan clone agar base filter tidak berubah saat iterasi berikutnya
            $items = (clone $queryAll)->where('kategori', $kat)
                ->latest('tanggal_pengeluaran')
                ->paginate(10, ['*'], $pageName)
                ->withQueryString();

            $totalPerKat = $allData->where('kategori', $kat)->sum('jumlah_pengeluaran');

            $perKategori[$kat] = [
                'items' => $items,
                'total' => $totalPerKat,
                'pageName' => $pageName
            ];
        }

        $activeTab = $request->get('tab', $kategoriList[0]);
        $perusahaan = auth()->user()->hasRole('Super Admin') ? \App\Models\Perusahaan::all() : collect();

        return view('pages.pengeluaran.index', compact(
            'totalPengeluaran',
            'perKategori',
            'activeTab',
            'month',
            'year',
            'kategoriList',
            'perusahaan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createOperasional()
    {
        return view('pages.pengeluaran.create-operasional');
    }

    public function createOffice()
    {
        return view('pages.pengeluaran.create-office');
    }

    public function createPengolahanLimbah()
    {
        return view('pages.pengeluaran.create-limbah');
    }

    public function createGajiKaryawan()
    {
        return view('pages.pengeluaran.create-gaji');
    }

    public function createMaintenance()
    {
        return view('pages.pengeluaran.create-maintenance');
    }

    public function createAdministrasi()
    {
        return view('pages.pengeluaran.create-administrasi');
    }


    /**
     * Menyimpan pengeluaran baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengeluaran' => 'required|string|max:255',
            'kategori' => 'required',
            'jumlah_pengeluaran' => 'required|numeric',
            'tanggal_pengeluaran' => 'required|date',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $Kategori = strtoupper($request->kategori);
            $subKategori = strtoupper($request->sub_kategori);
            $id_perusahaan = auth()->user()->id_perusahaan;

            // 1. Handle Upload Bukti
            $path = null;
            if ($request->hasFile('bukti')) {
                $path = $this->compressFile($request->file('bukti'));
            }

            // 2. Simpan Data ke Tabel Pengeluaran
            $pengeluaran = Pengeluaran::create([
                'id_perusahaan'      => auth()->user()->id_perusahaan,
                'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
                'nama_pengeluaran'   => $request->nama_pengeluaran,
                'kategori'           => $Kategori,
                'sub_kategori'       => $subKategori,
                'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
                'is_hpp'             => $request->is_hpp,
                'keterangan'         => $request->keterangan,
                'bukti'              => $path,
            ]);

            // 3. Logika Khusus Gas: Hubungkan pemakaian harian yang belum terbayar
            if ($Kategori === 'OPERASIONAL') {
                // Cek apakah sub_kategori yang diinput terdaftar di tabel kategori_pemakaian
                $cekKategoriPemakaian = KategoriPemakaian::where('id_perusahaan', $id_perusahaan)
                    ->where('nama_kategori', $subKategori)
                    ->first();

                if ($cekKategoriPemakaian) {
                    // Tentukan awal dan akhir bulan dari tanggal pengeluaran yang diinput
                    $tanggalInput = Carbon::parse($request->tanggal_pengeluaran);
                    $awalBulan = $tanggalInput->copy()->startOfMonth();
                    $akhirBulan = $tanggalInput->copy()->endOfMonth();

                    // Update semua pemakaian (Listrik, Gas, Air, dll) yang:
                    // 1. Milik kategori yang cocok
                    // 2. Belum memiliki relasi pengeluaran (id_pengeluaran IS NULL)
                    // 3. Dalam periode bulan yang sama
                    Pemakaian::where('id_perusahaan', $id_perusahaan)
                        ->where('id_kategori', $cekKategoriPemakaian->id)
                        ->whereNull('id_pengeluaran')
                        ->whereBetween('tanggal_pemakaian', [$awalBulan, $akhirBulan])
                        ->update(['id_pengeluaran' => $pengeluaran->id]);
                }
            }

            DB::commit();
            return redirect()->route('pengeluaran.index', ['tab' => $Kategori])
                ->with('success', 'Pengeluaran ' . $Kategori . ' berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
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
    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $Kategori = $pengeluaran->kategori;

        $request->validate([
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah_pengeluaran' => 'required|numeric',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 1. Handle Update Bukti (Hapus lama & Kompres baru)
            if ($request->hasFile('bukti')) {
                if ($pengeluaran->bukti && Storage::disk('public')->exists($pengeluaran->bukti)) {
                    Storage::disk('public')->delete($pengeluaran->bukti);
                }

                $pengeluaran->bukti = $this->compressFile($request->file('bukti'));
            }

            // 2. Update Data Utama
            $pengeluaran->update([
                'nama_pengeluaran'    => $request->nama_pengeluaran,
                'sub_kategori'        => $request->sub_kategori,
                'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
                'nama_pengeluaran'    => $request->nama_pengeluaran,
                'jumlah_pengeluaran'  => $request->jumlah_pengeluaran,
                'is_hpp'              => $request->is_hpp,
                'keterangan'          => $request->keterangan,
            ]);

            // 3. Sinkronisasi Gas jika kategori berubah atau data diedit
            if ($pengeluaran->sub_kategori === 'Gas' && $pengeluaran->kategori === 'OPERASIONAL') {
                Pemakaian::where('id_pengeluaran', $pengeluaran->id)
                    ->update(['id_pengeluaran' => $pengeluaran->id]);
            }

            DB::commit();
            return redirect()->route('pengeluaran.index', ['tab' => $Kategori])->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $kategoriAsal = $pengeluaran->kategori; // Simpan kategori untuk redirect tab

        try {
            DB::beginTransaction();

            // 1. Hapus file bukti dari storage jika ada
            if ($pengeluaran->bukti && Storage::disk('public')->exists($pengeluaran->bukti)) {
                Storage::disk('public')->delete($pengeluaran->bukti);
            }

            // 2. Jika pengeluaran ini adalah GAS, lepaskan kaitan di tabel pemakaian_gas
            if (strtoupper($pengeluaran->sub_kategori) === 'GAS') {
                Pemakaian::where('id_pengeluaran', $pengeluaran->id)
                    ->update(['id_pengeluaran' => null]);
            }

            // 3. Hapus data dari database
            $pengeluaran->delete();

            DB::commit();

            // Redirect kembali ke tab kategori yang sama
            return redirect()->route('pengeluaran.index', ['tab' => $kategoriAsal])
                ->with('success', 'Data pengeluaran berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    private function compressFile($file, $folder = 'bukti_pengeluaran')
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.' . $extension;
        $destinationPath = storage_path('app/public/' . $folder);

        // Buat folder jika belum ada
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            // --- LOGIKA KOMPRESI GAMBAR ---
            $source = ($extension == 'png')
                ? @imagecreatefrompng($file->getRealPath())
                : @imagecreatefromjpeg($file->getRealPath());

            if (!$source) {
                // Jika gagal load gambar (file korup), simpan apa adanya
                return $file->storeAs($folder, $filename, 'public');
            }

            // Simpan dengan kualitas 60%
            $fullPath = $destinationPath . '/' . $filename;
            if ($extension == 'png') {
                imagepng($source, $fullPath, 6); // Skala 0-9 untuk PNG
            } else {
                imagejpeg($source, $fullPath, 60); // Kualitas 0-100 untuk JPG
            }

            imagedestroy($source);
            return $folder . '/' . $filename;
        } elseif ($extension == 'pdf') {
            return $file->storeAs($folder, $filename, 'public');
        }

        return null;
    }
}
