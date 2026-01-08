<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailProduksi;
use App\Models\DetailInventory;
use App\Models\Perusahaan;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        // Query Dasar: Jika Super Admin filter id_perusahaan, jika bukan admin paksa ke id_perusahaan user
        $query = Produksi::query();

        if (auth()->user()->hasRole('Super Admin')) {
            if ($request->filled('id_perusahaan')) {
                $query->where('id_perusahaan', $request->id_perusahaan);
            }
        } else {
            $query->where('id_perusahaan', auth()->user()->id_perusahaan);
        }

        $query->withCount(['DetailInventory as BahanBakuMasuk' => function ($q) {
            $q->whereHas('Inventory.Barang.jenisBarang', function ($sq) {
                $sq->where('kode', 'BB');
            });
        }])
            ->with(['BarangKeluar.DetailInventory.Inventory.Barang'])
            ->latest('tanggal_produksi');

        // Filter Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tanggal_produksi', 'like', "%$search%")
                    ->orWhereHas('BarangKeluar.DetailInventory.Inventory.Barang', function ($sq) use ($search) {
                        $sq->where('nama_barang', 'like', "%$search%");
                    });
            });
        }

        // Filter Rentang Tanggal (Flatpickr Range)
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                // Jika memilih range (dari - sampai)
                $query->whereBetween('tanggal_produksi', [$dates[0], $dates[1]]);
            } else {
                // Jika hanya memilih satu tanggal spesifik
                $query->whereDate('tanggal_produksi', $dates[0]);
            }
        }

        $produksis = $query->paginate(10)->withQueryString(); // Tetap bawa parameter filter saat pindah page

        return view('pages.produksi.index', compact('produksis', 'perusahaan'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $produksi = Produksi::where('id_perusahaan', auth()->user()->id_perusahaan)->findOrFail($id);

        // Tentukan tab aktif (default 'bb')
        $activeTab = $request->get('tab', 'bb');

        // Load data sesuai tab aktif (opsional: bisa load semua, tapi pagination akan butuh appends)
        $bahanBaku = DetailInventory::where('id_produksi', $id)
            ->whereHas('Inventory.Barang.jenisBarang', fn($q) => $q->where('kode', 'BB'))
            ->paginate(10, ['*'], 'page_bb')->appends(['tab' => 'bb']);

        $barangPenolong = DetailInventory::where('id_produksi', $id)
            ->whereHas('Inventory.Barang.jenisBarang', fn($q) => $q->where('kode', 'BP'))
            ->paginate(10, ['*'], 'page_bp')->appends(['tab' => 'bp']);

        $barangKeluar = BarangKeluar::where('id_produksi', $id)
            ->paginate(10, ['*'], 'page_bk')->appends(['tab' => 'bk']);

        return view('pages.produksi.show', compact('produksi', 'bahanBaku', 'barangPenolong', 'barangKeluar', 'activeTab'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateDetail(Request $request, $id)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'total_kupas' => 'required|numeric|min:0',
            'total_a'     => 'required|numeric|min:0',
            'total_s'     => 'required|numeric|min:0',
            'total_j'     => 'required|numeric|min:0',
        ]);

        try {
            // 2. Cari data detail produksi berdasarkan ID
            $detail = DetailProduksi::findOrFail($id);

            // 3. Update data
            $detail->update([
                'total_kupas' => $request->total_kupas,
                'total_a'     => $request->total_a,
                'total_s'     => $request->total_s,
                'total_j'     => $request->total_j,
            ]);

            // 4. Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Data hasil produksi berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error jika terjadi kegagalan sistem
            Log::error("Gagal update detail produksi ID {$id}: " . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
