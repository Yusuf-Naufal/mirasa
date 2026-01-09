@props(['data'])

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{
    expandedGroup: null,
    editModalOpen: false,
    editData: {
        id: '',
        jumlah_diterima: 0,
        jumlah_rusak: 0,
        stok: 0,
        harga: 0,
        tgl_masuk: '',
        lokasi: ''
    },
    openEdit(item) {
        this.editData = item;
        this.editModalOpen = true;
    }
}">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Bahan Penolong
                        Per Tanggal</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                        Total Item</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total
                        Qty</th>
                    <th class="w-10 px-6 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $tanggal => $perBarang)
                    {{-- HEADER TANGGAL DENGAN JARAK --}}
                    <tr class="bg-gray-50/30">
                        <td colspan="5" class="px-6 py-4 border-t-8 border-white">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-black text-gray-600 uppercase tracking-widest italic">
                                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    @foreach ($perBarang as $idBarang => $items)
                        @php $firstItem = $items->first(); @endphp
                        <tr class="group hover:bg-emerald-50/30 transition-colors cursor-pointer"
                            @click="expandedGroup === '{{ $tanggal }}-{{ $idBarang }}' ? expandedGroup = null : expandedGroup = '{{ $tanggal }}-{{ $idBarang }}'">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-black text-xs">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800">
                                            {{ $firstItem->Inventory->Barang->nama_barang }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">KODE:
                                            {{ $firstItem->Inventory->Barang->kode }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center text-xs font-black text-gray-600">{{ $items->count() }}
                                Baris</td>
                            <td class="px-4 py-5 text-right">
                                <span
                                    class="text-sm font-black text-emerald-600">{{ number_format($items->sum('stok')) }}</span>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase ml-1">{{ $firstItem->Inventory->Barang->satuan }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <svg class="w-5 h-5 text-gray-300 transition-transform duration-300"
                                    :class="expandedGroup === '{{ $tanggal }}-{{ $idBarang }}' ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </td>
                        </tr>

                        {{-- DETAIL TRANSAKSI --}}
                        <tr x-show="expandedGroup === '{{ $tanggal }}-{{ $idBarang }}'" x-transition x-cloak>
                            <td colspan="5" class="px-12 py-4 bg-gray-50/50">
                                <div class="space-y-3 border-l-2 border-emerald-100 pl-6">
                                    @foreach ($items as $i)
                                        <div
                                            class="flex items-center justify-between p-3 bg-white rounded-2xl border border-gray-100 hover:shadow-sm transition-all">
                                            <div class="flex gap-10">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic">Supplier
                                                        / Asal</span>
                                                    <span
                                                        class="text-xs font-black text-gray-700">{{ $i->Supplier->nama_supplier ?? 'Internal' }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic">Lokasi</span>
                                                    <span
                                                        class="text-xs font-black text-gray-700">{{ $i->tempat_penyimpanan ?? '-' }}</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-6">
                                                <div class="text-right">
                                                    <span
                                                        class="text-sm font-black text-gray-800">{{ number_format($i->stok) }}</span>
                                                    <span
                                                        class="text-[10px] font-bold text-gray-400 uppercase ml-1">{{ $i->Inventory->Barang->satuan }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    {{-- Tombol Edit --}}
                                                    @if ($i->stok == $i->jumlah_diterima - $i->jumlah_rusak)
                                                        <a href="{{ route('barang-masuk.edit-bp', $i->id) }}"
                                                            class="inline-flex items-center justify-center w-9 h-9 text-slate-400 hover:text-blue-600 hover:bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-200 transition-all duration-200 bg-slate-50/50">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>

                                                        <form action="{{ route('barang-masuk.destroy', $i->id) }}"
                                                            method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex items-center justify-center w-9 h-9 text-slate-400 hover:text-red-600 hover:bg-white rounded-xl shadow-sm border border-slate-200 hover:border-red-200 transition-all duration-200 bg-slate-50/50">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div class="inline-flex items-center justify-center w-9 h-9 text-slate-300 bg-slate-100/50 rounded-xl border border-slate-100 cursor-not-allowed"
                                                            title="Data tidak dapat diedit karena stok sudah digunakan">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                            </svg>
                                                        </div>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5"
                            class="py-20 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Data
                            tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
