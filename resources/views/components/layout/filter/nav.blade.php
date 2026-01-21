@props(['selectedMonth', 'selectedYear', 'filterType'])

<div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
    {{-- TAB NAVIGATION --}}
    {{-- Menggunakan overflow-x-auto agar tab bisa di-scroll ke samping pada mobile --}}
    <div
        class="flex items-center gap-1 p-1.5 bg-gray-200/50 w-full sm:w-fit rounded-2xl border border-gray-200 backdrop-blur-sm overflow-x-auto no-scrollbar">
        <a href="{{ route('grafik.bahan-baku') }}"
            class="flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap {{ request()->routeIs('grafik.bahan-baku') ? 'bg-white text-blue-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m7.5 4.27 9 5.15" />
                <path
                    d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                <path d="m3.3 7 8.7 5 8.7-5" />
                <path d="M12 22V12" />
            </svg>
            Bahan Baku
        </a>
        <a href="{{ route('grafik.produksi') }}"
            class="flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap {{ request()->routeIs('grafik.produksi') ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3v18" />
                <path d="M5 8v8" />
                <path d="M19 8v8" />
                <path d="M19 19H5" />
                <path d="M5 5h14" />
            </svg>
            Produksi
        </a>
        <a href=""
            class="flex items-center justify-center gap-2 px-4 sm:px-5 py-2 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap {{ request()->routeIs('admin-gudang.grafik-pengeluaran') ? 'bg-white text-rose-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="currentColor"
                    d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97s-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1s.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64z" />
            </svg>
            Pemakaian
        </a>
    </div>

    {{-- SEARCH/DATE FILTER --}}
    <form action="{{ url()->current() }}" method="GET"
        class="flex flex-row items-center gap-2 bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm w-full sm:w-fit overflow-hidden">

        {{-- Filter Type --}}
        <div class="relative flex-1 sm:flex-none">
            <select name="filter_type" x-model="filterType"
                class="w-full appearance-none rounded-xl border-none text-[10px] sm:text-[11px] font-black py-2.5 pl-3 pr-8 outline-none bg-blue-50 text-blue-700 cursor-pointer focus:ring-2 focus:ring-blue-200 transition-all uppercase italic">
                <option value="month">Bulanan</option>
                <option value="year">Tahunan</option>
            </select>
            <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Month Select --}}
        <div x-show="filterType === 'month'" x-transition x-cloak class="flex-1 sm:flex-none">
            <select name="month"
                class="w-full rounded-xl border-gray-100 text-[10px] sm:text-[11px] font-bold py-2.5 px-3 outline-none focus:ring-2 focus:ring-gray-100 transition-all">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ (int) $selectedMonth === $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Year Select --}}
        <div class="flex-1 sm:flex-none">
            <select name="year"
                class="w-full rounded-xl border-gray-100 text-[10px] sm:text-[11px] font-bold py-2.5 px-3 outline-none focus:ring-2 focus:ring-gray-100 transition-all">
                @foreach (range(date('Y') - 3, date('Y')) as $y)
                    <option value="{{ $y }}" {{ (int) $selectedYear === $y ? 'selected' : '' }}>
                        {{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- Submit Button --}}
        <button type="submit"
            class="bg-gray-900 text-white p-2.5 rounded-xl hover:bg-blue-600 transition-all shadow-md group shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:rotate-12 transition-transform"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </button>
    </form>
</div>

<style>
    /* Menghilangkan scrollbar tapi tetap bisa di-scroll */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
