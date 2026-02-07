<x-layout.landing.mirasa.app title="{{ $berita->judul }} - Mirasa Food">
    <x-layout.landing.mirasa.nav />

    <div id="progress-bar" class="fixed top-0 left-0 h-1 bg-brand-red z-[100] transition-all duration-150"
        style="width: 0%"></div>

    <header class="relative pt-24 pb-20 bg-white overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
            <div class="flex items-center justify-center gap-4 mb-8">
                <a href="{{ route('allBerita') }}"
                    class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-red transition-all">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span class="lang-id">Kembali</span><span class="lang-en">Back</span>
                </a>
                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                <span
                    class="px-4 py-1.5 bg-brand-red/10 rounded-full text-[9px] font-black uppercase tracking-[0.2em] text-brand-red">
                    {{ $berita->kategori }}
                </span>
            </div>

            <h1
                class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase italic leading-[1.1] mb-10">
                {{ $berita->judul }}
            </h1>

            <div
                class="flex flex-wrap items-center justify-center gap-8 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-brand-red border border-slate-200">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <span>{{ $berita->penulis }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-brand-red border border-slate-200">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <span>{{ $berita->tanggal_publish->format('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-brand-red border border-slate-200">
                        <i class="fa-regular fa-eye"></i>
                    </div>
                    <span>{{ $berita->jumlah_view ?? 0 }} Views</span>
                </div>
            </div>
        </div>
    </header>

    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-6">
            <div class="aspect-[21/9] rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-200">
                <img src="{{ asset('storage/' . $berita->gambar_utama) }}" alt="{{ $berita->judul }}"
                    class="w-full h-full object-cover">
            </div>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="max-w-3xl mx-auto px-6">

            <div class="mb-12">
                <p
                    class="text-xl md:text-2xl font-medium text-slate-600 leading-relaxed italic border-l-4 border-brand-red pl-8">
                    {{ $berita->ringkasan }}
                </p>
            </div>

            <div
                class="prose prose-slate prose-lg max-w-none 
                        prose-headings:font-black prose-headings:uppercase prose-headings:italic prose-headings:tracking-tighter
                        prose-p:text-slate-600 prose-p:leading-relaxed prose-p:font-medium
                        prose-img:rounded-[2rem] prose-blockquote:border-brand-red prose-strong:text-brand-dark">
                {!! $berita->konten !!}
            </div>

            <div
                class="mt-20 pt-10 border-t border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Share:</span>
                    <div class="flex gap-2">
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

                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-tag text-brand-red text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest text-brand-dark italic">
                        #{{ str_replace(' ', '', $berita->kategori) }}Industry
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-[#FCFCFC] border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between mb-12">
                <h3 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    <span class="lang-id">Berita <span class="text-brand-red">Terkait</span></span>
                    <span class="lang-en">Related <span class="text-brand-red">News</span></span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($relatedBerita as $related)
                    <a href="{{ route('berita.show', $related->slug) }}" class="group space-y-4">
                        <div class="aspect-[16/9] rounded-[2rem] overflow-hidden border border-slate-100">
                            <img src="{{ asset('storage/' . $related->gambar_utama) }}" alt="{{ $related->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="space-y-2">
                            <span
                                class="text-[9px] font-black uppercase text-brand-red tracking-widest">{{ $related->kategori }}</span>
                            <h4
                                class="text-lg font-black text-slate-900 uppercase italic leading-tight group-hover:text-brand-red transition-colors line-clamp-2">
                                {{ $related->judul }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        // Progress bar scroll logic
        window.onscroll = function() {
            let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            let scrolled = (winScroll / height) * 100;
            document.getElementById("progress-bar").style.width = scrolled + "%";
        };
    </script>
</x-layout.landing.mirasa.app>
