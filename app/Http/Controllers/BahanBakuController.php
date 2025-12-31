<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Supplier;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BahanBaku::with(['Supplier', 'Barang'])
            ->where('id_perusahaan', auth()->user()->id_perusahaan);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('Barang', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            });
        }

        // Ambil data dan kelompokkan berdasarkan tanggal_masuk
        $listBahanBaku = $query->latest('tanggal_masuk')
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d');
            });

        return view('pages.bahanbaku.index', compact('listBahanBaku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        $supplier = Supplier::where('jenis_supplier', 'Bahan Baku')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BB']);
            })
            ->get();

        return view('pages.bahanbaku.create', compact('barang', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_perusahaan'     => 'required|exists:perusahaan,id',
            'id_supplier'       => 'required|exists:supplier,id',
            'id_barang'         => 'required|exists:barang,id',
            'tanggal_masuk'     => 'required|date',
            'jumlah_diterima'   => 'required|numeric|min:0.01',
            'harga'             => 'required|numeric|min:0',
            'kondisi_barang'    => 'required|string',
            'kondisi_kendaraan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Cari atau Buat Produksi berdasarkan tanggal masuk
            $produksi = Produksi::firstOrCreate(
                [
                    'id_perusahaan'    => $request->id_perusahaan,
                    'tanggal_produksi' => $request->tanggal_masuk,
                ]
            );

            // 2. Siapkan data Bahan Baku
            $dataBahanBaku = [
                'id_perusahaan'     => $request->id_perusahaan,
                'id_supplier'       => $request->id_supplier,
                'id_barang'         => $request->id_barang,
                'id_produksi'       => $produksi->id,
                'tanggal_masuk'     => $request->tanggal_masuk,
                'jumlah_diterima'   => $request->jumlah_diterima,
                'harga'             => $request->harga,
                'total_harga'       => $request->jumlah_diterima * $request->harga,
                'kondisi_barang'    => $request->kondisi_barang,
                'kondisi_kendaraan' => $request->kondisi_kendaraan,
                'status'            => 'Diterima',
            ];

            // 3. Simpan Bahan Baku
            BahanBaku::create($dataBahanBaku);

            DB::commit();

            return redirect()->route('bahan-baku.index')
                ->with('success', 'Data berhasil disimpan. Sesi produksi dan detail rekap otomatis diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
    public function edit($id)
    {
        $user = auth()->user();

        $bahanBaku = BahanBaku::findOrFail($id);

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BB']);
            })
            ->get();

        $supplier = Supplier::where('jenis_supplier', 'Bahan Baku')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        return view('pages.bahanbaku.edit', compact('bahanBaku', 'barang', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        $validated = $request->validate([
            'id_supplier'       => 'required|exists:supplier,id',
            'id_barang'         => 'required|exists:barang,id',
            'tanggal_masuk'     => 'required|date',
            'jumlah_diterima'   => 'required|numeric|min:0',
            'harga'             => 'required|numeric|min:0',
            'kondisi_barang'    => 'required|string',
            'kondisi_kendaraan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Cek jika tanggal berubah, maka id_produksi juga harus disesuaikan
            if ($bahanBaku->tanggal_masuk != $request->tanggal_masuk) {
                $produksi = Produksi::firstOrCreate([
                    'id_perusahaan'    => auth()->user()->id_perusahaan,
                    'tanggal_produksi' => $request->tanggal_masuk,
                ]);
                $bahanBaku->id_produksi = $produksi->id;
            }

            $bahanBaku->total_harga = $request->jumlah_diterima * $request->harga;
            $bahanBaku->update($validated);

            DB::commit();
            return redirect()->route('bahan-baku.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $bahanBaku->delete(); 

        return redirect()->route('bahan-baku.index')->with('success', 'Data berhasil dihapus.');
    }
}
