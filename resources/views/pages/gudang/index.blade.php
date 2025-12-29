<x-layout.beranda.app>
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
                                <a href="{{ route('inventory.create-produksi') }}"
                                    class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Produksi
                                </a>
                                <a href="{{ route('inventory.create-bp') }}"
                                    class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full"></span> Bahan Penolong
                                </a>
                                {{-- <a href="{{ route('inventory.create-bb') }}"
                                    class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span> Bahan Baku
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. GROUPBY INVENTORY (Card by Jenis Barang) --}}
            <div class="space-y-12">
                @php
                    // 1. Filter Koleksi untuk Hasil Produksi
                    $produksiItems = $inventory->filter(
                        fn($i) => in_array(strtoupper($i->barang->jenisBarang->kode), ['FG', 'WIP', 'EC']),
                    );

                    // 2. Filter Koleksi untuk Bahan Penolong
                    $penolongItems = $inventory->filter(fn($i) => strtoupper($i->barang->jenisBarang->kode) === 'BP');
                @endphp

                {{-- GRUP 1: HASIL PRODUKSI (DIPISAH PER KATEGORI FG, WIP, EC) --}}
                <section>
                    <div class="flex items-center justify-between mb-6 border-l-4 border-blue-600 pl-4">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hasil Produksi</h2>
                            <p class="text-sm text-gray-500 font-medium">Monitoring stok produk FG, WIP, dan Eceran</p>
                        </div>
                        <span
                            class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                            {{ $produksiItems->count() }} Total Item
                        </span>
                    </div>

                    @if ($produksiItems->isEmpty())
                        <div
                            class="bg-gray-50 rounded-2xl p-10 text-center border-2 border-dashed border-gray-200 text-gray-400">
                            Tidak ada data hasil produksi ditemukan
                        </div>
                    @else
                        {{-- Grid untuk menampung Card Kategori --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {{-- Kita Group berdasarkan nama jenis agar muncul card FG sendiri, WIP sendiri, dst --}}
                            @foreach ($produksiItems->groupBy('barang.jenisBarang.nama_jenis') as $jenis => $items)
                                <x-gudang.card-index :jenis="$jenis" :items="$items" accent="blue" />
                            @endforeach
                        </div>
                    @endif
                </section>

                {{-- GRUP 2: BAHAN PENOLONG (LANGSUNG GRID TANPA CARD KATEGORI) --}}
                <section>
                    <div class="flex items-center justify-between mb-6 border-l-4 border-emerald-500 pl-4">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Bahan Penolong</h2>
                            <p class="text-sm text-gray-500 font-medium">Stok material pendukung operasional (BP)</p>
                        </div>
                        <span
                            class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-100">
                            {{ $penolongItems->count() }} Total Item
                        </span>
                    </div>

                    @if ($penolongItems->isEmpty())
                        <div
                            class="bg-gray-50 rounded-2xl p-10 text-center border-2 border-dashed border-gray-200 text-gray-400">
                            Tidak ada data bahan penolong ditemukan
                        </div>
                    @else
                        {{-- Grid Item BP --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                            @foreach ($penolongItems as $item)
                                <x-gudang.grid-index :item="$item" accent="emerald" />
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $inventory->links() }}
            </div>
        </div>
    </div>
</x-layout.beranda.app>
