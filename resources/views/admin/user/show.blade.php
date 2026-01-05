@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto">

        {{-- Desktop Action --}}
        <div class="hidden sm:flex justify-end gap-3 mb-4">
            <a href="{{ route('admin.user.edit', $user->id) }}"
                class="py-2.5 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-yellow-500 text-yellow-600 hover:bg-yellow-50">
                Edit
            </a>

            <a href="{{ route('admin.user.index') }}"
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
                    Detail Pengguna
                </h2>
                <p class="text-sm text-gray-500">
                    Informasi lengkap data pengguna
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <p class="text-xs text-gray-500">Nama</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Username</p>
                    <p class="text-sm font-medium text-gray-800">{{ $user->username }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $user->email ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Role</p>
                    <p class="text-sm font-medium text-gray-800">
                        @if ($user->role)
                            <span
                                class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-lg text-xs font-medium
                                                @if ($user->role->name == 'superadmin') bg-blue-100 text-blue-800
                                                @elseif ($user->role->name == 'guru') bg-green-100 text-green-800
                                                @elseif ($user->role->name == 'siswa') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $user->role->name }}
                            </span>
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Kelas</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $user->kelas->nama_kelas ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Dibuat Pada</p>
                    <p class="text-sm font-medium text-gray-800">
                        @isset($user->created_at)
                            {{ $user->created_at->format('d M Y, H:i') }}
                        @else
                            -
                        @endisset
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Terakhir Diperbarui</p>
                    <p class="text-sm font-medium text-gray-800">
                        @isset($user->created_at)
                            {{ $user->created_at->format('d M Y, H:i') }}
                        @else
                            -
                        @endisset
                    </p>
                </div>

            </div>
        </div>

        {{-- Mobile Action (BOTTOM) --}}
        <div class="flex sm:hidden gap-3 mt-6">
            <a href="{{ route('admin.user.edit', $user->id) }}"
                class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-yellow-500
                   text-yellow-600 hover:bg-yellow-50">
                Edit
            </a>

            <a href="{{ route('admin.user.index') }}"
                class="flex-1 py-3 text-center text-sm font-medium
                   rounded-lg border border-gray-300
                   text-gray-600 hover:bg-gray-50">
                Kembali
            </a>
        </div>

    </div>
@endsection
