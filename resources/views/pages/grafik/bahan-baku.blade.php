<x-layout.user.app title="Grafik Bahan Baku">
    <div class="space-y-6 md:space-y-8" x-data="{ filterType: '{{ $filterType }}' }">

        <x-layout.filter.nav :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" :filterType="$filterType" :daftarPerusahaan="$daftarPerusahaan"/>

        {{-- QUICK STATS CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            {{-- Total Belanja --}}
            <div
                class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-gray-100 shadow-sm group hover:border-blue-200 transition-all">
                <div class="flex justify-between items-start mb-3 md:mb-4">
                    <div
                        class="p-2 md:p-3 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <span
                        class="hidden sm:block text-[9px] md:text-[10px] font-black text-blue-400 bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Nilai</span>
                </div>
                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total
                    Belanja</p>
                <h3 class="text-sm md:text-xl font-black text-gray-900 tracking-tight italic">Rp
                    {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</h3>
            </div>

            {{-- Total Pakai --}}
            <div
                class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-gray-100 shadow-sm group hover:border-rose-200 transition-all">
                <div class="flex justify-between items-start mb-3 md:mb-4">
                    <div
                        class="p-2 md:p-3 bg-rose-50 text-rose-600 rounded-xl md:rounded-2xl group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" />
                            <path d="M4 6v12c0 1.1.9 2 2 2h14v-4" />
                            <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4Z" />
                        </svg>
                    </div>
                    <span
                        class="hidden sm:block text-[9px] md:text-[10px] font-black text-rose-400 bg-rose-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Nilai</span>
                </div>
                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pakai
                </p>
                <h3 class="text-sm md:text-xl font-black text-gray-900 tracking-tight italic">Rp
                    {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</h3>
            </div>

            {{-- Total Volume In --}}
            <div
                class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-gray-100 shadow-sm group hover:border-emerald-200 transition-all">
                <div class="flex justify-between items-start mb-3 md:mb-4">
                    <div
                        class="p-2 md:p-3 bg-emerald-50 text-emerald-600 rounded-xl md:rounded-2xl group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="m7.5 4.27 9 5.15" />
                            <path
                                d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                        </svg>
                    </div>
                    <span
                        class="hidden sm:block text-[9px] md:text-[10px] font-black text-emerald-400 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Volume</span>
                </div>
                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total
                    Volume In</p>
                <h3 class="text-sm md:text-xl font-black text-gray-900 tracking-tight italic">
                    {{ number_format(collect($datasetsVolume)->where('type', 'in')->flatMap->data->sum(), 1) }}
                </h3>
            </div>

            {{-- Total Volume Out --}}
            <div
                class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-gray-100 shadow-sm group hover:border-orange-200 transition-all">
                <div class="flex justify-between items-start mb-3 md:mb-4">
                    <div
                        class="p-2 md:p-3 bg-orange-50 text-orange-600 rounded-xl md:rounded-2xl group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 22V12" />
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                        </svg>
                    </div>
                    <span
                        class="hidden sm:block text-[9px] md:text-[10px] font-black text-orange-400 bg-orange-50 px-2 py-1 rounded-lg uppercase tracking-tighter">Volume</span>
                </div>
                <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total
                    Volume Out</p>
                <h3 class="text-sm md:text-xl font-black text-gray-900 tracking-tight italic">
                    {{ number_format(collect($datasetsVolume)->where('type', 'out')->flatMap->data->sum(), 1) }}
                </h3>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            {{-- RUPIAH TREND --}}
            <div
                class="xl:col-span-8 bg-white p-4 md:p-8 rounded-2xl md:rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="mb-6 md:mb-8">
                    <h2 class="text-base md:text-xl font-black text-gray-900 tracking-tight uppercase italic">Trend
                        Nilai Material</h2>
                    <p class="text-[10px] md:text-xs text-gray-400 font-medium italic">Akumulasi total_harga per periode
                    </p>
                </div>
                <div class="h-[250px] md:h-[350px]">
                    <canvas id="valueChart"></canvas>
                </div>
            </div>

            {{-- SUMMARY SIDEBAR --}}
            <div class="xl:col-span-4 space-y-6">
                <div
                    class="bg-gray-900 p-6 md:p-8 rounded-2xl md:rounded-[2rem] shadow-xl relative overflow-hidden group h-full">
                    <h3
                        class="text-white font-black uppercase italic tracking-widest text-xs md:text-sm mb-6 flex items-center gap-3">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Ringkasan Per Item
                    </h3>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse ($items as $item)
                            @php
                                // Hitung Persentase Pemakaian: (Keluar / Masuk) * 100
                                $persentase = $item['masuk'] > 0 ? ($item['keluar'] / $item['masuk']) * 100 : 0;
                            @endphp
                            <div class="bg-white/5 border border-white/10 p-3 md:p-4 rounded-xl md:rounded-2xl">
                                <div class="flex justify-between items-center mb-2">
                                    <p
                                        class="text-[9px] md:text-[10px] font-black text-blue-400 uppercase truncate max-w-[120px]">
                                        {{ $item['name'] }}</p>
                                    <span class="text-[8px] font-black text-emerald-400 italic">Pakai:
                                        {{ number_format($persentase, 1) }}%</span>
                                </div>
                                <div class="flex items-end justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex justify-between text-[8px] text-gray-400 mb-1">
                                            <span>Masuk</span>
                                            <span>{{ number_format($item['masuk'], 1) }} {{ $item['satuan'] }}</span>
                                        </div>
                                        <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500/50" style="width: 100%"></div>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between text-[8px] text-gray-400 mb-1">
                                            <span>Keluar</span>
                                            <span>{{ number_format($item['keluar'], 1) }} {{ $item['satuan'] }}</span>
                                        </div>
                                        <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-orange-500/50"
                                                style="width: {{ min($persentase, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-white/30 text-xs italic">No Data Available</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- VOLUME TREND --}}
        <div class="bg-white p-4 md:p-8 rounded-2xl md:rounded-[2rem] border border-gray-100 shadow-sm">
            <h2 class="text-base md:text-xl font-black text-gray-900 tracking-tight uppercase italic mb-6">Trend Volume
                Per Item</h2>
            <div class="h-[300px] md:h-[450px]">
                <canvas id="itemVolumeChart"></canvas>
            </div>
        </div>
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

            // 2. Inisialisasi Data (Pastikan nama variabel tidak duplikat)
            const chartLabels = {!! json_encode($labels, JSON_NUMERIC_CHECK) !!};
            const dsVolume = {!! json_encode($datasetsVolume) !!};

            // 3. Value Chart (Trend Rupiah)
            const ctxValue = document.getElementById('valueChart');
            if (ctxValue) {
                new Chart(ctxValue, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                                label: 'Belanja (Masuk)',
                                data: {!! json_encode($chartMasuk, JSON_NUMERIC_CHECK) !!},
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Pemakaian (Keluar)',
                                data: {!! json_encode($chartKeluar, JSON_NUMERIC_CHECK) !!},
                                borderColor: '#fb7185',
                                backgroundColor: 'rgba(251, 113, 133, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        return ` ${ctx.dataset.label}: Rp ${ctx.raw.toLocaleString('id-ID')}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: (v) => 'Rp ' + formatShort(v)
                                }
                            }
                        }
                    }
                });
            }

            // 4. Volume Trend Chart (Multi-Line per Item)
            const ctxQty = document.getElementById('itemVolumeChart');
            if (ctxQty) {
                new Chart(ctxQty, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: dsVolume.map(ds => ({
                            label: ds.label,
                            data: ds.data,
                            borderColor: ds.borderColor,
                            backgroundColor: ds.borderColor.replace('1)', '0.1)'),
                            borderWidth: ds.type === 'in' ? 3 : 2,
                            borderDash: ds.type === 'out' ? [5, 5] : [],
                            tension: 0.4,
                            pointRadius: 2,
                            fill: false
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const dsInfo = dsVolume[ctx.datasetIndex];
                                        return ` ${ctx.dataset.label}: ${formatWeight(ctx.raw, dsInfo.satuan)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(v) {
                                        // Ringkasan sumbu Y untuk TON jika angka besar
                                        return v >= 1000 ? (v / 1000).toFixed(1) + 't' : v;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layout.user.app>
