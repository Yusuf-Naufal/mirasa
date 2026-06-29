<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
         // 1. Logika Saringan Tanggal (Sudah Sempurna)
        $tanggalRaw = $row['tanggal'];
        if (is_numeric($tanggalRaw)) {
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d');
        } else {
            $tanggal = date('Y-m-d', strtotime(str_replace('/', '-', $tanggalRaw)));
        }
        // 2. LOGIKA BARU: Saringan Jam Masuk (Mendukung Pecahan Desimal Excel & Teks AM/PM)
        $jamMasuk = null;
        if (!empty($row['jam_masuk'])) {
            if (is_numeric($row['jam_masuk'])) {
                // Jika berupa pecahan desimal bawaan Excel
                $jamMasuk = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['jam_masuk'])->format('H:i:s');
            } else {
                // Jika berupa teks string seperti "07.00 AM"
                $jamMasukRaw = str_replace('.', ':', $row['jam_masuk']);
                $jamMasuk = date('H:i:s', strtotime($jamMasukRaw));
            }
        }
        // 3. LOGIKA BARU: Saringan Jam Pulang (Mendukung Pecahan Desimal Excel & Teks AM/PM)
        $jamPulang = null;
        if (!empty($row['jam_pulang'])) {
            if (is_numeric($row['jam_pulang'])) {
                // Jika berupa pecahan desimal bawaan Excel
                $jamPulang = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['jam_pulang'])->format('H:i:s');
            } else {
                // Jika berupa teks string seperti "04.00 PM"
                $jamPulangRaw = str_replace('.', ':', $row['jam_pulang']);
                $jamPulang = date('H:i:s', strtotime($jamPulangRaw));
            }
        }
        // LOGIKA BARU: Saringan Shift Kerja (Mendukung A, B, dan non shift)
        $shiftRaw = strtolower(trim($row['shift'] ?? '')); // Ubah ke huruf kecil dulu agar seragam
        
        if ($shiftRaw === 'a' || $shiftRaw === 'b') {
            $shift = strtoupper($shiftRaw); // Menjadi 'A' atau 'B' huruf kapital
        } elseif ($shiftRaw === 'non shift') {
            $shift = 'non shift'; // Menjadi 'non shift' huruf kecil sesuai database
        } else {
            $shift = 'non shift'; // Jika kosong atau ketikan lain, defaultkan ke 'non shift' agar aman
        }
        
        // 4. Masukkan Data Bersih ke Database
        return new Attendance([
            'tanggal'       => $tanggal,
            'id_karyawan'   => $row['id_karyawan'],
            'nama_karyawan' => $row['nama_karyawan'],
            'devisi'        => $row['devisi'],
            'kelompok'      => strtolower($row['kelompok']),
            'shift'         => $shift,
            'jam_masuk'     => $jamMasuk,
            'jam_pulang'    => $jamPulang,
            'keterangan'    => strtolower($row['keterangan']),
            'nominal_gaji'  => $row['nominal_gaji'],
        ]);
    }
}
