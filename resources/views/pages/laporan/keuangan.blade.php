<x-layout.user.app title="Laporan Keuangan">
    <div class="max-w-7xl mx-auto space-y-8 pb-12" x-data="{ filterType: '{{ $filterType }}' }">

        {{-- SECTION 1: HEADER & FILTER --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase italic">Laporan Keuangan</h1>
                <p class="text-sm text-gray-400 font-medium italic">Insight pengeluaran periode ini</p>
            </div>

            <form action="{{ route('laporan-keuangan') }}" method="GET" class="flex flex-wrap items-center gap-2">
                @if (auth()->user()->hasRole('Super Admin'))
                    <select name="id_perusahaan"
                        class="rounded-xl border-gray-200 text-xs font-bold py-2 px-3 outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                        <option value="">Semua Perusahaan</option>
                        @foreach ($perusahaan as $p)
                            <option value="{{ $p->id }}"
                                {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}>{{ $p->nama_perusahaan }} ({{ $p->kota }})
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
            </form>
        </div>

        {{-- SECTION 2: SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
                <h2 class="text-3xl font-black text-gray-900 tracking-tighter">Rp
                    {{ number_format($totalBulanIni, 0, ',', '.') }}</h2>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="px-2 py-1 rounded-lg text-[10px] font-black {{ $percentage >= 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                        {{ $percentage >= 0 ? '↑' : '↓' }} {{ number_format(abs($percentage), 1) }}%
                    </span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase italic">vs Periode Lalu</span>
                </div>
            </div>

            {{-- Selisih Nominal --}}
            <div class="bg-gray-900 p-8 rounded-[2rem] text-white shadow-xl relative overflow-hidden">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Selisih Nominal</p>
                <h2 class="text-3xl font-black tracking-tighter italic">Rp {{ number_format(abs($diff), 0, ',', '.') }}
                </h2>
                <p
                    class="text-[9px] mt-4 font-black uppercase {{ $totalBulanLalu == 0 ? 'text-blue-400' : ($diff > 0 ? 'text-red-400' : 'text-green-400') }}">
                    @if ($totalBulanLalu == 0)
                        Baru Tercatat Periode Ini
                    @else
                        {{ $diff > 0 ? 'Terjadi Kenaikan' : 'Penghematan Biaya' }}
                    @endif
                </p>
            </div>

            <div class="bg-blue-600 p-8 rounded-[2rem] text-white shadow-xl">
                <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1">Periode Aktif</p>
                <h2 class="text-2xl font-black uppercase tracking-tighter italic">
                    {{ $filterType === 'month' ? Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') . ' ' . $selectedYear : 'Tahun ' . $selectedYear }}
                </h2>
                <div class="mt-4 h-1 w-12 bg-white/30 rounded-full"></div>
            </div>
        </div>

        {{-- SECTION 3: LINE CHART (TREN) --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Tren Pengeluaran per Kategori
                </h3>
            </div>
            <div class="w-full h-[350px]">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- SECTION 4: PIE CHART & DETAILS --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            {{-- Pie Chart --}}
            <div
                class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col items-center">
                <h3
                    class="text-xs font-black uppercase tracking-widest text-gray-800 mb-8 self-start border-l-4 border-blue-600 pl-3">
                    Struktur Biaya (%)</h3>
                <div class="w-full max-w-[280px]">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            {{-- Breakdown List --}}
            <div class="lg:col-span-3 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3
                    class="text-xs font-black uppercase tracking-widest text-gray-800 mb-6 border-l-4 border-gray-900 pl-3">
                    Rincian Nominal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($chartData as $cat => $val)
                        @php $perCategory = $totalBulanIni > 0 ? ($val / $totalBulanIni) * 100 : 0; @endphp
                        <div
                            class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col transition-all hover:bg-white hover:shadow-md group relative overflow-hidden">
                            <div
                                class="absolute top-4 right-5 text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                                {{ number_format($perCategory, 1) }}%
                            </div>
                            <span
                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-600">{{ $cat }}</span>
                            <span class="text-lg font-black text-gray-800 tracking-tight uppercase">Rp
                                {{ number_format($val, 0, ',', '.') }}</span>
                            <div class="w-full bg-gray-200 h-1 mt-3 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full" style="width: {{ $perCategory }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-10 text-center text-gray-400 italic text-sm">Tidak ada data transaksi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- LINE CHART (TREN) ---
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: {!! json_encode($lineChartData) !!}
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
                                boxWidth: 8,
                                usePointStyle: true,
                                font: {
                                    size: 9,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                font: {
                                    size: 9
                                },
                                callback: v => 'Rp ' + (v / 1000000) + 'jt'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 9
                                }
                            }
                        }
                    }
                }
            });

            // --- PIE CHART (LINGKARAN PENUH) ---
            const pieCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($chartData->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($chartData->values()) !!},
                        backgroundColor: ['#1e293b', '#2563eb', '#10b981', '#f59e0b', '#8b5cf6',
                            '#ef4444'
                        ],
                        borderWidth: 4,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
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
