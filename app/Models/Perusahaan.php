<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perusahaan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'jenis_perusahaan',
        'alamat',
        'domain',
        'kontak',
        'kota',
        'logo',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('perusahaan');
    }

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

    public function Pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_perusahaan');
    }

    public function Pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_perusahaan');
    }

    public function KategoriPemakaian()
    {
        return $this->hasMany(KategoriPemakaian::class, 'id_perusahaan');
    }
}
