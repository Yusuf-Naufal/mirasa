<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar_utama',
        'kategori',
        'penulis',
        'status_publish',
        'tanggal_publish',
        'jumlah_view',
    ];

    protected $casts = [
        'status_publish' => 'boolean',
        'tanggal_publish' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($berita) {
            $berita->slug = Str::slug($berita->judul) . '-' . Str::random(5);
            $berita->jumlah_view = 0;
        });

        // Update slug otomatis jika judul diubah
        static::updating(function ($berita) {
            if ($berita->isDirty('judul')) {
                $berita->slug = Str::slug($berita->judul) . '-' . Str::random(5);
            }
        });
    }
}
