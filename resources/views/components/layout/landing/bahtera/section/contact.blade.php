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
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-bmb-orange to-orange-400">Kelezatan
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
