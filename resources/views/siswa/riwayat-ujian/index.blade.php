@extends('layouts.app-siswa')

@section('content')
    {{-- TOP BAR (SEARCH + FILTER PLACEHOLDER, DISAMAKAN STRUKTUR) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:max-w-lg mb-2">

        <!-- Search -->
        <form method="GET" class="relative w-full" id="searchForm">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                class="ps-11 py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                focus:border-blue-500 focus:ring-blue-500"
                placeholder="search ujian..."
                id="searchInput">

            <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">

            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
        </form>

        {{-- SLOT KOSONG (BIAR GRID & JARAK SAMA PERSIS) --}}
        <div class="hidden sm:block"></div>
    </div>

    {{-- CARD WRAPPER --}}
    <div class="bg-white p-6 border border-gray-200 shadow-2xs rounded-xl hover:bg-gray-50 transition">

        {{-- LIST 1 KOLOM --}}
        <div class="grid grid-cols-1 gap-4">
            @forelse ($riwayatUjian as $attempt)
                <div>
                    <div class="flex items-start gap-4">
                        {{-- ICON --}}
                        <div
                            class="w-14 h-14 rounded-lg bg-gray-100 text-gray-600
                            flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                        </div>

                        {{-- CONTENT --}}
                        <div class="flex-1">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                <h3 class="font-semibold text-gray-800">
                                    {{ $attempt->ujian->nama_ujian }}
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
                                {{ $attempt->ujian->mataPelajaran->nama_mapel ?? '-' }}
                                • {{ $attempt->ujian->tahunAjaran->tahun ?? '-' }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                {{ $attempt->ujian->mulai_ujian->format('d M Y H:i') }}
                                –
                                {{ $attempt->ujian->selesai_ujian->format('d M Y H:i') }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                Kelas saat ujian:
                                <span class="font-medium text-gray-600">
                                    {{ $attempt->kelas->nama_kelas }}
                                </span>
                            </p>

                            {{-- NILAI --}}
                            <div class="mt-3">
                                <span class="text-xs text-gray-500">Nilai Akhir</span>
                                <div class="text-lg font-bold text-blue-700">
                                    {{ number_format($attempt->final_score, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-sm text-gray-400">
                    Tidak ada riwayat ujian
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $riwayatUjian->links() }}
        </div>
    </div>

    {{-- AUTO SEARCH --}}
    <script>
        let searchTimeout;

        document.getElementById('searchInput').addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 400);
        });
    </script>
@endsection
