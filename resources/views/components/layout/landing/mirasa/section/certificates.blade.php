<section id="certificates" class="py-24 lg:py-32 bg-slate-50 px-6 relative overflow-hidden z-0">
    <div
        class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand-red/[0.02] rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600/[0.02] rounded-full blur-3xl translate-y-1/3 -translate-x-1/3 pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto relative z-10">

        <div class="text-center mb-16 lg:mb-20 space-y-6">
            <div class="inline-flex items-center justify-center gap-4">
                <span class="w-8 md:w-12 h-[2px] bg-brand-red"></span>
                <h2
                    class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px] md:text-xs italic leading-none">
                    @translate('Legalitas & Mutu')
                </h2>
                <span class="w-8 md:w-12 h-[2px] bg-brand-red"></span>
            </div>

            <h3
                class="text-4xl md:text-5xl lg:text-6xl font-black font-display text-slate-900 leading-[1.1] tracking-tighter uppercase italic">
                @translate('Sertifikasi') <br class="hidden sm:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                    @translate('Resmi Kami.')
                </span>
            </h3>

            <p class="text-slate-500 text-base md:text-lg font-medium max-w-2xl mx-auto leading-relaxed italic">
                @translate('Komitmen nyata kami terhadap keamanan pangan dan standar kualitas internasional yang terjamin.')
            </p>
        </div>

        <div class="w-full relative z-10">
            <div id="certSlider"
                class="flex overflow-x-auto lg:grid lg:grid-cols-4 snap-x snap-mandatory gap-8 px-6 md:px-12 lg:max-w-7xl lg:mx-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">

                <div class="snap-center shrink-0 w-[280px] md:w-[320px] lg:w-full group">
                    <div
                        class="relative aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100 shadow-sm transition-all duration-700 group-hover:shadow-2xl group-hover:shadow-brand-red/10 group-hover:-translate-y-3">
                        <img src="{{ asset('assets/sertifikat/Halal.jpeg') }}"
                            class="w-full h-full object-cover filter contrast-[1.02] brightness-[1.02] group-hover:scale-110 transition-transform duration-1000">

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                            <p class="text-brand-red font-black text-[10px] tracking-[0.3em] uppercase mb-2">Halal
                                Indonesia
                            </p>
                            <h4 class="text-white font-bold text-xl mb-4">Sertifikat Halal</h4>
                        </div>
                    </div>
                    <div class="mt-6 text-center lg:text-left">
                        <h4
                            class="text-slate-900 font-black text-sm uppercase tracking-tight group-hover:text-brand-red transition-colors italic">
                            Sertifikat Halal</h4>
                        <p class="text-slate-400 text-[10px] font-bold tracking-widest mt-1 uppercase">
                            ID33210000001931219
                        </p>
                    </div>
                </div>

                <div class="snap-center shrink-0 w-[280px] md:w-[320px] lg:w-full group lg:mt-12">
                    <div
                        class="relative aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100 shadow-sm transition-all duration-700 group-hover:shadow-2xl group-hover:shadow-brand-red/10 group-hover:-translate-y-3">
                        <img src="{{ asset('assets/sertifikat/BPOM.jpeg') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                            <p class="text-brand-red font-black text-[10px] tracking-[0.3em] uppercase mb-2">BPOM RI</p>
                            <h4 class="text-white font-bold text-xl mb-4">Izin Edar Resmi</h4>
                        </div>
                    </div>
                    <div class="mt-6 text-center lg:text-left">
                        <h4
                            class="text-slate-900 font-black text-sm uppercase tracking-tight group-hover:text-brand-red transition-colors italic">
                            Izin Edar Resmi</h4>
                        <p class="text-slate-400 text-[10px] font-bold tracking-widest mt-1 uppercase">MD 272811001043
                        </p>
                    </div>
                </div>

                <div class="snap-center shrink-0 w-[280px] md:w-[320px] lg:w-full group">
                    <div
                        class="relative aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100 shadow-sm transition-all duration-700 group-hover:shadow-2xl group-hover:shadow-brand-red/10 group-hover:-translate-y-3">
                        <img src="{{ asset('assets/sertifikat/HACCP.jpeg') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                            <p class="text-brand-red font-black text-[10px] tracking-[0.3em] uppercase mb-2">Food Safety
                            </p>
                            <h4 class="text-white font-bold text-xl mb-4">Sertifikasi HACCP</h4>
                        </div>
                    </div>
                    <div class="mt-6 text-center lg:text-left">
                        <h4
                            class="text-slate-900 font-black text-sm uppercase tracking-tight group-hover:text-brand-red transition-colors italic">
                            Sertifikasi HACCP</h4>
                        <p class="text-slate-400 text-[10px] font-bold tracking-widest mt-1 uppercase">Hazard Analysis
                        </p>
                    </div>
                </div>

                <div class="snap-center shrink-0 w-[280px] md:w-[320px] lg:w-full group lg:mt-12">
                    <div
                        class="relative aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100 shadow-sm transition-all duration-700 group-hover:shadow-2xl group-hover:shadow-brand-red/10 group-hover:-translate-y-3">
                        <img src="{{ asset('assets/sertifikat/ISO.jpeg') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                            <p class="text-brand-red font-black text-[10px] tracking-[0.3em] uppercase mb-2">Global
                                Standard
                            </p>
                            <h4 class="text-white font-bold text-xl mb-4">ISO 22000:2018</h4>
                        </div>
                    </div>
                    <div class="mt-6 text-center lg:text-left">
                        <h4
                            class="text-slate-900 font-black text-sm uppercase tracking-tight group-hover:text-brand-red transition-colors italic">
                            ISO 9001:2015</h4>
                        <p class="text-slate-400 text-[10px] font-bold tracking-widest mt-1 uppercase">Food Safety
                            System
                        </p>
                    </div>
                </div>

                <div class="shrink-0 w-6 lg:hidden"></div>
            </div>
        </div>
    </div>
</section>
