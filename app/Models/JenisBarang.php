<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisBarang extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang';

    protected $fillable = [
        'nama_jenis',
        'kode',
    ];

    public function Barang()
    {
        return $this->hasMany(Barang::class, 'id_jenis');
    }
}
