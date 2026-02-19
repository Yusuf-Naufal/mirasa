<x-layout.user.app title="Manager Dashboard">
    <div class="space-y-6 py-4">

        {{-- Welcome Section --}}
        <div
            class="relative overflow-hidden bg-gradient-to-br from-white to-gray-50 border border-gray-100 rounded-3xl shadow-sm p-8">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tight">
                        Halo, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-gray-500 max-w-md font-medium leading-relaxed">
                        Berikut adalah ringkasan operasional dan efisiensi produksi perusahaan Anda untuk bulan
                        {{ now()->translatedFormat('F Y') }}.
                    </p>
                </div>
                <div class="px-5 py-3 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col items-center">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hari Ini</span>
                    <span class="text-sm font-black text-gray-800">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
            <div class="absolute right-0 top-0 opacity-[0.03] hidden lg:block">
                <svg class="w-80 h-80 -mt-20 -mr-20" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
        </div>

        {{-- Top Stats: Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- SKU --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm group transition-all hover:shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total SKU Produk</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalProducts) }}</h3>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600">
                    <span>{{ $jenisBarangDiproduksi }} SKU Terproses</span>
                </div>
            </div>

            {{-- Pengeluaran --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pengeluaran Bln Ini</p>
                <h3 class="text-2xl font-black text-red-600 tracking-tight">Rp
                    {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <div class="mt-4 text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Total Cash-Out Sistem
                </div>
            </div>

            {{-- Volume Produksi --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Volume Produksi</p>
                <h3 class="text-3xl font-black text-indigo-600">{{ number_format($totalVolumeProduksi, 1) }} <span
                        class="text-sm">Kg</span></h3>
                <div class="mt-4 text-xs font-bold text-indigo-400">Total Output (FG, WIP, EC)</div>
            </div>

            {{-- Rendemen --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all hover:shadow-xl">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Rendemen (Efisiensi)</p>
                <h3 class="text-3xl font-black text-green-600">{{ number_format($persentaseRendemen, 1) }}%</h3>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs font-bold text-red-400">Loss: {{ number_format($persentaseLoss, 1) }}%</span>
                </div>
            </div>
        </div>

        {{-- Bottom Row: Inventory SKU Status --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-3xl shadow-lg text-white">
                <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest opacity-80">Finished Goods (FG)
                </p>
                <h3 class="text-3xl font-black mt-1">{{ $countFG }} <span class="text-sm font-normal">SKU</span>
                </h3>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-3xl shadow-lg text-white">
                <p class="text-[10px] font-bold text-orange-100 uppercase tracking-widest opacity-80">Work In Progress
                    (WIP)</p>
                <h3 class="text-3xl font-black mt-1">{{ $countWIP }} <span class="text-sm font-normal">SKU</span>
                </h3>
            </div>
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 p-6 rounded-3xl shadow-lg text-white">
                <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest opacity-80">Eceran (EC)</p>
                <h3 class="text-3xl font-black mt-1">{{ $countEC }} <span class="text-sm font-normal">SKU</span>
                </h3>
            </div>
        </div>

        {{-- Middle Row: Chart & Grading --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Chart --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-xl font-black text-gray-900 tracking-tight mb-8">Trend Volume Keluar</h3>
                <div class="relative h-[300px]">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            {{-- Top Produk Section --}}
            <div
                class="bg-gray-900 p-8 rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden flex flex-col h-full">
                <div class="relative z-10 flex-1">
                    <h3 class="text-xl font-black tracking-tight mb-2">Top Produk</h3>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-8">
                        Volume Produksi Tertinggi (FG, WIP, EC)</p>

                    <div class="space-y-5">
                        @forelse ($topProduk as $index => $item)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    {{-- Badge Rank --}}
                                    <div
                                        class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-xs font-black group-hover:bg-indigo-600 transition-colors">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-gray-200 line-clamp-1 leading-none mb-1">{{ $item->nama_barang }}</span>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-indigo-400 font-black tracking-tighter">
                                                {{ number_format($item->qty_asli, 0, ',', '.') }}
                                            </span>
                                            <span
                                                class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">
                                                {{ $item->satuan }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-base font-black text-indigo-400">{{ number_format($item->total_qty, 1, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold text-gray-500 ml-1">Kg</span>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="border-b border-white/5 w-full"></div>
                            @endif
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 opacity-50">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-xs font-bold uppercase tracking-widest">Belum ada data produksi</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Background Decoration --}}
                <div class="absolute -right-10 -bottom-10 opacity-10">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('trendChart').getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Volume Keluar (Qty)',
                    data: @json($chartData),
                    borderColor: '#2563eb',
                    borderWidth: 4,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-layout.user.app>
