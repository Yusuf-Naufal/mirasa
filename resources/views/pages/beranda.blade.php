<x-layout.beranda.app title="Beranda">

    <div class="md:px-10 py-6 flex flex-col">
        {{-- MENU FITUR --}}
        <div class="flex-1 pt-20 h-fit">
            <div class="max-w-7xl mb-6">
                <h1 class="text-xl font-bold text-gray-800">Menu Fitur</h1>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mx-auto max-w-7xl items-center">
                <a href="{{ route('super-admin.dashboard') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-purple-50">
                    <svg class="w-12 h-12 mb-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Dashboard</span>
                </a>

                <a href="{{ route('inventory.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-yellow-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-yellow-600" viewBox="0 0 16 16">
                        <path fill="currentColor"
                            d="M16 4L7.94 0L0 4v1h1v11h2V7h10v9h2V5h1zM4 6V5h2v1zm3 0V5h2v1zm3 0V5h2v1z" />
                        <path fill="currentColor" d="M6 9H5V8H4v3h3V8H6zm0 4H5v-1H4v3h3v-3H6zm4 0H9v-1H8v3h3v-3h-1z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Gudang</span>
                </a>

                <a href="{{ route('produksi.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-gray-600" viewBox="0 0 48 48">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M24 1.5q-1.847 0-3.47.019c.056 2.59.186 5.094.294 6.863l2.104-1.335a2 2 0 0 1 2.144 0l2.104 1.335c.108-1.77.238-4.273.295-6.863A313 313 0 0 0 24 1.5m-12.788.308c1.557-.089 3.647-.18 6.318-.24c.068 3.12.24 6.104.356 7.876c.125 1.903 2.235 2.939 3.82 1.932L24 9.92l2.295 1.457c1.585 1.006 3.694-.03 3.82-1.933a188 188 0 0 0 .355-7.876c2.67.06 4.76.151 6.318.24c2.793.16 5.106 2.213 5.377 5.089c.179 1.895.335 4.564.335 8.103s-.156 6.208-.335 8.103c-.271 2.876-2.584 4.93-5.377 5.089c-2.646.15-6.832.308-12.788.308s-10.142-.157-12.788-.308c-2.793-.16-5.106-2.213-5.377-5.089C5.656 21.208 5.5 18.54 5.5 15s.156-6.208.335-8.103c.271-2.876 2.584-4.93 5.377-5.089M27 20.5a1.5 1.5 0 0 0 0 3h8a1.5 1.5 0 0 0 0-3zm1.5-4.5a1.5 1.5 0 0 1 1.5-1.5h5a1.5 1.5 0 0 1 0 3h-5a1.5 1.5 0 0 1-1.5-1.5M24 46.5a735 735 0 0 1-14.19-.12C5.704 46.3 1.5 43.776 1.5 39s4.203-7.3 8.31-7.38c3.251-.063 7.921-.12 14.19-.12s10.939.057 14.189.12c4.108.08 8.311 2.603 8.311 7.38s-4.203 7.3-8.31 7.38c-3.251.063-7.921.12-14.19.12M9 39a3 3 0 1 0 6 0a3 3 0 0 0-6 0m15 3a3 3 0 1 1 0-6a3 3 0 0 1 0 6m9-3a3 3 0 1 0 6 0a3 3 0 0 0-6 0"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-700">Produksi</span>
                </a>

                <a href="{{ route('bahan-baku.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-green-600" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M3 6.25A3.25 3.25 0 0 1 6.25 3h11.5A3.25 3.25 0 0 1 21 6.25v4.762a3.28 3.28 0 0 0-2.61.95l-5.903 5.903a3.7 3.7 0 0 0-.931 1.57c-.345-.536-.87-.915-1.412-1.133c-.691-.278-1.385-.16-1.936.035a5.5 5.5 0 0 0-.729.326a.5.5 0 0 1-.089.07L5.693 19.77c-.467.275-.907.365-1.298.335a1.9 1.9 0 0 1-.9-.311c-.387-.255-.496-.683-.496-1.005V6.25m16.1 6.42l-5.903 5.902a2.7 2.7 0 0 0-.706 1.247l-.428 1.712c-.355.17-.71.202-1.133.105c-.126-.03-.18-.175-.127-.293c.43-.962-.19-1.776-1.03-2.113c-.955-.385-2.226.515-3.292 1.268c-.592.42-1.12.793-1.496.876c-.525.117-1.162-.123-1.631-.38c-.209-.113-.487.072-.388.288c.242.529.731 1.133 1.71 1.255c.98.121 1.766-.347 2.55-.815c.583-.348 1.165-.696 1.826-.799c.086-.013.144.088.105.166c-.242.484-.356 1.37.218 1.818c.848.662 3.237.292 3.828.088q.073-.007.148-.027l1.83-.457a2.7 2.7 0 0 0 1.248-.707l5.903-5.902a2.286 2.286 0 0 0-3.233-3.232" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Bahan Baku</span>
                </a>

                <a href="{{ route('barang-keluar.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-red-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-red-600" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m21.706 5.292l-2.999-2.999A1 1 0 0 0 18 2H6a1 1 0 0 0-.707.293L2.294 5.292A1 1 0 0 0 2 6v13c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6a1 1 0 0 0-.294-.708M6.414 4h11.172l1 1H5.414zM14 14v3h-4v-3H7l5-5l5 5z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">Barang Keluar</span>
                </a>

                <a href="{{ route('barang-masuk.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-blue-600" viewBox="0 0 16 16">
                        <path fill="currentColor"
                            d="M13 1H3L0 4v10.5a.5.5 0 0 0 .5.5h15a.5.5 0 0 0 .5-.5V4zM8 13L3 9h3V6h4v3h3zM2.414 3l1-1h9.172l1 1z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Barang Masuk</span>
                </a>

                <a href="{{ route('gas.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-cyan-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-cyan-600" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                            d="M9 8a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v13.4a.6.6 0 0 1-.6.6H9.6a.6.6 0 0 1-.6-.6zm0 3h6m-3-6V2m0 0h-1m1 0h1" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-cyan-700">Penggunaan Gas</span>
                </a>

                <a href="{{ route('pengeluaran.index') }}"
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-amber-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-amber-600" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5">
                            <path d="M14.5 14.001a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0" />
                            <path
                                d="M8 7.89c-1.12-.006-2.44-.132-4.122-.465C2.921 7.235 2 7.946 2 8.922V18.94a1.47 1.47 0 0 0 1.145 1.441c6.965 1.536 8.104-.27 12.855-.27c1.51 0 2.736.143 3.676.32c1.096.207 2.324-.632 2.324-1.747V8.91c0-.569-.324-1.083-.867-1.251c-.81-.251-2.188-.57-4.133-.655" />
                            <path
                                d="M2 11.001c1.951 0 3.705-1.595 3.929-3.246M18.5 7.501c0 2.04 1.765 3.969 3.5 3.969m0 5.531c-1.9 0-3.74 1.31-3.898 3.098M6 20.497a4 4 0 0 0-4-4M9.5 5.501s1.8-2.5 2.5-2.5m2.5 2.5s-1.8-2.5-2.5-2.5m0 0v5.5" />
                        </g>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-amber-700">Pengeluaran</span>
                </a>

                <a href=""
                    class="group flex flex-col items-center p-6 bg-white rounded-lg shadow transition hover:shadow-lg hover:bg-emerald-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-emerald-600" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-width="2"
                            d="M1 1h3v3H1zm12 0h3v3h-3zM4 2h9m2 7h5M4 15h9M1 13h3v3H1zm12 0h3v3h-3zM2 4v9m13-9v9m5-5h3v3h-3zm-9 14h9M8 20h3v3H8zm12 0h3v3h-3zM9 16v4m13-9v9" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-700">Asset</span>
                </a>


            </div>
        </div>
    </div>

</x-layout.beranda.app>
