<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisBarang extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'jenis_barang';

    protected $fillable = [
        'nama_jenis',
        'kode',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('jenis_barang');
    }

    public function Barang()
    {
        return $this->hasMany(Barang::class, 'id_jenis');
    }
}
