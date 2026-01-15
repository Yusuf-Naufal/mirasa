@props(['items'])

<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/50 border-b border-gray-100">
            <tr>
                <th class="w-7 px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">#</th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Pengeluaran</th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">HPP</th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Nominal</th>
                <th class="px-4 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            {{-- Grouping Berdasarkan Tanggal --}}
            @forelse($items->groupBy('tanggal_pengeluaran') as $tanggal => $group)
                {{-- Header Tanggal --}}
                <tr class="bg-gray-50/30">
                    <td colspan="5" class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[11px] font-black text-blue-600 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </td>
                </tr>

                @foreach($group as $item)
                    <tr class="group hover:bg-gray-50/50 transition-all border-l-2 border-transparent hover:border-blue-500">
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700 uppercase leading-tight">{{ $item->nama_pengeluaran }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded font-bold uppercase tracking-tighter">
                                        {{ $item->sub_kategori }}
                                    </span>
                                    {{-- Link Lihat Bukti --}}
                                    @if($item->bukti)
                                        <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank" class="text-[9px] font-bold text-blue-500 hover:text-blue-700 flex items-center gap-1 underline decoration-dotted">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            LIHAT BUKTI
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if ($item->is_hpp)
                                <span class="px-2 py-1 rounded-lg bg-green-50 text-green-600 text-[9px] font-black uppercase">Ya</span>
                            @else
                                <span class="px-2 py-1 rounded-lg bg-gray-50 text-gray-400 text-[9px] font-black uppercase">Tidak</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="text-sm font-black text-gray-800 tracking-tight">
                                Rp {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                {{-- Tombol Edit --}}
                                <button class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                {{-- Tombol Hapus --}}
                                <form action="#" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">Data tidak ditemukan untuk kategori ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>