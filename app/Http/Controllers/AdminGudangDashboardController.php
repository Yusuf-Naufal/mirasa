<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminGudangDashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.admingudang');
    }
}
