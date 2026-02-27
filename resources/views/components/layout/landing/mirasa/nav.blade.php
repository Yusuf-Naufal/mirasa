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
                        <img src="{{ asset('assets/logo/Mirasa-logo.webp') }}" alt="Logo Mirasa"
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
                        <span>@translate('Industri Unggul')</span>
                    </span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-12">
                <div class="flex gap-10 text-[11px] font-black uppercase tracking-[0.2em] text-slate-500">
                    <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#history"
                        class="relative hover:text-brand-red transition-all duration-300 group">
                        @translate('Sejarah')
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#owner"
                        class="relative hover:text-brand-red transition-all duration-300 group">
                        @translate('Pemilik')
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#vision"
                        class="relative hover:text-brand-red transition-all duration-300 group">
                        @translate('Strategi')
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#stats"
                        class="relative hover:text-brand-red transition-all duration-300 group">
                        @translate('Produksi')
                        <span
                            class="absolute -bottom-2 left-0 w-0 h-[2px] bg-brand-red transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <div class="flex items-center gap-6 border-l border-slate-200 pl-8">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 px-4 py-2 rounded-xl border border-slate-200 hover:border-brand-red transition-all duration-300 group">
                            <div
                                class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-brand-red transition-colors">
                                <i class="fa-solid fa-globe text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-black tracking-widest uppercase text-slate-600">
                                {{ request('lang', 'ID') == 'EN-US' ? 'English' : (request('lang') == 'JA' ? 'Japanese' : 'Indonesia') }}
                            </span>
                            <i class="fa-solid fa-chevron-down text-[8px] text-slate-400 group-hover:text-brand-red transition-transform"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-slate-100 py-2 z-[110]">

                            <a href="{{ request()->fullUrlWithQuery(['lang' => 'ID']) }}"
                                class="flex items-center justify-between px-5 py-3 text-[10px] font-black uppercase tracking-widest {{ request('lang', 'ID') == 'ID' ? 'text-brand-red bg-slate-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                Bahasa Indonesia
                                @if (request('lang', 'ID') == 'ID')
                                    <i class="fa-solid fa-check"></i>
                                @endif
                            </a>

                            <a href="{{ request()->fullUrlWithQuery(['lang' => 'EN-US']) }}"
                                class="flex items-center justify-between px-5 py-3 text-[10px] font-black uppercase tracking-widest {{ request('lang') == 'EN-US' ? 'text-brand-red bg-slate-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                English
                                @if (request('lang') == 'EN-US')
                                    <i class="fa-solid fa-check"></i>
                                @endif
                            </a>

                            <a href="{{ request()->fullUrlWithQuery(['lang' => 'JA']) }}"
                                class="flex items-center justify-between px-5 py-3 text-[10px] font-black uppercase tracking-widest {{ request('lang') == 'JA' ? 'text-brand-red bg-slate-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                Japanese
                                @if (request('lang') == 'JA')
                                    <i class="fa-solid fa-check"></i>
                                @endif
                            </a>
                        </div>
                    </div>

                    <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#partners"
                        class="bg-slate-900 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-red hover:shadow-[0_10px_20px_-5px_rgba(227,6,19,0.4)] transition-all duration-500">
                        @translate('Mitra')
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
            <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#history" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    @translate('Sejarah')
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#owner" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    @translate('Pemilik')
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#vision" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    @translate('Strategi')
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#stats" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 border-b border-slate-50 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    @translate('Produksi')
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>

            <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#partners" onclick="toggleMobileMenu()"
                class="group flex justify-between items-center py-4 transition-all">
                <span
                    class="text-xl font-black text-slate-900 uppercase tracking-tighter group-hover:text-brand-red transition-colors">
                    @translate('Mitra')
                </span>
                <i
                    class="fa-solid fa-arrow-right text-brand-red opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
            </a>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <div class="pt-6 border-t border-slate-100" x-data="{ openMobLang: false }">
                <button @click="openMobLang = !openMobLang"
                    class="w-full flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-globe text-slate-400"></i>
                        <span
                            class="text-xs font-black uppercase tracking-widest text-slate-900">@translate('Pilih Bahasa')</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform"
                        :class="openMobLang ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openMobLang" x-collapse class="mt-2 space-y-1">
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'ID']) }}"
                        class="block w-full p-4 rounded-xl text-[11px] font-black uppercase tracking-widest {{ request('lang', 'ID') == 'ID' ? 'bg-brand-red text-white' : 'text-slate-600 bg-white border border-slate-50' }}">
                        Indonesia
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'EN-US']) }}"
                        class="block w-full p-4 rounded-xl text-[11px] font-black uppercase tracking-widest {{ request('lang') == 'EN-US' ? 'bg-brand-red text-white' : 'text-slate-600 bg-white border border-slate-50' }}">
                        English
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['lang' => 'JA']) }}"
                        class="block w-full p-4 rounded-xl text-[11px] font-black uppercase tracking-widest {{ request('lang') == 'JA' ? 'bg-brand-red text-white' : 'text-slate-600 bg-white border border-slate-50' }}">
                        Japanese
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
