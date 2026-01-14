<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\UjianAttemptModel;

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

    public function guru(Request $request)
    {
        $activeMenu = 'dashboard';
        $guruId = auth()->id();
        $kelasId        = $request->kelas_id;
        $semester       = $request->semester;
        $tahunAjaranId  = $request->tahun_ajaran_id;

        // Statistik ujian
        $ujian = [
            'kelas' => UjianModel::totalKelasForGuru(),
            'siswa' => UjianModel::totalSiswaForGuru(),
            'siswaSubmit' => UjianModel::totalSiswaSudahSubmit(),
        ];

        $ujian['siswaBlmSubmit'] = $ujian['siswa'] - $ujian['siswaSubmit'];

        $avgSemester = UjianAttemptModel::avgSemesterFair();

        // Distribusi nilai (chart)
        $distribusiNilai = UjianAttemptModel::distribusiNilai(
            $kelasId,
            $semester,
            $tahunAjaranId
        );

        return view('guru.dashboard', compact(
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
