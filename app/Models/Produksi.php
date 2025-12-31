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

    public function BahanBaku()
    {
        return $this->hasMany(BahanBaku::class, 'id_produksi');
    }

    public function syncTotals()
    {
        $rekap = $this->BahanBaku()
            ->select('id_barang')
            ->selectRaw('SUM(jumlah_diterima) as total_qty')
            ->selectRaw('SUM(total_harga) as total_nilai')
            ->groupBy('id_barang')
            ->get();

        // Ambil ID barang yang masih aktif dalam rekap
        $activeBarangIds = $rekap->pluck('id_barang')->toArray();

        // Hapus detail produksi untuk barang yang sudah tidak ada di catatan bahan baku
        $this->DetailProduksi()->whereNotIn('id_barang', $activeBarangIds)->delete();

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

    public function getRekapBahanBakuAttribute()
    {
        // Mengelompokkan bahan baku yang masuk berdasarkan barangnya
        return $this->BahanBaku()
            ->select('id_barang')
            ->selectRaw('SUM(jumlah_diterima) as total_qty')
            ->selectRaw('SUM(total_harga) as total_nilai')
            ->groupBy('id_barang')
            ->with('Barang')
            ->get();
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
