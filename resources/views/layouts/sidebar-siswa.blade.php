<!-- Sidebar -->
<div id="hs-sidebar-collapsible-group"
    class="hs-overlay
           [--auto-close:lg]
           [--overlay-backdrop:false]
           lg:block lg:translate-x-0 lg:end-auto lg:bottom-0
           w-64
           hs-overlay-open:translate-x-0
           -translate-x-full transition-all duration-300 transform
           h-full hidden
           fixed top-0 start-0 bottom-0 z-40
           bg-white border-e border-gray-200"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full ">
        <!-- Header -->
        <header class=" p-4 flex justify-between items-center gap-x-2">

            <a href="{{route('siswa.dashboard')}}" aria-label="Brand" class="flex-none focus:outline-hidden focus:opacity-80 mb-4 ">
                <img src="{{url('exam.png')}}" alt="Exam" class="h-10 w-auto">
            </a>

            <div class="lg:hidden -me-2">
                <!-- Close Button -->
                <button type="button"
                    class="flex justify-center items-center gap-x-3 size-6 bg-white border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 "
                    data-hs-overlay="#hs-sidebar-collapsible-group">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                    <span class="sr-only">Close</span>
                </button>
                <!-- End Close Button -->
            </div>
        </header>
        <!-- End Header -->

        <!-- Body -->
        <nav
            class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 ">
            <div class="hs-accordion-group pb-0 px-2  w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                <ul class="space-y-1">
                    <li>
                        <a class=" flex items-center gap-x-3.5 py-2 px-2.5 {{ $activeMenu == 'dashboard' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                            href="{{ route('siswa.dashboard') }}">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a class=" flex items-center gap-x-3.5 py-2 px-2.5 {{ $activeMenu == 'ujian' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                            href="{{ route('siswa.ujian.index') }}">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Ujian
                        </a>
                    </li>
                    <li>
                        <a class=" flex items-center gap-x-3.5 py-2 px-2.5 {{ $activeMenu == 'riwayat_ujian' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                            href="{{ route('siswa.riwayat-ujian.riwayat') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                            </svg>
                            Riwayat Ujian
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End Body -->
    </div>
</div>
<!-- End Sidebar -->
