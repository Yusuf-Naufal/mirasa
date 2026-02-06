<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Barang extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'barang';

    protected $fillable = [
        'id_perusahaan',
        'id_jenis',
        'foto',
        'nama_barang',
        'kode',
        'satuan',
        'jenis',
        'nilai_konversi',
        'isi_bungkus',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('barang');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function JenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'id_jenis');
    }

    public function Inventory()
    {
        return $this->hasMany(Inventory::class, 'id_barang');
    }

    public function DetailProduksi()
    {
        return $this->hasMany(DetailProduksi::class, 'id_barang');
    }
}
