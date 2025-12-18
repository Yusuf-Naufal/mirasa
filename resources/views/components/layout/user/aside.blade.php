<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 h-screen pt-20 transition-all duration-300 ease-in-out bg-white border-r border-gray-200 overflow-hidden group shadow-sm 
           -translate-x-full w-64 
           sm:translate-x-0 sm:w-20 sm:hover:w-64"
    aria-label="Sidebar">

    <div class="h-full px-4 pb-4 overflow-y-auto overflow-x-hidden bg-white scrollbar-hide">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('super-admin.dashboard') }}"
                    class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Dashboard</span>
                </a>
            </li>
        </ul>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('perusahaan.index') }}"
                    class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21 19h2v2H1v-2h2V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v15h2V9h3a1 1 0 0 1 1 1zM7 11v2h4v-2zm0-4v2h4V7z" />
                        </svg>
                    </div>
                    <span
                        class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Perusahaan</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
