<x-layout.beranda.app>
    <div class="md:px-10 py-6 flex flex-col">
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
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight mt-2">Catatan Barang Masuk</h1>
                <p class="text-sm text-gray-500 font-medium">
                    Gudang:
                    <span class="font-bold text-gray-800">
                        @if (request('id_perusahaan'))
                            {{-- Mengambil nama perusahaan dari koleksi $perusahaan berdasarkan filter --}}
                            {{ $perusahaan->firstWhere('id', request('id_perusahaan'))->nama_perusahaan ?? 'Semua Gudang' }}
                        @else
                            {{-- Default: Nama perusahaan user login --}}
                            {{ auth()->user()->perusahaan->nama_perusahaan ?? 'Semua' }}
                        @endif
                    </span>
                </p>
            </div>

            {{-- 2. SEARCH & BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="flex gap-2 items-center w-full">
                    <form action="{{ route('bahan-baku.index') }}" method="GET" class="relative w-full md:max-w-md">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari tanggal atau nama bahan..."
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-2xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm text-sm font-medium">
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

                {{-- BUTTON TAMBAH BARANG DENGAN DROPDOWN (Pemisahan) --}}
                <div class="relative inline-block text-left w-full md:w-56" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" type="button"
                        class="inline-flex justify-center items-center w-full lg:w-auto gap-x-1.5 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-700 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
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
                            <a href="{{ route('barang-masuk.create-produksi') }}"
                                class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50 flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Produksi
                            </a>
                            <a href="{{ route('barang-masuk.create-bp') }}"
                                class="text-gray-700 block px-4 py-3 text-sm hover:bg-gray-50 border-b border-gray-50 flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span> Bahan Penolong
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. DATA BAHAN BAKU GROUPED BY DATE (SLIM VERSION) --}}
            <div class="space-y-8">
                @forelse ($listBarangMasuk as $tanggal => $items)
                    <div class="space-y-3">
                        {{-- Label Tanggal Minimalis --}}
                        <div class="flex items-center gap-3 py-2">
                            <span
                                class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-lg uppercase tracking-wider">
                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                            </span>
                            <div class="h-px flex-1 bg-gray-100"></div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $items->count() }} Transaksi</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($items as $bahan)
                                <div
                                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-blue-200 transition-all p-3 flex items-center gap-4 group">

                                    {{-- Thumbnail Barang --}}
                                    <div class="relative w-16 h-16 flex-shrink-0 rounded-xl bg-gray-50 overflow-hidden">
                                        @if ($bahan->Inventory->Barang && $bahan->Inventory->Barang->foto)
                                            <img src="{{ asset('storage/' . $bahan->Inventory->Barang->foto) }}"
                                                class="w-full h-full object-cover"
                                                alt="{{ $bahan->Inventory->Barang->nama_barang }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Konten Utama --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-0.5">
                                            <h4 class="text-sm font-bold text-gray-800 truncate">
                                                {{ $bahan->Inventory->Barang->nama_barang }}
                                            </h4>
                                        </div>

                                        <p class="text-[11px] text-gray-500 flex items-center gap-1 mb-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $bahan->Supplier->nama_supplier ?? 'PRODUKSI' }}
                                        </p>

                                        <div class="flex items-center justify-between border-t border-gray-50 pt-2">
                                            <p class="text-xs font-black text-gray-900">
                                                Rp {{ number_format($bahan->total_harga) }}
                                            </p>
                                            <p class="text-[10px] font-medium text-gray-400">
                                                <span
                                                    class="text-blue-600 font-bold">{{ number_format($bahan->jumlah_diterima) }}</span>
                                                Kg
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi Minimalis --}}
                                    <div class="flex flex-col gap-1">
                                        {{-- Tombol Hapus --}}
                                        @if ($bahan->stok == $bahan->jumlah_diterima)
                                            @php
                                                // Ambil kode jenis barang melalui relasi Inventory -> Barang -> jenisBarang
                                                $kodeJenis = $bahan->Inventory->Barang->jenisBarang->kode ?? null;

                                                // Tentukan route berdasarkan kode jenis
                                                $routeEdit =
                                                    $kodeJenis === 'BP'
                                                        ? route('barang-masuk.edit-bp', $bahan->id)
                                                        : route('barang-masuk.edit-produksi', $bahan->id);
                                            @endphp

                                            {{-- Tombol Edit --}}
                                            <a href="{{ $routeEdit }}"
                                                class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('barang-masuk.destroy', $bahan->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus data ini? Rekapitulasi akan disesuaikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <p class="text-gray-400 text-sm">Belum ada catatan bahan baku.</p>
                    </div>
                @endforelse
            </div>

            {{-- 4. PAGINATION --}}
            <div class="mt-8">
                {{ $barangMasukPagination->links('vendor.pagination.custom') }}
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

            <form action="{{ route('bahan-baku.index') }}" method="GET" class="p-6">
                <input type="hidden" name="search" value="{{ request('search') }}">

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
                            <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-1">Bahan
                                Baku</label>
                            <select name="jenis" id="jenis"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FFC829] outline-none">
                                <option value="">Semua Bahan Baku</option>
                                @foreach ($barang as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('jenis') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Filter Rentang Tanggal --}}
                    <div>
                        <label for="date_range" class="block text-sm font-semibold text-gray-700 mb-1">Rentang Tanggal
                            Masuk</label>
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
                    <a href="{{ route('bahan-baku.index') }}"
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
