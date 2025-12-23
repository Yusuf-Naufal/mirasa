<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Costumer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'costumer';

    protected $fillable = [
        'id_perusahaan',
        'nama_costumer',
        'kode',
    ];

    public function Perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
}
