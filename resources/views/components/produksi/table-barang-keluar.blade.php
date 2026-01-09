@props(['items', 'totalBiaya'])

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
    <div class="px-8 py-5 bg-blue-50 border-b border-blue-100 flex justify-between items-center shrink-0">
        <h2 class="text-xs font-black text-blue-700 uppercase tracking-widest italic">Pengeluaran Stok (FIFO)</h2>
        <span
            class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-blue-600 border border-blue-100 uppercase tracking-tighter shadow-sm">KELUAR</span>
    </div>

    <div class="flex-grow overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-md">
                <tr>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Item & Batch</th>
                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Tujuan / Proses
                    </th>
                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Qty
                    </th>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">
                        Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($items as $bk)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-8 py-4">
                            <p class="font-bold text-gray-800 leading-tight">
                                {{ $bk->DetailInventory->Inventory->Barang->nama_barang }}</p>
                            <p class="text-[9px] text-blue-500 font-black uppercase mt-1">Batch:
                                {{ $bk->DetailInventory->nomor_batch ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if ($bk->jenis_keluar === 'PRODUKSI')
                                <span
                                    class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-700 text-[9px] font-black uppercase">PRODUKSI</span>
                                <p class="text-[10px] font-bold text-gray-600 mt-1 leading-none">
                                    {{ $bk->Proses->nama_proses ?? '-' }}</p>
                            @else
                                <span
                                    class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase">{{ $bk->jenis_keluar }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-gray-800">{{ number_format($bk->jumlah_keluar, 2) }}</span>
                        </td>
                        <td class="px-8 py-4 text-right font-black text-blue-600">Rp
                            {{ number_format($bk->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400 italic">Belum ada pengeluaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-gradient-to-r from-blue-50 to-white border-t-2 border-blue-100 px-8 py-4 shrink-0">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-black text-blue-800 uppercase tracking-widest leading-none">Total Biaya Keluar
            </p>
            <div class="flex flex-col items-end gap-2">
                <p class="text-2xl font-black text-blue-600 leading-none">Rp
                    {{ number_format($totalBiaya, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    <div class="flex justify-end">
        {{ $items->appends(request()->except($items->getPageName()))->links('vendor.pagination.custom') }}
    </div>
</div>
