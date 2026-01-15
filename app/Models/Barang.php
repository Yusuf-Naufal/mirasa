<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'id_perusahaan',
        'id_jenis',
        'foto',
        'nama_barang',
        'kode',
        'satuan',
        'nilai_konversi',
        'isi_bungkus',
    ];

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
