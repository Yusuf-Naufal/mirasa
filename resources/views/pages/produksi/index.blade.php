<x-layout.beranda.app>
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

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
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-2">Daftar Produksi Harian</h1>

                <p class="text-sm text-gray-500 font-medium lowercase italic tracking-tight mt-1">
                    Monitoring aktivitas produksi
                    <span class="font-bold text-gray-700 uppercase not-italic">
                        @if (auth()->user()->hasRole('Super Admin'))
                            @if (request('id_perusahaan'))
                                {{ $perusahaan->firstWhere('id', request('id_perusahaan'))->nama_perusahaan ?? 'Sistem' }}
                            @else
                                SEMUA PERUSAHAAN
                            @endif
                        @else
                            {{ auth()->user()->perusahaan?->nama_perusahaan ?? 'Tanpa Perusahaan' }}
                        @endif
                    </span>
                </p>
            </div>

            {{-- 2. SEARCH & BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <form action="{{ route('produksi.index') }}" method="GET" class="relative w-full md:max-w-md">
                    {{-- Tetap simpan filter lain saat cari teks --}}
                    @if(request('id_perusahaan')) <input type="hidden" name="id_perusahaan" value="{{ request('id_perusahaan') }}"> @endif
                    @if(request('date_range')) <input type="hidden" name="date_range" value="{{ request('date_range') }}"> @endif
                    
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
                    <span class="ml-2">Filter Lanjutan</span>
                </button>
            </div>

            {{-- 3. LIST CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($produksis as $item)
                    @php
                        // Logika Batch Number
                        $batchNomor = $produksis
                            ->where('tanggal_produksi', $item->tanggal_produksi)
                            ->reverse()
                            ->values()
                            ->search(fn($val) => $val->id === $item->id) + 1;
                    @endphp
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group">
                        <div class="p-6 flex-1">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2.5 py-1 rounded-lg uppercase">
                                    Batch #{{ $batchNomor }}
                                </span>
                                <span class="text-[10px] font-medium text-gray-400 italic">
                                    {{ $item->updated_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="mb-5">
                                <h3 class="text-xl font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ \Carbon\Carbon::parse($item->tanggal_produksi)->translatedFormat('d F Y') }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_produksi)->translatedFormat('l') }}
                                </p>
                            </div>

                            <div class="space-y-3 border-t border-gray-50 pt-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                        <span class="text-[10px] font-bold text-gray-600">
                                            {{ $item->BahanBakuMasuk }} Bahan Baku Masuk
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                        <span class="text-[10px] font-bold text-gray-600">
                                            {{ $item->barangKeluar->count() }} Item Keluar
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50/50 border-t border-gray-100 grid grid-cols-2 gap-3">
                            <a href="{{ route('produksi.show', $item->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('produksi.edit', $item->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 bg-yellow-600 rounded-xl text-xs font-bold text-white hover:bg-yellow-700 shadow-sm transition-all shadow-yellow-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Kelola
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-gray-200 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold">Belum ada data produksi</p>
                        <p class="text-xs text-gray-400">Data produksi harian Anda akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- 4. PAGINATION --}}
            <div class="mt-8">
                {{ $produksis->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>

    {{-- Filter Modal (Diletakkan di dalam layout agar styling tetap konsisten) --}}
    <div id="filterModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60" onclick="closeModal('filterModal')"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl" id="modalContent">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-bold text-gray-800">Filter Lanjutan</h2>
                <button onclick="closeModal('filterModal')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 15 15">
                        <path fill="currentColor" d="M10.969 3.219a.574.574 0 1 1 .812.812L8.313 7.5l3.468 3.469l.074.09a.575.575 0 0 1-.796.796l-.09-.074L7.5 8.312l-3.469 3.47a.574.574 0 1 1-.812-.813L6.688 7.5l-3.47-3.469l-.073-.09a.575.575 0 0 1 .796-.797l.09.075L7.5 6.687z" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('produksi.index') }}" method="GET" class="p-6">
                {{-- Tetap bawa nilai pencarian saat filter diterapkan --}}
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                <div class="space-y-5">
                    @if (auth()->user()->hasRole('Super Admin'))
                        <div>
                            <label for="id_perusahaan" class="block text-sm font-semibold text-gray-700 mb-1">Perusahaan</label>
                            <select name="id_perusahaan" id="id_perusahaan"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 outline-none">
                                <option value="">Semua Perusahaan</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}" {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label for="date_range" class="block text-sm font-semibold text-gray-700 mb-1">Rentang Tanggal Produksi</label>
                        <div class="relative">
                            <input type="text" name="date_range" id="date_range"
                                value="{{ request('date_range') }}" placeholder="Pilih rentang tanggal.."
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-blue-500 outline-none">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('produksi.index') }}"
                        class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-bold text-gray-600 hover:bg-gray-50">Reset</a>
                    <button type="submit"
                        class="flex-1 rounded-xl bg-blue-600 py-3 text-sm font-bold text-white hover:bg-blue-700 transition-colors shadow-sm">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Fungsi Modal
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        // Inisialisasi Flatpickr
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
    @endpush
</x-layout.beranda.app>