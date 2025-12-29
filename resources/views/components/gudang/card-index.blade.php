@props(['jenis', 'items', 'accent' => 'blue'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-300']) }}>
    {{-- Header Kartu --}}
    <div class="px-5 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">{{ $jenis ?: 'Tanpa Kategori' }}</h3>
        <span class="bg-{{ $accent }}-100 text-{{ $accent }}-600 text-xs font-bold px-2.5 py-1 rounded-lg">
            {{ $items->count() }} Item
        </span>
    </div>

    {{-- List Barang --}}
    <div class="p-5 flex-1 space-y-3">
        @foreach ($items as $item)
            @php
                $stokAktual = $item->stok;
                $stokMin = $item->minimum_stok ?? 0;
                $ambangKuning = $stokMin + 30;
            @endphp

            <a href="{{ route('inventory.show', $item->id) }}" class="flex items-center justify-between group p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-all duration-200">
                <div class="flex items-center gap-3">
                    {{-- Foto/Icon --}}
                    <div class="w-11 h-11 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 group-hover:ring-2 group-hover:ring-{{ $accent }}-400 transition-all overflow-hidden border border-gray-50">
                        @if ($item->barang->foto)
                            <img src="{{ asset('storage/' . $item->barang->foto) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        @endif
                    </div>

                    {{-- Nama & Kode --}}
                    <div>
                        <p class="text-sm font-bold text-gray-800 line-clamp-1 uppercase leading-tight">{{ $item->barang->nama_barang }}</p>
                        <p class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $item->barang->kode }}</p>
                    </div>
                </div>

                {{-- Stok & Indikator --}}
                <div class="text-right flex flex-col items-end shrink-0 pl-2">
                    <p class="text-sm font-black text-slate-700">
                        {{ number_format($stokAktual, 0, ',', '.') }}
                        <span class="text-[9px] text-slate-400 font-medium uppercase">{{ $item->barang->satuan }}</span>
                    </p>

                    @if ($stokAktual <= $stokMin)
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded uppercase">
                            <span class="w-1 h-1 rounded-full bg-red-600 animate-pulse"></span> Kritis
                        </span>
                    @elseif ($stokAktual <= $ambangKuning)
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded uppercase">
                            Limit
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase">
                            Aman
                        </span>
                    @endif
                </div>
            </a>
            @if (!$loop->last) <hr class="border-gray-50"> @endif
        @endforeach
    </div>
</div>