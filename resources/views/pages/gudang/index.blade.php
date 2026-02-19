<x-layout.beranda.app title="Inventory">
    <div class="md:px-10 py-6 flex flex-col">
        <div class="flex-1 pt-12">

            {{-- 1. INFORMASI GUDANG & HEADER --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('beranda') }}"
                        class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Manajemen inventory</h1>
                    <div class="flex items-center mt-1 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-sm font-medium">Gudang:
                            {{ auth()->user()->perusahaan->nama_perusahaan ?? 'Nama Perusahaan' }}</span>
                    </div>
                </div>
            </div>

            {{-- 2. ACTION BAR (SEARCH, FILTER) --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8 flex flex-col gap-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">

                    {{-- FORM SEARCH & FILTER --}}
                    <form action="{{ route('inventory.index') }}" method="GET"
                        class="flex flex-col lg:flex-row gap-4 flex-1 w-full">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                                placeholder="Cari nama barang atau kode...">
                        </div>

                        <div class="flex flex-wrap md:flex-nowrap gap-3">
                            <select name="id_jenis"
                                class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-none min-w-[150px]">
                                <option value="">Semua Jenis</option>
                                @foreach ($jenisBarang as $j)
                                    <option value="{{ $j->id }}"
                                        {{ request('id_jenis') == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_jenis }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition-colors flex items-center justify-center">
                                Filter
                            </button>

                            <a href="{{ route('inventory.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-5 py-2.5 rounded-xl font-medium transition-colors flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                    </form>

                    {{-- BUTTON TAMBAH BARANG DENGAN DROPDOWN (Pemisahan) --}}
                    @canany(['inventory.create-produksi, inventory.create-bahan-baku, inventory.create-bahan-penolong'])
                        <div class="relative inline-block text-left w-full lg:w-auto" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" type="button"
                                class="inline-flex justify-center items-center w-full lg:w-auto gap-x-1.5 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-700 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Barang Masuk
                                <svg class="-mr-1 h-5 w-5 text-green-200" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            {{-- MENU PILIHAN --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                                <div class="py-1">
                                    @can('inventory.create-produksi')
                                        <a href="{{ route('inventory.create-produksi') }}"
                                            class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Produksi
                                        </a>
                                    @endcan
                                    @can('inventory.create-bahan-baku')
                                        <a href="{{ route('inventory.create-bb') }}"
                                            class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-purple-500 rounded-full"></span> Bahan Baku
                                        </a>
                                    @endcan
                                    @can('inventory.create-bahan-penolong')
                                        <a href="{{ route('inventory.create-bp') }}"
                                            class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full"></span> Bahan Penolong
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endcanany
                </div>
            </div>

            {{-- GRID INDIKATOR STATISTIK --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-red-600 flex flex-col justify-between transition-transform hover:scale-[1.02]">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-red-50 rounded-lg">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Stok Habis</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['habis'] }}</h3>
                        <span
                            class="text-[10px] bg-red-100 text-red-700 px-2 py-1 rounded-md font-bold uppercase">Kritis</span>
                    </div>
                </div>

                <div
                    class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-orange-500 flex flex-col justify-between transition-transform hover:scale-[1.02]">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-orange-50 rounded-lg">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Dibawah Limit</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['limit'] }}</h3>
                        <span
                            class="text-[10px] bg-orange-100 text-orange-700 px-2 py-1 rounded-md font-bold uppercase">Restock</span>
                    </div>
                </div>

                <div
                    class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-yellow-400 flex flex-col justify-between transition-transform hover:scale-[1.02]">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-yellow-50 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-gray-500 uppercase">Mendekati Limit</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['warning'] }}</h3>
                        <span
                            class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md font-bold uppercase">Waspada</span>
                    </div>
                </div>

            </div>

            {{-- 3. GROUPBY INVENTORY (Card by Jenis Barang) --}}
            <div class="space-y-12">

                {{-- GRUP 1: HASIL PRODUKSI (Hanya tampil jika ada isi) --}}
                @if ($produksiItems->isNotEmpty())
                    <section>
                        <div class="flex items-center justify-between mb-6 border-l-4 border-blue-600 pl-4">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hasil Produksi
                                </h2>
                                <p class="text-sm text-gray-500 font-medium">Monitoring stok produk FG, WIP, dan Eceran
                                </p>
                            </div>
                        </div>

                        <div class="max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($produksiItems->groupBy('barang.jenisBarang.nama_jenis') as $jenis => $items)
                                    <x-gudang.card-index :jenis="$jenis" :items="$items" accent="blue" />
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                {{-- GRUP 2: BAHAN BAKU (Hanya tampil jika ada isi) --}}
                @if ($bahanBakuItems->total() > 0)
                    <section>
                        <div class="flex items-center justify-between mb-6 border-l-4 border-amber-500 pl-4">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Bahan Baku</h2>
                                <p class="text-sm text-gray-500 font-medium">Stok material utama produksi (BB)</p>
                            </div>
                            <span
                                class="bg-amber-50 text-amber-700 text-xs font-bold px-3 py-1 rounded-full border border-amber-100">
                                {{ $bahanBakuItems->total() }} Total Item
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                            @foreach ($bahanBakuItems as $item)
                                <x-gudang.grid-index :item="$item" accent="amber" />
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <div class="flex justify-end">
                                {{ $bahanBakuItems->appends(['page_bp' => request('page_bp'), 'search' => request('search'), 'id_jenis' => request('id_jenis')])->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </section>
                @endif

                {{-- GRUP 3: BAHAN PENOLONG (Hanya tampil jika ada isi) --}}
                @if ($penolongItems->total() > 0)
                    <section>
                        <div class="flex items-center justify-between mb-6 border-l-4 border-emerald-500 pl-4">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Bahan Penolong
                                </h2>
                                <p class="text-sm text-gray-500 font-medium">Stok material pendukung operasional (BP)
                                </p>
                            </div>
                            <span
                                class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-100">
                                {{ $penolongItems->total() }} Total Item
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                            @foreach ($penolongItems as $item)
                                <x-gudang.grid-index :item="$item" accent="emerald" />
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <div class="flex justify-end">
                                {{ $penolongItems->appends(['page_bb' => request('page_bb'), 'search' => request('search'), 'id_jenis' => request('id_jenis')])->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </section>
                @endif

                {{-- Pesan Jika Semua Kosong --}}
                @if ($produksiItems->isEmpty() && $bahanBakuItems->isEmpty() && $penolongItems->isEmpty())
                    <div class="bg-white rounded-3xl p-20 text-center border border-gray-100 shadow-sm">
                        <div class="flex justify-center mb-4">
                            <svg class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Barang tidak ditemukan</h3>
                        <p class="text-gray-500">Tidak ada barang yang cocok dengan kata kunci
                            "{{ request('search') }}"</p>
                        <a href="{{ route('inventory.index') }}"
                            class="mt-4 inline-block text-blue-600 font-semibold hover:underline">Lihat semua
                            barang</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layout.beranda.app>
