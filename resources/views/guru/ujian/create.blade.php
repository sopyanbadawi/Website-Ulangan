@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6">

        {{-- ================= ERROR ================= --}}
        @if ($errors->any())
            <div id="error-container" class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4"
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

                        <div class="mt-2 text-sm text-red-700 ">
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



        <form id="form-ujian" action="{{ route('guru.ujian.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ================= BAGIAN 1 : DATA UJIAN ================= --}}
            <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6 mb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">
                        Data Ujian
                    </h1>
                    <p class="text-sm text-gray-500">
                        Lengkapi data ujian
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

                    {{-- Nama Ujian --}}
                    <div class="w-full">
                        <label class="block text-sm font-medium mb-2 ">Nama Ujian</label>
                        <input type="text" name="nama_ujian"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Durasi --}}
                    <div class="max-w-full">
                        <label class="block text-sm font-medium mb-2 ">Durasi (menit)</label>
                        <input type="number" name="durasi"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="max-w-full">
                        <label class="block text-sm font-medium mb-2 ">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih --</option>
                            @if ($tahunAjaran)
                                <option value="{{ $tahunAjaran->id }}" selected>
                                    {{ $tahunAjaran->tahun }}
                                </option>
                            @endif
                        </select>
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="max-w-full">
                        <label class="block text-sm font-medium mb-2 ">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach ($mapel as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mulai --}}
                    <div class="max-w-full">
                        <label class="block text-sm font-medium mb-2 ">Mulai Ujian</label>
                        <input type="datetime-local" name="mulai_ujian"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Selesai --}}
                    <div class="max-w-full">
                        <label class="block text-sm font-medium mb-2 ">Selesai Ujian</label>
                        <input type="datetime-local" name="selesai_ujian"
                            class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm
                            focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                {{-- ================= KELAS ================= --}}
                <div class="mt-8 space-y-6">
                    <label class="block text-sm font-medium">Kelas</label>

                    {{-- ================= KELAS X ================= --}}
                    <div>
                        <div class="flex items-center gap-6 mb-3">
                            <div class="flex">
                                <input type="checkbox" onclick="toggleKelas('X', this)"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500">
                                <label class="text-sm text-gray-500 ms-3">
                                    All Kelas X
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-6">
                            @foreach ($kelasX as $k)
                                <div class="flex">
                                    <input type="checkbox" name="kelas_id[]" value="{{ $k->id }}" data-tingkat="X"
                                        class="kelas-checkbox shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500">
                                    <label class="text-sm text-gray-500 ms-3">
                                        {{ $k->nama_kelas }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ================= KELAS XI ================= --}}
                    <div>
                        <div class="flex items-center gap-6 mb-3">
                            <div class="flex">
                                <input type="checkbox" onclick="toggleKelas('XI', this)"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500">
                                <label class="text-sm text-gray-500 ms-3">
                                    All Kelas XI
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-6">
                            @foreach ($kelasXI as $k)
                                <div class="flex">
                                    <input type="checkbox" name="kelas_id[]" value="{{ $k->id }}"
                                        data-tingkat="XI"
                                        class="kelas-checkbox shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500">
                                    <label class="text-sm text-gray-500 ms-3">
                                        {{ $k->nama_kelas }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ================= KELAS XII ================= --}}
                    <div>
                        <div class="flex items-center gap-6 mb-3">
                            <div class="flex">
                                <input type="checkbox" onclick="toggleKelas('XII', this)"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500">
                                <label class="text-sm text-gray-500 ms-3">
                                    All Kelas XII
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-6">
                            @foreach ($kelasXII as $k)
                                <div class="flex">
                                    <input type="checkbox" name="kelas_id[]" value="{{ $k->id }}"
                                        data-tingkat="XII"
                                        class="kelas-checkbox shrink-0 mt-0.5 border-gray-200 rounded-sm text-blue-600 focus:ring-blue-500">
                                    <label class="text-sm text-gray-500 ms-3">
                                        {{ $k->nama_kelas }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            {{-- ================= BAGIAN 2 : IP ================= --}}
            <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6 mb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">
                        Batasi Akses Jaringan
                    </h1>
                    <p class="text-sm text-gray-500">
                        Masukkan IP atau range jaringan
                    </p>
                </div>

                <div id="ip-wrapper"></div>

                <button type="button" onclick="addIp()"
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none mt-4">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah IP
                </button>
            </div>

            {{-- ================= BAGIAN 3 : SOAL ================= --}}
            <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6 mb-4">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">
                        Buat Pertanyaan
                    </h1>
                    <p class="text-sm text-gray-500">
                        Silahkan buat pertanyaan serta opsi jawaban
                    </p>
                </div>

                <p id="sisa-bobot" class="text-sm font-semibold mb-4 mt-4">Sisa Bobot: 100</p>

                <div id="soal-wrapper" class="space-y-4"></div>

                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <button id="btn-tambah-soal" type="button" onclick="addSoal()"
                        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-teal-500 text-teal-500 hover:border-teal-400 hover:text-teal-400 focus:outline-hidden focus:border-teal-400 focus:text-teal-400 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Soal
                    </button>

                    <button type="button" data-hs-overlay="#modal-import-soal"
                        class="py-3 px-4 inline-flex items-center gap-x-2
                        text-sm font-medium rounded-lg
                        bg-teal-500 text-white
                        hover:bg-teal-600 focus:outline-hidden focus:bg-teal-700">

                        Import Soal
                    </button>

                </div>

            </div>

            <div class="flex justify-end gap-2">
                <button
                    class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">Simpan
                    Ujian</button>
                <button type="button" onclick="window.history.back()"
                    class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-gray-500 text-gray-500 hover:border-gray-800 hover:text-gray-800 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">Batal</button>
            </div>
        </form>
    </div>

    <div id="modal-import-soal" class="hs-overlay hidden fixed inset-0 z-[9999]
           bg-black/50 overflow-y-auto"
        role="dialog" tabindex="-1">

        <div
            class="hs-overlay-animation-target
               hs-overlay-open:mt-10
               hs-overlay-open:opacity-100
               hs-overlay-open:duration-300
               mt-0 opacity-0 transition-all
               sm:max-w-lg sm:w-full m-3 sm:mx-auto">

            <div
                class="flex flex-col bg-white border border-gray-200
                   shadow-xl rounded-xl pointer-events-auto">

                <!-- HEADER -->
                <div class="flex justify-between items-center py-3 px-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Import Soal
                    </h3>

                    <button type="button"
                        class="size-8 inline-flex justify-center items-center
                           rounded-full bg-gray-100 hover:bg-gray-200"
                        data-hs-overlay="#modal-import-soal">
                        ✕
                    </button>
                </div>

                <!-- BODY -->
                <div class="p-4 space-y-5">

                    <!-- TEMPLATE -->
                    <a href="{{ route('guru.ujian.template') }}"
                        class="inline-flex items-center gap-x-2
                           text-sm font-medium text-blue-600 hover:underline">
                        Download Template Soal
                    </a>

                    <!-- FILE INPUT -->
                    <div class="max-w-sm">
                        <label class="block">
                            <span class="sr-only">Upload file Excel</span>
                            <input type="file" id="fileExcel" accept=".xlsx"
                                class="block w-full text-sm text-gray-500
                                   file:me-4 file:py-2 file:px-4
                                   file:rounded-lg file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-500 file:text-white
                                   hover:file:bg-blue-600">
                        </label>
                    </div>

                    <p class="text-sm text-gray-500">
                        Gunakan template resmi agar data dapat diproses dengan benar.
                    </p>

                </div>

                <!-- FOOTER -->
                <div class="flex justify-end gap-x-2 py-3 px-4 border-t">
                    <button type="button"
                        class="px-4 py-2 rounded-lg border
                           text-gray-700 hover:bg-gray-50"
                        data-hs-overlay="#modal-import-soal">
                        Batal
                    </button>

                    <button type="button" onclick="importSoal()"
                        class="px-4 py-2 rounded-lg
                           bg-blue-600 text-white hover:bg-blue-700">
                        Import
                    </button>
                </div>
            </div>
        </div>
    </div>



    <script>
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }
    </script>

    <script>
        function resetSoalForm() {
            // Hapus semua soal di DOM
            document.getElementById('soal-wrapper').innerHTML = '';

            // Reset counter
            soalIndex = 0;
            totalBobot = 0;

            // (opsional) reset tampilan total bobot
            hitungTotalBobot?.();
        }
    </script>

    <script>
        function confirmResetSoal() {
            return confirm(
                'Import ulang akan menghapus semua soal yang sudah ada. Lanjutkan?'
            );
        }
    </script>

    <script>
        function hasSoal() {
            return document.querySelectorAll('.soal-item').length > 0;
        }
    </script>

    <script>
        async function importSoal() {
            const file = document.getElementById('fileExcel').files[0];

            if (!file) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File belum dipilih',
                    text: 'Silakan pilih file Excel terlebih dahulu'
                });
                return;
            }

            if (hasSoal()) {
                const confirm = await Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Soal yang sudah ada akan diganti dengan hasil import',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                });

                if (!confirm.isConfirmed) return;
            }

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const res = await fetch("{{ route('guru.ujian.import') }}", {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();

                if (!res.ok || data.success === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Import',
                        text: data.message || 'Terjadi kesalahan saat import'
                    });
                    return;
                }

                resetSoalForm();

                if (data.data) {
                    data.data.forEach(soal => addSoalFromImport(soal));
                }

                // ALERT MUNCUL DULU (DI DEPAN MODAL)
                await Swal.fire({
                    icon: 'success',
                    title: 'Import Berhasil',
                    text: data.message || 'Soal berhasil diimport',
                    confirmButtonText: 'OK'
                });

                // SETELAH KLIK OK → TUTUP MODAL
                HSOverlay.close('#modal-import-soal');

            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat import'
                });
            }
        }
    </script>


    <script>
        function addSoalFromImport(soal) {

            addSoal(); // pakai fungsi existing

            const idx = soalIndex - 1;
            const soalEl = document.querySelectorAll('.soal-item')[idx];

            // =====================
            // PERTANYAAN
            // =====================
            soalEl.querySelector(
                `textarea[name="soal[${idx}][pertanyaan]"]`
            ).value = soal.pertanyaan ?? '';

            // Bobot
            soalEl.querySelector(
                `input[name="soal[${idx}][bobot]"]`
            ).value = soal.bobot ?? 1;

            // =====================
            // GAMBAR SOAL (IMPORT)
            // =====================
            if (soal.pertanyaan_gambar) {
                // preview
                previewImage(soalEl, soal.pertanyaan_gambar);

                // HIDDEN INPUT (INI YANG PENTING)
                const hiddenImg = document.createElement('input');
                hiddenImg.type = 'hidden';
                hiddenImg.name = `soal[${idx}][pertanyaan_gambar]`;
                hiddenImg.value = soal.pertanyaan_gambar;

                soalEl.appendChild(hiddenImg);
            }

            // =====================
            // OPSI
            // =====================
            soal.opsi.forEach((opsi, i) => {

                addOpsi(
                    soalEl.querySelector('button[onclick^="addOpsi"]'),
                    idx
                );

                // teks opsi
                soalEl.querySelector(
                    `input[name="soal[${idx}][opsi][${i}][teks]"]`
                ).value = opsi.teks ?? '';

                // gambar opsi dari Excel
                if (opsi.gambar) {
                    previewImageOpsi(soalEl, idx, i, opsi.gambar);

                    // HIDDEN INPUT GAMBAR OPSI
                    const hiddenOpsiImg = document.createElement('input');
                    hiddenOpsiImg.type = 'hidden';
                    hiddenOpsiImg.name = `soal[${idx}][opsi][${i}][gambar]`;
                    hiddenOpsiImg.value = opsi.gambar;

                    soalEl.appendChild(hiddenOpsiImg);
                }
            });

            // =====================
            // JAWABAN BENAR
            // =====================
            const radio = soalEl.querySelector(
                `input[type="radio"][value="${soal.correct}"]`
            );
            if (radio) radio.checked = true;

            hitungTotalBobot();
        }
    </script>

    <script>
        function previewImage(soalEl, src) {
            const img = document.createElement('img');
            img.src = src;
            img.className = 'mt-2 max-h-40 rounded';

            soalEl.querySelector('.max-w-full').appendChild(img);
        }
    </script>

    <script>
        function previewImageOpsi(soalEl, idx, i, src) {
            const opsiWrap = soalEl.querySelectorAll('.opsi-item')[i];

            const img = document.createElement('img');
            img.src = src;
            img.className = 'mt-2 max-h-24 rounded';

            opsiWrap.querySelector('.flex-1').appendChild(img);
        }
    </script>



    {{-- ================= SCRIPT ================= --}}
    <script>
        let soalIndex = 0;
        let totalBobot = 0;
        const maxBobot = 100;

        function toggleKelas(tingkat, checkbox) {
            const kelasCheckboxes = document.querySelectorAll(
                'input[data-tingkat="' + tingkat + '"]'
            );

            kelasCheckboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
        }

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

        function reindexSoal() {
            const soalItems = document.querySelectorAll('.soal-item');

            soalItems.forEach((item, index) => {
                // Update judul soal
                item.querySelector('h3').innerText = `Soal ${index + 1}`;

                // Update semua input & textarea name
                item.querySelectorAll('input, textarea').forEach(el => {
                    if (el.name) {
                        el.name = el.name.replace(/soal\[\d+]/, `soal[${index}]`);
                    }
                });

                // Update tombol tambah opsi (biar index benar)
                const btnOpsi = item.querySelector('[onclick^="addOpsi"]');
                if (btnOpsi) {
                    btnOpsi.setAttribute('onclick', `addOpsi(this, ${index})`);
                }
            });

            // update soalIndex agar sinkron
            soalIndex = soalItems.length;
        }

        function hapusSoal(btn) {
            const soalItem = btn.closest('.soal-item');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Soal yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    soalItem.remove();
                    reindexSoal();
                    hitungTotalBobot();

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Soal berhasil dihapus',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
            });
        }

        function hapusOpsi(btn) {
            btn.closest('.opsi-item').remove();
        }

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
                        focus:border-blue-500 focus:ring-blue-100"
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
                            file:disabled:opacity-50 file:disabled:pointer-events-none"
                        >
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
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </div>
                `);
        }


        // VALIDASI SUBMIT
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
    </script>
@endsection
