<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'supplier';

    protected $fillable = [
        'id_perusahaan',
        'jenis_supplier',
        'nama_supplier',
        'kode',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('supplier');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function DetailInventory()
    {
        return $this->hasMany(DetailInventory::class, 'id_supplier');
    }

    public function Pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_supplier');
    }
}
