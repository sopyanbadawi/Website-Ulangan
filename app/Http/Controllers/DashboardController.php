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

    public function guru()
    {
        $activeMenu = 'dashboard';
        return view('guru.dashboard', compact('activeMenu'));
    }
    
    public function siswa(){
        $activeMenu ='dashboard';
        return view('siswa.dashboard',compact('activeMenu'));
    }
}
