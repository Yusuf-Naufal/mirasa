<nav class="fixed w-full z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-3">
                <div class="bg-white p-1.5 rounded-xl shadow-sm border border-slate-100">
                    <img src="{{ asset('assets/logo/logo-BMB-removebg-preview.png') }}" alt="Logo Cap Payung"
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

    <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-slate-200 px-6 py-8 space-y-6 shadow-xl">
        <a href="#home" class="block text-lg font-bold text-slate-800">Beranda</a>
        <a href="#about" class="block text-lg font-bold text-slate-800">Tentang Kami</a>
        <a href="#products" class="block text-lg font-bold text-slate-800">Varian Rasa</a>
        <a href="#contact" class="block text-lg font-bold text-slate-800">Kontak</a>
        <button class="w-full bg-bmb-blue text-white py-4 rounded-2xl font-bold">E-Katalog</button>
    </div>
</nav>
