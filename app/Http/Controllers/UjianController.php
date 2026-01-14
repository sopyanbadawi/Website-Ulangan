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
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $activeMenu = 'ujian';
        $title = 'Daftar Ujian';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => '']
        ];

        $ujianDraft = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->draft()
            ->when($roleName === 'guru', function ($q) use ($user) {
                $q->whereHas('mataPelajaran.guru', fn ($q) =>
                    $q->where('users.id', $user->id)
                );
            })
            ->latest()
            ->take(4)
            ->get();

        $ujianAktif = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->aktif()
            ->when($roleName === 'guru', function ($q) use ($user) {
                $q->whereHas('mataPelajaran.guru', fn ($q) =>
                    $q->where('users.id', $user->id)
                );
            })
            ->latest()
            ->take(4)
            ->get();

        $ujianSelesai = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->selesai()
            ->when($roleName === 'guru', function ($q) use ($user) {
                $q->whereHas('mataPelajaran.guru', fn ($q) =>
                    $q->where('users.id', $user->id)
                );
            })
            ->latest()
            ->take(4)
            ->get();

        return view($roleName . '.ujian.index', compact(
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
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $activeMenu = 'ujian';
        $title = 'Buat Ujian Baru';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route($roleName . '.ujian.index')],
            ['label' => 'Buat Ujian Baru', 'url' => '']
        ];

        $tahunAjaran = TahunAjaranModel::where('is_active', 1)->first();

        // FILTER & KELOMPOK KELAS 
        $kelasX = KelasModel::where('nama_kelas', 'like', 'X %')
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas']);

        $kelasXI = KelasModel::where('nama_kelas', 'like', 'XI %')
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas']);

        $kelasXII = KelasModel::where('nama_kelas', 'like', 'XII %')
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas']);

        $mapel = MataPelajaranModel::orderBy('nama_mapel')->get(['id', 'nama_mapel']);

        return view('admin.ujian.create', compact(
            'tahunAjaran',
            'kelasX',
            'kelasXI',
            'kelasXII',
            'mapel',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $data = $request->validate([
            // UJIAN
            'nama_ujian' => 'required|string|max:255',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'mulai_ujian' => 'required|date',
            'selesai_ujian' => 'required|date',
            'durasi' => 'required|integer|min:1',

            // KELAS
            'kelas_id' => 'required|array|min:1',
            'kelas_id.*' => 'exists:kelas,id',

            // SOAL
            'soal' => 'required|array|min:1',
            'soal.*.pertanyaan' => 'nullable|string',
            'soal.*.pertanyaan_gambar' => 'nullable|string', // bisa string dari import Excel
            'soal.*.bobot' => 'required|integer|min:1',

            // OPSI
            'soal.*.opsi' => 'required|array|min:2',
            'soal.*.opsi.*.teks' => 'nullable|string',
            'soal.*.opsi.*.gambar' => 'nullable|string', // bisa string dari import Excel

            // JAWABAN BENAR
            'soal.*.correct' => 'required|integer|min:0',

            // IP WHITELIST
            'ip_address' => 'nullable|array',
            'ip_address.*' => [
                'nullable',
                'regex:/^(((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)\.){3}(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d))(\/([0-9]|[1-2][0-9]|3[0-2]))?$/x'
            ],
        ], [
            // UJIAN
            'nama_ujian.required' => 'Nama ujian wajib diisi.',
            'nama_ujian.max' => 'Nama ujian maksimal 255 karakter.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
            'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
            'mata_pelajaran_id.exists' => 'Mata pelajaran tidak valid.',
            'mulai_ujian.required' => 'Waktu mulai ujian wajib diisi.',
            'selesai_ujian.required' => 'Waktu selesai ujian wajib diisi.',
            'durasi.required' => 'Durasi ujian wajib diisi.',
            'durasi.min' => 'Durasi ujian minimal 1 menit.',

            // KELAS
            'kelas_id.required' => 'Minimal satu kelas harus dipilih.',
            'kelas_id.min' => 'Minimal satu kelas harus dipilih.',
            'kelas_id.*.exists' => 'Kelas yang dipilih tidak valid.',

            // SOAL
            'soal.required' => 'Minimal satu soal harus ditambahkan.',
            'soal.min' => 'Minimal satu soal harus ditambahkan.',
            'soal.*.bobot.required' => 'Bobot soal wajib diisi.',
            'soal.*.bobot.min' => 'Bobot soal minimal 1.',
            'soal.*.pertanyaan_gambar.image' => 'Gambar soal harus berupa file gambar.',
            'soal.*.pertanyaan_gambar.mimes' => 'Gambar soal harus berformat JPG atau PNG.',
            'soal.*.pertanyaan_gambar.max' => 'Ukuran gambar soal maksimal 2MB.',

            // OPSI
            'soal.*.opsi.required' => 'Setiap soal harus memiliki minimal 2 opsi.',
            'soal.*.opsi.min' => 'Setiap soal harus memiliki minimal 2 opsi.',
            'soal.*.opsi.*.gambar.image' => 'Gambar opsi harus berupa file gambar.',
            'soal.*.opsi.*.gambar.mimes' => 'Gambar opsi harus berformat JPG atau PNG.',
            'soal.*.opsi.*.gambar.max' => 'Ukuran gambar opsi maksimal 2MB.',

            // JAWABAN BENAR
            'soal.*.correct.required' => 'Jawaban benar wajib ditentukan.',

            // IP
            'ip_address.*.regex' => 'IP harus berupa IP valid atau CIDR (contoh: 192.168.1.1 / 192.168.1.0/24).',
        ]);

        $mulai = Carbon::parse($data['mulai_ujian']);
        $selesai = Carbon::parse($data['selesai_ujian']);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            return back()->withErrors(['selesai_ujian' => 'Jam selesai harus lebih besar dari jam mulai.'])->withInput();
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

            // pastikan folder ada
            if (!is_dir(public_path('soal'))) mkdir(public_path('soal'), 0755, true);
            if (!is_dir(public_path('opsi'))) mkdir(public_path('opsi'), 0755, true);

            foreach ($data['soal'] as $i => $soalData) {

                // ====================
                // PERTANYAAN
                // ====================
                $pertanyaanGambar = null;

                // 1. File upload manual
                if ($request->hasFile("soal.$i.pertanyaan_gambar")) {
                    $file = $request->file("soal.$i.pertanyaan_gambar");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('soal'), $filename);
                    $pertanyaanGambar = 'soal/' . $filename;

                    // 2. Import dari Excel (string path)
                } elseif (!empty($soalData['pertanyaan_gambar'])) {
                    $src = public_path($soalData['pertanyaan_gambar']);
                    if (file_exists($src)) {
                        $filename = time() . '_' . basename($soalData['pertanyaan_gambar']);
                        $dest = public_path('soal/' . $filename);
                        copy($src, $dest);
                        $pertanyaanGambar = 'soal/' . $filename;
                    }
                }

                $soal = SoalModel::create([
                    'ujian_id' => $ujian->id,
                    'pertanyaan' => $soalData['pertanyaan'] ?? '',
                    'pertanyaan_gambar' => $pertanyaanGambar,
                    'bobot' => $soalData['bobot'],
                ]);

                // ====================
                // OPSI
                // ====================
                foreach ($soalData['opsi'] as $idx => $opsiData) {

                    $opsiGambar = null;

                    // 1. File upload manual
                    if ($request->hasFile("soal.$i.opsi.$idx.gambar")) {
                        $file = $request->file("soal.$i.opsi.$idx.gambar");
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('opsi'), $filename);
                        $opsiGambar = 'opsi/' . $filename;

                        // 2. Import dari Excel (string path)
                    } elseif (!empty($opsiData['gambar'])) {
                        $src = public_path($opsiData['gambar']);
                        if (file_exists($src)) {
                            $filename = time() . '_' . basename($opsiData['gambar']);
                            $dest = public_path('opsi/' . $filename);
                            copy($src, $dest);
                            $opsiGambar = 'opsi/' . $filename;
                        }
                    }

                    OpsiJawabanModel::create([
                        'soal_id' => $soal->id,
                        'opsi' => $opsiData['teks'] ?? '',
                        'opsi_gambar' => $opsiGambar,
                        'is_correct' => ($idx == $soalData['correct']),
                    ]);
                }
            }

            // ====================
            // IP WHITELIST
            // ====================
            if (!empty($data['ip_address'])) {
                foreach ($data['ip_address'] as $ip) {
                    $ip = trim($ip);
                    if ($ip) $ujian->ipWhitelist()->create(['ip_address' => $ip]);
                }
            }

            DB::commit();

            return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route($roleName . '.ujian.index')->with('success', 'Ujian berhasil dibuat');
    }






    // public function store(Request $request)
    // {
    //     /* =========================
    //  | VALIDASI
    //  ========================= */
    //     $data = $request->validate([
    //         // UJIAN
    //         'nama_ujian' => 'required|string|max:255',
    //         'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
    //         'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
    //         'mulai_ujian' => 'required|date',
    //         'selesai_ujian' => 'required|date',
    //         'durasi' => 'required|integer|min:1',

    //         // KELAS
    //         'kelas_id' => 'required|array|min:1',
    //         'kelas_id.*' => 'exists:kelas,id',

    //         // SOAL
    //         'soal' => 'required|array|min:1',
    //         'soal.*.pertanyaan' => 'nullable|string',
    //         'soal.*.pertanyaan_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //         'soal.*.bobot' => 'required|integer|min:1',

    //         // OPSI
    //         'soal.*.opsi' => 'required|array|min:2',
    //         'soal.*.opsi.*.teks' => 'nullable|string',
    //         'soal.*.opsi.*.gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

    //         // JAWABAN BENAR
    //         'soal.*.correct' => 'required|integer|min:0',

    //         // IP WHITELIST
    //         'ip_address' => 'nullable|array',
    //         'ip_address.*' => [
    //             'nullable',
    //             'regex:/^(
    //             ((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)\.){3}
    //             (25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)
    //         )
    //         (\/([0-9]|[1-2][0-9]|3[0-2]))?$/x'
    //         ],
    //     ], [
    //         // UJIAN
    //         'nama_ujian.required' => 'Nama ujian wajib diisi.',
    //         'nama_ujian.max' => 'Nama ujian maksimal 255 karakter.',
    //         'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
    //         'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
    //         'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
    //         'mata_pelajaran_id.exists' => 'Mata pelajaran tidak valid.',
    //         'mulai_ujian.required' => 'Waktu mulai ujian wajib diisi.',
    //         'selesai_ujian.required' => 'Waktu selesai ujian wajib diisi.',
    //         'durasi.required' => 'Durasi ujian wajib diisi.',
    //         'durasi.min' => 'Durasi ujian minimal 1 menit.',

    //         // KELAS
    //         'kelas_id.required' => 'Minimal satu kelas harus dipilih.',
    //         'kelas_id.min' => 'Minimal satu kelas harus dipilih.',
    //         'kelas_id.*.exists' => 'Kelas yang dipilih tidak valid.',

    //         // SOAL
    //         'soal.required' => 'Minimal satu soal harus ditambahkan.',
    //         'soal.min' => 'Minimal satu soal harus ditambahkan.',
    //         'soal.*.bobot.required' => 'Bobot soal wajib diisi.',
    //         'soal.*.bobot.min' => 'Bobot soal minimal 1.',
    //         'soal.*.pertanyaan_gambar.image' => 'Gambar soal harus berupa file gambar.',
    //         'soal.*.pertanyaan_gambar.mimes' => 'Gambar soal harus berformat JPG atau PNG.',
    //         'soal.*.pertanyaan_gambar.max' => 'Ukuran gambar soal maksimal 2MB.',

    //         // OPSI
    //         'soal.*.opsi.required' => 'Setiap soal harus memiliki minimal 2 opsi.',
    //         'soal.*.opsi.min' => 'Setiap soal harus memiliki minimal 2 opsi.',
    //         'soal.*.opsi.*.gambar.image' => 'Gambar opsi harus berupa file gambar.',
    //         'soal.*.opsi.*.gambar.mimes' => 'Gambar opsi harus berformat JPG atau PNG.',
    //         'soal.*.opsi.*.gambar.max' => 'Ukuran gambar opsi maksimal 2MB.',

    //         // JAWABAN BENAR
    //         'soal.*.correct.required' => 'Jawaban benar wajib ditentukan.',

    //         // IP
    //         'ip_address.*.regex' => 'IP harus berupa IP valid atau CIDR (contoh: 192.168.1.1 / 192.168.1.0/24).',
    //     ]);

    //     /* =========================
    //  | VALIDASI WAKTU
    //  ========================= */
    //     $mulai = Carbon::parse($data['mulai_ujian']);
    //     $selesai = Carbon::parse($data['selesai_ujian']);

    //     if ($selesai->lessThanOrEqualTo($mulai)) {
    //         return back()
    //             ->withErrors(['selesai_ujian' => 'Jam selesai harus lebih besar dari jam mulai.'])
    //             ->withInput();
    //     }

    //     /* =========================
    //  | SIMPAN DATA
    //  ========================= */
    //     DB::beginTransaction();
    //     try {

    //         $ujian = UjianModel::create([
    //             'created_by' => auth()->id(),
    //             'tahun_ajaran_id' => $data['tahun_ajaran_id'],
    //             'mata_pelajaran_id' => $data['mata_pelajaran_id'],
    //             'nama_ujian' => $data['nama_ujian'],
    //             'mulai_ujian' => $mulai,
    //             'selesai_ujian' => $selesai,
    //             'durasi' => $data['durasi'],
    //             'status' => 'draft',
    //         ]);

    //         $ujian->kelas()->sync($data['kelas_id']);

    //         // SOAL & OPSI
    //         foreach ($data['soal'] as $i => $soalData) {

    //             $pertanyaanGambar = null;
    //             if ($request->hasFile("soal.$i.pertanyaan_gambar")) {
    //                 $file = $request->file("soal.$i.pertanyaan_gambar");
    //                 $filename = time() . '_' . $file->getClientOriginalName();
    //                 $file->move(public_path('soal'), $filename);
    //                 $pertanyaanGambar = 'soal/' . $filename;
    //             }

    //             $soal = SoalModel::create([
    //                 'ujian_id' => $ujian->id,
    //                 'pertanyaan' => $soalData['pertanyaan'] ?? '',
    //                 'pertanyaan_gambar' => $pertanyaanGambar,
    //                 'bobot' => $soalData['bobot'],
    //             ]);

    //             foreach ($soalData['opsi'] as $idx => $opsiData) {

    //                 $opsiGambar = null;
    //                 if ($request->hasFile("soal.$i.opsi.$idx.gambar")) {
    //                     $file = $request->file("soal.$i.opsi.$idx.gambar");
    //                     $filename = time() . '_' . $file->getClientOriginalName();
    //                     $file->move(public_path('opsi'), $filename);
    //                     $opsiGambar = 'opsi/' . $filename;
    //                 }

    //                 OpsiJawabanModel::create([
    //                     'soal_id' => $soal->id,
    //                     'opsi' => $opsiData['teks'] ?? '',
    //                     'opsi_gambar' => $opsiGambar,
    //                     'is_correct' => ($idx == $soalData['correct']),
    //                 ]);
    //             }
    //         }

    //         // IP WHITELIST
    //         if (!empty($data['ip_address'])) {
    //             foreach ($data['ip_address'] as $ip) {
    //                 $ip = trim($ip);
    //                 if ($ip) {
    //                     $ujian->ipWhitelist()->create([
    //                         'ip_address' => $ip
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return redirect()
    //             ->route('admin.ujian.index')
    //             ->with('success', 'Ujian berhasil dibuat.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }



    public function edit($id)
    {
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $activeMenu = 'ujian';
        $title = 'Edit Ujian';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route($roleName . '.ujian.index')],
            ['label' => 'Edit Ujian', 'url' => '']
        ];

        // Load ujian beserta soal dan opsiJawaban
        $ujian = UjianModel::with(['kelas', 'soal.opsiJawaban'])->findOrFail($id);

        $tahunAjaran = TahunAjaranModel::where('is_active', 1)->get();
        $kelas = KelasModel::select('id', 'nama_kelas')->get();
        $mapel = MataPelajaranModel::select('id', 'nama_mapel')->get();

        return view($roleName . '.ujian.edit', compact(
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
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';
        $ujian = UjianModel::with('soal.opsiJawaban', 'ipWhitelist')->findOrFail($id);

        /* =========================
     | VALIDASI (SAMA DENGAN STORE)
     ========================= */
        $data = $request->validate([
            // UJIAN
            'nama_ujian' => 'required|string|max:255',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'mulai_ujian' => 'required|date',
            'selesai_ujian' => 'required|date',
            'durasi' => 'required|integer|min:1',

            // KELAS
            'kelas_id' => 'required|array|min:1',
            'kelas_id.*' => 'exists:kelas,id',

            // SOAL
            'soal' => 'required|array|min:1',
            'soal.*.id' => 'nullable|exists:soal,id',
            'soal.*.pertanyaan' => 'nullable|string',
            'soal.*.pertanyaan_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'soal.*.bobot' => 'required|integer|min:1',

            // OPSI
            'soal.*.opsi' => 'required|array|min:2',
            'soal.*.opsi.*.id' => 'nullable|exists:opsi_jawaban,id',
            'soal.*.opsi.*.teks' => 'nullable|string',
            'soal.*.opsi.*.gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // JAWABAN BENAR
            'soal.*.correct' => 'required|integer|min:0',

            // IP WHITELIST
            'ip_address' => 'nullable|array',
            'ip_address.*' => [
                'nullable',
                'regex:/^(
                ((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)\.){3}
                (25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)
            )
            (\/([0-9]|[1-2][0-9]|3[0-2]))?$/x'
            ],
        ], [

            /* =========================
             | PESAN VALIDASI (INDONESIA)
             ========================= */

            // UJIAN
            'nama_ujian.required' => 'Nama ujian wajib diisi.',
            'nama_ujian.max' => 'Nama ujian maksimal 255 karakter.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
            'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
            'mata_pelajaran_id.exists' => 'Mata pelajaran tidak valid.',
            'mulai_ujian.required' => 'Waktu mulai ujian wajib diisi.',
            'selesai_ujian.required' => 'Waktu selesai ujian wajib diisi.',
            'durasi.required' => 'Durasi ujian wajib diisi.',
            'durasi.min' => 'Durasi ujian minimal 1 menit.',

            // KELAS
            'kelas_id.required' => 'Minimal satu kelas harus dipilih.',
            'kelas_id.min' => 'Minimal satu kelas harus dipilih.',
            'kelas_id.*.exists' => 'Kelas yang dipilih tidak valid.',

            // SOAL
            'soal.required' => 'Minimal satu soal harus ditambahkan.',
            'soal.min' => 'Minimal satu soal harus ditambahkan.',
            'soal.*.bobot.required' => 'Bobot soal wajib diisi.',
            'soal.*.bobot.min' => 'Bobot soal minimal 1.',
            'soal.*.pertanyaan_gambar.image' => 'Gambar soal harus berupa file gambar.',
            'soal.*.pertanyaan_gambar.mimes' => 'Gambar soal harus berformat JPG atau PNG.',
            'soal.*.pertanyaan_gambar.max' => 'Ukuran gambar soal maksimal 2MB.',

            // OPSI
            'soal.*.opsi.required' => 'Setiap soal harus memiliki minimal 2 opsi jawaban.',
            'soal.*.opsi.min' => 'Setiap soal harus memiliki minimal 2 opsi jawaban.',
            'soal.*.opsi.*.gambar.image' => 'Gambar opsi harus berupa file gambar.',
            'soal.*.opsi.*.gambar.mimes' => 'Gambar opsi harus berformat JPG atau PNG.',
            'soal.*.opsi.*.gambar.max' => 'Ukuran gambar opsi maksimal 2MB.',

            // JAWABAN BENAR
            'soal.*.correct.required' => 'Jawaban benar wajib ditentukan.',

            // FILE
            'soal.*.pertanyaan_gambar.image' => 'Gambar pertanyaan harus berupa file gambar.',
            'soal.*.pertanyaan_gambar.mimes' => 'Format gambar pertanyaan harus JPG atau PNG.',
            'soal.*.opsi.*.gambar.image' => 'Gambar opsi harus berupa file gambar.',
            'soal.*.opsi.*.gambar.mimes' => 'Format gambar opsi harus JPG atau PNG.',

            // IP
            'ip_address.*.regex' => 'IP harus berupa IP valid atau CIDR (contoh: 192.168.1.1 atau 192.168.1.0/24).',
        ]);

        /* =========================
     | VALIDASI WAKTU
     ========================= */
        $mulai = Carbon::parse($data['mulai_ujian']);
        $selesai = Carbon::parse($data['selesai_ujian']);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            return back()
                ->withErrors(['selesai_ujian' => 'Jam selesai harus lebih besar dari jam mulai.'])
                ->withInput();
        }

        /* =========================
     | STATUS UJIAN
     ========================= */
        $now = Carbon::now();
        $status = $now->lt($mulai)
            ? 'draft'
            : ($now->between($mulai, $selesai) ? 'aktif' : 'selesai');

        /* =========================
     | UPDATE DATA
     ========================= */
        DB::beginTransaction();
        try {

            // UPDATE UJIAN
            $ujian->update([
                'nama_ujian' => $data['nama_ujian'],
                'tahun_ajaran_id' => $data['tahun_ajaran_id'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'mulai_ujian' => $mulai,
                'selesai_ujian' => $selesai,
                'durasi' => $data['durasi'],
                'status' => $status,
            ]);

            // SYNC KELAS
            $ujian->kelas()->sync($data['kelas_id']);

            /* =========================
         | SOAL & OPSI
         ========================= */
            $existingSoalIds = $ujian->soal->pluck('id')->toArray();
            $requestSoalIds = collect($data['soal'])->pluck('id')->filter()->toArray();

            // HAPUS SOAL YANG DIHAPUS DI FORM
            $deletedSoalIds = array_diff($existingSoalIds, $requestSoalIds);
            SoalModel::whereIn('id', $deletedSoalIds)->delete();

            foreach ($data['soal'] as $i => $soalData) {

                // GAMBAR PERTANYAAN
                $pertanyaanGambar = null;
                if ($request->hasFile("soal.$i.pertanyaan_gambar")) {
                    $file = $request->file("soal.$i.pertanyaan_gambar");
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('soal'), $filename);
                    $pertanyaanGambar = 'soal/' . $filename;
                }

                // UPDATE / CREATE SOAL
                $soal = SoalModel::updateOrCreate(
                    ['id' => $soalData['id'] ?? null],
                    [
                        'ujian_id' => $ujian->id,
                        'pertanyaan' => $soalData['pertanyaan'] ?? '',
                        'pertanyaan_gambar' => $pertanyaanGambar,
                        'bobot' => $soalData['bobot'],
                    ]
                );

                // OPSI
                $existingOpsiIds = $soal->opsiJawaban->pluck('id')->toArray();
                $requestOpsiIds = collect($soalData['opsi'])->pluck('id')->filter()->toArray();

                // HAPUS OPSI YANG DIHAPUS
                $deletedOpsiIds = array_diff($existingOpsiIds, $requestOpsiIds);
                OpsiJawabanModel::whereIn('id', $deletedOpsiIds)->delete();

                foreach ($soalData['opsi'] as $idx => $opsiData) {

                    $opsiGambar = null;
                    if ($request->hasFile("soal.$i.opsi.$idx.gambar")) {
                        $file = $request->file("soal.$i.opsi.$idx.gambar");
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('opsi'), $filename);
                        $opsiGambar = 'opsi/' . $filename;
                    }

                    OpsiJawabanModel::updateOrCreate(
                        ['id' => $opsiData['id'] ?? null],
                        [
                            'soal_id' => $soal->id,
                            'opsi' => $opsiData['teks'] ?? '',
                            'opsi_gambar' => $opsiGambar,
                            'is_correct' => ($idx == $soalData['correct']),
                        ]
                    );
                }
            }

            /* =========================
         | IP WHITELIST
         ========================= */
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

            return redirect()
                ->route('admin.ujian.index')
                ->with('success', 'Ujian berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route($roleName . '.ujian.index')
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
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $activeMenu = 'ujian';
        $title = 'Semua Ujian Aktif';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route($roleName . '.ujian.index')],
            ['label' => 'Ujian Aktif', 'url' => '']
        ];

        // base query
        $query = UjianModel::with('tahunAjaran', 'mataPelajaran')
            ->aktif();

        if ($roleName === 'guru') {
            $query->whereHas('mataPelajaran.guru', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
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

        return view($roleName . '.ujian.all-aktif', compact(
            'dataAktif',
            'tahunAjaranList',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function allDraft(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $activeMenu = 'ujian';
        $title = 'Semua Ujian Draft';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route($roleName . '.ujian.index')],
            ['label' => 'Ujian Draft', 'url' => '']
        ];

        // base query
        $query = UjianModel::with('tahunAjaran', 'mataPelajaran')->where('status', 'draft');

        if ($roleName === 'guru') {
            $query->whereHas('mataPelajaran.guru', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
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

        return view($roleName . '.ujian.all-draft', compact(
            'dataDraft',
            'tahunAjaranList',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function allSelesai(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $activeMenu = 'ujian';
        $title = 'Semua Ujian Selesai';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Daftar Ujian', 'url' => route($roleName . '.ujian.index')],
            ['label' => 'Ujian Selesai', 'url' => '']
        ];

        // base query
        $query = UjianModel::with('tahunAjaran', 'mataPelajaran')->where('status', 'selesai');

        if ($roleName === 'guru') {
            $query->whereHas('mataPelajaran.guru', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
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

        return view($roleName . '.ujian.all-selesai', compact(
            'dataSelesai',
            'tahunAjaranList',
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

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
                ->route($roleName . '.ujian.index')
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

        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
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
            $roleName . '.ujian.monitoring',
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

        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Monitoring Ujian', 'url' => route($roleName . '.ujian.monitoring')],
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
            $roleName . '.ujian.monitoring-detail',
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
            ['label' => 'Dashboard', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Monitoring Ujian', 'url' => route($roleName . '.ujian.monitoring')],
            ['label' => $ujian->nama_ujian, 'url' => route($roleName . '.ujian.monitoring-detail', $ujianId)],
            ['label' => 'Kelas ' . $kelas->nama_kelas, 'url' => '']
        ];

        return view(
            $roleName . '.ujian.monitoring-kelas',
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

    /**
     * Download template Excel
     */
    public function template()
    {
        $path = public_path('template/template-soal.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template tidak ditemukan');
        }

        return response()->download($path, 'template-soal.xlsx');
    }

    /**
     * Import soal dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:5120',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        /**
         * ===============================
         * VALIDASI HEADER TEMPLATE
         * ===============================
         */
        $expectedHeaders = [
            'A1' => 'pertanyaan',
            'B1' => 'gambar_soal',
            'C1' => 'bobot',
            'D1' => 'opsi_a',
            'E1' => 'gambar_a',
            'F1' => 'opsi_b',
            'G1' => 'gambar_b',
            'H1' => 'opsi_c',
            'I1' => 'gambar_c',
            'J1' => 'opsi_d',
            'K1' => 'gambar_d',
            'L1' => 'opsi_e',
            'M1' => 'gambar_e',
            'N1' => 'jawaban',
        ];

        foreach ($expectedHeaders as $cell => $header) {
            $value = strtolower(trim((string) $sheet->getCell($cell)->getValue()));
            if ($value !== $header) {
                return response()->json([
                    'success' => false,
                    'message' => "Template tidak sesuai. Kolom {$cell} harus bernama '{$header}'"
                ], 422);
            }
        }

        /**
         * ===============================
         * AMBIL GAMBAR
         * ===============================
         */
        $drawings = [];
        foreach ($sheet->getDrawingCollection() as $drawing) {
            $drawings[$drawing->getCoordinates()] = $drawing;
        }

        if (!is_dir(public_path('import'))) {
            mkdir(public_path('import'), 0755, true);
        }

        /**
         * ===============================
         * PROSES DATA
         * ===============================
         */
        $hasilSoal = [];
        $row = 2;

        while (trim((string) $sheet->getCell("A$row")->getValue()) !== '') {

            // VALIDASI JAWABAN
            $jawaban = strtoupper(trim((string) $sheet->getCell("N$row")->getValue()));

            if (!in_array($jawaban, ['A', 'B', 'C', 'D', 'E'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Jawaban tidak valid di baris {$row}. Jawaban harus A, B, C, D, atau E."
                ], 422);
            }

            // VALIDASI PERTANYAAN
            if (trim((string) $sheet->getCell("A$row")->getValue()) === '') {
                return response()->json([
                    'success' => false,
                    'message' => "Pertanyaan tidak boleh kosong di baris {$row}"
                ], 422);
            }

            // VALIDASI OPSI
            foreach (['D', 'F', 'H', 'J', 'L'] as $col) {
                if (trim((string) $sheet->getCell("{$col}{$row}")->getValue()) === '') {
                    return response()->json([
                        'success' => false,
                        'message' => "Opsi jawaban tidak boleh kosong di baris {$row}"
                    ], 422);
                }
            }

            $mapJawaban = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4];

            $soal = [
                'pertanyaan' => (string)$sheet->getCell("A$row")->getValue(),
                'pertanyaan_gambar' => $this->extractImage($drawings, "B$row"),
                'bobot' => (int)($sheet->getCell("C$row")->getValue() ?: 1),
                'correct' => $mapJawaban[$jawaban],
                'opsi' => [],
            ];

            $opsiMap = [
                ['D', 'E'], // A
                ['F', 'G'], // B
                ['H', 'I'], // C
                ['J', 'K'], // D
                ['L', 'M'], // E
            ];

            foreach ($opsiMap as [$textCol, $imgCol]) {
                $soal['opsi'][] = [
                    'teks' => (string)$sheet->getCell("$textCol$row")->getValue(),
                    'gambar' => $this->extractImage($drawings, "$imgCol$row"),
                ];
            }

            $hasilSoal[] = $soal;
            $row++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Import berhasil',
            'data' => $hasilSoal
        ]);
    }



    /**
     * Extract gambar dari Excel
     */
    private function extractImage(array $drawings, string $cell): ?string
    {
        if (!isset($drawings[$cell])) return null;

        $drawing = $drawings[$cell];
        $dir = public_path('import');
        $filename = Str::uuid() . '.png';
        $fullPath = $dir . '/' . $filename;

        // Memory drawing
        if ($drawing instanceof MemoryDrawing) {
            ob_start();
            call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
            $imageData = ob_get_clean();
            file_put_contents($fullPath, $imageData);
            return '/import/' . $filename;
        }

        // File drawing
        if ($drawing->getPath()) {
            copy($drawing->getPath(), $fullPath);
            return '/import/' . $filename;
        }

        return null;
    }

    /**
     * Salin gambar ke folder final
     */
    private function saveImportedImage(?string $pathFromExcel, string $type = 'soal'): ?string
    {
        if (!$pathFromExcel) return null;

        $fullPath = public_path($pathFromExcel);
        if (!file_exists($fullPath)) return null;

        $folder = $type === 'soal' ? 'soal' : 'opsi';
        if (!is_dir(public_path($folder))) mkdir(public_path($folder), 0755, true);

        $filename = time() . '_' . basename($pathFromExcel);
        $dest = public_path("$folder/$filename");
        copy($fullPath, $dest);

        return "$folder/$filename"; // path untuk disimpan ke DB
    }


    /**
     * =================================
     * EXTRACT GAMBAR DARI EXCEL
     * =================================
     */
}
