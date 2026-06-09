<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Berita;
use App\Models\Produk;
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
        $berita = collect();

        if ($perusahaan) {
            // Ambil Produk Terbaru
            $products = Produk::whereRaw('is_aktif = true')
                ->latest()
                ->limit(6)
                ->get();

            // Ambil Berita Terbaru untuk bagian News Section
            $berita = Berita::whereRaw('status_publish = true')
                ->latest()
                ->limit(3)
                ->get();
        }

        $viewPath = Str::contains(strtolower($host), 'bahtera') ? 'pages.landing.bahtera' : 'pages.landing.mirasa';

        // Kirim variabel 'berita' ke view
        return view($viewPath, compact('products', 'perusahaan', 'berita'));
    }

    public function katalog(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter'); // 'unggulan' atau 'semua'

        $products = Produk::query()
            ->whereRaw('is_aktif = true')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_produk', 'ilike', '%' . $search . '%')
                        ->orWhere('deskripsi', 'ilike', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query, $filter) {
                if ($filter === 'unggulan') {
                    return $query->whereRaw('is_unggulan = true');
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.landing.katalog', compact('products'));
    }

    public function allBerita()
    {
        $berita = Berita::whereRaw('status_publish = true')
            ->latest()
            ->paginate(12);

        return view('pages.landing.berita', compact('berita'));
    }

    public function showProduk($slug)
    {
        $product = Produk::where('slug', $slug)
            ->whereRaw('is_aktif = true')
            ->firstOrFail();

        // Mengambil produk terkait (opsional, untuk rekomendasi di bawah)
        $relatedProducts = Produk::whereRaw('is_aktif = true')
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('pages.landing.detail-produk', compact('product', 'relatedProducts'));
    }

    public function showBerita($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();

        // Tambahkan jumlah view setiap berita dibuka
        $berita->increment('jumlah_view');

        $relatedBerita = Berita::where('slug', '!=', $slug)
            ->whereRaw('status_publish = true')
            ->latest()
            ->take(3)
            ->get();

        return view('pages.landing.detail-berita', compact('berita', 'relatedBerita'));
    }
}
