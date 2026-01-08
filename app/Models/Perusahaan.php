<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'jenis_perusahaan',
        'alamat',
        'kontak',
        'kota',
        'logo',
    ];

    public function Inventory()
    {
        return $this->hasMany(Inventory::class, 'id_perusahaan');
    }

    public function Barang()
    {
        return $this->hasMany(Barang::class, 'id_perusahaan');
    }

    public function Costumer()
    {
        return $this->hasMany(Costumer::class, 'id_perusahaan');
    }

    public function User()
    {
        return $this->hasMany(User::class, 'id_perusahaan');
    }

    public function Proses()
    {
        return $this->hasMany(Proses::class, 'id_perusahaan');
    }

    public function BarangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_perusahaan');
    }
    
    public function Supplier()
    {
        return $this->hasMany(Supplier::class, 'id_perusahaan');
    }

    public function Produksi()
    {
        return $this->hasMany(Produksi::class, 'id_perusahaan');
    }

    public function BahanBaku()
    {
        return $this->hasMany(BahanBaku::class, 'id_perusahaan');
    }
}
