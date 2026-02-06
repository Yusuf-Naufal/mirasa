<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Beranda' }}</title>

    {{-- Favicon --}}
    @if(auth()->user()->perusahaan && auth()->user()->perusahaan->logo)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . auth()->user()->perusahaan->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}">
    @endif

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased group/sidebar">

    {{-- NAVBAR --}}
    <x-layout.user.nav />

    <div class="flex">
        {{-- SIDEBAR CUSTOM (SAMA DENGAN DASHBOARD) --}}
        <aside id="logo-sidebar"
            class="fixed top-0 left-0 z-40 h-screen pt-20 transition-all duration-300 ease-in-out bg-white border-r border-gray-200 overflow-hidden group shadow-sm -translate-x-full w-64 sm:translate-x-0 sm:w-20 sm:hover:w-64"
            aria-label="Sidebar">

            <div class="h-full px-4 pb-4 overflow-y-auto overflow-x-hidden bg-white scrollbar-hide">
                <ul class="space-y-2 font-medium">

                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Manager') || auth()->user()->hasRole('Admin Gudang'))
                        <li>
                            @php
                                $urlDashboard = '#';
                                if (auth()->user()->hasRole('Super Admin')) {
                                    $urlDashboard = Route::has('super-admin.dashboard') ? route('super-admin.dashboard') : url('/dashboard/super-admin');
                                } elseif (auth()->user()->hasRole('Manager')) {
                                    $urlDashboard = Route::has('manager.dashboard') ? route('manager.dashboard') : url('/dashboard/manager');
                                } elseif (auth()->user()->hasRole('Admin Gudang')) {
                                    $urlDashboard = Route::has('admin-gudang.dashboard') ? route('admin-gudang.dashboard') : url('/dashboard/admin-gudang');
                                }
                            @endphp
                            <a href="{{ $urlDashboard }}"
                                class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold text-gray-700">Dashboard</span>
                            </a>
                        </li>
                    @endif

                    {{-- Beranda (Menu Fitur) --}}
                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin Gudang'))
                        <li>
                            <a href="{{ route('beranda') }}"
                                class="flex items-center p-3 text-blue-600 bg-blue-50 rounded-xl group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 transition-colors" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 19v-8.5a1 1 0 0 0-.4-.8l-7-5.25a1 1 0 0 0-1.2 0l-7 5.25a1 1 0 0 0-.4.8V19a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1" />
                                    </svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-bold">Beranda</span>
                            </a>
                        </li>
                    @endif

                    {{-- Costumer, Barang, Supplier, Proses --}}
                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin Gudang'))
                        <li>
                            <a href="{{ Route::has('costumer.index') ? route('costumer.index') : '#' }}" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 12 12"><path fill="currentColor" d="M6.153 7.008A1.5 1.5 0 0 1 7.5 8.5c0 .771-.47 1.409-1.102 1.83c-.635.424-1.485.67-2.398.67s-1.763-.246-2.398-.67C.969 9.91.5 9.271.5 8.5A1.5 1.5 0 0 1 2 7h4zM10.003 7a1.5 1.5 0 0 1 1.5 1.5c0 .695-.432 1.211-.983 1.528c-.548.315-1.265.472-2.017.472q-.38-.001-.741-.056c.433-.512.739-1.166.739-1.944A2.5 2.5 0 0 0 7.997 7zM4.002 1.496A2.253 2.253 0 1 1 4 6.001a2.253 2.253 0 0 1 0-4.505m4.75 1.001a1.75 1.75 0 1 1 0 3.5a1.75 1.75 0 0 1 0-3.5" /></svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Costumer</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('barang.index') ? route('barang.index') : '#' }}" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 24 24"><path fill="currentColor" d="m17.578 4.432l-2-1.05C13.822 2.461 12.944 2 12 2s-1.822.46-3.578 1.382l-.321.169l8.923 5.099l4.016-2.01c-.646-.732-1.688-1.279-3.462-2.21m4.17 3.534l-3.998 2V13a.75.75 0 0 1-1.5 0v-2.286l-3.5 1.75v9.44c.718-.179 1.535-.607 2.828-1.286l2-1.05c2.151-1.129 3.227-1.693 3.825-2.708c.597-1.014.597-2.277.597-4.8v-.117c0-1.893 0-3.076-.252-3.978M11.25 21.904v-9.44l-8.998-4.5C2 8.866 2 10.05 2 11.941v.117c0 2.525 0 3.788.597 4.802c.598 1.015 1.674 1.58 3.825 2.709l2 1.049c1.293.679 2.11 1.107 2.828 1.286M2.96 6.641l9.04 4.52l3.411-1.705l-8.886-5.078l-.103.054c-1.773.93-2.816 1.477-3.462 2.21" /></svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Barang</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('supplier.index') ? route('supplier.index') : '#' }}" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 24 24"><path fill="currentColor" d="M19.15 8a2 2 0 0 0-1.72-1H15V5a1 1 0 0 0-1-1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 1 1.73a3.49 3.49 0 0 0 7 .27h3.1a3.48 3.48 0 0 0 6.9 0a2 2 0 0 0 2-2v-3a1.1 1.1 0 0 0-.14-.52zM15 9h2.43l1.8 3H15zM6.5 19A1.5 1.5 0 1 1 8 17.5A1.5 1.5 0 0 1 6.5 19m10 0a1.5 1.5 0 1 1 1.5-1.5a1.5 1.5 0 0 1-1.5 1.5" /></svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Supplier</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('proses.index') ? route('proses.index') : '#' }}" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 24 24"><path fill="currentColor" d="M19.5 12c0-.23-.01-.45-.03-.68l1.86-1.41c.4-.3.51-.86.26-1.3l-1.87-3.23a.987.987 0 0 0-1.25-.42l-2.15.91c-.37-.26-.76-.49-1.17-.68l-.29-2.31c-.06-.5-.49-.88-.99-.88h-3.73c-.51 0-.94.38-1 .88l-.29 2.31c-.41.19-.8.42-1.17.68l-2.15-.91c-.46-.2-1-.02-1.25.42L2.41 8.62c-.25.44-.14.99.26 1.3l1.86 1.41a7.3 7.3 0 0 0 0 1.35l-1.86 1.41c-.4.3-.51.86-.26 1.3l1.87 3.23c.25.44.79.62 1.25.42l2.15-.91c.37.26.76.49 1.17.68l.29 2.31c.06.5.49.88.99.88h3.73c.5 0 .93-.38.99-.88l.29-2.31c.41-.19.8-.42 1.17-.68l2.15.91c.46.2 1 .02 1.25-.42l1.87-3.23c.25-.44.14-.99-.26-1.3l-1.86-1.41c.03-.23.04-.45.04-.68m-7.46 3.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5s3.5 1.57 3.5 3.5s-1.57 3.5-3.5 3.5" /></svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Proses</span>
                            </a>
                        </li>
                    @endif

                    {{-- Admin Only Section --}}
                    @if (auth()->user()->hasRole('Super Admin'))
                        <li>
                            <a href="{{ Route::has('perusahaan.index') ? route('perusahaan.index') : '#' }}" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M19.618 1H4.382L2 5.764V11h20V5.764zM3 17v-4.5h2V17h7v-4.5h2V21h5v-8.5h2V23H3z" clip-rule="evenodd" /></svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Perusahaan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ Route::has('user.index') ? route('user.index') : '#' }}" class="flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 24 24"><path fill="currentColor" d="M12 14v8H4a8 8 0 0 1 8-8m0-1c-3.315 0-6-2.685-6-6s2.685-6 6-6s6 2.685 6 6s-2.685 6-6 6m9 4h1v5h-8v-5h1v-1a3 3 0 1 1 6 0zm-2 0v-1a1 1 0 1 0-2 0v1z" /></svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">User</span>
                            </a>
                        </li>
                    @endif

                    {{-- Laporan Dropdown --}}
                    <li x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center p-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 group/item transition-all whitespace-nowrap">
                            <div class="min-w-[32px] flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400 group-hover/item:text-blue-600 transition-colors" viewBox="0 0 24 24"><path fill="currentColor" d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2m1 18h2v-6H7zm4 0h2v-8h-2zm4 0h2v-4h-2z" /></svg>
                            </div>
                            <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-bold uppercase text-xs tracking-widest flex-1 text-left">Laporan</span>
                            <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak class="mt-1 space-y-1 px-2">
                            <a href="{{ Route::has('laporan-produksi') ? route('laporan-produksi') : '#' }}" class="flex opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center p-2.5 text-sm text-gray-500 rounded-lg hover:bg-gray-50 hover:text-blue-600 pl-12 font-medium">Produksi</a>
                            <a href="{{ Route::has('laporan-gudang') ? route('laporan-gudang') : '#' }}" class="flex opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center p-2.5 text-sm text-gray-500 rounded-lg hover:bg-gray-50 hover:text-blue-600 pl-12 font-medium">Gudang</a>
                        </div>
                    </li>

                    <hr class="my-2 border-gray-100 opacity-0 group-hover:opacity-100 transition-opacity">

                    {{-- Logout --}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center p-3 text-red-500 rounded-xl hover:bg-red-50 group/item transition-all whitespace-nowrap">
                                <div class="min-w-[32px] flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400 group-hover/item:text-red-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <span class="ms-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 font-semibold">Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        {{-- AREA KONTEN --}}
        <main id="main-content" class="flex-1 min-h-screen transition-all duration-300 pt-20 sm:ml-20 group-hover/sidebar:sm:ml-64 p-6">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Masukkan script alert dan modal Anda di sini seperti sebelumnya
    </script>
</body>
</html>