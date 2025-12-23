<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proses extends Model
{
    use SoftDeletes;
    
    protected $table = 'proses';

    protected $fillable = [
        'id_perusahaan',
        'nama_proses',
        'kode',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
}
