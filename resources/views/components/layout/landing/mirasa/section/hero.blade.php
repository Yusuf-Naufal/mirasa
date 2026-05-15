<section
    class="relative min-h-[95vh] flex items-center pt-32 pb-16 md:pt-40 md:pb-24 px-6 overflow-hidden bg-slate-50 z-0">

    <div class="absolute inset-0 z-[-1] pointer-events-none opacity-[0.03]"
        style="background-image: radial-gradient(#0F172A 1.5px, transparent 1.5px); background-size: 32px 32px;">
    </div>
    <div class="absolute inset-0 z-[-1] pointer-events-none opacity-[0.02]"
        style="background-image: linear-gradient(to right, #0F172A 1px, transparent 1px), linear-gradient(to bottom, #0F172A 1px, transparent 1px); background-size: 120px 120px;">
    </div>

    <div
        class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-brand-red/[0.05] rounded-full blur-[100px] pointer-events-none -z-10">
    </div>
    <div
        class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[500px] h-[500px] bg-brand-red/[0.04] rounded-full blur-[120px] pointer-events-none -z-10">
    </div>

    <div class="max-w-7xl mx-auto grid lg:grid-cols-12 gap-12 lg:gap-16 items-center relative z-10">

        <div class="lg:col-span-6 order-2 lg:order-1 text-center lg:text-left space-y-8 md:space-y-10">
            <div class="space-y-6">
                <div
                    class="inline-flex items-center gap-3 px-5 py-2.5 bg-white/60 backdrop-blur-md border border-slate-200/60 rounded-full shadow-sm hover:border-brand-red/30 hover:bg-white transition-all duration-300">
                    {{-- <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-red opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-red"></span>
                    </span> --}}
                    <span class="text-slate-700 text-[10px] md:text-xs font-black uppercase tracking-[0.3em]">
                        @translate('Berdiri Sejak 1979')
                    </span>
                </div>

                <h1
                    class="text-4xl md:text-5xl lg:text-[4rem] font-black font-display leading-[1.05] tracking-tight text-slate-900">
                    @translate('Kualitas Lokal')<span class="text-brand-red">,</span><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800 italic pr-2">
                        @translate('Standar Global').
                    </span>
                </h1>

                <p class="text-base md:text-lg text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                    @translate('Membawa cita rasa Nusantara ke panggung internasional melalui inovasi teknologi dan integritas mutu tanpa kompromi.')
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                <a href="#stats"
                    class="group bg-gradient-to-r from-brand-red to-red-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-brand-red/30 hover:shadow-brand-red/50 hover:-translate-y-1 transition-all duration-300 text-xs uppercase tracking-widest flex items-center justify-center gap-3">
                    @translate('Eksplorasi Data')
                    <i
                        class="fa-solid fa-arrow-trend-up group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </a>

                <a href="#history"
                    class="group bg-white/80 backdrop-blur-md border border-slate-200 text-slate-700 px-8 py-4 rounded-2xl font-bold hover:bg-white hover:border-slate-300 hover:shadow-md hover:-translate-y-1 transition-all duration-300 text-xs uppercase tracking-widest flex items-center justify-center">
                    @translate('Filosofi Kami')
                    <i
                        class="fa-solid fa-chevron-right ml-3 text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="lg:col-span-6 order-1 lg:order-2 flex justify-center relative lg:justify-end">

            <div
                class="w-80 h-80 md:w-[450px] md:h-[450px] bg-gradient-to-tr from-brand-red/10 to-transparent rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 blur-2xl pointer-events-none">
            </div>

            <div
                class="relative z-10 p-3 bg-white/40 backdrop-blur-2xl rounded-[3rem] border border-white shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] transition-transform duration-700 hover:-translate-y-2 group">

                <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-100">
                    <img src="{{ asset('assets/foto/Mirasa-Profile.webp') }}" loading="lazy" alt="PT Mirasa Food"
                        class="w-full max-w-[480px] h-auto object-cover aspect-[4/3] group-hover:scale-105 transition-transform duration-1000">

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 to-transparent pointer-events-none">
                    </div>
                </div>

                <div
                    class="absolute -bottom-8 -left-4 md:-bottom-10 md:-left-12 bg-white/90 backdrop-blur-xl p-5 md:p-6 rounded-[2rem] shadow-2xl shadow-slate-900/10 border border-white hidden md:block transform hover:-translate-y-2 transition-transform duration-500">
                    <div class="flex items-center gap-5">

                        <div
                            class="w-14 h-14 bg-gradient-to-br from-brand-red/10 to-orange-500/10 rounded-2xl flex items-center justify-center border border-white shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 64 64"
                                class="drop-shadow-sm">
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
                            <p class="text-[9px] font-black text-brand-red uppercase tracking-[0.2em] mb-1">
                                @translate('Standar Mutu')
                            </p>
                            <p class="text-sm font-bold text-slate-800 uppercase tracking-tight italic text-nowrap">
                                @translate('Siap Ekspor')
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
