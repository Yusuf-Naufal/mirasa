<x-layout.beranda.app title="Detail Inventory">
    <div class="min-h-screen bg-slate-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12 gap-6">

            {{-- Breadcrumb & Header --}}
            <div class="mb-2 px-4 md:px-0">
                <a href="{{ route('inventory.index') }}"
                    class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Gudang
                </a>
                <div class="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Informasi Produk</h1>
                        <p class="text-slate-500 text-sm mt-1">Pantau riwayat stok dan detail logistik barang secara
                            real-time.</p>
                    </div>

                    {{-- Tombol Edit --}}
                    @can('inventory.riwayat')
                        <a type="button" href="{{ route('inventory.riwayat', $inventory->id) }}"
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:border-blue-300 hover:text-blue-600 transition-all shadow-sm group">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 group-hover:rotate-12 transition-transform" viewBox="0 0 24 24">
                                <g fill="none">
                                    <path
                                        d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                    <path fill="currentColor"
                                        d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m0 2a8 8 0 1 0 0 16a8 8 0 0 0 0-16m0 2a1 1 0 0 1 .993.883L13 7v4.586l2.707 2.707a1 1 0 0 1-1.32 1.497l-.094-.083l-3-3a1 1 0 0 1-.284-.576L11 12V7a1 1 0 0 1 1-1" />
                                </g>
                            </svg>
                            <span class="text-sm font-bold">Semua Riwayat Masuk</span>
                        </a>
                    @endcan
                    {{-- Action Button (Opsional) --}}
                    {{-- <button
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl shadow-sm hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Laporan
                    </button> --}}
                </div>
            </div>

            {{-- Card Detail Barang --}}
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden mx-4 md:mx-0">
                <div class="p-6 md:p-10 flex flex-col md:flex-row gap-10 items-center md:items-start">
                    {{-- Image Container --}}
                    <div
                        class="relative group w-full md:w-64 h-64 rounded-2xl bg-slate-100 flex-shrink-0 overflow-hidden ring-4 ring-slate-50">
                        @if ($inventory->barang->foto)
                            <img src="{{ asset('storage/' . $inventory->barang->foto) }}"
                                alt="{{ $inventory->barang->nama_barang }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span class="text-xs font-medium mt-2 uppercase tracking-widest text-slate-400">No
                                    Image</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info Detail --}}
                    <div class="flex-1 w-full flex flex-col justify-center" x-data="{ openModal: false }">
                        <div class="w-full flex items-start justify-between">
                            <div class="mb-6">
                                <h2 class="text-3xl font-bold text-slate-900 leading-tight">
                                    {{ $inventory->barang->nama_barang }}
                                </h2>
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 mb-3 inline-block">
                                    KODE: {{ $inventory->barang->kode }}
                                </span>
                            </div>

                            {{-- Tombol Edit --}}
                            @can('inventory.minimum-edit')
                                <button @click="openModal = true"
                                    class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:border-blue-300 hover:text-blue-600 transition-all shadow-sm group">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 group-hover:rotate-12 transition-transform" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="text-sm hidden md:block font-bold">Atur Limit</span>
                                </button>
                            @endcan
                        </div>

                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-6 rounded-2xl bg-slate-50/80">
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total
                                    Stok Saat Ini</p>
                                <div class="flex items-baseline gap-2">
                                    <p class="text-3xl font-black text-blue-600">{{ $inventory->stok }}</p>
                                    <p class="text-sm font-bold text-slate-500 uppercase">
                                        {{ $inventory->barang->satuan }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stok
                                    Minimum</p>
                                <p class="text-3xl font-black text-slate-700">{{ $inventory->minimum_stok ?? '0' }}</p>
                            </div>
                            @php
                                $stokAktual = $inventory->stok;
                                $stokMin = $inventory->minimum_stok ?? 0;
                                $ambangPeringatan = $stokMin * 1.2;
                            @endphp

                            <div>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status
                                </p>

                                @if ($stokAktual <= $stokMin)
                                    {{-- Kondisi 1: Stok di bawah atau sama dengan minimum --}}
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-600 ring-1 ring-red-200">
                                        <span class="w-2 h-2 rounded-full bg-red-600 mr-2 animate-pulse"></span> Stok
                                        Rendah
                                    </span>
                                @elseif ($stokAktual <= $ambangPeringatan)
                                    {{-- Kondisi 2: Stok berada di antara Stok Min sampai Stok Min + 5 --}}
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-600 ring-1 ring-orange-200">
                                        <span class="w-2 h-2 rounded-full bg-orange-600 mr-2"></span> Mendekati Limit
                                    </span>
                                @else
                                    {{-- Kondisi 3: Stok aman --}}
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-600 ring-1 ring-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-600 mr-2"></span> Aman
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Modal Edit Stok Minimum --}}
                        <div x-show="openModal"
                            class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

                            <div @click.away="openModal = false"
                                class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100">

                                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                                    <h3 class="text-xl font-bold text-slate-800">Atur Stok Minimum</h3>
                                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ route('inventory.update-minimum', $inventory->id) }}" method="POST"
                                    class="p-8">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-6">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Ambang Batas
                                            Minimum</label>
                                        <div class="relative">
                                            <input type="number" step="any" name="minimum_stok"
                                                value="{{ $inventory->minimum_stok }}"
                                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none text-lg font-semibold"
                                                placeholder="Contoh: 10" required>
                                            <span
                                                class="absolute right-4 top-3.5 text-slate-400 text-sm font-medium">{{ $inventory->barang->satuan }}</span>
                                        </div>
                                        <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                                            Notifikasi peringatan akan muncul jika stok tersisa 30
                                            {{ $inventory->barang->satuan }} di atas
                                            ambang batas yang Anda tentukan.
                                        </p>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" @click="openModal = false"
                                            class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-3 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg shadow-green-200 transition-all">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @php
                $kodeJenis = $inventory->barang->JenisBarang->kode;
            @endphp

            @if (in_array($kodeJenis, ['FG', 'WIP', 'EC']))
                {{-- Produksi: Finished Goods, Mentah In Transfer, atau Empty Component --}}
                <x-gudang.table-produksi :details="$details" />
            @elseif ($kodeJenis === 'BP')
                {{-- Bahan Penolong --}}
                <x-gudang.table-bp :details="$details" />
            @elseif ($kodeJenis === 'BB')
                {{-- Bahan Baku --}}
                <x-gudang.table-bb :details="$details" />
            @else
                {{-- Default jika kode tidak dikenal --}}
                <div
                    class="p-4 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-100 italic text-sm text-center">
                    Tipe barang ({{ $kodeJenis }}) tidak memiliki format tabel riwayat yang spesifik.
                </div>
            @endif

        </div>
    </div>


</x-layout.beranda.app>
