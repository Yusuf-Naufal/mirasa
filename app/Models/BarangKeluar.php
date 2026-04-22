<?php

namespace App\Models;

use App\Models\KartuStok;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BarangKeluar extends Model
{
    use LogsActivity;

    protected $table = 'barang_keluar';

    public $keterangan_transaksi = null;

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
        'jumlah_dikonversi',
        'status',
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
        // 1. SEBELUM SIMPAN (CREATING)
        static::creating(function ($keluar) {
            $detail = $keluar->DetailInventory;
            if ($detail) {
                // Hitung Harga Netto FIFO
                $hargaNetto = $detail->jumlah_diterima > 0
                    ? ($detail->total_harga / $detail->jumlah_diterima)
                    : $detail->harga;

                $keluar->harga = $hargaNetto;
                $keluar->total_harga = $keluar->jumlah_keluar * $hargaNetto;
            }
        });

        // 2. SETELAH BERHASIL DISIMPAN (CREATED)
        static::created(function ($keluar) {
            $detail = $keluar->DetailInventory;
            if ($detail) {
                // Kurangi stok di batch/detail terkait
                $detail->stok -= $keluar->jumlah_keluar;
                $detail->save();

                // Catat ke Kartu Stok
                $keteranganDinamis = $keluar->keterangan_transaksi ?? 'Barang Keluar';

                KartuStok::create([
                    'id_inventory'      => $detail->id_inventory,
                    'tanggal_transaksi' => $keluar->tanggal_keluar,
                    'keterangan'        => $keteranganDinamis,
                    'nomor_batch'       => $detail->nomor_batch,
                    'qty'               => -$keluar->jumlah_keluar, // Negatif karena barang keluar
                    'harga'             => $keluar->total_harga,
                    'source_type'       => get_class($keluar),
                    'source_id'         => $keluar->id,
                    'saldo_qty'         => 0,
                ]);

                // Sinkronisasi Stok Global & Hitung Ulang Saldo Ledger (Efek Domino)
                $detail->Inventory->syncTotalStock();
                $detail->Inventory->recalculateLedger($keluar->tanggal_keluar);
            }
        });

        // 3. SAAT PROSES UPDATE (UPDATING)
        static::updating(function ($keluar) {
            // Jika jumlah keluar diubah, sesuaikan stok di detail_inventory
            if ($keluar->isDirty('jumlah_keluar')) {
                $oldQty = $keluar->getOriginal('jumlah_keluar');
                $newQty = $keluar->jumlah_keluar;
                $selisih = $newQty - $oldQty;

                $detail = $keluar->DetailInventory;
                if ($detail) {
                    $detail->stok -= $selisih;
                    $detail->save();
                }

                // Update total harga otomatis
                $keluar->total_harga = $newQty * $keluar->harga;
            }
        });

        // 4. SETELAH BERHASIL DIUPDATE (UPDATED)
        static::updated(function ($keluar) {
            // Update baris terkait di Kartu Stok
            $kartu = KartuStok::where('source_type', get_class($keluar))
                ->where('source_id', $keluar->id)
                ->first();

            if ($kartu) {
                $kartu->update([
                    'qty'               => -$keluar->jumlah_keluar,
                    'harga'             => $keluar->total_harga,
                    'tanggal_transaksi' => $keluar->tanggal_keluar,
                ]);
            }

            // Tentukan tanggal mulai hitung ulang (gunakan tanggal terkecil antara tgl lama vs tgl baru)
            $tanggalMulai = $keluar->isDirty('tanggal_keluar')
                ? min($keluar->getOriginal('tanggal_keluar'), $keluar->tanggal_keluar)
                : $keluar->tanggal_keluar;

            $keluar->DetailInventory->Inventory->syncTotalStock();
            $keluar->DetailInventory->Inventory->recalculateLedger($tanggalMulai);
        });

        // 5. SETELAH DATA DIHAPUS (DELETED)
        static::deleted(function ($keluar) {
            $detail = $keluar->DetailInventory;
            if ($detail) {
                // Kembalikan stok ke batch/detail terkait
                $detail->stok += $keluar->jumlah_keluar;
                $detail->save();

                // Hapus catatan di Kartu Stok
                KartuStok::where('source_type', get_class($keluar))
                    ->where('source_id', $keluar->id)
                    ->delete();

                // Sinkronisasi & Hitung Ulang Saldo
                $detail->Inventory->syncTotalStock();
                $detail->Inventory->recalculateLedger($keluar->tanggal_keluar);
            }
        });
    }
}
