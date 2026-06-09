<x-layout.landing.mirasa.app title="Semua Berita - Mirasa Food">
    <x-layout.landing.mirasa.nav />

    <section class="relative pt-40 pb-20 bg-[#0A0F1A] overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-red/[0.05] rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/5 border border-white/10 rounded-full mb-6">
                <span class="w-2 h-2 rounded-full bg-brand-red animate-pulse"></span>
                <h1 class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px]">
                    @translate('Ruang Berita')
                </h1>
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase italic leading-none">
                @translate('Kabar Terkini') & <br>
                <span class="text-brand-red">@translate('Wawasan Industri')</span>
            </h2>
        </div>
    </section>

    <section class="py-10 bg-[#FCFCFC] min-h-screen relative">
        <div class="max-w-5xl mx-auto px-6 relative z-10">

            @if ($berita->isEmpty())
                <div class="text-center py-20 bg-white rounded-[3rem] border border-slate-100 shadow-sm">
                    <i class="fa-solid fa-newspaper text-slate-200 text-6xl mb-6"></i>
                    <p class="text-slate-400 italic font-medium">@translate('Belum ada berita yang diterbitkan saat ini.')</p>
                </div>
            @else
                <div class="flex flex-col gap-12">
                    @foreach ($berita as $item)
                        <article
                            class="group bg-white rounded-[3rem] border border-slate-100 overflow-hidden hover:shadow-[0_40px_80px_-15px_rgba(0,0,0,0.08)] transition-all duration-500 flex flex-col md:flex-row items-stretch">

                            <div class="md:w-2/5 relative overflow-hidden">
                                <img src="{{ asset('storage/' . $item->gambar_utama) }}" alt="{{ $item->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 min-h-[250px] md:min-h-full">

                                <div class="absolute top-6 left-6">
                                    <span
                                        class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-full text-[9px] font-black uppercase tracking-widest text-brand-red shadow-sm">
                                        @translate($item->kategori)
                                    </span>
                                </div>
                            </div>

                            <div class="md:w-3/5 p-8 md:p-12 flex flex-col justify-center space-y-5">
                                <div
                                    class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-brand-red"></i>
                                        <span>{{ $item->tanggal_publish->format('d M, Y') }}</span>
                                    </div>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-user text-brand-red"></i>
                                        <span>{{ $item->penulis }}</span>
                                    </div>
                                </div>

                                <h4
                                    class="text-2xl md:text-3xl font-black text-slate-900 leading-tight uppercase tracking-tighter italic group-hover:text-brand-red transition-colors">
                                    <a href="{{ route('berita.show', ['slug' => $item->slug, 'lang' => request('lang', 'ID')]) }}">
                                        @translate($item->judul)
                                    </a>
                                </h4>

                                <p
                                    class="text-slate-500 text-sm md:text-base leading-relaxed italic font-medium line-clamp-3">
                                    @translate($item->ringkasan)
                                </p>

                                <div class="pt-4 flex items-center justify-between">
                                    <a href="{{ route('berita.show', ['slug' => $item->slug, 'lang' => request('lang', 'ID')]) }}"
                                        class="inline-flex items-center gap-3 text-[11px] font-black uppercase tracking-widest text-brand-dark group/link">
                                        @translate('Baca Selengkapnya')
                                        <div
                                            class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover/link:border-brand-red group-hover/link:bg-brand-red transition-all">
                                            <i
                                                class="fa-solid fa-chevron-right text-[8px] group-hover/link:text-white transition-transform group-hover/link:translate-x-1"></i>
                                        </div>
                                    </a>

                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">
                                        {{ $item->jumlah_view ?? 0 }} @translate('Dilihat')
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-20">
                    {{ $berita->links('vendor.pagination.custom') }}
                </div>
            @endif

        </div>

        <div
            class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-slate-100/50 to-transparent pointer-events-none">
        </div>
    </section>

</x-layout.landing.mirasa.app>