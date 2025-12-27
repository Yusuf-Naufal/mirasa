<x-layout.beranda.app>

    <div class="md:px-10 py-6 flex flex-col">
        {{-- MENU FITUR --}}
        <div class="flex-1 pt-20 h-fit">
            <div class="max-w-7xl mb-6">
                <h1 class="text-xl font-bold text-gray-800">Menu Fitur</h1>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mx-auto max-w-7xl items-center">
                <a href="{{ route('super-admin.dashboard') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-blue-50">
                    <svg class="w-12 h-12 mb-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Dashboard</span>
                </a>

                <a href=""
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-yellow-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-yellow-600" viewBox="0 0 16 16">
                        <path fill="currentColor"
                            d="M16 4L7.94 0L0 4v1h1v11h2V7h10v9h2V5h1zM4 6V5h2v1zm3 0V5h2v1zm3 0V5h2v1z" />
                        <path fill="currentColor" d="M6 9H5V8H4v3h3V8H6zm0 4H5v-1H4v3h3v-3H6zm4 0H9v-1H8v3h3v-3h-1z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Gudang</span>
                </a>

                <a href=""
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-green-600" viewBox="0 0 16 16">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M2.5 2a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5v-11a.5.5 0 0 0-.5-.5zM4 6h6V5H4zm7 0h1V5h-1zm-1 2.5H4v-1h6zm1 0h1v-1h-1zM10 11H4v-1h6zm1 0h1v-1h-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Kartu Stok</span>
                </a>
            </div>
        </div>
    </div>

</x-layout.beranda.app>
