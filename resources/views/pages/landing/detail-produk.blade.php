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
                                <a href="https://wa.me/6285120666420?text=Halo%20Bahtera,%20saya%20tertarik%20dengan%20produk%20{{ $product->nama_produk }}"
                                    target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl hover:bg-green-50 transition-colors">
                                    <div
                                        class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl">
                                        <i class="fa-brands fa-whatsapp"></i></div>
                                    <div class="text-left"><span
                                            class="block font-black text-slate-800 uppercase text-xs tracking-wider">WhatsApp</span><span
                                            class="text-[9px] text-slate-400 font-bold uppercase">Respon Cepat</span>
                                    </div>
                                </a>
                                <a href="https://shopee.co.id/bahtera.food" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl hover:bg-orange-50 transition-colors">
                                    <div
                                        class="w-12 h-12 bg-orange-100 text-[#EE4D2D] rounded-xl flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-bag-shopping"></i></div>
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
