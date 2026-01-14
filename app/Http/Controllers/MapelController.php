<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaranModel;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MapelController extends Controller
{
    public function index()
    {
        $activeMenu = 'mapel';
        $title = 'Manajemen Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => null],
            ['label' => 'Mata Pelajaran', 'url' => null],
        ];

        $query = MataPelajaranModel::query();

        // search
        if (request()->filled('search')) {
            $query->where('nama_mapel', 'like', '%' . request('search') . '%');
        }

        // urutkan
        $query->orderBy('nama_mapel', 'asc');

        // pagination
        $perPage = request()->get('per_page', 5);
        $dataMapel = $query->paginate($perPage)->withQueryString();

        return view('admin.mapel.index', compact(
            'activeMenu',
            'title',
            'dataMapel',
            'breadcrumbs'
        ));
    }

    public function create()
    {
        $activeMenu = 'mapel';
        $title = 'Tambah Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.mapel.index')],
            ['label' => 'Tambah Mata Pelajaran', 'url' => null],
        ];

        return view('admin.mapel.create', compact(
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function store(Request $request)
    {
        // Normalisasi input
        $request->merge([
            'nama_mapel' => Str::title($request->nama_mapel),
        ]);

        // Validasi
        $validatedData = $request->validate([
            'nama_mapel' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mata_pelajaran', 'nama_mapel'),
            ],
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique' => 'Nama mata pelajaran sudah ada.',
        ]);

        // Simpan
        MataPelajaranModel::create($validatedData);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $activeMenu = 'mapel';
        $title = 'Edit Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.mapel.index')],
            ['label' => 'Edit Mata Pelajaran', 'url' => null],
        ];

        $mapel = MataPelajaranModel::findOrFail($id);

        return view('admin.mapel.edit', compact(
            'activeMenu',
            'title',
            'mapel',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaranModel::findOrFail($id);

        // Normalisasi input
        $request->merge([
            'nama_mapel' => Str::title($request->nama_mapel),
        ]);

        // Validasi
        $validatedData = $request->validate([
            'nama_mapel' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mata_pelajaran', 'nama_mapel')->ignore($mapel->id),
            ],
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique' => 'Nama mata pelajaran sudah ada.',
        ]);

        // Update
        $mapel->update($validatedData);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function show($id)
    {
        $activeMenu = 'mapel';
        $title = 'Detail Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.mapel.index')],
            ['label' => 'Detail Mata Pelajaran', 'url' => null],
        ];

        $mapel = MataPelajaranModel::findOrFail($id);

        return view('admin.mapel.show', compact(
            'activeMenu',
            'title',
            'mapel',
            'breadcrumbs'
        ));
    }
}
