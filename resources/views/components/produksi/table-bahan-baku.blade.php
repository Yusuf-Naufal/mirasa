@props(['items', 'totalNilai', 'produksi', 'bahanBakuMasuk'])

<div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
    <div class="px-8 py-5 bg-emerald-50 border-b border-emerald-100 flex justify-between items-center shrink-0">
        <h2 class="text-xs font-black text-emerald-700 uppercase tracking-widest italic">Rincian Bahan Baku</h2>
        <span
            class="text-[9px] font-black bg-white px-3 py-1 rounded-lg text-emerald-600 border border-emerald-100 uppercase tracking-tighter shadow-sm">BB</span>
    </div>

    <div class="flex-grow overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 sticky top-0 z-10 backdrop-blur-md">
                <tr>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                    <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Qty
                    </th>
                    <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">
                        Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($items as $bb)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="px-8 py-4 font-bold text-gray-800 leading-tight">
                            {{-- Akses Barang: BarangKeluar -> DetailInventory -> Inventory -> Barang --}}
                            {{ $bb->DetailInventory->Inventory->Barang->nama_barang ?? 'No name' }}

                            <p class="text-[9px] text-gray-400 font-medium">
                                {{-- Akses Supplier: BarangKeluar -> DetailInventory -> Supplier --}}
                                Supplier: {{ $bb->DetailInventory->Supplier->nama_supplier ?? 'Internal' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-black border border-emerald-100">
                                {{-- Gunakan jumlah_keluar (dari tabel barang_keluar) bukan jumlah_diterima --}}
                                {{ number_format($bb->jumlah_keluar, 0) }}
                                {{ $bb->DetailInventory->Inventory->Barang->satuan ?? '' }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-right font-black text-gray-800">
                            {{-- total_harga diambil langsung dari tabel barang_keluar --}}
                            Rp {{ number_format($bb->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">Data Bahan Baku Kosong.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-gradient-to-r from-emerald-50 to-white border-t-2 border-emerald-100 px-8 py-4 shrink-0">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest leading-none">Total Akumulasi BB
            </p>
            <div class="flex flex-col items-end gap-2">
                <p class="text-2xl font-black text-emerald-600 leading-none">Rp
                    {{ number_format($totalNilai, 0, ',', '.') }}</p>
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

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 pb-8">
    @foreach ($bahanBakuMasuk as $rencana)
        @php
            $detailInv =
                $items->where('DetailInventory.Inventory.id_barang', $rencana->id_barang)->first()->DetailInventory ??
                null;

            $stokMasuk = $detailInv ? $detailInv->jumlah_diterima : 0;

            // 2. Hitung total Keluar (Realisasi) untuk barang ini
            $stokKeluar = $items
                ->where('DetailInventory.Inventory.id_barang', $rencana->id_barang)
                ->sum('jumlah_keluar');

            // 3. Hitung Sisa/Selisih
            $sisaStok = $stokMasuk - $stokKeluar;
        @endphp

        <div class="p-4 rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">
                    {{ $rencana->barang->nama_barang ?? 'Produk' }}
                </span>
                <span
                    class="text-[9px] font-black px-2 py-0.5 rounded-full {{ $sisaStok < 0 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                    Sisa: {{ number_format($sisaStok) }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-2">
                <div class="flex-1">
                    <p class="text-[9px] text-gray-400 uppercase font-bold">Total Masuk</p>
                    <p class="text-sm font-black text-gray-700">{{ number_format($stokMasuk) }} <span
                            class="text-[10px] font-normal italic">item</span></p>
                </div>

                <div class="text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </div>

                <div class="flex-1 border-l pl-4">
                    <p class="text-[9px] text-emerald-500 uppercase font-bold">Total Keluar</p>
                    <p class="text-sm font-black text-emerald-600">{{ number_format($stokKeluar) }} <span
                            class="text-[10px] font-normal italic text-emerald-400">item</span></p>
                </div>
            </div>

            <div class="mt-3 w-full bg-gray-200 rounded-full h-1">
                @php
                    $percent = $stokMasuk > 0 ? ($stokKeluar / $stokMasuk) * 100 : 0;
                @endphp
                <div class="h-1 rounded-full {{ $percent > 100 ? 'bg-red-500' : 'bg-emerald-500' }}"
                    style="width: {{ min($percent, 100) }}%"></div>
            </div>
        </div>
    @endforeach
</div>
