<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemakaianGas extends Model
{
    protected $table = 'pemakaian_gas';

    protected $fillable = [
        'id_perusahaan',
        'id_supplier',
        'id_pengeluaran',
        'tanggal_pemakaian',
        'jumlah_gas',
        'harga',
        'total_harga',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier')->withTrashed();
    }

    public function Pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran');
    }
}
