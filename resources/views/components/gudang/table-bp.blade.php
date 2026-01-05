@props(['details', 'supplier'])

<div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden mx-4 md:mx-0"
    x-data="{
        editModalOpen: false,
        editData: {
            id: '',
            jumlah_diterima: 0,
            jumlah_rusak: 0,
            stok: 0,
            harga: 0,
            tgl_masuk: '',
            tgl_exp: '',
            lokasi: '',
            kondisi_brg: '',
            kondisi_knd: ''
        },
        openEdit(item) {
            this.editData = item;
            this.editModalOpen = true;
        }
    }">

    <div class="px-6 py-5 border-b border-slate-100 bg-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="font-bold text-slate-800">Riwayat Stok Masuk</h3>
        </div>

        <div>
            {{-- Tombol Menuju Halaman Create --}}
            <a href="{{ route('inventory.create-bp') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-200 transition-all active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden md:block">Tambah Stok</span>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100">
                    <th
                        class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center w-16">
                        No</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Log Kedatangan
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tempat
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Supplier
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">Stok
                        Aktif</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">Nilai
                        Satuan (Rp)</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">Total
                        Investasi (Rp)</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($details as $index => $i)
                    <tr class="hover:bg-blue-50/40 transition-all duration-200 group">
                        <td class="px-6 py-4 text-sm text-slate-400 text-center font-medium">{{ $index + 1 }}</td>

                        {{-- Log Kedatangan --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($i->tanggal_masuk)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                {{ $i->tempat_penyimpanan }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                {{ $i->supplier->nama_supplier }}
                            </span>
                        </td>

                        {{-- Stok Aktif --}}
                        <td class="px-6 py-4 text-right">
                            <span
                                class="text-sm font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-xl ring-1 ring-blue-100">
                                {{ number_format($i->stok, $i->stok == floor($i->stok) ? 0 : 2, ',', '.') }}
                            </span>
                        </td>

                        {{-- Harga Satuan --}}
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm text-slate-600 font-medium">
                                {{ number_format($i->harga, $i->harga == floor($i->harga) ? 0 : 2, ',', '.') }}
                            </span>
                        </td>

                        {{-- Total Harga --}}
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                {{ number_format($i->total_harga, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            @if ($i->stok == $i->jumlah_diterima)
                                <button type="button"
                                    @click="openEdit({ 
                                    id: '{{ $i->id }}', 
                                    jumlah_diterima: '{{ $i->jumlah_diterima }}', 
                                    jumlah_rusak: '{{ $i->jumlah_rusak }}', 
                                    stok: '{{ $i->stok }}', 
                                    harga: '{{ $i->harga }}', 
                                    tgl_masuk: '{{ $i->tanggal_masuk }}', 
                                    lokasi: '{{ $i->tempat_penyimpanan }}', 
                                })"
                                    class="inline-flex items-center justify-center w-9 h-9 text-slate-400 hover:text-blue-600 hover:bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-200 transition-all duration-200 bg-slate-50/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @else
                                <div class="inline-flex items-center justify-center w-9 h-9 text-slate-300 bg-slate-100/50 rounded-xl border border-slate-100 cursor-not-allowed"
                                    title="Data tidak dapat diedit karena stok sudah digunakan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h4 class="text-slate-900 font-bold">Tidak ada riwayat</h4>
                                <p class="text-slate-400 text-sm max-w-[240px] mt-1">Data riwayat transaksi barang masuk
                                    belum tersedia untuk produk ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Update (Di luar loop, tapi masih dalam x-data) --}}
    <template x-teleport="body">
        <div x-show="editModalOpen"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-cloak>

            <div @click.away="editModalOpen = false"
                class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-100"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Edit Riwayat Stok</h3>
                    </div>
                    <button @click="editModalOpen = false"
                        class="p-2 hover:bg-white rounded-full text-slate-400 hover:text-slate-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="'/inventory-details/' + editData.id" method="POST" class="p-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                        {{-- Baris 1: Tanggal & Lokasi --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" x-model="editData.tgl_masuk"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Lokasi
                                Penyimpanan</label>
                            <input type="text" name="tempat_penyimpanan" x-model="editData.lokasi"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm"
                                placeholder="Contoh: Rak A1">
                        </div>

                        {{-- Baris 3: Kuantitas --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-blue-600 uppercase ml-1">Jumlah Diterima</label>
                            <input type="number" name="jumlah_diterima" x-model.number="editData.jumlah_diterima"
                                class="w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-red-500 uppercase ml-1">Jumlah Rusak
                                (Reject)</label>
                            <input type="number" name="jumlah_rusak" x-model.number="editData.jumlah_rusak"
                                class="w-full px-4 py-2.5 bg-red-50/30 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition-all font-bold text-red-600 text-sm">
                        </div>

                        {{-- Baris 4: Stok Akhir & Harga --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Stok Bersih (Masuk
                                Gudang)</label>
                            <input type="number" name="stok"
                                :value="editData.jumlah_diterima - editData.jumlah_rusak" readonly
                                class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl outline-none font-black text-gray-800 shadow-inner cursor-not-allowed text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-500 uppercase ml-1">Harga Per Satuan</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">Rp</span>
                                <input type="number" name="harga" x-model.number="editData.harga"
                                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="editModalOpen = false"
                            class="flex-1 px-4 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm">
                            Batalkan
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3.5 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
