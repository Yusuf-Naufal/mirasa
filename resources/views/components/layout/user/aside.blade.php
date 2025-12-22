<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 h-screen pt-20 transition-all duration-300 ease-in-out bg-white border-r border-gray-200 overflow-hidden group shadow-sm 
           -translate-x-full w-64 
           sm:translate-x-0 sm:w-20 sm:hover:w-64"
    aria-label="Sidebar">

    <div class="h-full px-4 pb-4 overflow-y-auto overflow-x-hidden bg-white scrollbar-hide">
<<<<<<< HEAD
        @if (auth()->user()->hasRole('Super Admin'))
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('super-admin.dashboard') }}"
                        class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                        <div class="min-w-[32px] flex justify-center">
                            <svg class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
=======
        <ul class="space-y-2 font-medium">
            {{-- Menu Dashboard --}}
            <li>
                <a href="{{ route('super-admin.dashboard') }}"
                    class="flex items-center p-3 {{ request()->routeIs('super-admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }} rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg class="w-6 h-6 {{ request()->routeIs('super-admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }} group-hover/item:text-blue-600 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </div>
                    <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Dashboard</span>
                </a>
            </li>

            {{-- Menu Perusahaan --}}
            <li>
                <a href="{{ route('perusahaan.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('perusahaan.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }} rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 {{ request()->routeIs('perusahaan.*') ? 'text-blue-600' : 'text-gray-400' }} group-hover/item:text-blue-600 transition-colors"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21 19h2v2H1v-2h2V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v15h2V9h3a1 1 0 0 1 1 1zM7 11v2h4v-2zm0-4v2h4V7z" />
                        </svg>
                    </div>
                    <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Perusahaan</span>
                </a>
            </li>

            {{-- Menu Supplier --}}
            <li>
                <a href="{{ route('supplier.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('supplier.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }} rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg class="w-6 h-6 {{ request()->routeIs('supplier.*') ? 'text-blue-600' : 'text-gray-400' }} group-hover/item:text-blue-600 transition-colors" fill="none" 
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Supplier</span>
                </a>
            </li>

            {{-- Menu Proses --}}
            <li>
                <a href="{{ route('proses.index') }}"
                    class="flex items-center p-3 {{ request()->routeIs('proses.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }} rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                    <div class="min-w-[32px] flex justify-center">
                        <svg class="w-6 h-6 {{ request()->routeIs('proses.*') ? 'text-blue-600' : 'text-gray-400' }} group-hover/item:text-blue-600 transition-colors" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Proses</span>
                </a>
            </li>
        </ul>
>>>>>>> 874d395 (tampilan supplier dan proses)

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

            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('barang.index.index') }}"
                        class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                        <div class="min-w-[32px] flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m17.578 4.432l-2-1.05C13.822 2.461 12.944 2 12 2s-1.822.46-3.578 1.382l-.321.169l8.923 5.099l4.016-2.01c-.646-.732-1.688-1.279-3.462-2.21m4.17 3.534l-3.998 2V13a.75.75 0 0 1-1.5 0v-2.286l-3.5 1.75v9.44c.718-.179 1.535-.607 2.828-1.286l2-1.05c2.151-1.129 3.227-1.693 3.825-2.708c.597-1.014.597-2.277.597-4.8v-.117c0-1.893 0-3.076-.252-3.978M11.25 21.904v-9.44l-8.998-4.5C2 8.866 2 10.05 2 11.941v.117c0 2.525 0 3.788.597 4.802c.598 1.015 1.674 1.58 3.825 2.709l2 1.049c1.293.679 2.11 1.107 2.828 1.286M2.96 6.641l9.04 4.52l3.411-1.705l-8.886-5.078l-.103.054c-1.773.93-2.816 1.477-3.462 2.21" />
                            </svg>
                        </div>
                        <span
                            class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Barang</span>
                    </a>
                </li>
            </ul>

            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('user.index') }}"
                        class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                        <div class="min-w-[32px] flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 14v8H4a8 8 0 0 1 8-8m0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6s6 2.685 6 6s-2.685 6-6 6m9 4h1v5h-8v-5h1v-1a3 3 0 1 1 6 0zm-2 0v-1a1 1 0 1 0-2 0v1z" />
                            </svg>
                        </div>
                        <span
                            class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">User</span>
                    </a>
                </li>
            </ul>
        @endif

        @if (auth()->user()->hasRole('Manager'))
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('manager.dashboard') }}"
                        class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                        <div class="min-w-[32px] flex justify-center">
                            <svg class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        @endif
    </div>
</aside>