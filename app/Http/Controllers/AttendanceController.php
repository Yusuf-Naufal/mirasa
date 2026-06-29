<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Imports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{
    // 1. Tampilkan Form & Tabel Hasil Input
        public function index(Request $request)
    {
        // Buat kueri dasar model
        $query = Attendance::query();

        // 1. Filter Tanggal (Menampilkan data sesuai input tanggal)
        if ($request->filled('filter_tanggal')) {
            $query->whereDate('tanggal', $request->filter_tanggal);
        }

        // 2. Filter Kelompok (Langsung / Tidak Langsung)
        if ($request->filled('filter_kelompok')) {
            $query->where('kelompok', $request->filter_kelompok);
        }

        // 3. Filter Shift (A / B)
        if ($request->filled('filter_shift')) {
            $query->where('shift', $request->filter_shift);
        }

        // 4. Filter Keterangan (Hadir / Izin / Sakit)
        if ($request->filled('filter_keterangan')) {
            $query->where('keterangan', $request->filter_keterangan);
        }

        // Ambil hasil saringan data database
        $attendances = $query->latest()->get();

        return view('attendance.index', compact('attendances'));
    }

    // 2. Aksi Create (Simpan Data Baru)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_karyawan' => 'required',
            'nama_karyawan' => 'required',
            'devisi' => 'required',
            'kelompok' => 'required|in:langsung,tidak langsung',
            'shift' => 'required|in:non shift,A,B',
            'keterangan' => 'required',
            'nominal_gaji' => 'required|numeric',
        ]);
        $attendance = Attendance::create($request->all());

        // FORMAT LOG DISESUAIKAN DENGAN STRUKTUR TABEL SPATIE ACTIVITYLOG
        Activity::create([
            'log_name'     => 'attendance',
            'description'  => 'CREATED',
            'subject_type' => 'App\Models\Attendance',
            'subject_id'   => $attendance->id,
            'causer_type'  => 'App\Models\User',
            'causer_id'    => Auth::id(),
            'properties'   => [
                'nama_karyawan' => $request->nama_karyawan,
                'id_karyawan'   => $request->id_karyawan,
                'shift'         => $request->shift,
                'nominal_gaji'  => $request->nominal_gaji,
            ]
        ]);
        return redirect()->back()->with('success', 'Data absensi berhasil ditambahkan!');
    }

    // 3. Aksi Update (Simpan Perubahan Data)
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'tanggal'      => 'required|date',
            'id_karyawan'  => 'required',
            'nama_karyawan'=> 'required',
            'devisi'       => 'required',
            'kelompok'     => 'required|in:langsung,tidak langsung',
            'shift'        => 'required|in:non shift,A,B',
            'keterangan'   => 'required',
            'nominal_gaji' => 'required|numeric',
        ]);

        // CATAT LOG PERUBAHAN DATA SEBELUM DATABASE DIUPDATE
        Activity::create([
            'log_name'     => 'attendance',
            'description'  => 'UPDATED',
            'subject_type' => 'App\Models\Attendance',
            'subject_id'   => $attendance->id,
            'causer_type'  => 'App\Models\User',
            'causer_id'    => Auth::id(),
            'properties'   => [
                'old' => [
                    'nama_karyawan' => $attendance->nama_karyawan,
                    'nominal_gaji'  => $attendance->nominal_gaji,
                ],
                'attributes' => [
                    'nama_karyawan' => $request->nama_karyawan,
                    'nominal_gaji'  => $request->nominal_gaji,
                ]
            ]
        ]);

        $attendance->update($request->all());

        return redirect()->back()->with('success', 'Data absensi berhasil diperbarui!');
    }

    // 4. Aksi Delete (Hapus Data)
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        // CATAT LOG TERLEBIH DAHULU SEBELUM DATA DIHAPUS DARI DATABASE
        Activity::create([
            'log_name'     => 'attendance',
            'description'  => 'DELETED',
            'subject_type' => 'App\Models\Attendance',
            'subject_id'   => $attendance->id,
            'causer_type'  => 'App\Models\User',
            'causer_id'    => Auth::id(),
            'properties'   => [
                'nama_karyawan' => $attendance->nama_karyawan,
                'id_karyawan'   => $attendance->id_karyawan,
            ]
        ]);

        // BARU JALANKAN PERINTAH HAPUS SETELAH NAMA BERHASIL DICATAT LOG
        $attendance->delete();

        return redirect()->back()->with('success', 'Data absensi berhasil dihapus!');
    }

    // 5. Aksi Print Report (Cetak Laporan)
    public function printReport(Request $request)
    {
        $query = Attendance::query();

        // Terapkan filter yang sama dengan halaman utama agar hasil cetak singkron
        if ($request->filled('filter_tanggal')) {
            $query->whereDate('tanggal', $request->filter_tanggal);
        }
        if ($request->filled('filter_kelompok')) {
            $query->where('kelompok', $request->filter_kelompok);
        }
        if ($request->filled('filter_shift')) {
            $query->where('shift', $request->filter_shift);
        }
        if ($request->filled('filter_keterangan')) {
            $query->where('keterangan', $request->filter_keterangan);
        }

        $attendances = $query->latest()->get();

        return view('attendance.print', compact('attendances', 'request'));
    }
    
    // 6. Terapkan import Excel untuk mengunggah data absensi dari file Excel
    public function import(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv|max:2048',]);

        Excel::import(new AttendanceImport, $request->file('file_excel'));

        Activity::create([
            'log_name'     => 'attendance',
            'description'  => 'IMPORTED',
            'subject_type' => 'App\Models\Attendance',
            'causer_type'  => 'App\Models\User',
            'causer_id'    => Auth::id(),
            'properties'   => [
                'keterangan' => 'Melakukan import massal data absensi via file Excel.'
            ]
        ]);

        return redirect()->back()->with('success', 'Seluruh data absensi dari Excel berhasil dimasukkan!');
    }
    public function show($id)
    {
        return redirect()->route('attendance.index');
    }
}
