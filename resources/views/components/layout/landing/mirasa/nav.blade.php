<nav id="main-nav"
    class="fixed w-full z-[100] transition-all duration-500 bg-white/70 backdrop-blur-xl border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 transition-all duration-500" id="nav-container">

            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="relative">
                    <div
                        class="absolute inset-0 bg-brand-red/20 blur-xl rounded-full scale-0 group-hover:scale-125 transition-transform duration-500">
                    </div>
                    <div
                        class="relative bg-white p-2 rounded-2xl shadow-sm border border-slate-100 group-hover:border-brand-red/20 transition-all duration-500">
                        <img src="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}" alt="Logo Mirasa"
                            class="w-10 h-10 object-contain transform group-hover:rotate-12 transition-transform duration-500">
                    </div>
                </div>
                <div class="flex flex-col">
                    <span
                        class="block font-black text-xl leading-none text-slate-900 tracking-tighter uppercase group-hover:text-brand-red transition-colors duration-300">
                        Mirasa Food <span class="font-light text-slate-400">Industry</span>
                    </span>
                    <span
                        class="text-[9px] font-black text-brand-red uppercase tracking-[0.4em] leading-none mt-1 opacity-70">
                        <span class="lang-id">Industri Unggul</span>
                        <span class="lang-en">Industrial Excellence</span>
                    </span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-12">
                <div class="flex gap-10 text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">
                    <a href="/#history" class="relative hover:text-brand-red transition-all duration-300 group">
                        <span class="lang-id">Sejarah</span><span class="lang-en">History</span>
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#owner" class="relative hover:text-brand-red transition-all duration-300 group">
                        <span class="lang-id">Pemilik</span><span class="lang-en">Owner</span>
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#vision" class="relative hover:text-brand-red transition-all duration-300 group">
                        <span class="lang-id">Visi</span><span class="lang-en">Vision</span>
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="/#stats" class="relative hover:text-brand-red transition-all duration-300 group">
                        <span class="lang-id">Produksi</span><span class="lang-en">Production</span>
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <div class="flex items-center gap-6 border-l border-slate-200 pl-8">
                    <button onclick="toggleLang()"
                        class="group flex items-center gap-2 text-slate-400 hover:text-brand-red transition-all duration-300">
                        <span class="text-[10px] font-black tracking-widest" id="lang-label">EN</span>
                        <div
                            class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:border-brand-red group-hover:bg-brand-red group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-globe text-[10px]"></i>
                        </div>
                    </button>

                    <a href="/#partners"
                        class="bg-slate-900 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-red hover:shadow-[0_10px_20px_-5px_rgba(227,6,19,0.4)] transition-all duration-500">
                        <span class="lang-id">Mitra</span><span class="lang-en">Partners</span>
                    </a>
                </div>
            </div>

            <div class="md:hidden">
                <button id="mobile-menu-button" onclick="toggleMobileMenu()"
                    class="relative w-12 h-12 flex items-center justify-center bg-slate-50 rounded-2xl border border-slate-200 text-slate-900 hover:border-brand-red transition-all duration-300">
                    <i class="fa-solid fa-bars-staggered text-xl" id="menu-icon-font"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu"
        class="hidden fixed inset-x-0 top-[80px] bg-white/95 backdrop-blur-2xl border-b border-slate-200 px-8 py-10 space-y-8 shadow-2xl transition-all duration-500 origin-top transform scale-y-0 opacity-0 z-[90]">

        <div class="grid grid-cols-1 gap-4">
            <a href="/#history" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    <span class="lang-id">Sejarah</span><span class="lang-en">History</span>
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="/#owner" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    <span class="lang-id">Pemilik</span><span class="lang-en">Owner</span>
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="/#vision" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    <span class="lang-id">Visi</span><span class="lang-en">Vision</span>
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="/#stats" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    <span class="lang-id">Produksi</span><span class="lang-en">Production</span>
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="/#partners" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    <span class="lang-id">Mitra</span><span class="lang-en">Partners</span>
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <button onclick="toggleLang()"
                class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-lg shadow-slate-200 active:scale-95 transition-all">
                <i class="fa-solid fa-globe"></i>
                <span>Switch Language (ID/EN)</span>
            </button>
        </div>
    </div>
</nav>
