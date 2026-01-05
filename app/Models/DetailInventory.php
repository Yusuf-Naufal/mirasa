<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailInventory extends Model
{
    protected $table = 'detail_inventory';

    protected $fillable = [
        'id_inventory',
        'id_supplier',
        'id_produksi',
        'nomor_batch',
        'tanggal_masuk',
        'tanggal_exp',
        'stok',
        'jumlah_diterima',
        'jumlah_rusak',
        'harga',
        'total_harga',
        'kondisi_barang',
        'kondisi_kendaraan',
        'tempat_penyimpanan',
        'status',
    ];

    public function Inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_inventory');
    }

    public function Produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi');
    }

    protected static function booted()
    {
        static::saved(function ($detail) {
            if ($detail->Inventory) {
                $detail->Inventory->syncTotalStock();
            }
        });

        static::deleted(function ($detail) {
            if ($detail->Inventory) {
                $detail->Inventory->syncTotalStock();
            }
            // Jika dihapus, tetap sync produksi terkait
            if ($detail->id_produksi && $detail->Produksi) {
                $detail->Produksi->syncTotals();
            }
        });
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function BarangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_detail_inventory');
    }
}
