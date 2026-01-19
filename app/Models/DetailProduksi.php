<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    protected $table = 'detail_produksi';

    protected $fillable = [
        'id_produksi',
        'id_barang',
        'total_bb_diterima',
        'total_harga_bb',
        'total_kupas',
        'total_a',
        'total_s',
        'total_j',
    ];

    public function Produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi');
    }

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang')->withTrashed();
    }
}
