@props(['items', 'totalNilai'])

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
    <div class="px-8 py-5 bg-purple-50 border-b border-purple-100 flex justify-between items-center shrink-0">
        <h2 class="text-xs font-black text-purple-700 uppercase tracking-widest italic">Rincian Barang Penolong</h2>
        <span class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-purple-600 border border-purple-100 uppercase tracking-tighter shadow-sm">BP</span>
    </div>

    <div class="flex-grow overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-md">
                <tr>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Item</th>
                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Supplier</th>
                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Nilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($items as $bp)
                    <tr class="hover:bg-purple-50/30 transition-colors">
                        <td class="px-8 py-4 font-bold text-gray-800 leading-tight">{{ $bp->Inventory->Barang->nama_barang }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-700 text-[9px] font-black uppercase">
                                {{ $bp->supplier->nama_supplier ?? 'Tanpa Supplier' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-gray-800">{{ number_format($bp->jumlah_diterima, 2) }}</span>
                            <span class="text-[9px] font-black text-gray-400 uppercase ml-0.5">{{ $bp->Inventory->Barang->satuan }}</span>
                        </td>
                        <td class="px-8 py-4 text-right font-black text-purple-600">Rp {{ number_format($bp->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-8 py-20 text-center text-gray-400 italic">Data Barang Penolong Kosong.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-gradient-to-r from-purple-50 to-white border-t-2 border-purple-100 px-8 py-4 shrink-0">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-black text-purple-800 uppercase tracking-widest leading-none">Total Nilai Penolong</p>
            <div class="flex flex-col items-end gap-2">
                <p class="text-2xl font-black text-purple-600 leading-none">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
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