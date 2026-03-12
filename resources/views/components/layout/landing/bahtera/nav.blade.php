<nav class="fixed w-full z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-xl shadow-sm border border-slate-100">
                    <img src="{{ asset('assets/logo/BMB-logo.webp') }}" alt="Logo Cap Payung"
                        class="w-10 h-10 rounded-lg flex items-center justify-center">
                </div>
                <div>
                    <span class="block font-extrabold text-xl leading-none text-bmb-blue tracking-tight">BAHTERA</span>
                    <span class="text-[10px] font-bold text-bmb-orange uppercase tracking-[0.2em]">Mandiri
                        Bersama</span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-10">
                <div class="flex gap-8 text-sm font-bold uppercase tracking-wide text-slate-600">
                    <a href="/#home" class="hover:text-bmb-orange transition-colors">Beranda</a>
                    <a href="/#about" class="hover:text-bmb-orange transition-colors">Visi</a>
                    <a href="/#products" class="hover:text-bmb-orange transition-colors">Varian Rasa</a>
                    <a href="/#contact" class="hover:text-bmb-orange transition-colors">Kontak</a>
                </div>
                <a href="{{ route('katalog') }}"
                    class="bg-bmb-orange text-white px-7 py-3 rounded-full text-sm font-extrabold hover:bg-orange-600 transition shadow-lg shadow-orange-200 uppercase">
                    E-Katalog
                </a>
            </div>

            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-slate-600 focus:outline-none">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="fixed inset-0 z-40 hidden md:hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div
            class="fixed right-0 top-0 bottom-0 w-[80%] max-w-sm bg-white shadow-2xl p-8 flex flex-col transform transition-transform duration-300">
            <div class="flex justify-end mb-8">
                <button id="close-menu" class="p-2 text-slate-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col gap-6">
                <a href="#home" class="mobile-link group">
                    <span
                        class="text-2xl font-black text-slate-800 group-hover:text-bmb-orange transition-colors">Beranda</span>
                    <div class="h-1 w-0 bg-bmb-orange group-hover:w-12 transition-all duration-300"></div>
                </a>
                <a href="#about" class="mobile-link group">
                    <span
                        class="text-2xl font-black text-slate-800 group-hover:text-bmb-orange transition-colors">Tentang
                        Kami</span>
                    <div class="h-1 w-0 bg-bmb-orange group-hover:w-12 transition-all duration-300"></div>
                </a>
                <a href="#products" class="mobile-link group">
                    <span
                        class="text-2xl font-black text-slate-800 group-hover:text-bmb-orange transition-colors">Varian
                        Rasa</span>
                    <div class="h-1 w-0 bg-bmb-orange group-hover:w-12 transition-all duration-300"></div>
                </a>
                <a href="#contact" class="mobile-link group">
                    <span
                        class="text-2xl font-black text-slate-800 group-hover:text-bmb-orange transition-colors">Kontak</span>
                    <div class="h-1 w-0 bg-bmb-orange group-hover:w-12 transition-all duration-300"></div>
                </a>
            </nav>

            <div class="mt-auto">
                <a href="{{ route('katalog') }}"
                    class="block w-full bg-gradient-to-r from-bmb-orange to-orange-600 text-white text-center py-4 rounded-2xl font-extrabold text-lg shadow-lg shadow-orange-200 uppercase tracking-wider">
                    Lihat E-Katalog
                </a>
                <p class="text-center text-slate-400 text-xs mt-6 font-medium">© 2024 Bahtera Mandiri Bersama</p>
            </div>
        </div>
    </div>
</nav>
