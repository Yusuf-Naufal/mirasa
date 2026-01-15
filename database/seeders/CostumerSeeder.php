<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CostumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $costumers = [
            ['id_perusahaan' => 1, 'kode' => 'IFM-SM', 'nama_costumer' => 'INDOFOOD SEMARANG'],
            ['id_perusahaan' => 1, 'kode' => 'IFM-CKL', 'nama_costumer' => 'INDOFOOD CIKOKOL'],
            ['id_perusahaan' => 1, 'kode' => 'IFM-CKP', 'nama_costumer' => 'INDOFOOD CIKUPA'],
            ['id_perusahaan' => 1, 'kode' => 'IDM-JG', 'nama_costumer' => 'INDOMARCO JOGJA'],
            ['id_perusahaan' => 1, 'kode' => 'IDM-KL', 'nama_costumer' => 'INDOMARCO KLATEN'],
            ['id_perusahaan' => 1, 'kode' => 'IDM-BY', 'nama_costumer' => 'INDOMARCO BOYOLALI'],
            ['id_perusahaan' => 1, 'kode' => 'ALF-CLP', 'nama_costumer' => 'SUMBER ALFARIA TRIJAYA CILACAP'],
            ['id_perusahaan' => 1, 'kode' => 'ALF-KLT', 'nama_costumer' => 'SUMBER ALFARIA TRIJAYA KLATEN'],
            ['id_perusahaan' => 1, 'kode' => 'MID-BYL', 'nama_costumer' => 'MIDI UTAMA BOYOLALI'],
            ['id_perusahaan' => 1, 'kode' => 'HPS-SMG', 'nama_costumer' => 'HOSANA PULUNG SARI'],
            ['id_perusahaan' => 1, 'kode' => 'TD-SMG', 'nama_costumer' => 'TIGA DEWI'],
            ['id_perusahaan' => 1, 'kode' => 'CV-PPJ', 'nama_costumer' => 'PUTRA PANGGIL JAYA'],
            ['id_perusahaan' => 1, 'kode' => 'CV-AJ', 'nama_costumer' => 'ANANTA JAYA'],
            ['id_perusahaan' => 1, 'kode' => 'EJ-SLO', 'nama_costumer' => 'EKAJAYA'],
            ['id_perusahaan' => 1, 'kode' => 'PL-SLO', 'nama_costumer' => 'PELANGI'],
        ];

        foreach ($costumers as $c) {
            DB::table('costumer')->insert(array_merge($c, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
