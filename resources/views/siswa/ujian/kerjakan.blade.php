@extends('layouts.app-siswa')

@section('content')
    <div class="max-w-7xl mx-auto px-4">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">
                {{ $attempt->ujian->nama_ujian }}
            </h2>

            <div
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                <!-- ICON JAM -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <!-- TIMER -->
                <span id="timer">--:--</span>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            {{-- KIRI --}}
            <div class="md:col-span-3">
                <form id="ujianForm" method="POST" action="{{ route('siswa.ujian.submit', $attempt->id) }}">
                    @csrf

                    @foreach ($attempt->ujian->soal as $index => $soal)
                        @php
                            $jawaban = $attempt->jawabanSiswa->firstWhere('soal_id', $soal->id);
                        @endphp

                        <div id="soal-{{ $index }}"
                            class="soal-item {{ $index !== 0 ? 'hidden' : '' }} bg-white border rounded-xl p-6 mb-6">

                            <div class="flex justify-between mb-3">
                                <p class="font-medium text-gray-400 text-sm">
                                    Soal {{ $index + 1 }} dari {{ $attempt->ujian->soal->count() }}
                                </p>
                                <span
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none">
                                    Bobot: {{ $soal->bobot }}
                                </span>
                            </div>

                            {{-- Pertanyaan Teks --}}
                            @if ($soal->pertanyaan)
                                <p class="font-semibold text-lg mb-3">{{ $soal->pertanyaan }}</p>
                            @endif

                            {{-- Pertanyaan Gambar --}}
                            @if ($soal->pertanyaan_gambar)
                                <div class="mb-3 cursor-pointer"
                                    onclick="openModal('{{ asset($soal->pertanyaan_gambar) }}')">
                                    <img src="{{ asset($soal->pertanyaan_gambar) }}"
                                        class="w-full max-h-64 object-contain border rounded" alt="Gambar Soal">
                                </div>
                            @endif

                            <div class="space-y-3">
                                @foreach ($soal->opsiJawaban as $opsi)
                                    <label
                                        class="flex flex-col sm:flex-row gap-3 p-3 border rounded cursor-pointer hover:bg-gray-50">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="soal_{{ $soal->id }}"
                                                value="{{ $opsi->id }}" @checked(optional($jawaban)->opsi_id === $opsi->id)
                                                onchange="saveAnswer({{ $soal->id }}, {{ $opsi->id }}, {{ $index }})">
                                            @if ($opsi->opsi)
                                                <span>{{ $opsi->opsi }}</span>
                                            @endif
                                        </div>
                                        @if ($opsi->opsi_gambar)
                                            <img src="{{ asset($opsi->opsi_gambar) }}"
                                                class="w-full sm:max-w-[150px] max-h-40 object-contain border rounded mt-2 sm:mt-0 cursor-pointer"
                                                alt="Opsi Gambar" onclick="openModal('{{ asset($opsi->opsi_gambar) }}')">
                                        @endif
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex justify-between mt-6">
                                <!-- Previous -->
                                <button type="button" onclick="prevSoal()"
                                    class="px-4 py-2 border rounded inline-flex items-center gap-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15.75 4.5-7.5 7.5 7.5 7.5" />
                                    </svg>
                                    Previous
                                </button>

                                <!-- Next -->
                                <button type="button" onclick="nextSoal()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded inline-flex items-center gap-x-2">
                                    Next
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    @endforeach

                    <button type="submit"
                        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-500 text-white hover:bg-green-600 focus:outline-hidden focus:bg-green-600 disabled:opacity-50 disabled:pointer-events-none">
                        Submit Ujian
                    </button>
                </form>
            </div>

            {{-- KANAN --}}
            <div class="md:col-span-1">
                <div class="bg-white border rounded-xl p-4 sticky top-4">
                    <h3 class="font-semibold mb-3">Navigasi Soal</h3>
                    <div class="grid grid-cols-5 sm:grid-cols-5 gap-2">
                        @foreach ($attempt->ujian->soal as $i => $s)
                            @php
                                $jawaban = $attempt->jawabanSiswa->firstWhere('soal_id', $s->id);
                            @endphp
                            <button id="nav-{{ $i }}" onclick="goToSoal({{ $i }})"
                                class="w-full max-w-[40px] aspect-square flex items-center justify-center border rounded text-sm
                                {{ $jawaban ? 'bg-green-200 border-green-500' : 'bg-white border-gray-300' }}
                                hover:bg-blue-100">
                                {{ $i + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PREVIEW GAMBAR --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-50">
        <div class="relative">
            <button onclick="closeModal()"
                class="absolute top-2 right-2 text-white text-2xl font-bold z-50">&times;</button>
            <img id="modalImage" src="" class="max-w-full max-h-screen object-contain border rounded"
                alt="Preview Gambar">
        </div>
    </div>

    {{-- OVERLAY LOCK --}}
    <div id="lockOverlay"
        class="fixed inset-0 bg-black bg-opacity-90 text-white hidden
     flex flex-col items-center justify-center z-50">
        <h1 class="text-2xl font-bold mb-4">🔒 UJIAN DIKUNCI</h1>
        <p class="text-center max-w-md">
            Anda melanggar aturan ujian.<br>
            Silakan hubungi <b>pengawas</b>.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* ================= BASIC ================= */
        let currentSoal = 0;
        const form = document.getElementById('ujianForm');
        const overlay = document.getElementById('lockOverlay');

        /* ================= TIMER ================= */
        const examKey = "exam_end_time_{{ $attempt->id }}";

        if (!localStorage.getItem(examKey)) {
            localStorage.setItem(
                examKey,
                Date.now() + ({{ $attempt->ujian->durasi }} * 60 * 1000)
            );
        }

        const endTime = parseInt(localStorage.getItem(examKey));
        const timerEl = document.getElementById('timer');

        let countdown = setInterval(() => {
            let remaining = Math.floor((endTime - Date.now()) / 1000);

            if (remaining <= 0) {
                clearInterval(countdown);

                Swal.fire({
                    title: 'Waktu Habis',
                    text: 'Ujian akan otomatis disubmit',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Hapus overlay sementara agar form bisa submit
                    overlay.classList.add('hidden');
                    document.querySelectorAll('input,button').forEach(el => el.disabled = false);

                    // Submit form
                    form.submit();
                });
                return;
            }


            timerEl.textContent =
                `${String(Math.floor(remaining / 60)).padStart(2,'0')}:${String(remaining % 60).padStart(2,'0')}`;
        }, 1000);

        /* ================= NAV ================= */
        function updateNavActive() {
            document.querySelectorAll('[id^="nav-"]').forEach(btn => {
                btn.classList.remove('bg-blue-200', 'border-blue-500');
            });

            const activeBtn = document.getElementById(`nav-${currentSoal}`);
            if (!activeBtn) return;

            if (activeBtn.classList.contains('bg-green-200')) {
                activeBtn.classList.add('bg-green-400', 'border-green-600');
            } else {
                activeBtn.classList.add('bg-blue-200', 'border-blue-500');
            }
        }

        function showSoal(i) {
            document.querySelectorAll('.soal-item').forEach(el => el.classList.add('hidden'));
            document.getElementById(`soal-${i}`).classList.remove('hidden');
            currentSoal = i;
            updateNavActive();
        }

        function nextSoal() {
            if (currentSoal < {{ $attempt->ujian->soal->count() - 1 }}) showSoal(currentSoal + 1);
        }

        function prevSoal() {
            if (currentSoal > 0) showSoal(currentSoal - 1);
        }

        function goToSoal(i) {
            showSoal(i);
        }

        /* ================= SIMPAN JAWABAN ================= */
        function saveAnswer(soalId, opsiId, index) {
            fetch("{{ route('siswa.ujian.jawab', $attempt->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    soal_id: soalId,
                    opsi_id: opsiId
                })
            }).then(() => {
                const nav = document.getElementById(`nav-${index}`);
                nav.classList.add('bg-green-200', 'border-green-500');
                updateNavActive();
            });
        }

        /* ================= ANTI CURANG ================= */
        let pelanggaran = 0;
        const MAX_PELANGGARAN = 3;
        let sudahLocked = false;

        document.addEventListener('visibilitychange', () => {
            if (document.hidden && !sudahLocked) {
                pelanggaran++;

                if (pelanggaran < MAX_PELANGGARAN) {
                    Swal.fire(
                        'Peringatan',
                        `Keluar halaman (${pelanggaran}/${MAX_PELANGGARAN})`,
                        'warning'
                    );
                }

                if (pelanggaran >= MAX_PELANGGARAN) {
                    lockToBackend();
                }
            }
        });

        function lockToBackend() {
            sudahLocked = true;

            fetch("{{ route('siswa.ujian.lock', $attempt->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).finally(() => {
                overlay.classList.remove('hidden');
                document.querySelectorAll('input,button').forEach(el => el.disabled = true);
            });
        }

        window.addEventListener('beforeunload', e => {
            e.preventDefault();
            e.returnValue = '';
        });

        showSoal(0);

        /* ================= MODAL PREVIEW GAMBAR ================= */
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        function openModal(src) {
            modalImage.src = src;
            imageModal.classList.remove('hidden');
        }

        function closeModal() {
            imageModal.classList.add('hidden');
            modalImage.src = '';
        }

        imageModal.addEventListener('click', (e) => {
            if (e.target === imageModal) closeModal();
        });
    </script>

    {{-- SWEET ALERT KONFIRMASI SUBMIT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const totalSoal = {{ $attempt->ujian->soal->count() }};
                const answered = document.querySelectorAll('input[type="radio"]:checked').length;
                const unanswered = totalSoal - answered;

                Swal.fire({
                    title: 'Yakin ingin submit ujian?',
                    html: `
                        <div style="text-align:center; line-height:1.8">
                            <p>Total soal: <b>${totalSoal}</b></p>
                            <p>Sudah dijawab: <b>${answered}</b></p>
                            <p>Belum dijawab: <b>${unanswered}</b></p>
                        </div>
                    `,
                    icon: unanswered > 0 ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Submit',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>

    @if ($isLocked)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('lockOverlay').classList.remove('hidden');
                document.querySelectorAll('input,button').forEach(el => el.disabled = true);
            });
        </script>
    @endif
@endsection
