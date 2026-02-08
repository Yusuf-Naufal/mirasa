<x-layout.beranda.app title="Live Monitoring Dashboard">
    {{-- Library Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Animasi Peringatan Merah */
        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        .low-stock-animate {
            animation: pulse-red 2s infinite;
            border: 2px solid #fee2e2;
        }

        /* Animasi Angka Melayang (+ / -) */
        @keyframes floatUp {
            0% {
                transform: translateY(0);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            100% {
                transform: translateY(-50px);
                opacity: 0;
            }
        }

        .anim-indicator {
            position: absolute;
            right: 1.5rem;
            top: 40%;
            font-weight: 900;
            font-size: 1.8rem;
            pointer-events: none;
            z-index: 20;
        }

        .animate-plus {
            animation: floatUp 1.2s ease-out;
            color: #10b981;
        }

        .animate-minus {
            animation: floatUp 1.2s ease-out;
            color: #ef4444;
        }

        /* Card Styling */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .grid-header {
            position: relative;
            padding-left: 1rem;
        }

        .grid-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 2px;
        }
    </style>

    {{-- Wrapper agar tidak tertutup NAV --}}
    <div class="md:px-10 py-6 flex flex-col">
        <div class="flex-1 pt-12">

            {{-- Top Navigation & Filter Bar --}}
            <div
                class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 mb-8 rounded-2xl shadow-sm">
                <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Live Monitoring</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                Sync Terakhir: <span id="lastUpdated" class="text-slate-800">Menghubungkan...</span>
                            </p>
                        </div>
                    </div>

                    @if (auth()->user()->hasRole('Super Admin'))
                        <div class="flex items-center gap-3 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                            <span class="pl-3 text-[10px] font-black text-slate-400 uppercase">Unit:</span>
                            <select id="filterPerusahaan" onchange="fetchMonitoring()"
                                class="bg-white px-4 py-2 rounded-xl shadow-sm border-none text-xs font-bold focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                <option value="">Semua Perusahaan</option>
                                @foreach ($perusahaans as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6">
                {{-- SECTION 1: CRITICAL ALERTS (Stok < Minimum) --}}
                <div id="lowStockAlert" class="hidden mb-12">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-red-500 p-2 rounded-lg shadow-lg shadow-red-200">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Stok Kritis (Low Stock)
                        </h3>
                    </div>
                    <div id="lowStockContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"></div>
                </div>

                {{-- SECTION 2: ANALYTICS CARDS --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="glass-card p-6 rounded-[2.5rem] bg-white border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Pembelian
                                Hari Ini</p>
                            <h2 id="totalHargaMasuk" class="text-3xl font-black text-slate-800 tracking-tighter italic">
                                Rp 0</h2>
                            <div class="mt-4 h-1 w-12 bg-blue-500 rounded-full"></div>
                        </div>
                        <div class="glass-card p-6 rounded-[2.5rem] bg-white border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Pengeluaran
                                Hari Ini</p>
                            <h2 id="totalHargaKeluar"
                                class="text-3xl font-black text-slate-800 tracking-tighter italic">Rp 0</h2>
                            <div class="mt-4 h-1 w-12 bg-emerald-500 rounded-full"></div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 glass-card p-8 rounded-[2.5rem] bg-white border-slate-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Aktivitas 7 Hari Terakhir</h3>
                        </div>
                        <div class="h-44">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: INVENTORY CATEGORIES (Termasuk Stok 0) --}}
                <div class="space-y-16">
                    <section>
                        <div class="grid-header border-blue-500 mb-8">
                            <h3 class="text-base font-black text-slate-800 uppercase tracking-tighter">Hasil Produksi
                            </h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">FG, WIP, & EC</p>
                        </div>
                        <div id="grid-produksi" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
                    </section>

                    <section>
                        <div class="grid-header border-emerald-500 mb-8">
                            <h3 class="text-base font-black text-slate-800 uppercase tracking-tighter">Bahan Baku</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Material Utama
                                (BB)</p>
                        </div>
                        <div id="grid-bahan_baku" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
                    </section>

                    <section>
                        <div class="grid-header border-orange-500 mb-8">
                            <h3 class="text-base font-black text-slate-800 uppercase tracking-tighter">Barang Penolong
                            </h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Supplies (BP)</p>
                        </div>
                        <div id="grid-penolong" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script>
        let previousStok = {};
        let myChart = null;

        async function fetchMonitoring() {
            try {
                const perusahaanId = document.getElementById('filterPerusahaan')?.value || '';
                const response = await fetch(`/api/monitoring-data?id_perusahaan=${perusahaanId}`);
                const data = await response.json();

                document.getElementById('totalHargaMasuk').innerText = formatIDR(data.stats.totalMasuk);
                document.getElementById('totalHargaKeluar').innerText = formatIDR(data.stats.totalKeluar);
                document.getElementById('lastUpdated').innerText = new Date().toLocaleTimeString('id-ID');

                renderChart(data.chart);
                renderLowStock(data.lowStock);

                // Perbaikan: Tetap tampilkan meski stok 0
                renderGrid('grid-produksi', data.inventory.produksi);
                renderGrid('grid-bahan_baku', data.inventory.bahan_baku);
                renderGrid('grid-penolong', data.inventory.penolong);

            } catch (e) {
                console.error("Sync Error:", e);
            }
        }

        function renderChart(chartData) {
            const ctx = document.getElementById('trendChart').getContext('2d');
            if (myChart) myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Masuk',
                            data: chartData.masuk,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0
                        },
                        {
                            label: 'Keluar',
                            data: chartData.keluar,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0
                        }
                    ]
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
                            display: false
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 9,
                                    weight: 'bold'
                                },
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
        }

        function renderLowStock(items) {
            const container = document.getElementById('lowStockContainer');
            const alertBox = document.getElementById('lowStockAlert');
            if (!items || items.length === 0) {
                alertBox.classList.add('hidden');
                return;
            }
            alertBox.classList.remove('hidden');
            container.innerHTML = items.map(item => `
                <div class="bg-white p-5 rounded-[2rem] flex justify-between items-center low-stock-animate">
                    <div>
                        <h4 class="text-xs font-black text-slate-800 leading-tight">${item.barang.nama_barang}</h4>
                        <p class="text-[9px] font-bold text-red-500 mt-1 uppercase">Sisa: ${item.stok} / Limit: ${item.minimum_stok}</p>
                    </div>
                    <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M13 17h8m-8-4h8m-8-4h8M5.636 18.364a9 9 0 1112.728 0 9 9 0 01-12.728 0z" />
                        </svg>
                    </div>
                </div>
            `).join('');
        }

        function renderGrid(containerId, items) {
            const grid = document.getElementById(containerId);
            // Perbaikan: Jika data null atau tidak ada array
            if (!Array.isArray(items) || items.length === 0) {
                grid.innerHTML =
                    `<div class="col-span-full py-10 text-center text-slate-300 font-bold uppercase tracking-widest text-[10px] border-2 border-dashed border-slate-200 rounded-[2.5rem]">Tidak ada data barang</div>`;
                return;
            }

            items.forEach(item => {
                let card = document.getElementById(`inv-card-${item.id}`);
                const currentVal = parseInt(item.stok);
                const oldVal = previousStok[item.id];

                // Logic Warna Stok: Jika 0, beri warna abu-abu/merah
                const textStokClass = currentVal === 0 ? 'text-red-300' : 'text-slate-900';
                const opacityClass = currentVal === 0 ? 'opacity-60' : 'opacity-100';

                if (!card) {
                    grid.insertAdjacentHTML('beforeend', `
                        <div id="inv-card-${item.id}" class="glass-card bg-white p-6 rounded-[2.5rem] relative overflow-hidden h-40 flex flex-col justify-between ${opacityClass}">
                            <div>
                                <div class="bg-slate-100 w-fit px-2 py-0.5 rounded-lg mb-2">
                                    <p class="text-[8px] font-black text-slate-500 uppercase">${item.barang.kode}</p>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-800 leading-snug line-clamp-2">${item.barang.nama_barang}</h4>
                            </div>
                            <div class="flex items-end justify-between border-t border-slate-50 pt-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">${item.barang.satuan}</span>
                                <span id="stok-val-${item.id}" class="text-3xl font-black ${textStokClass} tracking-tighter italic">${currentVal}</span>
                            </div>
                            <div id="anim-${item.id}" class="anim-indicator"></div>
                        </div>
                    `);
                } else {
                    // Update nilai stok jika berbeda
                    const stokElement = document.getElementById(`stok-val-${item.id}`);
                    if (oldVal !== undefined && currentVal !== oldVal) {
                        stokElement.innerText = currentVal;

                        // Update class jika dari 0 ke berisi atau sebaliknya
                        if (currentVal === 0) {
                            stokElement.classList.replace('text-slate-900', 'text-slate-300');
                            card.classList.add('opacity-60');
                        } else {
                            stokElement.classList.replace('text-slate-300', 'text-slate-900');
                            card.classList.remove('opacity-60');
                        }

                        triggerAnimation(card, document.getElementById(`anim-${item.id}`), currentVal > oldVal ?
                            '+' : '-', currentVal > oldVal ? 'animate-plus' : 'animate-minus');
                    }
                }
                previousStok[item.id] = currentVal;
            });
        }

        function triggerAnimation(card, animEl, symbol, animClass) {
            animEl.innerText = symbol;
            animEl.classList.remove('animate-plus', 'animate-minus');
            void animEl.offsetWidth;
            animEl.classList.add(animClass);

            const ringColor = symbol === '+' ? 'ring-emerald-400' : 'ring-red-400';
            card.classList.add('ring-4', ringColor);
            setTimeout(() => card.classList.remove('ring-4', ringColor), 1500);
        }

        function formatIDR(val) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(val);
        }

        // Panggil pertama kali
        fetchMonitoring();
        // Sinkronisasi setiap 30 detik
        setInterval(fetchMonitoring, 30000);
    </script>
</x-layout.beranda.app>
