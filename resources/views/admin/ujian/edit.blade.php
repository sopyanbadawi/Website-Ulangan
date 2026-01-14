@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6 max-w-5xl space-y-4">

        {{-- ================= ERROR ================= --}}
        @if ($errors->any())
            <div id="error-container"
                class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4"
                role="alert" tabindex="-1" aria-labelledby="error-alert-title">

                <div class="flex">
                    <div class="shrink-0">
                        <svg class="shrink-0 size-4 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m15 9-6 6"></path>
                            <path d="m9 9 6 6"></path>
                        </svg>
                    </div>

                    <div class="ms-4">
                        <h3 id="error-alert-title" class="text-sm font-semibold">
                            Terjadi kesalahan.
                        </h3>

                        <div class="mt-2 text-sm text-red-700">
                            <ul id="error-list" class="list-disc space-y-1 ps-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Hidden container untuk error JS --}}
            <div id="error-container"
                class="hidden
        bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4"
                role="alert" tabindex="-1" aria-labelledby="error-alert-title">

                <div class="flex">
                    <div class="shrink-0">
                        <svg class="shrink-0 size-4 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m15 9-6 6"></path>
                            <path d="m9 9 6 6"></path>
                        </svg>
                    </div>

                    <div class="ms-4">
                        <h3 id="error-alert-title" class="text-sm font-semibold">
                            Terjadi kesalahan.
                        </h3>

                        <div class="mt-2 text-sm text-red-700">
                            <ul id="error-list" class="list-disc space-y-1 ps-5"></ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form id="form-ujian" action="{{ route('admin.ujian.update', $ujian->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ================= DATA UJIAN ================= --}}
            <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6 mb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800 ">
                        Data Ujian
                    </h1>
                    <p class="text-sm text-gray-500">
                        Lengkapi data ujian
                    </p>
                </div>

                {{-- ================= FORM ================= --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

                    {{-- Nama Ujian --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">
                            Nama Ujian
                        </label>
                        <input type="text" name="nama_ujian" value="{{ old('nama_ujian', $ujian->nama_ujian) }}"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Durasi --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">
                            Durasi (menit)
                        </label>
                        <input type="number" name="durasi" value="{{ old('durasi', $ujian->durasi) }}"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">
                            Tahun Ajaran
                        </label>
                        <select name="tahun_ajaran_id"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($tahunAjaran as $ta)
                                <option value="{{ $ta->id }}"
                                    {{ $ujian->tahun_ajaran_id == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">
                            Mata Pelajaran
                        </label>
                        <select name="mata_pelajaran_id"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500">
                            @foreach ($mapel as $m)
                                <option value="{{ $m->id }}"
                                    {{ $ujian->mata_pelajaran_id == $m->id ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mulai --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">
                            Mulai Ujian
                        </label>
                        <input type="datetime-local" name="mulai_ujian"
                            value="{{ $ujian->mulai_ujian->format('Y-m-d\TH:i') }}"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Selesai --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">
                            Selesai Ujian
                        </label>
                        <input type="datetime-local" name="selesai_ujian"
                            value="{{ $ujian->selesai_ujian->format('Y-m-d\TH:i') }}"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                {{-- ================= KELAS ================= --}}
                <div class="mt-8 space-y-6">
                    <label class="block text-sm font-medium ">
                        Kelas
                    </label>

                    @foreach (['X', 'XI', 'XII'] as $tingkat)
                        <div>
                            {{-- All Kelas --}}
                            <div class="flex items-center gap-6 mb-3">
                                <div class="flex">
                                    <input type="checkbox" onclick="toggleKelas('{{ $tingkat }}')"
                                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600
                                    focus:ring-blue-500">
                                    <label class="text-sm text-gray-500 ms-3">
                                        All Kelas {{ $tingkat }}
                                    </label>
                                </div>
                            </div>

                            {{-- List Kelas --}}
                            <div class="flex flex-wrap gap-6">
                                @foreach ($kelas->filter(fn($k) => Str::startsWith($k->nama_kelas, $tingkat . ' ')) as $k)
                                    <div class="flex">
                                        <input type="checkbox" name="kelas_id[]" value="{{ $k->id }}"
                                            data-tingkat="{{ $tingkat }}"
                                            {{ $ujian->kelas->contains($k->id) ? 'checked' : '' }}
                                            class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600
                                        focus:ring-blue-500">
                                        <label class="text-sm text-gray-500 ms-3">
                                            {{ $k->nama_kelas }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ================= BAGIAN : BATASI AKSES JARINGAN ================= --}}
            <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6 mb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800 ">
                        Batasi Akses Jaringan
                    </h1>
                    <p class="text-sm text-gray-500">
                        Masukkan IP atau range jaringan
                    </p>
                </div>

                {{-- ===== LIST IP ===== --}}
                <div id="ip-wrapper" class="mt-4 space-y-3">
                    @foreach ($ujian->ipWhitelist as $ip)
                        <div class="flex items-end gap-3">

                            <div class="w-full">
                                <label class="block text-sm font-medium mb-2 ">
                                    IP Address / Range
                                </label>

                                <input type="text" name="ip_address[]" value="{{ $ip->ip_address }}"
                                    placeholder="Contoh: 192.168.1.1 / 192.168.1.0/24"
                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                                           focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <button type="button" onclick="this.closest('.flex').remove()"
                                class="text-red-600 hover:text-red-700 mb-2" title="Hapus IP">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>

                        </div>
                    @endforeach
                </div>


                {{-- ===== TAMBAH IP ===== --}}
                <button type="button" onclick="addIp()"
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg
        border border-gray-200 bg-white text-gray-800 shadow-2xs
        hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50
        disabled:opacity-50 disabled:pointer-events-none mt-2">
                    + Tambah IP
                </button>
            </div>


            {{-- ================= SOAL ================= --}}
            <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6 mb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800 ">
                        Buat Pertanyaan
                    </h1>
                    <p class="text-sm text-gray-500">
                        Silahkan buat pertanyaan serta opsi jawaban
                    </p>
                </div>

                <p id="sisa-bobot" class="text-sm font-semibold mb-4 mt-4">
                    Sisa Bobot: 100
                </p>

                <div id="soal-wrapper" class="space-y-4">

                    @foreach ($ujian->soal as $i => $soal)
                        <div class="soal-item border rounded-xl p-4">
                            {{-- HEADER SOAL --}}
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-semibold">
                                    Soal {{ $i + 1 }}
                                </h3>

                                <button type="button" onclick="hapusSoal(this)" class="text-red-600 hover:text-red-700"
                                    data-id="{{ $soal->id }}" title="Hapus soal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>

                            {{-- ID SOAL --}}
                            <input type="hidden" name="soal[{{ $i }}][id]" value="{{ $soal->id }}">

                            {{-- PERTANYAAN --}}
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-2 ">
                                    Pertanyaan
                                </label>
                                <textarea name="soal[{{ $i }}][pertanyaan]"
                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                                    focus:border-blue-500 focus:ring-blue-500
        ">{{ $soal->pertanyaan }}</textarea>
                            </div>

                            {{-- GAMBAR PERTANYAAN --}}
                            <div class="mb-4">
                                @if ($soal->pertanyaan_gambar)
                                    <img src="{{ asset($soal->pertanyaan_gambar) }}" class="h-32 rounded mb-2">
                                @endif

                                <input type="file" name="soal[{{ $i }}][pertanyaan_gambar]"
                                    class="block w-full text-sm text-gray-500
                                    file:me-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-600 file:text-white
                                    hover:file:bg-blue-700">
                            </div>

                            {{-- BOBOT --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2 ">
                                    Bobot
                                </label>
                                <input type="number" name="soal[{{ $i }}][bobot]"
                                    value="{{ $soal->bobot }}" oninput="hitungTotalBobot()"
                                    class="bobot-input py-2.5 sm:py-3 px-4 block w-40 border-gray-200 rounded-lg sm:text-sm
                                    focus:border-blue-500 focus:ring-blue-500
        ">
                            </div>

                            {{-- OPSI --}}
                            <div class="opsi-wrapper space-y-3 mb-3">
                                @foreach ($soal->opsiJawaban as $j => $opsi)
                                    <div class="opsi-item border rounded-xl p-3 flex gap-3 items-start">

                                        <!-- Radio jawaban benar -->
                                        <input type="radio" name="soal[{{ $i }}][correct]"
                                            value="{{ $j }}" {{ $opsi->is_correct ? 'checked' : '' }}
                                            class="mt-2">

                                        <div class="flex-1 space-y-1">

                                            <!-- Hidden ID opsi (untuk edit) -->
                                            <input type="hidden"
                                                name="soal[{{ $i }}][opsi][{{ $j }}][id]"
                                                value="{{ $opsi->id }}">

                                            <!-- Teks Opsi -->
                                            <div>
                                                <label class="block text-sm font-medium mb-1">
                                                    Opsi {{ $j + 1 }}
                                                </label>
                                                <input type="text"
                                                    name="soal[{{ $i }}][opsi][{{ $j }}][teks]"
                                                    value="{{ $opsi->opsi }}"
                                                    placeholder="Masukkan teks opsi {{ $j + 1 }}"
                                                    class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                                                           focus:border-blue-500 focus:ring-blue-500">
                                            </div>

                                            <!-- Gambar Opsi -->
                                            <div>
                                                <label class="block text-sm font-medium mb-1">
                                                    Gambar Opsi {{ $j + 1 }} (opsional)
                                                </label>

                                                @if ($opsi->opsi_gambar)
                                                    <img src="{{ asset($opsi->opsi_gambar) }}" class="h-20 rounded mb-2">
                                                @endif

                                                <input type="file"
                                                    name="soal[{{ $i }}][opsi][{{ $j }}][gambar]"
                                                    class="block w-full text-sm text-gray-500
                                                           file:me-4 file:py-2 file:px-4
                                                           file:rounded-lg file:border-0
                                                           file:text-sm file:font-semibold
                                                           file:bg-blue-600 file:text-white
                                                           hover:file:bg-blue-700
                                                           file:disabled:opacity-50 file:disabled:pointer-events-none">
                                            </div>
                                        </div>

                                        <!-- Hapus opsi -->
                                        <button type="button" onclick="hapusOpsi(this)"
                                            class="text-red-600 hover:text-red-700 mt-2" title="Hapus opsi">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>

                                    </div>
                                @endforeach
                            </div>


                            <button type="button" onclick="addOpsi(this, {{ $i }})"
                                class="py-1.5 px-3 inline-flex items-center gap-x-1.5
                            text-xs font-medium rounded-md
                            bg-blue-100 text-blue-800
                            hover:bg-blue-200
                            focus:outline-hidden focus:bg-blue-200
                            disabled:opacity-50 disabled:pointer-events-none">
                                + Tambah Opsi
                            </button>
                        </div>
                    @endforeach
                </div>

                <button id="btn-tambah-soal" type="button" onclick="addSoal()"
                    class="py-2 px-3 rounded-lg border border-green-600 text-green-600 mt-4">
                    + Tambah Soal
                </button>
            </div>


            <div class="flex justify-end gap-3">
                <button class="bg-blue-600 text-white px-5 py-3 rounded-lg">
                    Update Ujian
                </button>
                <button type="button" onclick="window.history.back()" class="border px-5 py-3 rounded-lg">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <script>
        let soalIndex = {{ count($ujian->soal) }};
        let totalBobot = 0;
        const maxBobot = 100;

        /* ================= HITUNG BOBOT ================= */
        function hitungTotalBobot() {
            totalBobot = 0;

            document.querySelectorAll('.bobot-input').forEach(input => {
                totalBobot += parseInt(input.value) || 0;
            });

            const sisa = maxBobot - totalBobot;
            const el = document.getElementById('sisa-bobot');
            el.innerText = `Sisa Bobot: ${sisa}`;

            if (sisa === 0) {
                el.classList.add('text-red-600');
                document.getElementById('btn-tambah-soal').disabled = true;
                document.getElementById('btn-tambah-soal')
                    .classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                el.classList.remove('text-red-600');
                document.getElementById('btn-tambah-soal').disabled = false;
                document.getElementById('btn-tambah-soal')
                    .classList.remove('opacity-50', 'cursor-not-allowed');
            }

            return totalBobot;
        }

        /* ================= REINDEX SOAL ================= */
        function reindexSoal() {
            document.querySelectorAll('.soal-item').forEach((soal, i) => {
                soal.dataset.index = i;
                soal.querySelector('h3').innerText = `Soal ${i + 1}`;

                soal.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/soal\[\d+\]/, `soal[${i}]`);
                });
            });

            soalIndex = document.querySelectorAll('.soal-item').length;
            hitungTotalBobot();
        }

        /* ================= TAMBAH SOAL ================= */
        function addSoal() {
            if (totalBobot >= maxBobot) return;

            const html = `
            <div class="soal-item border rounded-xl p-4 mb-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold">Soal ${soalIndex + 1}</h3>

                    <button
                        type="button"
                        onclick="hapusSoal(this)"
                        class="text-red-600 hover:text-red-700"
                        title="Hapus soal"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>

                <!-- Pertanyaan -->
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-2 ">
                        Pertanyaan
                    </label>
                    <textarea
                        name="soal[${soalIndex}][pertanyaan]"
                        placeholder="Masukkan pertanyaan"
                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500"
                    ></textarea>
                </div>

                <!-- Upload Gambar -->
                <div class="max-w-full mb-4">
                    <label class="block mb-2 text-sm font-medium ">
                        Upload gambar soal
                    </label>
                    <input
                        type="file"
                        name="soal[${soalIndex}][pertanyaan_gambar]"
                        accept="image/png,image/jpeg"
                        onchange="validateImage(this)"
                        class="block w-full text-sm text-gray-500
                        file:me-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600 file:text-white
                        hover:file:bg-blue-700"
                    >
                </div>

                <!-- Bobot -->
                <div class="max-w-full mb-4">
                    <label class="block text-sm font-medium mb-2 ">
                        Bobot
                    </label>
                    <input
                        type="number"
                        min="1"
                        name="soal[${soalIndex}][bobot]"
                        oninput="hitungTotalBobot()"
                        placeholder="Bobot soal"
                        class="bobot-input py-2.5 sm:py-3 px-4 block w-40 border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>

                <!-- Opsi -->
                <div class="opsi-wrapper space-y-2 mb-3"></div>

                <button
                    type="button"
                    onclick="addOpsi(this, ${soalIndex})"
                    class="py-1.5 px-3 inline-flex items-center gap-x-1.5
                        text-xs font-medium rounded-md
                        bg-blue-100 text-blue-800
                        hover:bg-blue-200"
                >
                    + Tambah Opsi
                </button>
            </div>
                `;

            document.getElementById('soal-wrapper')
                .insertAdjacentHTML('beforeend', html);

            soalIndex++;
        }


        /* ================= HAPUS SOAL ================= */
        function hapusSoal(btn) {
            const soalItem = btn.closest('.soal-item');
            const soalId = btn.dataset.id; // ⬅️ bisa undefined (soal baru)

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Soal yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;

                // =========================
                // 🔹 SOAL BARU (BELUM KE DB)
                // =========================
                if (!soalId) {
                    soalItem.remove();
                    hitungTotalBobot?.();
                    reindexSoal?.();

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: 'Soal berhasil dihapus.',
                        timer: 1000,
                        showConfirmButton: false
                    });
                    return;
                }

                // =========================
                // 🔥 SOAL DARI DATABASE
                // =========================
                fetch(`{{ route('admin.ujian.soal.destroy', ':id') }}`.replace(':id', soalId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) throw res;

                        soalItem.remove();
                        hitungTotalBobot?.();
                        reindexSoal?.();

                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message || 'Terjadi kesalahan'
                        });
                    });
            });
        }


        /* ================= TAMBAH OPSI ================= */
        function addOpsi(btn, idx) {
            const wrap = btn.previousElementSibling;
            const i = wrap.children.length;

            wrap.insertAdjacentHTML('beforeend', `
            <div class="opsi-item border rounded-xl p-3 flex gap-3 items-start">
                
                <!-- Radio jawaban benar -->
                <input
                    type="radio"
                    name="soal[${idx}][correct]"
                    value="${i}"
                    class="mt-2"
                >

                <div class="flex-1 space-y-3">
                    <!-- Teks Opsi -->
                    <div>
                        <label class="block text-sm font-medium mb-1 ">
                            Opsi ${i + 1}
                        </label>
                        <input
                            type="text"
                            name="soal[${idx}][opsi][${i}][teks]"
                            placeholder="Masukkan teks opsi ${i + 1}"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <!-- Gambar Opsi -->
                    <div>
                        <label class="block text-sm font-medium mb-1 ">
                            Gambar Opsi ${i + 1} (opsional)
                        </label>
                        <input
                            type="file"
                            name="soal[${idx}][opsi][${i}][gambar]"
                            accept="image/png,image/jpeg"
                            onchange="validateImage(this)"
                            class="block w-full text-sm text-gray-500
                            file:me-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-600 file:text-white
                            hover:file:bg-blue-700
                            file:disabled:opacity-50 file:disabled:pointer-events-none">
                    </div>
                </div>

                <!-- Hapus opsi -->
                <button
                type="button"
                onclick="hapusOpsi(this)"
                class="text-red-600 hover:text-red-700 mt-2"
                title="Hapus opsi"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>

            </div>
            `);
        }


        /* ================= HAPUS OPSI ================= */
        function hapusOpsi(btn) {
            const wrapper = btn.closest('.opsi-wrapper');
            btn.closest('.opsi-item').remove();

            // reindex opsi & radio value
            wrapper.querySelectorAll('.opsi-item').forEach((opsi, i) => {
                opsi.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name
                        .replace(/\[opsi\]\[\d+\]/, `[opsi][${i}]`);
                });

                const radio = opsi.querySelector('input[type="radio"]');
                if (radio) radio.value = i;
            });
        }

        function addIp() {
            document.getElementById('ip-wrapper').insertAdjacentHTML('beforeend', `
            <div class="flex items-end gap-3 mt-3">
                <div class="w-full">
                    <label class="block text-sm font-medium mb-2">
                        IP Address / Range
                    </label>
                    <input
                        type="text"
                        name="ip_address[]"
                        placeholder="Contoh: 192.168.1.1 / 192.168.1.0/24"
                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                        focus:border-blue-500 focus:ring-blue-500
                        disabled:opacity-50 disabled:pointer-events-none"
                    >
                </div>

                <button
                    type="button"
                    onclick="this.closest('.flex').remove()"
                    class="text-red-600 hover:text-red-700 mb-2"
                    title="Hapus"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </div>
                `);
        }

        /* ================= VALIDASI SUBMIT ================= */
        document.getElementById('form-ujian').addEventListener('submit', function(e) {
            const totalBobot = hitungTotalBobot(); // pastikan fungsi ini ada
            const errorContainer = document.getElementById('error-container');
            const errorList = document.getElementById('error-list');

            // Reset error
            errorList.innerHTML = '';
            errorContainer.classList.add('hidden');

            if (totalBobot !== 100) {
                e.preventDefault();

                // Tambah error ke list
                const li = document.createElement('li');
                li.textContent = 'Total bobot seluruh soal harus berjumlah 100.';
                errorList.appendChild(li);

                // Tampilkan error box
                errorContainer.classList.remove('hidden');

                // Scroll ke atas biar langsung kelihatan
                errorContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        function validateImage(input) {
            const file = input.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(file.type)) {
                alert('Gambar harus berformat PNG atau JPG.');
                input.value = '';
                return;
            }

            if (file.size > maxSize) {
                alert('Ukuran gambar maksimal 2MB.');
                input.value = '';
                return;
            }
        }

        hitungTotalBobot();
    </script>

@endsection
