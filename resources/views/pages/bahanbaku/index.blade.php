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
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight mt-2">Catatan Bahan Baku</h1>
                <p class="text-sm text-gray-500 font-medium">Gudang:
                    {{ auth()->user()->perusahaan->nama_perusahaan ?? 'Nama Perusahaan' }}</p>
            </div>

            {{-- 2. SEARCH & BUTTON --}}
            <div class="mb-8 flex flex-col md:flex-row gap-4 justify-between items-center">
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

                <a href="{{ route('bahan-baku.create') }}"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Bahan Baku
                </a>
            </div>

            {{-- 3. DATA BAHAN BAKU GROUPED BY DATE (SLIM VERSION) --}}
            <div class="space-y-8">
                @forelse ($listBahanBaku as $tanggal => $items)
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
                                            {{ $bahan->Supplier->nama_supplier }}
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
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('bahan-baku.edit', $bahan->id) }}"
                                                class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('bahan-baku.destroy', $bahan->id) }}" method="POST"
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

        </div>
    </div>
</x-layout.beranda.app>
