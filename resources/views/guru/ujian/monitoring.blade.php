@extends('layouts.app-guru')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <!-- Left section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:max-w-lg">

            <!-- Search -->
            <form method="GET" class="relative w-full" id="searchForm">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="ps-11 py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                    focus:border-blue-500 focus:ring-blue-500"
                    placeholder="search ujian..." id="searchInput">

                <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">

                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </form>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden border border-gray-200 rounded-lg">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Nama Ujian
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Mata Pelajaran
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Tahun Ajaran
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Waktu
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Durasi
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Kelas
                                </th>
                                <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($ujians as $ujian)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $ujian->nama_ujian }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $ujian->mataPelajaran->nama_mapel ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $ujian->tahunAjaran->tahun ?? '-' }}
                                        - {{ $ujian->tahunAjaran->semester ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $ujian->mulai_ujian->format('H:i') }}
                                        -
                                        {{ $ujian->selesai_ujian->format('H:i') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $ujian->durasi }} menit
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg
                                            text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $ujian->kelas->count() }} kelas
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-end text-sm font-medium">
                                        <a href="{{ route('guru.ujian.monitoring-detail', $ujian->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            Detail
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Data ujian belum tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Wrapper -->
                    <div class="grid gap-3 px-4 py-3 sm:flex sm:items-center sm:justify-between">

                        <!-- Pagination -->
                        <nav class="flex justify-center sm:justify-start items-center gap-x-1" aria-label="Pagination">
                    
                            <!-- Previous -->
                            <a href="{{ $ujians->previousPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
                                text-sm rounded-lg border border-transparent text-gray-800
                                hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
                                {{ $ujians->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </a>
                    
                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                @foreach ($ujians->getUrlRange(
                                    max(1, $ujians->currentPage() - 2),
                                    min($ujians->lastPage(), $ujians->currentPage() + 2)
                                ) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="min-h-9.5 min-w-9.5 flex justify-center items-center
                                        py-2 px-3 text-sm rounded-lg
                                        {{ $page == $ujians->currentPage()
                                            ? 'border border-gray-200 text-gray-800 bg-gray-50'
                                            : 'border border-transparent text-gray-800 hover:bg-gray-100' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                            </div>
                    
                            <!-- Next -->
                            <a href="{{ $ujians->nextPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
                                text-sm rounded-lg border border-transparent text-gray-800
                                hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
                                {{ $ujians->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
                                <span class="sr-only">Next</span>
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </a>
                    
                        </nav>
                        <!-- End Pagination -->
                    
                        <!-- Page Size Dropdown -->
                        <div class="hs-dropdown relative [--placement:top-left]
                            inline-flex justify-center sm:justify-start">
                    
                            <button type="button"
                                class="py-1.5 px-2 inline-flex items-center gap-x-1
                                text-sm rounded-lg border border-gray-200 text-gray-800 shadow-2xs
                                hover:bg-gray-50 focus:outline-hidden focus:bg-gray-100">
                    
                                {{ request('per_page', 5) }} page
                    
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>
                    
                            @php
                                $sizes = [5, 8, 10];
                                $currentSize = request('per_page', 5);
                            @endphp
                    
                            <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 w-48 hidden z-50
                                transition-[margin,opacity] opacity-0 duration-300 mb-2
                                bg-white shadow-md rounded-lg p-1 space-y-0.5">
                    
                                @foreach ($sizes as $size)
                                    <a href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}"
                                        class="w-full flex items-center gap-x-3.5 py-2 px-3
                                        rounded-lg text-sm
                                        {{ $currentSize == $size
                                            ? 'text-gray-800 bg-gray-100 font-semibold'
                                            : 'text-gray-800 hover:bg-gray-100' }}">
                    
                                        {{ $size }} page
                    
                                        @if ($currentSize == $size)
                                            <svg class="ms-auto shrink-0 size-4 text-blue-600"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="none"
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
