<nav class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 border-b border-gray-200 bg-white fixed z-30">
    <div class="flex items-center justify-between h-16 w-full">

        <!-- Left: Hamburger + Brand -->
        <div class="flex items-center gap-x-3 flex-shrink-0">

            <!-- Hamburger -->
            <button type="button"
                class="lg:hidden inline-flex items-center justify-center p-2
                       text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg
                       hover:bg-gray-50 focus:outline-none"
                aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-sidebar-collapsible-group"
                data-hs-overlay="#hs-sidebar-collapsible-group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

        </div>

        <!-- Right: User Dropdown -->
        <div class="hs-dropdown [--auto-close:inside] relative inline-flex flex-shrink-0">

            <button id="hs-dropdown-default" type="button"
                class="hs-dropdown-toggle inline-flex items-center gap-x-2 p-2
                       text-sm font-medium text-gray-800 bg-white rounded-lg
                       hover:bg-gray-50 focus:outline-none">

                <!-- Avatar -->
                <span class="inline-block w-9 h-9 bg-gray-100 rounded-full overflow-hidden">
                    <svg class="w-full h-full text-gray-300" width="16" height="16" viewBox="0 0 16 16"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.62854" y="0.359985" width="15" height="15" rx="7.5" fill="white">
                        </rect>
                        <path
                            d="M8.12421 7.20374C9.21151 7.20374 10.093 6.32229 10.093 5.23499C10.093 4.14767 9.21151 3.26624 8.12421 3.26624C7.0369 3.26624 6.15546 4.14767 6.15546 5.23499C6.15546 6.32229 7.0369 7.20374 8.12421 7.20374Z"
                            fill="currentColor"></path>
                        <path
                            d="M11.818 10.5975C10.2992 12.6412 7.42106 13.0631 5.37731 11.5537C5.01171 11.2818 4.69296 10.9631 4.42107 10.5975C4.28982 10.4006 4.27107 10.1475 4.37419 9.94123L4.51482 9.65059C4.84296 8.95684 5.53671 8.51624 6.30546 8.51624H9.95231C10.7023 8.51624 11.3867 8.94749 11.7242 9.62249L11.8742 9.93184C11.968 10.1475 11.9586 10.4006 11.818 10.5975Z"
                            fill="currentColor"></path>
                    </svg>
                </span>

                @auth
                    <span class="hidden md:inline text-sm font-medium">
                        {{ Auth::user()->name }}
                    </span>
                @endauth

                <svg class="w-4 h-4 hs-dropdown-open:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6" />
                </svg>

            </button>

            <!-- Dropdown Menu -->
            <div
                class="hs-dropdown-menu hidden min-w-[12rem] mt-2 rounded-lg bg-white shadow-md">
                <form action="{{ route('logout') }}" method="POST" class="p-1">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-3 py-2 text-sm rounded-lg text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            </div>

        </div>

    </div>
</nav>
