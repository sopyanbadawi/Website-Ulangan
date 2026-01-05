@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6">

            <form action="{{ route('admin.tahun.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Semester --}}
                <div>
                    <label for="semester" class="block text-sm font-medium mb-2">
                        Semester
                    </label>

                    <select id="semester" name="semester"
                        class="py-3 px-4 pe-9 block w-full rounded-lg text-sm
                    border {{ $errors->has('semester')
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                        : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}">

                        <option value="" disabled {{ old('semester') ? '' : 'selected' }}>
                            Pilih Semester
                        </option>
                        <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>
                            Ganjil
                        </option>
                        <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>
                            Genap
                        </option>
                    </select>

                    @error('semester')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Ajaran --}}
                <div>
                    <label for="tahun" class="block text-sm font-medium mb-2">
                        Tahun Ajaran
                    </label>

                    <input type="text" id="tahun" name="tahun" value="{{ old('tahun') }}"
                        placeholder="Contoh: 2025/2026"
                        class="py-2.5 sm:py-3 px-4 block w-full rounded-lg sm:text-sm
                    border {{ $errors->has('tahun')
                        ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                        : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}">

                    @error('tahun')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Jadikan Tahun Ajaran Aktif
                    </label>
                </div>

                {{-- Tombol --}}
                <div class="grid grid-cols-1 gap-2 mt-6 sm:flex sm:justify-end sm:items-center">
                    <button type="submit"
                        class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2
                    text-sm font-medium rounded-lg border border-transparent
                    bg-blue-600 text-white hover:bg-blue-700 focus:outline-none">
                        Simpan
                    </button>

                    <a href="{{ route('admin.tahun.index') }}"
                        class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2
                    text-sm font-medium rounded-lg border border-gray-500
                    text-gray-500 hover:border-gray-800 hover:text-gray-800">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
@endsection
