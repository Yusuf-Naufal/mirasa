<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriPemakaian extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    protected $table = 'kategori_pemakaian';

    protected $fillable = [
        'id_perusahaan',
        'nama_kategori',
        'satuan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('kategori_pemakaian');
    }

    public function Pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_kategori');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }


}
