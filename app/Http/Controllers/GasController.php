<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Perusahaan;
use App\Models\Pengeluaran;
use App\Models\PemakaianGas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');

        $perusahaan = Perusahaan::whereNull('deleted_at')->get();

        $query = PemakaianGas::with(['supplier', 'perusahaan']);

        // 1. Filter Perusahaan
        if ($isSuperAdmin) {
            if ($request->filled('id_perusahaan')) {
                $query->where('id_perusahaan', $request->id_perusahaan);
            }
        } else {
            $query->where('id_perusahaan', $user->id_perusahaan);
        }

        // 2. Filter Search (Tanggal atau Nama Supplier)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tanggal_pemakaian', 'like', "%$search%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('nama_supplier', 'like', "%$search%");
                    });
            });
        }

        // 3. Filter Rentang Tanggal (Date Range)
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $start = $dates[0];
                $end = $dates[1];
                $query->whereBetween('tanggal_pemakaian', [$start, $end]);
            } else {
                // Jika hanya pilih satu tanggal
                $query->where('tanggal_pemakaian', $dates[0]);
            }
        }

        // Pagination
        $gas = $query->latest('tanggal_pemakaian')->paginate(10)->withQueryString();

        // Query Supplier untuk Modal Tambah/Edit
        $supplierQuery = Supplier::where('jenis_supplier', 'Barang')->whereNull('deleted_at');

        if (!$isSuperAdmin) {
            $supplierQuery->where('id_perusahaan', $user->id_perusahaan);
        } elseif ($request->filled('id_perusahaan')) {
            $supplierQuery->where('id_perusahaan', $request->id_perusahaan);
        }

        $supplier = $supplierQuery->get();

        return view('pages.gas.index', compact('supplier', 'perusahaan', 'gas'));
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
            'id_supplier' => 'required|exists:supplier,id',
            'tanggal_pemakaian' => 'required|date',
            'jumlah_gas' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $idPerusahaan = $user->hasRole('Super Admin') ? $request->id_perusahaan : $user->id_perusahaan;

        // 1. Tentukan bulan dan tahun dari tanggal pemakaian
        $tanggal = Carbon::parse($request->tanggal_pemakaian);
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;

        // 2. Cari data Pengeluaran OPERASIONAL - GAS pada bulan & tahun tersebut
        $pengeluaran = Pengeluaran::where('id_perusahaan', $idPerusahaan)
            ->where('kategori', 'OPERASIONAL')
            ->where('sub_kategori', 'GAS')
            ->whereMonth('tanggal_pengeluaran', $bulan)
            ->whereYear('tanggal_pengeluaran', $tahun)
            ->first();

        // 3. Simpan PemakaianGas
        PemakaianGas::create([
            'id_perusahaan' => $idPerusahaan,
            'id_supplier'   => $request->id_supplier,
            'id_pengeluaran' => $pengeluaran ? $pengeluaran->id : null,
            'tanggal_pemakaian' => $request->tanggal_pemakaian,
            'jumlah_gas'    => $request->jumlah_gas,
            'harga'         => $request->harga,
            'total_harga'   => $request->harga * $request->jumlah_gas,
        ]);

        return redirect()->back()->with('success', 'Data pemakaian gas berhasil ditambahkan' . ($pengeluaran ? ' dan direlasikan ke pengeluaran operasional.' : '.'));
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'tanggal_pemakaian' => 'required|date',
            'jumlah_gas' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        $gas = PemakaianGas::findOrFail($id);

        // Keamanan: Pastikan user biasa tidak bisa mengedit data perusahaan lain
        if (!auth()->user()->hasRole('Super Admin') && $gas->id_perusahaan !== auth()->user()->id_perusahaan) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        try {
            DB::beginTransaction();

            // 1. Hitung total harga baru
            $totalHargaBaru = $request->harga * $request->jumlah_gas;

            // 2. Logika Relasi Pengeluaran (Jika tanggal berubah bulan/tahun)
            $tanggalBaru = Carbon::parse($request->tanggal_pemakaian);
            $bulanBaru = $tanggalBaru->month;
            $tahunBaru = $tanggalBaru->year;

            // Cari pengeluaran yang sesuai dengan kriteria dan waktu pemakaian baru
            $pengeluaran = Pengeluaran::where('id_perusahaan', $gas->id_perusahaan)
                ->where('kategori', 'OPERASIONAL')
                ->where('sub_kategori', 'GAS')
                ->whereMonth('tanggal_pengeluaran', $bulanBaru)
                ->whereYear('tanggal_pengeluaran', $tahunBaru)
                ->first();

            // 3. Update data pemakaian gas
            $gas->update([
                'id_supplier' => $request->id_supplier,
                'id_pengeluaran' => $pengeluaran ? $pengeluaran->id : null,
                'tanggal_pemakaian' => $request->tanggal_pemakaian,
                'jumlah_gas' => $request->jumlah_gas,
                'harga' => $request->harga,
                'total_harga' => $totalHargaBaru,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data pemakaian gas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gas = PemakaianGas::findOrFail($id);

        $gas->delete();
        return redirect()->route('gas.index')->with('success', 'Data gas barang berhasil dihapus');
    }
}
