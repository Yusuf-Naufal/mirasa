<x-layout.user.app title="Grafik Pemakaian">
    <div class="space-y-6 md:space-y-8" x-data="{ filterType: '{{ $filterType }}' }">
        <x-layout.filter.nav :selectedMonth="$selectedMonth" :selectedYear="$selectedYear" :filterType="$filterType" :daftarPerusahaan="$daftarPerusahaan"/>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Biaya Pemakaian</p>
                <p class="text-2xl font-black text-rose-600">Rp {{ number_format($totalSemuaPemakaian, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Operasional</p>
                <p class="text-2xl font-black text-blue-600">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-xs font-black text-gray-500 uppercase mb-4 tracking-widest">Tren Biaya Pemakaian
                    (Rupiah)</h3>
                <div class="h-[300px]">
                    <canvas id="chartBiaya"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-xs font-black text-gray-500 uppercase mb-4 tracking-widest">Tren Jumlah Pemakaian
                    (Volume)</h3>
                <div class="h-[300px]">
                    <canvas id="chartJumlah"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50/50 border-b border-gray-100">
                <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest">Analisis Kategori & Perbandingan
                    Belanja</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3 text-center">Jumlah Pakai</th>
                            <th class="px-4 py-3 text-right">Biaya Pakai (HPP)</th>
                            <th class="px-4 py-3 text-right">Biaya Belanja</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($dataSummary as $row)
                            <tr>
                                <td class="px-4 py-3 font-black text-gray-700">{{ $row['nama'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold">{{ number_format($row['jumlah'], 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-gray-400 italic">{{ $row['satuan'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-rose-600">Rp
                                    {{ number_format($row['biaya_pakai'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold text-blue-600">Rp
                                    {{ number_format($row['biaya_keluar'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Fungsi Utilitas Tunggal untuk Format K, Jt, M
            const formatShort = (val) => {
                if (val === 0) return 0;
                const absoluteVal = Math.abs(val);
                if (absoluteVal >= 1e9) return (val / 1e9).toFixed(1).replace(/\.0$/, '') + ' M';
                if (absoluteVal >= 1e6) return (val / 1e6).toFixed(1).replace(/\.0$/, '') + ' Jt';
                if (absoluteVal >= 1e3) return (val / 1e3).toFixed(1).replace(/\.0$/, '') + ' K';
                return val.toLocaleString('id-ID');
            };

            const chartLabels = {!! json_encode($labels) !!};

            // 2. Grafik Biaya (Rupiah)
            new Chart(document.getElementById('chartBiaya'), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: {!! json_encode($datasetsBiaya) !!}
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
                                label: (ctx) =>
                                    ` ${ctx.dataset.label}: Rp ${ctx.raw.toLocaleString('id-ID')}`
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

            // 3. Grafik Jumlah (Volume)
            const dsJumlah = {!! json_encode($datasetsJumlah) !!};
            new Chart(document.getElementById('chartJumlah'), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: dsJumlah
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
                                label: (ctx) => {
                                    const sat = dsJumlah[ctx.datasetIndex].satuan || '';
                                    return ` ${ctx.dataset.label}: ${ctx.raw.toLocaleString('id-ID')} ${sat}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                // Menggunakan format K, Jt, M untuk sumbu Y Volume
                                callback: (v) => formatShort(v)
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout.user.app>
