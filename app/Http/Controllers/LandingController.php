<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Perusahaan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $host = request()->getHost();
        $searchKeyword = Str::contains(strtolower($host), 'bahtera') ? 'Bahtera' : 'Mirasa';
        $perusahaan = Perusahaan::where('nama_perusahaan', 'LIKE', '%' . $searchKeyword . '%')->first();

        $products = collect();

        if ($perusahaan) {
            $codes = ['FG'];
            foreach ($codes as $code) {
                // Ambil maksimal 2 barang per kode untuk pemerataan
                $itemPerType = Barang::where('id_perusahaan', $perusahaan->id)
                    ->whereHas('JenisBarang', function ($query) use ($code) {
                        $query->where('kode', $code);
                    })
                    ->with('JenisBarang')
                    ->limit(2)
                    ->get();

                $products = $products->concat($itemPerType);
            }

            if ($products->count() < 6) {
                $excludeIds = $products->pluck('id');
                $additional = Barang::where('id_perusahaan', $perusahaan->id)
                    ->whereNotIn('id', $excludeIds)
                    ->whereHas('JenisBarang', function ($query) use ($codes) {
                        $query->whereIn('kode', $codes);
                    })
                    ->limit(6 - $products->count())
                    ->get();

                $products = $products->concat($additional);
            }
        }

        $viewPath = Str::contains(strtolower($host), 'bahtera') ? 'pages.landing.bahtera' : 'pages.landing.mirasa';
        return view($viewPath, compact('products', 'perusahaan'));
    }
}
