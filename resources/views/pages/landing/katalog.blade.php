<x-layout.landing.bahtera.app title="Katalog Artisan - Bahtera Mandiri Bersama">
    <x-layout.landing.bahtera.nav />

    <section class="relative bg-[#001540] pt-32 pb-24 lg:pt-48 lg:pb-40 overflow-hidden">
        <div class="absolute inset-0">
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-600/20 rounded-full filter blur-[120px] animate-pulse">
            </div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-orange-600/10 rounded-full filter blur-[120px] animate-pulse"
                style="animation-delay: 2s"></div>
            <div class="absolute inset-0 opacity-10"
                style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png')"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <nav class="flex justify-center mb-8" aria-label="Breadcrumb">
                <ol
                    class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black tracking-[0.3em] uppercase text-blue-400/60">
                    <li><a href="/" class="hover:text-orange-400 transition-colors">Beranda</a></li>
                    <li><span class="mx-2 text-slate-700">/</span></li>
                    <li class="text-orange-400">Katalog</li>
                </ol>
            </nav>
            <h1 class="text-6xl md:text-8xl font-black text-white uppercase tracking-tighter mb-6 leading-none">
                Daftar <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Produk</span>
            </h1>
            <p class="text-slate-400 mt-4 max-w-2xl mx-auto text-base md:text-lg leading-relaxed font-medium">
                Menghadirkan kelezatan umbi nusantara yang diproses dengan standar emas untuk penikmat camilan kelas
                dunia.
            </p>
        </div>
    </section>

    <section class="sticky top-0 z-40 -mt-10">
        <div class="max-w-5xl mx-auto px-6">
            <div
                class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-slate-100 rounded-[2.5rem] p-3 md:p-4 backdrop-blur-md bg-white/95">
                <form action="{{ route('katalog') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1 group">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari varian atau nama produk..."
                            class="w-full pl-14 pr-6 py-4 rounded-2xl border-2 border-slate-50 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-orange-500/5 focus:border-orange-500 outline-none transition-all font-bold text-slate-700">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-orange-500 transition-colors"></i>
                    </div>

                    <div class="flex gap-2">
                        <div class="relative min-w-[160px]">
                            <select name="filter" onchange="this.form.submit()"
                                class="w-full appearance-none px-6 py-4 rounded-2xl border-2 border-slate-50 bg-slate-50 font-black text-xs uppercase tracking-widest text-slate-700 outline-none hover:border-orange-500 transition-all cursor-pointer">
                                <option value="semua" {{ request('filter') == 'semua' ? 'selected' : '' }}>Semua
                                </option>
                                <option value="unggulan" {{ request('filter') == 'unggulan' ? 'selected' : '' }}>⭐
                                    Unggulan</option>
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 text-[10px] pointer-events-none"></i>
                        </div>

                        @if (request('search') || request('filter'))
                            <a href="{{ route('katalog') }}"
                                class="flex items-center justify-center px-4 md:px-5 rounded-2xl bg-slate-900 text-white hover:bg-orange-600 transition-all shadow-md active:scale-95"
                                title="Reset Filter">
                                <i class="fa-solid fa-rotate-left text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="py-24 min-h-screen relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative">
            @if ($products->isEmpty())
                <div class="text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <i class="fa-solid fa-box-open text-6xl text-slate-200 mb-6"></i>
                    <h3 class="text-2xl font-black text-slate-900 uppercase">Produk Tidak Ditemukan</h3>
                    <p class="text-slate-500 mt-2 mb-8">Coba gunakan kata kunci lain atau reset filter.</p>
                    <a href="{{ route('katalog') }}"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-orange-500 transition-all">
                        Reset Pencarian
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12 md:gap-x-10 md:gap-y-20">
                    @foreach ($products as $product)
                        <div class="group flex flex-col">

                            <div
                                class="relative aspect-[4/5] rounded-[2.5rem] bg-slate-50 p-2 border border-slate-100 overflow-hidden transition-all duration-500 group-hover:shadow-2xl group-hover:shadow-blue-900/10 group-hover:-translate-y-2">


                                <div class="absolute top-4 right-4 z-20">
                                    <span
                                        class="bg-white/90 backdrop-blur-md text-slate-900 text-[8px] md:text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest shadow-sm border border-slate-100">
                                        {{ $product->kategori ?? 'Camilan' }}
                                    </span>
                                </div>

                                <div class="relative h-full w-full rounded-[2rem] overflow-hidden bg-white">
                                    @if ($product->foto)
                                        <img src="{{ asset('storage/' . $product->foto) }}"
                                            alt="{{ $product->nama_produk }}"
                                            class="w-full h-full object-cover transform transition-transform duration-1000 group-hover:scale-110">
                                    @else
                                        <div
                                            class="w-full h-full flex flex-col items-center justify-center bg-slate-50 text-slate-300">
                                            <i class="fa-solid fa-image text-4xl mb-2 opacity-20"></i>
                                            <span class="text-[8px] font-black uppercase tracking-widest">No
                                                Image</span>
                                        </div>
                                    @endif

                                    <div
                                        class="absolute inset-0 bg-blue-900/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center p-6">
                                        <a href="{{ route('produk.show', $product->slug) }}"
                                            class="bg-white text-slate-900 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-orange-500 hover:text-white transition-all transform scale-90 group-hover:scale-100">
                                            Rincian Produk
                                        </a>
                                    </div>
                                </div>

                                @if ($product->is_unggulan)
                                    <div class="absolute bottom-6 left-6 z-10">
                                        <div
                                            class="bg-orange-500 text-white text-[8px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-lg shadow-orange-500/40 flex items-center gap-2">
                                            <i class="fa-solid fa-star text-[7px]"></i>
                                            Terlaris
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-8 text-center sm:text-left px-2">
                                <h3
                                    class="text-xl md:text-2xl font-black text-slate-900 uppercase tracking-tighter leading-tight group-hover:text-orange-500 transition-colors mb-2">
                                    {{ $product->nama_produk }}
                                </h3>

                                <div class="flex items-center justify-center sm:justify-start gap-2 mb-3">
                                    <div class="h-[2px] w-3 bg-blue-600 rounded-full"></div>
                                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                        Rasa: {{ $product->rasa ?? 'Original' }}
                                    </span>
                                </div>

                                <p class="text-slate-400 text-xs font-medium leading-relaxed line-clamp-2">
                                    {{ $product->deskripsi }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-32 flex justify-center">
                    <div class="inline-flex p-2 bg-slate-50 rounded-[2rem] border border-slate-100 shadow-inner">
                        {{ $products->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    <footer class="bg-slate-50 py-20 border-t border-slate-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <div class="flex justify-center mb-10">
                <img src="{{ asset('assets/logo/BMB-logo.webp') }}"
                    class="h-12 md:h-16 opacity-30 grayscale hover:grayscale-0 transition-all duration-500">
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.6em] mb-6">
                © {{ date('Y') }} {{ $perusahaan->nama_perusahaan ?? 'BAHTERA MANDIRI BERSAMA' }}
            </p>
            <div class="h-[3px] w-12 bg-blue-600 mx-auto rounded-full mb-8"></div>
            <p class="text-slate-400 text-sm mt-6 font-bold tracking-tight">Kualitas Rasa Utama - Dari Umbi Pilihan
                Untuk Dunia</p>
        </div>
    </footer>
</x-layout.landing.bahtera.app>
