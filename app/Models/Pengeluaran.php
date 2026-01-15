<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
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

    protected $casts = [
        'is_hpp' => 'boolean',
        'tanggal_pengeluaran' => 'date',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function PemakaianGas()
    {
        return $this->hasMany(PemakaianGas::class, 'id_pengeluaran');
    }
}
