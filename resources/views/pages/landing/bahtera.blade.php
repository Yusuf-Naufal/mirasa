<x-layout.landing.bahtera.app>

    <x-layout.landing.bahtera.nav />

    <x-layout.landing.bahtera.section.home />
    
    <x-layout.landing.bahtera.section.about />
    
    <x-layout.landing.bahtera.section.proses />
    
    <x-layout.landing.bahtera.section.products :products="$products" />
    
    <x-layout.landing.bahtera.section.contact />
    
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
