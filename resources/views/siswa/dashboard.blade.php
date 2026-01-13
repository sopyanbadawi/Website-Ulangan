@extends('layouts.app-siswa')

@section('content')
    <div class="mb-6">
        <div class="overflow-hidden border border-gray-200 rounded-lg bg-white p-5">
            <div class="flex items-center gap-4">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="p-3 bg-gray-200 rounded-lg text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                clip-rule="evenodd" />
                        </svg>

                    </div>
                </div>

                <!-- Content -->
                <div>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $user->name }}
                    </p>

                    <p class="text-sm text-gray-700">
                        {{ $kelasAktif?->kelas?->nama_kelas ?? '-' }} | {{ $tahunAjaranAktif?->tahun }}
                        ({{ ucfirst($tahunAjaranAktif?->semester) }})
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

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
                            {{ $totalUjianDikerjakan }}
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
                            {{ $totalUjianAktif }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total selesai --}}
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
                                Total Ujian Selesai
                            </h3>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $totalUjianSelesai }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white border border-gray-200 mt-6">
        <!-- =========================
                 | HEADER
                 ========================= -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <!-- Title -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center shrink-0">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                    </svg>

                </div>
                <h2 class="text-base font-semibold text-gray-800">
                    Grafik Nilai
                </h2>
            </div>

            <!-- Filter -->
            <div class="hs-dropdown [--auto-close:outside] relative inline-flex">
                <button type="button"
                    class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2
                    text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50">
                    Filter
                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div class="hs-dropdown-menu hidden min-w-64 bg-white shadow-lg rounded-xl mt-2 z-30 p-4 space-y-4">
                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600">
                            Tahun Ajaran
                        </label>
                        <select onchange="location = this.value"
                            class="mt-1 block w-full text-sm rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500">
                            <option value="{{ request()->fullUrlWithQuery(['tahun_ajaran_id' => null]) }}">
                                Semua
                            </option>
                            @foreach ($listTahunAjaran as $ta)
                                <option value="{{ request()->fullUrlWithQuery(['tahun_ajaran_id' => $ta->id]) }}"
                                    {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->tahun }}
                                    @if ($ta->is_active)
                                        (Aktif)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600">
                            Semester
                        </label>
                        <select onchange="location = this.value"
                            class="mt-1 block w-full text-sm rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500">
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

                    <!-- Mata Pelajaran -->
                    <div>
                        <label class="text-xs font-semibold text-gray-600">
                            Mata Pelajaran
                        </label>
                        <select onchange="location = this.value"
                            class="mt-1 block w-full text-sm rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500">
                            <option value="{{ request()->fullUrlWithQuery(['mata_pelajaran_id' => null]) }}">
                                Semua
                            </option>
                            @foreach ($listMataPelajaran as $mapel)
                                <option value="{{ request()->fullUrlWithQuery(['mata_pelajaran_id' => $mapel->id]) }}"
                                    {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset -->
                    <a href="{{ route('siswa.dashboard') }}"
                        class="block text-center py-2 text-sm font-medium
                        text-red-600 hover:bg-red-50 rounded-lg">
                        Reset Filter
                    </a>
                </div>
            </div>
        </div>

        <!-- =========================
                 | CHART
                 ========================= -->
        <div class="px-6 py-4">
            <div id="hs-curved-line-charts" class="w-full h-[260px] overflow-hidden">
            </div>
        </div>
    </div>




    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const options = {
                    chart: {
                        height: 250,
                        type: 'area',
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },

                    series: [{
                        name: 'Rata-rata Nilai',
                        data: @json($chartData)
                    }],

                    colors: ['#166534'],

                    stroke: {
                        curve: 'smooth',
                        width: 4
                    },

                    dataLabels: {
                        enabled: false
                    },

                    fill: {
                        type: 'gradient',
                        opacity: 1,
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.3,
                            opacityFrom: 0.45,
                            opacityTo: 0,
                            stops: [0, 80, 100]
                        }
                    },

                    grid: {
                        borderColor: '#e5e7eb',
                        padding: {
                            top: -20,
                            right: 0,
                            left: 0,
                            bottom: 0
                        }
                    },

                    xaxis: {
                        categories: @json($chartLabels),
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: '#9ca3af',
                                fontSize: '13px'
                            }
                        }
                    },

                    yaxis: {
                        min: 0,
                        max: 100,
                        tickAmount: 5,
                        labels: {
                            style: {
                                colors: '#9ca3af',
                                fontSize: '12px'
                            }
                        }
                    },

                    tooltip: {
                        theme: 'light',
                        y: {
                            formatter: (val) => `Nilai ${val}`
                        }
                    }
                };

                const chart = new ApexCharts(
                    document.querySelector("#hs-curved-line-charts"),
                    options
                );

                chart.render();
            });
        </script>
    @endpush
@endsection
