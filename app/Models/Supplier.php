<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    
    protected $table = 'supplier';

    protected $fillable = [
        'id_perusahaan',
        'jenis_supplier',
        'nama_supplier',
        'kode',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function DetailInventory()
    {
        return $this->hasMany(DetailInventory::class, 'id_supplier');
    }
}
