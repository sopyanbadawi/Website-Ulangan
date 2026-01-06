@extends('layouts.app')

@section('content')

    {{-- ========================= --}}
    {{-- Header Info --}}
    {{-- ========================= --}}
    <div class="mb-4">
        <div class="flex flex-col gap-1">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $ujian->nama_ujian }}
            </h2>
            <p class="text-sm text-gray-600">
                Kelas {{ $kelas->nama_kelas }}
            </p>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- Tabel Monitoring Siswa --}}
    {{-- ========================= --}}
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden border border-gray-200 rounded-lg">

                    <table class="min-w-full border-collapse divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Nama
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    NISN
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Nilai
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    IP
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($siswa as $item)
                                @php
                                    $attempt = $item->ujianAttempts->first();
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Nama --}}
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->name }}
                                    </td>

                                    {{-- NISN --}}
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ $attempt?->nisn ?? '-' }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 text-center text-sm">
                                        @if (!$attempt)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-lg
                                                       text-xs font-medium bg-gray-100 text-gray-800">
                                                Belum Mulai
                                            </span>
                                        @elseif ($attempt->status === 'ongoing')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-lg
                                                       text-xs font-medium bg-blue-100 text-blue-800">
                                                Ongoing
                                            </span>
                                        @elseif ($attempt->status === 'selesai')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-lg
                                                       text-xs font-medium bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @elseif ($attempt->status === 'lock')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-lg
                                                       text-xs font-medium bg-red-100 text-red-800">
                                                Terkunci
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Nilai --}}
                                    <td class="px-6 py-4 text-center text-sm text-gray-800">
                                        {{ $attempt?->final_score ?? '-' }}
                                    </td>

                                    {{-- IP --}}
                                    <td class="px-6 py-4 text-center text-xs text-gray-600">
                                        {{ $attempt?->ip_address ?? '-' }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center text-sm font-medium space-x-1">
                                        @if ($attempt)
                                            {{-- Button Aktivitas --}}
                                            <a href="{{ route('admin.ujian.monitoring-activity', [$ujian->id, $kelas->id, $attempt->id]) }}"
                                                class="inline-flex items-center px-3 py-1.5
                                                      text-xs font-medium rounded-lg
                                                      bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                                Aktivitas
                                            </a>

                                            {{-- Button UNLOCK (HANYA JIKA LOCK) --}}
                                            @if ($attempt->status === 'lock')
                                                <form
                                                    action="{{ route('admin.ujian.monitoring-unlock', [$ujian->id, $kelas->id, $attempt->id]) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Buka kembali ujian siswa ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5
                                                               text-xs font-medium rounded-lg
                                                               bg-red-600 text-white hover:bg-red-700 transition">
                                                        Unlock
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">
                                        Tidak ada siswa
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
