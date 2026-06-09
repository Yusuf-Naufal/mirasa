<x-layout.landing.mirasa.app title="{{ $berita->judul }} - Mirasa Food">
    <x-layout.landing.mirasa.nav />

    <div id="progress-bar" class="fixed top-0 left-0 h-1 bg-brand-red z-[100] transition-all duration-150"
        style="width: 0%"></div>

    <header class="relative pt-28 pb-20 bg-white overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
            <div class="flex items-center justify-center gap-4 mb-8">
                {{-- Link Kembali dengan parameter bahasa aktif --}}
                <a href="{{ route('allBerita', ['lang' => request('lang', 'ID')]) }}"
                    class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-red transition-all">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    @translate('Kembali')
                </a>
                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                <span
                    class="px-4 py-1.5 bg-brand-red/10 rounded-full text-[9px] font-black uppercase tracking-[0.2em] text-brand-red">
                    @translate($berita->kategori)
                </span>
            </div>

            <h1
                class="text-4xl md:text-6xl font-black text-slate-900 leading-[1.1] uppercase tracking-tighter italic mb-8">
                @translate($berita->judul)
            </h1>

            <div
                class="flex items-center justify-center gap-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-brand-red"></i>
                    <span>{{ $berita->tanggal_publish->format('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-user text-brand-red"></i>
                    <span>{{ $berita->penulis }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-eye text-brand-red"></i>
                    <span>{{ $berita->jumlah_view }} @translate('Dilihat')</span>
                </div>
            </div>
        </div>
    </header>

    <article class="pb-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            {{-- Featured Image --}}
            <div class="aspect-[21/9] rounded-[3rem] overflow-hidden border border-slate-100 shadow-2xl mb-10">
                <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->judul }}"
                    class="w-full h-full object-cover">
            </div>

            <section class="py-20 bg-white">
                <div class="max-w-3xl mx-auto px-6">

                    {{-- Ringkasan Berita --}}
                    <div class="mb-12">
                        <p
                            class="text-xl md:text-2xl font-medium text-slate-600 leading-relaxed italic border-l-4 border-brand-red pl-8">
                            @translate($berita->ringkasan)
                        </p>
                    </div>

                    <div
                        class="prose prose-slate prose-lg max-w-none 
                    prose-headings:font-black prose-headings:uppercase prose-headings:italic prose-headings:tracking-tighter
                    prose-p:text-slate-600 prose-p:leading-relaxed prose-p:font-medium
                    prose-img:rounded-[2rem] prose-blockquote:border-brand-red prose-strong:text-brand-dark">
                        @translate($berita->konten)
                    </div>

                    <div
                        class="mt-20 pt-10 border-t border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <div class="flex items-center gap-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                @translate('Bagikan'):
                            </span>
                            <div class="flex gap-2">
                                {{-- WhatsApp --}}
                                <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-green-500 hover:text-white transition-all">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                                {{-- X (Twitter) --}}
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-sky-500 hover:text-white transition-all">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                                {{-- Facebook --}}
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Tag Kategori --}}
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-tag text-brand-red text-xs"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-brand-dark italic">
                                #@translate(str_replace(' ', '', $berita->kategori))Industry
                            </span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>

    {{-- Related Posts Section --}}
    <section class="py-24 bg-[#FCFCFC] border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-4 mb-12">
                <span class="w-12 h-[2px] bg-brand-red"></span>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter">
                    @translate('Berita Terkait')
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($relatedBerita as $related)
                    <a href="{{ route('berita.show', ['slug' => $related->slug, 'lang' => request('lang', 'ID')]) }}"
                        class="group space-y-4">
                        <div class="aspect-[16/9] rounded-[2rem] overflow-hidden border border-slate-100">
                            <img src="{{ asset('storage/' . $related->gambar_utama) }}" alt="{{ $related->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="space-y-2">
                            <span class="text-[9px] font-black uppercase text-brand-red tracking-widest">
                                @translate($related->kategori)
                            </span>
                            <h4
                                class="text-lg font-black text-slate-900 uppercase italic leading-tight group-hover:text-brand-red transition-colors line-clamp-2">
                                @translate($related->judul)
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById("progress-bar").style.width = scrolled + "%";
        };
    </script>
</x-layout.landing.mirasa.app>
