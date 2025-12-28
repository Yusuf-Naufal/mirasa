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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
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
                                <a href="{{ route('inventory.create-bb') }}"
                                    class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span> Bahan Baku
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. GROUPBY INVENTORY (Card by Jenis Barang) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($inventory->groupBy('barang.jenisBarang.nama_jenis') as $jenis => $items)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-gray-700">{{ $jenis ?: 'Tanpa Kategori' }}</h3>
                            <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                                {{ $items->count() }} Item
                            </span>
                        </div>

                        <div class="p-5 flex-1 space-y-4">
                            @foreach ($items as $item)
                                <a href="{{ route('inventory.show', $item->id) }}"
                                    class="flex items-center justify-between group hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-colors overflow-hidden">
                                            @if ($item->barang->foto)
                                                <img src="{{ asset('storage/' . $item->barang->foto) }}"
                                                    alt="{{ $item->barang->nama_barang }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $item->barang->nama_barang }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $item->barang->kode }}</p>
                                        </div>
                                    </div>

                                    @php
                                        $stokAktual = $item->stok;
                                        $stokMin = $item->minimum_stok ?? 0;
                                        // Indikator Kuning jika stok di atas minimum tapi kurang dari atau sama dengan (stok min + 5)
                                        $ambangKuning = $stokMin + 30;
                                    @endphp

                                    <div class="text-right flex flex-col items-end gap-1">
                                        {{-- Angka Stok --}}
                                        <p class="text-sm font-bold text-slate-700">
                                            {{ number_format($stokAktual, 0, ',', '.') }}
                                            <span
                                                class="text-[10px] text-slate-400 font-normal uppercase">{{ $item->barang->satuan }}</span>
                                        </p>

                                        {{-- Indikator Titik (Dot Indicator) --}}
                                        <div
                                            class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-slate-50 border border-slate-100">
                                            @if ($stokAktual <= $stokMin)
                                                {{-- Merah: Stok Kritis --}}
                                                <span class="relative flex h-2 w-2">
                                                    <span
                                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span
                                                        class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                                                </span>
                                                <span
                                                    class="text-[10px] font-bold text-red-600 uppercase tracking-tighter">Kritis</span>
                                            @elseif ($stokAktual <= $ambangKuning)
                                                {{-- Kuning: Mendekati Limit --}}
                                                <span class="relative flex h-2 w-2">
                                                    <span
                                                        class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                </span>
                                                <span
                                                    class="text-[10px] font-bold text-amber-600 uppercase tracking-tighter">Limit</span>
                                            @else
                                                {{-- Hijau: Aman --}}
                                                <span class="relative flex h-2 w-2">
                                                    <span
                                                        class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                <span
                                                    class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">Aman</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                @if (!$loop->last)
                                    <hr class="border-gray-50">
                                @endif
                            @endforeach
                        </div>

                    </div>
                @empty
                    <div
                        class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="bg-gray-50 p-4 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada inventory</h3>
                        <p class="text-gray-500">Silahkan klik tombol "Barang Masuk" untuk menambah data.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $inventory->links() }}
            </div>
        </div>
    </div>
</x-layout.beranda.app>
