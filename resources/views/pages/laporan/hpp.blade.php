<x-layout.user.app title="Laporan Keuangan">
    <div class="max-w-7xl mx-auto space-y-8 pb-12" x-data="{ filterType: '{{ $filterType ?? 'month' }}' }">

        {{-- SECTION 1: HEADER & FILTER --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase italic">Laporan HPP</h1>
                <p class="text-sm text-gray-400 font-medium italic">Insight hpp periode ini</p>
            </div>

            <form action="{{ route('laporan-hpp') }}" method="GET" class="flex flex-wrap items-center gap-2">
                @if (auth()->user()->hasRole('Super Admin'))
                    <select name="id_perusahaan"
                        class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        <option value="">Semua Perusahaan</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}"
                                {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>{{ $p->nama_perusahaan }}
                                ({{ $p->kota }})
                            </option>
                        @endforeach
                    </select>
                @endif

                <select name="filter_type" x-model="filterType"
                    class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none bg-blue-50 text-blue-600 shadow-sm">
                    <option value="month">MODE BULANAN</option>
                    <option value="year">MODE TAHUNAN</option>
                </select>

                <div x-show="filterType === 'month'" x-transition>
                    <select name="month"
                        class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}"
                                {{ (int) ($selectedMonth ?? date('m')) === $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <select name="year"
                    class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm">
                    @foreach (range(date('Y') - 2, date('Y') + 1) as $y)
                        <option value="{{ $y }}" {{ ($selectedYear ?? date('Y')) == $y ? 'selected' : '' }}>
                            {{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-gray-900 text-white px-5 py-2 rounded-xl font-bold text-xs uppercase hover:bg-blue-600 transition-all shadow-md">Update</button>
            </form>
        </div>

        {{-- SECTION 2: PRODUK JADI --}}
        <div class="space-y-6">
            {{-- SUMMARY PRODUK --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- BERAPA JENIS YANG DI PRODUKSI --}}
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jenis Produk</p>
                        {{-- Jenis Produk --}}
                        <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $summary['current_count_sku'] }} <span
                                class="text-sm font-medium text-gray-400">SKU</span></h3>
                        <p
                            class="text-[10px] {{ $summary['diff_sku'] >= 0 ? 'text-green-500' : 'text-red-500' }} font-bold mt-2">
                            {{ $summary['diff_sku'] >= 0 ? '+' : '' }}{{ $summary['diff_sku'] }} dari periode lalu
                        </p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>

                {{-- TOTAL KG YANG DI PRODUKSI --}}
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Volume Produksi</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-1">
                            {{ number_format($summary['current_volume'], 0, ',', '.') }} <span
                                class="text-sm font-medium text-gray-400">Kg</span></h3>
                        <p
                            class="text-[10px] {{ $summary['diff_volume_pct'] >= 0 ? 'text-blue-500' : 'text-orange-500' }} font-bold mt-2">
                            {{ number_format($summary['diff_volume_pct'], 1) }}% dibanding periode lalu
                        </p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                </div>

                {{-- BIAYA HASIL PRODUKSI --}}
                <div class="bg-blue-600 p-6 rounded-3xl shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-blue-100 uppercase tracking-wider">Total Biaya Produk</p>
                        <h3 class="text-3xl font-black text-white mt-1">
                            Rp {{ number_format($summary['total_cost'], 0, ',', '.') }}
                        </h3>
                        <p class="text-[10px] text-blue-200 font-bold mt-2 italic">
                            Avg. Rp {{ number_format($summary['avg_cost_per_kg'], 0, ',', '.') }} / Kg
                        </p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-10 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

            </div>

            {{-- RINCIAN TABEL BARANG --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-black text-gray-900 uppercase italic">Rincian Produksi Barang</h2>
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase">FG,
                        WIP, & EC</span>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-[600px] scrollbar-thin scrollbar-thumb-gray-200">
                    <table class="w-full text-left border-collapse sticky-header">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase">Nama Produk</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase text-center">Tipe
                                </th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase text-right">Total
                                    Diterima</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase text-right">Volume
                                    (Kg)</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase text-right">Total
                                    Biaya</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($rincianProduksi as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-800">{{ $item['nama_barang'] }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium tracking-tight">
                                            {{ $item['kode'] }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $color = match ($item['tipe']) {
                                                'FG' => 'bg-purple-50 text-purple-600',
                                                'WIP' => 'bg-orange-50 text-orange-600',
                                                'EC' => 'bg-blue-50 text-blue-600',
                                                default => 'bg-gray-50 text-gray-600',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 {{ $color }} rounded-lg text-[10px] font-black">
                                            {{ $item['tipe'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-700 text-right">
                                        <div class="flex flex-col items-end">
                                            <span>{{ number_format($item['total_diterima'], 0, ',', '.') }}</span>
                                            <span
                                                class="text-[10px] text-gray-400 font-medium uppercase">{{ $item['satuan'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-700 text-right">
                                        {{ number_format($item['total_qty_kg'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-black text-gray-900 text-right">
                                        Rp {{ number_format($item['total_biaya'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                        Tidak ada data produksi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SECTION 3: PEMAKAIAN BAHAN BAKU & BAHAN PENOLONG --}}
        <div class="space-y-8">

            {{-- SUMMARY CARDS BAHAN KELUAR --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Bahan Penolong</p>
                    <h3 class="text-3xl font-black mt-1 italic">
                        Rp {{ number_format($summaryBahan['total_harga_penolong'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] text-blue-400 font-bold mt-2 uppercase italic">
                        {{ $summaryBahan['count_jenis_penolong'] }} Jenis SKU Terpakai
                    </p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Bahan Baku</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-1">
                        Rp {{ number_format($summaryBahan['total_harga_baku'], 0, ',', '.') }}
                    </h3>
                    <div class="flex items-center gap-2 mt-2">
                        @php $pctBaku = $summaryBahan['diff_baku_pct']; @endphp
                        <span
                            class="px-2 py-0.5 {{ $pctBaku >= 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-md text-[10px] font-black italic">
                            {{ $pctBaku >= 0 ? '+' : '' }}{{ number_format($pctBaku, 1) }}%
                        </span>
                        <p class="text-[10px] text-blue-600 font-bold uppercase italic">
                            {{ number_format($summaryBahan['total_kg_baku'], 2, ',', '.') }} Kg
                        </p>
                    </div>
                </div>

                <div class="bg-gray-900 p-6 rounded-3xl shadow-xl relative overflow-hidden text-white">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Barang Keluar</p>
                    <h3 class="text-3xl font-black mt-1">
                        Rp {{ number_format($summaryBahan['total_harga_keluar'], 0, ',', '.') }}
                    </h3>
                    <div class="flex items-center gap-2 mt-2">
                        @php $pctTotal = $summaryBahan['diff_total_keluar_pct']; @endphp
                        <span
                            class="px-2 py-0.5 {{ $pctTotal >= 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-md text-[10px] font-black italic">
                            {{ $pctTotal >= 0 ? '+' : '' }}{{ number_format($pctTotal, 1) }}% vs Lalu
                        </span>
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Baku +
                            Penolong</span>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
                {{-- KIRI: LIST RINCIAN KELUAR BAHAN BAKU --}}
                <div class="lg:col-span-3">
                    <h3
                        class="text-xs font-black uppercase tracking-widest text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                        Rincian Keluar Bahan Baku
                    </h3>
                    <div class="space-y-1 max-h-[500px] overflow-y-auto pr-4 scrollbar-thin scrollbar-thumb-gray-200">
                        @forelse ($rincianBahan->where('jenis_keluar', 'BAHAN BAKU') as $item)
                            <div
                                class="group flex items-center justify-between py-4 border-b border-gray-50 hover:bg-gray-50/50 transition-all rounded-xl px-2">
                                <div class="flex-1">
                                    <h4
                                        class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition-colors uppercase italic">
                                        {{ $item['nama_barang'] }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                            {{ number_format($item['total_qty'], 0, ',', '.') }} {{ $item['satuan'] }}
                                        </span>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span
                                            class="text-[10px] font-black text-blue-500 italic">{{ number_format($item['total_kg'], 2, ',', '.') }}
                                            Kg</span>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-1">
                                    <span class="text-sm font-black text-gray-900">Rp
                                        {{ number_format($item['total_biaya'], 0, ',', '.') }}</span>
                                    <div class="w-24 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full transition-all duration-700"
                                            style="width: {{ $item['persen'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="py-10 text-center text-gray-400 italic text-sm border-2 border-dashed border-gray-100 rounded-2xl">
                                Tidak ada pemakaian bahan baku.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KANAN: RINCIAN BAHAN PENOLONG KELUAR --}}
                <div class="lg:col-span-2">
                    <h3
                        class="text-xs font-black uppercase tracking-widest text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-gray-900 rounded-full"></span>
                        Rincian Bahan Penolong
                    </h3>
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-y-auto max-h-[500px] scrollbar-thin">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase">Nama Bahan
                                        </th>
                                        <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase text-right">
                                            Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($rincianBahan->where('jenis_keluar', 'PRODUKSI') as $penolong)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="px-5 py-4">
                                                <p class="text-[10px] font-bold text-gray-800 uppercase italic">
                                                    {{ Str::limit($penolong['nama_barang'], 30) }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold">
                                                    {{ number_format($penolong['total_qty'], 0) }}
                                                    {{ $penolong['satuan'] }}</p>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <span class="text-xs font-black text-gray-900 italic">Rp
                                                    {{ number_format($penolong['total_biaya'], 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2"
                                                class="px-5 py-10 text-center text-gray-400 italic text-[10px]">Data
                                                penolong kosong</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: PENGELUARAN DENGAN BEBAN KE HPP --}}
        <div class="space-y-6">

            {{-- SUMMARY CARD TOTAL PENGELUARAN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Biaya Pengeluaran
                            (HPP)</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-1 italic">
                            Rp {{ number_format($totalBebanHpp, 0, ',', '.') }}
                        </h3>
                        <div class="flex items-center gap-2 mt-2">
                            @if ($diffBebanHppPct != 0)
                                <span
                                    class="px-2 py-0.5 {{ $diffBebanHppPct >= 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-md text-[10px] font-black italic">
                                    {{ $diffBebanHppPct >= 0 ? '↑' : '↓' }}
                                    {{ number_format(abs($diffBebanHppPct), 1) }}%
                                </span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter italic">vs
                                    Periode Lalu</span>
                            @else
                                <span
                                    class="px-2 py-0.5 bg-gray-50 text-gray-400 rounded-md text-[10px] font-black italic">0%
                                    Perubahan</span>
                            @endif
                        </div>
                    </div>
                    {{-- Decorative Watermark --}}
                    <div class="absolute -right-4 -bottom-4 opacity-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                {{-- INFO JENIS BEBAN (Tren Persentase Saja) --}}
                <div class="bg-gray-900 p-6 rounded-3xl shadow-xl relative overflow-hidden text-white md:col-span-2">
                    <p
                        class="text-xs font-black text-gray-500 uppercase tracking-widest mb-6 border-l-2 border-orange-500 pl-3">
                        Tren Biaya (vs Periode Lalu)
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-x-8 gap-y-6 relative z-10">
                        @foreach ($bebanKategoriHpp->take(4) as $kat)
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-bold text-gray-500 uppercase italic leading-tight">
                                    {{ Str::limit($kat['nama'], 15) }}
                                </span>

                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-xl font-black italic {{ $kat['diff_pct'] >= 0 ? 'text-red-400' : 'text-green-400' }}">
                                        {{ $kat['diff_pct'] >= 0 ? '↑' : '↓' }}{{ number_format(abs($kat['diff_pct']), 1) }}%
                                    </span>
                                    {{-- Opsional: Indikator Dot --}}
                                    <div
                                        class="w-1.5 h-1.5 rounded-full {{ $kat['diff_pct'] >= 0 ? 'bg-red-400' : 'bg-green-400' }} animate-pulse">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Decorative Background Icon --}}
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                {{-- KIRI: Summary Kategori --}}
                <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <h3
                        class="text-xs font-black uppercase tracking-widest text-gray-800 mb-6 border-l-4 border-orange-500 pl-3">
                        Persentase Beban
                    </h3>
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 scrollbar-thin">
                        @forelse ($bebanKategoriHpp as $kat)
                            <div
                                class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col group relative overflow-hidden transition-all hover:bg-white hover:shadow-md">
                                <div
                                    class="absolute top-4 right-5 text-[10px] font-black text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">
                                    {{ number_format($kat['persen'], 1) }}%
                                </div>
                                <span
                                    class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-orange-600">{{ $kat['nama'] }}</span>
                                <span class="text-lg font-black text-gray-800 tracking-tight italic uppercase">Rp
                                    {{ number_format($kat['total'], 0, ',', '.') }}</span>
                                <div class="w-full bg-gray-200 h-1 mt-3 rounded-full overflow-hidden">
                                    <div class="bg-orange-500 h-full rounded-full transition-all duration-700"
                                        style="width: {{ $kat['persen'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-gray-400 italic text-sm">Tidak ada data transaksi.</div>
                        @endforelse
                    </div>
                </div>

                {{-- KANAN: Tabel Rincian --}}
                <div
                    class="lg:col-span-3 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="text-xs font-black text-gray-900 uppercase italic">Rincian Transaksi Beban</h2>
                        <span
                            class="text-[10px] font-bold text-gray-400 uppercase italic">{{ count($pengeluaranHpp) }}
                            Transaksi</span>
                    </div>
                    <div class="overflow-y-auto max-h-[500px] scrollbar-thin scrollbar-thumb-gray-200">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase text-center tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase text-right tracking-wider">
                                        Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($pengeluaranHpp as $beban)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-[11px] font-black text-gray-800 uppercase italic">
                                                {{ $beban->nama_pengeluaran }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold mt-0.5 leading-tight">
                                                {{ $beban->keterangan }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-lg uppercase">
                                                {{ \Carbon\Carbon::parse($beban->tanggal_pengeluaran)->format('d/m/y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-xs font-black text-gray-900 italic">Rp
                                                {{ number_format($beban->jumlah_pengeluaran, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: KESIMPULAN HPP AKHIR (SOFT VERSION - RESPONSIVE TEXT) --}}
        <div
            class="bg-slate-50 rounded-[2rem] md:rounded-[3rem] p-6 md:p-10 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">

                {{-- SISI KIRI: MAIN METRIC --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[9px] md:text-[10px] font-black tracking-widest uppercase text-slate-500 shadow-sm">
                            Summary HPP Final
                        </span>
                    </div>
                    <h2
                        class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-[0.1em] md:tracking-[0.2em] mb-2">
                        Harga Pokok Produksi (HPP) / Kg</h2>

                    <div class="flex flex-wrap items-baseline gap-2 md:gap-4">
                        {{-- Responsif: text-3xl di mobile, text-5xl di tablet, text-6xl di desktop --}}
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black italic tracking-tighter text-slate-800">
                            Rp {{ number_format($hppPerKg, 0, ',', '.') }}
                        </h1>

                        <div class="flex flex-col">
                            <span
                                class="text-sm md:text-lg font-bold {{ $diffHppPct >= 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ $diffHppPct >= 0 ? '↑' : '↓' }} {{ number_format(abs($diffHppPct), 1) }}%
                            </span>
                            <span
                                class="text-[8px] md:text-[9px] text-slate-400 font-bold uppercase italic whitespace-nowrap">vs
                                periode lalu</span>
                        </div>
                    </div>

                    <p class="text-xs md:text-sm text-slate-500 mt-4 md:mt-6 max-w-md font-medium leading-relaxed">
                        Berdasarkan total biaya <span class="text-slate-800 font-bold whitespace-nowrap">Rp
                            {{ number_format($grandTotalBiayaHpp, 0, ',', '.') }}</span>
                        dan total output produksi sebesar <span
                            class="text-slate-800 font-bold whitespace-nowrap">{{ number_format($totalVolumeProduksi, 2, ',', '.') }}
                            Kg</span>.
                    </p>
                </div>

                {{-- SISI KANAN: BREAKDOWN SEDERHANA --}}
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] p-5 md:p-8 border border-slate-100 shadow-sm">
                    <h3
                        class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 md:mb-6">
                        Komponen Pembentuk HPP</h3>

                    <div class="space-y-4 md:space-y-6">
                        {{-- Bahan Baku --}}
                        <div class="flex justify-between items-end border-b border-slate-50 pb-3 md:pb-4 group">
                            <div>
                                <p
                                    class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                    Bahan Baku</p>
                                <p class="text-base md:text-lg font-black italic text-slate-700">Rp
                                    {{ number_format($summaryBahan['total_harga_baku'], 0, ',', '.') }}</p>
                            </div>
                            <p
                                class="text-[10px] md:text-xs font-bold text-indigo-400 italic bg-indigo-50 px-2 py-0.5 md:py-1 rounded-md">
                                {{ $grandTotalBiayaHpp > 0 ? number_format(($summaryBahan['total_harga_baku'] / $grandTotalBiayaHpp) * 100, 1) : 0 }}%
                            </p>
                        </div>

                        {{-- Bahan Penolong --}}
                        <div class="flex justify-between items-end border-b border-slate-50 pb-3 md:pb-4 group">
                            <div>
                                <p
                                    class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                    Bahan Penolong</p>
                                <p class="text-base md:text-lg font-black italic text-slate-700">Rp
                                    {{ number_format($summaryBahan['total_harga_penolong'], 0, ',', '.') }}</p>
                            </div>
                            <p
                                class="text-[10px] md:text-xs font-bold text-sky-400 italic bg-sky-50 px-2 py-0.5 md:py-1 rounded-md">
                                {{ $grandTotalBiayaHpp > 0 ? number_format(($summaryBahan['total_harga_penolong'] / $grandTotalBiayaHpp) * 100, 1) : 0 }}%
                            </p>
                        </div>

                        {{-- Beban Lain --}}
                        <div class="flex justify-between items-end group">
                            <div>
                                <p
                                    class="text-[8px] md:text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                    Beban Operasional HPP</p>
                                <p class="text-base md:text-lg font-black italic text-slate-700">Rp
                                    {{ number_format($totalBebanHpp, 0, ',', '.') }}</p>
                            </div>
                            <p
                                class="text-[10px] md:text-xs font-bold text-amber-400 italic bg-amber-50 px-2 py-0.5 md:py-1 rounded-md">
                                {{ $grandTotalBiayaHpp > 0 ? number_format(($totalBebanHpp / $grandTotalBiayaHpp) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Abstract Decoration (Subtle) --}}
            <div class="absolute -right-20 -bottom-20 opacity-[0.03] text-slate-900 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-64 md:h-96 w-64 md:w-96" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </div>
</x-layout.user.app>
