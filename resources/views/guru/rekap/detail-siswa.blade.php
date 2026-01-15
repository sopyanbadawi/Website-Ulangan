@extends('layouts.app-guru')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header Utama --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            {{-- Mengarahkan kembali ke Daftar Ujian sesuai alur Controller --}}
            <a href="{{ route('guru.rekap.detail-ujian', ['mata_pelajaran_id' => $ujian->mata_pelajaran_id, 'kelas_id' => $kelasId]) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-blue-600 transition-all shadow-sm">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Hasil Peserta</h2>
                <p class="text-sm text-gray-500">Laporan nilai siswa untuk keperluan administrasi dan rekapitulasi.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Tombol Export Excel --}}
            <a href="{{ route('guru.rekap.export_excel', $ujian->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition-all shadow-sm">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    <hr class="border-gray-200">

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-8">
        {{-- Header Internal Kartu --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600 rounded-lg text-white">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg leading-tight">
                        {{ $ujian->nama_ujian }}
                    </h3>
                    <p class="text-[11px] text-blue-600 font-bold uppercase tracking-wider mt-0.5">
                        {{ $ujian->mataPelajaran->nama_mapel ?? '-' }} • Kelas {{ $namaKelas }}
                    </p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Tahun Ajaran</span>
                <span class="text-xs font-bold text-gray-600 uppercase">{{ $ujian->tahunAjaran->tahun }} ({{ ucfirst($ujian->tahunAjaran->semester) }})</span>
            </div>
        </div>

        {{-- Isi Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-12 text-center">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-1/3">Nama Peserta</th>
                        {{-- NISN/Username disesuaikan dengan kolom Excel --}}
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Username / NISN</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswaNilai as $index => $item)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 text-xs text-gray-400 text-center font-medium">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                                {{ $item->user->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-gray-400 tabular-nums">
                                {{ $item->user->username ?? $item->nisn ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{-- Batas kelulusan (75) disesuaikan dengan standar umum sekolah --}}
                            @php $isLulus = $item->final_score >= 75; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $isLulus ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $isLulus ? 'Selesai' : 'Remedial' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            {{-- Nilai menggunakan font merah jika remedial, sesuai logika Excel --}}
                            <span class="text-sm font-black tabular-nums {{ $isLulus ? 'text-gray-800' : 'text-red-600' }}">
                                {{ number_format($item->final_score, 1) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 text-sm italic">
                            Belum ada data nilai terkumpul untuk ujian ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer --}}
        <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-100 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <span>Total Peserta: {{ $siswaNilai->count() }} Siswa</span>
            <span>Rata-rata Kelas: {{ number_format($siswaNilai->avg('final_score'), 1) }}</span>
        </div>
    </div>
</div>
@endsection