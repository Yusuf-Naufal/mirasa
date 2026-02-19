<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Perusahaan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Models\KategoriPemakaian;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $id_perusahaan_user = $user->id_perusahaan;

        // 1. Ambil Data Kategori & Perusahaan untuk Dropdown Filter
        if ($user->hasRole('Super Admin')) {
            $kategoris = KategoriPemakaian::all();
            $perusahaan = Perusahaan::all();
        } else {
            $kategoris = KategoriPemakaian::where('id_perusahaan', $id_perusahaan_user)->get();
            $perusahaan = Perusahaan::where('id', $id_perusahaan_user)->get();
        }

        // 2. Query Data Pemakaian dengan Eager Loading
        $query = Pemakaian::with(['KategoriPemakaian', 'Perusahaan']);

        // 3. Proteksi & Filter Role: Jika bukan Super Admin, WAJIB kunci ke id_perusahaan user
        if (!$user->hasRole('Super Admin')) {
            $query->where('id_perusahaan', $id_perusahaan_user);
        } else {
            if ($request->filled('id_perusahaan')) {
                $query->where('id_perusahaan', $request->id_perusahaan);
            }
        }

        // 4. Filter Kategori (Penting untuk navigasi per kategori)
        if ($request->filled('id_kategori')) {
            $query->where('id_kategori', $request->id_kategori);
        }

        // 5. Filter Rentang Tanggal
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('tanggal_pemakaian', [$dates[0], $dates[1]]);
            } else {
                $query->whereDate('tanggal_pemakaian', $dates[0]);
            }
        }

        $pemakaians = $query->orderBy('tanggal_pemakaian', 'desc')->get();

        return view('pages.pemakaian.index', compact('kategoris', 'perusahaan', 'pemakaians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori_pemakaian,id',
            'id_perusahaan' => 'required|exists:perusahaan,id',
            'jumlah' => 'required|numeric|min:0',
            'harga' => 'nullable|numeric|min:0',
            'tanggal_pemakaian' => 'nullable|date',
        ]);

        // 1. Validasi Unik: 1 Tanggal, 1 Kategori, 1 Perusahaan
        $exists = Pemakaian::where('id_perusahaan', $request->id_perusahaan)
            ->where('id_kategori', $request->id_kategori)
            ->whereDate('tanggal_pemakaian', $request->tanggal_pemakaian)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data untuk kategori ini pada tanggal tersebut sudah dicatat.');
        }

        // 2. Persiapkan data kategori dan perhitungan
        $kategori = KategoriPemakaian::findOrFail($request->id_kategori);
        $total_harga = $request->jumlah * $request->harga;

        // Ambil bulan dan tahun dari tanggal pemakaian untuk pencarian relasi
        $bulanPemakaian = date('m', strtotime($request->tanggal_pemakaian));
        $tahunPemakaian = date('Y', strtotime($request->tanggal_pemakaian));

        // 3. Cari Pengeluaran yang cocok (Bulan & Tahun sama, Sub Kategori sama dengan Nama Kategori)
        // Pengeluaran bersifat bulanan, Pemakaian bersifat harian
        $pengeluaran = Pengeluaran::where('id_perusahaan', $request->id_perusahaan)
            ->whereMonth('tanggal_pengeluaran', $bulanPemakaian)
            ->whereYear('tanggal_pengeluaran', $tahunPemakaian)
            ->where('sub_kategori', strtoupper($kategori->nama_kategori))
            ->first();

        // 4. Simpan data Pemakaian
        Pemakaian::create([
            'id_perusahaan'     => $request->id_perusahaan,
            'id_kategori'       => $request->id_kategori,
            'id_pengeluaran'    => $pengeluaran ? $pengeluaran->id : null,
            'tanggal_pemakaian' => $request->tanggal_pemakaian,
            'jumlah'            => $request->jumlah,
            'harga'             => $request->harga,
            'total_harga'       => $total_harga,
        ]);

        return back()->with('success', 'Data pemakaian berhasil dicatat.');
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
        $request->validate([
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric',
            'tanggal_pemakaian' => 'required|date',
        ]);

        $user = auth()->user();
        $pemakaian = Pemakaian::findOrFail($id);
        if (!$user->hasRole('Super Admin') && $user->id_perusahaan !== $pemakaian->id_perusahaan) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit data ini.');
        }

        $total_harga = $request->jumlah * $request->harga;

        $pemakaian->update([
            'id_perusahaan' => $request->id_perusahaan ?? $pemakaian->id_perusahaan,
            'tanggal_pemakaian' => $request->tanggal_pemakaian,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'total_harga' => $total_harga,
        ]);

        return back()->with('success', 'Data pemakaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $pemakaian->delete();

        return back()->with('success', 'Data pemakaian berhasil dihapus.');
    }
}
