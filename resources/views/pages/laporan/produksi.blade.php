<x-layout.user.app title="Laporan Produksi">
    <div class="space-y-6">
        {{-- HEADER & FILTER --}}
        <div class="relative overflow-hidden bg-white border border-gray-100 rounded-[2rem] p-4 shadow-sm">
            {{-- Decorative Background Ornament --}}
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2 ml-5">
                        <h1 class="text-base md:text-xl font-black text-gray-800 uppercase tracking-tighter">Laporan
                            Produksi
                        </h1>
                    </div>
                    <p class="text-sm text-gray-400 font-medium ml-5">
                        Periode:
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg font-bold">
                            @php
                                $dates = explode(' to ', $dateRange);
                                $start = \Carbon\Carbon::parse($dates[0])->translatedFormat('d M Y');
                                $end = isset($dates[1])
                                    ? \Carbon\Carbon::parse($dates[1])->translatedFormat('d M Y')
                                    : $start;
                            @endphp

                            {{ $start === $end ? $start : $start . ' - ' . $end }}
                        </span>
                    </p>
                </div>

                <form action="{{ route('laporan-produksi') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 bg-gray-50/50 p-3 rounded-[1.5rem] border border-gray-100">
                    @if (auth()->user()->hasRole('Super Admin'))
                        <div class="flex flex-col gap-1">
                            <select name="id_perusahaan"
                                class="w-full rounded-xl border-gray-200 text-[11px] font-black uppercase tracking-wider py-2.5 pl-4 pr-10 outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <option value="">Semua Perusahaan</option>
                                @foreach ($perusahaan as $p)
                                    <option value="{{ $p->id }}"
                                        {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_perusahaan }} ({{ $p->kota }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="text" name="date_range" id="date_range" value="{{ $dateRange }}"
                            placeholder="Pilih Rentang Waktu"
                            class="rounded-xl border-gray-200 text-[11px] font-black py-2.5 pl-10 w-56 outline-none focus:ring-2 focus:ring-blue-500 shadow-sm uppercase tracking-wider">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-gray-800 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 transition-all">
                            Terapkan
                        </button>
                        <a href="{{ route('laporan-produksi') }}"
                            class="p-2.5 bg-white text-gray-400 rounded-xl border border-gray-200 hover:text-red-500 hover:border-red-100 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd"
                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Biaya Bahan Baku
                </p>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">
                    Rp {{ number_format($totalBiayaBB, 0, ',', '.') }}
                </h3>
                <p class="text-[9px] font-bold text-blue-500 mt-2 uppercase tracking-tighter italic">*Berdasarkan
                    Tanggal Masuk</p>
            </div>

            <div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-12 h-12 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pemakaian Bahan Penolong
                </p>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">
                    Rp {{ number_format($totalBiayaBP, 0, ',', '.') }}
                </h3>
                <p class="text-[9px] font-bold text-orange-500 mt-2 uppercase tracking-tighter italic">*Jenis Keluar:
                    PRODUKSI</p>
            </div>

            <div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                    <svg class="w-12 h-12 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                        </path>
                    </svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Produk Jadi (FG/WIP/EC)
                </p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-gray-800 tracking-tight">
                        {{ $hasilProduksi->count() }}
                    </h3>
                    <span class="text-xs font-bold text-gray-400 uppercase">Jenis Barang</span>
                </div>
                <p class="text-[9px] font-bold text-emerald-500 mt-2 uppercase tracking-tighter italic">Nilai: Rp
                    {{ number_format($hasilProduksi->sum('total_nilai'), 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8">

            {{-- RINCIAN HASIL PRODUKSI (FG, WIP, EC) --}}
            <div
                class="bg-white border border-gray-100 rounded-[1.5rem] md:rounded-[2rem] shadow-sm overflow-hidden flex flex-col">
                <div
                    class="px-5 py-4 md:px-8 md:py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-[10px] md:text-[11px] font-black text-gray-800 uppercase tracking-widest">Output:
                        Produk Dihasilkan</h3>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-72 md:max-h-96 scrollbar-hide">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 z-10">
                            <tr
                                class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase tracking-tighter bg-white shadow-sm">
                                <th class="px-5 py-3 md:px-8 md:py-4">Nama Barang</th>
                                <th class="px-3 py-3 md:px-4 md:py-4 text-center">Qty Total</th>
                                <th class="px-5 py-3 md:px-8 md:py-4 text-right">Nilai Asset</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($hasilProduksi as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-5 py-3 md:px-8 md:py-4">
                                        <p
                                            class="text-xs md:text-sm font-bold text-gray-700 group-hover:text-emerald-600 transition-colors truncate max-w-[120px] md:max-w-none">
                                            {{ $item['nama_barang'] }}
                                        </p>
                                        <p class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase">
                                            {{ $item['satuan'] }}</p>
                                    </td>
                                    <td
                                        class="px-3 py-3 md:px-4 md:py-4 text-center text-xs md:text-sm font-black text-gray-800">
                                        {{ number_format($item['total_qty'], 2) }}
                                    </td>
                                    <td
                                        class="px-5 py-3 md:px-8 md:py-4 text-right text-xs md:text-sm font-bold text-gray-600 font-mono italic">
                                        <span class="hidden sm:inline text-[10px]">Rp</span>
                                        {{ number_format($item['total_nilai'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- RINCIAN BARANG DIKELUARKAN (BB & BP) --}}
            <div
                class="bg-white border border-gray-100 rounded-[1.5rem] md:rounded-[2rem] shadow-sm overflow-hidden flex flex-col">
                <div
                    class="px-5 py-4 md:px-8 md:py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-[10px] md:text-[11px] font-black text-gray-800 uppercase tracking-widest">Input:
                        Bahan Keluar</h3>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-72 md:max-h-96 scrollbar-hide">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 z-10">
                            <tr
                                class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase tracking-tighter bg-white shadow-sm">
                                <th class="px-5 py-3 md:px-8 md:py-4">Nama Bahan</th>
                                <th class="px-3 py-3 md:px-4 md:py-4 text-center">Qty Pakai</th>
                                <th class="px-5 py-3 md:px-8 md:py-4 text-right">Total Biaya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($barangKeluar as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-5 py-3 md:px-8 md:py-4">
                                        <p
                                            class="text-xs md:text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors truncate max-w-[120px] md:max-w-none">
                                            {{ $item['nama_barang'] }}
                                        </p>
                                        <p class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase">
                                            {{ $item['satuan'] }}</p>
                                    </td>
                                    <td
                                        class="px-3 py-3 md:px-4 md:py-4 text-center text-xs md:text-sm font-black text-gray-800">
                                        {{ number_format($item['total_qty'], 2) }}
                                    </td>
                                    <td
                                        class="px-5 py-3 md:px-8 md:py-4 text-right text-xs md:text-sm font-bold text-blue-600 font-mono italic">
                                        <span class="hidden sm:inline text-[10px]">Rp</span>
                                        {{ number_format($item['total_nilai'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

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

</x-layout.user.app>
