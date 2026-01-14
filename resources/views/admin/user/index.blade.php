@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <!-- Left section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:max-w-lg">

            <!-- Search -->
            <form method="GET" class="relative w-full" id="searchForm">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="ps-11 py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                       focus:border-blue-500 focus:ring-blue-500"
                    placeholder="search users..." id="searchInput">

                <input type="hidden" name="role" value="{{ request('role') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">

                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </form>

            <!-- Filter -->
            @php
                $activeRole = $roles->firstWhere('id', request('role'));
            @endphp

            <div class="hs-dropdown [--auto-close:inside] relative inline-flex w-full sm:w-auto">

                <!-- Button -->
                <button id="hs-dropdown-default" type="button"
                    class="hs-dropdown-toggle py-3 px-4 w-full sm:w-auto inline-flex justify-between items-center gap-x-2
                    text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs
                    hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50"
                    aria-haspopup="menu" aria-expanded="false">

                    {{ $activeRole ? $activeRole->name : 'Filter' }}

                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div class="hs-dropdown-menu transition-[opacity,margin] duration
                    hs-dropdown-open:opacity-100 opacity-0 hidden
                    min-w-60 bg-white shadow-md rounded-lg mt-2 z-30"
                    role="menu" aria-orientation="vertical">

                    <div class="p-1 space-y-0.5">

                        @foreach ($roles as $role)
                            <a href="{{ request()->fullUrlWithQuery(['role' => $role->id, 'page' => 1]) }}"
                                class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                    {{ request('role') == $role->id ? 'bg-gray-100 font-semibold' : 'text-gray-800 hover:bg-gray-100' }}">
                                {{ $role->name }}
                            </a>
                        @endforeach

                        <!-- Reset Filter -->
                        <a href="{{ route('admin.user.index') }}"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50">
                            Reset Filter
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Right button -->
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

            {{-- Import Siswa --}}
            <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
                <!-- BUTTON -->
                <button type="button"
                    class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2
                           text-sm font-medium rounded-lg
                            bg-green-600 text-white 
                           hover:bg-green-700 focus:outline-hidden focus:bg-green-800"
                    aria-haspopup="menu" aria-expanded="false">

                    Import Siswa
                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <!-- MENU -->
                <div class="hs-dropdown-menu transition-[opacity,margin] duration
                            hs-dropdown-open:opacity-100 opacity-0 hidden
                            min-w-60 bg-white shadow-md rounded-lg mt-2
                            after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full
                            before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                    role="menu">

                    <div class="p-1 space-y-0.5">

                        <!-- TAMBAH -->
                        <button type="button" data-hs-overlay="#modal-import-tambah"
                            class="w-full flex items-center gap-x-3.5 py-2 px-3
                                   rounded-lg text-sm text-gray-800
                                   hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100">
                            Tambah Siswa
                        </button>

                        <!-- UPDATE -->
                        <button type="button" data-hs-overlay="#modal-import-update"
                            class="w-full flex items-center gap-x-3.5 py-2 px-3
                                   rounded-lg text-sm text-gray-800
                                   hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100">
                            Update / Naik Kelas
                        </button>

                    </div>
                </div>
            </div>

            {{-- Tambah Pengguna --}}
            <button type="button" onclick="window.location.href='{{ route('admin.user.create') }}'"
                class="w-full sm:w-auto py-3 px-4 inline-flex justify-center items-center gap-x-2
                       text-sm font-medium rounded-lg border border-transparent
                       bg-blue-600 text-white hover:bg-blue-700">

                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pengguna
            </button>
        </div>
    </div>



    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full border-collapse divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase border-gray-200">
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase border-gray-200">
                                    Username
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase border-gray-200">
                                    Role
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase border-gray-200">
                                    Kelas
                                </th>
                                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($user as $item)
                                <tr class="hover:bg-gray-50">
                                    <!-- Name -->
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800 border-gray-200">
                                        {{ $item->name }}
                                    </td>

                                    <!-- Username -->
                                    <td class="px-6 py-4 text-sm text-gray-800 border-gray-200">
                                        {{ $item->username }}
                                    </td>

                                    <!-- Role -->
                                    <td class="px-6 py-4 text-sm text-gray-800 border-gray-200">
                                        @if ($item->role)
                                            <span
                                                class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-lg text-xs font-medium
                                                @if ($item->role->name == 'superadmin') bg-blue-100 text-blue-800
                                                @elseif ($item->role->name == 'guru') bg-green-100 text-green-800
                                                @elseif ($item->role->name == 'siswa') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $item->role->name }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>


                                    <!-- Kelas -->
                                    <td class="px-6 py-4 text-sm text-gray-800 border-gray-200">
                                        {{ $item->kelas->nama_kelas ?? '-' }}
                                    </td>

                                    <!-- Action -->
                                    <td class="px-6 py-4 text-end text-sm font-medium">
                                        <div class="inline-flex items-center gap-2">
                                            {{-- Detail --}}
                                            <a href="{{ route('admin.user.show', $item->id) }}"
                                                class="text-gray-600 hover:text-blue-600 transition" title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.user.edit', $item->id) }}"
                                                class="text-gray-600 hover:text-yellow-600 transition" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Data pengguna belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Wrapper -->
                    <div
                        class="grid gap-3 px-4 py-3
                            sm:flex sm:items-center sm:justify-between">

                        <!-- Pagination -->
                        <nav class="flex justify-center sm:justify-start items-center gap-x-1" aria-label="Pagination">

                            <!-- Previous -->
                            <a href="{{ $user->previousPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
                                text-sm rounded-lg border border-transparent text-gray-800
                                hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
                                {{ $user->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </a>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                @foreach ($user->getUrlRange(max(1, $user->currentPage() - 2), min($user->lastPage(), $user->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="min-h-9.5 min-w-9.5 flex justify-center items-center
                                        py-2 px-3 text-sm rounded-lg
                                        {{ $page == $user->currentPage()
                                            ? 'border border-gray-200 text-gray-800 bg-gray-50'
                                            : 'border border-transparent text-gray-800 hover:bg-gray-100' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Next -->
                            <a href="{{ $user->nextPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
                                text-sm rounded-lg border border-transparent text-gray-800
                                hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
                                {{ $user->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
                                <span class="sr-only">Next</span>
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </a>

                        </nav>
                        <!-- End Pagination -->

                        <!-- Page Size Dropdown -->
                        <div
                            class="hs-dropdown relative [--placement:top-left]
                            inline-flex justify-center sm:justify-start">

                            <button id="hs-bordered-pagination-dropdown" type="button"
                                class="py-1.5 px-2 inline-flex items-center gap-x-1
                                text-sm rounded-lg border border-gray-200 text-gray-800 shadow-2xs
                                hover:bg-gray-50 focus:outline-hidden focus:bg-gray-100">

                                {{ request('per_page', 5) }} page

                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>

                            @php
                                $sizes = [5, 8, 10];
                                $currentSize = request('per_page', 5);
                            @endphp

                            <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 w-48 hidden z-50
                                transition-[margin,opacity] opacity-0 duration-300 mb-2
                                bg-white shadow-md rounded-lg p-1 space-y-0.5"
                                role="menu" aria-labelledby="hs-bordered-pagination-dropdown">

                                @foreach ($sizes as $size)
                                    <a href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}"
                                        class="w-full flex items-center gap-x-3.5 py-2 px-3
                                        rounded-lg text-sm
                                        {{ $currentSize == $size ? 'text-gray-800 bg-gray-100 font-semibold' : 'text-gray-800 hover:bg-gray-100' }}">

                                        {{ $size }} page

                                        @if ($currentSize == $size)
                                            <svg class="ms-auto shrink-0 size-4 text-blue-600"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach

                            </div>
                        </div>
                        <!-- End Dropdown -->
                    </div>
                    <!-- End Pagination Wrapper -->
                </div>
            </div>
        </div>
    </div>

    <div id="modal-import-tambah" class="hs-overlay hidden fixed inset-0 z-[9999] bg-black/50 overflow-y-auto"
        role="dialog" tabindex="-1">

        <div
            class="hs-overlay-animation-target
               hs-overlay-open:mt-10
               hs-overlay-open:opacity-100
               hs-overlay-open:duration-300
               mt-0 opacity-0 transition-all
               sm:max-w-lg sm:w-full m-3 sm:mx-auto">

            <div
                class="flex flex-col bg-white border border-gray-200
                   shadow-xl rounded-xl pointer-events-auto">

                <!-- HEADER -->
                <div class="flex justify-between items-center py-3 px-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Import Tambah Siswa
                    </h3>

                    <button type="button"
                        class="size-8 inline-flex justify-center items-center
                           rounded-full bg-gray-100 hover:bg-gray-200"
                        data-hs-overlay="#modal-import-tambah">
                        ✕
                    </button>
                </div>

                <!-- FORM -->
                <form action="{{ route('admin.user.import-siswa') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" value="tambah">

                    <!-- BODY -->
                    <div class="p-4 space-y-5">

                        <!-- TEMPLATE -->
                        <a href="{{ route('admin.user.template-siswa', 'add') }}"
                            class="inline-flex items-center gap-x-2
                               text-sm font-medium text-blue-600 hover:underline">
                            Download Template Tambah Siswa
                        </a>

                        <!-- FILE INPUT -->
                        <div class="max-w-sm">
                            <label class="block">
                                <span class="sr-only">Upload file Excel</span>
                                <input type="file" name="file" required accept=".xls,.xlsx"
                                    class="block w-full text-sm text-gray-500
                                       file:me-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-blue-600 file:text-white
                                       hover:file:bg-blue-700">
                            </label>
                        </div>

                        <!-- INFO -->
                        <p class="text-sm text-gray-500">
                            Kolom wajib: <b>name, username, kelas, password</b>
                        </p>

                    </div>

                    <!-- FOOTER -->
                    <div class="flex justify-end gap-x-2 py-3 px-4 border-t">
                        <button type="button"
                            class="px-4 py-2 rounded-lg border
                               text-gray-700 hover:bg-gray-50"
                            data-hs-overlay="#modal-import-tambah">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2 rounded-lg
                               bg-blue-600 text-white hover:bg-blue-700">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div id="modal-import-update" class="hs-overlay hidden fixed inset-0 z-[9999] bg-black/50 overflow-y-auto"
        role="dialog" tabindex="-1">

        <div
            class="hs-overlay-animation-target
               hs-overlay-open:mt-10
               hs-overlay-open:opacity-100
               hs-overlay-open:duration-300
               mt-0 opacity-0 transition-all
               sm:max-w-lg sm:w-full m-3 sm:mx-auto">

            <div
                class="flex flex-col bg-white border border-gray-200
                   shadow-xl rounded-xl pointer-events-auto">

                <!-- HEADER -->
                <div class="flex justify-between items-center py-3 px-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Import Update / Naik Kelas
                    </h3>

                    <button type="button"
                        class="size-8 inline-flex justify-center items-center
                           rounded-full bg-gray-100 hover:bg-gray-200"
                        data-hs-overlay="#modal-import-update">
                        ✕
                    </button>
                </div>

                <!-- FORM -->
                <form action="{{ route('admin.user.import-siswa') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" value="update">

                    <!-- BODY -->
                    <div class="p-4 space-y-5">

                        <!-- TEMPLATE -->
                        <a href="{{ route('admin.user.template-siswa', 'update') }}"
                            class="inline-flex items-center gap-x-2
                               text-sm font-medium text-blue-600 hover:underline">
                            Download Template Update Kelas
                        </a>

                        <!-- FILE INPUT -->
                        <div class="max-w-sm">
                            <label class="block">
                                <span class="sr-only">Upload file Excel</span>
                                <input type="file" name="file" required accept=".xls,.xlsx"
                                    class="block w-full text-sm text-gray-500
                                       file:me-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-blue-600 file:text-white
                                       hover:file:bg-blue-700">
                            </label>
                        </div>

                        <!-- INFO -->
                        <p class="text-sm text-gray-500">
                            Kolom wajib: <b>username, kelas</b><br>
                            Username harus sudah terdaftar.
                        </p>

                    </div>

                    <!-- FOOTER -->
                    <div class="flex justify-end gap-x-2 py-3 px-4 border-t">
                        <button type="button"
                            class="px-4 py-2 rounded-lg border
                               text-gray-700 hover:bg-gray-50"
                            data-hs-overlay="#modal-import-update">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2 rounded-lg
                               bg-blue-600 text-white hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script>
        let searchTimeout;

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 400);
        });
    </script>
@endsection
