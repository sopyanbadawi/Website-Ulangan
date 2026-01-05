<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaranModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $activeMenu = 'tahun';
        $title = 'Manajemen Tahun Ajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => null],
            ['label' => 'Tahun Ajaran', 'url' => null],
        ];

        $query = TahunAjaranModel::query();

        // 🔍 Search
        if (request()->filled('search')) {
            $query->where(function ($q) {
                $q->where('semester', 'like', '%' . request('search') . '%')
                    ->orWhere('tahun', 'like', '%' . request('search') . '%');
            });
        }

        // 🔽 Filter semester (ENUM)
        if (request()->filled('semester')) {
            $query->where('semester', request('semester'));
        }

        // 🔝 Prioritas aktif
        $query->orderByDesc('is_active')
            ->orderByDesc('created_at');

        // 📄 Pagination
        $perPage = request()->get('per_page', 5);
        $dataTahun = $query->paginate($perPage)->withQueryString();

        return view('admin.tahun.index', compact(
            'activeMenu',
            'title',
            'dataTahun',
            'breadcrumbs'
        ));
    }


    public function create()
    {
        $activeMenu = 'tahun';
        $title = 'Tambah Tahun Ajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.tahun.index')],
            ['label' => 'Tambah Tahun Ajaran', 'url' => null],
        ];

        return view('admin.tahun.create', compact(
            'activeMenu',
            'title',
            'breadcrumbs'
        ));
    }



    public function store(Request $request)
    {
        $request->validate(
            [
                'semester' => ['required', 'in:ganjil,genap'],
                'tahun' => [
                    'required',
                    'string',
                    'max:9',
                    Rule::unique('tahun_ajaran')
                        ->where(fn($q) => $q->where('semester', $request->semester)),
                ],
                'is_active' => 'nullable|boolean',
            ],
            [
                'semester.required' => 'Semester wajib diisi.',
                'semester.in' => 'Semester harus ganjil atau genap.',
                'tahun.required' => 'Tahun Ajaran wajib diisi.',
                'tahun.unique' => 'Tahun Ajaran untuk semester ini sudah ada.',
            ]
        );

        // Jika is_active dicentang → nonaktifkan lainnya
        if ($request->boolean('is_active')) {
            TahunAjaranModel::where('is_active', true)->update(['is_active' => false]);
        }

        TahunAjaranModel::create([
            'semester' => $request->semester,
            'tahun' => $request->tahun,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.tahun.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $activeMenu = 'tahun';
        $title = 'Edit Tahun Ajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.tahun.index')],
            ['label' => 'Edit Tahun Ajaran', 'url' => null],
        ];

        $tahunAjaran = TahunAjaranModel::findOrFail($id);

        return view('admin.tahun.edit', compact(
            'activeMenu',
            'title',
            'tahunAjaran',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaranModel::findOrFail($id);

        $request->validate(
            [
                'semester' => ['required', 'in:ganjil,genap'],
                'tahun' => [
                    'required',
                    'string',
                    'max:9',
                    Rule::unique('tahun_ajaran')
                        ->where(fn($q) => $q->where('semester', $request->semester))
                        ->ignore($tahunAjaran->id),
                ],
                'is_active' => 'nullable|boolean',
            ],
            [
                'semester.required' => 'Semester wajib diisi.',
                'semester.in' => 'Semester harus ganjil atau genap.',
                'tahun.required' => 'Tahun Ajaran wajib diisi.',
                'tahun.unique' => 'Tahun Ajaran untuk semester ini sudah ada.',
            ]
        );

        // Jika diaktifkan → nonaktifkan yang lain
        if ($request->boolean('is_active')) {
            TahunAjaranModel::where('id', '!=', $tahunAjaran->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $tahunAjaran->update([
            'semester' => $request->semester,
            'tahun' => $request->tahun,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.tahun.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function show($id)
    {
        $activeMenu = 'tahun';
        $title = 'Detail Tahun Ajaran';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Akademik', 'url' => route('admin.tahun.index')],
            ['label' => 'Detail Tahun Ajaran', 'url' => null],
        ];

        $tahunAjaran = TahunAjaranModel::findOrFail($id);

        return view('admin.tahun.show', compact(
            'activeMenu',
            'title',
            'tahunAjaran',
            'breadcrumbs'
        ));
    }
}
