<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Perusahaan;
use App\Models\BarangKeluar;
use App\Models\Costumer;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Contoh data ringkasan untuk Super Admin
        $stats = [
            'total_perusahaan'  => Perusahaan::whereNull('deleted_at')->count(),
            'total_user'        => User::count(),
            'total_barang' => Barang::whereHas('jenisBarang', function ($query) {
                $query->whereIn('kode', ['FG', 'WIP', 'EC']);
            })->count(),
            'total_costumer'      => Costumer::whereNull('deleted_at')->count(),
        ];

        // Mengambil katalog barang per perusahaan
        $katalog_perusahaan = Perusahaan::with(['barang' => function ($query) {
            $query->whereHas('jenisBarang', function ($q) {
                $q->whereIn('kode', ['FG', 'WIP', 'EC']);
            })->with('jenisBarang');
        }])
            ->orderBy('id', 'asc') 
            ->get();

        // Data aktivitas terbaru lintas perusahaan
        $recent_activities = BarangKeluar::with(['Produksi.DetailInventory.Inventory.Barang', 'Perusahaan'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.dashboard.superadmin', compact('stats', 'recent_activities', 'katalog_perusahaan'));
    }
}
