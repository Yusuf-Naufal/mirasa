<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class KartuStok extends Model
{
    protected $table = 'kartu_stok';

    protected $fillable = [
        'id_inventory',
        'tanggal_transaksi',
        'keterangan',
        'nomor_batch',
        'qty',
        'harga',
        'source_type',
        'source_id',
        'saldo_qty',
    ];

    public function source(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'source_type', 'source_id');
    }

    public function Inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_inventory');
    }
}
