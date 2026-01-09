@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ACTION BAR --}}
        <div class="flex justify-end">
            <a href="{{ route('admin.ujian.export_excel', $ujian->id) }}"
                class="w-full sm:w-auto
                            h-12 sm:h-10
                            px-4 sm:px-5
                            inline-flex items-center justify-center gap-x-2
                            text-sm font-semibold rounded-lg
                            bg-green-100 text-green-800
                            hover:bg-green-200
                            focus:outline-hidden focus:bg-green-200">

                {{-- ICON --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Excel
            </a>
        </div>

        {{-- GRID KELAS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($kelasList as $kelas)
                <a href="{{ route('admin.ujian.hasil-detail', [$ujian->id, $kelas->id]) }}"
                    class="border rounded-xl p-4
                          flex items-center justify-between
                          hover:bg-gray-100 transition group">

                    {{-- LEFT --}}
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            Kelas {{ $kelas->nama_kelas }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $kelas->peserta_count ?? 0 }} Peserta
                        </p>
                    </div>

                    {{-- RIGHT ICON --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5 text-gray-400
                                group-hover:text-gray-700 transition">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @endforeach
        </div>

    </div>
@endsection
