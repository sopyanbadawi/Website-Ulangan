@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Desktop Action --}}
    <div class="hidden sm:flex justify-end gap-3 mb-4">
        <a href="{{ route('admin.mapel.edit', $mapel->id) }}"
            class="py-2.5 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-yellow-500 text-yellow-600 hover:bg-yellow-50">
            Edit
        </a>

        <a href="{{ route('admin.mapel.index') }}"
            class="py-2.5 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-gray-300 text-gray-600 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-2xs p-6 space-y-6">

        {{-- Title --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                Detail Mata Pelajaran
            </h2>
            <p class="text-sm text-gray-500">
                Informasi lengkap data mata pelajaran
            </p>
        </div>

        {{-- Content --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
                <p class="text-xs text-gray-500">Nama mapel</p>
                <p class="text-sm font-medium text-gray-800">
                    {{ $mapel->nama_mapel }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Dibuat Pada</p>
                <p class="text-sm font-medium text-gray-800">
                    @isset($mapel->created_at)
                        {{ $mapel->created_at->format('d M Y, H:i') }}
                    @else
                        -
                    @endisset
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Terakhir Diperbarui</p>
                <p class="text-sm font-medium text-gray-800">
                    @isset($mapel->updated_at)
                        {{ $mapel->updated_at->format('d M Y, H:i') }}
                    @else
                        -
                    @endisset
                </p>
            </div>

        </div>
    </div>

    {{-- Mobile Action --}}
    <div class="flex sm:hidden gap-3 mt-6">
        <a href="{{ route('admin.mapel.edit', $mapel->id) }}"
            class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-yellow-500
                   text-yellow-600 hover:bg-yellow-50">
            Edit
        </a>

        <a href="{{ route('admin.mapel.index') }}"
            class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-gray-300
                   text-gray-600 hover:bg-gray-50">
            Kembali
        </a>
    </div>

</div>
@endsection
