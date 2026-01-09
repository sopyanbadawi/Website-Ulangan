<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\TahunAjaranModel;
use App\Models\KelasModel;
use App\Models\MataPelajaranModel;
use App\Models\SoalModel;
use App\Models\OpsiJawabanModel;
use App\models\User;
use App\models\UjianAttemptModel;
use App\models\UjianActivityLogModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class UjianController extends Controller
{
    public function index()
    {
        $activeMenu = 'ujian';
        $title = 'Daftar Ujian';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => '']
        ];

        $ujianDraft = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->draft()
            ->latest()
            ->take(4)
            ->get();

        $ujianAktif = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->aktif()
            ->latest()
            ->take(4)
            ->get();

        $ujianSelesai = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->selesai()
            ->latest()
            ->take(4)
            ->get();

        return view('admin.ujian.index', compact(
            'ujianDraft',
            'ujianAktif',
            'ujianSelesai',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }


    public function create()
    {
        $activeMenu = 'ujian';
        $title = 'Buat Ujian Baru';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Buat Ujian Baru', 'url' => '']
        ];
        $tahunAjaran = TahunAjaranModel::where('is_active', 1)->get();
        $kelas = KelasModel::all();
        $mapel = MataPelajaranModel::all();
        return view('admin.ujian.create', compact('tahunAjaran', 'kelas', 'mapel', 'activeMenu', 'title', 'breadcrumbs'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'mulai_ujian' => 'required|date',
            'selesai_ujian' => 'required|date',
            'durasi' => 'required|integer|min:1',
            'kelas_id' => 'required|array|min:1',
            'kelas_id.*' => 'exists:kelas,id',

            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'nullable|string',
            'soal.*.pertanyaan_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'soal.*.bobot' => 'required|integer|min:1',

            'soal.*.opsi' => 'required|array|min:2',
            'soal.*.opsi.*.teks' => 'nullable|string',
            'soal.*.opsi.*.gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'soal.*.correct' => 'required|integer|min:0',

            'ip_address' => 'nullable|array',
            'ip_address.*' => 'nullable|string', // Bisa IP tunggal atau CIDR
        ]);

        $mulai = Carbon::parse($data['mulai_ujian']);
        $selesai = Carbon::parse($data['selesai_ujian']);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            return back()->withErrors(['selesai_ujian' => 'Jam selesai harus lebih besar dari jam mulai'])->withInput();
        }

        DB::beginTransaction();
        try {
            $ujian = UjianModel::create([
                'created_by' => auth()->id(),
                'tahun_ajaran_id' => $data['tahun_ajaran_id'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'nama_ujian' => $data['nama_ujian'],
                'mulai_ujian' => $mulai,
                'selesai_ujian' => $selesai,
                'durasi' => $data['durasi'],
                'status' => 'draft',
            ]);

            $ujian->kelas()->sync($data['kelas_id']);

            // Simpan soal dan opsi
            foreach ($data['soal'] as $i => $soalData) {
                $pertanyaanGambar = null;
                if ($request->hasFile("soal.$i.pertanyaan_gambar")) {
                    $file = $request->file("soal.$i.pertanyaan_gambar");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('soal'), $filename);
                    $pertanyaanGambar = 'soal/' . $filename;
                }

                $soal = SoalModel::create([
                    'ujian_id' => $ujian->id,
                    'pertanyaan' => $soalData['pertanyaan'] ?? '', // Jangan NULL
                    'pertanyaan_gambar' => $pertanyaanGambar,
                    'bobot' => $soalData['bobot'],
                ]);

                foreach ($soalData['opsi'] as $idx => $opsiData) {
                    $opsiGambar = null;
                    if ($request->hasFile("soal.$i.opsi.$idx.gambar")) {
                        $file = $request->file("soal.$i.opsi.$idx.gambar");
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('opsi'), $filename);
                        $opsiGambar = 'opsi/' . $filename;
                    }

                    OpsiJawabanModel::create([
                        'soal_id' => $soal->id,
                        'opsi' => $opsiData['teks'] ?? '',
                        'opsi_gambar' => $opsiGambar,
                        'is_correct' => ($idx == $soalData['correct']),
                    ]);
                }
            }

            // Simpan IP whitelist (bisa single atau CIDR)
            if (!empty($data['ip_address'])) {
                foreach ($data['ip_address'] as $ip) {
                    $ip = trim($ip);
                    if ($ip) {
                        $ujian->ipWhitelist()->create(['ip_address' => $ip]);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil dibuat');
    }

    public function edit($id)
    {
        $activeMenu = 'ujian';
        $title = 'Edit Ujian';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Edit Ujian', 'url' => '']
        ];

        // Load ujian beserta soal dan opsiJawaban
        $ujian = UjianModel::with(['kelas', 'soal.opsiJawaban'])->findOrFail($id);

        $tahunAjaran = TahunAjaranModel::where('is_active', 1)->get();
        $kelas = KelasModel::all();
        $mapel = MataPelajaranModel::all();

        return view('admin.ujian.edit', compact(
            'ujian',
            'tahunAjaran',
            'kelas',
            'mapel',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, $id)
    {
        $ujian = UjianModel::with('soal.opsiJawaban', 'ipWhitelist')->findOrFail($id);

        $data = $request->validate([
            'nama_ujian'         => 'required|string|max:255',
            'tahun_ajaran_id'    => 'required|exists:tahun_ajaran,id',
            'mata_pelajaran_id'  => 'required|exists:mata_pelajaran,id',
            'mulai_ujian'        => 'required|date',
            'selesai_ujian'      => 'required|date',
            'durasi'             => 'required|integer|min:1',
            'kelas_id'           => 'required|array|min:1',
            'kelas_id.*'         => 'exists:kelas,id',

            'soal'                          => 'required|array|min:1',
            'soal.*.id'                      => 'nullable|exists:soal,id',
            'soal.*.pertanyaan'             => 'nullable|string',
            'soal.*.pertanyaan_gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'soal.*.bobot'                  => 'required|integer|min:1',

            'soal.*.opsi'                   => 'required|array|min:2',
            'soal.*.opsi.*.id'              => 'nullable|exists:opsi_jawaban,id',
            'soal.*.opsi.*.teks'            => 'nullable|string',
            'soal.*.opsi.*.gambar'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'soal.*.correct'                => 'required|integer|min:0',

            'ip_address' => 'nullable|array',
            'ip_address.*' => 'nullable|string', // Bisa IP tunggal atau CIDR
        ]);

        $mulai   = Carbon::parse($data['mulai_ujian']);
        $selesai = Carbon::parse($data['selesai_ujian']);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            return back()->withErrors([
                'selesai_ujian' => 'Jam selesai harus lebih besar dari jam mulai'
            ])->withInput();
        }

        // Tentukan status ujian
        $now = Carbon::now();
        $status = $now->lt($mulai) ? 'draft' : ($now->between($mulai, $selesai) ? 'aktif' : 'selesai');

        DB::beginTransaction();
        try {
            // Update ujian
            $ujian->update([
                'nama_ujian'        => $data['nama_ujian'],
                'tahun_ajaran_id'   => $data['tahun_ajaran_id'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'mulai_ujian'       => $mulai,
                'selesai_ujian'     => $selesai,
                'durasi'            => $data['durasi'],
                'status'            => $status,
            ]);

            // Sync kelas
            $ujian->kelas()->sync($data['kelas_id']);

            // Proses soal & opsi
            foreach ($data['soal'] as $i => $soalData) {
                $soal = !empty($soalData['id'])
                    ? SoalModel::findOrFail($soalData['id'])
                    : new SoalModel(['ujian_id' => $ujian->id]);

                // Upload gambar pertanyaan
                if ($request->hasFile("soal.$i.pertanyaan_gambar")) {
                    $file = $request->file("soal.$i.pertanyaan_gambar");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('soal'), $filename);
                    $soal->pertanyaan_gambar = 'soal/' . $filename;
                }

                // Jangan biarkan pertanyaan NULL
                $soal->pertanyaan = $soalData['pertanyaan'] ?? $soal->pertanyaan ?? '';
                $soal->bobot = $soalData['bobot'];
                $soal->save();

                // Proses opsi jawaban
                foreach ($soalData['opsi'] as $idx => $opsiData) {
                    $opsi = !empty($opsiData['id'])
                        ? OpsiJawabanModel::findOrFail($opsiData['id'])
                        : new OpsiJawabanModel(['soal_id' => $soal->id]);

                    // Upload gambar opsi
                    if ($request->hasFile("soal.$i.opsi.$idx.gambar")) {
                        $file = $request->file("soal.$i.opsi.$idx.gambar");
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('opsi'), $filename);
                        $opsi->opsi_gambar = 'opsi/' . $filename;
                    }

                    $opsi->opsi = $opsiData['teks'] ?? $opsi->opsi ?? '';
                    $opsi->is_correct = ($idx == $soalData['correct']);
                    $opsi->save();
                }
            }

            // Update IP whitelist (single atau CIDR)
            $ujian->ipWhitelist()->delete();
            if (!empty($data['ip_address'])) {
                foreach ($data['ip_address'] as $ip) {
                    $ip = trim($ip);
                    if ($ip) {
                        $ujian->ipWhitelist()->create(['ip_address' => $ip]);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.ujian.index')
            ->with('success', 'Ujian berhasil diperbarui');
    }


    public function activate($id)
    {
        UjianModel::whereId($id)->update(['status' => 'aktif']);
        return back()->with('success', 'Ujian diaktifkan');
    }

    public function destroySoal(SoalModel $soal)
    {
        DB::beginTransaction();

        try {
            $soal->opsiJawaban()->delete();
            $soal->jawabanSiswa()->delete();

            $soal->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil dihapus'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus soal',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function allAktif(Request $request)
    {
        $activeMenu = 'ujian';
        $title = 'Semua Ujian Aktif';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Aktif', 'url' => '']
        ];

        // base query
        $query = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->aktif();

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_ujian', 'like', "%{$search}%")
                    ->orWhereHas('mataPelajaran', function ($qMapel) use ($search) {
                        $qMapel->where('nama_mapel', 'like', "%{$search}%");
                    });
            });
        }

        // FILTER TAHUN AJARAN (by ID)
        if ($request->filled('tahun')) {
            $query->where('tahun_ajaran_id', $request->tahun);
        }

        // FILTER SEMESTER (via relasi)
        if ($request->filled('semester')) {
            $query->whereHas('tahunAjaran', function ($q) use ($request) {
                $q->semester($request->semester);
            });
        }

        // PAGINATION
        $dataAktif = $query
            ->latest()
            ->paginate($request->get('per_page', 5))
            ->withQueryString();

        // dropdown data
        $tahunAjaranList = TahunAjaranModel::orderBy('tahun', 'desc')->get();

        return view('admin.ujian.all-aktif', compact(
            'dataAktif',
            'tahunAjaranList',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function allDraft(Request $request)
    {
        $activeMenu = 'ujian';
        $title = 'Semua Ujian Draft';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Draft', 'url' => '']
        ];

        // base query
        $query = UjianModel::with('tahunAjaran', 'mataPelajaran')->where('status', 'draft');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_ujian', 'like', "%{$search}%")
                    ->orWhereHas('mataPelajaran', function ($qMapel) use ($search) {
                        $qMapel->where('nama_mapel', 'like', "%{$search}%");
                    });
            });
        }

        // FILTER TAHUN AJARAN (by ID)
        if ($request->filled('tahun')) {
            $query->where('tahun_ajaran_id', $request->tahun);
        }

        // FILTER SEMESTER (via relasi)
        if ($request->filled('semester')) {
            $query->whereHas('tahunAjaran', function ($q) use ($request) {
                $q->semester($request->semester);
            });
        }

        // PAGINATION
        $dataDraft = $query
            ->latest()
            ->paginate($request->get('per_page', 5))
            ->withQueryString();

        // dropdown data
        $tahunAjaranList = TahunAjaranModel::orderBy('tahun', 'desc')->get();

        return view('admin.ujian.all-draft', compact(
            'dataDraft',
            'tahunAjaranList',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function allSelesai(Request $request)
    {
        $activeMenu = 'ujian';
        $title = 'Semua Ujian Selesai';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route('admin.ujian.index')],
            ['label' => 'Ujian Selesai', 'url' => '']
        ];

        // base query
        $query = UjianModel::with('tahunAjaran', 'mataPelajaran')->where('status', 'selesai');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_ujian', 'like', "%{$search}%")
                    ->orWhereHas('mataPelajaran', function ($qMapel) use ($search) {
                        $qMapel->where('nama_mapel', 'like', "%{$search}%");
                    });
            });
        }

        // FILTER TAHUN AJARAN (by ID)
        if ($request->filled('tahun')) {
            $query->where('tahun_ajaran_id', $request->tahun);
        }

        // FILTER SEMESTER (via relasi)
        if ($request->filled('semester')) {
            $query->whereHas('tahunAjaran', function ($q) use ($request) {
                $q->semester($request->semester);
            });
        }

        // PAGINATION
        $dataSelesai = $query
            ->latest()
            ->paginate($request->get('per_page', 5))
            ->withQueryString();

        // dropdown data
        $tahunAjaranList = TahunAjaranModel::orderBy('tahun', 'desc')->get();

        return view('admin.ujian.all-selesai', compact(
            'dataSelesai',
            'tahunAjaranList',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function destroy($id)
    {
        $ujian = UjianModel::with([
            'soal.opsiJawaban',
            'soal.jawabanSiswa',
            'kelas',
            'ipWhitelist'
        ])->findOrFail($id);

        DB::beginTransaction();

        try {
            // Hapus semua soal beserta relasinya
            foreach ($ujian->soal as $soal) {
                $soal->opsiJawaban()->delete();
                $soal->jawabanSiswa()->delete();
                $soal->delete();
            }

            // Hapus relasi kelas (pivot)
            $ujian->kelas()->detach();

            // Hapus IP whitelist
            $ujian->ipWhitelist()->delete();

            // Hapus ujian
            $ujian->delete();

            DB::commit();

            return redirect()
                ->route('admin.ujian.index')
                ->with('success', 'Ujian berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with(
                'error',
                'Gagal menghapus ujian: ' . $e->getMessage()
            );
        }
    }

    public function monitoring(Request $request)
    {
        $activeMenu = 'monitoring';
        $title = 'Monitoring Ujian';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Monitoring Ujian', 'url' => '']
        ];

        $search   = $request->search;
        $perPage  = $request->per_page ?? 5;

        $ujians = UjianModel::sedangBerjalan()
            ->with([
                'mataPelajaran',
                'tahunAjaran',
                'kelas'
            ])

            // 🔍 SEARCH (nama ujian)
            ->when($search, function ($q) use ($search) {
                $q->where('nama_ujian', 'like', '%' . $search . '%');
            })

            ->orderBy('mulai_ujian', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'admin.ujian.monitoring',
            compact(
                'activeMenu',
                'title',
                'breadcrumbs',
                'ujians'
            )
        );
    }

    public function monitoringDetail(Request $request, $ujianId)
    {
        $activeMenu = 'monitoring';
        $title = 'Monitoring Kelas Ujian';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Monitoring Ujian', 'url' => route('admin.ujian.monitoring')],
            ['label' => 'Detail Ujian', 'url' => '']
        ];

        $ujian = UjianModel::with([
            'mataPelajaran',
            'tahunAjaran'
        ])->findOrFail($ujianId);

        $search  = $request->search;
        $perPage = $request->per_page ?? 5;

        $kelas = $ujian->kelas()
            ->withCount('siswa')

            // SEARCH KELAS
            ->when($search, function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%$search%");
            })

            ->orderBy('nama_kelas')
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'admin.ujian.monitoring-detail',
            compact(
                'activeMenu',
                'title',
                'breadcrumbs',
                'ujian',
                'kelas'
            )
        );
    }


    public function monitoringKelas(Request $request, $ujianId, $kelasId)
    {
        $activeMenu = 'monitoring';
        $title = 'Monitoring Peserta Ujian';

        $ujian = UjianModel::findOrFail($ujianId);
        $kelas = KelasModel::findOrFail($kelasId);

        $search  = $request->search;
        $perPage = $request->per_page ?? 5;

        $siswa = User::where('kelas_id', $kelasId)
            ->whereHas('role', fn($q) => $q->where('name', 'siswa'))

            // SEARCH
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
            })

            ->with([
                'ujianAttempts' => function ($q) use ($ujianId) {
                    $q->where('ujian_id', $ujianId);
                }
            ])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Monitoring Ujian', 'url' => route('admin.ujian.monitoring')],
            ['label' => $ujian->nama_ujian, 'url' => route('admin.ujian.monitoring-detail', $ujianId)],
            ['label' => 'Kelas ' . $kelas->nama_kelas, 'url' => '']
        ];

        return view(
            'admin.ujian.monitoring-kelas',
            compact(
                'activeMenu',
                'title',
                'breadcrumbs',
                'ujian',
                'kelas',
                'siswa'
            )
        );
    }


    public function unlockAttempt(
        int $ujianId,
        int $kelasId,
        int $attemptId
    ): RedirectResponse {

        $attempt = UjianAttemptModel::where('id', $attemptId)
            ->where('ujian_id', $ujianId)
            ->where('kelas_id', $kelasId)
            ->firstOrFail();

        // Pastikan hanya unlock jika status LOCK
        if ($attempt->status !== 'lock') {
            return back()->with('error', 'Attempt tidak dalam kondisi terkunci.');
        }

        $attempt->update([
            'status' => 'ongoing',
        ]);

        // Log aktivitas UNLOCK
        UjianActivityLogModel::log(
            $attempt->id,
            'ATTEMPT_UNLOCKED',
            'Attempt dibuka kembali oleh admin'
        );

        return back()->with('success', 'Attempt berhasil dibuka kembali.');
    }

    public function monitoringActivity($ujianId, $kelasId, $attemptId)
    {
        $activeMenu = 'monitoring';
        $title = 'Log Aktivitas';

        $ujian = UjianModel::findOrFail($ujianId);
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Monitoring Ujian', 'url' => route('admin.ujian.monitoring')],
            ['label' => $ujian->nama_ujian, 'url' => route('admin.ujian.monitoring-detail', $ujianId)],
            ['label' => 'Log Aktivitas', 'url' => '']
        ];
        $attempt = UjianAttemptModel::with('user')->findOrFail($attemptId);

        $logs = UjianActivityLogModel::byAttempt($attemptId)
            ->latestFirst()
            ->get();

        return view(
            'admin.ujian.monitoring-activity',
            compact('attempt', 'logs', 'activeMenu', 'title', 'breadcrumbs')
        );
    }
}
