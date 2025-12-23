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
    ];

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
    
    public function Suplier()
    {
        return $this->hasMany(Supplier::class, 'id_perusahaan');
    }
}
