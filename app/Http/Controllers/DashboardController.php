<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\UjianModel;
use App\models\UjianAttemptModel;

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
            'kelas' => UjianModel::totalKelas(),
            'siswa' => UjianModel::totalSiswa(),
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
