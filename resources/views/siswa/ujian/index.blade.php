@extends('layouts.app-siswa')

@section('content')
    {{-- ================= HEADER (SAMA PERSIS) ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:max-w-lg">
            <form method="GET" class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="ps-11 py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Cari ujian...">

                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </form>
        </div>

        <div class="text-sm text-gray-600">
            Total Ujian: <b>{{ $ujians->count() }}</b>
        </div>
    </div>

    {{-- ================= DESKTOP TABLE ================= --}}
    <div class="hidden sm:block">
        <div class="overflow-hidden border border-gray-200 rounded-lg bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nama Ujian</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Mapel</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Durasi</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($ujians as $ujian)
                        @php $attempt = $ujian->attempts->first(); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $ujian->nama_ujian }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $ujian->mataPelajaran->nama_mapel ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $ujian->durasi }} menit
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $attempt && $attempt->isFinished() ? $attempt->final_score : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if (!$attempt)
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                        Belum Mulai
                                    </span>
                                @elseif ($attempt->isOngoing())
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Sedang Dikerjakan
                                    </span>
                                @elseif ($attempt->isFinished())
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                                        Terkunci
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-end">
                                @if (!$attempt)
                                    <a href="{{ route('siswa.ujian.start', $ujian->id) }}"
                                        class="py-2 px-3 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">
                                        Mulai
                                    </a>
                                @elseif ($attempt->isOngoing())
                                    <a href="{{ route('siswa.ujian.kerjakan', $attempt->id) }}"
                                        class="py-2 px-3 rounded-lg text-sm font-medium bg-yellow-500 text-white hover:bg-yellow-600">
                                        Lanjutkan
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">
                                Tidak ada ujian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= MOBILE CARD (NO SCROLL) ================= --}}
    <div class="sm:hidden space-y-4">
        @forelse ($ujians as $ujian)
            @php $attempt = $ujian->attempts->first(); @endphp

            <div class="border border-gray-200 rounded-lg p-4 bg-white space-y-2">
                <div class="font-semibold text-gray-800">
                    {{ $ujian->nama_ujian }}
                </div>

                <div class="text-sm text-gray-600">
                    {{ $ujian->mataPelajaran->nama_mapel ?? '-' }} • {{ $ujian->durasi }} menit
                </div>

                <div>
                    @if (!$attempt)
                        <span
                            class="inline-flex items-center py-1 px-2 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                            Belum Mulai
                        </span>
                    @elseif ($attempt->isOngoing())
                        <span
                            class="inline-flex items-center py-1 px-2 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800">
                            Sedang Dikerjakan
                        </span>
                    @elseif ($attempt->isFinished())
                        <span
                            class="inline-flex items-center py-1 px-2 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                            Selesai ({{ $attempt->final_score }})
                        </span>
                    @else
                        <span
                            class="inline-flex items-center py-1 px-2 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                            Terkunci
                        </span>
                    @endif
                </div>

                <div>
                    @if (!$attempt)
                        <a href="{{ route('siswa.ujian.start', $ujian->id) }}"
                            class="block text-center py-2 px-3 rounded-lg text-sm font-medium bg-blue-600 text-white">
                            Mulai
                        </a>
                    @elseif ($attempt->isOngoing())
                        <a href="{{ route('siswa.ujian.kerjakan', $attempt->id) }}"
                            class="block text-center py-2 px-3 rounded-lg text-sm font-medium bg-yellow-500 text-white">
                            Lanjutkan
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-sm text-gray-500">
                Tidak ada ujian
            </div>
        @endforelse
    </div>
@endsection
