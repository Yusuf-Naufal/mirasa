@props(['item', 'accent' => 'blue'])

@php
    $stokAktual = $item->stok;
    $stokMin = $item->minimum_stok ?? 0;
    $ambangKuning = $stokMin * 1.2;

    // Logika warna status
    $statusColor = 'emerald';
    $statusText = 'Aman';

    if ($stokAktual <= $stokMin) {
        $statusColor = 'red';
        $statusText = 'Kritis';
    } elseif ($stokAktual <= $ambangKuning) {
        $statusColor = 'amber';
        $statusText = 'Limit';
    }

    $hasAccess = auth()->user()->can('inventory.show');
@endphp

<a @if ($hasAccess) href="{{ route('inventory.show', $item->id) }}" @endif
    class="group relative bg-white border border-gray-100 rounded-2xl p-4 transition-all duration-200 flex flex-col gap-3 
    {{ $hasAccess ? 'hover:shadow-lg hover:border-' . $accent . '-200 cursor-pointer' : 'cursor-not-allowed opacity-75 select-none' }}">

    {{-- Header: Foto & Badge Status --}}
    <div class="flex items-start justify-between">
        <div
            class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 {{ $hasAccess ? 'group-hover:scale-105' : '' }} transition-transform">
            @if ($item->barang->foto)
                <img src="{{ asset('storage/' . $item->barang->foto) }}" class="w-full h-full object-cover">
            @else
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            @endif
        </div>

        <span
            class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 border border-{{ $statusColor }}-100">
            {{ $statusText }}
        </span>
    </div>

    {{-- Content: Nama & Kode --}}
    <div class="flex-1">
        <h4
            class="text-sm font-bold text-gray-800 line-clamp-2 leading-snug {{ $hasAccess ? 'group-hover:text-' . $accent . '-600' : '' }} transition-colors uppercase">
            {{ $item->barang->nama_barang }}
        </h4>
        <p class="text-[10px] font-mono text-gray-400 mt-1 uppercase">{{ $item->barang->kode }}</p>
    </div>

    {{-- Footer: Stok --}}
    <div class="pt-4 mt-1 border-t border-gray-50 flex items-center justify-between">
        <div class="flex flex-col">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stok</span>
            <div class="flex items-center gap-1">
                <span
                    class="flex h-1.5 w-1.5 rounded-full {{ $stokAktual <= $stokMin ? 'bg-red-500 animate-pulse' : ($stokAktual <= $ambangKuning ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                <span class="text-[9px] font-medium text-gray-500">Tersedia</span>
            </div>
        </div>

        <div class="text-right">
            <div class="flex items-baseline justify-end gap-1">
                <span
                    class="text-2xl font-black tracking-tighter {{ $stokAktual <= $stokMin ? 'text-red-600' : 'text-slate-800' }}">
                    {{ number_format($stokAktual, 0, ',', '.') }}
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                    {{ $item->barang->satuan }}
                </span>
            </div>
        </div>
    </div>
</a>
