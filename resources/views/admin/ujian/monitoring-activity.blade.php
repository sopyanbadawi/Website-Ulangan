@extends('layouts.app')

@section('content')
    {{-- ========================= --}}
    {{-- Header Info --}}
    {{-- ========================= --}}
    {{-- <div class="mb-4">
        <div class="flex flex-col gap-1">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ $ujian->nama_ujian }}
            </h2>
            <p class="text-sm text-gray-600">
                Kelas {{ $kelas->nama_kelas }} · {{ $siswa->name }}
            </p>
        </div>
    </div> --}}

    {{-- ========================= --}}
    {{-- Tabel Log Aktivitas --}}
    {{-- ========================= --}}
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden border border-gray-200 rounded-lg">

                    <table class="min-w-full border-collapse divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Waktu
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Event
                                </th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Detail
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    IP Address
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Waktu --}}
                                    <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </td>

                                    {{-- Event --}}
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $log->event }}
                                    </td>

                                    {{-- Detail --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $log->detail }}
                                    </td>

                                    {{-- IP --}}
                                    <td class="px-6 py-4 text-center text-xs text-gray-600">
                                        {{ $log->ip_address }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                                        Tidak ada log aktivitas
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
