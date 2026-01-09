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
                            href="{{ route('admin.dashboard') }}">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                @if (Auth::user()->role->name == 'superadmin') 
                    <li class="hs-accordion" id="account-accordion">
                        <button type="button"
                            class=" hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="account-accordion-sub-1-collapse-1">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            User Management

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400 "
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400 "
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    @endif
                        <div id="account-accordion-sub-1-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="account-accordion">
                            <ul class="pt-1 ps-7 space-y-1">
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'user' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route('admin.user.index') }}">
                                        Pengguna
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'role' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route('admin.role.index') }}">
                                        Role
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="hs-accordion" id="projects-accordion">
                        <button type="button"
                            class=" hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="projects-accordion-sub-1-collapse-1">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                            Akademik

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400 "
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400 "
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div id="projects-accordion-sub-1-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="projects-accordion">
                            <ul class="pt-1 ps-7 space-y-1">
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'kelas' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{route('admin.kelas.index')}}">
                                        Kelas
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'tahun' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route('admin.tahun.index') }}">
                                        Tahun Ajaran
                                    </a>
                                </li>
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'mapel' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route('admin.mapel.index') }}">
                                        Mata Pelajaran
                                    </a>
                                </li>
                            @if (Auth::user()->role->name == 'superadmin') 
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'guru_mapel' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route('admin.guru_mapel.index') }}">
                                        Guru Mapel
                                    </a>
                                </li>
                            @endif
                            </ul>
                        </div>
                    </li>

                    <li class="hs-accordion" id="account-accordion">
                        <button type="button"
                            class=" hs-accordion-toggle w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            aria-expanded="true" aria-controls="account-accordion-sub-1-collapse-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                            Manajemen Ujian

                            <svg class="hs-accordion-active:block ms-auto hidden size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400 "
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>

                            <svg class="hs-accordion-active:hidden ms-auto block size-4 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400 "
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div id="account-accordion-sub-1-collapse-1"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden"
                            role="region" aria-labelledby="account-accordion">
                            <ul class="pt-1 ps-7 space-y-1">
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'ujian' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route((Auth::user()->role->name == 'superadmin' ? 'admin' : Auth::user()->role->name) . '.ujian.index') }}">
                                        Ujian
                                    </a>
                                </li>
                            </ul>
                            <ul class="pt-1 ps-7 space-y-1">
                                <li>
                                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm {{ $activeMenu == 'monitoring' ? 'bg-blue-50 text-blue-800' : 'text-gray-800 hover:bg-gray-100 bg-white' }} bg-blue-50 text-sm text-blue-800 rounded-lg hover:bg-blue-100 focus:outline-hidden focus:bg-blue-100"
                                        href="{{ route('admin.ujian.monitoring') }}">
                                        Monitoring Ujian
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a class=" w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                            href="#">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Documentation
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End Body -->
    </div>
</div>
<!-- End Sidebar -->
