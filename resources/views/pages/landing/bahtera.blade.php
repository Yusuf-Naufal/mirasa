<x-layout.landing.bahtera.app>

    <x-layout.landing.bahtera.nav />

    <section id="home" class="relative pt-24 pb-16 lg:pt-40 lg:pb-32 hero-pattern overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-50/50 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-orange-50/50 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">

            <div class="relative max-w-md mx-auto order-1 lg:order-2">
                <div
                    class="absolute -bottom-4 -right-4 z-30 bg-white px-5 py-3 rounded-2xl shadow-lg border border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 bg-bmb-orange/10 rounded-full flex items-center justify-center text-bmb-orange">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-slate-800 uppercase tracking-tight">Pilihan Utama Ekspor</span>
                </div>

                <div class="relative z-10 bg-white p-3 rounded-[3rem] shadow-2xl border border-slate-50">
                    <div class="relative bg-slate-100 aspect-[4/5] rounded-[2.5rem] overflow-hidden">
                        <img src="{{ asset('assets/foto/bahtera-profile.webp') }}" alt="Singkong Artisan Premium"
                            class="w-full h-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 to-transparent"></div>
                    </div>
                </div>

                <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-bmb-blue/5 rounded-full -z-10"></div>
            </div>

            <div class="z-10 text-center lg:text-left order-2 lg:order-1">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 bg-bmb-blue/5 rounded-full mb-6 border border-bmb-blue/10">
                    <span class="text-bmb-blue font-bold text-[11px] uppercase tracking-[0.2em]">Camilan Autentik
                        Indonesia</span>
                </div>

                <h1
                    class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 leading-tight mb-6 tracking-tight">
                    Standar Baru <br>
                    <span class="text-bmb-orange">Camilan Tradisional.</span>
                </h1>

                <p class="text-lg text-slate-600 mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Menghadirkan cita rasa asli melalui kurasi umbi pilihan dan teknik pengolahan modern
                    oleh
                    <span class="font-bold text-slate-900 border-b-2 border-bmb-orange/30">PT Mirasa Food
                        Industri</span>.
                </p>

                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#produk"
                        class="bg-bmb-blue text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-blue-800 transition-colors shadow-lg shadow-blue-900/10 text-center">
                        Lihat Produk
                    </a>
                    <a href="#kontak"
                        class="bg-white border-2 border-slate-200 text-slate-700 px-10 py-4 rounded-xl font-bold text-lg hover:bg-slate-50 transition-colors text-center">
                        Hubungi Kami
                    </a>
                </div>

                <div class="mt-12 flex items-center justify-center lg:justify-start gap-6 text-slate-400">
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="text-slate-900 font-bold text-sm">Kualitas</span>
                        <span class="text-[10px] uppercase tracking-widest font-medium">Premium</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="text-slate-900 font-bold text-sm">Bahan</span>
                        <span class="text-[10px] uppercase tracking-widest font-medium">Alami</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="text-slate-900 font-bold text-sm">Pengiriman</span>
                        <span class="text-[10px] uppercase tracking-widest font-medium">Global</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="about" class="py-16 md:py-24 lg:py-32 bg-bmb-blue relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div
                class="absolute top-10 left-10 w-48 h-48 md:w-64 md:h-64 bg-white rounded-full blur-[80px] md:blur-[100px]">
            </div>
            <div
                class="absolute bottom-10 right-10 w-72 h-72 md:w-96 md:h-96 bg-bmb-orange rounded-full blur-[100px] md:blur-[120px]">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <div class="order-2 lg:order-1 text-center lg:text-left">
                    <h2
                        class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-6 md:mb-8 leading-[1.2] tracking-tight uppercase">
                        Dedikasi Penuh <br>
                        <span class="text-bmb-orange italic">Untuk Kualitas</span> Terbaik.
                    </h2>

                    <div class="space-y-4 max-w-2xl mx-auto lg:mx-0">
                        <div
                            class="group flex flex-col sm:flex-row items-center sm:items-start gap-4 md:gap-5 p-5 md:p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl md:rounded-3xl hover:bg-white/15 transition-all duration-300">
                            <div
                                class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-white rounded-xl md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl shadow-lg transform group-hover:scale-110 transition-transform">
                                🌱
                            </div>
                            <div class="text-white">
                                <h4 class="font-bold text-base md:text-lg mb-1">Pemberdayaan Lokal</h4>
                                <p class="text-blue-100/80 leading-relaxed text-sm md:text-base">
                                    Kami merangkul petani lokal secara langsung guna memastikan setiap umbi yang kami
                                    olah memiliki kualitas kesegaran yang terjaga.
                                </p>
                            </div>
                        </div>

                        <div
                            class="group flex flex-col sm:flex-row items-center sm:items-start gap-4 md:gap-5 p-5 md:p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl md:rounded-3xl hover:bg-white/15 transition-all duration-300">
                            <div
                                class="flex-shrink-0 w-12 h-12 md:w-14 md:h-14 bg-white rounded-xl md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl shadow-lg transform group-hover:scale-110 transition-transform">
                                🛡️
                            </div>
                            <div class="text-white">
                                <h4 class="font-bold text-base md:text-lg mb-1">Keamanan Pangan Teruji</h4>
                                <p class="text-blue-100/80 leading-relaxed text-sm md:text-base">
                                    Berada di bawah pengawasan ketat <span class="text-white font-bold">PT Mirasa Food
                                        Industri</span> untuk memenuhi standar keamanan pangan internasional.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 flex justify-center">
                    <div class="relative w-full sm:max-w-sm px-4">
                        <div class="absolute inset-0 bg-bmb-orange rounded-full blur-[60px] md:blur-[80px] opacity-30">
                        </div>

                        <div
                            class="relative bg-white pt-10 pb-8 md:pt-12 md:pb-10 px-6 md:px-8 rounded-[2.5rem] md:rounded-[3.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] text-center border border-slate-100">
                            <div
                                class="absolute -top-4 md:-top-6 left-1/2 -translate-x-1/2 bg-bmb-orange text-white px-4 md:px-6 py-1.5 md:py-2 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] shadow-lg whitespace-nowrap">
                                Keunggulan Kami
                            </div>

                            <p
                                class="text-slate-400 text-[10px] md:text-xs font-bold uppercase mb-3 md:mb-4 tracking-widest">
                                Bagian Utama Dari</p>

                            <h3
                                class="text-xl sm:text-2xl md:text-3xl font-black text-bmb-blue tracking-tighter leading-none">
                                MIRASA FOOD <br>
                                <span class="text-bmb-orange text-base md:text-lg uppercase">Industri</span>
                            </h3>

                            <div class="w-10 md:w-12 h-1 bg-slate-100 mx-auto my-5 md:my-6 rounded-full"></div>

                            <p class="text-slate-500 font-medium italic text-[13px] md:text-sm leading-relaxed">
                                "Dedikasi mengolah umbi menjadi mahakarya rasa yang mendunia."
                            </p>
                        </div>

                        <div
                            class="absolute -bottom-4 md:-bottom-6 -right-2 bg-bmb-blue p-3 md:p-4 rounded-xl md:rounded-2xl border-2 md:border-4 border-white shadow-xl hidden xs:block">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="proses" class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl text-center lg:text-left">
                    <span class="text-bmb-orange font-black uppercase tracking-[0.3em] text-sm">Standar Produksi</span>
                    <h2
                        class="text-3xl md:text-5xl font-black text-slate-900 mt-4 tracking-tighter uppercase leading-tight">
                        Dibuat dengan <span class="text-bmb-blue text-nowrap">Ketelitian Tinggi</span>
                    </h2>
                </div>
                <div class="hidden lg:block pb-2">
                    <p class="text-slate-500 font-medium max-w-xs text-right italic">
                        "Setiap langkah diawasi ketat untuk memastikan kerenyahan yang sempurna."
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="relative p-8 rounded-[3rem] bg-slate-50 border border-slate-100 group hover:bg-bmb-blue transition-colors duration-500">
                    <div
                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-md group-hover:scale-110 transition-transform duration-500">
                        <span class="text-2xl font-black text-bmb-blue">01</span>
                    </div>
                    <h4
                        class="text-xl font-black text-slate-900 mb-4 group-hover:text-white transition-colors uppercase tracking-tight">
                        Seleksi Ketat</h4>
                    <p
                        class="text-slate-600 leading-relaxed group-hover:text-blue-100 transition-colors text-sm md:text-base">
                        Hanya umbi terbaik dengan usia panen tepat yang dipilih langsung dari petani mitra kami untuk
                        tekstur yang optimal.
                    </p>
                </div>

                <div
                    class="relative p-8 rounded-[3rem] bg-slate-50 border border-slate-100 group hover:bg-bmb-blue transition-colors duration-500">
                    <div
                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-md group-hover:scale-110 transition-transform duration-500">
                        <span class="text-2xl font-black text-bmb-blue">02</span>
                    </div>
                    <h4
                        class="text-xl font-black text-slate-900 mb-4 group-hover:text-white transition-colors uppercase tracking-tight">
                        Proses Modern</h4>
                    <p
                        class="text-slate-600 leading-relaxed group-hover:text-blue-100 transition-colors text-sm md:text-base">
                        Menggunakan teknik pemotongan presisi dan penggorengan suhu rendah untuk menjaga nutrisi dan
                        rasa asli singkong.
                    </p>
                </div>

                <div
                    class="relative p-8 rounded-[3rem] bg-slate-50 border border-slate-100 group hover:bg-bmb-blue transition-colors duration-500">
                    <div
                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-md group-hover:scale-110 transition-transform duration-500">
                        <span class="text-2xl font-black text-bmb-blue">03</span>
                    </div>
                    <h4
                        class="text-xl font-black text-slate-900 mb-4 group-hover:text-white transition-colors uppercase tracking-tight">
                        Kualitas Akhir</h4>
                    <p
                        class="text-slate-600 leading-relaxed group-hover:text-blue-100 transition-colors text-sm md:text-base">
                        Melewati dua tahap pengecekan kualitas sebelum dikemas secara higienis dalam wadah kedap udara
                        standar ekspor.
                    </p>
                </div>
            </div>
        </div>
    </section>

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

    <section class="py-12 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div
                class="relative bg-slate-900 rounded-[2.5rem] md:rounded-[3.5rem] p-8 sm:p-12 md:p-20 overflow-hidden flex flex-col items-center text-center shadow-[0_40px_100px_-20px_rgba(15,23,42,0.3)]">

                <div
                    class="absolute top-0 right-0 w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-bmb-orange to-orange-600 rounded-full blur-[80px] md:blur-[120px] opacity-20 -mr-32 -mt-32 md:-mr-40 md:-mt-40">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-64 h-64 md:w-80 md:h-80 bg-gradient-to-tr from-bmb-blue to-blue-600 rounded-full blur-[80px] md:blur-[120px] opacity-20 -ml-32 -mb-32 md:-ml-40 md:-mb-40">
                </div>

                <div class="relative z-10 w-full">
                    <span
                        class="inline-block px-3 py-1.5 md:px-4 md:py-2 bg-white/5 backdrop-blur-md border border-white/10 rounded-full text-bmb-orange text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] md:tracking-[0.3em] mb-6 md:mb-8">
                        Penawaran Terbatas
                    </span>

                    <h2
                        class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 md:mb-8 leading-[1.2] md:leading-[1.1] tracking-tighter uppercase">
                        Siap Merasakan <br class="hidden sm:block">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-bmb-orange to-orange-400">Kelezatan
                            Autentik?</span>
                    </h2>

                    <p
                        class="text-slate-400 max-w-2xl mx-auto mb-10 md:mb-12 text-sm sm:text-base md:text-lg lg:text-xl leading-relaxed font-medium">
                        Nikmati sensasi keripik artisan langsung di rumah Anda. Pesan sekarang atau hubungi admin kami
                        untuk kemitraan eksklusif.
                    </p>

                    <div
                        class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6 w-full max-w-2xl mx-auto">

                        <div class="relative w-full sm:flex-[1.2]" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="w-full bg-bmb-blue hover:bg-blue-700 text-white flex items-center justify-center gap-3 md:gap-4 py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase tracking-[0.1em] md:tracking-[0.15em] text-xs md:text-sm transition-all duration-300 shadow-xl active:scale-[0.96] group">
                                <i class="fa-solid fa-cart-shopping transition-transform group-hover:rotate-12"></i>
                                <span>Beli Sekarang</span>
                                <i class="fa-solid fa-chevron-down text-[9px] md:text-[10px] transition-transform duration-500"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute bottom-full mb-4 left-0 w-full bg-white rounded-2xl md:rounded-[2.5rem] shadow-2xl border border-white/20 overflow-hidden z-50 p-2 md:p-3 space-y-1 md:space-y-2">

                                <a href="https://www.tokopedia.com/bahterafood" target="_blank"
                                    class="flex items-center gap-3 md:gap-5 p-3 md:p-5 rounded-xl md:rounded-3xl hover:bg-green-50 transition-all group/item">
                                    <div
                                        class="w-10 h-10 md:w-14 md:h-14 bg-green-100 text-green-600 rounded-lg md:rounded-2xl flex items-center justify-center text-lg md:text-2xl">
                                        <i class="fa-solid fa-store"></i>
                                    </div>
                                    <div class="text-left">
                                        <span
                                            class="block font-black text-slate-900 uppercase text-[10px] md:text-sm tracking-tight">Tokopedia</span>
                                        <span
                                            class="text-[8px] md:text-[10px] text-green-600 font-bold uppercase tracking-widest">Toko
                                            Resmi</span>
                                    </div>
                                </a>

                                <a href="https://shopee.co.id/bahtera.food" target="_blank"
                                    class="flex items-center gap-3 md:gap-5 p-3 md:p-5 rounded-xl md:rounded-3xl hover:bg-orange-50 transition-all group/item">
                                    <div
                                        class="w-10 h-10 md:w-14 md:h-14 bg-orange-100 text-[#EE4D2D] rounded-lg md:rounded-2xl flex items-center justify-center text-lg md:text-2xl">
                                        <i class="fa-solid fa-bag-shopping"></i>
                                    </div>
                                    <div class="text-left">
                                        <span
                                            class="block font-black text-slate-900 uppercase text-[10px] md:text-sm tracking-tight">Shopee</span>
                                        <span
                                            class="text-[8px] md:text-[10px] text-orange-600 font-bold uppercase tracking-widest">Toko
                                            Resmi</span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <a href="https://wa.me/6285124666420" target="_blank"
                            class="w-full sm:flex-1 bg-white/10 backdrop-blur-md border border-white/20 text-white px-6 py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase text-[11px] md:text-sm tracking-widest hover:bg-white hover:text-slate-900 transition-all duration-300 flex items-center justify-center gap-3 group">
                            <i class="fa-brands fa-whatsapp text-lg md:text-xl text-[#25D366]"></i>
                            <span>Admin</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="contact" class="bg-slate-900 text-white pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-4 gap-12 mb-20">
                <div class="lg:col-span-2">
                    <h4 class="text-3xl font-black mb-6 uppercase tracking-tighter">Bahtera Mandiri Bersama</h4>
                    <p class="text-slate-400 max-w-sm leading-relaxed mb-8">
                        Membawa tradisi camilan Nusantara ke tingkat yang lebih tinggi. Berfokus pada pengolahan umbi
                        premium yang berkelanjutan dan higienis.
                    </p>
                    <div class="space-y-6">
                        <div>
                            <h5 class="text-bmb-orange font-black uppercase tracking-widest text-[10px] mb-4">Media
                                Sosial</h5>
                            <div class="flex flex-wrap gap-3">
                                <a href="https://www.instagram.com/bahtera.food"
                                    class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#E1306C] hover:border-[#E1306C] transition-all duration-300 group"
                                    title="Instagram">
                                    <i
                                        class="fa-brands fa-instagram text-lg text-slate-400 group-hover:text-white"></i>
                                </a>
                                <a href="https://www.tiktok.com/@bahterafood"
                                    class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#000000] hover:border-slate-700 transition-all duration-300 group"
                                    title="TikTok">
                                    <i class="fa-brands fa-tiktok text-lg text-slate-400 group-hover:text-white"></i>
                                </a>
                                <a href="https://www.facebook.com/share/1DHV9cZbmh/"
                                    class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#1877F2] hover:border-[#1877F2] transition-all duration-300 group"
                                    title="Facebook">
                                    <i
                                        class="fa-brands fa-facebook-f text-lg text-slate-400 group-hover:text-white"></i>
                                </a>
                                <a href="https://wa.me/6285124666420"
                                    class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#25D366] hover:border-[#25D366] transition-all duration-300 group"
                                    title="WhatsApp">
                                    <i class="fa-brands fa-whatsapp text-xl text-slate-400 group-hover:text-white"></i>
                                </a>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-bmb-orange font-black uppercase tracking-widest text-[10px] mb-4">Beli
                                Online</h5>
                            <div class="flex flex-wrap gap-3">
                                <a href="https://shopee.co.id/bahtera.food"
                                    class="h-10 px-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-2 hover:bg-[#EE4D2D] hover:border-[#EE4D2D] transition-all duration-300 group">
                                    <i class="fa-solid fa-bag-shopping text-slate-400 group-hover:text-white"></i>
                                    <span class="text-xs font-bold text-slate-300 group-hover:text-white">Shopee</span>
                                </a>
                                <a href="https://www.tokopedia.com/bahterafood"
                                    class="h-10 px-4 rounded-xl bg-white/5 border border-white/10 flex items-center gap-2 hover:bg-[#03AC0E] hover:border-[#03AC0E] transition-all duration-300 group">
                                    <i class="fa-solid fa-cart-shopping text-slate-400 group-hover:text-white"></i>
                                    <span
                                        class="text-xs font-bold text-slate-300 group-hover:text-white">Tokopedia</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h5 class="text-bmb-orange font-black uppercase tracking-widest text-xs mb-8">Navigasi</h5>
                    <ul class="space-y-4 text-slate-300 font-semibold">
                        <li><a href="#about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#products" class="hover:text-white transition">Produk</a></li>
                        <li><a href="{{ route('katalog') }}" class="hover:text-white transition">Katalog</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-bmb-orange font-black uppercase tracking-widest text-xs mb-8">Lokasi Kami</h5>

                    <div class="mb-6">
                        <p class="text-slate-300 font-semibold leading-relaxed">
                            JL. Munggur No.1, RT.01/RW.05, Kadipuro, Mungkid, Kec. Mungkid, Kabupaten Magelang, Jawa
                            Tengah 56512
                        </p>
                    </div>

                    <a href="https://maps.app.goo.gl/jecRLDaMR1CqUW4U7" target="_blank"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-bmb-orange hover:border-bmb-orange hover:text-white transition-all duration-300 group">
                        <i class="fa-solid fa-location-dot text-bmb-orange group-hover:text-white"></i>
                        <span class="text-sm font-bold">Lihat di Google Maps</span>
                    </a>
                </div>
            </div>
            <div
                class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-6 text-center">
                <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest">© {{ date('Y') }} CV
                    BAHTERA MANDIRI
                    BERSAMA. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-3">
                    <span class="text-slate-500 font-bold uppercase text-[10px] tracking-widest">Anggota Dari</span>
                    <a href="http://mirasa.nopaldev.my.id" target="_blank"
                        class="px-3 py-1 bg-white/5 rounded border border-white/10 text-xs font-black text-white/50 hover:text-white hover:bg-white/10 hover:border-white/30 transition-all duration-300 tracking-wider">
                        MIRASA GROUP
                    </a>
                </div>
            </div>
        </div>
    </footer>
</x-layout.landing.bahtera.app>
