<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Produksi extends Model
{
    use LogsActivity;

    protected $table = 'produksi';

    protected $fillable = [
        'id_perusahaan',
        'tanggal_produksi',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName('produksi');
    }

    public function syncTotals()
    {
        // 1. Bersihkan cache relasi agar data terbaru terbaca
        $this->unsetRelation('BarangKeluar');

        // Ambil rekap dari tabel BarangKeluar
        $rekap = $this->BarangKeluar()
            ->whereHas('DetailInventory.Inventory.Barang', function ($q) {
                $q->where('jenis', 'Utama')
                    ->whereHas('jenisBarang', function ($sq) {
                        $sq->where('kode', 'BB');
                    });
            })
            ->join('detail_inventory', 'barang_keluar.id_detail_inventory', '=', 'detail_inventory.id')
            ->join('inventory', 'detail_inventory.id_inventory', '=', 'inventory.id')
            ->select('inventory.id_barang')
            // Gunakan jumlah_keluar dan total_harga dari tabel barang_keluar
            ->selectRaw('SUM(barang_keluar.jumlah_keluar) as total_qty')
            ->selectRaw('SUM(barang_keluar.total_harga) as total_nilai')
            ->groupBy('inventory.id_barang')
            ->get();

        $activeBarangIds = $rekap->pluck('id_barang')->toArray();

        // 2. Hapus detail_produksi yang barangnya sudah tidak ada di list pengeluaran ini
        $this->DetailProduksi()->whereNotIn('id_barang', $activeBarangIds)->delete();

        // 3. Update atau buat baru data ringkasan di DetailProduksi
        foreach ($rekap as $data) {
            $this->DetailProduksi()->updateOrCreate(
                ['id_barang' => $data->id_barang],
                [
                    'total_bb_diterima' => $data->total_qty,
                    'total_harga_bb'    => $data->total_nilai,
                ]
            );
        }
    }

    public function getListBahanBakuAttribute()
    {
        return $this->DetailInventory->filter(function ($item) {
            return optional(optional($item->Inventory->Barang)->jenisBarang)->kode === 'BB';
        });
    }

    public function getListBarangPenolongMasukAttribute()
    {
        return $this->DetailInventory->filter(function ($item) {
            return optional(optional($item->Inventory->Barang)->jenisBarang)->kode === 'BP';
        });
    }

    public function DetailProduksi()
    {
        return $this->hasMany(DetailProduksi::class, 'id_produksi');
    }

    public function DetailInventory()
    {
        return $this->hasMany(DetailInventory::class, 'id_produksi');
    }

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan')->withTrashed();
    }

    public function BarangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_produksi');
    }
}
