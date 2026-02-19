<x-layout.landing.mirasa.app>

    <x-layout.landing.mirasa.nav />

    <section
        class="relative min-h-[95vh] flex items-center pt-32 pb-16 md:pt-40 md:pb-24 px-6 overflow-hidden bg-[#FCFCFC]">

        <div
            class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-brand-red/[0.04] rounded-full blur-[120px] pointer-events-none">
        </div>

        <div
            class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[400px] h-[400px] bg-brand-red/[0.03] rounded-full blur-[100px] pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 lg:gap-24 items-center relative z-10">

            <div class="order-2 lg:order-1 text-center lg:text-left space-y-8 md:space-y-12">
                <div class="space-y-6">
                    <div
                        class="inline-flex items-center gap-3 px-5 py-2.5 bg-white border border-slate-200 rounded-full shadow-sm transition-colors hover:border-brand-red/30">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-brand-red"></span>
                        <span class="text-brand-red text-[10px] md:text-[11px] font-black uppercase tracking-[0.4em]">
                            @translate('Berdiri Sejak 1979')
                        </span>
                    </div>

                    <h1
                        class="text-5xl md:text-5xl lg:text-7xl font-[900] leading-[0.95] tracking-[-0.04em] text-slate-900">
                        @translate('Kualitas Lokal') ,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                            @translate('Standar Global').
                        </span>
                    </h1>

                    <p class="text-base md:text-xl text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        @translate('Membawa cita rasa Nusantara ke panggung internasional melalui inovasi teknologi dan integritas mutu tanpa kompromi.')
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start pt-4">
                    <a href="#stats"
                        class="bg-brand-red text-white px-10 py-5 rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-red-700 transition-colors text-xs uppercase tracking-widest flex items-center justify-center gap-3">
                        @translate('Eksplorasi Data')
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </a>

                    <a href="#history"
                        class="bg-white border border-slate-200 text-slate-700 px-10 py-5 rounded-2xl font-bold hover:bg-slate-50 transition-colors text-xs uppercase tracking-widest flex items-center justify-center shadow-sm">
                        @translate('Filosofi Kami')
                        <i class="fa-solid fa-chevron-right ml-3 text-[10px]"></i>
                    </a>
                </div>
            </div>

            <div class="order-1 lg:order-2 flex justify-center relative">
                <div
                    class="w-80 h-80 md:w-[500px] md:h-[500px] bg-brand-red/[0.04] rounded-full absolute blur-[120px] pointer-events-none">
                </div>

                <div
                    class="relative z-10 p-4 bg-white/90 rounded-[4rem] border border-white/60 shadow-[0_40px_80px_-15px_rgba(0,0,0,0.1)] transition-transform duration-500">

                    <div class="relative overflow-hidden rounded-[3.2rem]">
                        <img src="{{ asset('assets/foto/mirasa_bg.png') }}" loading="lazy" alt="PT Mirasa Food"
                            class="w-80 md:w-[520px] h-auto object-cover shadow-inner aspect-[4/3]">
                    </div>

                    <div
                        class="absolute -bottom-6 -right-6 md:-bottom-10 md:-right-10 bg-white p-6 md:p-8 rounded-[2.5rem] shadow-2xl border border-slate-50 hidden md:block">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-green-500/10 rounded-2xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                    viewBox="0 0 64 64">
                                    <path fill="#428bc1" d="M47.8 2L35.2 21h6.3L54.1 2z" />
                                    <path fill="#e8e8e8" d="M41.5 2L28.8 21h6.4L47.8 2z" />
                                    <path fill="#ed4c5c" d="M35.2 2L22.5 21h6.3L41.5 2z" />
                                    <path fill="#ffc200"
                                        d="M20.4 16.8c-.6 0-1.1.5-1.1 1.1v9.5c0 .6.5 1.1 1.1 1.1h23.2c.6 0 1.1-.5 1.1-1.1v-9.5c0-.6-.5-1.1-1.1-1.1zm22.1 7.3c0 .6-.5 1.1-1.1 1.1h-19c-.6 0-1.1-.5-1.1-1.1v-4.2c0-.6.5-1.1 1.1-1.1h19c.6 0 1.1.5 1.1 1.1z" />
                                    <path fill="#ed4c5c" d="M22.5 21h6.3L16.2 2H9.9z" />
                                    <path fill="#e8e8e8" d="M28.8 21h6.4L22.5 2h-6.3z" />
                                    <path fill="#3e4347" d="m33.1 5.2l-3.2 4.7L37.3 21h4.2l1-1.6z" opacity="0.5" />
                                    <path fill="#428bc1" d="M35.2 21h6.3L28.8 2h-6.3z" />
                                    <circle cx="32" cy="42.3" r="19.7" fill="#ffc200" />
                                    <path fill="#e68a00"
                                        d="M32.3 24.4c-10.1 0-18.2 8.2-18.2 18.2c0 3 .7 5.8 2 8.3c-.6-2-1-4.1-1-6.3c0-10.7 8.2-19.4 18.7-20.2z" />
                                    <path fill="#ffe394"
                                        d="M46 31c5.1 9 2.5 20.6-6.4 26.5c-1.8 1.2-3.8 2.1-5.8 2.7c2.8-.3 5.5-1.3 8-3c8.4-5.6 10.6-16.8 5.1-25z" />
                                    <path fill="#f2b200" d="M32 34.3v-6.4l-3.2 10l1.4 1.8z" />
                                    <path fill="#e68a00" d="m33.8 39.7l1.4-1.8l-3.2-10v6.4z" />
                                    <path fill="#c47500" d="m34.8 43l2.4 1.1l8.5-6.2l-6.3 1.8z" />
                                    <path fill="#ffe394" d="m39.4 39.7l6.3-1.8H35.2l-1.4 1.8z" />
                                    <path fill="#ffd252" d="m30.2 39.7l-1.4-1.8H18.3l6.3 1.8z" />
                                    <path fill="#ffdb75" d="m24.6 39.7l-6.3-1.8l8.4 6.2l2.5-1.1z" />
                                    <path fill="#e68a00" d="m34.8 43l1.8 5.4l3.9 5.7l-3.3-10z" />
                                    <path fill="#f2b200"
                                        d="M32 45.1v2.8l8.5 6.2l-3.9-5.7zM29.2 43l-2.5 1.1l-3.2 10l3.9-5.7z" />
                                    <path fill="#e68a00" d="m27.4 48.4l-3.9 5.7l8.5-6.2v-2.8z" />
                                    <circle cx="32" cy="42.3" r="19.7" fill="#ffc200" />
                                    <path fill="#ffce31"
                                        d="M33.8 39.7L32 34.3l-1.8 5.4h-5.6l4.6 3.3l-1.8 5.4l4.6-3.3l4.6 3.3l-1.8-5.4l4.6-3.3z" />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1 leading-none">
                                    @translate('Standar Global')
                                </p>
                                <p
                                    class="text-base font-bold text-slate-800 uppercase tracking-tight italic text-nowrap">
                                    @translate('Siap Ekspor')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="history" class="py-32 bg-[#FCFCFC] relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-[0.02] pointer-events-none"
            style="background-image: radial-gradient(#E30613 1px, transparent 1px); background-size: 30px 30px;"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-12 gap-16 items-center">

                <div class="lg:col-span-5 order-2 lg:order-1">
                    <div class="relative">
                        <div
                            class="relative z-20 bg-white p-10 rounded-[3rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.05)] border border-slate-100 group hover:border-brand-red/30 transition-all duration-500">
                            <div class="flex items-start justify-between mb-8">
                                <div
                                    class="w-16 h-16 bg-brand-red/10 rounded-2xl flex items-center justify-center group-hover:bg-brand-red transition-colors duration-500">
                                    <i
                                        class="fa-solid fa-clock-rotate-left text-brand-red text-2xl group-hover:text-white"></i>
                                </div>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">
                                    @translate('Sejak 1979')
                                </span>
                            </div>
                            <h4 id="experience-years"
                                class="text-7xl font-black font-display tracking-tighter text-brand-dark mb-2 italic">
                                00
                            </h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] leading-relaxed">
                                <span class="italic text-brand-red">@translate('Tahun Dedikasi')</span>
                            </p>
                        </div>

                        <div
                            class="absolute -bottom-12 -right-6 md:-right-12 z-30 bg-brand-dark p-8 rounded-[2.5rem] shadow-2xl border border-white/10 w-2/3 transform group hover:scale-105 transition-transform duration-500">
                            <div class="flex items-center gap-5">
                                <div class="relative flex items-center justify-center">
                                    <svg class="w-16 h-16 transform -rotate-90">
                                        <circle cx="32" cy="32" r="28" stroke="currentColor"
                                            stroke-width="4" fill="transparent" class="text-white/10" />
                                        <circle cx="32" cy="32" r="28" stroke="currentColor"
                                            stroke-width="4" fill="transparent" stroke-dasharray="175.9"
                                            stroke-dashoffset="0" class="text-brand-red" />
                                    </svg>
                                    <span class="absolute text-white font-bold text-xs">100%</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-brand-red uppercase tracking-widest mb-1">
                                        @translate('Murni')
                                    </p>
                                    <p class="text-xs font-bold text-slate-300 uppercase tracking-tighter">
                                        @translate('Bahan Alami')
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-brand-red/5 rounded-full blur-3xl"></div>
                    </div>
                </div>

                <div class="lg:col-span-7 order-1 lg:order-2 space-y-8">
                    <div class="inline-flex items-center gap-4">
                        <div class="h-[2px] w-12 bg-brand-red"></div>
                        <h2 class="text-brand-red font-black text-[11px] uppercase tracking-[0.5em] italic">
                            @translate('Warisan Kami')
                        </h2>
                    </div>

                    <h3
                        class="text-5xl md:text-6xl font-black font-display text-slate-900 leading-[0.9] tracking-tighter uppercase italic">
                        @translate('Mengukir Sejarah di') <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                            @translate('Setiap Musim.')
                        </span>
                    </h3>

                    <div class="grid md:grid-cols-2 gap-8 pt-6">
                        <div class="space-y-4">
                            <div class="w-10 h-[1px] bg-slate-300"></div>
                            <p class="text-slate-500 leading-relaxed text-sm md:text-base">
                                @translate('Berawal dari industri rumahan pada tahun 1979 di Mungkid, Magelang, PT Mirasa Food Industry bertransformasi menjadi pionir camilan berkualitas global.')
                            </p>
                        </div>
                        <div class="space-y-4">
                            <div class="w-10 h-[1px] bg-slate-300"></div>
                            <p class="text-slate-500 leading-relaxed text-sm md:text-base italic">
                                @translate('"Cap Payung" bukan sekadar logo; ini adalah janji perlindungan terhadap kualitas produk dan kepuasan Anda di segala kondisi.')
                            </p>
                        </div>
                    </div>

                    <div class="pt-8">
                        <a href="#vision"
                            class="inline-flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.3em] text-brand-dark hover:text-brand-red transition-colors group">
                            @translate('Lihat Visi Kami')
                            <div
                                class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-red transition-all">
                                <i
                                    class="fa-solid fa-arrow-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

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

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-brand-dark/40 to-transparent opacity-60">
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

                {{-- Content Section --}}
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

                    {{-- Quote --}}
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

                    {{-- Key Points --}}
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

    <section id="vision" class="py-32 bg-slate-50 px-6 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-brand-red/[0.02] rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-brand-red/[0.02] rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-2 gap-10">

                <div
                    class="group relative p-12 bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-white hover:border-brand-red/20 transition-all duration-500 overflow-hidden">
                    <div
                        class="absolute -top-24 -right-24 w-48 h-48 bg-brand-red/5 rounded-full blur-3xl group-hover:bg-brand-red/10 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center mb-10 group-hover:bg-brand-red group-hover:rotate-[10deg] transition-all duration-500 shadow-lg shadow-slate-200 group-hover:shadow-brand-red/30">
                            <i class="fa-solid fa-eye text-white text-2xl"></i>
                        </div>

                        <h3 class="text-3xl font-black font-display mb-6 tracking-tighter uppercase italic">
                            @translate('Visi Kami')
                        </h3>

                        <p class="text-slate-500 text-lg leading-relaxed font-medium">
                            <span class="italic">"@translate('Menjadi pemimpin pasar industri makanan ringan berbasis singkong di tingkat global dengan mempertahankan identitas Nusantara.')"</span>
                        </p>

                        <div class="mt-10 h-1 w-12 bg-brand-red/20 group-hover:w-full transition-all duration-700">
                        </div>
                    </div>
                </div>

                <div
                    class="group relative p-12 bg-slate-900 rounded-[3rem] shadow-2xl border border-slate-800 hover:border-brand-red/40 transition-all duration-500 overflow-hidden">
                    <div
                        class="absolute -bottom-24 -left-24 w-48 h-48 bg-brand-red/10 rounded-full blur-3xl group-hover:bg-brand-red/20 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-brand-red rounded-2xl flex items-center justify-center mb-10 group-hover:-rotate-[10deg] transition-all duration-500 shadow-lg shadow-brand-red/20">
                            <i class="fa-solid fa-bullseye text-white text-2xl"></i>
                        </div>

                        <h3 class="text-3xl font-black font-display text-white mb-6 tracking-tighter uppercase italic">
                            @translate('Misi Kami')
                        </h3>

                        <ul class="space-y-6">
                            <li class="flex items-start gap-4 group/item">
                                <div class="mt-1.5 w-5 h-[2px] bg-brand-red group-hover/item:w-8 transition-all"></div>
                                <div class="text-slate-400 group-hover/item:text-white transition-colors">
                                    <span
                                        class="font-bold uppercase tracking-widest text-[10px] block mb-1 text-brand-red">@translate('Teknologi')</span>
                                    <span class="text-sm md:text-base leading-snug">@translate('Modernisasi teknologi produksi higienis.')</span>
                                </div>
                            </li>

                            <li class="flex items-start gap-4 group/item">
                                <div class="mt-1.5 w-5 h-[2px] bg-brand-red group-hover/item:w-8 transition-all"></div>
                                <div class="text-slate-400 group-hover/item:text-white transition-colors">
                                    <span
                                        class="font-bold uppercase tracking-widest text-[10px] block mb-1 text-brand-red">@translate('Pemberdayaan')</span>
                                    <span class="text-sm md:text-base leading-snug">@translate('Pemberdayaan mitra tani lokal secara adil.')</span>
                                </div>
                            </li>

                            <li class="flex items-start gap-4 group/item">
                                <div class="mt-1.5 w-5 h-[2px] bg-brand-red group-hover/item:w-8 transition-all"></div>
                                <div class="text-slate-400 group-hover/item:text-white transition-colors">
                                    <span
                                        class="font-bold uppercase tracking-widest text-[10px] block mb-1 text-brand-red">@translate('Inovasi Rasa')</span>
                                    <span class="text-sm md:text-base leading-snug">@translate('Inovasi rasa untuk standar internasional.')</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

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
                            <i class="fa-solid fa-earth-americas text-brand-red group-hover:text-white text-2xl"></i>
                        </div>
                        <div>
                            <div
                                class="text-6xl font-black font-display tracking-tighter italic group-hover:text-brand-red transition-colors">
                                25<span class="text-2xl ml-1 font-bold opacity-50 uppercase">+</span>
                            </div>
                            <div class="h-1 w-10 bg-brand-red mt-2 group-hover:w-20 transition-all duration-500"></div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                @translate('Wilayah Ekspor')
                            </p>
                            <p class="text-[10px] text-slate-500 font-medium italic leading-none">@translate('Jaringan distribusi global')
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="partners" class="py-32 bg-white px-6 overflow-hidden relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-[0.03] pointer-events-none"
            style="background-image: radial-gradient(#0F172A 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-32">

                <div class="lg:w-5/12 text-center lg:text-left space-y-6">
                    <div class="inline-flex items-center gap-3">
                        <span class="w-12 h-[2px] bg-brand-red"></span>
                        <h2
                            class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px] italic leading-none">
                            @translate('Jaringan Global')
                        </h2>
                    </div>

                    <h3
                        class="text-4xl md:text-5xl font-black font-display text-slate-900 leading-[1] tracking-tighter uppercase italic">
                        @translate('Sinergi') <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                            @translate('Strategis Dunia.')
                        </span>
                    </h3>

                    <p class="text-slate-400 text-base font-medium max-w-sm mx-auto lg:mx-0 leading-relaxed italic">
                        @translate('Membangun ekosistem distribusi dan standar mutu bersama pemimpin industri terpercaya di seluruh dunia.')
                    </p>
                </div>

                <div class="lg:w-7/12 w-full">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 items-stretch">

                        <div
                            class="group relative flex flex-col items-center justify-center p-10 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                            <div
                                class="absolute top-0 inset-x-0 h-1.5 bg-[#00549B] scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                            </div>
                            <div class="h-16 w-full flex items-center justify-center mb-6">
                                <img src="{{ asset('assets/logo/logo-indofood.png') }}" loading="lazy"
                                    class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-60 group-hover:opacity-100"
                                    alt="Indofood">
                            </div>
                            <span
                                class="text-[9px] font-black text-slate-300 group-hover:text-[#00549B] uppercase tracking-[0.3em] transition-colors">Indofood</span>
                        </div>

                        <div
                            class="group relative flex flex-col items-center justify-center p-10 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                            <div
                                class="absolute top-0 inset-x-0 h-1.5 bg-brand-red scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                            </div>
                            <div class="h-16 w-full flex items-center justify-center mb-6">
                                <img src="{{ asset('assets/logo/logo-alfa.webp') }}" loading="lazy"
                                    class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-60 group-hover:opacity-100"
                                    alt="Alfamart">
                            </div>
                            <span
                                class="text-[9px] font-black text-slate-300 group-hover:text-brand-red uppercase tracking-[0.3em] transition-colors">Alfamart</span>
                        </div>

                        <div
                            class="group relative flex flex-col items-center justify-center p-10 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                            <div
                                class="absolute top-0 inset-x-0 h-1.5 bg-purple-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                            </div>
                            <div class="h-16 w-full flex items-center justify-center mb-6">
                                <img src="{{ asset('assets/logo/logo-halal.avif') }}" loading="lazy"
                                    class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-60 group-hover:opacity-100"
                                    alt="Halal Indonesia">
                            </div>
                            <div class="text-center">
                                <span
                                    class="text-[9px] font-black text-slate-300 group-hover:text-purple-600 uppercase tracking-[0.3em] transition-colors block">
                                    @translate('Tersertifikasi')
                                </span>
                                <span
                                    class="text-[7px] font-bold text-slate-300 uppercase tracking-widest leading-none">
                                    Halal Indonesia
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="news" class="py-32 bg-[#FCFCFC] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-3">
                        <span class="w-12 h-[2px] bg-brand-red"></span>
                        <h2 class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px] italic">
                            @translate('Kabar Terkini')
                        </h2>
                    </div>
                    <h3
                        class="text-4xl md:text-5xl font-black font-display text-slate-900 leading-[1] tracking-tighter uppercase italic">
                        @translate('Wawasan') & <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                            @translate('Pembaruan Mirasa.')
                        </span>
                    </h3>
                </div>

                <a href="{{ route('allBerita', ['lang' => request('lang', 'ID')]) }}"
                    class="group inline-flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-brand-red transition-all">
                    @translate('Lihat Semua Berita')
                    <div
                        class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-red group-hover:bg-brand-red transition-all">
                        <i
                            class="fa-solid fa-arrow-right text-[10px] group-hover:text-white transition-transform group-hover:translate-x-1"></i>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($berita as $item)
                    <article
                        class="group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] transition-all duration-500 hover:-translate-y-2">

                        <div class="aspect-[16/10] overflow-hidden relative">
                            <img src="{{ asset('storage/' . $item->gambar_utama) }}" alt="{{ $item->judul }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                            <div class="absolute top-6 left-6">
                                <span
                                    class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-full text-[9px] font-black uppercase tracking-widest text-brand-red shadow-sm">
                                    @translate($item->kategori)
                                </span>
                            </div>
                        </div>

                        <div class="p-8 space-y-4">
                            <div
                                class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar text-brand-red"></i>
                                    <span>{{ $item->tanggal_publish->format('d M, Y') }}</span>
                                </div>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span>{{ $item->penulis }}</span>
                            </div>

                            <h4
                                class="text-xl font-black text-slate-900 leading-tight uppercase tracking-tighter italic group-hover:text-brand-red transition-colors line-clamp-2">
                                @translate($item->judul)
                            </h4>

                            <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 italic font-medium">
                                @translate($item->ringkasan)
                            </p>

                            <div class="pt-4">
                                <a href="{{ route('berita.show', ['slug' => $item->slug, 'lang' => request('lang', 'ID')]) }}"
                                    class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-brand-dark group/link">
                                    @translate('Baca Selengkapnya')
                                    <i
                                        class="fa-solid fa-chevron-right text-[8px] group-hover/link:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div
            class="absolute bottom-0 right-0 w-96 h-96 bg-brand-red/[0.02] rounded-full blur-3xl translate-y-1/2 translate-x-1/2">
        </div>
    </section>

    <footer class="bg-[#0A0F1A] text-white pt-24 pb-12 px-6 relative overflow-hidden">
        <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-brand-red to-transparent opacity-50">
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-8 mb-20">

                <div class="lg:col-span-5 space-y-8 text-center lg:text-left">
                    <div class="flex flex-col items-center lg:items-start gap-4">
                        <img src="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}" loading="lazy"
                            class="h-16 w-auto brightness-0 invert" alt="Footer Logo">
                        <div>
                            <span class="block font-black text-xl tracking-tighter uppercase">PT Mirasa Food
                                Industry</span>
                            <span class="text-[9px] font-bold text-brand-red uppercase tracking-[0.3em]">@translate('Warisan Kualitas')</span>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md mx-auto lg:mx-0">
                        <span class="italic">"@translate('Dedikasi tanpa henti untuk menjaga standar mutu camilan Nusantara sejak 1979. Kami tumbuh bersama petani lokal untuk menghadirkan kebahagiaan di setiap kemasan.')"</span>
                    </p>
                </div>

                <div class="lg:col-span-3 grid grid-cols-2 lg:grid-cols-1 gap-8 text-center lg:text-left">
                    <div class="space-y-6">
                        <span class="text-[11px] font-black uppercase tracking-[0.3em] text-brand-red">
                            @translate('Eksplorasi')
                        </span>
                        <ul class="space-y-4 text-[10px] font-bold uppercase tracking-widest text-slate-300">
                            <li>
                                <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#history"
                                    class="hover:text-white transition-colors">
                                    @translate('Sejarah Kami')
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#owner"
                                    class="hover:text-white transition-colors">
                                    @translate('Pemilik')
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#stats"
                                    class="hover:text-white transition-colors">
                                    @translate('Data Produksi')
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="space-y-6 md:mt-0 lg:mt-8">
                        <span class="text-[11px] font-black uppercase tracking-[0.3em] text-brand-red">
                            @translate('Sosial Media')
                        </span>
                        <ul class="space-y-4 text-[10px] font-bold uppercase tracking-widest text-slate-300">
                            <li>
                                <a target="blank"
                                    href="https://www.instagram.com/mirasafood.ind?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="
                                    class="hover:text-white transition-colors">Instagram</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-8 bg-white/5 p-6 md:p-8 rounded-[2.5rem] border border-white/5">
                    <span class="text-[11px] font-black uppercase tracking-[0.3em] text-brand-red">
                        @translate('Kantor Pusat')
                    </span>

                    <div class="space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 justify-center lg:justify-start">
                                <i class="fa-solid fa-location-dot mt-1 text-brand-red"></i>
                                <p class="text-xs text-slate-300 leading-relaxed font-medium">
                                    Jalan Munggur No. 2 Ambartawang, Japun Satu, Paremono, Kec. Mungkid, Kabupaten Magelang, Jawa Tengah 56512
                                </p>
                            </div>
                            <div class="flex items-center gap-4 justify-center lg:justify-start">
                                <i class="fa-solid fa-envelope text-brand-red"></i>
                                <p class="text-xs text-slate-300 font-medium">mirasafoodhrd@gmail.com</p>
                            </div>
                        </div>

                        <div
                            class="relative w-full h-44 rounded-2xl overflow-hidden border border-white/10 shadow-inner group">
                            <iframe class="absolute inset-0 w-full h-full border-0"
                                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15820.473972317046!2d110.24919329705028!3d-7.562057721282352!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a8bfcfb06852f%3A0xdcdf376e26390df9!2sPT.%20Mirasa%20Food%20Industry!5e0!3m2!1sid!2sid!4v1770107697111!5m2!1sid!2sid"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            <div
                                class="absolute inset-0 pointer-events-none bg-gradient-to-t from-[#0A0F1A] via-transparent to-transparent">
                            </div>
                            <div
                                class="absolute top-4 right-4 w-2 h-2 bg-brand-red rounded-full shadow-[0_0_12px_rgba(214,28,28,0.8)]">
                            </div>
                        </div>

                        <a href="https://maps.app.goo.gl/JcwMj1BrfzmVC4vT8" target="_blank"
                            class="flex items-center justify-center lg:justify-start gap-2 text-[9px] font-black text-brand-red uppercase tracking-widest hover:translate-x-1 transition-transform">
                            Google Maps
                            <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-bold text-slate-500 tracking-[0.2em] text-center md:text-left uppercase">
                    © {{ date('Y') }} PT MIRASA FOOD INDUSTRY. <span class="hidden md:inline">|</span>
                    <span class="block md:inline">@translate('Seluruh Hak Cipta Dilindungi.')</span>
                </p>
                <div class="flex gap-4">
                    <div class="bg-green-500/10 px-3 py-1 rounded-md border border-green-500/20">
                        <span
                            class="text-[8px] font-black text-green-500 uppercase tracking-widest italic">@translate('Tersertifikasi Siap Ekspor')</span>
                    </div>
                    <div class="bg-brand-red/10 px-3 py-1 rounded-md border border-brand-red/20">
                        <span
                            class="text-[8px] font-black text-brand-red uppercase tracking-widest italic">@translate('Tersertifikasi Halal')</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</x-layout.landing.mirasa.app>
