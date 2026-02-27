<x-layout.user.app title="Laporan Keuangan">
    <div class="space-y-6" x-data="{ filterType: '{{ $filterType }}' }">

        {{-- SECTION 1: HEADER & FILTER --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase italic">Laporan Transaksi</h1>
                <p class="text-sm text-gray-400 font-medium italic">Insight barang masuk dan keluar periode ini</p>
            </div>

            <form action="{{ route('laporan-transaksi') }}" method="GET" class="flex flex-wrap items-center gap-2">
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
                            <option value="{{ $m }}" {{ (int) $selectedMonth === $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <select name="year"
                    class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none shadow-sm">
                    @foreach (range(date('Y') - 2, date('Y') + 1) as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            {{ $y }}</option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-gray-900 text-white px-5 py-2 rounded-xl font-bold text-xs uppercase hover:bg-blue-600 transition-all shadow-md">Update</button>

                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open" @click.away="open = false"
                        class="p-1.5 bg-white text-gray-500 rounded-xl border border-gray-200 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm flex items-center justify-center"
                        title="Unduh Laporan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-40 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden"
                        style="display: none;">

                        <div class="py-2">
                            <button type="submit" name="action" value="pdf" @click="open = false"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500"
                                    viewBox="0 0 24 24">
                                    <g fill="none" fill-rule="evenodd">
                                        <path
                                            d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                        <path fill="currentColor"
                                            d="M12 2v6.5a1.5 1.5 0 0 0 1.5 1.5H20v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm-.989 9.848a6.22 6.22 0 0 1-2.235 3.872c-.887.716-.076 2.121.988 1.712a6.22 6.22 0 0 1 4.471 0c1.064.41 1.875-.995.988-1.712a6.22 6.22 0 0 1-2.235-3.872c-.177-1.126-1.8-1.127-1.977 0M12 14.303l.806 1.394h-1.61zm2-12.26a2 2 0 0 1 1 .543L19.414 7a2 2 0 0 1 .543 1H14z" />
                                    </g>
                                </svg>
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- GRAFIK & LEADERBOARD --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Grafik Nilai Transaksi --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black uppercase italic mb-4 text-gray-500">Nilai Transaksi (Rp)</h3>
                <div class="h-80"><canvas id="chartTransaksi"></canvas></div>
            </div>

            {{-- Leaderboard Pengiriman KG --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
                <div class="mb-4">
                    <h3 class="text-sm font-black uppercase italic text-gray-500">Peringkat Pengiriman</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Berdasarkan Total Berat
                        (KG)</p>
                </div>

                {{-- Container Scrollable --}}
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar" style="max-height: 320px;">
                    <div class="space-y-3">
                        @foreach ($keluarPerCostumer as $index => $data)
                            <div
                                class="flex items-center justify-between p-3 rounded-2xl border border-gray-50 bg-gray-50/30 hover:bg-emerald-50/50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    {{-- Badge Peringkat --}}
                                    <div
                                        class="w-8 h-8 flex-shrink-0 rounded-xl flex items-center justify-center font-black text-xs 
                                {{ $loop->iteration <= 3 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-400 border border-gray-100' }}">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <h4
                                            class="text-xs font-black text-gray-800 uppercase truncate w-32 tracking-tighter">
                                            {{ $data['nama_costumer'] }}
                                        </h4>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                                            Rp {{ number_format($data['total_nilai']) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-sm font-black text-emerald-600 tracking-tighter">
                                        {{ number_format($data['total_kg'], 1) }}
                                    </span>
                                    <span class="text-[9px] text-gray-400 font-black italic uppercase">Kilogram</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- BARANG MASUK --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach (['Bahan Baku', 'Barang'] as $jenis)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div
                        class="p-5 bg-gray-50 border-b font-black uppercase italic text-gray-800 flex justify-between items-center">
                        <span>Supplier {{ $jenis }}</span>
                        <span class="text-[10px] bg-white px-2 py-1 rounded-lg border border-gray-200">INTERNAL
                            LIST</span>
                    </div>

                    <div class="overflow-y-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-50 text-gray-400 font-bold uppercase">
                                @forelse($masukPerSupplier->get($jenis, []) as $index => $data)
                                    <tr x-data="{ open: false }"
                                        class="flex flex-col border-b border-gray-50 last:border-0">
                                        <td class="px-6 py-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition-colors"
                                            @click="open = !open">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xs font-black">
                                                    {{ substr($data['nama_supplier'], 0, 1) }}
                                                </div>
                                                <div>
                                                    <span
                                                        class="block font-bold text-gray-800 tracking-tighter">{{ $data['nama_supplier'] }}</span>
                                                    <span
                                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                                        {{ $data['details']->unique('id_inventory')->count() }} Jenis
                                                        Barang Diterima
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right flex items-center gap-4">
                                                <span class="font-black text-blue-600 italic">Rp
                                                    {{ number_format($data['total_nilai']) }}</span>
                                                <i class="fas fa-chevron-down text-gray-300 text-xs transition-transform duration-300"
                                                    :class="open ? 'rotate-180' : ''"></i>
                                            </div>
                                        </td>

                                        <td x-show="open" x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0"
                                            class="px-6 pb-5 bg-gray-50/50">
                                            <div class="space-y-3 border-t border-gray-100 pt-4">
                                                <div
                                                    class="flex justify-between text-[9px] text-gray-400 font-black tracking-widest border-b pb-1">
                                                    <span>ITEM / BARANG</span>
                                                    <div class="flex gap-8">
                                                        <span>QTY</span>
                                                        <span class="w-20 text-right">TOTAL</span>
                                                    </div>
                                                </div>

                                                @foreach ($data['details'] as $item)
                                                    <div class="flex justify-between items-center text-xs">
                                                        <span
                                                            class="text-gray-600 font-medium">{{ $item->Inventory->Barang->nama_barang }}</span>
                                                        <div class="flex items-center gap-8">
                                                            <span class="font-bold text-gray-800">
                                                                {{ number_format($item->jumlah_diterima) }} <span
                                                                    class="text-[10px] text-gray-400">{{ $item->Inventory->Barang->satuan }}</span>
                                                            </span>
                                                            <span class="w-24 text-right font-black text-gray-900">
                                                                Rp {{ number_format($item->total_harga) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="p-20 text-center">
                                            <i class="fas fa-box-open text-gray-200 text-4xl mb-3 block"></i>
                                            <span class="text-gray-300 italic font-medium">Belum ada transaksi
                                                {{ $jenis }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- BARANG KELUAR --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 bg-gray-50 border-b font-black uppercase italic text-gray-800 text-center">Rekapitulasi
                Costumer</div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-100">
                    @foreach ($keluarPerCostumer as $data)
                        <tr x-data="{ open: false }" class="flex flex-col">
                            <td class="px-6 py-4 flex flex-wrap justify-between items-center cursor-pointer"
                                @click="open = !open">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="bg-emerald-100 text-emerald-700 w-10 h-10 rounded-full flex items-center justify-center font-black">
                                        {{ substr($data['nama_costumer'], 0, 1) }}</div>
                                    <div>
                                        <span
                                            class="block font-bold text-gray-900">{{ $data['nama_costumer'] }}</span>
                                        <span
                                            class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-black uppercase">{{ number_format($data['total_kg'], 2) }}
                                            KG</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <span class="block font-black text-gray-900 text-base">Rp
                                            {{ number_format($data['total_nilai']) }}</span>
                                        <span
                                            class="text-[10px] text-gray-400 font-bold uppercase">{{ number_format($data['total_qty']) }}
                                            Unit Keluar</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-300" :class="open ? 'rotate-180' : ''"></i>
                                </div>
                            </td>
                            {{-- Bagian Expand Detail pada Tabel Barang Keluar --}}
                            <td x-show="open" x-transition
                                class="px-6 pb-6 bg-emerald-50/20 border-t border-emerald-50">
                                <div class="space-y-3 mt-4">
                                    {{-- Header Detail --}}
                                    <div
                                        class="flex justify-between text-[9px] text-emerald-600 font-black tracking-widest border-b border-emerald-100 pb-1">
                                        <span>ITEM TERKIRIM</span>
                                        <div class="flex gap-8">
                                            <span>QTY</span>
                                            <span class="w-24 text-right">SUBTOTAL</span>
                                        </div>
                                    </div>

                                    @foreach ($data['details'] as $item)
                                        @php
                                            // Mengambil objek barang melalui relasi berjenjang
                                            $inventory = optional($item->DetailInventory)->Inventory;
                                            $barang = optional($inventory)->Barang;
                                        @endphp
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-gray-700">
                                                {{ $barang->nama_barang ?? 'Barang Terhapus' }}
                                            </span>
                                            <div class="flex items-center gap-8">
                                                <span class="font-bold text-gray-800">
                                                    {{ number_format($item->jumlah_keluar) }}
                                                    <span
                                                        class="text-[10px] text-gray-400">{{ $barang->satuan ?? '-' }}</span>
                                                </span>
                                                <span class="w-24 text-right font-black text-gray-900">
                                                    Rp {{ number_format($item->total_harga) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js - Bar Chart
        new Chart(document.getElementById('chartTransaksi'), {
            type: 'bar',
            data: {
                labels: ['Masuk', 'Keluar'],
                datasets: [{
                    data: [{{ $masukRaw->sum('total_harga') }},
                        {{ $keluarPerCostumer->sum('total_nilai') }}
                    ],
                    backgroundColor: ['#3b82f6', '#10b981'],
                    borderRadius: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-layout.user.app>
