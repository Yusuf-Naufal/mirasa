<x-layout.user.app title="Laporan Keuangan">
    <div class="space-y-6" x-data="{ filterType: '{{ $filterType }}' }">

        {{-- SECTION 1: HEADER & FILTER --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase italic">Laporan Pengeluaran</h1>
                <p class="text-sm text-gray-400 font-medium italic">Insight pengeluaran periode ini</p>
            </div>

            <form action="{{ route('laporan-pengeluaran') }}" method="GET" class="flex flex-wrap items-center gap-2">
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
