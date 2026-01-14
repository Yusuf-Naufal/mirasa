<x-layout.beranda.app title="Daftar Barang Keluar">
    <div class="md:px-10 py-6 flex flex-col" x-data="{ tab: 'produksi', menuOpen: false }">
        <div class="flex-1 pt-12">

            {{-- 1. HEADER --}}
            <div class="mb-8">
                <a href="{{ route('beranda') }}"
                    class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-2">Catatan Barang Keluar</h1>
                <p class="text-sm text-gray-500 font-medium">Manajemen distribusi stok gudang</p>
            </div>

            {{-- 2. SEARCH & DROPDOWN BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="w-full flex gap-2 items-center">
                    <form action="{{ route('barang-keluar.index') }}" method="GET"
                        class="relative w-full md:max-w-md">

                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        @if (request('id_perusahaan'))
                            <input type="hidden" name="id_perusahaan" value="{{ request('id_perusahaan') }}">
                        @endif
                        @if (request('date_range'))
                            <input type="hidden" name="date_range" value="{{ request('date_range') }}">
                        @endif

                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama barang..."
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-2xl bg-white focus:ring-2 focus:ring-blue-500 transition-all shadow-sm text-sm">
                    </form>

                    <button onclick="openModal('filterModal')"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M9 5a1 1 0 1 0 0 2a1 1 0 0 0 0-2M6.17 5a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-7.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 0 1 0-2zM15 11a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-2.83 0a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-1.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 1 1 0-2zM9 17a1 1 0 1 0 0 2a1 1 0 0 0 0-2m-2.83 0a3.001 3.001 0 0 1 5.66 0H19a1 1 0 1 1 0 2h-7.17a3.001 3.001 0 0 1-5.66 0H5a1 1 0 1 1 0-2z" />
                        </svg>
                        <span class="hidden md:block md:ml-2">Filter</span>
                    </button>
                </div>

                {{-- Dropdown Button --}}
                <div class="relative w-full md:w-56">
                    <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Catat Keluar
                        <svg class="w-4 h-4 transition-transform" :class="menuOpen ? 'rotate-180' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="menuOpen" x-transition
                        class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-[70] overflow-hidden">
                        <a href="{{ route('barang-keluar.create-produksi') }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-yellow-50 text-gray-700 transition-colors">
                            <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                    viewBox="0 0 48 48">
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M24 1.5q-1.847 0-3.47.019c.056 2.59.186 5.094.294 6.863l2.104-1.335a2 2 0 0 1 2.144 0l2.104 1.335c.108-1.77.238-4.273.295-6.863A313 313 0 0 0 24 1.5m-12.788.308c1.557-.089 3.647-.18 6.318-.24c.068 3.12.24 6.104.356 7.876c.125 1.903 2.235 2.939 3.82 1.932L24 9.92l2.295 1.457c1.585 1.006 3.694-.03 3.82-1.933a188 188 0 0 0 .355-7.876c2.67.06 4.76.151 6.318.24c2.793.16 5.106 2.213 5.377 5.089c.179 1.895.335 4.564.335 8.103s-.156 6.208-.335 8.103c-.271 2.876-2.584 4.93-5.377 5.089c-2.646.15-6.832.308-12.788.308s-10.142-.157-12.788-.308c-2.793-.16-5.106-2.213-5.377-5.089C5.656 21.208 5.5 18.54 5.5 15s.156-6.208.335-8.103c.271-2.876 2.584-4.93 5.377-5.089M27 20.5a1.5 1.5 0 0 0 0 3h8a1.5 1.5 0 0 0 0-3zm1.5-4.5a1.5 1.5 0 0 1 1.5-1.5h5a1.5 1.5 0 0 1 0 3h-5a1.5 1.5 0 0 1-1.5-1.5M24 46.5a735 735 0 0 1-14.19-.12C5.704 46.3 1.5 43.776 1.5 39s4.203-7.3 8.31-7.38c3.251-.063 7.921-.12 14.19-.12s10.939.057 14.189.12c4.108.08 8.311 2.603 8.311 7.38s-4.203 7.3-8.31 7.38c-3.251.063-7.921.12-14.19.12M9 39a3 3 0 1 0 6 0a3 3 0 0 0-6 0m15 3a3 3 0 1 1 0-6a3 3 0 0 1 0 6m9-3a3 3 0 1 0 6 0a3 3 0 0 0-6 0"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex flex-col"><span class="text-sm font-bold">Barang Produksi</span><span
                                    class="text-[10px] text-gray-400">Bahan Penolong</span></div>
                        </a>
                        <a href="{{ route('barang-keluar.create-penjualan') }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 text-gray-700 transition-colors border-t border-gray-50">
                            <div class="p-2 bg-green-100 text-green-600 rounded-lg"><svg class="w-4 h-4"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg></div>
                            <div class="flex flex-col"><span class="text-sm font-bold">Penjualan /
                                    Transfer</span><span class="text-[10px] text-gray-400">Produk Jadi
                                    (FG/WIP/EC)</span></div>
                        </a>
                        <a href="{{ route('barang-keluar.create-bahan-baku') }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 text-gray-700 transition-colors border-t border-gray-50">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 2048 2048">
                                    <path fill="currentColor"
                                        d="m1344 2l704 352v785l-128-64V497l-512 256v258l-128 64V753L768 497v227l-128-64V354zm0 640l177-89l-463-265l-211 106zm315-157l182-91l-497-249l-149 75zm-507 654l-128 64v-1l-384 192v455l384-193v144l-448 224L0 1735v-676l576-288l576 288zm-640 710v-455l-384-192v454zm64-566l369-184l-369-185l-369 185zm576-1l448-224l448 224v527l-448 224l-448-224zm384 576v-305l-256-128v305zm384-128v-305l-256 128v305zm-320-288l241-121l-241-120l-241 120z" />
                                </svg>
                            </div>
                            <div class="flex flex-col"><span class="text-sm font-bold">Bahan Baku</span>
                                <span class="text-[10px] text-gray-400">Bahan baku</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. TABS NAVIGATION --}}
            <div class="flex gap-4 border-b border-gray-100 mb-6">
                <a href="{{ route('barang-keluar.index', ['tab' => 'PRODUKSI']) }}"
                    class="pb-4 px-2 border-b-2 font-bold text-sm transition-all {{ $activeTab === 'PRODUKSI' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    Barang Produksi
                </a>

                <a href="{{ route('barang-keluar.index', ['tab' => 'DISTRIBUSI']) }}"
                    class="pb-4 px-2 border-b-2 font-bold text-sm transition-all {{ $activeTab === 'DISTRIBUSI' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    Distribusi
                </a>

                <a href="{{ route('barang-keluar.index', ['tab' => 'BAHAN BAKU']) }}"
                    class="pb-4 px-2 border-b-2 font-bold text-sm transition-all {{ $activeTab === 'BAHAN BAKU' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                    Bahan Baku
                </a>
            </div>

            {{-- AREA TABEL --}}
            <div class="mt-4">
                @if ($activeTab === 'PRODUKSI')
                    <x-barangkeluar.table-produksi :data="$data" />
                @elseif ($activeTab === 'BAHAN BAKU')
                    <x-barangkeluar.table-bahan-baku :data="$data" />
                @else
                    <x-barangkeluar.table-distribusi :data="$data" />
                @endif
            </div>

            <div class="mt-6">
                {{ $data->links('vendor.pagination.custom') }}
            </div>

        </div>
    </div>

    {{-- Filter Modal --}}
    <div id="filterModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60" onclick="closeModal('filterModal')"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl" id="modalContent">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800">Filter Lanjutan</h2>
                <button onclick="closeModal('filterModal')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 15 15">
                        <path fill="currentColor"
                            d="M10.969 3.219a.574.574 0 1 1 .812.812L8.313 7.5l3.468 3.469l.074.09a.575.575 0 0 1-.796.796l-.09-.074L7.5 8.312l-3.469 3.47a.574.574 0 1 1-.812-.813L6.688 7.5l-3.47-3.469l-.073-.09a.575.575 0 0 1 .796-.797l.09.075L7.5 6.687z" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('barang-keluar.index') }}" method="GET" class="p-6">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div class="space-y-5">
                    {{-- Filter Berdasarkan Perusahaan (Hanya Super Admin) --}}
                    @if (auth()->user()->hasRole('Super Admin'))
                        <div>
                            <label for="id_perusahaan"
                                class="block text-sm font-semibold text-gray-700 mb-1">Perusahaan</label>
                            <select name="id_perusahaan" id="id_perusahaan"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FFC829] outline-none">
                                <option value="">Semua Perusahaan</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }} ({{ $p->kota }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if (!auth()->user()->hasRole('Super Admin'))
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Pilih Barang (Tab {{ ucfirst($activeTab) }})
                            </label>
                            <select name="id_barang"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-blue-500">
                                <option value="">Semua Barang</option>
                                @foreach ($listBarang as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('id_barang') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Filter Rentang Tanggal --}}
                    <div>
                        <label for="date_range" class="block text-sm font-semibold text-gray-700 mb-1">Rentang Tanggal
                            Keluar</label>
                        <div class="relative">
                            <input type="text" name="date_range" id="date_range"
                                value="{{ request('date_range') }}" placeholder="Pilih rentang tanggal.."
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 outline-none">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('barang-keluar.index') }}"
                        class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-600 hover:bg-gray-50">Reset</a>
                    <button type="submit"
                        class="flex-1 rounded-xl bg-gray-600 py-3 text-sm font-bold text-white hover:bg-gray-800 transition-colors shadow-sm">Terapkan
                        Filter</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true,
                altInput: true,
                altFormat: "d M Y",
            });
        });
    </script>
</x-layout.beranda.app>
