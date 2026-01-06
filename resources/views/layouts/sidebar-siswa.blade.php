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
           bg-white border-e border-gray-200
           dark:bg-neutral-800 dark:border-neutral-700"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full ">
        <!-- Header -->
        <header class=" p-4 flex justify-between items-center gap-x-2">

            <a class="flex-none font-semibold text-xl text-black focus:outline-hidden focus:opacity-80 dark:text-white "
                href="#" aria-label="Brand">Exam</a>

            <div class="lg:hidden -me-2">
                <!-- Close Button -->
                <button type="button"
                    class="flex justify-center items-center gap-x-3 size-6 bg-white border border-gray-200 text-sm text-gray-600 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
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
            class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
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
                            href="{{route('siswa.ujian.index')}}">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Ujian
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End Body -->
    </div>
</div>
<!-- End Sidebar -->
