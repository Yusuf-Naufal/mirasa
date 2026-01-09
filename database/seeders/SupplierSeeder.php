<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'PT. SMART', 'kode' => 'SUP-SMR'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'PT. SALIM IVOMAS', 'kode' => 'SUP-SIM'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'PT. INDO ASIA', 'kode' => 'SUP-IAM'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'PT. MITRA ADHIKARYA', 'kode' => 'SUP-MAP'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'CV. FILLA DJAYA', 'kode' => 'SUP-FIL'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'PT. PURI NUSA EKA PERS...', 'kode' => 'SUP-APP'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Barang', 'nama_supplier' => 'PT. SRIWAHANA ADITYAKA...', 'kode' => 'SUP-SWA'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'BAGUS', 'kode' => 'SUP-BGS'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'ALDI', 'kode' => 'SUP-ALD'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'ASEP', 'kode' => 'SUP-ASP'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'ANGGRI', 'kode' => 'SUP-ANG'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'SUYITNO', 'kode' => 'SUP-SYT'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'ZAINI', 'kode' => 'SUP-ZAI'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'PPL', 'kode' => 'SUP-PPL'],
            ['id_perusahaan' => 1, 'jenis_supplier' => 'Bahan Baku', 'nama_supplier' => 'ARIFIN', 'kode' => 'SUP-ARF'],
        ];

        foreach ($suppliers as $data) {
            DB::table('suppliers')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
