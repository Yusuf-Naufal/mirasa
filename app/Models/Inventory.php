<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'id_perusahaan',
        'id_barang',
        'stok',
        'minimum_stok',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang')->withTrashed();
    }

    public function DetailInventory()
    {
        return $this->hasMany(DetailInventory::class, 'id_inventory');
    }

    public function syncTotalStock()
    {
        $this->stok = $this->DetailInventory()->sum('stok');
        $this->save();
    }
}
