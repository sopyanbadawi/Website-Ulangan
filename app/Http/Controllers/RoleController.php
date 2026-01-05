<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'role';
        $title = 'Manajemen Role';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => null],
            ['label' => 'Role', 'url' => null],
        ];

        $query = RoleModel::query();

        // search 
        if (request()->filled('search')) {
            $query->where('name', 'like', '%' . request()->search . '%');
        }

        // filter
        if (request()->filled('role')) {
            $query->where('id', request()->role);
        }

        // pagination
        $perPage = request()->get('per_page', 5);
        $roles = $query->paginate($perPage)->withQueryString();

        // role list
        $role = RoleModel::all();

        return view('admin.role.index', compact(
            'activeMenu',
            'title',
            'roles',
            'role',
            'breadcrumbs'
        ));
    }

    public function create()
    {
        $activeMenu = 'role';
        $title = 'Tambah Role';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => route('admin.role.index')],
            ['label' => 'Tambah Role', 'url' => null],
        ];

        return view('admin.role.create', compact(
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|unique:roles,name',
                ],
                [
                    'name.required' => 'Nama role wajib diisi.',
                    'name.unique'   => 'Nama role sudah ada di database.',
                ]
            );

            RoleModel::create([
                'name' => $request->name,
            ]);
            return redirect()->route('admin.role.index')->with('success', 'Role berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $activeMenu = 'role';
            $title = 'Edit Role';

            $breadcrumbs = [
                ['label' => 'Home', 'url' => route('admin.dashboard')],
                ['label' => 'User Management', 'url' => route('admin.role.index')],
                ['label' => 'Edit Role', 'url' => null],
            ];

            $role = RoleModel::findOrFail($id);

            return view('admin.role.edit', compact(
                'activeMenu',
                'title',
                'role',
                'breadcrumbs'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.role.index')->with('error', 'Role tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = RoleModel::findOrFail($id);

            $request->validate(
                [
                    'name' => 'required|unique:roles,name,' . $role->id,
                ],
                [
                    'name.required' => 'Nama role wajib diisi.',
                    'name.unique'   => 'Nama role sudah ada di database.',
                ]
            );

            $role->update([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.role.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.role.index')->with('error', 'Role tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show($id)
    {
        $activeMenu = 'role';
        $title = 'Detail Role';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'User Management', 'url' => route('admin.role.index')],
            ['label' => 'Detail Role', 'url' => null],
        ];

        $role = RoleModel::findOrFail($id);
        return view('admin.role.show', compact(
            'activeMenu',
            'title',
            'role',
            'breadcrumbs'
        ));
    }
}
