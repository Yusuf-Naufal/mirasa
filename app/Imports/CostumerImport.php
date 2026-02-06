<?php

namespace App\Imports;

use App\Models\Costumer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CostumerImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    private $rowsImported = 0;

    public function model(array $row)
    {
        $this->rowsImported++;

        return new Costumer([
            'id_perusahaan' => auth()->user()->id_perusahaan,
            'nama_costumer' => $row['nama_costumer'],
            'kode'          => $row['kode'],
        ]);
    }

    public function rules(): array
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        return [
            'nama_costumer' => [
                'required',
                Rule::unique('costumer', 'nama_costumer')->where('id_perusahaan', $id_perusahaan)
            ],
            'kode' => [
                'required',
                Rule::unique('costumer', 'kode')->where('id_perusahaan', $id_perusahaan)
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_costumer.required' => 'Nama costumer wajib diisi.',
            'nama_costumer.unique'   => 'Nama ":input" sudah terdaftar.',
            'kode.required'          => 'Kode costumer wajib diisi.',
            'kode.unique'            => 'Kode ":input" sudah digunakan.',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'nama_costumer' => 'Nama Costumer',
            'kode'          => 'Kode Costumer',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowsImported;
    }
}
