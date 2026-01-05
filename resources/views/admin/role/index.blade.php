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
                    placeholder="search role..." id="searchInput">

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
                $activeRole = $role->firstWhere('id', request('role'));
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

                        @foreach ($role as $role)
                            <a href="{{ request()->fullUrlWithQuery(['role' => $role->id, 'page' => 1]) }}"
                                class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                                {{ request('role') == $role->id ? 'bg-gray-100 font-semibold' : 'text-gray-800 hover:bg-gray-100' }}">
                                {{ $role->name }}
                            </a>
                        @endforeach

                        <!-- Reset Filter -->
                        <a href="{{ route('admin.role.index') }}"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50">
                            Reset Filter
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Right button -->
        <div class="w-full sm:w-auto">
            <button type="button" onclick="location.href='{{ route('admin.role.create') }}'"3
                class="w-full sm:w-auto py-3 px-4 inline-flex justify-center items-center gap-x-2
               text-sm font-medium rounded-lg border border-transparent
               bg-blue-600 text-white hover:bg-blue-700">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Role
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
                                    Nama Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($roles as $item)
                                <tr class="hover:bg-gray-50">
                                    <!-- Name -->
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800 border-gray-200">
                                        {{ $item->name }}
                                    </td>

                                    <!-- Action -->
                                    <td class="px-6 py-4 text-start text-sm font-medium">
                                        <div class="inline-flex items-center gap-2">
                                            {{-- Detail --}}
                                            <a href="{{route('admin.role.show',$item->id)}}" class="text-gray-600 hover:text-blue-600 transition"
                                                title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.role.edit', $item->id) }}"
                                                class="text-gray-600 hover:text-yellow-600 transition" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
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
                                        Data role tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Wrapper -->
                    <div class="grid gap-3 px-4 py-3
                    sm:flex sm:items-center sm:justify-between">

                        <!-- Pagination -->
                        <nav class="flex justify-center sm:justify-start items-center gap-x-1" aria-label="Pagination">

                            <!-- Previous -->
                            <a href="{{ $roles->previousPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
   text-sm rounded-lg border border-transparent text-gray-800
   hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
   {{ $roles->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </a>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                @foreach ($roles->getUrlRange(max(1, $roles->currentPage() - 2), min($roles->lastPage(), $roles->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="min-h-9.5 min-w-9.5 flex justify-center items-center
           py-2 px-3 text-sm rounded-lg
           {{ $page == $roles->currentPage()
               ? 'border border-gray-200 text-gray-800 bg-gray-50'
               : 'border border-transparent text-gray-800 hover:bg-gray-100' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Next -->
                            <a href="{{ $roles->nextPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
   text-sm rounded-lg border border-transparent text-gray-800
   hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
   {{ $roles->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
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
