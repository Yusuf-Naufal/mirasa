<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\JenisBarang;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BarangImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    private $rowsImported = 0;

    public function model(array $row)
    {
        $kategoriInput = strtoupper($row['kategori_sistem'] ?? '');
        $jenisBarang = JenisBarang::where('kode', $kategoriInput)->first();

        if (!$jenisBarang) {
            return null;
        }

        $isBB = ($kategoriInput === 'BB');

        $this->rowsImported++;

        return new Barang([
            'id_perusahaan'  => auth()->user()->id_perusahaan,
            'id_jenis'       => $jenisBarang->id,
            'nama_barang'    => $row['nama_barang'],
            'kode'           => $row['kode_barang'],
            'satuan'         => strtoupper($row['satuan']),
            // Aturan 1: Hanya BB yang memiliki sub-kategori, kapital huruf depan saja, jika kosong set null
            'jenis'          => ($isBB && !empty($row['sub_kategori_bb']))
                ? ucfirst(strtolower($row['sub_kategori_bb']))
                : null,
            // Aturan 2: nilai_konversi dan isi_bungkus jika kosong set null
            'nilai_konversi' => !empty($row['nilai_konversi']) ? $row['nilai_konversi'] : null,
            'isi_bungkus'    => !empty($row['isi_bungkus']) ? $row['isi_bungkus'] : null,
            'foto'           => null,
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris
     */
    public function rules(): array
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        return [
            'nama_barang' => [
                'required',
                // Unik hanya untuk perusahaan yang sedang login
                Rule::unique('barang', 'nama_barang')->where('id_perusahaan', $id_perusahaan)
            ],
            'kode_barang' => [
                'required',
                Rule::unique('barang', 'kode')->where('id_perusahaan', $id_perusahaan)
            ],
            'satuan' => 'required',
            'kategori_sistem' => 'required|in:FG,WIP,EC,BB,BP',
        ];
    }

    /**
     * Pesan error yang informatif dalam Bahasa Indonesia
     */
    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'nama_barang.unique'   => 'Nama barang ":input" sudah ada di database perusahaan Anda.',
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang ":input" sudah digunakan.',
            'satuan.required'      => 'Satuan wajib diisi (contoh: KG).',
            'kategori_sistem.required' => 'Kategori wajib dipilih.',
            'kategori_sistem.in'       => 'Kategori ":input" tidak valid. Gunakan: FG, WIP, EC, BB, atau BP.',
        ];
    }

    /**
     * Nama atribut untuk pesan error
     */
    public function customValidationAttributes()
    {
        return [
            'nama_barang'     => 'Nama Barang',
            'kode_barang'     => 'Kode Barang',
            'satuan'          => 'Satuan',
            'kategori_sistem' => 'Kategori Sistem',
        ];
    }

    /**
     * Mendapatkan total baris yang berhasil diimpor
     */
    public function getRowCount(): int
    {
        return $this->rowsImported;
    }
}
