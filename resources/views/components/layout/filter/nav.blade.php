@props(['selectedMonth', 'selectedYear', 'filterType', 'daftarPerusahaan' => null])

<div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">

    {{-- DROPDOWN NAVIGATION --}}
    <div class="relative w-full lg:w-72" x-data="{ open: false }">
        @php
            // Definisi Tab dengan properti lengkap
            $allTabs = collect([
                [
                    'route' => 'grafik.bahan-baku',
                    'label' => 'Bahan Baku',
                    'desc' => 'Distribusi Bahan Baku',
                    'color' => 'text-blue-600',
                    'bg' => 'bg-blue-50',
                    'viewBox' => '0 0 24 24',
                    'full_svg' => '<path d="m7.5 4.27 9 5.15" />
                <path
                    d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                <path d="m3.3 7 8.7 5 8.7-5" />
                <path d="M12 22V12" />',
                ],
                [
                    'route' => 'grafik.produksi',
                    'label' => 'Produksi',
                    'desc' => 'Hasil & Rendemen',
                    'color' => 'text-gray-600',
                    'bg' => 'bg-gray-100',
                    'viewBox' => '0 0 24 24',
                    'full_svg' => '<path d="M12 3v18" />
                <path d="M5 8v8" />
                <path d="M19 8v8" />
                <path d="M19 19H5" />
                <path d="M5 5h14" />',
                ],
                [
                    'route' => 'grafik.pemakaian',
                    'label' => 'Pemakaian',
                    'desc' => 'Logistik & Utility',
                    'color' => 'text-rose-600',
                    'bg' => 'bg-rose-50',
                    'viewBox' => '0 0 24 24',
                    'full_svg' => '<path fill="currentColor"
                    d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97s-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1s.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64z" />',
                ],
                [
                    'route' => 'grafik.hpp',
                    'label' => 'HPP',
                    'desc' => 'Harga Pokok Produksi',
                    'color' => 'text-green-600',
                    'bg' => 'bg-green-50',
                    'viewBox' => '0 0 24 24',
                    'full_svg' => '<path fill="none" stroke="currentColor" stroke-width="2"
                    d="M16 16c0-1.105-3.134-2-7-2s-7 .895-7 2s3.134 2 7 2s7-.895 7-2ZM2 16v4.937C2 22.077 5.134 23 9 23s7-.924 7-2.063V16M9 5c-4.418 0-8 .895-8 2s3.582 2 8 2M1 7v5c0 1.013 3.582 2 8 2M23 4c0-1.105-3.1-2-6.923-2s-6.923.895-6.923 2s3.1 2 6.923 2S23 5.105 23 4Zm-7 12c3.824 0 7-.987 7-2V4M9.154 4v10.166M9 9c0 1.013 3.253 2 7.077 2S23 10.013 23 9" />',
                ],
                [
                    'route' => 'grafik.transaksi',
                    'label' => 'Transaksi',
                    'desc' => 'Alur Masuk & Keluar',
                    'color' => 'text-yellow-600',
                    'bg' => 'bg-yellow-50',
                    'viewBox' => '0 0 1024 1024',
                    'full_svg' =>
                        '<path fill="currentColor" d="M668.6 320c0-4.4-3.6-8-8-8h-54.5c-3 0-5.8 1.7-7.1 4.4l-84.7 168.8H511l-84.7-168.8a8 8 0 0 0-7.1-4.4h-55.7c-1.3 0-2.6.3-3.8 1c-3.9 2.1-5.3 7-3.2 10.8l103.9 191.6h-57c-4.4 0-8 3.6-8 8v27.1c0 4.4 3.6 8 8 8h76v39h-76c-4.4 0-8 3.6-8 8v27.1c0 4.4 3.6 8 8 8h76V704c0 4.4 3.6 8 8 8h49.9c4.4 0 8-3.6 8-8v-63.5h76.3c4.4 0 8-3.6 8-8v-27.1c0-4.4-3.6-8-8-8h-76.3v-39h76.3c4.4 0 8-3.6 8-8v-27.1c0-4.4-3.6-8-8-8H564l103.7-191.6c.5-1.1.9-2.4.9-3.7M157.9 504.2a352.7 352.7 0 0 1 103.5-242.4c32.5-32.5 70.3-58.1 112.4-75.9c43.6-18.4 89.9-27.8 137.6-27.8c47.8 0 94.1 9.3 137.6 27.8c42.1 17.8 79.9 43.4 112.4 75.9c10 10 19.3 20.5 27.9 31.4l-50 39.1a8 8 0 0 0 3 14.1l156.8 38.3c5 1.2 9.9-2.6 9.9-7.7l.8-161.5c0-6.7-7.7-10.5-12.9-6.3l-47.8 37.4C770.7 146.3 648.6 82 511.5 82C277 82 86.3 270.1 82 503.8a8 8 0 0 0 8 8.2h60c4.3 0 7.8-3.5 7.9-7.8M934 512h-60c-4.3 0-7.9 3.5-8 7.8a352.7 352.7 0 0 1-103.5 242.4a352.6 352.6 0 0 1-112.4 75.9c-43.6 18.4-89.9 27.8-137.6 27.8s-94.1-9.3-137.6-27.8a352.6 352.6 0 0 1-112.4-75.9c-10-10-19.3-20.5-27.9-31.4l49.9-39.1a8 8 0 0 0-3-14.1l-156.8-38.3c-5-1.2-9.9 2.6-9.9 7.7l-.8 161.7c0 6.7 7.7 10.5 12.9 6.3l47.8-37.4C253.3 877.7 375.4 942 512.5 942C747 942 937.7 753.9 942 520.2a8 8 0 0 0-8-8.2"/>',
                ],
            ]);

            // 2. FILTER: Hanya ambil tab yang diizinkan oleh permission user
            $tabs = $allTabs->filter(function ($tab) {
                return auth()->user()->can($tab['route']);
            });

            // 3. Tentukan Active Tab berdasarkan route saat ini
            $activeTab = $tabs->first(fn($tab) => request()->routeIs($tab['route']));

            // 4. FALLBACK: Jika route saat ini tidak ada di daftar yang diizinkan (misal akses manual URL)
            // Ambil tab pertama yang diizinkan sebagai default tampilan
            if (!$activeTab && $tabs->isNotEmpty()) {
                $activeTab = $tabs->first();
            }
        @endphp

        <button @click="open = !open" @click.away="open = false"
            class="w-full flex items-center justify-between gap-3 px-5 py-3 bg-white rounded-3xl border border-gray-100 shadow-sm hover:border-blue-400 transition-all group">

            <div class="flex items-center gap-3">
                <div class="p-2 rounded-xl bg-gray-50 {{ $activeTab['color'] ?? 'text-gray-600' }}">
                    {{-- Dinamis ViewBox agar ikon Transaksi (1024) tidak terpotong --}}
                    <svg class="w-4 h-4" viewBox="{{ $activeTab['viewBox'] ?? '0 0 24 24' }}" fill="none"
                        stroke="currentColor" stroke-width="2.5">
                        {!! $activeTab['full_svg'] !!}
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori
                        Grafik</span>
                    <span class="block text-xs font-black text-gray-800 uppercase italic tracking-tighter">
                        {{ $activeTab['label'] ?? 'Pilih Grafik' }}
                    </span>
                </div>
            </div>

            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- DROPDOWN MENU --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="absolute z-50 w-full mt-3 bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden backdrop-blur-xl">
            <div class="p-2 space-y-1">
                @foreach ($tabs as $item)
                    @php $isCurrent = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ $isCurrent ? $item['bg'] . ' ring-1 ring-inset ring-black/5' : 'hover:bg-gray-50' }}">
                        <div
                            class="p-1.5 rounded-lg {{ $isCurrent ? 'bg-white shadow-sm ' . $item['color'] : 'bg-gray-50 text-gray-400' }}">
                            <svg class="w-4 h-4" viewBox="{{ $item['viewBox'] }}" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                {!! $item['full_svg'] !!}
                            </svg>
                        </div>
                        <div>
                            <span
                                class="block text-[11px] font-black uppercase tracking-widest {{ $isCurrent ? $item['color'] : 'text-gray-700' }}">
                                {{ $item['label'] }}
                            </span>
                            <span class="block text-[9px] font-bold text-gray-400 uppercase italic tracking-tighter">
                                {{ $item['desc'] }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SEARCH/DATE FILTER --}}
    <form action="{{ url()->current() }}" method="GET"
        class="flex flex-row items-center gap-2 bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm w-full sm:w-fit overflow-hidden">

        {{-- Filter Perusahaan --}}
        @if (auth()->user()->hasRole('Super Admin') && isset($daftarPerusahaan))
            <div class="relative flex-1 sm:flex-none">
                <select name="id_perusahaan" onchange="this.form.submit()"
                    class="w-full appearance-none rounded-xl border-none text-[10px] sm:text-[11px] font-black py-2.5 pl-3 pr-8 outline-none {{ request('id_perusahaan') ? 'bg-blue-50 text-blue-700' : 'bg-gray-50 text-gray-500' }} cursor-pointer focus:ring-2 focus:ring-blue-200 transition-all uppercase italic">
                    <option value="" class="not-italic">Semua Perusahaan</option>
                    @foreach ($daftarPerusahaan as $p)
                        <option value="{{ $p->id }}" {{ request('id_perusahaan') == $p->id ? 'selected' : '' }}
                            class="not-italic">
                            {{-- Menampilkan Nama Perusahaan dan Kota --}}
                            {{ $p->nama_perusahaan }} {{ $p->kota ? "({$p->kota})" : '' }}
                        </option>
                    @endforeach
                </select>

                {{-- Icon Dropdown agar lebih cantik --}}
                <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        @endif

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
