<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuruMapelModel;
use App\Models\MataPelajaranModel;
use App\Models\User;

class GuruMapelController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'guru_mapel';
        $title = 'Manajemen Guru Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => null],
            ['label' => 'Guru Mata Pelajaran', 'url' => null],
        ];

        $query = GuruMapelModel::with(['guru', 'mataPelajaran']);

        // search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', function ($qGuru) use ($search) {
                    $qGuru->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('mataPelajaran', function ($qMapel) use ($search) {
                        $qMapel->where('nama_mapel', 'like', "%{$search}%");
                    });
            });
        }

        // filter
        if ($request->filled('mapel')) {
            $query->where('mata_pelajaran_id', $request->mapel);
        }

        // pagination
        $perPage = $request->get('per_page', 5);
        $dataGuruMapel = $query->paginate($perPage)->withQueryString();

        // mata pelajaran list
        $mapels = MataPelajaranModel::all();

        return view('admin.guru_mapel.index', compact(
            'activeMenu',
            'title',
            'dataGuruMapel',
            'mapels',
            'breadcrumbs'
        ));
    }

    public function create()
    {
        $activeMenu = 'guru_mapel';
        $title = 'Tambah Guru Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.guru_mapel.index')],
            ['label' => 'Tambah Guru Mata Pelajaran', 'url' => null],
        ];

        $guru = User::whereHas('role', fn($q) => $q->where('name', 'guru'))
            ->orderBy('name')
            ->get();

        $mapel =  MataPelajaranModel::orderBy('nama_mapel')->get();

        return view('admin.guru_mapel.create', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'guru',
            'mapel'
        ));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        ]);

        // Cek jika kombinasi guru & mapel sudah ada
        $exists = GuruMapelModel::where('user_id', $request->user_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['user_id' => 'Guru ini sudah mengampu mata pelajaran yang dipilih.']);
        }

        // Simpan ke tabel guru_mapel
        GuruMapelModel::create([
            'user_id' => $request->user_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
        ]);

        return redirect()->route('admin.guru_mapel.index')
            ->with('success', 'Guru mata pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $activeMenu = 'guru_mapel';
        $title = 'Edit Guru Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.guru_mapel.index')],
            ['label' => 'Edit Guru Mata Pelajaran', 'url' => null],
        ];

        $guruMapel = GuruMapelModel::findOrFail($id);

        $guru = User::whereHas('role', fn($q) => $q->where('name', 'guru'))
            ->orderBy('name')
            ->get();

        $mapel = MataPelajaranModel::orderBy('nama_mapel')->get();

        return view('admin.guru_mapel.edit', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'guruMapel',
            'guru',
            'mapel'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        ]);

        $guruMapel = GuruMapelModel::findOrFail($id);

        // Cek duplikasi kecuali ID yang sedang diedit
        $exists = GuruMapelModel::where('user_id', $request->user_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('id', '!=', $guruMapel->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['user_id' => 'Guru ini sudah mengampu mata pelajaran yang dipilih.']);
        }

        $guruMapel->update([
            'user_id' => $request->user_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
        ]);

        return redirect()->route('admin.guru_mapel.index')
            ->with('success', 'Guru mata pelajaran berhasil diperbarui.');
    }

    public function show($id)
    {
        $activeMenu = 'guru_mapel';
        $title = 'Detail Guru Mata Pelajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.guru_mapel.index')],
            ['label' => 'Detail Guru Mata Pelajaran', 'url' => null],
        ];

        $guruMapel = GuruMapelModel::with(['guru', 'mataPelajaran'])->findOrFail($id);

        return view('admin.guru_mapel.show', compact(
            'activeMenu',
            'title',
            'breadcrumbs',
            'guruMapel'
        ));
    }
}
