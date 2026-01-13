<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Mirasa Food Industry</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .hero-gradient { background: radial-gradient(circle at top right, #ef4444, #b91c1c); }
    </style>
</head>
<body class="bg-[#FAFAFA] text-slate-900 overflow-x-hidden">

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-100 shadow-sm">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <div class="flex items-center gap-4">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('/assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}" 
                         alt="Logo" 
                         class="w-12 h-12 object-contain group-hover:scale-105 transition-transform">
                    <span class="text-xl font-black tracking-tighter text-red-600 uppercase">
                        {{ $perusahaan->nama_perusahaan ?? 'MIRASA FOOD INDUSTRY' }}
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="#about" class="text-sm font-bold text-gray-500 hover:text-red-600 transition-colors uppercase tracking-widest">Tentang</a>
                <a href="#products" class="text-sm font-bold text-gray-500 hover:text-red-600 transition-colors uppercase tracking-widest">Produk</a>
                <a href="#contact" class="text-sm font-bold text-gray-500 hover:text-red-600 transition-colors uppercase tracking-widest">Kontak</a>
            </div>

            <div class="flex items-center">
                <a href="{{ route('login') }}" class="flex items-center bg-gray-900 text-white rounded-full px-8 py-2.5 text-sm font-bold hover:bg-red-600 transition-all shadow-lg shadow-gray-200">
                    LOGIN SYSTEM
                </a>
            </div>

        </div>
    </div>
</nav>

    <header class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
        
        <div class="absolute top-1/4 -right-20 w-96 h-96 bg-red-400 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-1/4 -left-20 w-72 h-72 bg-orange-400 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full mb-8">
                    <span class="flex h-2 w-2 rounded-full bg-red-300 animate-ping"></span>
                    <span class="text-white text-xs font-bold tracking-[0.2em] uppercase">Global Export Quality</span>
                </div>
                <h2 class="text-5xl md:text-8xl font-extrabold text-white mb-8 tracking-tighter leading-[1.1]">
                    Citarasa <span class="text-red-200">Otentik</span> <br>Sejak 1979.
                </h2>
                <p class="text-lg md:text-xl text-red-50/80 mb-12 max-w-2xl font-light leading-relaxed">
                    Dedikasi PT Mirasa Food Industry dalam mengolah singkong Grade A pilihan menjadi keripik legendaris <span class="font-bold italic">Cap Payung</span> yang kini dinikmati hingga pasar Eropa dan Timur Tengah.
                </p>
                <div class="flex flex-wrap gap-5">
                    <a href="#products" class="group bg-white text-red-600 px-8 py-4 rounded-2xl font-bold shadow-2xl hover:bg-slate-900 hover:text-white transition-all duration-300 flex items-center gap-3">
                        Eksplor Produk
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="relative z-20 -mt-16 container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 text-center">
                <div class="text-3xl font-extrabold text-red-600 mb-1">45+</div>
                <div class="text-gray-500 text-xs font-bold uppercase tracking-widest">Tahun Pengalaman</div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 text-center">
                <div class="text-3xl font-extrabold text-red-600 mb-1">10+</div>
                <div class="text-gray-500 text-xs font-bold uppercase tracking-widest">Negara Ekspor</div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 text-center">
                <div class="text-3xl font-extrabold text-red-600 mb-1">100%</div>
                <div class="text-gray-500 text-xs font-bold uppercase tracking-widest">Bahan Alami</div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 text-center">
                <div class="text-3xl font-extrabold text-red-600 mb-1">Grade A</div>
                <div class="text-gray-500 text-xs font-bold uppercase tracking-widest">Standar Kualitas</div>
            </div>
        </div>
    </section>

    <section id="about" class="py-32">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-64 h-64 bg-red-100 rounded-full filter blur-3xl opacity-60"></div>
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white group">
                        <img src="{{ asset('images/bg-gudang.jpg') }}" alt="Produksi" class="w-full h-[500px] object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white">
                            <p class="text-sm font-bold tracking-widest uppercase mb-2">Internal Facility</p>
                            <h5 class="text-xl font-bold">Standardisasi Ekspor Higienis</h5>
                        </div>
                    </div>
                </div>
                
                <div>
                    <span class="text-red-600 font-bold tracking-[0.3em] uppercase text-sm mb-4 block">Our Heritage</span>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-8 leading-tight">Membawa Kearifan Lokal ke Meja Global.</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-8">
                        Berawal dari industri rumahan oleh <strong>Bapak Muslich</strong> pada 1979, kami bertransformasi menjadi pemimpin industri olahan singkong. Komitmen kami pada kualitas <span class="text-red-600 font-semibold italic">CAP PAYUNG</span> tidak pernah berubah.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-10 w-10 bg-red-50 rounded-full flex items-center justify-center text-red-600 font-bold">✓</div>
                            <p class="font-semibold text-slate-700">Sertifikasi Halal & BPOM Internasional</p>
                        </div>
                        <div class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-10 w-10 bg-red-50 rounded-full flex items-center justify-center text-red-600 font-bold">✓</div>
                            <p class="font-semibold text-slate-700">Jaringan Distribusi Global Terintegrasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="py-32 bg-slate-900 text-white rounded-[4rem] mx-4">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div class="max-w-xl">
                    <span class="text-red-500 font-bold tracking-[0.3em] uppercase text-sm mb-4 block">Product Showcase</span>
                    <h3 class="text-4xl md:text-5xl font-extrabold tracking-tight">Varian Unggulan Kami</h3>
                </div>
                <p class="text-slate-400 max-w-xs text-sm border-l-2 border-red-600 pl-6 leading-relaxed">
                    Setiap varian diracik dengan bumbu alami tanpa pengawet buatan untuk menjaga kemurnian rasa.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $p)
                <div class="group relative bg-white/5 border border-white/10 rounded-3xl p-4 hover:bg-white hover:text-slate-900 transition-all duration-500">
                    <div class="relative h-64 mb-6 overflow-hidden rounded-2xl bg-slate-800">
                        @if($p->foto)
                            <img src="{{ asset('storage/' . $p->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-600 text-[10px] uppercase font-bold tracking-tighter">
                                No Image Available
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase">
                            {{ $p->JenisBarang->nama_jenis ?? 'Snack' }}
                        </div>
                    </div>
                    
                    <h4 class="text-xl font-bold mb-2">{{ $p->nama_barang }}</h4>
                    <div class="flex items-center justify-between opacity-60 group-hover:opacity-100 transition-opacity">
                        <span class="text-xs font-bold uppercase tracking-widest text-red-500">Premium Grade</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest">Netto: 250g</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer id="contact" class="pt-32 pb-12">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-20 mb-24">
                <div class="col-span-1">
                    <img src="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}" alt="Logo" class="h-16 mb-8">
                    <p class="text-slate-500 leading-relaxed mb-8">
                        Menjadi pionir olahan singkong dunia dengan inovasi berkelanjutan dan pemberdayaan petani lokal.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all text-xs font-bold">IG</a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all text-xs font-bold">FB</a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all text-xs font-bold">LI</a>
                    </div>
                </div>
                
                <div class="col-span-2 grid md:grid-cols-2 gap-12 bg-white p-12 rounded-[3rem] shadow-sm border border-slate-100">
                    <div>
                        <h4 class="font-extrabold text-slate-900 mb-6 uppercase tracking-widest text-xs">Lokasi Operasional</h4>
                        <div class="flex gap-4">
                            <span class="text-red-600 font-bold italic">📍</span>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Jl. Munggur No.2, Ambartawang, Kec. Mungkid, Magelang, Jawa Tengah 56512
                            </p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 mb-6 uppercase tracking-widest text-xs">Hubungi Kami</h4>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 text-sm text-slate-500 hover:text-red-600 transition-colors">
                                <span class="bg-red-50 p-2 rounded-lg text-red-600 italic">✉</span> admin@mirasafood.com
                            </div>
                            <div class="flex items-center gap-4 text-sm text-slate-500 hover:text-red-600 transition-colors">
                                <span class="bg-red-50 p-2 rounded-lg text-red-600 italic">📞</span> +62 812 3456 7890
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-12 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] text-slate-400 uppercase tracking-[0.3em]">© 2026 PT Mirasa Food Industry. All Rights Reserved.</p>
                <div class="flex gap-8">
                    <a href="#" class="text-[10px] uppercase font-bold text-slate-400 hover:text-slate-900 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-[10px] uppercase font-bold text-slate-400 hover:text-slate-900 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>