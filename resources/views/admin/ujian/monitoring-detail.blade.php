@extends('layouts.app')

@section('content')

    {{-- ========================= --}}
    {{-- Header Ujian --}}
    {{-- ========================= --}}
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
                    {{ $ujian->mulai_ujian->format('d M Y H:i') }}
                    -
                    {{ $ujian->selesai_ujian->format('H:i') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- Tabel Kelas Peserta --}}
    {{-- ========================= --}}
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden border border-gray-200 rounded-lg">

                    <table class="min-w-full border-collapse divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Kelas
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Jumlah Siswa
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($ujian->kelas as $kelas)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Nama Kelas --}}
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $kelas->nama_kelas }}
                                    </td>

                                    {{-- Jumlah Siswa --}}
                                    <td class="px-6 py-4 text-center text-sm text-gray-800">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg
                                                   text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $kelas->siswa->count() }} siswa
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center text-sm font-medium">
                                        <a href="{{ route('admin.ujian.monitoring-kelas', [$ujian->id, $kelas->id]) }}"
                                           class="inline-flex items-center px-3 py-1.5
                                                  text-xs font-medium rounded-lg
                                                  bg-blue-600 text-white hover:bg-blue-700 transition">
                                            Detail Kelas
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-6 text-center text-sm text-gray-500">
                                        Tidak ada kelas terdaftar
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection
