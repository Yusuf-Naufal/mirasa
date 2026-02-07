<x-layout.landing.bahtera.app>

    <x-layout.landing.bahtera.nav />

    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 hero-pattern overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
            <div class="z-10 text-center lg:text-left">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full mb-6 border border-slate-200 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-bmb-orange animate-ping"></span>
                    <span class="text-bmb-blue font-bold text-xs uppercase tracking-widest">Kualitas Ekspor
                        Premium</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-8 tracking-tighter">
                    Nikmati Sensasi <br> <span class="text-bmb-orange underline decoration-bmb-blue/20">Singkong
                        Artisan.</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Dipilih dari umbi terbaik dan diolah dengan standar emas <span
                        class="font-bold text-bmb-blue text-nowrap text-lg">PT Mirasa Food Industri</span>.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#products"
                        class="bg-bmb-blue text-white px-10 py-5 rounded-2xl font-black text-lg shadow-xl shadow-blue-200 hover:-translate-y-1 transition-all">
                        Eksplorasi Rasa
                    </a>
                    <a href="#contact"
                        class="bg-white border-2 border-slate-200 text-slate-700 px-10 py-5 rounded-2xl font-black text-lg hover:bg-slate-50 transition-all">
                        Hubungi Kami
                    </a>
                </div>
            </div>

            <div class="relative group max-w-md mx-auto">
                <div
                    class="relative z-10 bg-white p-3 sm:p-5 rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] 
                rotate-3 group-hover:rotate-0 transition-all duration-700 ease-out">

                    <div
                        class="relative bg-slate-50 aspect-square rounded-[2.5rem] flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('assets/foto/bahtera-bg.png') }}" alt="Produk Kripik"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                            loading="lazy">

                        <div class="absolute inset-0 bg-gradient-to-tr from-black/5 to-transparent pointer-events-none">
                        </div>
                    </div>
                </div>

                <div
                    class="absolute -bottom-6 -left-6 z-20 bg-bmb-orange text-white px-8 py-6 rounded-3xl font-black text-center shadow-2xl">
                    <span class="block text-4xl leading-none">1990</span>
                    <span class="text-[10px] uppercase tracking-[0.2em]">Sejak Dahulu</span>
                </div>

                <div class="absolute -top-4 -right-4 w-24 h-24 bg-orange-100 rounded-full blur-3xl opacity-50 -z-10">
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 bg-bmb-blue relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1">
                    <h2 class="text-4xl lg:text-5xl font-black text-white mb-8 leading-tight uppercase tracking-tight">
                        Satu <span class="text-bmb-orange">Visi</span> Dalam <br> Setiap Gigitan.
                    </h2>
                    <div class="space-y-6">
                        <div class="flex gap-4 p-6 glass-card rounded-2xl">
                            <div class="text-3xl">🌿</div>
                            <p class="text-slate-800 font-medium">Bekerjasama langsung dengan petani lokal untuk
                                menjamin kesegaran bahan baku utama kami.</p>
                        </div>
                        <div class="flex gap-4 p-6 glass-card rounded-2xl">
                            <div class="text-3xl">🛡️</div>
                            <p class="text-slate-800 font-medium">Diawasi ketat oleh <b>PT Mirasa Food Industri</b>
                                untuk menjaga standar keamanan pangan dunia.</p>
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2 flex justify-center">
                    <div class="relative w-full max-w-sm">
                        <div class="absolute inset-0 bg-bmb-orange rounded-full blur-3xl opacity-20"></div>
                        <div class="relative bg-white p-10 rounded-[3rem] shadow-inner text-center">
                            <p class="text-slate-400 text-sm font-bold uppercase mb-4 tracking-widest">Bagian Dari</p>
                            <h3 class="text-2xl font-black text-bmb-blue">MIRASA FOOD</h3>
                            <p class="text-slate-500 mt-2 italic">"Mastering the Tuber"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="py-32 bg-white overflow-hidden">
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
                                    <i class="fa-brands fa-instagram text-lg text-slate-400 group-hover:text-white"></i>
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
                        <li><a href="#" class="hover:text-white transition">Kemitraan</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
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
