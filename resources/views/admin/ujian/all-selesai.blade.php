@extends('layouts.app')

@section('content')
    

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:max-w-lg mb-2">

        <!-- Search -->
        <form method="GET" class="relative w-full" id="searchForm">
            <input type="text" name="search" value="{{ request('search') }}"
                class="ps-11 py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
               focus:border-blue-500 focus:ring-blue-500"
                placeholder="search ujian..." id="searchInput">

            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">

            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </form>

        <div class="hs-dropdown [--auto-close:inside] relative inline-flex w-full sm:w-auto">

            <!-- Button -->
            <button type="button"
                class="hs-dropdown-toggle py-3 px-4 w-full sm:w-auto inline-flex justify-between items-center gap-x-2
                text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs
                hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50">

                Filter
                <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div
                class="hs-dropdown-menu transition-[opacity,margin] duration
                hs-dropdown-open:opacity-100 opacity-0 hidden
                min-w-60 bg-white shadow-md rounded-lg mt-2 z-30">

                <div class="p-3 space-y-3">

                    <!-- Filter Tahun Ajaran -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Tahun Ajaran</label>
                        <select onchange="location = this.value"
                            class="mt-1 block w-full text-sm rounded-lg border-gray-200">
                            <option value="{{ request()->fullUrlWithQuery(['tahun' => null, 'page' => 1]) }}">
                                Semua
                            </option>
                            @foreach ($tahunAjaranList as $ta)
                                <option value="{{ request()->fullUrlWithQuery(['tahun' => $ta->id, 'page' => 1]) }}"
                                    {{ request('tahun') == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->tahun }} ({{ $ta->semester }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Semester -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Semester</label>
                        <select onchange="location = this.value"
                            class="mt-1 block w-full text-sm rounded-lg border-gray-200">
                            <option value="{{ request()->fullUrlWithQuery(['semester' => null, 'page' => 1]) }}">
                                Semua
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['semester' => 'ganjil']) }}"
                                {{ request('semester') == 'ganjil' ? 'selected' : '' }}>
                                Ganjil
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['semester' => 'genap']) }}"
                                {{ request('semester') == 'genap' ? 'selected' : '' }}>
                                Genap
                            </option>
                        </select>
                    </div>

                    <!-- Reset -->
                    <a href="{{ route('admin.ujian.all_draft') }}"
                        class="flex items-center justify-center py-2 rounded-lg
                        text-sm text-red-600 hover:bg-red-50">
                        Reset Filter
                    </a>

                </div>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 border border-gray-200 shadow-2xs rounded-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-600 uppercase">
                Semua Ujian Selesai
            </h2>
        </div>

        {{-- LIST 1 KOLOM --}}
        <div class="grid grid-cols-1 gap-4">
            @forelse ($dataSelesai as $item)
                <div class="border rounded-xl p-4 hover:bg-gray-50 transition">

                    <div class="flex items-start gap-4">
                        {{-- ICON --}}
                        <div
                            class="w-14 h-14 rounded-lg bg-gray-100 text-gray-600
                            flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                        </div>

                        {{-- CONTENT --}}
                        <div class="flex-1">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                <h3 class="font-semibold text-gray-800">
                                    {{ $item->nama_ujian }}
                                </h3>

                                <span
                                    class="inline-flex items-center gap-x-1.5
                                    py-1 px-3 rounded-full text-xs font-medium
                                    bg-gray-100 text-gray-800
                                    sm:ml-2">
                                    <span class="size-1.5 rounded-full bg-gray-800"></span>
                                    selesai
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item->mataPelajaran->nama_mapel ?? '-' }} •
                                {{ $item->tahunAjaran->tahun ?? '-' }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($item->mulai_ujian)->format('d M Y H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($item->selesai_ujian)->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- ACTION BAR --}}
                    <div class="mt-2 pt-4 flex sm:justify-end">
                        <a href="{{ route('admin.ujian.hasil', $item->id) }}"
                            class="w-full sm:w-auto
                            h-12 sm:h-10
                            px-4 sm:px-5
                            inline-flex items-center justify-center gap-x-2
                            text-sm font-medium rounded-lg
                            bg-blue-100 text-blue-800
                            hover:bg-blue-200
                            focus:outline-hidden focus:bg-blue-200">
                            Lihat Hasil
                        </a>
                    </div>


                </div>
            @empty
                <div class="text-sm text-gray-400">
                    Tidak ada ujian selesai
                </div>
            @endforelse
        </div>


        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $dataSelesai->links() }}
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
