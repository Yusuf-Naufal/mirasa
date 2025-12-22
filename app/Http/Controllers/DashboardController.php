<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_products' => DB::table('products')->count(),
            'total_orders' => DB::table('orders')->count(),
        ];

        // Get recent users
        $recentUsers = User::latest()->take(5)->get();

        // Get monthly data for chart
        $monthlyData = $this->getMonthlyData();

        return view('dashboard.index', compact('stats', 'recentUsers', 'monthlyData'));
    }

    private function getMonthlyData()
    {
        // Dummy data for chart - replace with actual database queries
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'users' => [120, 150, 180, 220, 260, 300],
            'revenue' => [5000, 6500, 7200, 8100, 9200, 10500],
        ];
    }
}