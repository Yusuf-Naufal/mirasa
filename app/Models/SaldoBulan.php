<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoBulan extends Model
{
    protected $table = 'saldo_bulan';

    protected $fillable = [
        'id_inventory',
        'periode_bulan',
        'periode_tahun',
        'stok_awal',
        'nilai_awal',
    ];

    public function Inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_inventory');
    }
}
