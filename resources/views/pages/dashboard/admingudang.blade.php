<x-layout.user.app title="Admin Gudang Dashboard">
    <div class="space-y-6">

        {{-- GREETING SECTION --}}
        <div class="relative overflow-hidden bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Gudang
                        {{ auth()->user()->perusahaan->nama_perusahaan ?? 'Mirasa' }} 📦</h1>
                    <p class="text-gray-500 max-w-md">Pantau ketersediaan bahan baku dan alur distribusi barang hari ini.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('produksi.index') }}"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all">Riwayat
                        Produksi</a>
                    <a href="{{ route('inventory.index') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">Gudang</a>
                </div>
            </div>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div
                        class="p-4 {{ $stats['stok_kritis'] > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }} rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Stok
                            Kritis</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ $stats['stok_kritis'] }} <span
                                class="text-xs font-medium text-gray-400">Item</span></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                            Total Bahan Baku</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ $stats['total_bahan_baku'] }} <span
                                class="text-xs font-medium text-gray-400">Jenis</span></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-purple-50 rounded-2xl text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                            Barang Produksi</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ $stats['total_barang_produksi'] }} <span
                                class="text-xs font-medium text-gray-400">Jenis</span></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-yellow-50 rounded-2xl text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="m4.036 2.488l6.611 2.833L8 6.455L1.427 3.638c.148-.151.329-.273.535-.352zm1.338-.514l1.55-.596a3 3 0 0 1 2.153 0l4.962 1.908c.205.08.386.2.534.352l-2.656 1.138zm9.62 2.572L8.5 7.329v7.45q.295-.05.577-.158l4.962-1.909a1.5 1.5 0 0 0 .961-1.4V4.686q0-.07-.007-.14M7.5 14.779v-7.45L1.007 4.546a2 2 0 0 0-.007.14v6.626a1.5 1.5 0 0 0 .962 1.4l4.961 1.909q.282.108.577.158" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                            Total Barang Penolong</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ $stats['total_barang_penolong'] }} <span
                                class="text-xs font-medium text-gray-400">Jenis</span></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- RECENT MOVEMENTS TABLE --}}
            <div
                class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest italic">Aktivitas Stok Terbaru
                    </h3>
                </div>
                <div class="flex-grow">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-4">Barang</th>
                                <th class="px-6 py-4 text-center">Batch</th>
                                <th class="px-8 py-4 text-right">Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recent_stock_movements as $move)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <p class="text-sm font-bold text-gray-800 leading-tight">
                                            {{ $move->Inventory->Barang->nama_barang }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">
                                            {{ $move->tanggal_masuk ?? 'Baru' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 bg-gray-100 rounded-lg text-[10px] font-black text-gray-600 uppercase">{{ $move->nomor_batch ?? '-' }}</span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <p class="text-sm font-black text-emerald-600">
                                            +{{ number_format($move->jumlah_diterima, 0) }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase">
                                            {{ $move->Inventory->Barang->satuan }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center text-gray-400 italic text-sm">Belum
                                        ada mutasi stok hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- NOTIFICATIONS / ALERT --}}
            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                    <h4 class="font-black uppercase text-xs tracking-[0.2em] mb-6 text-gray-400 leading-none">Status
                        Gudang</h4>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-1.5 h-12 bg-emerald-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Sistem Normal</p>
                                <p class="text-xs text-gray-500">Semua sinkronisasi data dengan pusat berjalan lancar.
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-1.5 h-12 {{ $stats['stok_kritis'] > 0 ? 'bg-red-500' : 'bg-gray-200' }} rounded-full">
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Cek Ketersediaan</p>
                                <p class="text-xs text-gray-500">
                                    {{ $stats['stok_kritis'] > 0 ? 'Beberapa bahan baku berada di bawah batas minimum.' : 'Stok bahan baku terpantau aman.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-8 text-white shadow-xl">
                    <h4 class="font-black uppercase text-[10px] tracking-[0.2em] mb-4 opacity-50">Bantuan Gudang</h4>
                    <p class="text-lg font-bold mb-4">Butuh bantuan teknis?</p>
                    <button
                        class="w-full py-3 bg-white/10 hover:bg-white/20 transition-all rounded-2xl font-black text-xs uppercase tracking-widest border border-white/10">Hubungi
                        IT Support</button>
                </div>
            </div>
        </div>

    </div>
</x-layout.user.app>
