<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    // // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'tanggal',
        'id_karyawan',
        'nama_karyawan',
        'devisi',
        'kelompok',
        'shift',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
        'nominal_gaji',
    ];
    // Relasi balik ke model User (Karyawan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
