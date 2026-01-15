<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perusahaan = [
            [
                'nama_perusahaan' => 'PT Mirasa Food Industry',
                'jenis_perusahaan' => 'Pusat',
                'alamat' => 'Jalan Munggur No. 2 Ambartawang, Japun Satu, Paremono, Kec. Mungkid, Kabupaten Magelang, Jawa Tengah 56512',
                'kontak' => '6287880809279',
                'kota' => 'Magelang',
                'logo' => null,
            ],
            [
                'nama_perusahaan' => 'CV Bahtera Mandiri Ber...',
                'jenis_perusahaan' => 'Anak Perusahaan',
                'alamat' => 'JL. Munggur No.1, RT.01/RW.05, Kadipuro, Mungkid, Kec. Mungkid, Kabupaten Magelang, Jawa Tengah 56512',
                'kontak' => '6285124666420',
                'kota' => 'Magelang',
                'logo' => null,
            ],
            [
                'nama_perusahaan' => 'PT Mirasa Food Industry',
                'jenis_perusahaan' => 'Cabang',
                'alamat' => 'Jl. Kosambi Baru No.35, RT.5/RW.1, Duri Kosambi, Kecamatan Cengkareng, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11750',
                'kontak' => '6287880809279',
                'kota' => 'Jakarta',
                'logo' => null,
            ],
        ];

        foreach ($perusahaan as $p) {
            DB::table('perusahaan')->insert(array_merge($p, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
