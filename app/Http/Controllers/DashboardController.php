<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\UjianAttemptModel;
use App\Models\KelasModel;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $activeMenu = 'dashboard';

        $kelasId        = $request->kelas_id;
        $semester       = $request->semester;
        $tahunAjaranId  = $request->tahun_ajaran_id;

        // Statistik ujian
        $ujian = [
            'total' => UjianModel::totalUjian(),
            'aktif' => UjianModel::totalUjianAktif(),
            'kelas' => KelasModel::totalKelas(),
            'siswa' => User::totalSiswa(),
        ];

        $avgSemester = UjianAttemptModel::avgSemesterFair();

        // Distribusi nilai (chart)
        $distribusiNilai = UjianAttemptModel::distribusiNilai(
            $kelasId,
            $semester,
            $tahunAjaranId
        );

        return view('admin.dashboard', compact(
            'activeMenu',
            'ujian',
            'distribusiNilai',
            'kelasId',
            'semester',
            'tahunAjaranId',
            'avgSemester'
        ));
    }

    public function siswa()
    {
        $activeMenu = 'dashboard';
        return view('siswa.dashboard', compact('activeMenu'));
    }
}
