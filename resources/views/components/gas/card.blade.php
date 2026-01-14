@props(['gas'])

<div x-data="{ openCardId: null, openDropdownId: null }" class="space-y-4 md:hidden">
    @forelse ($gas as $index => $i)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 transition-all">

            <div class="flex justify-between items-start gap-3">
                {{-- Area Klik untuk Expand --}}
                <div class="flex gap-4 cursor-pointer flex-1 min-w-0"
                    @click="openCardId = (openCardId === {{ $i->id }} ? null : {{ $i->id }})">

                    {{-- Nomor / Icon --}}
                    <div class="flex-shrink-0">
                        <div
                            class="h-12 w-12 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl flex items-center justify-center text-blue-600 font-bold border border-blue-100 shadow-sm">
                            {{ $index + 1 }}
                        </div>
                    </div>

                    {{-- Info Utama --}}
                    <div class="flex flex-col justify-center overflow-hidden">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-black text-gray-800 truncate">
                                {{ number_format($i->jumlah_gas, 2, ',', '.') }}
                            </h2>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300"
                                :class="openCardId === {{ $i->id }} ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <p class="text-[11px] text-gray-500 font-medium flex items-center gap-1 mt-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($i->tanggal_pemakaian)->translatedFormat('d M Y') }}
                        </p>
                    </div>
                </div>

                {{-- Dropdown Action --}}
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <button
                            @click.stop="openDropdownId = (openDropdownId === {{ $i->id }} ? null : {{ $i->id }})"
                            @click.away="if(openDropdownId === {{ $i->id }}) openDropdownId = null"
                            class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition text-gray-500 focus:outline-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>

                        <div x-show="openDropdownId === {{ $i->id }}" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden outline-none">

                            <ul class="flex flex-col text-xs font-medium">
                                <li>
                                    <button type="button" onclick="openModal('editModal-{{ $i->id }}')"
                                        class="flex items-center gap-2 px-4 py-3 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit Data
                                    </button>
                                </li>

                                <li>
                                    <form id="delete-form-{{ $i->id }}"
                                        action="{{ route('gas.destroy', $i->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $i->id }})"
                                            class="w-full flex items-center gap-2 px-4 py-3 text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus Data
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div x-show="openCardId === {{ $i->id }}" x-collapse x-cloak
                class="mt-3 pt-3 border-t border-gray-50 space-y-3">

                <div class="flex justify-between gap-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Perusahaan:</span>
                    <span class="text-xs text-gray-700 leading-relaxed">{{ $i->perusahaan->nama_perusahaan ?? '-' }}
                        ({{ $i->perusahaan->kota ?? '-' }})
                    </span>
                </div>
            </div>
        </div>

    @empty
        <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-gray-100">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <p class="text-sm text-gray-500 font-bold tracking-tight">Belum ada catatan pemakaian gas</p>
        </div>
    @endforelse
</div>

<div class="md:hidden mt-6">
    <div class="flex justify-end">
        {{ $gas->links('vendor.pagination.custom') }}
    </div>
</div>
