@props(['products'])

<section id="products" class="py-20 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-bmb-orange font-black uppercase tracking-[0.4em] text-sm">Koleksi Kami</span>
            <h2 class="text-4xl md:text-6xl font-black text-slate-900 mt-4 tracking-tighter uppercase">
                Varian <span class="text-bmb-blue">Terfavorit</span>
            </h2>
            <div class="w-24 h-2 bg-bmb-orange mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="swiper product-swiper !pb-12">
            <div class="swiper-wrapper">
                @forelse($products as $product)
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <div
                                class="aspect-[4/5] bg-slate-50 rounded-[2.5rem] mb-6 overflow-hidden relative border border-slate-100 group-hover:shadow-2xl transition-all duration-500">

                                @if ($product->is_unggulan)
                                    <div class="absolute top-6 left-6 z-10">
                                        <span
                                            class="bg-bmb-orange text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg">
                                            Top Pick
                                        </span>
                                    </div>
                                @endif

                                @if ($product->foto)
                                    <img src="{{ asset('storage/' . $product->foto) }}"
                                        alt="{{ $product->nama_produk }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                        loading="lazy">
                                @else
                                    <div
                                        class="absolute inset-0 flex items-center justify-center text-slate-300 font-bold uppercase tracking-widest text-center p-4">
                                        No Image<br>{{ $product->nama_produk }}
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-bmb-blue/90 via-bmb-blue/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                                    <a href="{{ route('produk.show', $product->slug) }}"
                                        class="w-full bg-white text-bmb-blue py-4 rounded-2xl font-black uppercase text-xs tracking-widest text-center hover:bg-bmb-orange hover:text-white transition-colors shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                        Detail Produk
                                    </a>
                                </div>
                            </div>

                            <div class="text-center px-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                                    {{ $product->kategori ?? 'Camilan' }}
                                </span>

                                <h3
                                    class="text-xl font-black text-slate-900 uppercase tracking-tight line-clamp-1 mt-1">
                                    {{ $product->nama_produk }}
                                </h3>

                                <div class="flex items-center justify-center gap-2 mt-2">
                                    <div class="h-px w-4 bg-slate-200"></div>
                                    <p class="text-bmb-blue font-bold uppercase text-[10px] tracking-widest">
                                        Rasa: {{ $product->rasa ?? 'Original' }}
                                    </p>
                                    <div class="h-px w-4 bg-slate-200"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-20">
                        <p class="text-slate-400 italic font-bold uppercase tracking-widest">
                            Maaf, Produk belum tersedia.
                        </p>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
