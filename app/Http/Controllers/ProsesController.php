<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Nama class HARUS sesuai dengan nama file
class ProsesController extends Controller 
{
    public function index()
    {
        // Data simulasi sesuai gambar Excel Anda
        $daftarProses = [
            ['kode' => 'PAC-XXMX', 'proses' => 'PACKING XX MX'],
            ['kode' => 'PAC-MAN', 'proses' => 'PACKING MANUAL'],
            ['kode' => 'PR-IFM', 'proses' => 'IFM'],
            ['kode' => 'PR-MAN', 'proses' => 'MANUAL'],
            ['kode' => 'TGJ-2', 'proses' => 'TRANSFER GUDANG JKT'],
            ['kode' => 'TGB-3', 'proses' => 'TRANSFER GUDANG TIMUR'],
            ['kode' => 'PAC-EC', 'proses' => 'PACKING ECERAN'],
        ];

        return view('pages.proses.index', compact('daftarProses'));
    }

    public function create()
    {
        return view('pages.proses.create');
    }

    public function store(Request $request)
    {
       
        return redirect()->route('proses.index')->with('success', 'Data berhasil disimpan!');
    }
}