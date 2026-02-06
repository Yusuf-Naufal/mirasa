<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\JenisBarang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $jenisBarang = JenisBarang::all();
        $search = strtolower($request->search);
        $id_jenis = $request->id_jenis;

        // Base Query Builder function untuk efisiensi
        $getBaseQuery = function () use ($user, $request, $search, $id_jenis) {
            // 1. Gunakan with(['Barang' => fn($q) => $q->withTrashed()...])
            $q = Inventory::with([
                'Barang' => function ($query) {
                    $query->withTrashed(); // Load data barang meski sudah softdelete
                },
                'Barang.jenisBarang',
                'Perusahaan'
            ])
                // 2. Filter utama: Tampilkan jika (Barang TIDAK dihapus) ATAU (Barang DIHAPUS tapi STOK > 0)
                ->whereHas('Barang', function ($bq) {
                    $bq->withTrashed()
                        ->where(function ($sub) {
                            $sub->whereNull('deleted_at') // Barang aktif
                                ->orWhere(function ($stokQuery) {
                                    $stokQuery->whereNotNull('deleted_at') // Barang terhapus
                                        ->where('inventory.stok', '>', 0); // Tapi stok masih ada
                                });
                        });
                })
                ->whereHas('Barang.jenisBarang');

            // Proteksi Data
            if (!$user->hasRole('Super Admin')) {
                $q->where('id_perusahaan', $user->id_perusahaan);
            } elseif ($request->filled('id_perusahaan')) {
                $q->where('id_perusahaan', $request->id_perusahaan);
            }

            // Search Filter (Gunakan withTrashed agar pencarian kena ke barang yang dihapus)
            if ($search) {
                $q->whereHas('Barang', function ($bq) use ($search) {
                    $bq->withTrashed()
                        ->where(function ($sub) use ($search) {
                            $sub->whereRaw('LOWER(nama_barang) like ?', ["%{$search}%"])
                                ->orWhereRaw('LOWER(kode) like ?', ["%{$search}%"]);
                        });
                });
            }

            // Jenis Barang Filter
            if ($id_jenis) {
                $q->whereHas('Barang', function ($bq) use ($id_jenis) {
                    $bq->withTrashed()->where('id_jenis', $id_jenis);
                });
            }

            return $q;
        };

        // 2. Hitung Statistik (Berdasarkan ID Barang yang Unik)
        $stats = [
            // 1. Habis: Stok benar-benar 0
            'habis'   => $getBaseQuery()->where('stok', '<=', 0)
                ->distinct('id_barang')->count('id_barang'),

            // 2. Limit: Stok > 0 DAN Stok < minimum_stok
            'limit'   => $getBaseQuery()->whereColumn('stok', '<', 'minimum_stok')
                ->where('stok', '>', 0)
                ->distinct('id_barang')->count('id_barang'),

            // 3. Warning: Stok mendekati limit (Hanya untuk limit > 0)
            'warning' => $getBaseQuery()->whereRaw('minimum_stok > 0 AND stok >= minimum_stok AND stok <= (minimum_stok * 1.2)')
                ->where('stok', '>', 0)
                ->distinct('id_barang')->count('id_barang'),
        ];

        // 1. HASIL PRODUKSI (FG, WIP, EC) - Scrollable (Ambil semua yang sesuai filter)
        $produksiItems = $getBaseQuery()->whereHas('Barang.jenisBarang', function ($q) {
            $q->whereIn('kode', ['FG', 'WIP', 'EC']);
        })->latest()->get();

        // 2. BAHAN BAKU (BB) - Paginate 20
        $bahanBakuItems = $getBaseQuery()->whereHas('Barang.jenisBarang', function ($q) {
            $q->where('kode', 'BB');
        })->latest()->paginate(20, ['*'], 'page_bb')->withQueryString();

        // 3. BAHAN PENOLONG (BP) - Paginate 20
        $penolongItems = $getBaseQuery()->whereHas('Barang.jenisBarang', function ($q) {
            $q->where('kode', 'BP');
        })->latest()->paginate(20, ['*'], 'page_bp')->withQueryString();

        return view('pages.gudang.index', compact(
            'produksiItems',
            'bahanBakuItems',
            'penolongItems',
            'jenisBarang',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $kategori = $request->query('kategori', 'produksi');

        // Ambil data barang berdasarkan perusahaan user
        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)->get();

        return view('pages.gudang.create', compact('barang', 'kategori'));
    }

    public function createProduksi()
    {
        $user = auth()->user();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['FG', 'WIP', 'EC']);
            })
            ->get();

        return view('pages.gudang.create-produksi', compact('barang'));
    }

    public function createBb()
    {
        $user = auth()->user();

        $supplier = Supplier::where('jenis_supplier', 'Bahan Baku')
            ->whereNull('deleted_at')
            ->get();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BB']);
            })
            ->get();

        return view('pages.gudang.create-bb', compact('barang', 'supplier'));
    }

    public function createBp()
    {
        $user = auth()->user();

        $supplier = Supplier::where('jenis_supplier', 'Barang')
            ->where('id_perusahaan', $user->id_perusahaan)
            ->whereNull('deleted_at')
            ->get();

        $barang = Barang::where('id_perusahaan', $user->id_perusahaan)
            ->whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['BP']);
            })
            ->get();

        return view('pages.gudang.create-bp', compact('barang', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storeProduksi(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_barang'          => 'required|exists:barang,id',
            'tanggal_masuk'      => 'required|date',
            'tanggal_exp'        => 'nullable|date|after_or_equal:tanggal_masuk',
            'jumlah_diterima'    => 'required|numeric|min:0.01',
            'harga'              => 'required|numeric|min:0',
            'tempat_penyimpanan' => 'nullable|string|max:255',
            'nomor_batch'        => 'nullable|string|max:255',
            'total_harga'        => 'required|numeric',
        ], [
            'id_barang.required'      => 'Silahkan pilih barang terlebih dahulu.',
            'jumlah_diterima.required' => 'Jumlah barang tidak boleh kosong.',
            'harga.required'          => 'Harga satuan harus diisi.',
        ]);

        try {
            // Mulai Transaksi Database
            DB::beginTransaction();

            // 2. Update atau Buat data di tabel Inventory (Master Stok)
            // Kita gunakan updateOrCreate agar jika barang & perusahaan sudah ada, stoknya ditambah
            $inventory = Inventory::where('id_perusahaan', $request->id_perusahaan)
                ->where('id_barang', $request->id_barang)
                ->first();

            if ($inventory) {
                // Jika sudah ada, tambahkan stoknya
                $inventory->stok += $request->jumlah_diterima;
                $inventory->save();
            } else {
                // Jika belum ada, buat record baru
                $inventory = Inventory::create([
                    'id_perusahaan' => $request->id_perusahaan,
                    'id_barang'     => $request->id_barang,
                    'stok'          => $request->jumlah_diterima,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'minimum_stok'  => 0,
                ]);
            }

            // 3. Simpan Riwayat ke tabel DetailInventory
            DB::table('detail_inventory')->insert([
                'id_inventory'       => $inventory->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'tanggal_exp'        => $request->tanggal_exp,
                'stok'               => $request->jumlah_diterima,
                'jumlah_diterima'    => $request->jumlah_diterima,
                'harga'              => $request->harga,
                'total_harga'        => $request->total_harga,
                'tempat_penyimpanan' => $request->tempat_penyimpanan,
                'nomor_batch'        => $request->nomor_batch,
                'status'             => 'Tersedia',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // Commit jika semua berhasil
            DB::commit();

            return redirect()->route('inventory.index')
                ->with('success', 'Data produksi berhasil disimpan dan stok telah diperbarui.');
        } catch (\Exception $e) {
            // Batalkan jika ada error
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function storeBahan(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_perusahaan'      => 'required|exists:perusahaan,id',
            'id_barang'          => 'required|exists:barang,id',
            'id_supplier'        => 'required|exists:supplier,id',
            'tanggal_masuk'      => 'required|date',
            'tempat_penyimpanan' => 'nullable|string|max:255',
            'jumlah_diterima'    => 'required|numeric|min:0',
            'jumlah_rusak'       => 'nullable|numeric|min:0',
            'stok'               => 'nullable|numeric|min:0',
            'harga'              => 'nullable|numeric|min:0',
            'total_harga'        => 'nullable|numeric|min:0',
            'diskon'             => 'nullable|numeric|min:0|max:100',
        ], [
            'id_barang.required' => 'Silahkan pilih barang terlebih dahulu.',
            'jumlah_diterima.required' => 'Jumlah barang masuk harus diisi.',
            'diskon.max' => 'Diskon tidak boleh lebih dari 100%.',
        ]);

        try {
            DB::beginTransaction();

            $jumlahMasuk = (float) $request->jumlah_diterima;
            $jumlahRusak = (float) ($request->jumlah_rusak ?? 0);
            $stokBersih  = $request->stok > 0 ? (float) $request->stok : ($jumlahMasuk - $jumlahRusak);

            // --- LOGIKA DISKON ---
            $hargaSatuan = (float) ($request->harga ?? 0);
            $diskonPersen = (float) ($request->diskon ?? 0);

            // Hitung total harga jika tidak dikirim dari frontend (Back-end safety calculation)
            $subtotal = $jumlahMasuk * $hargaSatuan;
            $potongan = $subtotal * ($diskonPersen / 100);
            $totalSetelahDiskon = $subtotal - $potongan;

            // Gunakan total_harga dari request jika ada, jika tidak gunakan hasil hitung manual
            $totalFinal = $request->total_harga ?? $totalSetelahDiskon;

            // 1. Cari atau buat Produksi
            $produksi = Produksi::firstOrCreate([
                'id_perusahaan'    => $request->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_masuk,
            ]);

            // 2. Cari atau buat Inventory (Master)
            $inventory = Inventory::firstOrCreate(
                ['id_perusahaan' => $request->id_perusahaan, 'id_barang' => $request->id_barang]
            );

            // 3. Simpan Riwayat Detail
            DetailInventory::create([
                'id_inventory'       => $inventory->id,
                'id_supplier'        => $request->id_supplier,
                'id_produksi'        => $produksi->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'jumlah_diterima'    => $jumlahMasuk,
                'jumlah_rusak'       => $jumlahRusak,
                'stok'               => $stokBersih,
                'harga'              => $hargaSatuan,
                'diskon'             => $diskonPersen,
                'total_harga'        => $totalFinal,
                'tempat_penyimpanan' => $request->tempat_penyimpanan,
                'status'             => 'Tersedia',
            ]);

            // 4. Refresh Produksi
            $produksi->syncTotals();

            // 5. Sinkronisasi Stok Master
            if ($inventory) {
                $inventory->syncTotalStock();
            }

            DB::commit();

            $namaBarang = $inventory->barang->nama_barang ?? 'Barang';

            return redirect()->route('inventory.show', $inventory->id)
                ->with('success', "Data {$namaBarang} berhasil masuk gudang dengan diskon {$diskonPersen}% dan stok diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data barang: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mengambil data inventory beserta barangnya
        $inventory = Inventory::with('barang')->findOrFail($id);

        // Mengambil details dengan filter: stok > 0 dan urutan paling lama (oldest)
        $details = $inventory->DetailInventory()
            ->where('stok', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        return view('pages.gudang.show', [
            'inventory' => $inventory,
            'details'   => $details
        ]);
    }

    public function allRiwayat(Request $request, string $id)
    {
        // Mengambil data inventory beserta barangnya
        $inventory = Inventory::with('barang')->findOrFail($id);

        // Inisialisasi query dari DetailInventory milik inventory ini
        $query = $inventory->DetailInventory()->with('Supplier');

        // Filter Pencarian (Supplier atau Nomor Batch)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nomor_batch) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('Supplier', function ($sq) use ($search) {
                        $sq->whereRaw('LOWER(nama_supplier) LIKE ?', ["%{$search}%"]);
                    });
            });
        }

        // Filter Rentang Tanggal Masuk
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        // Urutan terbaru dan paginasi dengan mempertahankan query string
        $details = $query->orderBy('tanggal_masuk', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('pages.gudang.riwayat', [
            'inventory' => $inventory,
            'details'   => $details
        ]);
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
        $barang = DetailInventory::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data Berhasil dihapus');
    }

    public function updateMinimum(Request $request, $id)
    {
        // 1. Validasi input (gunakan numeric agar titik/koma desimal diterima)
        $request->validate([
            'minimum_stok' => 'required|numeric|min:0',
        ], [
            'minimum_stok.required' => 'Angka stok minimum wajib diisi.',
            'minimum_stok.numeric'  => 'Input harus berupa angka desimal atau bulat.',
            'minimum_stok.min'      => 'Stok minimum tidak boleh kurang dari 0.',
        ]);

        try {
            // 2. Cari data inventory
            $inventory = Inventory::findOrFail($id);

            // 3. Gunakan floatval() untuk menghapus nol di depan dan mendukung desimal
            $newMinimum = floatval($request->minimum_stok);

            // 4. Update data
            $inventory->update([
                'minimum_stok' => $newMinimum
            ]);

            // 5. Logika Peringatan Stok Kritis
            if ($inventory->stok <= $newMinimum) {
                return redirect()->back()->with('warning', "Ambang batas diperbarui. Stok saat ini ({$inventory->stok}) sudah mencapai batas minimum!");
            }

            return redirect()->back()->with('success', 'Ambang batas stok berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function updateDetail(Request $request, $id)
    {
        $request->validate([
            'tanggal_masuk'      => 'required|date',
            'tanggal_exp'        => 'nullable|date',
            'jumlah_diterima'    => 'nullable|numeric|min:0',
            'jumlah_rusak'       => 'nullable|numeric|min:0',
            'stok'               => 'nullable|numeric|min:0',
            'harga'              => 'required|numeric|min:0',
            'total_harga'        => 'nullable|numeric|min:0',
            'nomor_batch'        => 'nullable|string',
            'diskon'             => 'nullable|numeric|min:0|max:100',
            'tempat_penyimpanan' => 'nullable|string|max:255',
        ]);

        try {
            $detail = DetailInventory::with('Inventory.Barang.JenisBarang')->findOrFail($id);

            // --- PROTEKSI UTAMA ---
            if ($detail->BarangKeluar()->exists()) {
                return redirect()->back()->with('error', 'Gagal! Data tidak dapat diubah karena stok dari batch ini sudah ada yang keluar/terpakai.');
            }

            DB::beginTransaction();

            // 1. Identifikasi Jenis Barang
            // Cek apakah kode jenis barang adalah 'BB' (Bahan Baku)
            $kodeJenis = $detail->Inventory->Barang->JenisBarang->kode ?? null;
            $isBahanBaku = ($kodeJenis === 'BB');

            // 2. SIMPAN STATE LAMA
            $idProduksiLama = $detail->id_produksi;

            // 3. Cari atau Buat Produksi BARU
            $produksiBaru = Produksi::firstOrCreate([
                'id_perusahaan'    => auth()->user()->id_perusahaan,
                'tanggal_produksi' => $request->tanggal_masuk,
            ]);

            // 4. Logika Perhitungan
            $diterima    = (float) $request->jumlah_diterima;
            $rusak       = (float) ($request->jumlah_rusak ?? 0);
            $stok        = $request->stok ?? ($diterima - $rusak);
            $hargaSatuan = (float) $request->harga;

            // --- LOGIKA DISKON KHUSUS BB ---
            $diskonPersen = 0;
            $totalHarga   = 0;

            if ($isBahanBaku) {
                // Jika BB, hitung diskon
                $diskonPersen = (float) ($request->diskon ?? 0);
                $subtotal     = $diterima * $hargaSatuan;
                $potongan     = $subtotal * ($diskonPersen / 100);
                $totalHarga   = $request->total_harga ?? ($subtotal - $potongan);
            } else {
                // Jika BUKAN BB, abaikan diskon (normal)
                $totalHarga = $request->total_harga ?? ($diterima * $hargaSatuan);
            }

            // 5. Update data detail
            $detail->update([
                'id_produksi'        => $produksiBaru->id,
                'tanggal_masuk'      => $request->tanggal_masuk,
                'tanggal_exp'        => $request->tanggal_exp,
                'jumlah_diterima'    => $diterima,
                'jumlah_rusak'       => $rusak,
                'nomor_batch'        => $request->nomor_batch,
                'stok'               => $stok,
                'harga'              => $hargaSatuan,
                'diskon'      => $isBahanBaku ? $diskonPersen : 0, // Set 0 jika bukan BB
                'total_harga'        => $totalHarga,
                'tempat_penyimpanan' => $request->tempat_penyimpanan,
            ]);

            // 6. SINKRONISASI PRODUKSI
            $produksiBaru->syncTotals();

            if ($idProduksiLama && $idProduksiLama != $produksiBaru->id) {
                $oldProd = Produksi::find($idProduksiLama);
                if ($oldProd) {
                    $oldProd->syncTotals();
                }
            }

            // 7. SINKRONISASI STOK MASTER
            if ($detail->Inventory) {
                $detail->Inventory->syncTotalStock();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diperbarui' . ($isBahanBaku ? ' dengan penyesuaian diskon.' : '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function quickUpdate(Request $request)
    {
        // Menggunakan Model DetailInventory
        $item = DetailInventory::findOrFail($request->id);

        DB::beginTransaction();
        try {
            switch ($request->type) {
                case 'add':
                    $request->validate(['qty' => 'required|numeric|min:1']);

                    // 1. Tambah stok dan jumlah_diterima
                    $item->stok += (float) $request->qty;
                    $item->jumlah_diterima += (float) $request->qty;

                    // 2. Kalkulasi harga setelah diskon
                    $hargaSatuan = (float) $item->harga;
                    $diskonPersen = (float) ($item->diskon ?? 0);

                    // Rumus: (Total Qty * Harga Satuan) - Potongan Diskon
                    $subtotal = $item->jumlah_diterima * $hargaSatuan;
                    $potongan = $subtotal * ($diskonPersen / 100);
                    $item->total_harga = $subtotal - $potongan;

                    $item->save(); // Save DetailInventory

                    // 3. CEK DAN UPDATE BARANG KELUAR
                    // Hitung Harga Netto baru setelah diskon & penambahan qty
                    $hargaNettoBaru = $item->jumlah_diterima > 0
                        ? ($item->total_harga / $item->jumlah_diterima)
                        : $item->harga;

                    $barangsKeluar = BarangKeluar::where('id_detail_inventory', $item->id)->get();

                    foreach ($barangsKeluar as $keluar) {
                        $keluar->harga = $hargaNettoBaru;
                        $keluar->total_harga = $keluar->jumlah_keluar * $hargaNettoBaru;
                        $keluar->save();
                    }

                    if ($item->Produksi) {
                        $item->Produksi->syncTotals();
                    }

                    $message = "Stok dan seluruh riwayat barang keluar berhasil diperbarui dengan harga netto Rp " . number_format($hargaNettoBaru, 0, ',', '.');
                    break;

                case 'reduce':
                    // Validasi agar input tidak kosong, minimal 1, dan maksimal sebesar stok yang ada
                    $request->validate([
                        'qty' => 'required|numeric|min:0.01|max:' . $item->stok
                    ], [
                        'qty.max' => 'Jumlah pengurangan (' . $request->qty . ') melebihi stok yang tersedia (' . $item->stok . ').',
                        'qty.min' => 'Jumlah pengurangan minimal 0.01.',
                    ]);

                    // 1. Kurangi stok fisik pada detail_inventory
                    $item->stok -= $request->qty;

                    $item->save();

                    $message = "Penyesuaian stok fisik berhasil disinkronkan ke inventory.";
                    break;

                case 'price':
                    $request->validate(['harga' => 'required|numeric|min:0']);

                    // 1. Update DetailInventory (Harga & Total Investasi)
                    $item->harga = $request->harga;
                    $item->total_harga = $item->jumlah_diterima * $request->harga;
                    $item->save();

                    // 2. Update Semua Barang Keluar yang merujuk ke detail ini
                    BarangKeluar::where('id_detail_inventory', $item->id)
                        ->update([
                            'harga' => $request->harga,
                            'total_harga' => DB::raw("jumlah_keluar * " . $request->harga)
                        ]);

                    $message = "Harga satuan dan riwayat barang keluar berhasil diperbarui.";
                    break;
            }

            DB::commit();
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }
}
