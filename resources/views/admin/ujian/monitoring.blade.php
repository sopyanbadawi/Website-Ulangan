@extends('layouts.app')

@section('content')

<div class="flex flex-col gap-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">
            Daftar Ujian
        </h2>

        {{-- Optional: tombol tambah ujian --}}
        {{-- 
        <a href="{{ route('admin.ujian.create') }}"
           class="py-2 px-4 inline-flex items-center gap-x-2
                  text-sm font-medium rounded-lg
                  bg-blue-600 text-white hover:bg-blue-700">
            + Tambah Ujian
        </a>
        --}}
    </div>

    {{-- Table --}}
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
                            {{-- Optional Action --}}
                            {{--
                            <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                Action
                            </th>
                            --}}
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

                                {{-- Action --}}
                                
                                <td class="px-6 py-4 text-end text-sm font-medium">
                                    <a href="{{ route('admin.ujian.monitoring-detail', $ujian->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        Detail
                                    </a>
                                </td>
                               
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 text-center text-sm text-gray-500">
                                    Data ujian belum tersedia
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
