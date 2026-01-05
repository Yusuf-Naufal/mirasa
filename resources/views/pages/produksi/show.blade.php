<x-layout.beranda.app>
    <div class="min-h-screen bg-gray-50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- HEADER --}}
            <div class="mb-8">
                <a href="{{ route('produksi.index') }}"
                    class="text-blue-600 hover:underline text-sm font-bold inline-flex items-center gap-2 mb-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Detail Aktivitas Produksi</h1>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-widest">
                    {{ \Carbon\Carbon::parse($produksi->tanggal_produksi)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- KIRI: RINGKASAN DATA --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">

                    {{-- Card 1: Bahan Baku Masuk --}}
                    <div
                        class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110">
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Ringkasan Bahan
                            Baku
                            Masuk</p>

                        <div class="space-y-4">
                            <div>
                                <span class="text-[9px] font-black text-emerald-400 uppercase">Total Nilai</span>
                                <h3 class="text-3xl font-black text-emerald-600 leading-none mt-1">
                                    Rp {{ number_format($produksi->DetailInventory->sum('total_harga'), 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-bold text-gray-700">{{ $produksi->DetailInventory->count() }}
                                    Bahan
                                    Baku Diterima</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Pengeluaran (FIFO) --}}
                    <div
                        class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110">
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Ringkasan
                            Pengeluaran (HPP)</p>

                        <div class="space-y-4">
                            <div>
                                <span class="text-[9px] font-black text-blue-400 uppercase">Total Biaya</span>
                                <h3 class="text-3xl font-black text-blue-600 leading-none mt-1">
                                    Rp {{ number_format($produksi->barangKeluar->sum('total_harga'), 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-xs font-bold text-gray-700">{{ $produksi->barangKeluar->count() }}
                                    Transaksi Keluar</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Daftar Hasil Produksi --}}
                    <div x-data="{
                        open: false,
                        detail: { id: '', kupas: 0, a: 0, s: 0, j: 0, nama: '' },
                        initEdit(item, namaBarang) {
                            this.detail = {
                                id: item.id,
                                kupas: item.total_kupas,
                                a: item.total_a,
                                s: item.total_s,
                                j: item.total_j,
                                nama: namaBarang
                            };
                            this.open = true;
                        }
                    }">
                        <div
                            class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110">
                            </div>

                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
                                Rincian Hasil Produksi
                            </p>

                            <div class="space-y-6">
                                {{-- Menggunakan @forelse untuk menangani data kosong --}}
                                @forelse ($produksi->detailProduksi as $detail)
                                    <div class="relative p-4 rounded-2xl bg-gray-50/50 border border-gray-100">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-800">
                                                    {{ $detail->barang->nama_barang ?? 'Produk Hasil' }}
                                                    ({{ $detail->barang->satuan ?? 'XX' }})
                                                </h4>
                                            </div>
                                            <button type="button"
                                                @click="initEdit({{ $detail }}, '{{ $detail->barang->nama_barang ?? 'Produk' }}')"
                                                class="bg-white shadow-sm text-purple-600 hover:bg-purple-600 hover:text-white p-2 rounded-xl transition-all border border-purple-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                                            <div>
                                                <p class="text-[8px] font-black text-gray-400 uppercase">Total Bersih
                                                </p>
                                                <p class="text-sm font-bold text-gray-700">
                                                    {{ number_format($detail->total_kupas, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[8px] font-black text-gray-400 uppercase">Grade A</p>
                                                <p class="text-sm font-bold text-gray-700">
                                                    {{ number_format($detail->total_a, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[8px] font-black text-gray-400 uppercase">Second Grade
                                                </p>
                                                <p class="text-sm font-bold text-gray-700">
                                                    {{ number_format($detail->total_s, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[8px] font-black text-gray-400 uppercase">Grade Jumbo</p>
                                                <p class="text-sm font-bold text-gray-700">
                                                    {{ number_format($detail->total_j, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    {{-- Tampilan Visual saat Data Kosong --}}
                                    <div
                                        class="flex flex-col items-center justify-center py-12 px-4 rounded-3xl border-2 border-dashed border-gray-100 bg-gray-50/30">
                                        <div class="p-3 bg-purple-50 rounded-2xl mb-4">
                                            <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <h5 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Data
                                            Kosong</h5>
                                        <p class="text-[10px] text-gray-400 font-medium text-center">Belum ada rincian
                                            hasil produksi yang dicatat untuk periode ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Modal (Alpine.js Controlled) --}}
                        <template x-teleport="body">
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-[99] flex items-center justify-center p-4">

                                {{-- Overlay --}}
                                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false">
                                </div>

                                {{-- Modal Content --}}
                                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="relative bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl">

                                    <div class="mb-6">
                                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Update
                                            Output</h3>
                                        <p x-text="detail.nama" class="text-xs font-bold text-purple-500 uppercase"></p>
                                    </div>

                                    <form :action="'/produksi/detail/' + detail.id" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="space-y-1">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase ml-2">Total
                                                    Kupas</label>
                                                <input type="number" name="total_kupas" x-model="detail.kupas"
                                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-purple-500 focus:bg-white focus:ring-0 transition-all font-bold">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase ml-2">Grade
                                                    A</label>
                                                <input type="number" name="total_a" x-model="detail.a"
                                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-purple-500 focus:bg-white focus:ring-0 transition-all font-bold">
                                            </div>
                                            <div class="space-y-1">
                                                <label
                                                    class="text-[10px] font-bold text-gray-500 uppercase ml-2">Second
                                                    Grade</label>
                                                <input type="number" name="total_s" x-model="detail.s"
                                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-purple-500 focus:bg-white focus:ring-0 transition-all font-bold">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-[10px] font-bold text-gray-500 uppercase ml-2">Grade
                                                    Jumbo</label>
                                                <input type="number" name="total_j" x-model="detail.j"
                                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-purple-500 focus:bg-white focus:ring-0 transition-all font-bold">
                                            </div>
                                        </div>

                                        <div class="flex gap-3 pt-6">
                                            <button type="button" @click="open = false"
                                                class="flex-1 px-6 py-4 rounded-2xl font-bold text-gray-400 hover:bg-gray-100 transition-all">Batal</button>
                                            <button type="submit"
                                                class="flex-1 px-6 py-4 rounded-2xl bg-purple-600 font-bold text-white shadow-xl shadow-purple-200 hover:bg-purple-700 transition-all">Update
                                                Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div class="bg-gray-900 p-6 rounded-[2rem] text-white shadow-xl">
                        <div class="flex items-center gap-3 mb-3 text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Catatan Sistem</span>
                        </div>
                        <p class="text-[10px] text-gray-400 leading-relaxed font-medium uppercase tracking-tighter">
                            Data ini disinkronisasi secara otomatis melalui sistem inventory FIFO. Setiap perubahan
                            jumlah akan mempengaruhi nilai HPP secara langsung.
                        </p>
                    </div>
                </div>

                {{-- KANAN: RINCIAN DETAIL --}}
                <div class="lg:col-span-8 space-y-8">

                    {{-- 3. DAFTAR BAHAN BAKU MASUK --}}
                    <div
                        class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                        <div
                            class="px-8 py-5 bg-emerald-50 border-b border-emerald-100 flex justify-between items-center shrink-0">
                            <h2 class="text-xs font-black text-emerald-700 uppercase tracking-widest italic">Rincian
                                Penerimaan Bahan Baku</h2>
                            <span
                                class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-emerald-600 border border-emerald-100 uppercase tracking-tighter shadow-sm">Bahan
                                Baku</span>
                        </div>

                        {{-- Container Scroll pada Body --}}
                        <div
                            class="overflow-x-auto overflow-y-auto max-h-[400px] scrollbar-thin scrollbar-thumb-emerald-100">
                            <table class="w-full text-left border-collapse">
                                {{-- Header Sticky --}}
                                <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-md">
                                    <tr>
                                        <th
                                            class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                            Barang</th>
                                        <th
                                            class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                            Supplier</th>
                                        <th
                                            class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">
                                            Qty</th>
                                        <th
                                            class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">
                                            Nilai Masuk</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($produksi->DetailInventory as $bb)
                                        <tr class="text-sm hover:bg-emerald-50/30 transition-colors">
                                            <td class="px-8 py-4 font-bold text-gray-800 leading-tight">
                                                {{ $bb->inventory->barang->nama_barang }}</td>
                                            <td
                                                class="px-6 py-4 text-gray-500 uppercase text-[10px] font-bold tracking-tighter">
                                                {{ $bb->supplier->nama_supplier ?? 'Tanpa Supplier' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-black shadow-sm border border-emerald-100">
                                                    {{ number_format($bb->jumlah_diterima, 0) }}
                                                    {{ $bb->Inventory->barang->satuan }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-4 text-right font-black text-gray-800 tracking-tight">
                                                Rp {{ number_format($bb->total_harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-8 py-12 text-center text-gray-400 italic text-xs">Belum ada
                                                rincian bahan baku masuk.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- FOOTER BAHAN BAKU --}}
                        <div
                            class="bg-gradient-to-r from-emerald-50 to-white border-t-2 border-emerald-100 px-8 py-6 shrink-0">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] font-black text-emerald-800 uppercase tracking-widest leading-none">
                                            Total Akumulasi</p>
                                        <p class="text-xs text-emerald-600/70 font-bold mt-1">Rincian
                                            penerimaan stok</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-8">
                                    <div class="text-center sm:text-right">
                                        <p class="text-lg font-black text-gray-800">
                                            {{ number_format($produksi->DetailInventory->sum('jumlah_diterima'), 0) }}
                                        </p>
                                    </div>
                                    <div class="h-10 w-[1px] bg-emerald-100 hidden sm:block"></div>
                                    <div class="text-center sm:text-right">
                                        <p class="text-2xl font-black text-emerald-600 leading-none">
                                            {{ number_format($produksi->DetailInventory->sum('total_harga'), 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. DAFTAR BARANG KELUAR --}}
                    <div
                        class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                        <div
                            class="px-8 py-5 bg-blue-50 border-b border-blue-100 flex justify-between items-center shrink-0">
                            <h2 class="text-xs font-black text-blue-700 uppercase tracking-widest italic">Rincian
                                Pengeluaran Stok</h2>
                            <span
                                class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-blue-600 border border-blue-100 uppercase tracking-tighter shadow-sm">Keluar
                                (FIFO)</span>
                        </div>

                        <div
                            class="overflow-x-auto overflow-y-auto max-h-[400px] scrollbar-thin scrollbar-thumb-blue-100">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-md">
                                    <tr>
                                        <th
                                            class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                            Item & Batch</th>
                                        <th
                                            class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                            Tujuan / Proses</th>
                                        <th
                                            class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">
                                            Qty</th>
                                        <th
                                            class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    @forelse($produksi->barangKeluar as $bk)
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-8 py-4">
                                                <p class="font-bold text-gray-800 leading-tight">
                                                    {{ $bk->DetailInventory->Inventory->Barang->nama_barang }}</p>
                                                @if ($bk->jenis_keluar != 'PRODUKSI')
                                                    <p
                                                        class="text-[9px] text-blue-400 font-black uppercase tracking-tighter mt-1">
                                                        Batch : {{ $bk->DetailInventory->nomor_batch }}</p>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col gap-1">
                                                    @if ($bk->jenis_keluar === 'PRODUKSI')
                                                        <span
                                                            class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-700 text-[9px] font-black w-fit uppercase">PRODUKSI</span>
                                                        <span
                                                            class="text-[10px] font-bold text-gray-600 tracking-tighter">{{ $bk->Proses->nama_proses ?? '-' }}</span>
                                                    @else
                                                        <span
                                                            class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[9px] font-black w-fit uppercase">{{ $bk->jenis_keluar }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="font-black text-gray-800">{{ number_format($bk->jumlah_keluar, 2) }}</span>
                                                <span
                                                    class="text-[9px] font-black text-gray-400 uppercase ml-0.5">{{ $bk->DetailInventory->Inventory->Barang->satuan }}</span>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <p class="font-black text-blue-600 tracking-tight">Rp
                                                    {{ number_format($bk->total_harga, 0, ',', '.') }}</p>
                                                <p
                                                    class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                                    Rp {{ number_format($bk->harga, 0) }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-8 py-12 text-center text-gray-400 italic text-xs">Belum ada
                                                aktivitas barang keluar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- FOOTER BARANG KELUAR --}}
                        <div
                            class="bg-gradient-to-r from-blue-50 to-white border-t-2 border-blue-100 px-8 py-6 shrink-0">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] font-black text-blue-800 uppercase tracking-widest leading-none">
                                            Ringkasan</p>
                                        <p class="text-xs text-blue-600/70 font-bold mt-1">Total pengeluaran bahan</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-8">
                                    <div class="text-center sm:text-right">

                                        <p class="text-lg font-black text-gray-800">
                                            {{ number_format($produksi->barangKeluar->sum('jumlah_keluar'), 2) }}
                                        </p>
                                    </div>
                                    <div class="h-10 w-[1px] bg-blue-100 hidden sm:block"></div>
                                    <div class="text-center sm:text-right">
                                        <p class="text-2xl font-black text-blue-600 leading-none">
                                            {{ number_format($produksi->barangKeluar->sum('total_harga'), 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.beranda.app>
