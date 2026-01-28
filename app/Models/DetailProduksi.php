<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DetailProduksi extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('detail_produksi');
    }

    public function Produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi');
    }

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang')->withTrashed();
    }
}
