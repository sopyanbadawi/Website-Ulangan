<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\UjianAttemptModel;
use App\Models\KelasModel;
use App\Models\KelasHistoryModel;
use App\Models\TahunAjaranModel;
use App\Models\MataPelajaranModel;
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

    public function siswa(Request $request)
    {
        $activeMenu = 'dashboard';
        $user = auth()->user();

        /* =========================
     | DATA DASAR SISWA
     ========================= */

        // Tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaranModel::where('is_active', true)->first();

        // Kelas aktif siswa (berdasarkan tahun ajaran aktif)
        $kelasAktif = null;
        if ($tahunAjaranAktif) {
            $kelasAktif = KelasHistoryModel::where('user_id', $user->id)
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->with('kelas')
                ->first();
        }

        // Semua kelas historis siswa (penting biar pindah kelas tetap kehitung)
        $kelasIds = KelasHistoryModel::where('user_id', $user->id)
            ->pluck('kelas_id');

        /* =========================
     | STATISTIK DASHBOARD
     ========================= */

        $totalUjianAktif = UjianModel::aktif()
            ->sedangBerjalan()
            ->whereHas('kelas', function ($q) use ($kelasIds) {
                $q->whereIn('kelas.id', $kelasIds);
            })
            ->count();

        $totalUjianDikerjakan = UjianAttemptModel::where('user_id', $user->id)->count();

        $totalUjianSelesai = UjianAttemptModel::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        /* =========================
     | FILTER (REQUEST)
     ========================= */

        $filterTahunAjaran = $request->tahun_ajaran_id;
        $filterSemester    = $request->semester;
        $filterMapel       = $request->mata_pelajaran_id;

        /* =========================
     | QUERY NILAI (FILTERABLE)
     ========================= */

        $nilaiQuery = UjianAttemptModel::query()
            ->join('ujian', 'ujian.id', '=', 'ujian_attempt.ujian_id')
            ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'ujian.tahun_ajaran_id')
            ->join('mata_pelajaran', 'mata_pelajaran.id', '=', 'ujian.mata_pelajaran_id')
            ->where('ujian_attempt.user_id', $user->id)
            ->where('ujian_attempt.status', 'selesai')
            ->whereNotNull('ujian_attempt.final_score')
            ->where('ujian.status', 'selesai');

        // ✅ Filter Tahun Ajaran
        if ($filterTahunAjaran) {
            $nilaiQuery->where('ujian.tahun_ajaran_id', $filterTahunAjaran);
        }

        // ✅ Filter Semester
        if ($filterSemester) {
            $nilaiQuery->where('tahun_ajaran.semester', $filterSemester);
        }

        // ✅ Filter Mata Pelajaran
        if ($filterMapel) {
            $nilaiQuery->where('ujian.mata_pelajaran_id', $filterMapel);
        }

        /* =========================
     | GRAFIK RATA-RATA NILAI
     ========================= */

        $avgNilaiPerSemester = $nilaiQuery
            ->groupBy(
                'ujian.tahun_ajaran_id',
                'tahun_ajaran.tahun',
                'tahun_ajaran.semester'
            )
            ->orderBy('tahun_ajaran.tahun')
            ->selectRaw('
            tahun_ajaran.tahun,
            tahun_ajaran.semester,
            ROUND(AVG(ujian_attempt.final_score), 2) as avg_score
        ')
            ->get();

        $chartLabels = $avgNilaiPerSemester->map(
            fn($item) =>
            $item->tahun . ' ' . ucfirst($item->semester)
        );

        $chartData = $avgNilaiPerSemester->pluck('avg_score');

        /* =========================
     | DATA UNTUK DROPDOWN FILTER
     ========================= */

        $listTahunAjaran = TahunAjaranModel::orderBy('tahun', 'desc')->get();

        $listMataPelajaran = MataPelajaranModel::whereHas('ujian.attempts', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        /* =========================
     | VIEW
     ========================= */

        return view('siswa.dashboard', compact(
            'activeMenu',
            'user',
            'tahunAjaranAktif',
            'kelasAktif',
            'totalUjianAktif',
            'totalUjianDikerjakan',
            'totalUjianSelesai',
            'chartLabels',
            'chartData',
            'listTahunAjaran',
            'listMataPelajaran'
        ));
    }
}
