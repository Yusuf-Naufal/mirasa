<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory; // Sesuaikan dengan model stok Anda
use App\Models\Barang;

class LandingMirasaController extends Controller
{
    public function index()
    {
        // Ambil produk unggulan (contoh: Finished Goods dari PT Mirasa)
        // Sesuaikan 'id_perusahaan' dengan ID PT Mirasa di database Anda
        $products = Barang::where('id_perusahaan', 1) 
                    ->whereHas('JenisBarang', function($query) {
                        $query->whereIn('nama_jenis', ['Finished Goods', 'Eceran']);
                    })
                    ->limit(4)
                    ->get();

        return view('pages.landing.mirasa', compact('products'));
    }
}