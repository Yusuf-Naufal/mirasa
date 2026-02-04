@props(['items'])

{{-- Pindahkan x-data ke pembungkus paling luar --}}
<div class="overflow-x-auto" x-data="{
    editModalOpen: false,
    editUrl: '',
    editData: { nama: '', sub: '', tanggal: '', jumlah: '', hpp: '' }
}">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/50 border-b border-gray-100">
            <tr>
                <th class="w-7 px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Pengeluaran
                </th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">HPP</th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Nominal
                </th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($items->groupBy('tanggal_pengeluaran') as $tanggal => $group)
                {{-- Header Tanggal --}}
                <tr class="bg-gray-50/30">
                    <td colspan="5" class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[11px] font-black text-blue-600 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </td>
                </tr>

                @foreach ($group as $item)
                    <tr
                        class="group hover:bg-gray-50/50 transition-all border-l-2 border-transparent hover:border-blue-500">
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col text-left">
                                <span
                                    class="text-sm font-bold text-gray-700 uppercase leading-tight">{{ $item->nama_pengeluaran }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-[9px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded font-bold uppercase tracking-tighter">{{ $item->sub_kategori }}</span>
                                    @if ($item->bukti)
                                        <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank"
                                            class="text-[9px] font-bold text-blue-500 hover:text-blue-700 flex items-center gap-1 underline decoration-dotted">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            LIHAT BUKTI
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span
                                class="px-2 py-1 rounded-lg text-[9px] font-black uppercase {{ $item->is_hpp ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400' }}">
                                {{ $item->is_hpp ? 'Ya' : 'Tidak' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="text-sm font-black text-gray-800 tracking-tight">Rp
                                {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                {{-- Tombol Edit --}}
                                <a type="button" href="{{ route('pengeluaran.edit', $item->id) }}"
                                    class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">Data tidak ditemukan
                        untuk kategori ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- MODAL EDIT: Diletakkan di luar table agar tidak duplikasi --}}
    <div x-show="editModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">

        <div @click.away="editModalOpen = false" class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight text-left">Edit Pengeluaran</h3>
                <button @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form :action="editUrl" method="POST" enctype="multipart/form-data" class="p-8 space-y-5 text-left">
                @csrf @method('PUT')

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nama
                        Pengeluaran</label>
                    <input type="text" name="nama_pengeluaran" x-model="editData.nama"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Sub
                            Kategori</label>
                        <input type="text" name="sub_kategori" x-model="editData.sub"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all uppercase text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Tanggal</label>
                        <input type="date" name="tanggal_pengeluaran" x-model="editData.tanggal"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nominal (Rp)</label>
                    <input type="number" name="jumlah_pengeluaran" x-model="editData.jumlah"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none font-black text-lg">
                </div>

                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                    <label class="block text-[10px] font-black text-blue-900 uppercase mb-3 text-center">Klasifikasi
                        HPP</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="is_hpp" value="1" x-model="editData.hpp"
                                class="peer hidden">
                            <div
                                class="py-2 text-center rounded-lg border-2 border-white bg-white text-[10px] font-bold text-gray-400 peer-checked:border-blue-500 peer-checked:text-blue-600 transition-all uppercase">
                                Ya</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="is_hpp" value="0" x-model="editData.hpp"
                                class="peer hidden">
                            <div
                                class="py-2 text-center rounded-lg border-2 border-white bg-white text-[10px] font-bold text-gray-400 peer-checked:border-gray-500 peer-checked:text-gray-700 transition-all uppercase">
                                Tidak</div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Ganti Bukti
                        (Opsional)</label>
                    <input type="file" name="bukti"
                        class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg shadow-gray-200 mt-4">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
