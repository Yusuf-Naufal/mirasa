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

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('costumer.index') }}"
                    class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors"
                            viewBox="0 0 12 12">
                            <path fill="currentColor"
                                d="M6.153 7.008A1.5 1.5 0 0 1 7.5 8.5c0 .771-.47 1.409-1.102 1.83c-.635.424-1.485.67-2.398.67s-1.763-.246-2.398-.67C.969 9.91.5 9.271.5 8.5A1.5 1.5 0 0 1 2 7h4zM10.003 7a1.5 1.5 0 0 1 1.5 1.5c0 .695-.432 1.211-.983 1.528c-.548.315-1.265.472-2.017.472q-.38-.001-.741-.056c.433-.512.739-1.166.739-1.944A2.5 2.5 0 0 0 7.997 7zM4.002 1.496A2.253 2.253 0 1 1 4 6.001a2.253 2.253 0 0 1 0-4.505m4.75 1.001a1.75 1.75 0 1 1 0 3.5a1.75 1.75 0 0 1 0-3.5" />
                        </svg>
                    </div>
                    <span
                        class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Costumer</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
