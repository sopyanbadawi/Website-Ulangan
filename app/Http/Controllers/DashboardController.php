<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        $activeMenu = 'dashboard';
        return view('admin.dashboard', compact('activeMenu'));
    }
}
