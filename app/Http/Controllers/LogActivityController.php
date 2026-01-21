<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])->latest();

        // Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search (Tidak Sensitif - Case Insensitive untuk PostgreSQL)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'ilike', "%{$search}%")
                    ->orWhereHas('causer', function ($q2) use ($search) {
                        $q2->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhere('properties', 'ilike', "%{$search}%");
            });
        }

        // Filter Aksi & Model (Eksisting)
        if ($request->filled('action')) $query->where('description', $request->action);
        if ($request->filled('model')) $query->where('subject_type', $request->model);

        $logs = $query->paginate(20)->withQueryString();

        $models = Activity::select('subject_type')->distinct()->get()->map(function ($item) {
            return [
                'value' => $item->subject_type,
                'label' => class_basename($item->subject_type)
            ];
        });

        return view('pages.log.index', compact('logs', 'models'));
    }
}
