@extends('layouts.app')

@section('content')
    <!-- Header Info -->
    <div class="mb-6">
        <div class="overflow-hidden border border-gray-200 rounded-lg bg-white p-5">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $ujian->nama_ujian }}
                </h2>

                <p class="text-sm text-gray-600">
                    {{ $ujian->mataPelajaran->nama_mapel }}
                    |
                    {{ $ujian->tahunAjaran->tahun }}
                    ({{ ucfirst($ujian->tahunAjaran->semester) }})
                </p>

                <p class="text-sm text-gray-700">
                    Kelas {{ $kelas->nama_kelas }}
                </p>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:max-w-lg">

            <form method="GET" class="relative w-full" id="searchForm">
                <input type="text" name="search" value="{{ request('search') }}" id="searchInput"
                    class="ps-11 py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                    focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Search peserta...">

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

    <!-- Table -->
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden border border-gray-200 rounded-lg bg-white">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">NISN</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">IP</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($siswa as $item)
                                @php $attempt = $item->ujianAttempts->first(); @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        {{ $attempt?->nisn ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm">
                                        @if (!$attempt)
                                            <span class="px-3 py-1 rounded-lg text-xs bg-gray-100 text-gray-700">
                                                Belum Mulai
                                            </span>
                                        @elseif ($attempt->status === 'ongoing')
                                            <span class="px-3 py-1 rounded-lg text-xs bg-blue-100 text-blue-800">
                                                Ongoing
                                            </span>
                                        @elseif ($attempt->status === 'selesai')
                                            <span class="px-3 py-1 rounded-lg text-xs bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-lg text-xs bg-red-100 text-red-800">
                                                Terkunci
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm">
                                        {{ $attempt?->final_score ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-xs text-gray-600">
                                        {{ $attempt?->ip_address ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                        Tidak ada data siswa
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="grid gap-3 px-4 py-3 sm:flex sm:items-center sm:justify-between">

                        <!-- Pagination -->
                        <nav class="flex justify-center sm:justify-start items-center gap-x-1" aria-label="Pagination">

                            <!-- Previous -->
                            <a href="{{ $siswa->previousPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
                                text-sm rounded-lg border border-transparent text-gray-800
                                hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
                                {{ $siswa->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6"></path>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </a>

                            <!-- Page Numbers -->
                            <div class="flex items-center gap-x-1">
                                @foreach ($siswa->getUrlRange(max(1, $siswa->currentPage() - 2), min($siswa->lastPage(), $siswa->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="min-h-9.5 min-w-9.5 flex justify-center items-center
                                        py-2 px-3 text-sm rounded-lg
                                        {{ $page == $siswa->currentPage()
                                            ? 'border border-gray-200 text-gray-800 bg-gray-50 font-semibold'
                                            : 'border border-transparent text-gray-800 hover:bg-gray-100' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Next -->
                            <a href="{{ $siswa->nextPageUrl() ?? '#' }}"
                                class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center
                                text-sm rounded-lg border border-transparent text-gray-800
                                hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100
                                {{ $siswa->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
                                <span class="sr-only">Next</span>
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6"></path>
                                </svg>
                            </a>

                        </nav>
                        <!-- End Pagination -->

                        <!-- Page Size Dropdown -->
                        @php
                            $sizes = [5, 8, 10];
                            $currentSize = request('per_page', 5);
                        @endphp

                        <div
                            class="hs-dropdown relative [--placement:top-left] inline-flex justify-center sm:justify-start">

                            <button type="button"
                                class="py-1.5 px-2 inline-flex items-center gap-x-1
                                text-sm rounded-lg border border-gray-200 text-gray-800 shadow-2xs
                                hover:bg-gray-50 focus:outline-hidden focus:bg-gray-100">

                                {{ $currentSize }} page

                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m6 9 6 6 6-6"></path>
                                </svg>
                            </button>

                            <div
                                class="hs-dropdown-menu hs-dropdown-open:opacity-100 w-48 hidden z-50
                                transition-[margin,opacity] opacity-0 duration-300 mb-2
                                bg-white shadow-md rounded-lg p-1 space-y-0.5">

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

                    <!-- End Pagination -->

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
