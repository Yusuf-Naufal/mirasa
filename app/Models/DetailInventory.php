<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailInventory extends Model
{
    protected $table = 'detail_inventory';

    protected $fillable = [
        'id_inventory',
        'id_supplier',
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
    ];

    public function Inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_inventory');
    }

    protected static function booted()
    {
        // Setiap kali ada update di detail, otomatis hitung ulang stok di parent (Inventory)
        static::updated(function ($detail) {
            $detail->inventory->syncTotalStock();
        });
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
