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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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

        $query = User::with('role', 'kelas')
            ->join('roles', 'users.role_id', '=', 'roles.id');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', "%{$request->search}%")
                    ->orWhere('users.username', 'like', "%{$request->search}%")
                    ->orWhere('users.email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('users.role_id', $request->role);
        }

        $query
            ->orderByRaw("
                CASE
                    WHEN roles.name = 'superadmin' THEN 1
                    WHEN roles.name = 'guru' THEN 2
                    WHEN roles.name = 'siswa' THEN 3
                    ELSE 4
                END
            ")
            ->orderByRaw("
                CASE
                    WHEN roles.name = 'siswa' THEN users.name
                    ELSE NULL
                END ASC
            ")
            ->orderByDesc('users.created_at')
            ->select('users.*');


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
            $role = RoleModel::find($request->role_id);

            $validatedData = $request->validate([
                'name'      => 'required|string|max:255',
                'username'  => 'required|string|max:255|unique:users,username',
                'email'     => 'nullable|email|max:255|unique:users,email',
                'password'  => 'required|string|min:8|confirmed',
                'role_id'   => 'required|exists:roles,id',

                'kelas_id'  => $role?->name === 'siswa'
                    ? 'required|exists:kelas,id'
                    : 'nullable',
            ], [
                'name.required'       => 'Nama lengkap wajib diisi.',
                'username.required'   => 'Username wajib diisi.',
                'username.unique'     => 'Username sudah digunakan.',
                'email.email'         => 'Format email tidak valid.',
                'email.unique'        => 'Email sudah digunakan.',
                'password.required'   => 'Password wajib diisi.',
                'password.min'        => 'Password minimal 8 karakter.',
                'password.confirmed'  => 'Konfirmasi password tidak cocok.',
                'role_id.required'    => 'Role wajib dipilih.',
                'kelas_id.required'   => 'Kelas wajib dipilih untuk siswa.',
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

                // SIMPAN KE KELAS_HISTORY (KHUSUS SISWA)
                if ($user->isSiswa()) {
                    $tahunAjaran = TahunAjaranModel::where('is_active', true)->first();

                    if (! $tahunAjaran) {
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
            // Mode tambah: name, username, kelas, password
            $headers = ['name', 'username', 'kelas', 'password'];
            $fileName = 'template_import_siswa_tambah.xlsx';
        } else {
            // Mode update: username, kelas
            $headers = ['username', 'kelas'];
            $fileName = 'template_import_siswa_update.xlsx';
        }

        // Set header
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:' . chr(64 + count($headers)) . '1')->getFont()->setBold(true);

        // Set auto size
        foreach (range('A', chr(64 + count($headers))) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set kolom yang perlu dianggap string (misal: username) agar nol di depan tidak hilang
        $stringColumns = [];
        if ($mode === 'add') {
            $stringColumns = ['B']; // kolom B = username
        } else {
            $stringColumns = ['A']; // kolom A = username
        }

        foreach ($stringColumns as $col) {
            $sheet->getStyle($col)->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_TEXT);
        }

        // Jika ingin menambahkan contoh baris kosong agar user tahu formatnya
        $exampleRow = [];
        foreach ($headers as $header) {
            if (in_array($header, ['username'])) {
                $exampleRow[] = '01234'; // contoh username dengan nol di depan
            } else {
                $exampleRow[] = ''; // kosong untuk kolom lain
            }
        }
        $sheet->fromArray($exampleRow, null, 'A2');

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
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

            // hapus header
            unset($rows[0]);

            $roleSiswa = RoleModel::where('name', 'siswa')->firstOrFail();
            $tahunAjaran = TahunAjaranModel::where('is_active', true)->first();

            if (!$tahunAjaran) {
                throw new \Exception('Tahun ajaran aktif belum diset. Silakan aktifkan terlebih dahulu.');
            }

            foreach ($rows as $index => $row) {

                // ===============================
                // MODE TAMBAH SISWA BARU
                // ===============================
                if ($request->mode === 'tambah') {

                    [$name, $username, $kelasNama, $password] = $row;

                    // skip jika ada field kosong
                    if (!$username || !$kelasNama || !$password) {
                        continue;
                    }

                    // pastikan username tetap string (nol di depan)
                    $username = (string) $username;

                    // skip jika username sudah ada
                    if (User::where('username', $username)->exists()) {
                        continue;
                    }

                    // normalisasi nama kelas: hapus spasi & huruf besar semua
                    $kelasNamaNormalized = strtoupper(str_replace(' ', '', $kelasNama));
                    $kelas = KelasModel::whereRaw("REPLACE(UPPER(nama_kelas), ' ', '') = ?", [$kelasNamaNormalized])->first();

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

                    $username = (string) $username;

                    $user = User::where('username', $username)->first();
                    if (!$user) continue;

                    // normalisasi nama kelas
                    $kelasNamaNormalized = strtoupper(str_replace(' ', '', $kelasNama));
                    $kelas = KelasModel::whereRaw("REPLACE(UPPER(nama_kelas), ' ', '') = ?", [$kelasNamaNormalized])->first();
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
