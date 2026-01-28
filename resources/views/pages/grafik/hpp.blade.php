<x-layout.user.app title="Analisis Tren HPP">
    <div class="space-y-6 md:space-y-8" x-data="{ filterType: '{{ $filterType }}' }">
        <x-layout.filter.nav :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" :filterType="$filterType" :daftarPerusahaan="$daftarPerusahaan"/>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm md:col-span-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rata-rata HPP per Kg (Periode
                    Ini)</p>
                <p class="text-3xl font-black text-indigo-600">Rp {{ number_format($avgHpp, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Volume Produksi</p>
                <p class="text-2xl font-black text-gray-700">{{ number_format(array_sum($chartVol), 0, ',', '.') }} <span
                        class="text-xs font-normal text-gray-400">Kg</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Tren HPP vs Volume Produksi</h3>
                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded-lg font-bold">
                    {{ $filterType === 'month' ? 'Harian' : 'Bulanan' }}
                </span>
            </div>
            <div class="h-[400px]">
                <canvas id="hppChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest">Data Rincian Perhitungan HPP</h3>
                <div class="group relative">
                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded cursor-help font-bold">INFO
                        RUMUS</span>
                    <div
                        class="hidden group-hover:block absolute right-0 bottom-8 w-64 bg-gray-900 text-white text-[10px] p-3 rounded-lg shadow-xl z-50">
                        <p class="font-bold mb-1">Rumus HPP:</p>
                        <p class="italic">(Biaya Bahan + Biaya Ops) / Volume Produksi</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[11px] text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3 text-right">Biaya Bahan (Rp)</th>
                            <th class="px-4 py-3 text-right">Biaya Ops (Rp)</th>
                            <th class="px-4 py-3 text-right bg-blue-50/30">Total Biaya (Rp)</th>
                            <th class="px-4 py-3 text-center">Volume (Kg)</th>
                            <th class="px-4 py-3 text-right font-black text-indigo-600">HPP / Kg</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach (array_reverse($rincianHarian) as $row)
                            @if ($row['total_biaya'] > 0 || $row['volume'] > 0)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3 font-bold text-gray-700">{{ $row['label'] }}</td>
                                    <td class="px-4 py-3 text-right text-gray-500">
                                        {{ number_format($row['biaya_bahan'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-gray-500">
                                        {{ number_format($row['biaya_ops'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-blue-600 bg-blue-50/20">
                                        {{ number_format($row['total_biaya'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center font-medium">
                                        {{ number_format($row['volume'], 1, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-black text-indigo-600">
                                        {{ $row['hpp'] > 0 ? 'Rp ' . number_format($row['hpp'], 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatShort = (val) => {
                if (val >= 1e6) return (val / 1e6).toFixed(1) + ' Jt';
                if (val >= 1e3) return (val / 1e3).toFixed(1) + ' K';
                return val;
            };

            new Chart(document.getElementById('hppChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                            label: 'HPP per Kg (Rp)',
                            data: {!! json_encode($chartHpp) !!},
                            borderColor: '#4f46e5',
                            backgroundColor: '#4f46e5',
                            borderWidth: 3,
                            tension: 0.4,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Volume Produksi (Kg)',
                            type: 'bar',
                            data: {!! json_encode($chartVol) !!},
                            backgroundColor: '#e2e8f0',
                            borderRadius: 4,
                            yAxisID: 'y1',
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
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'HPP (Rupiah)',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: (v) => 'Rp ' + formatShort(v)
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Volume (Kg)',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: (v) => formatShort(v)
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout.user.app>
