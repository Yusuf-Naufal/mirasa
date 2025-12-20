<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis = [
            [
                'nama_jenis' => 'First Grade',
                'kode'       => 'FG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Second Grade',
                'kode'       => 'WIP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Bahan Baku',
                'kode'       => 'BB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Bahan Produksi',
                'kode'       => 'BP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Eceran',
                'kode'       => 'EC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Ganti dengan nama tabel Anda yang sesuai
        DB::table('jenis_barang')->insert($jenis);
    }
}
