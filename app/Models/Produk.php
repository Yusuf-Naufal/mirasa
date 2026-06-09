<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'rasa',
        'kategori',
        'slug',
        'deskripsi',
        'foto',
        'is_aktif',
        'is_unggulan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($p) => $p->slug = Str::slug($p->nama_produk));
        static::updating(fn($p) => $p->isDirty('nama_produk') ? $p->slug = Str::slug($p->nama_produk) : null);
    }
}
