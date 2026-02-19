@props(['produk'])

<div x-data="{ openCardId: null, openDropdownId: null }" class="space-y-4 md:hidden">
    @forelse ($produk as $index => $i)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 transition-all">

            <div class="flex justify-between items-start gap-3">
                {{-- Area Klik untuk Expand --}}
                <div class="flex gap-4 cursor-pointer flex-1 min-w-0"
                    @click="openCardId = (openCardId === {{ $i->id }} ? null : {{ $i->id }})">

                    <div class="flex-shrink-0 relative">
                        {{-- FOTO PRODUK --}}
                        <div
                            class="h-16 w-16 rounded-xl overflow-hidden border border-gray-100 shadow-inner bg-gray-50 flex items-center justify-center">
                            @if ($i->foto)
                                <img src="{{ asset('storage/' . $i->foto) }}" alt="{{ $i->nama_produk }}"
                                    class="h-full w-full object-cover transition-transform duration-500"
                                    :class="openCardId === {{ $i->id }} ? 'scale-110' : ''">
                            @else
                                <svg class="w-7 h-7 text-gray-200" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                </svg>
                            @endif
                        </div>
                        {{-- Indikator Unggulan (Kecil di Pojok Foto) --}}
                        @if ($i->is_unggulan)
                            <div
                                class="absolute -top-1 -right-1 bg-yellow-400 text-white p-1 rounded-full border-2 border-white shadow-sm">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-center overflow-hidden flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="text-sm font-bold text-gray-800 truncate tracking-tight">{{ $i->nama_produk }}
                            </h2>
                            <svg class="w-4 h-4 text-gray-300 transition-transform duration-300"
                                :class="openCardId === {{ $i->id }} ? 'rotate-180 text-blue-500' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        {{-- BADGE STATUS --}}
                        <div class="flex items-center gap-2 mt-1.5">
                            @if ($i->is_aktif)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-50 text-green-600 border border-green-100">
                                    <span class="w-1 h-1 rounded-full bg-green-500 mr-1"></span> AKTIF
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-50 text-gray-400 border border-gray-100">
                                    DRAFT
                                </span>
                            @endif

                            @if ($i->is_unggulan)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100">
                                    UNGGULAN
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative">
                        <button
                            @click.stop="openDropdownId = (openDropdownId === {{ $i->id }} ? null : {{ $i->id }})"
                            @click.away="if(openDropdownId === {{ $i->id }}) openDropdownId = null"
                            class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition text-gray-500 focus:outline-none border border-transparent active:border-gray-200">
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
                                @can('produk.edit')
                                    <li>
                                        <a type="button" href="{{ route('produk.edit', $i->id) }}"
                                            class="w-full flex items-center gap-2 px-4 py-3 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition border-b border-gray-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Edit Data
                                        </a>
                                    </li>
                                @endcan

                                @can('produk.delete')
                                    <li>
                                        <form id="delete-form-{{ $i->id }}"
                                            action="{{ route('produk.destroy', $i->id) }}" method="POST">
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
                                @endcan

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETAIL COLLAPSE --}}
            <div x-show="openCardId === {{ $i->id }}" x-collapse x-cloak>
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi Singkat
                    </p>
                    <p class="text-xs text-gray-600 leading-relaxed italic">
                        {{ $i->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}
                    </p>
                </div>
            </div>

        </div>
    @empty
        <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
            <p class="text-sm text-gray-500 font-medium">Data produk tidak ditemukan</p>
            @can('produk.create')
                <a href="{{ route('produk.create') }}"
                    class="mt-3 inline-block text-blue-600 text-xs font-bold hover:underline">TAMBAH SEKARANG</a>
            @endcan
        </div>
    @endforelse
</div>

<div class="md:hidden mt-6">
    <div class="flex justify-end">
        {{ $produk->links('vendor.pagination.custom') }}
    </div>
</div>
