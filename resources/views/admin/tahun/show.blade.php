@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">

        {{-- Desktop Action --}}
        <div class="hidden sm:flex justify-end gap-3 mb-4">
            <a href="{{ route('admin.tahun.edit', $tahunAjaran->id) }}"
                class="py-2.5 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-yellow-500 text-yellow-600 hover:bg-yellow-50">
                Edit
            </a>

            <a href="{{ route('admin.tahun.index') }}"
                class="py-2.5 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-gray-300 text-gray-600 hover:bg-gray-50">
                Kembali
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-2xs p-6 space-y-6">

            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Detail Tahun Ajaran
                </h2>
                <p class="text-sm text-gray-500">
                    Informasi lengkap data tahun ajaran
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <p class="text-xs text-gray-500">Tahun Ajaran</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $tahunAjaran->tahun }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Semester</p>
                    <p class="text-sm font-medium text-gray-800 capitalize">
                        {{ $tahunAjaran->semester }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    @if ($tahunAjaran->is_active)
                        <span
                            class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-lg
                                   text-xs font-medium bg-green-100 text-green-800">
                            Aktif
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-x-1.5 py-1 px-3 rounded-lg
                                   text-xs font-medium bg-gray-100 text-gray-800">
                            Tidak Aktif
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-gray-500">Dibuat Pada</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $tahunAjaran->created_at
                            ? $tahunAjaran->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i')
                            : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Terakhir Diperbarui</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $tahunAjaran->updated_at
                            ? $tahunAjaran->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i')
                            : '-' }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Mobile Action --}}
        <div class="flex sm:hidden gap-3 mt-6">
            <a href="{{ route('admin.tahun.edit', $tahunAjaran->id) }}"
                class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-yellow-500
                   text-yellow-600 hover:bg-yellow-50">
                Edit
            </a>

            <a href="{{ route('admin.tahun.index') }}"
                class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-gray-300
                   text-gray-600 hover:bg-gray-50">
                Kembali
            </a>
        </div>

    </div>
@endsection
