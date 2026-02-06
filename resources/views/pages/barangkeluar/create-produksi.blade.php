<x-layout.beranda.app title="Tambah Barang Keluar - Produksi">
    <div class="min-h-screen bg-gray-50/50 md:px-10 py-8" x-data="{
        open: false,
        search: '',
        selectedInventoryId: '',
        selectedName: '',
        maxStok: 0,
        selectedSatuan: '-',
        jumlah: 0,
    
        {{-- Data mentah dari Server --}}
        inventoryRaw: {{ $inventory->map(
                fn($inv) => [
                    'id' => $inv->id,
                    'nama' => $inv->Barang->nama_barang,
                    'kode' => $inv->Barang->kode,
                    'satuan' => $inv->Barang->satuan,
                    'stok_total' => (float) $inv->stok,
                    'batches' => $inv->DetailInventory->map(
                        fn($d) => [
                            'id' => $d->id,
                            'tgl' => \Carbon\Carbon::parse($d->tanggal_masuk)->format('d/m/y'),
                            'tanggal_masuk_raw' => $d->tanggal_masuk,
                            'created_at' => $d->created_at,
                            'stok' => (float) $d->stok,
                            'harga' => (float) $d->harga,
                        ],
                    ),
                ],
            )->toJson() }},
    
        get filtered() {
            return this.inventoryRaw.filter(i =>
                i.nama.toLowerCase().includes(this.search.toLowerCase()) &&
                i.stok_total > 0
            );
        },
    
        select(i) {
            this.selectedInventoryId = i.id;
            this.selectedName = i.nama;
            this.maxStok = i.stok_total;
            this.selectedSatuan = i.satuan;
            this.open = false;
        },
    
        {{-- Logika Simulasi FIFO (Multi-Batch) --}}
        get simulasiFIFO() {
            if (!this.selectedInventoryId || this.jumlah <= 0) return { totalBiaya: 0, rencana: [] };
    
            let sisa = this.jumlah;
            let totalBiaya = 0;
            let rencana = [];
            let item = this.inventoryRaw.find(inv => inv.id === this.selectedInventoryId);
    
            if (!item || !item.batches) return { totalBiaya: 0, rencana: [] };
    
            // --- LOGIKA SORTING FIFO GANDA ---
            const sortedBatches = [...item.batches].sort((a, b) => {
                // 1. Urutkan berdasarkan Tanggal Masuk (Prioritas Utama)
                let dateA = new Date(a.tanggal_masuk_raw);
                let dateB = new Date(b.tanggal_masuk_raw);
    
                if (dateA - dateB !== 0) {
                    return dateA - dateB; // Dari yang terlama ke terbaru
                }
    
                // 2. Jika tanggal masuk sama, urutkan berdasarkan created_at (Prioritas Kedua)
                return new Date(a.created_at) - new Date(b.created_at);
            });
    
            for (let b of sortedBatches) {
                if (sisa <= 0) break;
                let diambil = Math.min(b.stok, sisa);
                if (diambil > 0) {
                    rencana.push({
                        tgl: b.tgl,
                        qty: diambil,
                        harga: b.harga,
                        subtotal: diambil * b.harga
                    });
                    totalBiaya += diambil * b.harga;
                    sisa -= diambil;
                }
            }
            return { totalBiaya, rencana };
        }
    }">

        <div class="mx-auto flex flex-col pt-12">
            {{-- Header --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('barang-keluar.index') }}"
                        class="group inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-semibold transition-all mb-2">
                        <svg class="h-4 w-4 mr-1 transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengeluaran <span
                            class="text-blue-600">FIFO</span></h1>
                    <p class="text-sm text-gray-500 font-medium italic">*Sistem akan mengambil batch tertua secara
                        otomatis jika jumlah melebihi satu batch.</p>
                </div>
            </div>

            <form action="{{ route('barang-keluar.store') }}" method="POST" class="form-prevent-multiple-submits">
                @csrf
                <input type="hidden" name="jenis_keluar" value="PRODUKSI">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {{-- SISI KIRI: PEMILIHAN BARANG --}}
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                            <label class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Langkah 1:
                                Pilih Barang</label>
                            <div class="relative">
                                <input type="hidden" name="id_inventory" :value="selectedInventoryId">
                                <button type="button" @click="open = !open"
                                    class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 transition-all">
                                    <span x-text="selectedName || '-- Cari Nama Barang --'"
                                        :class="selectedName ? 'text-gray-800 font-bold' : 'text-gray-400'"></span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                                    <input type="text" x-model="search" placeholder="Cari barang..."
                                        class="w-full px-4 py-3 border-b outline-none">
                                    <div class="max-h-60 overflow-y-auto">
                                        <template x-for="i in filtered" :key="i.id">
                                            <button type="button" @click="select(i)"
                                                class="w-full px-5 py-3 text-left hover:bg-blue-50 border-b border-gray-50 flex justify-between items-center transition-colors">
                                                <div>
                                                    <span class="font-bold text-gray-700 block" x-text="i.nama"></span>
                                                    <span class="text-[10px] text-gray-400" x-text="i.kode"></span>
                                                </div>
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-lg font-bold"
                                                    x-text="'Stok: ' + i.stok_total"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- VISUALISASI FIFO BATCH --}}
                        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-200"
                            x-show="selectedInventoryId">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-bold uppercase tracking-wider">Rencana Pengambilan Batch</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>

                            <div class="space-y-3">
                                <template x-if="simulasiFIFO.rencana.length === 0">
                                    <p class="text-xs text-blue-200 italic font-medium">Masukkan jumlah untuk melihat
                                        simulasi batch...</p>
                                </template>

                                <template x-for="(r, index) in simulasiFIFO.rencana" :key="index">
                                    <div
                                        class="flex justify-between items-center bg-blue-500/50 p-3 rounded-xl border border-blue-400/30">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-blue-200 font-bold uppercase">Batch Tgl: <span
                                                    x-text="r.tgl" class="text-white"></span></span>
                                            <span class="text-xs font-medium">Rp <span
                                                    x-text="new Intl.NumberFormat('id-ID').format(r.harga)"></span></span>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-black text-lg" x-text="r.qty"></span>
                                            <span class="text-[10px]" x-text="selectedSatuan"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-6 pt-4 border-t border-blue-400 flex justify-between items-center">
                                <span class="text-xs font-bold uppercase">Total Estimasi Biaya:</span>
                                <span class="text-xl font-black">Rp <span
                                        x-text="new Intl.NumberFormat('id-ID').format(simulasiFIFO.totalBiaya)"></span></span>
                            </div>
                        </div>
                    </div>

                    {{-- SISI KANAN: DETAIL FORM --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                            <label
                                class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-6 block">Langkah
                                2: Detail Keluar</label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal Keluar</label>
                                    <input type="date" name="tanggal_keluar" value="{{ date('Y-m-d') }}" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahap Proses</label>
                                    <select name="id_proses" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih Proses --</option>
                                        @foreach ($proses as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_proses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <div class="flex justify-between items-center ml-1">
                                        <label class="text-xs font-bold text-blue-600 uppercase">Jumlah
                                            Pengeluaran</label>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Tersedia: <span
                                                x-text="maxStok" class="text-blue-600"></span> <span
                                                x-text="selectedSatuan"></span></span>
                                    </div>
                                    <div class="relative">
                                        <input type="number" step="any" name="jumlah_keluar"
                                            x-model.number="jumlah" :max="maxStok" required
                                            class="w-full px-5 py-5 bg-blue-50 border-2 border-blue-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-black text-2xl outline-none transition-all">
                                        <span
                                            class="absolute right-6 top-1/2 -translate-y-1/2 font-bold text-blue-400 text-lg uppercase"
                                            x-text="selectedSatuan"></span>
                                    </div>
                                    <template x-if="jumlah > maxStok">
                                        <div
                                            class="flex items-center gap-2 text-red-500 bg-red-50 p-3 rounded-xl border border-red-100 mt-2">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-xs font-bold italic">Jumlah melebihi total stok yang
                                                tersedia!</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <button type="submit" :disabled="jumlah > maxStok || jumlah <= 0 || !selectedInventoryId"
                                class="btn-submit w-full mt-10 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white py-5 rounded-3xl font-black uppercase tracking-widest shadow-xl shadow-blue-200 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="btn-text">Konfirmasi Keluar FIFO</span>
                                <svg class="btn-spinner hidden animate-spin ml-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout.beranda.app>
