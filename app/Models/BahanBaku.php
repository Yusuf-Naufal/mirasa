<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'id_perusahaan',
        'id_supplier',
        'id_barang',
        'id_produksi',
        'tanggal_masuk',
        'jumlah_diterima',
        'harga',
        'total_harga',
        'kondisi_barang',
        'kondisi_kendaraan',
        'status',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function Produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi');
    }

    protected static function booted()
    {
        // Memicu sinkronisasi saat data ditambahkan atau diperbarui
        static::saved(function ($bahanBaku) {
            if ($bahanBaku->Produksi) {
                $bahanBaku->Produksi->syncTotals();
            }
        });

        // Memicu sinkronisasi saat data dihapus
        static::deleted(function ($bahanBaku) {
            if ($bahanBaku->Produksi) {
                $bahanBaku->Produksi->syncTotals();
            }
        });
    }
}
