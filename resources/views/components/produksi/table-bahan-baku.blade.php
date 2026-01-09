@props(['items', 'totalNilai'])

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
    <div class="px-8 py-5 bg-emerald-50 border-b border-emerald-100 flex justify-between items-center shrink-0">
        <h2 class="text-xs font-black text-emerald-700 uppercase tracking-widest italic">Rincian Bahan Baku</h2>
        <span class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-emerald-600 border border-emerald-100 uppercase tracking-tighter shadow-sm">BB</span>
    </div>

    <div class="flex-grow overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-md">
                <tr>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($items as $bb)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="px-8 py-4 font-bold text-gray-800 leading-tight">
                            {{ $bb->inventory->barang->nama_barang }}
                            <p class="text-[9px] text-gray-400 font-medium">Supplier: {{ $bb->supplier->nama_supplier ?? 'Internal' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-black border border-emerald-100">
                                {{ number_format($bb->jumlah_diterima, 0) }} {{ $bb->Inventory->barang->satuan }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right font-black text-gray-800">Rp {{ number_format($bb->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">Data Bahan Baku Kosong.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-gradient-to-r from-emerald-50 to-white border-t-2 border-emerald-100 px-8 py-4 shrink-0">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest leading-none">Total Akumulasi BB</p>
            <div class="flex flex-col items-end gap-2">
                <p class="text-2xl font-black text-emerald-600 leading-none">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
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