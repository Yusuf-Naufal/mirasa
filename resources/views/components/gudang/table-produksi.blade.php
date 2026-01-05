@props(['details'])

<div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden mx-4 md:mx-0"
    x-data="{
        editModalOpen: false,
        editData: { id: '', stok: '', harga: '', tgl_masuk: '', tgl_exp: '', no_batch: '', tempat: '' },
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
            <a href="{{ route('inventory.create-produksi') }}"
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
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-400">
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-center w-16">
                        No</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">Tgl Masuk</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">Tgl Expired</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">No Batch</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider">Tempat</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-right">Stok
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-right">Harga
                        Satuan</th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-right">Total
                    </th>
                    <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-center">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($details as $index => $i)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4 text-sm text-slate-400 text-center">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($i->tanggal_masuk)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium">
                                {{ \Carbon\Carbon::parse($i->tanggal_exp)->translatedFormat('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                            {{ $i->nomor_batch }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                            {{ $i->tempat_penyimpanan }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                            {{ number_format($i->stok, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-slate-600">
                            Rp{{ number_format($i->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-slate-900 group-hover:text-blue-600">
                                Rp{{ number_format($i->total_harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($i->stok == $i->jumlah_diterima)
                                <button type="button"
                                    @click="openEdit({ 
                                    id: '{{ $i->id }}', 
                                    stok: '{{ $i->stok }}', 
                                    harga: '{{ $i->harga }}', 
                                    tgl_masuk: '{{ $i->tanggal_masuk }}', 
                                    no_batch: '{{ $i->nomor_batch }}', 
                                    tempat: '{{ $i->tempat_penyimpanan }}', 
                                    tgl_exp: '{{ $i->tanggal_exp }}' 
                                })"
                                    class="p-2 text-slate-400 hover:text-blue-600 hover:bg-white rounded-xl shadow-sm border border-transparent hover:border-slate-100 transition-all">
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
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-medium text-sm italic">Belum ada riwayat
                                    transaksi.</p>
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
                class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100"
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

                    <div class="grid grid-cols-2 gap-5 mb-8">
                        <div class="col-span-1">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Stok
                                Tersisa</label>
                            <input type="number" name="stok" x-model="editData.stok"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-slate-700 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Harga
                                Satuan (Rp)</label>
                            <input type="number" name="harga" x-model="editData.harga"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold text-slate-700 transition-all">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tgl
                                Masuk</label>
                            <input type="date" name="tanggal_masuk" x-model="editData.tgl_masuk"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tgl
                                Expired</label>
                            <input type="date" name="tanggal_exp" x-model="editData.tgl_exp"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">No
                                Batch</label>
                            <input type="text" name="nomor_batch" x-model="editData.no_batch"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                        <div class="col-span-1">
                            <label
                                class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tempat</label>
                            <input type="text" name="tempat_penyimpanan" x-model="editData.tempat"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none text-slate-600 transition-all text-sm">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="editModalOpen = false"
                            class="flex-1 px-4 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors text-sm">
                            Batalkan
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3.5 rounded-2xl bg-yellow-600 text-white font-bold hover:bg-yellow-700 shadow-lg shadow-yellow-200 transition-all text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
