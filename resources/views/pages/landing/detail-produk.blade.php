<x-layout.landing.bahtera.app title="{{ $product->nama_produk }} - Bahtera Mandiri Bersama">
    <x-layout.landing.bahtera.nav />

    <section class="pt-24 pb-20 lg:pt-32 lg:pb-32 bg-white relative overflow-hidden">
        <div
            class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/3 w-[600px] h-[600px] bg-bmb-blue/5 rounded-full blur-3xl -z-10">
        </div>
        <div
            class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/3 w-[400px] h-[400px] bg-bmb-orange/5 rounded-full blur-3xl -z-10">
        </div>

        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex mb-12 text-[10px] md:text-xs font-black uppercase tracking-[0.2em]" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-slate-400">
                    <li class="flex items-center"><a href="/"
                            class="hover:text-bmb-blue transition-colors">Beranda</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[8px]"></i></li>
                    <li class="flex items-center"><a href="{{ route('katalog') }}"
                            class="hover:text-bmb-blue transition-colors">Katalog</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[8px]"></i></li>
                    <li class="text-bmb-orange underline underline-offset-4 decoration-2">{{ $product->nama_produk }}
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">
                <div class="lg:col-span-5 relative group">
                    <div
                        class="absolute -inset-2 bg-gradient-to-br from-slate-200 to-slate-50 rounded-[3rem] -z-10 blur-sm group-hover:blur-md transition-all">
                    </div>
                    <div
                        class="aspect-square rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white bg-white">
                        @if ($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                                loading="lazy">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-300 font-bold uppercase tracking-widest text-center">
                                No Image Available
                            </div>
                        @endif
                    </div>

                    @if ($product->is_unggulan)
                        <div
                            class="absolute -top-4 -right-4 bg-bmb-orange text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] shadow-xl border-4 border-white transform rotate-6">
                            Produk Unggulan
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-7 space-y-10">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="px-4 py-1.5 bg-bmb-blue/10 text-bmb-blue text-[10px] font-black uppercase tracking-widest rounded-full">
                                {{ $product->kategori ?? 'Camilan Nusantara' }}
                            </span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                        </div>
                        <h1
                            class="text-5xl md:text-7xl font-black text-slate-900 uppercase tracking-tighter leading-[0.9] mb-6">
                            {{ $product->nama_produk }}
                        </h1>
                        <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl font-medium">
                            {{ $product->deskripsi }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                            <span
                                class="block text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Varian
                                Rasa</span>
                            <span
                                class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ $product->rasa ?? 'Original' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-8 py-8 border-y border-slate-100">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-bmb-blue shadow-sm">
                                <i class="fa-solid fa-leaf text-sm"></i>
                            </div>
                            <span class="font-black text-slate-700 uppercase text-[10px] tracking-widest">Bahan
                                Alami</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-bmb-orange shadow-sm">
                                <i class="fa-solid fa-award text-sm"></i>
                            </div>
                            <span class="font-black text-slate-700 uppercase text-[10px] tracking-widest">Kualitas
                                Premium</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <div class="relative flex-[2] group" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="w-full bg-slate-900 text-white flex items-center justify-center gap-4 py-6 rounded-2xl font-black uppercase tracking-[0.2em] text-sm hover:bg-bmb-blue transition-all shadow-2xl active:scale-[0.98]">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span>Pesan Sekarang</span>
                                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" x-transition
                                class="absolute bottom-full mb-4 left-0 w-full bg-white rounded-[2rem] shadow-[0_20px_60px_rgba(0,0,0,0.2)] border border-slate-100 overflow-hidden z-50 p-2 space-y-1">
                                <a href="https://www.tokopedia.com/bahterafood" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl hover:bg-green-50 transition-colors">
                                    <div
                                        class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 48 48">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M27.043 12.942c-3.43-2.897-16.85-2.247-16.85-2.247l-.473 32.65s17.855.134 23.353 0s9.341-4.508 9.4-7.878s0-24.18 0-24.18c-6.858-.829-11.942-.178-15.43 1.655"
                                                stroke-width="1" />
                                            <circle cx="19.531" cy="24.172" r="6.976" fill="none"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="1" />
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M32.043 29.33a6.272 6.272 0 1 0-2.3-1.786m-19.55-16.849l-4.494 3.252L5.5 39.369l4.22 3.977m23.975-32.251a7.796 7.796 0 0 0-15.318-.299"
                                                stroke-width="1" />
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M34.396 19.662a2.36 2.36 0 0 1-3.878 2.59a4.194 4.194 0 1 0 3.878-2.59m-13.872.345a2.424 2.424 0 0 1-4.251 2.211a4.31 4.31 0 1 0 4.25-2.21m3.838 11.41c0-2.817 2.031-3.962 4.721-3.962c2.395 0 3.755 3.252 3.755 3.252a18.2 18.2 0 0 1-7.45 1.449a9.9 9.9 0 0 0 5.321 2.542s-.827.62-3.665.62c-2.306.001-2.682-2.453-2.682-3.902"
                                                stroke-width="1" />
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" d="M30.317 31.569a10.4 10.4 0 0 1-.258 3.008"
                                                stroke-width="1" />
                                        </svg>
                                    </div>
                                    <div class="text-left"><span
                                            class="block font-black text-slate-800 uppercase text-xs tracking-wider">Tokopedia</span><span
                                            class="text-[9px] text-slate-400 font-bold uppercase">Official Store</span>
                                    </div>
                                </a>
                                <a href="https://shopee.co.id/bahtera.food" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl hover:bg-orange-50 transition-colors">
                                    <div
                                        class="w-12 h-12 bg-orange-100 text-[#EE4D2D] rounded-xl flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </div>
                                    <div class="text-left"><span
                                            class="block font-black text-slate-800 uppercase text-xs tracking-wider">Shopee</span><span
                                            class="text-[9px] text-slate-400 font-bold uppercase">Official Store</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('katalog') }}"
                            class="flex-1 bg-slate-100 text-slate-500 py-6 rounded-2xl font-black uppercase tracking-[0.2em] text-sm text-center hover:bg-slate-200 transition-all active:scale-[0.98]">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->count() > 0)
        <section class="py-32 bg-slate-50/50 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-16">
                    <div class="text-center md:text-left">
                        <span class="text-bmb-orange font-black uppercase tracking-[0.4em] text-xs">Temukan Lebih
                            Banyak</span>
                        <h2 class="text-4xl font-black text-slate-900 uppercase tracking-tighter mt-3">Produk <span
                                class="text-bmb-blue">Terkait</span></h2>
                    </div>
                    <a href="{{ route('katalog') }}"
                        class="group flex items-center gap-3 text-bmb-blue font-black uppercase text-xs tracking-widest">
                        Lihat Semua Katalog <i
                            class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach ($relatedProducts as $item)
                        <a href="{{ route('produk.show', $item->slug) }}" class="group">
                            <div
                                class="bg-white rounded-[2.5rem] p-4 border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-3">
                                <div class="aspect-square rounded-[1.8rem] overflow-hidden mb-6 relative">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                            alt="{{ $item->nama_produk }}"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-bmb-blue/20 opacity-0 group-hover:opacity-100 transition-opacity">
                                    </div>
                                </div>
                                <div class="px-2 pb-2">
                                    <span
                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->kategori }}</span>
                                    <h3
                                        class="text-lg font-black text-slate-900 uppercase tracking-tight mt-1 group-hover:text-bmb-blue transition-colors truncate">
                                        {{ $item->nama_produk }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout.landing.bahtera.app>
