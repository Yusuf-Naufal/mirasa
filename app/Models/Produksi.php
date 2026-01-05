<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';

    protected $fillable = [
        'id_perusahaan',
        'tanggal_produksi',
    ];

    public function syncTotals()
    {
        // 1. Paksa refresh relasi agar data detail_inventory yang baru pindah terbaca
        $this->unsetRelation('DetailInventory');

        $rekap = $this->DetailInventory()
            ->whereHas('Inventory.Barang.jenisBarang', function ($q) {
                $q->where('kode', 'BB');
            })
            // Menggunakan join untuk memastikan kita menjumlahkan id_barang yang benar
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->select('inventory.id_barang')
            ->selectRaw('SUM(detail_inventory.jumlah_diterima) as total_qty')
            ->selectRaw('SUM(detail_inventory.total_harga) as total_nilai')
            ->groupBy('inventory.id_barang')
            ->get();

        $activeBarangIds = $rekap->pluck('id_barang')->toArray();

        // 2. Hapus detail_produksi yang barangnya sudah tidak ada di produksi ini
        $this->DetailProduksi()->whereNotIn('id_barang', $activeBarangIds)->delete();

        // 3. Update atau buat baru untuk barang yang tersisa/baru masuk
        foreach ($rekap as $data) {
            $this->DetailProduksi()->updateOrCreate(
                ['id_barang' => $data->id_barang],
                [
                    'total_bb_diterima' => $data->total_qty,
                    'total_harga_bb'    => $data->total_nilai,
                ]
            );
        }
    }

    public function DetailProduksi()
    {
        return $this->hasMany(DetailProduksi::class, 'id_produksi');
    }

    public function DetailInventory()
    {
        return $this->hasMany(DetailInventory::class, 'id_produksi');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function BarangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_produksi');
    }
}
