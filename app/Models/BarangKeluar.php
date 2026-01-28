<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BarangKeluar extends Model
{
    use LogsActivity;

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
        'keterangan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('barang_keluar');
    }

    public function Proses()
    {
        return $this->belongsTo(Proses::class, 'id_proses')->withTrashed();
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function Costumer()
    {
        return $this->belongsTo(Costumer::class, 'id_costumer')->withTrashed();
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
        static::creating(function ($keluar) {
            $detail = $keluar->DetailInventory;
            if ($detail) {
                // Formula: total_harga / jumlah_diterima
                $hargaNetto = $detail->jumlah_diterima > 0
                    ? ($detail->total_harga / $detail->jumlah_diterima)
                    : $detail->harga;

                $keluar->harga = $hargaNetto;
                $keluar->total_harga = $keluar->jumlah_keluar * $hargaNetto;
            }
        });

        static::created(function ($keluar) {
            $detail = $keluar->DetailInventory;
            if ($detail) {
                $detail->stok -= $keluar->jumlah_keluar;
                $detail->save();
            }
        });

        static::deleted(function ($keluar) {
            $detail = $keluar->DetailInventory;
            if ($detail) {
                $detail->stok += $keluar->jumlah_keluar;
                $detail->save();
            }
        });
    }
}
