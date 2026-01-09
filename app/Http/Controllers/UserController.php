<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleModel;
use App\Models\KelasModel;
use App\Models\KelasHistoryModel;
use App\Models\TahunAjaranModel;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'user';
        $title = 'Manajemen Pengguna';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => null],
            ['label' => 'Pengguna', 'url' => null],
        ];

        $query = User::with('role', 'kelas');

        // search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // filter
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // sorting by role superadmin
        $query->orderByRaw("
            CASE
                WHEN role_id = 1 THEN 1  -- Superadmin
                WHEN role_id = 2 THEN 2  -- Guru
                WHEN role_id = 3 THEN 3  -- Siswa
                ELSE 4
            END
        ")->orderByDesc('created_at');

        // pagination
        $perPage = $request->get('per_page', 5);
        $user = $query->paginate($perPage)->withQueryString();

        // role list
        $roles = RoleModel::select('id', 'name')->get();

        return view('admin.user.index', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'user',
            'roles'
        ));
    }

    public function create()
    {
        $activeMenu = 'user';
        $title = 'Tambah Pengguna';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => route('admin.user.index')],
            ['label' => 'Tambah Pengguna', 'url' => null],
        ];

        $role  = RoleModel::select('id', 'name')->get();
        $kelas = KelasModel::select('id', 'nama_kelas')->get();


        return view('admin.user.create', compact(
            'activeMenu',
            'title',
            'role',
            'kelas',
            'breadcrumbs'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'      => 'required|string|max:255',
                'username'  => 'required|string|max:255|unique:users,username',
                'email'     => 'nullable|email|max:255|unique:users,email',
                'password'  => 'required|string|min:8|confirmed',
                'role_id'   => 'required|exists:roles,id',
                'kelas_id'  => 'nullable|exists:kelas,id',
            ], [
                'name.required'     => 'Nama lengkap wajib diisi.',
                'username.required' => 'Username wajib diisi.',
                'username.unique'   => 'Username sudah digunakan.',
                'email.email'       => 'Format email tidak valid.',
                'email.unique'      => 'Email sudah digunakan.',
                'password.required' => 'Password wajib diisi.',
                'password.min'      => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'role_id.required'  => 'Role wajib dipilih.',
            ]);

            DB::transaction(function () use ($validatedData) {

                $user = User::create([
                    'name'     => $validatedData['name'],
                    'username' => $validatedData['username'],
                    'email'    => $validatedData['email'] ?? null,
                    'password' => bcrypt($validatedData['password']),
                    'role_id'  => $validatedData['role_id'],
                    'kelas_id' => $validatedData['kelas_id'] ?? null,
                ]);

                // 🔥 SIMPAN KE KELAS_HISTORY (KHUSUS SISWA)
                if (
                    $user->role->name === 'siswa' &&
                    !empty($validatedData['kelas_id'])
                ) {
                    $tahunAjaran = TahunAjaranModel::where('is_active', true)->first();

                    if (!$tahunAjaran) {
                        throw new \Exception('Tahun ajaran aktif belum diset.');
                    }

                    KelasHistoryModel::create([
                        'user_id'         => $user->id,
                        'kelas_id'        => $validatedData['kelas_id'],
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ]);
                }
            });

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function edit($id)
    {
        $activeMenu = 'user';
        $title = 'Edit Pengguna';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => route('admin.user.index')],
            ['label' => 'Edit Pengguna', 'url' => null],
        ];

        $user  = User::findOrFail($id);
        $role  = RoleModel::select('id', 'name')->get();
        $kelas = KelasModel::select('id', 'nama_kelas')->get();

        return view('admin.user.edit', compact(
            'activeMenu',
            'title',
            'user',
            'role',
            'kelas',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'name'      => 'required|string|max:255',
                'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
                'email'     => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'password'  => 'nullable|string|min:8|confirmed',
                'role_id'   => 'required|exists:roles,id',
                'kelas_id'  => 'nullable|exists:kelas,id',
            ]);

            DB::transaction(function () use ($validatedData, $user) {

                $oldKelasId = $user->kelas_id;

                // UPDATE USER
                $user->update([
                    'name'     => $validatedData['name'],
                    'username' => $validatedData['username'],
                    'email'    => $validatedData['email'] ?? null,
                    'role_id'  => $validatedData['role_id'],
                    'kelas_id' => $validatedData['kelas_id'] ?? null,
                    'password' => !empty($validatedData['password'])
                        ? bcrypt($validatedData['password'])
                        : $user->password,
                ]);

                // SIMPAN RIWAYAT HANYA JIKA KELAS BERUBAH
                if (
                    $user->role->name === 'siswa' &&
                    !empty($validatedData['kelas_id']) &&
                    $oldKelasId != $validatedData['kelas_id']
                ) {
                    $tahunAjaran = TahunAjaranModel::where('is_active', true)->firstOrFail();

                    KelasHistoryModel::create([
                        'user_id'         => $user->id,
                        'kelas_id'        => $validatedData['kelas_id'],
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ]);
                }
            });

            return redirect()
                ->route('admin.user.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show($id)
    {
        $activeMenu = 'user';
        $title = 'Detail Pengguna';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => route('admin.user.index')],
            ['label' => 'Detail Pengguna', 'url' => null],
        ];

        $user = User::with(['role', 'kelas'])->findOrFail($id);

        return view('admin.user.show', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'user'
        ));
    }

    public function downloadTemplateSiswa($mode)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($mode === 'add') {
            $headers = ['name', 'username', 'kelas', 'password'];
            $fileName = 'template_import_siswa_tambah.xlsx';
        } else {
            $headers = ['username', 'kelas'];
            $fileName = 'template_import_siswa_update.xlsx';
        }

        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        foreach (range('A', chr(64 + count($headers))) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return new StreamedResponse(function () use ($spreadsheet) {
            (new Xlsx($spreadsheet))->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }


    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'mode' => 'required|in:tambah,update',
        ]);

        DB::beginTransaction();

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            unset($rows[0]); // hapus header

            $roleSiswa = RoleModel::where('name', 'siswa')->firstOrFail();
            $tahunAjaran = TahunAjaranModel::where('is_active', true)->firstOrFail();

            foreach ($rows as $index => $row) {

                // ===============================
                // MODE TAMBAH SISWA BARU
                // ===============================
                if ($request->mode === 'tambah') {

                    [$name, $username, $kelasNama, $password] = $row;

                    if (!$username || !$kelasNama || !$password) {
                        continue;
                    }

                    // skip jika username sudah ada
                    if (User::where('username', $username)->exists()) {
                        continue;
                    }

                    $kelas = KelasModel::where('nama_kelas', trim($kelasNama))->first();
                    if (!$kelas) continue;

                    $user = User::create([
                        'name'     => $name,
                        'username' => $username,
                        'password' => bcrypt($password),
                        'role_id'  => $roleSiswa->id,
                        'kelas_id' => $kelas->id,
                    ]);

                    KelasHistoryModel::create([
                        'user_id'         => $user->id,
                        'kelas_id'        => $kelas->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ]);
                }

                // ===============================
                // MODE UPDATE / NAIK KELAS
                // ===============================
                if ($request->mode === 'update') {

                    [$username, $kelasNama, $password] = $row;

                    if (!$username || !$kelasNama) continue;

                    $user = User::where('username', $username)->first();
                    if (!$user) continue;

                    $kelas = KelasModel::where('nama_kelas', trim($kelasNama))->first();
                    if (!$kelas) continue;

                    $oldKelasId = $user->kelas_id;

                    // update kelas
                    $user->update([
                        'kelas_id' => $kelas->id,
                    ]);

                    // update password jika diisi
                    if (!empty($password)) {
                        $user->update([
                            'password' => bcrypt($password),
                        ]);
                    }

                    // simpan history jika kelas berubah
                    if ($oldKelasId != $kelas->id) {
                        KelasHistoryModel::create([
                            'user_id'         => $user->id,
                            'kelas_id'        => $kelas->id,
                            'tahun_ajaran_id' => $tahunAjaran->id,
                        ]);
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Import siswa berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
