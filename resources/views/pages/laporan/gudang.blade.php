<x-layout.user.app title="Laporan Gudang">
    <div class="space-y-6" x-data="{ tab: 'PRODUKSI' }">

        {{-- HEADER & FILTER --}}
        <div class="relative overflow-hidden bg-white border border-gray-100 rounded-[2rem] p-4 shadow-sm">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50">
            </div>
            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2 ml-5">
                        <h1 class="text-base md:text-xl font-black text-gray-800 uppercase tracking-tighter">Laporan
                            Gudang</h1>
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

                <form action="{{ route('laporan-gudang') }}" method="GET"
                    class="flex flex-wrap items-center gap-3 bg-gray-50/50 p-3 rounded-[1.5rem] border border-gray-100">
                    @if (auth()->user()->hasRole('Super Admin'))
                        <select name="id_perusahaan"
                            class="w-full md:w-auto rounded-xl border-gray-200 text-[11px] font-black uppercase tracking-wider py-2.5 pl-4 pr-10 outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            <option value="">Semua Perusahaan</option>
                            @foreach ($perusahaan as $p)
                                <option value="{{ $p->id }}"
                                    {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>{{ $p->nama_perusahaan }} ({{ $p->kota }})
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <input type="text" name="date_range" id="date_range" value="{{ $dateRange }}"
                            class="rounded-xl border-gray-200 text-[11px] font-black py-2.5 pl-10 w-56 outline-none shadow-sm uppercase">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-gray-800 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-blue-600 transition-all">Terapkan</button>
                        <a href="{{ route('laporan-gudang') }}"
                            class="p-2.5 bg-white text-gray-400 rounded-xl border border-gray-200 hover:text-red-500 transition-all shadow-sm">
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

        {{-- RINGKASAN ASSET & ITEM --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Total Asset --}}
            <div
                class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div
                    class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total
                        Asset</p>
                    <h3 class="text-base md:text-xl font-black text-emerald-600 truncate">Rp
                        {{ number_format($summary['total_asset'], 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- Item Produksi --}}
            <div
                class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div
                    class="absolute -right-4 -top-4 w-16 h-16 bg-gray-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                        Produksi</p>
                    <h3 class="text-base md:text-xl font-black text-gray-800">{{ $summary['count_produksi'] }} <span
                            class="text-[10px] text-gray-400">Jenis</span></h3>
                </div>
            </div>

            {{-- Bahan Baku --}}
            <div
                class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div
                    class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Bahan
                        Baku</p>
                    <h3 class="text-base md:text-xl font-black text-gray-800">{{ $summary['count_bb'] }} <span
                            class="text-[10px] text-gray-400">Jenis</span></h3>
                </div>
            </div>

            {{-- Bahan Penolong --}}
            <div
                class="bg-white p-4 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div
                    class="absolute -right-4 -top-4 w-16 h-16 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
                <div class="relative z-10">
                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">B.
                        Penolong</p>
                    <h3 class="text-base md:text-xl font-black text-gray-800">{{ $summary['count_bp'] }} <span
                            class="text-[10px] text-gray-400">Jenis</span></h3>
                </div>
            </div>
        </div>

        {{-- STOK GLOBAL DENGAN SISTEM TAB --}}
        <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-sm overflow-hidden flex flex-col">
            <div class="flex border-b border-gray-50 bg-gray-50/30 p-2 gap-2">
                @foreach (['PRODUKSI', 'BAHAN BAKU', 'BAHAN PENOLONG'] as $t)
                    <button @click="tab = '{{ $t }}'"
                        :class="tab === '{{ $t }}' ? 'bg-white shadow-sm text-gray-800' :
                            'text-gray-400 hover:text-gray-600'"
                        class="flex-1 py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all text-center">
                        {{ $t }}
                    </button>
                @endforeach
            </div>

            <div class="overflow-x-auto overflow-y-auto max-h-80 scrollbar-hide">
                @foreach (['PRODUKSI', 'BAHAN BAKU', 'BAHAN PENOLONG'] as $jenis)
                    <div x-show="tab === '{{ $jenis }}'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95">

                        @if (isset($stokGlobalGrouped[$jenis]) && $stokGlobalGrouped[$jenis]->count() > 0)
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 z-20 bg-white/95 backdrop-blur-md shadow-sm">
                                    <tr
                                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                        <th class="px-8 py-4">Nama Barang</th>
                                        <th class="px-4 py-4 text-center">Stok Minimal</th> {{-- Kolom Baru --}}
                                        <th class="px-4 py-4 text-center">Stok Saat Ini</th>
                                        <th class="px-8 py-4 text-right">Status Gudang</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($stokGlobalGrouped[$jenis] as $item)
                                        <tr class="hover:bg-gray-50/50 transition-all group">
                                            <td class="px-8 py-4">
                                                <span
                                                    class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                                                    {{ optional($item->Barang)->nama_barang }}
                                                </span>
                                                {{-- Menampilkan Satuan di bawah Nama Barang agar layout tetap bersih --}}
                                                <p
                                                    class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">
                                                    Satuan: {{ optional($item->Barang)->satuan ?? '-' }}
                                                </p>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="text-xs font-bold text-gray-400">
                                                    {{ number_format($item->minimum_stok, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="flex flex-col items-center">
                                                    <span
                                                        class="text-sm font-black {{ $item->stok <= $item->minimum_stok ? 'text-red-600' : 'text-gray-900' }}">
                                                        {{ number_format($item->stok, 0, ',', '.') }}
                                                    </span>
                                                    {{-- Badge satuan kecil di bawah angka stok --}}
                                                    <span
                                                        class="text-[8px] font-bold text-gray-400 uppercase">{{ optional($item->Barang)->satuan }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                @if ($item->stok == 0)
                                                    <span
                                                        class="px-4 py-1.5 bg-red-50 text-red-600 rounded-full text-[9px] font-black uppercase shadow-sm shadow-red-100">
                                                        Habis
                                                    </span>
                                                @elseif ($item->stok <= $item->minimum_stok)
                                                    <span
                                                        class="px-4 py-1.5 bg-yellow-50 text-yellow-600 rounded-full text-[9px] font-black uppercase shadow-sm shadow-yellow-100">
                                                        Limit
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase shadow-sm shadow-emerald-100">
                                                        Aman
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-20 text-center">
                                <div class="inline-flex p-4 rounded-full bg-gray-50 mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400 italic font-medium">Tidak ada data stok
                                    {{ strtolower($jenis) }} untuk kategori ini.</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- LOG MASUK & KELUAR --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-sm overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-blue-50/20">
                    <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest italic">Log Barang Masuk
                        (Batch)</h3>
                </div>
                <div class="overflow-y-auto max-h-80 scrollbar-hide">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($stokDetail as $sd)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-800">
                                            {{ $sd->Inventory->Barang->nama_barang }}</p>
                                        <p
                                            class="text-[9px] font-black text-gray-400 mt-0.5 font-mono uppercase tracking-tighter">
                                            Batch: {{ $sd->nomor_batch ?? 'SISTEM' }}</p>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span
                                            class="text-xs font-black text-blue-600">+{{ number_format($sd->stok, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-right text-[10px] text-gray-400 font-bold italic">
                                        {{ \Carbon\Carbon::parse($sd->tanggal_masuk)->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-sm overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-yellow-50/20">
                    <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest italic">Log Mutasi Keluar
                    </h3>
                </div>
                <div class="overflow-y-auto max-h-80 scrollbar-hide">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($barangKeluar as $bk)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-800">
                                            {{ $bk->DetailInventory->Inventory->Barang->nama_barang }}</p>
                                        <p class="text-[9px] font-black text-red-400 mt-0.5 uppercase italic italic">
                                            Proses Pengeluaran</p>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span
                                            class="text-xs font-black text-red-600">-{{ number_format($bk->jumlah_keluar, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-right text-[10px] text-gray-400 font-bold italic">
                                        {{ \Carbon\Carbon::parse($bk->tanggal_keluar)->translatedFormat('d M Y') }}
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
