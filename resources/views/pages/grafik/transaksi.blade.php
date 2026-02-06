<x-layout.user.app title="Grafik Transaksi">
    <div class="space-y-6 md:space-y-8" x-data="{ filterType: '{{ $filterType }}' }">
        <x-layout.filter.nav :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" :filterType="$filterType" :daftarPerusahaan="$daftarPerusahaan" />

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="font-black uppercase italic text-gray-800 mb-6 text-lg tracking-tighter text-center">Tren Nilai
                Transaksi (Rp)</h3>
            <div class="h-80"><canvas id="chartTrenNilai"></canvas></div>
        </div>

        @php
            $sections = [
                ['id' => 'BB', 'title' => 'SUPPLIER BAHAN BAKU', 'data' => $masukBB, 'color' => 'blue'],
                ['id' => 'Barang', 'title' => 'SUPPLIER BARANG', 'data' => $masukBarang, 'color' => 'indigo'],
                [
                    'id' => 'Costumer',
                    'title' => 'COSTUMER (FG/WIP/EC)',
                    'data' => ['chart' => $dsCostumerTrend, 'table' => $rincianCostumerTable],
                    'color' => 'emerald',
                ],
            ];
        @endphp

        @foreach ($sections as $sec)
            @if (count($sec['data']['chart']) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h3
                            class="font-black uppercase italic text-{{ $sec['color'] }}-600 mb-6 text-xs tracking-widest text-center">
                            Tren Volume {{ $sec['title'] }}</h3>
                        <div class="h-96"><canvas id="chartTrend{{ $sec['id'] }}"></canvas></div>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col h-full">
                        <h3 class="font-black uppercase italic text-gray-800 mb-4 text-xs text-center border-b pb-3">
                            Rincian {{ $sec['title'] }}
                        </h3>
                        <div class="overflow-y-auto max-h-[400px] custom-scrollbar flex-1">
                            <table class="w-full text-left text-xs">
                                <thead
                                    class="sticky top-0 bg-white text-gray-400 uppercase text-[9px] font-black tracking-widest border-b">
                                    <tr>
                                        <th class="py-2 px-3">{{ $sec['id'] === 'Costumer' ? 'PELANGGAN' : 'SUPPLIER' }}
                                        </th>
                                        <th class="py-2 px-3 text-right">JENIS</th>
                                    </tr>
                                </thead>
                                <tbody x-data="{ selected: null }">
                                    @foreach ($sec['data']['table'] as $id => $item)
                                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer group"
                                            @click="selected !== {{ $id }} ? selected = {{ $id }} : selected = null">
                                            <td class="p-3">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas text-[10px] text-gray-300 transition-transform duration-300"
                                                        :class="selected === {{ $id }} ?
                                                            'fa-chevron-down rotate-180 text-{{ $sec['color'] }}-500' :
                                                            'fa-chevron-right'"></i>
                                                    <span
                                                        class="font-bold text-gray-700 group-hover:text-{{ $sec['color'] }}-600 uppercase italic">
                                                        {{ $item['label'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="p-3 text-right">
                                                <span
                                                    class="bg-{{ $sec['color'] }}-50 text-{{ $sec['color'] }}-600 px-2 py-1 rounded-lg font-black italic">
                                                    {{ count($item['barang']) }} Item
                                                </span>
                                            </td>
                                        </tr>

                                        {{-- Detail Expand: Menampilkan Banyak Barang --}}
                                        <tr x-show="selected === {{ $id }}"
                                            x-transition:enter="transition ease-out duration-200" x-cloak>
                                            <td colspan="2" class="bg-gray-50/50 p-0">
                                                <div
                                                    class="px-4 py-3 space-y-2 border-l-4 border-{{ $sec['color'] }}-500 ml-4 my-2">
                                                    @foreach ($item['barang'] as $b)
                                                        <div
                                                            class="flex justify-between items-center text-[10px] border-b border-white pb-1 last:border-0">
                                                            <span
                                                                class="font-medium text-gray-500 uppercase">{{ $b['nama'] }}</span>
                                                            <span class="font-black text-gray-800">
                                                                {{ number_format($b['total']) }}
                                                                <span
                                                                    class="text-[9px] text-gray-400 font-normal italic lowercase">{{ $b['satuan'] }}</span>
                                                            </span>
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
                </div>
            @endif
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Fungsi Utilitas untuk Format
            const formatShort = (val) => {
                if (val >= 1e9) return (val / 1e9).toFixed(1).replace(/\.0$/, '') + ' M';
                if (val >= 1e6) return (val / 1e6).toFixed(1).replace(/\.0$/, '') + ' Jt';
                if (val >= 1e3) return (val / 1e3).toFixed(1).replace(/\.0$/, '') + ' K';
                return val;
            };

            const formatWeight = (val, satuan = 'KG') => {
                if (satuan.toUpperCase() === 'KG' && val >= 1000) {
                    return (val / 1000).toFixed(1).replace(/\.0$/, '') + ' TON';
                }
                return val.toLocaleString('id-ID') + ' ' + satuan;
            };

            // 2. Global Chart Config
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: {
                                size: 9,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.raw;
                                // Format khusus Rupiah untuk chartTrenNilai
                                if (ctx.chart.canvas.id === 'chartTrenNilai') {
                                    return ` ${ctx.dataset.label}: Rp ${val.toLocaleString('id-ID')}`;
                                }
                                // Format standar dengan pemisah ribuan untuk volume
                                return ` ${ctx.dataset.label}: ${val.toLocaleString('id-ID')}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) {
                                // Gunakan formatShort (K, Jt, M) untuk semua sumbu Y
                                const formatted = formatShort(v);
                                if (this.chart.canvas.id === 'chartTrenNilai') {
                                    return 'Rp ' + formatted;
                                }
                                return formatted;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            };

            // 3. Render function
            const renderLine = (ctxId, data) => {
                const canvas = document.getElementById(ctxId);
                if (canvas) {
                    new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($labels) !!},
                            datasets: data.map(d => ({
                                ...d,
                                tension: 0.4,
                                pointRadius: 2,
                                borderWidth: 2
                            }))
                        },
                        options: chartOptions
                    });
                }
            };

            // 4. Inisialisasi Semua Grafik
            renderLine('chartTrenNilai', [{
                    label: 'Masuk',
                    data: {!! json_encode($chartMasuk) !!},
                    borderColor: '#2563eb',
                    fill: true,
                    backgroundColor: 'rgba(37, 99, 235, 0.1)'
                },
                {
                    label: 'Keluar',
                    data: {!! json_encode($chartKeluar) !!},
                    borderColor: '#10b981',
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)'
                }
            ]);

            renderLine('chartTrendBB', {!! json_encode($masukBB['chart']) !!});
            renderLine('chartTrendBarang', {!! json_encode($masukBarang['chart']) !!});
            renderLine('chartTrendCostumer', {!! json_encode($dsCostumerTrend) !!});
        });
    </script>
</x-layout.user.app>
