<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Pengeluaran extends Model
{
    use LogsActivity;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'id_perusahaan',
        'tanggal_pengeluaran',
        'nama_pengeluaran',
        'is_hpp',
        'kategori',
        'sub_kategori',
        'jumlah_pengeluaran',
        'bukti',
        'keterangan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('pengeluaran');
    }

    protected $casts = [
        'is_hpp' => 'boolean',
        'tanggal_pengeluaran' => 'date',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function Pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_pengeluaran');
    }
}
