<x-layout.user.app>
    <style>
        /* Menghilangkan scrollbar tapi fungsi scroll tetap jalan */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div class="space-y-6">

        {{-- HEADER SECTION --}}
        <div class="relative overflow-hidden bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pusat Kendali Sistem 👋</h1>
                    <p class="text-gray-500 max-w-md">Monitoring performa seluruh gudang dan rantai pasokan Mirasa Food
                        dalam satu pintu.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('user.create') }}"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all">+
                        User</a>
                    <a href="{{ route('perusahaan.create') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">+
                        Perusahaan</a>
                </div>
            </div>
        </div>

        {{-- STATS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Perusahaan --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Gudang Aktif</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['total_perusahaan']) }}
                        </h3>
                    </div>
                </div>
            </div>

            {{-- Total User --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 rounded-2xl text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M336 256c-20.56 0-40.44-9.18-56-25.84c-15.13-16.25-24.37-37.92-26-61c-1.74-24.62 5.77-47.26 21.14-63.76S312 80 336 80c23.83 0 45.38 9.06 60.7 25.52c15.47 16.62 23 39.22 21.26 63.63c-1.67 23.11-10.9 44.77-26 61C376.44 246.82 356.57 256 336 256m131.83 176H204.18a27.71 27.71 0 0 1-22-10.67a30.22 30.22 0 0 1-5.26-25.79c8.42-33.81 29.28-61.85 60.32-81.08C264.79 297.4 299.86 288 336 288c36.85 0 71 9 98.71 26.05c31.11 19.13 52 47.33 60.38 81.55a30.27 30.27 0 0 1-5.32 25.78A27.68 27.68 0 0 1 467.83 432M147 260c-35.19 0-66.13-32.72-69-72.93c-1.42-20.6 5-39.65 18-53.62c12.86-13.83 31-21.45 51-21.45s38 7.66 50.93 21.57c13.1 14.08 19.5 33.09 18 53.52c-2.87 40.2-33.8 72.91-68.93 72.91m65.66 31.45c-17.59-8.6-40.42-12.9-65.65-12.9c-29.46 0-58.07 7.68-80.57 21.62c-25.51 15.83-42.67 38.88-49.6 66.71a27.39 27.39 0 0 0 4.79 23.36A25.32 25.32 0 0 0 41.72 400h111a8 8 0 0 0 7.87-6.57c.11-.63.25-1.26.41-1.88c8.48-34.06 28.35-62.84 57.71-83.82a8 8 0 0 0-.63-13.39c-1.57-.92-3.37-1.89-5.42-2.89" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Total User</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['total_user']) }}</h3>
                    </div>
                </div>
            </div>

            {{-- Total Produk --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-50 rounded-2xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Katalog Produk</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['total_barang']) }}</h3>
                    </div>
                </div>
            </div>

            {{-- Total Distribusi --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-orange-50 rounded-2xl text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 28 28">
                            <path fill="currentColor"
                                d="M9.5 13a4.5 4.5 0 1 0 0-9a4.5 4.5 0 0 0 0 9m14-3.5a3.5 3.5 0 1 1-7 0a3.5 3.5 0 0 1 7 0M2 17.25A2.25 2.25 0 0 1 4.25 15h10.5q.298.001.573.074A7.48 7.48 0 0 0 13 20.5c0 .665.086 1.309.249 1.922c-.975.355-2.203.578-3.749.578C2 23 2 17.75 2 17.75zm25 3.25a6.5 6.5 0 1 1-13 0a6.5 6.5 0 0 1 13 0m-5.786-3.96a.742.742 0 0 0-1.428 0l-.716 2.298h-2.318c-.727 0-1.03.97-.441 1.416l1.875 1.42l-.716 2.298c-.225.721.567 1.32 1.155.875l1.875-1.42l1.875 1.42c.588.446 1.38-.154 1.155-.875l-.716-2.298l1.875-1.42c.588-.445.286-1.416-.441-1.416H21.93z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Total Costumer</p>
                        <h3 class="text-2xl font-black text-gray-800">{{ number_format($stats['total_costumer']) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KATALOG PRODUK --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-black uppercase text-xs tracking-[0.2em] text-gray-400">Katalog Produk Per
                        Perusahaan</h4>
                    <div class="flex gap-2">
                        <span
                            class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-wider">Swipe</span>
                    </div>
                </div>

                @forelse($katalog_perusahaan as $perusahaan)
                    <div class="space-y-4">
                        {{-- Header Perusahaan --}}
                        <div class="flex items-center justify-between px-2">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                    {{ substr($perusahaan->nama_perusahaan, 0, 1) }}
                                </div>
                                <h3 class="font-bold text-gray-800 tracking-tight">{{ $perusahaan->nama_perusahaan }} ({{ $perusahaan->kota }})
                                </h3>
                            </div>
                            <a href="#"
                                class="text-blue-600 text-[11px] font-black uppercase tracking-wider hover:underline">Lihat
                                Semua</a>
                        </div>

                        {{-- Slider Container --}}
                        <div class="flex overflow-x-auto gap-4 pb-4 snap-x snap-mandatory no-scrollbar"
                            style="scrollbar-width: none; -ms-overflow-style: none;">
                            @forelse($perusahaan->barang->take(7) as $barang)
                                <div class="min-w-[160px] md:min-w-[180px] snap-start">
                                    <div
                                        class="bg-white rounded-[2rem] p-3 border border-gray-100 shadow-sm hover:shadow-md transition-all group group">

                                        {{-- Foto Produk --}}
                                        <div
                                            class="aspect-square w-full rounded-[1.5rem] bg-gray-50 overflow-hidden mb-3 relative">
                                            @if ($barang->foto)
                                                <img src="{{ asset('storage/' . $barang->foto) }}"
                                                    alt="{{ $barang->nama_barang }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                                                    <svg class="w-8 h-8 text-gray-200" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif

                                            {{-- Floating Badge Kode --}}
                                            <div class="absolute top-2 right-2">
                                                <span
                                                    class="px-2 py-1 rounded-lg text-[8px] font-black uppercase shadow-sm backdrop-blur-md {{ $barang->jenisBarang->kode == 'FG' ? 'bg-green-500/90 text-white' : ($barang->jenisBarang->kode == 'WIP' ? 'bg-orange-500/90 text-white' : 'bg-purple-500/90 text-white') }}">
                                                    {{ $barang->jenisBarang->kode }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Info Produk --}}
                                        <div class="px-1 pb-1">
                                            <h4 class="font-bold text-gray-700 text-xs truncate leading-snug group-hover:text-blue-600 transition-colors"
                                                title="{{ $barang->nama_barang }}">
                                                {{ $barang->nama_barang }}
                                            </h4>
                                            <p class="text-[9px] text-gray-400 mt-1 font-medium italic">
                                                #{{ $barang->kode ?? 'No-Code' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="w-full bg-gray-50 rounded-3xl py-10 text-center border border-dashed border-gray-200">
                                    <p class="text-xs text-gray-400 font-medium">Katalog barang masih kosong.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if (!$loop->last)
                        <div class="h-px bg-gradient-to-r from-transparent via-gray-100 to-transparent my-2"></div>
                    @endif

                @empty
                    <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-100">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="text-gray-400 font-medium text-sm">Belum ada data perusahaan yang terdaftar.</p>
                    </div>
                @endforelse
            </div>

            {{-- QUICK ACTIONS / INFO --}}
            <div class="space-y-6">
                <div
                    class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl shadow-blue-100">
                    <h4 class="font-black uppercase text-xs tracking-[0.2em] mb-4 opacity-80">Info Lisensi</h4>
                    <p class="text-2xl font-bold mb-2">Mirasa Enterprise</p>
                    <p class="text-sm opacity-70 leading-relaxed mb-6">Sistem ini mengelola multi-warehouse dengan
                        sinkronisasi stok real-time.</p>

                    <div class="space-y-3">
                        <h5 class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-3">Fitur Monitoring
                            Aktif</h5>

                        <div class="grid grid-cols-1 gap-2">
                            {{-- Monitoring Stok --}}
                            <div
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/10">
                                <div class="p-2 bg-blue-400/20 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold tracking-wide">Monitoring Stok Produk</span>
                            </div>

                            {{-- Monitoring Keuangan --}}
                            <div
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/10">
                                <div class="p-2 bg-emerald-400/20 rounded-lg">
                                    <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold tracking-wide">Monitoring Keuangan</span>
                            </div>

                            {{-- Monitoring Gudang --}}
                            <div
                                class="flex items-center gap-3 p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/10">
                                <div class="p-2 bg-orange-400/20 rounded-lg">
                                    <svg class="w-4 h-4 text-orange-100" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold tracking-wide">Monitoring Gudang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                    <h4 class="font-black uppercase text-xs tracking-[0.2em] mb-6 text-gray-400">Pusat Bantuan</h4>
                    <div class="space-y-4">
                        <div
                            class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div
                                class="p-2 bg-gray-100 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Dokumentasi API</span>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div
                                class="p-2 bg-gray-100 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">Hubungi Developer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layout.user.app>
