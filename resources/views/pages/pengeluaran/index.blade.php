<x-layout.beranda.app title="Riwayat Pengeluaran">
    <div class="min-h-screen md:px-10 py-6 flex flex-col" x-data="{ menuOpen: false, activeTab: '{{ $activeTab }}' }">
        <div class="flex-1 pt-12">

            {{-- 1. HEADER --}}
            <div class="mb-4">
                <a href="{{ route('beranda') }}"
                    class="group text-blue-600 hover:text-blue-700 text-sm font-semibold inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-2">Catatan Pengeluaran Perusahaan
                </h1>
                <p class="text-sm text-gray-500 font-medium">Monitoring pengeluaran perusahaan : <span
                        class="font-bold text-gray-700 uppercase not-italic">
                        @if (request('id_perusahaan'))
                            {{-- Jika ada filter perusahaan, cari namanya dari koleksi $perusahaan --}}
                            {{ $perusahaan->firstWhere('id', request('id_perusahaan'))->nama_perusahaan ?? 'Sistem' }}
                        @else
                            {{-- Jika tidak ada filter, tampilkan nama perusahaan user atau 'SEMUA PERUSAHAAN' untuk Super Admin --}}
                            {{ auth()->user()->perusahaan->nama_perusahaan ?? 'SEMUA PERUSAHAAN' }}
                        @endif
                    </span></p>
            </div>

            {{-- 2. SEARCH & DROPDOWN BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="w-full flex gap-2 items-center">
                    <form action="{{ route('pengeluaran.index') }}" method="GET" class="relative w-full md:max-w-md">

                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        @if (request('id_perusahaan'))
                            <input type="hidden" name="id_perusahaan" value="{{ request('id_perusahaan') }}">
                        @endif
                        @if (request('date_range'))
                            <input type="hidden" name="date_range" value="{{ request('date_range') }}">
                        @endif
                        @if (request('is_hpp'))
                            <input type="hidden" name="is_hpp" value="{{ request('is_hpp') }}">
                        @endif

                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama pengeluaran..."
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

                {{-- Dropdown Button Section --}}
                @canany(['pengeluaran.create-operasional', 'pengeluaran.create-office', 'pengeluaran.create-limbah',
                    'pengeluaran.create-kesejahtraan', 'pengeluaran.create-maintenance', 'pengeluaran.create-administrasi'])
                    <div class="relative w-full md:w-64" x-data="{ menuOpen: false }">
                        <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false"
                            class="w-full md:w-64 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Pengeluaran
                            <svg class="w-4 h-4 transition-transform" :class="menuOpen ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="menuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 z-[70] overflow-hidden"
                            x-cloak>

                            {{-- Kategori: Operasional --}}
                            @can('pengeluaran.create-operasional')
                                <a href="{{ route('pengeluaran.create-operasional') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-blue-50 text-gray-700 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">Operasional</span>
                                        <span class="text-[10px] text-gray-400 leading-tight tracking-tight">Biaya energi
                                            (listrik/air)
                                            , BBM gudang, & aktivitas harian pabrik.</span>
                                    </div>
                                </a>
                            @endcan

                            {{-- Kategori: Office --}}
                            @can('pengeluaran.create-office')
                                <a href="{{ route('pengeluaran.create-office') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-gray-700 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-slate-100 text-slate-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">Keperluan Kantor</span>
                                        <span class="text-[10px] text-gray-400 leading-tight tracking-tight">Pengadaan ATK,
                                            cetak form administrasi, & perlengkapan kerja.</span>
                                    </div>
                                </a>
                            @endcan

                            {{-- Kategori: Limbah --}}
                            @can('pengeluaran.create-limbah')
                                <a href="{{ route('pengeluaran.create-limbah') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-50 text-gray-700 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">Pengolahan Limbah</span>
                                        <span class="text-[10px] text-gray-400 leading-tight tracking-tight">Retribusi
                                            kebersihan, pengangkutan limbah, & biaya sanitasi.</span>
                                    </div>
                                </a>
                            @endcan

                            {{-- Kategori: Gaji --}}
                            @can('pengeluaran.create-kesejahtraan')
                                <a href="{{ route('pengeluaran.create-kesejahteraan') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 text-gray-700 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-purple-100 text-purple-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">Gaji & Kesejahteraan</span>
                                        <span class="text-[10px] text-gray-400 leading-tight tracking-tight">Pembayaran gaji
                                            staf, uang lembur, & tunjangan karyawan.</span>
                                    </div>
                                </a>
                            @endcan

                            {{-- Kategori: Maintenance --}}
                            @can('pengeluaran.create-maintenance')
                                <a href="{{ route('pengeluaran.create-maintenance') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-amber-50 text-gray-700 transition-colors border-b border-gray-50">
                                    <div class="p-2 bg-amber-100 text-amber-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">Maintenance / Perbaikan</span>
                                        <span class="text-[10px] text-gray-400 leading-tight tracking-tight">Pemeliharaan rutin
                                            mesin produksi, AC, & aset fisik perusahaan.</span>
                                    </div>
                                </a>
                            @endcan

                            {{-- Kategori: Administrasi Umum --}}
                            @can('pengeluaran.create-administrasi')
                                <a href="{{ route('pengeluaran.create-administrasi') }}"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 text-gray-700 transition-colors">
                                    <div class="p-2 bg-orange-100 text-orange-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold">Administrasi / Umum</span>
                                        <span class="text-[10px] text-gray-400 leading-tight tracking-tight">Biaya perizinan,
                                            legalitas dokumen, & pengeluaran tak terduga.</span>
                                    </div>
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcanany
            </div>

            {{-- 3. TABS --}}
            <div class="flex overflow-x-auto no-scrollbar gap-2 mb-10 pb-2">
                @foreach ($perKategori as $kat => $data)
                    @php
                        // Mempertahankan semua query string (month, year) dan hanya mengubah tab
                        $url = request()->fullUrlWithQuery(['tab' => $kat]);
                        $isActive = $activeTab === $kat;
                    @endphp

                    <a href="{{ $url }}"
                        class="whitespace-nowrap px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border-2 inline-block {{ $isActive ? 'bg-gray-900 border-gray-900 text-white shadow-xl shadow-gray-200' : 'bg-white border-gray-100 text-gray-400 hover:border-gray-300' }}">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>

            {{-- 4. TABLE --}}
            @php
                // Definisikan data aktif berdasarkan tab yang dipilih agar pagination di bawah tidak error
                $dataAktif = $perKategori[$activeTab];
            @endphp

            <div class="w-full bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Gunakan Switch Case untuk menampilkan tabel yang sesuai dengan tab aktif --}}
                @switch($activeTab)
                    @case('OPERASIONAL')
                        <x-pengeluaran.table-operasional :items="$dataAktif['items']" />
                    @break

                    @case('GAJI KARYAWAN')
                        <x-pengeluaran.table-gaji :items="$dataAktif['items']" />
                    @break

                    @case('PENGOLAHAN LIMBAH')
                        <x-pengeluaran.table-limbah :items="$dataAktif['items']" />
                    @break

                    @case('MAINTENANCE')
                        <x-pengeluaran.table-maintenance :items="$dataAktif['items']" />
                    @break

                    @case('OFFICE')
                        <x-pengeluaran.table-office :items="$dataAktif['items']" />
                    @break

                    @case('ADMINISTRASI')
                        <x-pengeluaran.table-administrasi :items="$dataAktif['items']" />
                    @break

                    @default
                        {{-- Default --}}
                        <x-pengeluaran.table-operasional :items="$dataAktif['items']" />
                @endswitch
            </div>

            {{-- 5. PAGINATION --}}
            <div class="px-6 py-6 border-t border-gray-50 bg-gray-50/30">
                {{-- Links pagination dengan mempertahankan parameter tab, month, dan year --}}
                {{ $dataAktif['items']->appends(request()->query())->links() }}
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

            <form action="{{ route('pengeluaran.index') }}" method="GET" class="p-6">

                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div class="space-y-5">
                    {{-- Filter Berdasarkan Perusahaan --}}
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

                    <div>
                        <label for="filter_is_hpp" class="block text-sm font-semibold text-gray-700 mb-1">Dampak
                            HPP</label>
                        <select name="is_hpp" id="filter_is_hpp"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FFC829] focus:outline-none focus:ring-2 focus:ring-[#FFC829]/20 outline-none">
                            <option value="">Tampil semua</option>
                            <option value="1" {{ request('is_hpp') == '1' ? 'selected' : '' }}>HPP
                            </option>
                            <option value="0" {{ request('is_hpp') == '0' ? 'selected' : '' }}>
                                Non HPP</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('pengeluaran.index', ['tab' => $activeTab]) }}"
                        class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">Reset</a>
                    <button type="submit"
                        class="flex-1 rounded-xl bg-gray-600 py-3 text-sm font-bold text-white hover:bg-gray-800 transition-colors shadow-sm">Terapkan
                        Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- HANDLE RENTANG TANGGAL --}}
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
