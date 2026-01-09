<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\UjianAttemptModel;
use App\Models\UjianActivityLogModel;
use App\Models\JawabanSiswaModel;
use App\Models\User;
use App\Models\UjianIpWhitelist;
use Illuminate\Support\Facades\Auth;

class SiswaUjianController extends Controller
{
    public function index()
    {
        $activeMenu = 'ujian';
        $title = 'Daftar Ujian';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('siswa.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => '']
        ];

        $user = auth()->user();

        $ujians = UjianModel::sedangBerjalan()
            ->whereHas('kelas', fn($q) => $q->where('kelas_id', $user->kelas_id))
            ->with([
                'attempts' => fn($q) => $q->where('user_id', $user->id)
            ])
            ->latest()
            ->get();

        return view('siswa.ujian.index', compact(
            'ujians',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function start($id)
    {
        $user = auth()->user();
        $ip   = request()->ip();

        $ujian = UjianModel::findOrFail($id);

        if (!UjianIpWhitelist::isAllowed($ujian->id, $ip)) {
            abort(403, 'IP tidak diizinkan');
        }

        $attempt = UjianAttemptModel::firstOrCreate(
            [
                'ujian_id' => $ujian->id,
                'user_id'  => $user->id,
            ],
            [
                'kelas_id'    => $user->kelas_id,
                'nisn'        => $user->username,
                'ip_address'  => $ip,
                'final_score' => 0,
                'status'      => 'ongoing',
            ]
        );

        if ($attempt->isFinished()) {
            abort(403, 'Ujian sudah selesai');
        }

        return redirect()->route('siswa.ujian.kerjakan', $attempt->id);
    }


    public function kerjakan(UjianAttemptModel $attempt)
    {
        // Pastikan user punya akses
        abort_if($attempt->user_id !== auth()->id(), 403);

        // ❌ Jangan lanjutkan jika ujian sudah selesai
        if ($attempt->isFinished()) {
            abort(403, 'Ujian sudah selesai');
        }

        $currentIp = request()->ip();

        // 🔒 Cek IP whitelist setiap request
        if (!UjianIpWhitelist::isAllowed($attempt->ujian_id, $currentIp)) {

            // Catat aktivitas IP berubah atau tidak valid
            UjianActivityLogModel::ipChanged(
                $attempt->id,
                $attempt->ip_address,
                $currentIp
            );

            // Lock attempt
            $attempt->lockAttempt();

            // Abort supaya user tidak bisa melanjutkan
            abort(403, 'IP Anda tidak diizinkan untuk ujian ini');
        }

        // 🔄 Update IP terakhir untuk attempt ongoing
        if ($attempt->status === 'ongoing' && $attempt->ip_address !== $currentIp) {
            $attempt->update(['ip_address' => $currentIp]);
        }

        // Load soal beserta opsi dan jawaban siswa
        $attempt->load([
            'ujian.soal.opsiJawaban',
            'jawabanSiswa'
        ]);

        return view('siswa.ujian.kerjakan', [
            'attempt'    => $attempt,
            'activeMenu' => 'ujian',
            'isLocked'   => $attempt->isLocked(),
        ]);
    }


    public function jawab(Request $request, UjianAttemptModel $attempt)
    {
        abort_if($attempt->user_id !== auth()->id(), 403);
        abort_if(!$attempt->isOngoing(), 403);

        $request->validate([
            'soal_id' => 'required|exists:soal,id',
            'opsi_id' => 'nullable|exists:opsi_jawaban,id',
        ]);

        $jawaban = JawabanSiswaModel::updateOrCreate(
            [
                'ujian_attempt_id' => $attempt->id,
                'soal_id'          => $request->soal_id,
            ]
        );

        $jawaban->submitAnswer($request->opsi_id);

        return response()->json(['status' => 'saved']);
    }

    public function lock(UjianAttemptModel $attempt)
    {
        abort_if($attempt->user_id !== auth()->id(), 403);

        if ($attempt->isOngoing()) {
            $attempt->lockAttempt();
            UjianActivityLogModel::tabSwitch($attempt->id);
        }

        return response()->json(['locked' => true]);
    }

    public function submit(UjianAttemptModel $attempt)
    {
        abort_if($attempt->user_id !== auth()->id(), 403);

        if ($attempt->isFinished()) {
            return redirect()->route('siswa.ujian.index');
        }

        $totalSkor = $attempt->jawabanSiswa()->sum('skor');

        $attempt->finish($totalSkor);

        UjianActivityLogModel::finished($attempt->id);

        return redirect()
            ->route('siswa.ujian.index')
            ->with('success', 'Ujian selesai');
    }



    public function riwayat(Request $request)
    {
        $activeMenu = 'riwayat_ujian';
        $title = 'Riwayat Ujian Saya';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('siswa.dashboard')],
            ['label' => 'Riwayat Ujian', 'url' => '']
        ];

        // 🔐 ID SISWA DARI AUTH
        $siswaId = auth()->id();

        $siswa = User::with('role')
            ->whereHas('role', fn($q) => $q->where('name', 'siswa'))
            ->findOrFail($siswaId);

        $perPage = $request->get('per_page', 5);
        $search  = $request->get('search');

        /**
         * 🔥 RIWAYAT UJIAN BERDASARKAN ujian_attempt
         * BUKAN kelas aktif
         */
        $riwayatUjian = UjianAttemptModel::with([
            'ujian.mataPelajaran',
            'ujian.tahunAjaran',
            'kelas',
        ])
            ->byUser($siswaId)
            ->selesai()
            ->when($search, function ($q) use ($search) {
                $q->whereHas('ujian', function ($ujian) use ($search) {
                    $ujian->where('nama_ujian', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('siswa.riwayat-ujian.index', compact(
            'activeMenu',
            'title',
            'siswa',
            'breadcrumbs',
            'riwayatUjian'
        ));
    }
}
