<x-layout.beranda.app>
    <div class="min-h-screen bg-gray-50 md:px-10 py-8">
        <div class="mx-auto flex flex-col pt-12">

            {{-- HEADER --}}
            <div class="mb-8">
                <a href="{{ route('produksi.index') }}" class="text-blue-600 hover:underline text-sm font-bold inline-flex items-center gap-2 mb-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Detail Aktivitas Produksi</h1>
                <p class="text-sm text-gray-500 font-medium uppercase tracking-widest">{{ \Carbon\Carbon::parse($produksi->tanggal_produksi)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- KIRI: RINGKASAN DATA --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                    
                    {{-- Card 1: Pengeluaran (FIFO) --}}
                    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Ringkasan Pengeluaran (HPP)</p>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-[9px] font-black text-blue-400 uppercase">Total Biaya Riil</span>
                                <h3 class="text-3xl font-black text-blue-600 leading-none mt-1">
                                    Rp {{ number_format($produksi->barangKeluar->sum('total_harga'), 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-xs font-bold text-gray-700">{{ $produksi->barangKeluar->count() }} Transaksi Keluar</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Bahan Baku Masuk --}}
                    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-12 -mt-12 transition-transform group-hover:scale-110"></div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Ringkasan Stok Masuk</p>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-[9px] font-black text-emerald-400 uppercase">Total Nilai Aset</span>
                                <h3 class="text-3xl font-black text-emerald-600 leading-none mt-1">
                                    Rp {{ number_format($produksi->bahanBaku->sum('total_harga'), 0, ',', '.') }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-bold text-gray-700">{{ $produksi->bahanBaku->count() }} Bahan Baku Diterima</span>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div class="bg-gray-900 p-6 rounded-[2rem] text-white shadow-xl">
                        <div class="flex items-center gap-3 mb-3 text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Catatan Sistem</span>
                        </div>
                        <p class="text-[10px] text-gray-400 leading-relaxed font-medium uppercase tracking-tighter">
                            Data ini disinkronisasi secara otomatis melalui sistem inventory FIFO. Setiap perubahan jumlah akan mempengaruhi nilai HPP secara langsung.
                        </p>
                    </div>
                </div>

                {{-- KANAN: RINCIAN DETAIL --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- 3. DAFTAR BAHAN BAKU MASUK --}}
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-8 py-5 bg-emerald-50 border-b border-emerald-100 flex justify-between items-center">
                            <h2 class="text-xs font-black text-emerald-700 uppercase tracking-widest italic">Rincian Penerimaan Stok</h2>
                            <span class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-emerald-600 border border-emerald-100 uppercase tracking-tighter shadow-sm">Bahan Baku</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Supplier</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                                        <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Nilai Masuk</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($produksi->bahanBaku as $bb)
                                        <tr class="text-sm hover:bg-emerald-50/30 transition-colors">
                                            <td class="px-8 py-4 font-bold text-gray-800 leading-tight">{{ $bb->barang->nama_barang }}</td>
                                            <td class="px-6 py-4 text-gray-500 uppercase text-[10px] font-bold tracking-tighter">{{ $bb->supplier->nama_supplier ?? 'Tanpa Supplier' }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-black shadow-sm border border-emerald-100">
                                                    {{ number_format($bb->jumlah_diterima, 0) }} {{ $bb->barang->satuan }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-4 text-right font-black text-gray-800 tracking-tight">Rp {{ number_format($bb->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-8 py-12 text-center text-gray-400 italic text-xs">Belum ada rincian bahan baku masuk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 4. DAFTAR BARANG KELUAR --}}
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-8 py-5 bg-blue-50 border-b border-blue-100 flex justify-between items-center">
                            <h2 class="text-xs font-black text-blue-700 uppercase tracking-widest italic">Rincian Pengeluaran Stok</h2>
                            <span class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-blue-600 border border-blue-100 uppercase tracking-tighter shadow-sm">Keluar (FIFO)</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Item & Batch</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Tujuan / Proses</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                                        <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    @forelse($produksi->barangKeluar as $bk)
                                        <tr class="hover:bg-blue-50/30 transition-colors">
                                            <td class="px-8 py-4">
                                                <p class="font-bold text-gray-800 leading-tight">{{ $bk->DetailInventory->Inventory->Barang->nama_barang }}</p>
                                                <p class="text-[9px] text-blue-400 font-black uppercase tracking-tighter mt-1">Batch #{{ $bk->id_detail_inventory }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col gap-1">
                                                    @if($bk->jenis_keluar === 'PRODUKSI')
                                                        <span class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-700 text-[9px] font-black w-fit">PRODUKSI</span>
                                                        <span class="text-[10px] font-bold text-gray-600 tracking-tighter">{{ $bk->Proses->nama_proses ?? '-' }}</span>
                                                    @elseif($bk->jenis_keluar === 'PENJUALAN')
                                                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[9px] font-black w-fit">PENJUALAN</span>
                                                        <span class="text-[10px] font-bold text-gray-600 tracking-tighter">{{ $bk->Costumer->nama_costumer ?? 'Umum' }}</span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[9px] font-black w-fit">TRANSFER</span>
                                                        <span class="text-[10px] font-bold text-gray-600 tracking-tighter">{{ $bk->Perusahaan->nama_perusahaan ?? 'Cabang' }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="font-black text-gray-800">{{ number_format($bk->jumlah_keluar, 2) }}</span>
                                                <span class="text-[9px] font-black text-gray-400 uppercase ml-0.5">{{ $bk->DetailInventory->Inventory->Barang->satuan }}</span>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <p class="font-black text-blue-600 tracking-tight">Rp {{ number_format($bk->total_harga, 0, ',', '.') }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">@Rp {{ number_format($bk->harga, 0) }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-8 py-12 text-center text-gray-400 italic text-xs">Belum ada aktivitas barang keluar.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.beranda.app>