@props(['data'])

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{
    expandedGroup: null,
    editOpen: false,
    editData: { id: '', nama: '', batch: '', expired: '', qty: '', satuan: '', hpp: '' }
}">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Transaksi
                        Per Tanggal</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                        Total Item</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total
                        Berat (Kg)</th>
                    <th class="w-10 px-6 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $tanggal => $perBarang)
                    {{-- HEADER TANGGAL DENGAN JARAK (SPACING) --}}
                    <tr class="bg-gray-50/30">
                        <td colspan="4" class="px-6 py-4 border-t-8 border-white">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-xs font-black text-gray-600 uppercase tracking-widest italic">
                                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    @foreach ($perBarang as $idBarang => $items)
                        @php $firstItem = $items->first(); @endphp
                        <tr class="group hover:bg-blue-50/30 transition-colors cursor-pointer"
                            @click="expandedGroup === '{{ $tanggal }}-{{ $idBarang }}' ? expandedGroup = null : expandedGroup = '{{ $tanggal }}-{{ $idBarang }}'">
                            <td class="px-6 py-5">
                                <div class="flex flex-col ml-4">
                                    <span
                                        class="font-black text-gray-800 uppercase tracking-tight">{{ $firstItem->Inventory->Barang->nama_barang }}</span>
                                    <span class="text-[10px] font-bold text-blue-500 tracking-widest uppercase">KODE:
                                        {{ $firstItem->Inventory->Barang->kode }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center font-black text-gray-900">
                                {{ number_format($items->sum('stok')) }}
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase">{{ $firstItem->Inventory->Barang->satuan }}</span>
                            </td>
                            <td class="px-4 py-5 text-right font-black text-blue-600">
                                {{ number_format($items->sum(fn($i) => $i->stok * ($i->Inventory->Barang->nilai_konversi ?? 1)), 2) }}
                                Kg
                            </td>
                            <td class="px-6 py-5">
                                <svg class="w-4 h-4 text-gray-300 transition-transform duration-300"
                                    :class="expandedGroup === '{{ $tanggal }}-{{ $idBarang }}' ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </td>
                        </tr>

                        <tr x-show="expandedGroup === '{{ $tanggal }}-{{ $idBarang }}'" x-collapse x-cloak
                            class="bg-white">
                            <td colspan="4" class="px-12 py-4">
                                <div class="grid grid-cols-1 gap-2 border-l-2 border-blue-100 pl-4">
                                    @foreach ($items as $item)
                                        <div
                                            class="flex items-center justify-between bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                                            <div class="flex gap-8">
                                                <div>
                                                    <p class="text-[9px] font-bold text-gray-400 uppercase">No. Batch
                                                    </p>
                                                    <p class="font-mono text-xs font-bold text-gray-700">
                                                        {{ $item->nomor_batch ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[9px] font-bold text-gray-400 uppercase">Expired</p>
                                                    <p
                                                        class="text-xs font-bold {{ $item->tanggal_exp && \Carbon\Carbon::parse($item->tanggal_exp)->isPast() ? 'text-red-500' : 'text-gray-700' }}">
                                                        {{ $item->tanggal_exp ? \Carbon\Carbon::parse($item->tanggal_exp)->format('d/m/y') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="text-right">
                                                    <span
                                                        class="text-sm font-black text-gray-800">{{ number_format($item->jumlah_diterima) }}</span>
                                                    <span
                                                        class="text-[10px] font-bold text-gray-400 uppercase ml-1">{{ $item->Inventory->Barang->satuan }}</span>
                                                </div>
                                                @if ($item->stok == $item->jumlah_diterima - $item->jumlah_rusak)
                                                    @can('barang-masuk.edit-produksi')
                                                        <a href="{{ route('barang-masuk.edit-produksi', $item->id) }}"
                                                            class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </a>
                                                    @endcan

                                                    @can('barang-masuk.delete')
                                                        <form action="{{ route('barang-masuk.destroy', $item->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Hapus data produksi ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="p-2 text-gray-400 hover:text-red-600">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endcan
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
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4"
                            class="py-20 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Data
                            tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL EDIT PRODUKSI --}}
    <template x-teleport="body">
        <div x-show="editOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            <div x-show="editOpen" x-transition.opacity @click="editOpen = false"
                class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div x-show="editOpen" x-transition.scale.95
                class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-8">
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight mb-6"
                    x-text="'Edit ' + editData.nama"></h3>
                <form :action="'{{ url('barang-masuk') }}/' + editData.id" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah
                                Stok</label>
                            <input type="number" name="stok" x-model="editData.qty"
                                class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold focus:ring-blue-500 border">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Harga /
                                HPP</label>
                            <input type="number" name="harga" x-model="editData.hpp"
                                class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold focus:ring-blue-500 border">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No.
                                Batch</label>
                            <input type="text" name="nomor_batch" x-model="editData.batch"
                                class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold focus:ring-blue-500 border uppercase">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-8">
                        <button type="button" @click="editOpen = false"
                            class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Batal</button>
                        <button type="submit"
                            class="px-8 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-200">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
