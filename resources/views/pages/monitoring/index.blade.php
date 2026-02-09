<x-layout.beranda.app title="Live Monitoring Dashboard">
    {{-- Library Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Kunci layar agar tidak bisa scroll */
        body,
        html {
            height: 100%;
            overflow: hidden;
        }

        /* Animasi tetap dipertahankan namun lebih halus */
        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
        }

        .low-stock-animate {
            animation: pulse-red 2s infinite;
            border: 1px solid #fee2e2;
        }

        /* Glass Card lebih compact */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.2s;
        }

        /* Custom Scrollbar untuk grid jika item terlalu banyak */
        .custom-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .grid-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            border-radius: 2px;
        }

        /* Animasi Melayang untuk + / - */
        @keyframes float-up {
            0% {
                transform: translateY(0);
                opacity: 1;
            }

            100% {
                transform: translateY(-20px);
                opacity: 0;
            }
        }

        .animate-float {
            position: absolute;
            right: 10px;
            top: 5px;
            font-weight: 900;
            font-size: 1.2rem;
            pointer-events: none;
            animation: float-up 1s ease-out forwards;
        }

        /* Scrollbar Otomatis untuk Inventory Grids */
        .inventory-scroll-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            overflow-y: auto;
            max-height: 100%;
            /* Memastikan scroll bekerja dalam kontainer parent */
            padding-right: 4px;
        }

        /* Percantik Scrollbar */
        .inventory-scroll-container::-webkit-scrollbar {
            width: 4px;
        }

        .inventory-scroll-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .inventory-scroll-container::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    {{-- Container Utama Setinggi Layar --}}
    <div class="md:px-10 py-6 flex flex-col">
        <div class="flex-1 pt-12">

            {{-- Header: Dibuat lebih tipis --}}
            <header
                class="flex items-center justify-between bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Live Monitoring</h1>
                    <div class="flex items-center gap-2 border-l pl-4 border-slate-200">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                            Sync: <span id="lastUpdated" class="text-slate-800 italic">...</span>
                        </p>
                    </div>
                </div>

                @if (auth()->user()->hasRole('Super Admin'))
                    <select id="filterPerusahaan" onchange="fetchMonitoring()"
                        class="bg-slate-100 px-3 py-1.5 rounded-xl border-none text-[10px] font-bold focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="">Semua Unit</option>
                        @foreach ($perusahaans as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_perusahaan }} ({{ $p->kota }})</option>
                        @endforeach
                    </select>
                @endif
            </header>

            {{-- Main Content: Flex Grow agar mengisi sisa layar --}}
            <main class="flex-1 mt-2 flex flex-col gap-4 overflow-hidden">

                {{-- Row 1: Stats & Chart --}}
                <div class="grid grid-cols-12 gap-4 h-1/3 min-h-[180px]">
                    {{-- Stats --}}
                    <div class="col-span-3 flex flex-col gap-3">
                        <div class="flex-1 glass-card p-4 rounded-3xl flex flex-col justify-center">
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Pembelian
                                Hari
                                Ini</p>
                            <h2 id="totalHargaMasuk" class="text-xl font-black text-blue-600 tracking-tighter italic">Rp
                                0
                            </h2>
                        </div>
                        <div class="flex-1 glass-card p-4 rounded-3xl flex flex-col justify-center">
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Pengeluaran
                                Hari
                                Ini</p>
                            <h2 id="totalHargaKeluar"
                                class="text-xl font-black text-emerald-600 tracking-tighter italic">Rp
                                0</h2>
                        </div>
                    </div>

                    {{-- Chart --}}
                    <div class="col-span-6 glass-card p-4 rounded-3xl relative">
                        <p
                            class="text-[8px] font-black text-slate-400 uppercase tracking-widest absolute top-4 right-6">
                            Aktivitas 7 Hari</p>
                        <div class="h-full w-full pt-4">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>

                    {{-- Low Stock (Compact View) --}}
                    <div id="lowStockAlert"
                        class="col-span-3 glass-card p-4 rounded-3xl flex flex-col overflow-hidden bg-red-50/30 border-red-100">
                        <h3
                            class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <span class="flex h-1.5 w-1.5 rounded-full bg-red-500"></span> Stok Kritis
                        </h3>
                        <div id="lowStockContainer" class="flex-1 overflow-y-auto custom-scroll space-y-2 pr-1">
                            {{-- Item Low Stock --}}
                        </div>
                    </div>
                </div>

                {{-- Row 2: Inventory Grids --}}
                <div class="flex-1 grid grid-cols-3 gap-4 overflow-hidden min-h-0">
                    {{-- Kolom Produksi --}}
                    <div class="flex flex-col gap-2 overflow-hidden">
                        <div class="grid-header border-blue-500 pl-3">
                            <h3 class="text-[10px] font-black text-slate-800 uppercase italic">Produksi (FG/WIP)</h3>
                        </div>
                        <div id="grid-produksi"
                            class="flex-1 overflow-y-auto custom-scroll pr-1 grid grid-cols-1 gap-2 contents-start">
                        </div>
                    </div>

                    {{-- Kolom Bahan Baku --}}
                    <div class="flex flex-col gap-2 overflow-hidden border-x border-slate-200 px-2">
                        <div class="grid-header border-emerald-500 pl-3">
                            <h3 class="text-[10px] font-black text-slate-800 uppercase italic">Bahan Baku (BB)</h3>
                        </div>
                        <div id="grid-bahan_baku"
                            class="flex-1 overflow-y-auto custom-scroll pr-1 grid grid-cols-1 gap-2 contents-start">
                        </div>
                    </div>

                    {{-- Kolom Penolong --}}
                    <div class="flex flex-col gap-2 overflow-hidden">
                        <div class="grid-header border-orange-500 pl-3">
                            <h3 class="text-[10px] font-black text-slate-800 uppercase italic">Penolong (BP)</h3>
                        </div>
                        <div id="grid-penolong"
                            class="flex-1 overflow-y-auto custom-scroll pr-1 grid grid-cols-1 gap-2 contents-start">
                        </div>
                    </div>
                </div>
            </main>
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

                // Update Stats & Chart
                document.getElementById('totalHargaMasuk').innerText = formatIDR(data.stats.totalMasuk);
                document.getElementById('totalHargaKeluar').innerText = formatIDR(data.stats.totalKeluar);
                document.getElementById('lastUpdated').innerText = new Date().toLocaleTimeString('id-ID');

                renderChart(data.chart);
                renderLowStock(data.lowStock);

                // Render Grids
                renderGrid('grid-produksi', data.inventory.produksi);
                renderGrid('grid-bahan_baku', data.inventory.bahan_baku);
                renderGrid('grid-penolong', data.inventory.penolong);

            } catch (e) {
                console.error("Sync Error:", e);
            }
        }

        function renderChart(chartData) {
            const ctx = document.getElementById('trendChart').getContext('2d');

            // Membuat Gradient untuk efek visual yang lebih dalam
            const gradientIn = ctx.createLinearGradient(0, 0, 0, 300);
            gradientIn.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Blue
            gradientIn.addColorStop(1, 'rgba(59, 130, 246, 0)');

            const gradientOut = ctx.createLinearGradient(0, 0, 0, 300);
            gradientOut.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // Emerald
            gradientOut.addColorStop(1, 'rgba(16, 185, 129, 0)');

            if (myChart) myChart.destroy();

            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Masuk',
                            data: chartData.masuk,
                            borderColor: '#3b82f6',
                            backgroundColor: gradientIn,
                            fill: true,
                            tension: 0.45, // Sedikit lebih melengkung agar elegan
                            borderWidth: 4,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Keluar',
                            data: chartData.keluar,
                            borderColor: '#10b981',
                            backgroundColor: gradientOut,
                            fill: true,
                            tension: 0.45,
                            borderWidth: 4,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: false // Tetap false untuk menjaga kebersihan single-page
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            maximumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            display: false,
                            beginAtZero: true
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    weight: '600'
                                },
                                color: '#94a3b8',
                                padding: 10
                            }
                        }
                    }
                }
            });
        }

        function renderGrid(containerId, items) {
            const grid = document.getElementById(containerId);
            if (!Array.isArray(items) || items.length === 0) {
                grid.innerHTML =
                    `<div class="text-[8px] text-slate-300 py-4 text-center font-bold uppercase border border-dashed rounded-xl">Kosong</div>`;
                return;
            }

            grid.innerHTML = items.map(item => {
                const currentVal = parseInt(item.stok);
                const prevVal = previousStok[item.id] !== undefined ? previousStok[item.id] : currentVal;

                let animTag = '';
                let ringClass = '';

                // Deteksi Perubahan
                if (currentVal > prevVal) {
                    animTag = `<span class="animate-float text-emerald-500">+</span>`;
                    ringClass = 'ring-2 ring-emerald-400';
                } else if (currentVal < prevVal) {
                    animTag = `<span class="animate-float text-red-500">-</span>`;
                    ringClass = 'ring-2 ring-red-400';
                }

                // Simpan stok saat ini untuk pengecekan berikutnya
                previousStok[item.id] = currentVal;

                const textStokClass = currentVal === 0 ? 'text-red-400' : 'text-slate-800';
                const opacityClass = currentVal === 0 ? 'bg-red-50/50' : 'bg-white';

                return `
                            <div id="inv-card-${item.id}" class="glass-card ${opacityClass} ${ringClass} p-3 rounded-2xl flex items-center justify-between relative overflow-hidden">
                                ${animTag}
                                <div class="flex-1 overflow-hidden mr-2">
                                    <p class="text-[7px] font-black text-slate-400 uppercase truncate">${item.barang.kode}</p>
                                    <h4 class="text-[10px] font-extrabold text-slate-700 leading-tight truncate">${item.barang.nama_barang}</h4>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <span class="text-lg font-black ${textStokClass} italic leading-none">${currentVal}</span>
                                    <span class="text-[7px] font-bold text-slate-400 uppercase">${item.barang.satuan}</span>
                                </div>
                            </div>
                        `;
            }).join('');

            // Hapus efek ring setelah 2 detik agar tidak permanen
            setTimeout(() => {
                items.forEach(item => {
                    const el = document.getElementById(`inv-card-${item.id}`);
                    if (el) el.classList.remove('ring-2', 'ring-emerald-400', 'ring-red-400');
                });
            }, 2000);
        }

        // Update fungsi renderLowStock untuk menyesuaikan tampilan list
        function renderLowStock(items) {
            const container = document.getElementById('lowStockContainer');
            const alertBox = document.getElementById('lowStockAlert');
            if (!items || items.length === 0) {
                alertBox.classList.add('opacity-30');
                container.innerHTML = `<p class="text-[8px] text-slate-400 text-center py-4 italic">Semua stok aman</p>`;
                return;
            }
            alertBox.classList.remove('opacity-30');
            container.innerHTML = items.map(item => {
                const isOut = parseInt(item.stok) <= 0;
                const statusText = isOut ? 'HABIS' : `Sisa: ${item.stok}`;
                const bgBadge = isOut ? 'bg-red-600 text-white' : 'bg-red-100 text-red-600';

                return `
                            <div class="bg-white/80 p-2 rounded-xl flex justify-between items-center border border-red-100">
                                <div class="w-2/3">
                                    <h4 class="text-[9px] font-bold text-slate-700 truncate">${item.barang.nama_barang}</h4>
                                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-tighter">Min: ${item.minimum_stok}</p>
                                </div>
                                <span class="text-[8px] font-black px-2 py-0.5 rounded-md ${bgBadge}">${statusText}</span>
                            </div>
                        `;
            }).join('');
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
