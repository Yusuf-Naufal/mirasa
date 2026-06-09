<section id="owner" class="py-32 bg-white overflow-hidden relative">
    <div
        class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-1/4 pointer-events-none select-none opacity-[0.02]">
        <h2 class="text-[20rem] font-black font-display uppercase leading-none">@translate('Penemu')</h2>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row gap-20 items-center">

            <div class="lg:w-5/12 relative">
                <div
                    class="relative z-20 p-3 bg-slate-50 rounded-[3.5rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.1)] border border-slate-100 group">
                    <div class="aspect-[3/4] overflow-hidden rounded-[2.8rem] relative">
                        <img src="{{ asset('assets/foto/owner.jpg') }}" loading="lazy" alt="Owner PT Mirasa Food"
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-110">

                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/40 to-transparent opacity-60">
                        </div>
                    </div>

                    <div
                        class="absolute -bottom-6 -right-6 md:-right-10 bg-white p-6 md:p-8 rounded-[2rem] shadow-2xl border border-slate-50 animate-float">
                        <div class="space-y-1">
                            <p
                                class="text-[10px] font-black text-brand-red uppercase tracking-[0.3em] leading-none mb-1">
                                Founder & CEO
                            </p>
                            <h4 class="text-xl font-black text-brand-dark uppercase tracking-tighter italic">
                                Yusuf Naufal
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="absolute -top-10 -left-10 w-40 h-40 bg-brand-red/[0.03] rounded-full blur-3xl"></div>
            </div>


            <div class="lg:w-7/12 space-y-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-[2px] bg-brand-red"></span>
                        <h2 class="text-brand-red font-black uppercase tracking-[0.4em] text-[11px] italic">
                            @translate('Pesan Pendiri')
                        </h2>
                    </div>
                    <h3
                        class="text-5xl md:text-6xl font-black font-display text-slate-900 leading-[0.9] tracking-tighter uppercase italic">
                        <span class="italic">@translate('Visi Melampaui')</span>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                            @translate('Ekspektasi.')
                        </span>
                    </h3>
                </div>


                <div class="relative pt-6">
                    <i class="fa-solid fa-quote-left absolute -top-4 -left-6 text-6xl text-slate-100 -z-10"></i>

                    <div class="space-y-8">
                        <div
                            class="text-2xl md:text-3xl font-medium text-slate-600 leading-tight italic tracking-tight">
                            <p>
                                "@translate('Kepercayaan investor dan konsumen dibangun di atas satu hal:')
                                <span class="text-brand-dark font-black not-italic">@translate('Konsistensi')</span>.
                                @translate('Di Mirasa Food, kami tidak pernah berkompromi dengan kualitas produk kami.')"
                            </p>
                        </div>

                        <div class="flex items-center gap-6 pt-4">
                            <div class="h-[1px] w-20 bg-slate-200"></div>
                            <p class="font-black text-brand-dark uppercase tracking-[0.3em] text-[10px]">
                                OWNER, PT MIRASA FOOD INDUSTRY
                            </p>
                        </div>
                    </div>
                </div>


                <div class="pt-10 grid grid-cols-2 gap-8 border-t border-slate-100">
                    <div class="space-y-1">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Quality Control</p>
                        <p class="text-xl font-black text-brand-dark uppercase italic">@translate('Tanpa Kompromi')</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Innovation</p>
                        <p class="text-xl font-black text-brand-dark uppercase italic">@translate('Berbasis Teknologi')</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
