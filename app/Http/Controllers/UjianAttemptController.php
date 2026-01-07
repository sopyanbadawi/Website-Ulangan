<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianAttemptModel;
use App\Models\UjianModel;
use App\Models\KelasModel;
use App\Models\User;

class UjianAttemptController extends Controller
{
    public function index($ujianId)
    {
        $activeMenu = 'hasil_ujian';
        $title = 'Hasil Ujian';

        $ujian = UjianModel::with(['mataPelajaran', 'tahunAjaran', 'kelas'])
            ->findOrFail($ujianId);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Selesai', 'url' => route('admin.ujian.all_selesai')],
            ['label' => 'Hasil Ujian', 'url' => ''],
        ];

        /**
         * 🔥 HITUNG PESERTA BERDASARKAN ujian_attempt
         * bukan siswa aktif sekarang
         */
        $kelasList = $ujian->kelas()
            ->get()
            ->map(function ($kelas) use ($ujianId) {
                $kelas->peserta_count = UjianAttemptModel::where('ujian_id', $ujianId)
                    ->where('kelas_id', $kelas->id)
                    ->count();

                return $kelas;
            });

        return view('admin.ujian.hasil-ujian', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'ujian',
            'kelasList'
        ));
    }


    public function detail(Request $request, $ujianId, $kelasId)
    {
        $activeMenu = 'hasil_ujian';
        $title = 'Detail Hasil Ujian';

        $ujian = UjianModel::with(['mataPelajaran', 'tahunAjaran'])
            ->findOrFail($ujianId);

        $kelas = KelasModel::findOrFail($kelasId);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Selesai', 'url' => route('admin.ujian.all_selesai')],
            ['label' => 'Hasil Ujian', 'url' => route('admin.ujian.hasil', $ujian->id)],
            ['label' => $kelas->nama_kelas, 'url' => ''],
        ];

        $perPage = $request->get('per_page', 5);
        $search  = $request->get('search');

        /**
         * 🔥 INI INTI PERBAIKANNYA
         * Ambil siswa BERDASARKAN ujian_attempt
         * BUKAN kelas aktif user sekarang
         */
        $siswa = User::query()
            ->select('users.*', 'ua.final_score')
            ->join('ujian_attempt as ua', function ($join) use ($ujianId, $kelasId) {
                $join->on('ua.user_id', '=', 'users.id')
                    ->where('ua.ujian_id', $ujianId)
                    ->where('ua.kelas_id', $kelasId); // ✅ snapshot kelas saat ujian
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.username', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('ua.final_score')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.ujian.siswa-hasil-ujian', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'ujian',
            'kelas',
            'siswa'
        ));
    }
}
