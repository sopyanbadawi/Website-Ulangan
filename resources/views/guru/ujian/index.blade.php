@extends('layouts.app-guru')

@section('content')
    <div class="space-y-4">
        {{-- HEADER --}}
        <div class="bg-white p-6 border border-gray-200 shadow-2xs rounded-xl flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800"> Daftar Ujian</h1>

            <a href="{{ route('guru.ujian.create') }}"
                class="py-2 px-4 inline-flex items-center gap-x-2
                   text-sm font-medium rounded-lg
                   border border-blue-600 bg-blue-600
                   text-white hover:bg-blue-700">
                Tambah Ujian
            </a>
        </div>

        {{-- ================= DRAFT ================= --}}
        <div class="bg-white p-6 border border-gray-200 shadow-2xs rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-yellow-700 mb-4 uppercase">
                    Draft
                </h2>

                <a href="{{ route('guru.ujian.all_draft') }}"
                    class="text-xs font-medium text-yellow-700 hover:text-yellow-900
                              inline-flex items-center gap-1">
                    Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($ujianDraft as $item)
                    <div class="border rounded-xl p-4 hover:bg-gray-50 transition">

                        <div class="flex flex-col gap-4">

                            {{-- TOP --}}
                            <div class="flex items-start gap-4">
                                {{-- ICON --}}
                                <div
                                    class="w-14 h-14 rounded-lg bg-yellow-100 text-yellow-600
                                        flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                    </svg>
                                </div>

                                {{-- INFO --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-semibold text-gray-800">
                                            {{ $item->nama_ujian }}
                                        </h3>

                                        <span
                                            class="inline-flex items-center gap-x-1.5 py-1 px-3
                                               rounded-full text-xs font-medium
                                               bg-yellow-100 text-yellow-800">
                                            <span class="size-1.5 rounded-full bg-yellow-800"></span>
                                            Draft
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $item->mataPelajaran->nama_mapel ?? '-' }} •
                                        {{ $item->tahunAjaran->tahun ?? '-' }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($item->mulai_ujian)->format('d M Y H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($item->selesai_ujian)->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('guru.ujian.edit', $item->id) }}"
                                    class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-yellow-100 text-yellow-800 hover:bg-yellow-200 focus:outline-hidden focus:bg-yellow-200 disabled:opacity-50 disabled:pointer-events-none">
                                    Edit
                                </a>

                                <form action="{{ route('guru.ujian.destroy', $item->id) }}" method="POST"
                                    class="form-hapus-ujian">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg
                                             border border-transparent bg-red-100 text-red-800
                                             hover:bg-red-200 focus:outline-hidden focus:bg-red-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-sm text-gray-400">
                        Tidak ada ujian draft
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ================= AKTIF ================= --}}
        <div class="bg-white p-6 border border-gray-200 shadow-2xs rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-green-700 uppercase">
                    Aktif
                </h2>

                <a href="{{ route('guru.ujian.all_aktif') }}"
                    class="text-xs font-medium text-green-700 hover:text-green-900
                          inline-flex items-center gap-1">
                    Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($ujianAktif as $item)
                    <div class="border rounded-xl p-4 hover:bg-gray-50 transition">

                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-lg bg-green-100 text-green-600
                                    flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                </svg>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $item->nama_ujian }}
                                    </h3>

                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1 px-3
                                           rounded-full text-xs font-medium
                                           bg-green-100 text-green-800">
                                        <span class="size-1.5 rounded-full bg-green-800"></span>
                                        Aktif
                                    </span>
                                </div>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $item->mataPelajaran->nama_mapel ?? '-' }} •
                                    {{ $item->tahunAjaran->tahun ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($item->mulai_ujian)->format('d M Y H:i') }}
                                    –
                                    {{ \Carbon\Carbon::parse($item->selesai_ujian)->format('d M Y H:i') }}
                                </p>

                                <div class="flex justify-end mt-4">
                                    <a href="{{ route('guru.ujian.edit', $item->id) }}"
                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-yellow-600 text-white hover:bg-yellow-700 focus:outline-hidden focus:bg-yellow-700 disabled:opacity-50 disabled:pointer-events-none">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full text-sm text-gray-400">
                        Tidak ada ujian aktif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ================= SELESAI ================= --}}
        <div class="bg-white p-6 border border-gray-200 shadow-2xs rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-700 uppercase">
                    Draft
                </h2>

                <a href="{{ route('guru.ujian.all_selesai') }}"
                    class="text-xs font-medium text-gray-700 hover:text-gray-900
                          inline-flex items-center gap-1">
                    Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($ujianSelesai as $item)
                    <div class="border rounded-xl p-4 hover:bg-gray-50 transition">

                        <div class="flex items-start gap-4">
                            <div
                                class="w-14 h-14 rounded-lg bg-gray-100 text-gray-600
                                    flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                </svg>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $item->nama_ujian }}
                                    </h3>

                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1 px-3
                                           rounded-full text-xs font-medium
                                           bg-gray-100 text-gray-800">
                                        <span class="size-1.5 rounded-full bg-gray-600"></span>
                                        Selesai
                                    </span>
                                </div>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $item->mataPelajaran->nama_mapel ?? '-' }} •
                                    {{ $item->tahunAjaran->tahun ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($item->mulai_ujian)->format('d M Y H:i') }}
                                    –
                                    {{ \Carbon\Carbon::parse($item->selesai_ujian)->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full text-sm text-gray-400">
                        Tidak ada ujian selesai
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-hapus-ujian').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // ❗ stop submit langsung

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Ujian yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // ✅ submit form jika konfirmasi
                        }
                    });
                });
            });
        });
    </script>
@endsection
