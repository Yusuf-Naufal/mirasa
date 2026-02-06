<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading relasi causer, perusahaan causer, dan subject log
        $query = Activity::with(['causer.perusahaan', 'subject'])->latest();

        // Filter Berdasarkan Perusahaan (Eloquent whereHas)
        if ($request->filled('id_perusahaan')) {
            $query->whereHasMorph('causer', [User::class], function ($q) use ($request) {
                $q->where('id_perusahaan', $request->id_perusahaan);
            });
        }

        // Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search (Case Insensitive PostgreSQL)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'ilike', "%{$search}%")
                    ->orWhere('properties', 'ilike', "%{$search}%")
                    ->orWhereHasMorph('causer', [User::class], function ($q2) use ($search) {
                        $q2->where('name', 'ilike', "%{$search}%")
                            ->orWhereHas('perusahaan', function ($q3) use ($search) {
                                $q3->where('nama_perusahaan', 'ilike', "%{$search}%");
                            });
                    });
            });
        }

        // Filter Aksi & Model
        if ($request->filled('action')) {
            $query->where('description', $request->action);
        }

        if ($request->filled('model')) {
            $query->where('subject_type', $request->model);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Generate daftar model untuk filter
        $models = Activity::select('subject_type')->distinct()->get()->map(function ($item) {
            return [
                'value' => $item->subject_type,
                'label' => class_basename($item->subject_type)
            ];
        });

        $listPerusahaan = Perusahaan::all();

        return view('pages.log.index', compact('logs', 'models', 'listPerusahaan'));
    }
}
