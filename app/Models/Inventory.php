<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use LogsActivity;

    protected $table = 'inventory';

    protected $fillable = [
        'id_perusahaan',
        'id_barang',
        'stok',
        'minimum_stok',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('inventory');
    }

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
