@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Desktop Action --}}
    <div class="hidden sm:flex justify-end gap-3 mb-4">
        <a href="{{ route('admin.kelas.edit', $kelas->id) }}"
            class="py-2.5 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-yellow-500 text-yellow-600 hover:bg-yellow-50">
            Edit
        </a>

        <a href="{{ route('admin.kelas.index') }}"
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
                Detail Kelas
            </h2>
            <p class="text-sm text-gray-500">
                Informasi lengkap data kelas
            </p>
        </div>

        {{-- Content --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
                <p class="text-xs text-gray-500">Nama Kelas</p>
                <p class="text-sm font-medium text-gray-800">
                    {{ $kelas->nama_kelas }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Dibuat Pada</p>
                <p class="text-sm font-medium text-gray-800">
                    @isset($kelas->created_at)
                        {{ $kelas->created_at->format('d M Y, H:i') }}
                    @else
                        -
                    @endisset
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Terakhir Diperbarui</p>
                <p class="text-sm font-medium text-gray-800">
                    @isset($kelas->updated_at)
                        {{ $kelas->updated_at->format('d M Y, H:i') }}
                    @else
                        -
                    @endisset
                </p>
            </div>

        </div>
    </div>

    {{-- Mobile Action --}}
    <div class="flex sm:hidden gap-3 mt-6">
        <a href="{{ route('admin.kelas.edit', $kelas->id) }}"
            class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-yellow-500
                   text-yellow-600 hover:bg-yellow-50">
            Edit
        </a>

        <a href="{{ route('admin.kelas.index') }}"
            class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-gray-300
                   text-gray-600 hover:bg-gray-50">
            Kembali
        </a>
    </div>

</div>
@endsection
