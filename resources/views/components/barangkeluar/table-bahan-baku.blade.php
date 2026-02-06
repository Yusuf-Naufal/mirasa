@props(['data'])

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{
    expandedGroup: null,
    editOpen: false,
    editData: { id: '', nama: '', jumlah: '', jenis: '', tanggal: '', maxStok: 0 }
}">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="w-7 px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Produk & Tanggal
                    </th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                        Jenis Transaksi</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total
                        Qty</th>
                    <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total
                        Nilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($data as $groupKey => $items)
                    @php
                        $firstItem = $items->first();
                        $barang = $firstItem->DetailInventory->Inventory->Barang;
                        $totalGroup = $items->sum('jumlah_keluar');
                        $totalNilai = $items->sum('total_harga');
                        $groupId = 'group-' . $loop->index;
                    @endphp

                    <tr class="hover:bg-emerald-50/30 transition-colors cursor-pointer group"
                        @click="expandedGroup = (expandedGroup === '{{ $groupId }}' ? null : '{{ $groupId }}')">
                        <td class="px-6 py-5 text-center">
                            <div class="transition-transform duration-300"
                                :class="expandedGroup === '{{ $groupId }}' ? 'rotate-180' : ''">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm">{{ $barang->nama_barang }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                                    {{ \Carbon\Carbon::parse($firstItem->tanggal_keluar)->format('d M Y') }} •
                                    {{ $barang->kode }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-5 text-center">
                            <div class="flex justify-center gap-1">
                                @foreach ($items->pluck('jenis_keluar')->unique() as $type)
                                    <span
                                        class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-tighter 
                                        {{ $type === 'PENJUALAN' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ $type }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-5 text-right font-black text-gray-700 text-sm">
                            {{ number_format($totalGroup, 0) }} <span
                                class="text-[10px] text-gray-400 font-bold uppercase ml-1">{{ $barang->satuan }}</span>
                        </td>
                        <td class="px-4 py-5 text-right font-black text-emerald-600 text-sm">
                            Rp {{ number_format($totalNilai, 0, ',', '.') }}
                        </td>

                    </tr>

                    <tr x-show="expandedGroup === '{{ $groupId }}'" x-collapse x-cloak class="bg-gray-50/50">
                        <td colspan="9" class="p-0">
                            <div class="px-20 py-6 border-l-4 border-emerald-500 ml-6 my-2">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="text-[9px] font-black text-emerald-600 uppercase tracking-widest border-b border-emerald-100">
                                            <th class="py-2 text-left">Keluar dari</th>
                                            <th class="py-2 text-center">Supplier</th>
                                            <th class="py-2 text-right">Qty</th>
                                            <th class="py-2 text-right">Subtotal</th>
                                            <th class="py-2 text-right w-20">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-emerald-50/50">
                                        @foreach ($items as $item)
                                            <tr class="group">
                                                <td class="py-3 text-xs font-bold text-gray-700">
                                                    {{ $item->jenis_keluar === 'PENJUALAN' ? $item->Costumer->nama_costumer ?? 'Umum' : $item->Perusahaan->nama_perusahaan ?? 'Cabang' }}
                                                </td>
                                                <td class="py-3 text-center font-mono text-[10px] text-gray-400">
                                                    {{ $item->DetailInventory->Supplier->nama_supplier ?? 'Lainnya' }}
                                                </td>
                                                <td class="py-3 text-right text-xs font-bold text-gray-700">
                                                    {{ number_format($item->jumlah_keluar, 0) }}
                                                </td>
                                                <td class="py-3 text-right text-xs font-black text-gray-800">
                                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                                </td>
                                                <td class="py-3 text-right items-end justify-end flex">
                                                    {{-- Edit Item (Per Batch) --}}
                                                    <button
                                                        @click="editOpen = true; editData = {
                                                        id: '{{ $item->id }}', 
                                                        nama: '{{ $barang->nama_barang }}',
                                                        jenis: '{{ $item->jenis_keluar }}',
                                                        tanggal: '{{ $item->tanggal_keluar }}',
                                                        jumlah: '{{ $item->jumlah_keluar }}',
                                                        maxStok: {{ $item->DetailInventory->stok + $item->jumlah_keluar }}
                                                    }"
                                                        class="p-1.5 text-yellow-400 hover:bg-yellow-100 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>

                                                    {{-- Tombol Delete --}}
                                                    <form action="{{ route('barang-keluar.destroy', $item->id) }}"
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    </div>

    {{-- Modal Teleport --}}
    <template x-teleport="body">
        <div x-show="editOpen" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                {{-- Backdrop --}}
                <div x-show="editOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" @click="editOpen = false"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75 backdrop-blur-sm transition-opacity">
                </div>

                {{-- Card Modal --}}
                <div x-show="editOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative inline-block w-full max-w-lg p-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem]">

                    <form :action="`{{ url('barang-keluar') }}/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <h3 class="text-2xl font-black text-gray-900 leading-tight">Edit Distribusi</h3>
                            <p class="text-sm font-bold text-emerald-600 uppercase tracking-widest mt-1"
                                x-text="editData.nama"></p>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Tanggal Keluar --}}
                                <div class="space-y-1.5">
                                    <label
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal
                                        Keluar</label>
                                    <input type="date" name="tanggal_keluar" x-model="editData.tanggal" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none font-bold text-sm transition-all text-gray-700">
                                </div>

                                {{-- Jumlah Keluar --}}
                                <div class="space-y-1.5">
                                    <div class="flex justify-between items-center px-1">
                                        <label
                                            class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah</label>
                                        <span class="text-[10px] font-bold uppercase"
                                            :class="parseFloat(editData.jumlah) > parseFloat(editData.maxStok) ?
                                                'text-red-500' : 'text-emerald-500'">
                                            Max: <span x-text="parseFloat(editData.maxStok).toFixed(2)"></span>
                                        </span>
                                    </div>
                                    <input type="number" name="jumlah_keluar" x-model="editData.jumlah"
                                        :max="editData.maxStok" step="any" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 outline-none font-black text-lg transition-all"
                                        :class="parseFloat(editData.jumlah) > parseFloat(editData.maxStok) ?
                                            'focus:ring-red-500 border-red-200 bg-red-50 text-red-600' :
                                            'focus:ring-emerald-500 text-gray-700'">
                                </div>
                            </div>

                            {{-- Warning Box --}}
                            <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl flex gap-3">
                                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p
                                    class="text-[10px] text-amber-700 leading-relaxed font-bold uppercase tracking-tighter">
                                    Perubahan jumlah akan otomatis menyesuaikan stok di batch terkait. Harap pastikan
                                    jumlah tidak melebihi kapasitas batch asal.
                                </p>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="editOpen = false"
                                class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                :disabled="parseFloat(editData.jumlah) > parseFloat(editData.maxStok) || editData.jumlah <= 0"
                                class="px-8 py-4 text-xs font-black uppercase tracking-widest text-white bg-emerald-600 rounded-2xl shadow-xl shadow-emerald-200 disabled:bg-gray-300 disabled:shadow-none transition-all active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
