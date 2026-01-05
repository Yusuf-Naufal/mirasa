<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'id_perusahaan',
        'id_produksi',
        'id_costumer',
        'id_tujuan',
        'id_proses',
        'id_detail_inventory',
        'tanggal_keluar',
        'jenis_keluar',
        'jumlah_keluar',
        'harga',
        'total_harga',
        'no_jalan',
        'no_faktur',
    ];

    public function Proses()
    {
        return $this->belongsTo(Proses::class, 'id_proses');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function Costumer()
    {
        return $this->belongsTo(Costumer::class, 'id_costumer');
    }

    public function Produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi');
    }

    public function DetailInventory()
    {
        return $this->belongsTo(DetailInventory::class, 'id_detail_inventory');
    }

    protected static function booted()
    {
        static::created(function ($keluar) {
            // Logika pemotongan stok otomatis tetap sama
            $detail = $keluar->DetailInventory;
            if ($detail) {
                $detail->stok -= $keluar->jumlah_keluar;
                $detail->save();
            }
        });

        static::deleted(function ($keluar) {
            // Kembalikan stok jika catatan dihapus
            $detail = $keluar->DetailInventory;
            if ($detail) {
                $detail->stok += $keluar->jumlah_keluar;
                $detail->save();
            }
        });
    }
}
