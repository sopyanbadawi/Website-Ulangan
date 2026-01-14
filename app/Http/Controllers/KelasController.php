<?php

namespace App\Http\Controllers;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    public function index()
    {
        $activeMenu = 'kelas';
        $title = 'Manajemen Kelas';
        $user = auth()->user();
        $roleName = $user->role->name === 'superadmin' ? 'admin' : 'guru';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route($roleName . '.dashboard')],
            ['label' => 'Akademik', 'url' => null],
            ['label' => 'Kelas', 'url' => null],
        ];

        $query = KelasModel::query();

        // search
        if (request()->filled('search')) {
            $query->where('nama_kelas', 'like', '%' . request()->search . '%');
        }

        // pagination
        $perPage = request()->get('per_page', 5);
        $dataKelas = $query->paginate($perPage)->withQueryString();

        // kelas list
        $kelas = KelasModel::select('id','nama_kelas')->get();

        return view($roleName . '.kelas.index', compact(
            'activeMenu',
            'title',
            'kelas',
            'dataKelas',
            'breadcrumbs'
        ));
    }

    public function create()
    {
        $activeMenu = 'kelas';
        $title = 'Tambah Kelas';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.kelas.index')],
            ['label' => 'Tambah Kelas', 'url' => null],
        ];

        return view('admin.kelas.create', compact(
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            ],
            [
                'nama_kelas.required' => 'Nama kelas wajib diisi.',
                'nama_kelas.string'   => 'Nama kelas harus berupa teks.',
                'nama_kelas.max'      => 'Nama kelas maksimal 255 karakter.',
                'nama_kelas.unique'   => 'Nama kelas sudah ada.',
            ]
        );

        KelasModel::create([
            'nama_kelas' => Str::upper($request->nama_kelas),
        ]);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $activeMenu = 'kelas';
        $title = 'Edit Kelas';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.kelas.index')],
            ['label' => 'Edit Kelas', 'url' => null],
        ];

        $kelas = KelasModel::findOrFail($id);

        return view('admin.kelas.edit', compact(
            'activeMenu',
            'title',
            'kelas',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $kelas = KelasModel::findOrFail($id);

            $request->validate(
                [
                    'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id,
                ],
                [
                    'nama_kelas.required' => 'Nama kelas wajib diisi.',
                    'nama_kelas.string'   => 'Nama kelas harus berupa teks.',
                    'nama_kelas.max'      => 'Nama kelas maksimal 255 karakter.',
                    'nama_kelas.unique'   => 'Nama kelas sudah ada.',
                ]
            );

            $kelas->update([
                'nama_kelas' => Str::upper($request->nama_kelas),
            ]);

            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show($id)
    {
        $activeMenu = 'kelas';
        $title = 'Detail Kelas';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.kelas.index')],
            ['label' => 'Detail Kelas', 'url' => null],
        ];

        $kelas = KelasModel::findOrFail($id);

        return view('admin.kelas.show', compact(
            'activeMenu',
            'title',
            'kelas',
            'breadcrumbs'
        ));
    }

    public function rekap(Request $request)
    {
        $activeMenu = 'kelas';
        $guruId = auth()->id();
        $tahunAjaranId = $request->tahun_ajaran_id;

        // Data pendukung untuk filter dropdown (Tahun Ajaran)
        $listTahun = \App\Models\TahunAjaranModel::orderBy('tahun', 'desc')->get();
        
        // Memanggil fungsi baru tanpa mengganggu distribusiNilai() yang sudah ada
        $rekapData = \App\Models\UjianAttemptModel::getRekapUntukGuru($guruId, $tahunAjaranId);

        return view('guru.rekap', compact(
            'activeMenu',
            'rekapData', 
            'listTahun', 
            'tahunAjaranId'));
    }
}
