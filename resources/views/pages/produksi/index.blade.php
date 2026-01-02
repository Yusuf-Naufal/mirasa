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
                <p class="text-sm text-gray-500 font-medium">Monitoring aktivitas produksi
                    {{-- PERBAIKAN: Menggunakan null safe operator (?->) dan fallback string --}}
                    {{ auth()->user()->perusahaan?->nama_perusahaan ?? 'Tanpa Perusahaan' }}
                </p>
            </div>

            {{-- 2. SEARCH & BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                <form action="{{ route('produksi.index') }}" method="GET" class="relative w-full md:max-w-md">
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

                <a href="{{ route('produksi.create') }}"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Mulai Produksi Baru
                </a>
            </div>

            {{-- 3. LIST CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($produksis as $item)
                    @php
                        $batchNomor =
                            $produksis
                                ->where('tanggal_produksi', $item->tanggal_produksi)
                                ->reverse()
                                ->values()
                                ->search(fn($val) => $val->id === $item->id) + 1;
                    @endphp
                    <div
                        class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group">
                        <div class="p-6 flex-1">
                            {{-- Badge ID & Status --}}
                            <div class="flex justify-between items-center mb-4">
                                <span
                                    class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2.5 py-1 rounded-lg uppercase">
                                    Batch #{{ $batchNomor }}
                                </span>
                                <span class="text-[10px] font-medium text-gray-400 italic">
                                    {{ $item->updated_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Info Utama --}}
                            <div class="mb-5">
                                <h3
                                    class="text-xl font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ \Carbon\Carbon::parse($item->tanggal_produksi)->translatedFormat('d F Y') }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_produksi)->translatedFormat('l') }}</p>
                            </div>

                            {{-- Ringkasan Bahan --}}
                            <div class="space-y-3 border-t border-gray-50 pt-4">
                                <div class="flex flex-col gap-2">
                                    {{-- Indikator Bahan Masuk --}}
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                        <span
                                            class="text-[10px] font-bold text-gray-600">{{ $item->bahanBaku->count() }}
                                            Bahan Baku Masuk</span>
                                    </div>
                                    {{-- Indikator Barang Keluar --}}
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                        <span
                                            class="text-[10px] font-bold text-gray-600">{{ $item->barangKeluar->count() }}
                                            Item Keluar</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="p-4 bg-gray-50/50 border-t border-gray-100 grid grid-cols-2 gap-3">
                            <a href="{{ route('produksi.show', $item->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('produksi.edit', $item->id) }}"
                                class="flex items-center justify-center gap-2 py-2.5 bg-yellow-600 rounded-xl text-xs font-bold text-white hover:bg-yellow-700 shadow-sm transition-all shadow-yellow-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Kelola
                            </a>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-gray-200 flex flex-col items-center justify-center">
                        <div
                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold">Belum ada data produksi</p>
                        <p class="text-xs text-gray-400">Data produksi harian Anda akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- 4. PAGINATION --}}
            <div class="mt-8">
                {{ $produksis->links() }}
            </div>
        </div>
    </div>
</x-layout.beranda.app>