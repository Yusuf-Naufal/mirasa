<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proses extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'proses';

    protected $fillable = [
        'id_perusahaan',
        'nama_proses',
        'kode',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('proses');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function BarangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_proses');
    }
}
