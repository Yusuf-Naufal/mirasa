<section id="stats" class="py-32 bg-[#0A0F1A] text-white px-6 relative overflow-hidden">
    <div
        class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-brand-red/[0.05] rounded-full blur-[120px] pointer-events-none">
    </div>
    <div
        class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-blue-600/[0.03] rounded-full blur-[100px] pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-20 space-y-4">
            <div
                class="inline-flex items-center gap-3 px-4 py-2 bg-white/5 border border-white/10 rounded-full backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-brand-red animate-pulse"></span>
                <h2 class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px]">
                    @translate('Skala Industri')
                </h2>
            </div>
            <h3 class="text-4xl md:text-6xl font-black font-display tracking-tighter italic uppercase">
                @translate('Kapasitas') &
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-500">
                    @translate('Jangkauan Global')
                </span>
            </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <div
                class="group relative p-10 rounded-[3rem] bg-white/[0.03] border border-white/10 hover:border-brand-red/50 transition-all duration-500 overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-brand-red/[0.05] to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div class="relative z-10 space-y-6">
                    <div
                        class="w-14 h-14 bg-brand-red/10 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-brand-red transition-all duration-500 shadow-lg shadow-brand-red/20">
                        <i class="fa-solid fa-bolt-lightning text-brand-red group-hover:text-white text-2xl"></i>
                    </div>
                    <div>
                        <div
                            class="text-6xl font-black font-display tracking-tighter italic group-hover:text-brand-red transition-colors">
                            5<span class="text-2xl ml-1 font-bold opacity-50 uppercase">@translate('ton')+</span>
                        </div>
                        <div class="h-1 w-10 bg-brand-red mt-2 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            @translate('Produksi Harian')
                        </p>
                        <p class="text-[10px] text-slate-500 font-medium italic leading-none">@translate('Manufaktur kecepatan tinggi')
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="group relative p-10 rounded-[3rem] bg-white/[0.03] border border-white/10 hover:border-brand-red/50 transition-all duration-500 overflow-hidden lg:mt-8">
                <div class="relative z-10 space-y-6">
                    <div
                        class="w-14 h-14 bg-brand-red/10 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-brand-red transition-all duration-500 shadow-lg shadow-brand-red/20">
                        <i class="fa-solid fa-boxes-stacked text-brand-red group-hover:text-white text-2xl"></i>
                    </div>
                    <div>
                        <div
                            class="text-6xl font-black font-display tracking-tighter italic group-hover:text-brand-red transition-colors">
                            150<span class="text-2xl ml-1 font-bold opacity-50 uppercase">@translate('ton')+</span>
                        </div>
                        <div class="h-1 w-10 bg-brand-red mt-2 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            @translate('Kapasitas Bulanan')
                        </p>
                        <p class="text-[10px] text-slate-500 font-medium italic leading-none">@translate('Rantai pasok teroptimasi')
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="group relative p-10 rounded-[3rem] bg-white/[0.03] border border-white/10 hover:border-brand-red/50 transition-all duration-500 overflow-hidden">
                <div class="relative z-10 space-y-6">
                    <div
                        class="w-14 h-14 bg-brand-red/10 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-brand-red transition-all duration-500 shadow-lg shadow-brand-red/20">
                        <i class="fa-solid fa-handshake-angle text-brand-red group-hover:text-white text-2xl"></i>
                    </div>
                    <div>
                        <div
                            class="text-6xl font-black font-display tracking-tighter italic group-hover:text-brand-red transition-colors">
                            500<span class="text-2xl ml-1 font-bold opacity-50 uppercase">+</span>
                        </div>
                        <div class="h-1 w-10 bg-brand-red mt-2 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            @translate('Mitra Petani')
                        </p>
                        <p class="text-[10px] text-slate-500 font-medium italic leading-none">@translate('Sumber berkelanjutan')
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="group relative p-10 rounded-[3rem] bg-white/[0.03] border border-white/10 hover:border-brand-red/50 transition-all duration-500 overflow-hidden lg:mt-8">
                <div class="relative z-10 space-y-6">
                    <div
                        class="w-14 h-14 bg-brand-red/10 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:bg-brand-red transition-all duration-500 shadow-lg shadow-brand-red/20">
                        <i class="fa-solid fa-boxes-packing text-brand-red group-hover:text-white text-2xl"></i>
                    </div>
                    <div>
                        <div
                            class="text-6xl font-black font-display tracking-tighter italic group-hover:text-brand-red transition-colors">
                            10<span class="text-2xl ml-1 font-bold opacity-50 uppercase">+</span>
                        </div>
                        <div class="h-1 w-10 bg-brand-red mt-2 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            @translate('Varian Produk')
                        </p>
                        <p class="text-[10px] text-slate-500 font-medium italic leading-none">@translate('Kualitas standar mutu tinggi')
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
