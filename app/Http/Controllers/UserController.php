<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleModel;
use App\Models\KelasModel;
use App\Models\KelasHistoryModel;
use App\Models\TahunAjaranModel;
use Illuminate\Support\Facades\DB;

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
        $roles = RoleModel::all();

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

        $role  = RoleModel::all();
        $kelas = KelasModel::all();

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
        $role  = RoleModel::all();
        $kelas = KelasModel::all();

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

                // 🔄 UPDATE USER
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

                // 🔥 SIMPAN RIWAYAT HANYA JIKA KELAS BERUBAH
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
}
