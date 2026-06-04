<section id="partners" class="py-24 lg:py-32 bg-white px-6 overflow-hidden relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-[0.03] pointer-events-none"
        style="background-image: radial-gradient(#0F172A 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="max-w-7xl mx-auto relative z-10">

        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10 mb-16 lg:mb-20">
            <div class="lg:w-2/3 space-y-6">
                <div class="inline-flex items-center gap-3">
                    <span class="w-12 h-[2px] bg-brand-red"></span>
                    <h2
                        class="text-brand-red font-black tracking-[0.4em] uppercase text-[10px] md:text-xs italic leading-none">
                        @translate('Jaringan Global')
                    </h2>
                </div>

                <h3
                    class="text-4xl md:text-5xl lg:text-6xl font-black font-display text-slate-900 leading-[1.1] tracking-tighter uppercase italic">
                    @translate('Sinergi') <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-red to-red-800">
                        @translate('Strategis Dunia.')
                    </span>
                </h3>

                <p class="text-slate-500 text-base md:text-lg font-medium max-w-xl leading-relaxed italic">
                    @translate('Membangun ekosistem distribusi dan standar mutu bersama pemimpin industri terpercaya di seluruh dunia.')
                </p>
            </div>

            
        </div>

        <div class="w-full relative group/slider">

            <button id="slideLeft"
                class="absolute left-0 top-1/2 -translate-y-1/2 -ml-4 lg:-ml-6 z-20 w-14 h-14 rounded-full border border-slate-100 bg-white/90 backdrop-blur shadow-xl shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-brand-red hover:border-brand-red hover:bg-white transition-all duration-300 md:opacity-0 md:group-hover/slider:opacity-100 hidden md:flex">
                <i class="fa-solid fa-arrow-left"></i>
            </button>

            <div id="partnerSlider"
                class="flex overflow-x-auto snap-x snap-mandatory gap-6 md:gap-8 pb-12 pt-4 px-2 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] items-stretch cursor-grab active:cursor-grabbing scroll-smooth">

                <div
                    class="snap-center shrink-0 w-[260px] md:w-[280px] lg:w-[320px] group relative flex flex-col items-center justify-center p-10 lg:p-12 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-[#00549B] scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                    </div>
                    <div class="h-20 w-full flex items-center justify-center mb-8 relative z-10">
                        <img src="{{ asset('assets/logo/indofood-logo.webp') }}" loading="lazy"
                            class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-50 group-hover:opacity-100"
                            alt="Indofood">
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-400 group-hover:text-[#00549B] uppercase tracking-[0.3em] transition-colors relative z-10">Indofood</span>
                </div>

                <div
                    class="snap-center shrink-0 w-[260px] md:w-[280px] lg:w-[320px] group relative flex flex-col items-center justify-center p-10 lg:p-12 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-[#00549B] scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                    </div>
                    <div class="h-20 w-full flex items-center justify-center mb-8 relative z-10">
                        <img src="{{ asset('assets/logo/Indomart-logo.png') }}" loading="lazy"
                            class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-50 group-hover:opacity-100"
                            alt="Indomaret">
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-400 group-hover:text-[#00549B] uppercase tracking-[0.3em] transition-colors relative z-10">Indomaret</span>
                </div>

                <div
                    class="snap-center shrink-0 w-[260px] md:w-[280px] lg:w-[320px] group relative flex flex-col items-center justify-center p-10 lg:p-12 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-red-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                    </div>
                    <div class="h-20 w-full flex items-center justify-center mb-8 relative z-10">
                        <img src="{{ asset('assets/logo/Alfamidi-logo.png') }}" loading="lazy"
                            class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-50 group-hover:opacity-100"
                            alt="Alfamidi">
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-400 group-hover:text-red-600 uppercase tracking-[0.3em] transition-colors relative z-10">Alfamidi</span>
                </div>

                <div
                    class="snap-center shrink-0 w-[260px] md:w-[280px] lg:w-[320px] group relative flex flex-col items-center justify-center p-10 lg:p-12 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-brand-red scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                    </div>
                    <div class="h-20 w-full flex items-center justify-center mb-8 relative z-10">
                        <img src="{{ asset('assets/logo/logo-alfa.webp') }}" loading="lazy"
                            class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-50 group-hover:opacity-100"
                            alt="Alfamart">
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-400 group-hover:text-brand-red uppercase tracking-[0.3em] transition-colors relative z-10">Alfamart</span>
                </div>

                <div
                    class="snap-center shrink-0 w-[260px] md:w-[280px] lg:w-[320px] group relative flex flex-col items-center justify-center p-10 lg:p-12 rounded-[3rem] bg-slate-50 border border-slate-100 transition-all duration-500 hover:bg-white hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-2">
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-blue-700 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 rounded-t-full">
                    </div>
                    <div class="h-20 w-full flex items-center justify-center mb-8 relative z-10">
                        <img src="{{ asset('assets/logo/Indogrosir-logo.png') }}" loading="lazy"
                            class="h-full w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 opacity-50 group-hover:opacity-100"
                            alt="Indogrosir">
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-400 group-hover:text-blue-700 uppercase tracking-[0.3em] transition-colors relative z-10">Indogrosir</span>
                </div>

                <div class="shrink-0 w-4 md:w-8"></div>
            </div>

            <button id="slideRight"
                class="absolute right-0 top-1/2 -translate-y-1/2 -mr-4 lg:-mr-6 z-20 w-14 h-14 rounded-full border border-slate-100 bg-white/90 backdrop-blur shadow-xl shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-brand-red hover:border-brand-red hover:bg-white transition-all duration-300 md:opacity-0 md:group-hover/slider:opacity-100 hidden md:flex">
                <i class="fa-solid fa-arrow-right"></i>
            </button>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('partnerSlider');
        const slideLeft = document.getElementById('slideLeft');
        const slideRight = document.getElementById('slideRight');

        slideLeft.addEventListener('click', () => {
            const scrollAmount = slider.clientWidth > 768 ? 352 : 284;
            slider.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        slideRight.addEventListener('click', () => {
            const scrollAmount = slider.clientWidth > 768 ? 352 : 284;
            slider.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // Drag to scroll
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active:cursor-grabbing');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active:cursor-grabbing');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active:cursor-grabbing');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    });
</script>
