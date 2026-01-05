@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6">
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="py-2.5 sm:py-3 px-4 block w-full rounded-lg sm:text-sm
                    border {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}"
                        placeholder="Masukan Nama Lengkap">

                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-medium mb-2">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        class="py-2.5 sm:py-3 px-4 block w-full rounded-lg sm:text-sm
                    border {{ $errors->has('username') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}"
                        placeholder="Masukan Username">

                    @error('username')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan Email"
                        class="py-2.5 sm:py-3 px-4 block w-full rounded-lg sm:text-sm
                    border {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}">

                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label for="role_id" class="block text-sm font-medium mb-2">Role</label>
                    <select id="role_id" name="role_id"
                        class="py-3 px-4 pe-9 block w-full rounded-lg text-sm
                    border {{ $errors->has('role_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}">
                        <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Pilih Role</option>
                        @foreach ($role as $item)
                            <option value="{{ $item->id }}" {{ old('role_id') == $item->id ? 'selected' : '' }}>
                                {{ ucfirst($item->name) }}
                            </option>
                        @endforeach
                    </select>

                    @error('role_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kelas --}}
                <div id="kelas-wrapper" class="hidden">
                    <label for="kelas_id" class="block text-sm font-medium mb-2">Kelas</label>
                    <select id="kelas_id" name="kelas_id"
                        class="py-3 px-4 pe-9 block w-full rounded-lg text-sm
                    border {{ $errors->has('kelas_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500' }}">
                        <option value="" disabled {{ old('kelas_id') ? '' : 'selected' }}>Pilih Kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                {{ ucfirst($item->nama_kelas) }}
                            </option>
                        @endforeach
                    </select>

                    @error('kelas_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password"
                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Masukan Password">

                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ulangi Password">
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

    {{-- Script toggle kelas --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            const kelasWrapper = document.getElementById('kelas-wrapper');

            const ROLE_SISWA = 'siswa';

            function toggleKelas() {
                const selectedRole = roleSelect.options[roleSelect.selectedIndex]?.text.toLowerCase();
                if (selectedRole === ROLE_SISWA) {
                    kelasWrapper.classList.remove('hidden');
                } else {
                    kelasWrapper.classList.add('hidden');
                    document.getElementById('kelas_id').value = '';
                }
            }

            toggleKelas();
            roleSelect.addEventListener('change', toggleKelas);
        });
    </script>
@endsection
