<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Pemakaian extends Model
{
    use LogsActivity;

    protected $table = 'pemakaian';

    protected $fillable = [
        'id_perusahaan',
        'id_pengeluaran',
        'id_kategori',
        'tanggal_pemakaian',
        'jumlah',
        'harga',
        'total_harga',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('pemakaian');
    }

    public function KategoriPemakaian()
    {
        return $this->belongsTo(KategoriPemakaian::class, 'id_kategori')->withTrashed();
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function Pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran');
    }
}
