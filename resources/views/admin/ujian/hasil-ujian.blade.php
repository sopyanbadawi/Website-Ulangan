@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    @foreach ($kelasList as $kelas)
        <a href="{{ route('admin.ujian.hasil-detail', [$ujian->id, $kelas->id]) }}"
           class="border rounded-xl p-4
           flex items-center justify-between
           hover:bg-gray-100 transition group">

            {{-- LEFT CONTENT --}}
            <div>
                <h3 class="font-semibold text-gray-800">
                    Kelas {{ $kelas->nama_kelas }}
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $kelas->peserta_count ?? '' }} Peserta
                </p>
            </div>

            {{-- RIGHT ICON --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor"
                class="size-5 text-gray-400
                group-hover:text-gray-700
                transition">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>

        </a>
    @endforeach

</div>
@endsection
