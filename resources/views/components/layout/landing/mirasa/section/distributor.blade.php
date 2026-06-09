<section id="bahtera-portal" class="py-16 lg:py-20 bg-slate-50 px-6">
    <div class="max-w-6xl mx-auto">
        <div
            class="relative bg-slate-900 rounded-[2.5rem] overflow-hidden flex flex-col md:flex-row items-center shadow-2xl shadow-slate-900/10 border border-slate-800 transition-transform duration-500 hover:-translate-y-1">

            <div
                class="absolute right-0 top-0 w-full md:w-1/2 h-full bg-gradient-to-l from-blue-600/10 to-transparent pointer-events-none">
            </div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="p-8 md:p-12 lg:p-14 md:w-2/3 space-y-7 relative z-10 w-full text-left">

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                    <div
                        class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center p-3 shadow-xl shrink-0 border border-white/10">
                        <img src="{{ asset('assets/logo/BMB-logo.webp') }}" alt="Logo CV Bahtera"
                            class="w-full h-full object-contain">
                    </div>

                    <div class="space-y-1">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[9px] font-bold uppercase tracking-widest">
                            <i class="fa-solid fa-network-wired"></i>
                            @translate('Cabang Resmi Mirasa Food')
                        </div>
                        <h4 class="text-xl md:text-2xl font-black text-white uppercase tracking-tighter italic">
                            CV Bahtera Mandiri Bersama
                        </h4>
                    </div>
                </div>

                <h3 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-tight">
                    @translate('Eksplorasi Lini Produk Ekstensif')
                </h3>

                <p class="text-slate-400 text-sm md:text-base leading-relaxed max-w-xl font-medium italic">
                    @translate('Sebagai cabang strategis, CV Bahtera menghadirkan ragam varian produk jadi yang melengkapi katalog utama PT Mirasa Food. Temukan pilihan produk eksklusif untuk kebutuhan ritel dan mitra B2B Anda.')
                </p>
            </div>

            <div
                class="p-8 md:p-12 lg:p-14 md:w-1/3 flex justify-start md:justify-end border-t md:border-t-0 md:border-l border-slate-800 relative z-10 w-full backdrop-blur-sm h-full items-center">

                <a href="http://bahteramandiri.nopaldev.my.id" target="_blank"
                    class="group w-full flex items-center justify-between gap-6 bg-blue-600 hover:bg-blue-500 text-white px-6 py-4 lg:py-5 rounded-2xl transition-all duration-300 shadow-lg shadow-blue-900/20 hover:shadow-blue-600/40 border border-blue-500/50">

                    <div class="flex flex-col text-left">
                        <span class="text-[9px] font-bold text-blue-100 uppercase tracking-[0.2em] mb-1 opacity-80">
                            @translate('Kunjungi Portal')
                        </span>
                        <span class="text-lg font-black tracking-tight">
                            Bahtera
                        </span>
                    </div>

                    <div
                        class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center group-hover:translate-x-1 group-hover:bg-white group-hover:text-blue-600 transition-all duration-300 shrink-0">
                        <i class="fa-solid fa-arrow-right text-lg"></i>
                    </div>
                </a>

            </div>
        </div>
    </div>
</section>
