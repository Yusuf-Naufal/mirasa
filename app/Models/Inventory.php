<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use LogsActivity;

    protected $table = 'inventory';

    protected $fillable = [
        'id_perusahaan',
        'id_barang',
        'stok',
        'minimum_stok',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('inventory');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function Barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang')->withTrashed();
    }

    public function DetailInventory()
    {
        return $this->hasMany(DetailInventory::class, 'id_inventory');
    }

    public function KartuStok()
    {
        return $this->hasMany(KartuStok::class, 'id_inventory');
    }

    public function SaldoBulan()
    {
        return $this->hasMany(SaldoBulan::class, 'id_inventory');
    }

    public function syncTotalStock()
    {
        $this->stok = $this->DetailInventory()->sum('stok');
        $this->save();
    }

    public function recalculateLedger($startDate)
    {
        // 1. Ambil saldo awal bulan tersebut dari tabel SaldoBulan
        $tanggal = Carbon::parse($startDate);

        // Pastikan pencarian saldo awal konsisten dengan Controller
        $saldoBulan = $this->SaldoBulan()
            ->where('periode_bulan', (int)$tanggal->month)
            ->where('periode_tahun', (int)$tanggal->year)
            ->first();

        $runningBalance = $saldoBulan ? $saldoBulan->stok_awal : 0;
        $runningNilai = $saldoBulan ? $saldoBulan->nilai_awal : 0;

        $ledgers = $this->KartuStok()
            ->where('tanggal_transaksi', '>=', $tanggal->startOfMonth())
            ->orderBy('tanggal_transaksi', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($ledgers as $log) {
            $runningBalance += $log->qty;
            $log->update(['saldo_qty' => $runningBalance]);
        }
    }
}
