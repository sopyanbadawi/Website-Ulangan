@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Card Total Ujian --}}
            <div class="border rounded-xl p-4 bg-white transition">
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600
                            flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                <h3 class="font-medium text-sm text-gray-800">
                                    Total Ujian
                                </h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $ujian['total'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Ujian Aktif --}}
            <div class="border rounded-xl p-4 bg-white transition">
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-green-100 text-green-600
                            flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                <h3 class="font-medium text-sm text-gray-800">
                                    Total Ujian Aktif
                                </h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $ujian['aktif'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Total Siswa --}}
            <div class="border rounded-xl p-4 bg-white transition">
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600
                            flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>

                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                <h3 class="font-medium text-sm text-gray-800">
                                    Total Siswa
                                </h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $ujian['siswa'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Total Kelas --}}
            <div class="border rounded-xl p-4 bg-white transition">
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-600
                            flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                <h3 class="font-medium text-sm text-gray-800">
                                    Total Kelas
                                </h3>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $ujian['kelas'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- CARD CHART -->
            <div class="bg-white rounded-xl border p-4 flex flex-col gap-4">

                <!-- Header + Filter -->
                <div class="flex items-center justify-between gap-4">
                    <div class="font-semibold text-gray-700">
                        Distribusi Nilai Siswa
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="hs-dropdown [--auto-close:outside] relative inline-flex">
                        <button type="button"
                            class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2
                            text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm">
                            Filter
                            <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="hs-dropdown-menu hidden min-w-60 bg-white shadow-md rounded-lg mt-2 z-30 p-3 space-y-3">

                            <!-- Filter Kelas -->
                            <div>
                                <label class="text-xs font-semibold text-gray-600">Kelas</label>
                                <select onchange="location = this.value"
                                    class="mt-1 block w-full text-sm rounded-lg border-gray-200">
                                    <option value="{{ request()->fullUrlWithQuery(['kelas_id' => null]) }}">
                                        Semua
                                    </option>
                                    @foreach (\App\Models\KelasModel::all() as $kelas)
                                        <option value="{{ request()->fullUrlWithQuery(['kelas_id' => $kelas->id]) }}"
                                            {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Semester -->
                            <div>
                                <label class="text-xs font-semibold text-gray-600">Semester</label>
                                <select onchange="location = this.value"
                                    class="mt-1 block w-full text-sm rounded-lg border-gray-200">
                                    <option value="{{ request()->fullUrlWithQuery(['semester' => null]) }}">
                                        Semua
                                    </option>
                                    <option value="{{ request()->fullUrlWithQuery(['semester' => 'ganjil']) }}"
                                        {{ request('semester') == 'ganjil' ? 'selected' : '' }}>
                                        Ganjil
                                    </option>
                                    <option value="{{ request()->fullUrlWithQuery(['semester' => 'genap']) }}"
                                        {{ request('semester') == 'genap' ? 'selected' : '' }}>
                                        Genap
                                    </option>
                                </select>
                            </div>

                            <!-- ✅ Filter Tahun Ajaran -->
                            <div>
                                <label class="text-xs font-semibold text-gray-600">Tahun Ajaran</label>
                                <select onchange="location = this.value"
                                    class="mt-1 block w-full text-sm rounded-lg border-gray-200">
                                    <option value="{{ request()->fullUrlWithQuery(['tahun_ajaran_id' => null]) }}">
                                        Semua
                                    </option>
                                    @foreach (\App\Models\TahunAjaranModel::orderBy('tahun', 'desc')->get() as $ta)
                                        <option value="{{ request()->fullUrlWithQuery(['tahun_ajaran_id' => $ta->id]) }}"
                                            {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                                            {{ $ta->tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Reset -->
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex justify-center py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                Reset Filter
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="flex flex-col items-center">
                    <div id="hs-doughnut-chart" class="w-full max-w-[240px]"></div>

                    <!-- Legend -->
                    <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 mt-4 text-[13px] text-gray-600">
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-blue-600 rounded-sm mr-2"></span>
                            0–59
                        </div>
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-cyan-500 rounded-sm mr-2"></span>
                            60–69
                        </div>
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-yellow-400 rounded-sm mr-2"></span>
                            70–79
                        </div>
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 bg-green-500 rounded-sm mr-2"></span>
                            80–100
                        </div>
                    </div>
                </div>
            </div>



            <!-- CARD KANAN -->
            <div class="bg-white rounded-xl border p-4 flex flex-col gap-4">

                <!-- Header -->


                <!-- Header + Legend -->
                <div class="flex items-center justify-between">
                    <div class="font-semibold text-gray-700">
                        Perbandingan Rata-Rata Nilai Semester
                    </div>

                    <!-- Legend -->
                    <div class="flex gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 bg-blue-600"></span>
                            Semester Sekarang
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-1 border-t-2 border-dashed border-gray-400"></span>
                            Semester Sebelumnya
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div id="hs-multiple-line-charts" class="w-full"></div>
            </div>
        </div>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const data = @json($avgSemester);

            const labels = data.map(d => d.semester);
            const values = data.map(d => Number(d.avg));

            const currentSemester = values;
            const previousSemester = [null, ...values.slice(0, -1)];

            const options = {
                chart: {
                    height: 260,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },

                series: [{
                        name: 'Semester Sebelumnya',
                        data: previousSemester
                    },
                    {
                        name: 'Semester Sekarang',
                        data: currentSemester
                    }
                ],

                stroke: {
                    width: [3, 4],
                    curve: 'straight',
                    dashArray: [6, 0] 
                },

                colors: ['#9ca3af', '#2563EB'],

                tooltip: {
                    enabled: true,
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: val => val ? val.toFixed(2) : '-'
                    }
                },

                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '12px'
                        }
                    }
                },

                yaxis: {
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: val => val.toFixed(0)
                    }
                },

                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },

                grid: {
                    borderColor: '#e5e7eb',
                    strokeDashArray: 4
                }
            };

            new ApexCharts(
                document.querySelector('#hs-multiple-line-charts'),
                options
            ).render();
        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                chart: {
                    type: 'donut',
                    height: 230
                },
                series: [
                    {{ $distribusiNilai['0_59'] }},
                    {{ $distribusiNilai['60_69'] }},
                    {{ $distribusiNilai['70_79'] }},
                    {{ $distribusiNilai['80_100'] }},
                ],
                labels: ['0–59', '60–69', '70–79', '80–100'],
                colors: ['#2563eb', '#22d3ee', '#facc15', '#22c55e'],
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 3,
                    colors: ['#fff']
                }
            };

            new ApexCharts(
                document.querySelector('#hs-doughnut-chart'),
                options
            ).render();
        });
    </script>
@endsection
