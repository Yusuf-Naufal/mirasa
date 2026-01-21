<x-layout.user.app title="Grafik Produksi">
    <div class="space-y-6 md:space-y-8" x-data="{ filterType: '{{ $filterType }}' }">

        <x-layout.filter.nav :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" :filterType="$filterType" />

        {{-- SUMMARY STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Total Produksi --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 16 16">
                        <path fill="currentColor" fill-rule="evenodd"
                            d="M13.5 10.421V5.475l-2 .714V8.25a.75.75 0 0 1-1.5 0V6.725l-2.25.804v6.088l4.777-1.792a1.5 1.5 0 0 0 .973-1.404m-2.254-5.734l1.6-.571a2 2 0 0 0-.175-.104L9.499 2.427a1.5 1.5 0 0 0-1.197-.063l-.941.353l3.724 1.862q.09.045.16.108M5.444 3.435l3.878 1.94l-2.273.811l-3.805-1.903q.108-.063.23-.109zm.806 4.029L2.5 5.589v5.057a1.5 1.5 0 0 0 .83 1.342l2.92 1.46zM1 5.579c0-.436.094-.856.266-1.236a.75.75 0 0 1 .2-.37c.342-.54.855-.968 1.48-1.203L7.777.96a3 3 0 0 1 2.394.125l3.172 1.586A3 3 0 0 1 15 5.354v5.067a3 3 0 0 1-1.947 2.809l-4.828 1.81a3 3 0 0 1-2.395-.125l-3.172-1.586A3 3 0 0 1 1 10.646z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Output Produksi</p>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight italic">
                        {{ number_format($totalBerat, 1) }} <span class="text-sm">KG</span></h3>
                </div>
            </div>

            {{-- Jenis Barang --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Komposisi Produk</p>
                <div class="flex gap-4">
                    @foreach (['FG' => 'FG', 'WIP' => 'WIP', 'EC' => 'Eceran'] as $k => $label)
                        <div class="text-center">
                            <span class="block text-lg font-black text-blue-600">{{ $countPerKategori[$k] ?? 0 }}</span>
                            <span class="text-[8px] font-bold text-gray-400 uppercase">{{ $label }}
                                ({{ $k }})
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Product --}}
            <div class="bg-gray-900 p-6 rounded-3xl shadow-xl flex items-center justify-between">
                <div class="text-white">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Paling Banyak
                        Diproduksi</p>
                    <h3 class="text-lg font-bold leading-tight">{{ $topProduk->first()->nama_barang ?? 'N/A' }}</h3>
                </div>
                <div class="text-right">
                    <span
                        class="text-emerald-400 font-black italic">{{ number_format($topProduk->first()->total_qty ?? 0, 0) }}
                        KG</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            {{-- LINE CHART TREND PRODUKSI --}}
            <div class="xl:col-span-8 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="mb-8">
                    <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase italic">Grafik Hasil Produksi
                    </h2>
                    <p class="text-xs text-gray-400 font-medium italic">Monitor volume output (FG, WIP, EC) per periode
                    </p>
                </div>
                <div class="h-[400px]">
                    <canvas id="chart-produksi-harian"></canvas>
                </div>
            </div>

            {{-- TOP 5 ITEMS TABLE --}}
            <div class="xl:col-span-4 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <h3
                    class="text-gray-900 font-black uppercase italic tracking-widest text-sm mb-6 flex items-center gap-3">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Top 5 Produk
                </h3>
                <div class="space-y-4">
                    @forelse($topProduk as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-6 h-6 flex items-center justify-center bg-gray-900 text-white text-[10px] font-black rounded-lg italic">#{{ $index + 1 }}</span>
                                <p class="text-xs font-bold text-gray-700 truncate w-32">{{ $item->nama_barang }}</p>
                            </div>
                            <p class="text-xs font-black text-emerald-600 italic">
                                {{ number_format($item->total_qty, 1) }} KG</p>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-400 italic">Data tidak ditemukan</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- PERBANDINGAN BAHAN BAKU VS HASIL (RENDEMEN) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Ringkasan Rendemen --}}
            <div
                class="lg:col-span-4 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-center">
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Rendemen Akhir</p>
                <h2 class="text-6xl font-black text-emerald-600 italic tracking-tighter">
                    {{ number_format($persentaseRendemen, 1) }}%
                </h2>

                <div class="mt-8 space-y-4">
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Efisiensi (Output
                            KG)</span>
                        <span
                            class="text-xs font-black text-emerald-500">{{ number_format($persentaseRendemen, 1) }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: {{ $persentaseRendemen }}%"></div>
                    </div>

                    <div class="flex justify-between items-end mt-2">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Penyusutan
                            (Loss)</span>
                        <span class="text-xs font-black text-rose-500">{{ number_format($persentaseLoss, 1) }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500" style="width: {{ $persentaseLoss }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Grafik Perbandingan Volume --}}
            <div class="lg:col-span-8 bg-gray-900 p-8 rounded-[2rem] shadow-xl relative overflow-hidden group">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <h3 class="text-white font-black uppercase italic tracking-widest text-sm flex items-center gap-3">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Perbandingan Input BB vs Output (FG/WIP/EC)
                    </h3>
                    <div class="flex gap-4">
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 uppercase font-bold">Input (BB Keluar)</p>
                            <p class="text-sm font-black text-blue-400 italic">{{ number_format($bbKeluar, 1) }} KG</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 uppercase font-bold">Output (Konversi KG)</p>
                            <p class="text-sm font-black text-emerald-400 italic">{{ number_format($totalBerat, 1) }}
                                KG</p>
                        </div>
                    </div>
                </div>
                <div class="h-[250px]">
                    <canvas id="chart-comparison-rendemen"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[600px]">
                    <thead class="bg-gray-50 uppercase text-[10px] font-black text-gray-500">
                        <tr>
                            <th class="px-6 py-4 sticky left-0 bg-gray-50 md:relative">Nama Barang (BB)</th>
                            <th class="px-6 py-4 text-right text-emerald-600">Grade A</th>
                            <th class="px-6 py-4 text-right text-orange-600">Second</th>
                            <th class="px-6 py-4 text-right text-purple-600">Jumbo</th>
                            <th class="px-6 py-4 text-right">Kupas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($hasilGrading as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-700 sticky left-0 bg-white md:relative">
                                    {{ $item->Barang->nama_barang }}
                                </td>
                                <td class="px-6 py-4 text-right font-black">{{ number_format($item->total_a, 1) }}</td>
                                <td class="px-6 py-4 text-right font-black">{{ number_format($item->total_s, 1) }}</td>
                                <td class="px-6 py-4 text-right font-black">{{ number_format($item->total_j, 1) }}</td>
                                <td class="px-6 py-4 text-right font-black">{{ number_format($item->total_kupas, 1) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- CHART JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatShort = (val) => {
                if (val >= 1e6) return (val / 1e6).toFixed(1).replace(/\.0$/, '') + ' Jt';
                if (val >= 1e3) return (val / 1e3).toFixed(1).replace(/\.0$/, '') + ' K';
                return val;
            };

            const labels = {!! json_encode($labels, JSON_NUMERIC_CHECK) !!};
            const datasetsProduksi = {!! json_encode($datasetsProduksi) !!};
            const comparisonData = {!! json_encode($comparisonData) !!};

            const ctx = document.getElementById('chart-produksi-harian');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasetsProduksi.map(ds => ({
                            label: ds.label,
                            data: ds.data,
                            borderColor: ds.borderColor,
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            fill: false
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 10,
                                        weight: 'bold'
                                    },
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const dsInfo = datasetsProduksi[context.datasetIndex];
                                        const val = context.parsed.y;
                                        return ` ${dsInfo.label}: ${val.toLocaleString('id-ID')} ${dsInfo.satuan}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [5, 5],
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    callback: (v) => formatShort(v)
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
            }

            const ctxComp = document.getElementById('chart-comparison-rendemen').getContext('2d');
            new Chart(ctxComp, {
                type: 'bar',
                data: {
                    labels: comparisonData.labels,
                    datasets: [{
                        data: comparisonData.values,
                        backgroundColor: ['#3b82f6', '#10b981', '#fb7185'],
                        borderRadius: 12,
                        barThickness: 40
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
                            grid: {
                                color: 'rgba(255,255,255,0.05)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#fff',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout.user.app>
