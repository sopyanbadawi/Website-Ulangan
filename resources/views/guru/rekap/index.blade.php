@extends('layouts.app-guru')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Rekapitulasi Akademik</h2>
            <p class="text-sm text-gray-500">Data nilai berdasarkan Mata Pelajaran dan Kelas yang Anda ampu.</p>
        </div>
        
        <form action="{{ route('guru.rekap.index') }}" method="GET" class="flex items-center gap-2">
            <select name="tahun_ajaran_id" onchange="this.form.submit()" 
                class="py-2 px-4 border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($listTahun as $ta)
                    <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                        TA {{ $ta->tahun }} - Semester {{ ucfirst($ta->semester) }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <hr class="border-gray-200">

    @forelse($rekapData as $namaMapel => $daftarKelas)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600 rounded-lg text-white">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18 18.247 18.477 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ $namaMapel }}</h3>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Mata Pelajaran</span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($daftarKelas as $namaKelas => $attempts)
                        <div class="group p-5 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md hover:border-blue-200 transition-all duration-200">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-gray-700 text-base">{{ $namaKelas }}</h4>
                                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold uppercase">
                                    {{ $attempts->count() }} Data Nilai
                                    @php
                                    $sample = $attempts->first(); 
                                    @endphp
                                </span>
                            </div>

                            <div class="flex items-end gap-1">
                                <span class="text-3xl font-black text-blue-600 leading-none">
                                    {{ number_format($attempts->avg('final_score'), 1) }}
                                </span>
                                <span class="text-xs text-gray-400 mb-1">Rata-rata Kelas</span>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('guru.rekap.detail-ujian', [
                                    'mata_pelajaran_id' => $sample->ujian->mata_pelajaran_id,
                                    'kelas_id' => $sample->kelas_id ]) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    Lihat Detail Ujian
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
    @empty
        <div class="flex flex-col items-center justify-center py-20 bg-white border border-dashed border-gray-300 rounded-2xl">
            <div class="p-4 bg-gray-50 rounded-full mb-4">
                <svg class="size-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Tidak ada data rekap</h3>
            <p class="text-gray-500 text-sm">Belum ada ujian yang selesai atau sesuai dengan filter Anda.</p>
        </div>
    @endforelse
</div>
@endsection