@props(['data'])

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{
    selected: null,
    editModal: false,
    editData: { id: '', qty: '', originalQty: '', hpp: '', total: '', barang: '', satuan: '', tanggal: '', stok_batch: 0 }
}">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th
                        class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-10">
                        #</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang &
                        Informasi Gudang</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu Keluar
                    </th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                        Total Volume</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total
                        Nilai HPP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $groupKey => $items)
                    @php
                        $first = $items->first();
                        $totalQty = $items->sum('jumlah_keluar');
                        $totalHpp = $items->sum('total_harga');
                    @endphp

                    <tr class="hover:bg-blue-50/30 transition-all cursor-pointer group"
                        @click="selected !== '{{ $groupKey }}' ? selected = '{{ $groupKey }}' : selected = null">
                        <td class="px-6 py-4 text-center">
                            <svg class="w-4 h-4 text-gray-300 transition-transform"
                                :class="selected === '{{ $groupKey }}' ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span
                                    class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">
                                    {{ $first->DetailInventory->Inventory->Barang->nama_barang }}
                                </span>
                                <span class="text-[10px] text-blue-500 font-medium">Terdiri dari {{ $items->count() }}
                                    Batch pengambilan</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-blue-600 font-bold text-xs bg-blue-50 px-2 py-1 rounded-full">
                                {{ \Carbon\Carbon::parse($first->tanggal_keluar)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1">
                                <span
                                    class="text-sm font-black text-gray-800">{{ number_format($totalQty, 0, ',', '.') }}</span>
                                <span
                                    class="text-[9px] font-bold text-gray-400 uppercase">{{ $first->DetailInventory->Inventory->Barang->satuan }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-900 text-sm">Rp
                                {{ number_format($totalHpp, 0, ',', '.') }}</span>
                        </td>
                    </tr>

                    {{-- Baris Detail --}}
                    <tr x-show="selected === '{{ $groupKey }}'" x-cloak x-transition.opacity class="bg-gray-50/50">
                        <td colspan="5" class="px-6 py-4">
                            <div class="grid grid-cols-1 gap-2 pl-10 border-l-2 border-blue-200">
                                @foreach ($items as $detail)
                                    <div
                                        class="flex items-center justify-between bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:border-blue-200 transition-colors">
                                        <div class="flex flex-wrap items-center gap-6">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[10px] text-gray-400 uppercase font-bold tracking-tight">Gudang
                                                    / Lokasi</span>
                                                <span
                                                    class="text-xs text-gray-700 font-medium">{{ $detail->DetailInventory->tempat_penyimpanan }}</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[10px] text-gray-400 uppercase font-bold tracking-tight">Qty
                                                    Keluar</span>
                                                <span class="text-xs font-bold text-gray-800">
                                                    {{ number_format($detail->jumlah_keluar, 0, ',', '.') }}
                                                    <span
                                                        class="text-[10px] text-gray-400 font-normal">{{ $detail->DetailInventory->Inventory->Barang->satuan }}</span>
                                                </span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[10px] text-gray-400 uppercase font-bold tracking-tight">Subtotal</span>
                                                <span class="text-xs font-black text-blue-600">Rp
                                                    {{ number_format($detail->total_harga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 ml-4">
                                            {{-- Tombol Edit --}}
                                            <button
                                                @click="
                                                editData = { 
                                                    id: '{{ $detail->id }}', 
                                                    qty: '{{ $detail->jumlah_keluar }}', 
                                                    originalQty: '{{ $detail->jumlah_keluar }}',
                                                    barang: '{{ $detail->DetailInventory->Inventory->Barang->nama_barang }}',
                                                    satuan: '{{ $detail->DetailInventory->Inventory->Barang->satuan }}',
                                                    stok_batch: {{ $detail->DetailInventory->stok }},
                                                    tanggal: '{{ \Carbon\Carbon::parse($detail->tanggal_keluar)->format('Y-m-d') }}'
                                                };
                                                editModal = true"
                                                class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Delete --}}
                                            <form action="{{ route('barang-keluar.destroy', $detail->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Hapus data batch ini? Stok akan dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">Data tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Modal Teleport --}}
        <template x-teleport="body">
            <div x-show="editModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div x-show="editModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        @click="editModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                    </div>

                    <div x-show="editModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        class="relative inline-block w-full max-w-lg p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-3xl">

                        <form :action="`{{ url('barang-keluar') }}/${editData.id}`" method="POST">
                            @csrf
                            @method('PUT')

                            <h3 class="text-xl font-bold text-gray-900 mb-1">Edit Pengeluaran</h3>
                            <p class="text-sm text-gray-500 mb-6" x-text="editData.barang"></p>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal
                                        Keluar</label>
                                    <input type="date" name="tanggal_keluar" x-model="editData.tanggal" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Jumlah
                                            Keluar</label>

                                        {{-- Keterangan Max Stok menggunakan originalQty --}}
                                        <span class="text-[10px] font-bold uppercase"
                                            :class="parseFloat(editData.qty) > (parseFloat(editData.originalQty) + parseFloat(
                                                editData.stok_batch)) ? 'text-red-500' : 'text-blue-500'">
                                            Max: <span
                                                x-text="(parseFloat(editData.originalQty) + parseFloat(editData.stok_batch)).toFixed(2)"></span>
                                            <span x-text="editData.satuan"></span>
                                        </span>
                                    </div>

                                    <input type="number" name="jumlah_keluar" x-model="editData.qty"
                                        {{-- Atribut native HTML max untuk validasi browser --}}
                                        :max="parseFloat(editData.originalQty) + parseFloat(editData.stok_batch)"
                                        step="any" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 outline-none font-bold text-lg transition-all"
                                        :class="parseFloat(editData.qty) > (parseFloat(editData.originalQty) + parseFloat(
                                                editData.stok_batch)) ? 'focus:ring-red-500 border-red-200 bg-red-50' :
                                            'focus:ring-blue-500'">

                                    {{-- Pesan Error Instan --}}
                                    <template
                                        x-if="parseFloat(editData.qty) > (parseFloat(editData.originalQty) + parseFloat(editData.stok_batch))">
                                        <p class="mt-2 text-[10px] text-red-600 font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Jumlah melebihi total stok tersedia!
                                        </p>
                                    </template>
                                </div>

                                <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl">
                                    <p class="text-[10px] text-amber-700 leading-relaxed font-medium">
                                        <strong>Informasi:</strong> Perubahan jumlah akan otomatis menyesuaikan stok di
                                        batch terkait. Jika jumlah ditambah, stok akan berkurang, dan sebaliknya.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" @click="editModal = false"
                                    class="px-6 py-3 text-sm font-bold text-gray-500 hover:bg-gray-50 rounded-2xl">Batal</button>
                                <button type="submit"
                                    :disabled="parseFloat(editData.qty) > (parseFloat(editData.originalQty) + parseFloat(editData
                                        .stok_batch)) || editData.qty <= 0"
                                    class="px-8 py-3 text-sm font-bold text-white bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 disabled:bg-gray-300 disabled:shadow-none transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
