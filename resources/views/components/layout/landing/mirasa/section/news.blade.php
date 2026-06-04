@props(['berita'])

<section id="news" class="py-32 bg-[#FCFCFC] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 relative z-10">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-3">
                    <span class="w-12 h-[2px] bg-brand-red"></span>
                    <h2 class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px] italic">
                        @translate('Kabar Terkini')
                    </h2>
                </div>
                <h3
                    class="text-4xl md:text-5xl font-black font-display text-slate-900 leading-[1] tracking-tighter uppercase italic">
                    @translate('Wawasan') & <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                        @translate('Pembaruan Mirasa.')
                    </span>
                </h3>
            </div>

            <a href="{{ route('allBerita', ['lang' => request('lang', 'ID')]) }}"
                class="group inline-flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-brand-red transition-all">
                @translate('Lihat Semua Berita')
                <div
                    class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-red group-hover:bg-brand-red transition-all">
                    <i
                        class="fa-solid fa-arrow-right text-[10px] group-hover:text-white transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($berita as $item)
                <article
                    class="group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] transition-all duration-500 hover:-translate-y-2">

                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="{{ asset('storage/' . $item->gambar_utama) }}" alt="{{ $item->judul }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                        <div class="absolute top-6 left-6">
                            <span
                                class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-full text-[9px] font-black uppercase tracking-widest text-brand-red shadow-sm">
                                @translate($item->kategori)
                            </span>
                        </div>
                    </div>

                    <div class="p-8 space-y-4">
                        <div
                            class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-brand-red"></i>
                                <span>{{ $item->tanggal_publish->format('d M, Y') }}</span>
                            </div>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span>{{ $item->penulis }}</span>
                        </div>

                        <h4
                            class="text-xl font-black text-slate-900 leading-tight uppercase tracking-tighter italic group-hover:text-brand-red transition-colors line-clamp-2">
                            @translate($item->judul)
                        </h4>

                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 italic font-medium">
                            @translate($item->ringkasan)
                        </p>

                        <div class="pt-4">
                            <a href="{{ route('berita.show', ['slug' => $item->slug, 'lang' => request('lang', 'ID')]) }}"
                                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-brand-dark group/link">
                                @translate('Baca Selengkapnya')
                                <i
                                    class="fa-solid fa-chevron-right text-[8px] group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center p-12 text-center bg-slate-50 border border-dashed border-slate-200 rounded-[2.5rem]">
                    <div
                        class="w-16 h-16 mb-5 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center justify-center text-slate-400">
                        <i class="fa-regular fa-newspaper text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tighter italic">
                        @translate('Belum Ada Berita')
                    </h4>
                    <p class="text-slate-500 text-sm font-medium max-w-sm mx-auto leading-relaxed italic">
                        @translate('Berita atau artikel terbaru belum tersedia saat ini. Silakan kembali lagi nanti.')
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div
        class="absolute bottom-0 right-0 w-96 h-96 bg-brand-red/[0.02] rounded-full blur-3xl translate-y-1/2 translate-x-1/2">
    </div>
</section>
