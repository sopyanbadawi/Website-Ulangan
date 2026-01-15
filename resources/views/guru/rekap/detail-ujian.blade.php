@extends('layouts.app-guru')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header Utama --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('guru.rekap.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-blue-600 transition-all shadow-sm">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Ujian</h2>
                <p class="text-sm text-gray-500">Daftar semua ujian yang telah dilaksanakan untuk mata pelajaran ini.</p>
            </div>
        </div>
    </div>

    <hr class="border-gray-200">

    {{-- Container Utama (Mengikuti layout Rekap Anda) --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-8">
        {{-- Header Biru/Abu-abu di dalam Kartu --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600 rounded-lg text-white">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">
                        {{ $daftarUjian->first()->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}
                    </h3>
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Total Ujian: {{ request()->route('kelas_id') }}</p>
                </div>
            </div>
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Daftar Ujian</span>
        </div>

        <div class="p-6">
            {{-- Grid Kartu Kecil --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($daftarUjian as $ujian)
                    <div class="group p-5 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-blue-200 transition-all duration-200">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-bold text-gray-700 text-base">{{ $ujian->nama_ujian }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium">TA {{ $ujian->tahunAjaran->tahun }} • Sem. {{ ucfirst($ujian->tahunAjaran->semester) }}</p>
                            </div>
                            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold uppercase">
                                {{ $ujian->attempts_count }} Siswa
                            </span>
                        </div>

                        <div class="flex items-end gap-1">
                            <span class="text-3xl font-black text-blue-600 leading-none">
                                {{ number_format($ujian->attempts_avg_final_score, 1) }}
                            </span>
                            <span class="text-xs text-gray-400 mb-1">Rata-rata</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('guru.rekap.detail-siswa', [$ujian->id, $kelasId]) }}"
                               class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                Lihat Detail Nilai
                                <svg class="size-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection