@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6">
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama Kelas --}}
                <div>
                    <label for="nama_mapel" class="block text-sm font-medium mb-2">Mata Pelajaran</label>
                    <input type="text" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel') }}"
                        class="py-2.5 sm:py-3 px-4 block w-full rounded-lg sm:text-sm
                    border {{ $errors->has('nama_mapel') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}"
                        placeholder="Masukan Mata Pelajaran">

                    @error('nama_mapel')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="grid grid-cols-1 gap-2 mt-6 sm:flex sm:justify-end sm:items-center">
                    <button type="submit"
                        class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                        Simpan
                    </button>
                    <button type="button" onclick="window.history.back()"
                        class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-gray-500 text-gray-500 hover:border-gray-800 hover:text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
