<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Mirasa Food Industry - Profil Perusahaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}" alt="Logo Mirasa" class="h-10 w-auto">
                <h1 class="text-xl font-bold text-red-600 hidden md:block">PT Mirasa Food Industry</h1>
            </div>

            <div class="space-x-6">
                <a href="#about" class="hover:text-red-600 font-medium">Tentang</a>
                <a href="#products" class="hover:text-red-600 font-medium">Produk</a>
                <a href="#contact" class="hover:text-red-600 font-medium">Kontak</a>
                <a href="{{ route('login') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Portal Admin</a>
            </div>
        </div>
    </nav>

    <header class="relative bg-gradient-to-br from-red-700 via-red-600 to-red-500 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="container mx-auto px-6 text-center relative z-10">
            <span class="inline-block bg-red-800/30 backdrop-blur-sm text-red-100 px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase mb-6">Established 1979</span>
            <h2 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight leading-tight">Keripik Singkong Otentik<br><span class="text-red-200">Kualitas Dunia.</span></h2>
            <p class="text-lg mb-10 text-red-50/90 max-w-2xl mx-auto font-light leading-relaxed">Mengolah singkong pilihan dengan standar higienis tinggi didukung sistem manajemen modern untuk kepuasan pelanggan global.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#products" class="bg-white text-red-600 px-10 py-4 rounded-full font-bold shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all">Lihat Koleksi Produk</a>
                <a href="#about" class="border border-red-300 text-white px-10 py-4 rounded-full font-bold hover:bg-white/10 transition-all">Pelajari Visi Kami</a>
            </div>
        </div>
    </header>

    <section id="about" class="py-24">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap items-center">
                <div class="w-full md:w-1/2 mb-12 md:mb-0 pr-0 md:pr-16">
                    <h3 class="text-sm font-bold text-red-600 tracking-[0.3em] uppercase mb-4">Warisan & Tradisi</h3>
                    <h4 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 tracking-tight">Membangun Kepercayaan Melalui Rasa</h4>
                    <p class="text-gray-600 leading-relaxed mb-8 text-justify">
                        Berawal di tahun 1979 dari industri rumah tangga kecil oleh Bapak Muslich, kami membawa visi sederhana: menghadirkan keripik yang <strong>halal, higienis, dan berkualitas tinggi</strong>. Perjalanan dari distribusi lokal Jakarta hingga merambah pasar internasional seperti Belanda, Inggris, Jerman, dan Qatar adalah bukti dedikasi kami terhadap inovasi rasa tanpa meninggalkan akar otentik <span class="text-red-600 font-semibold italic">CAP PAYUNG</span>.
                    </p>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                            <span class="bg-red-50 text-red-600 p-2 rounded-lg mr-4 font-bold">01</span>
                            <span class="text-gray-700 font-medium">Bahan baku singkong kupas Grade A pilihan</span>
                        </div>
                        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                            <span class="bg-red-50 text-red-600 p-2 rounded-lg mr-4 font-bold">02</span>
                            <span class="text-gray-700 font-medium">Standar pengolahan pangan ekspor</span>
                        </div>
                        <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                            <span class="bg-red-50 text-red-600 p-2 rounded-lg mr-4 font-bold">03</span>
                            <span class="text-gray-700 font-medium">Distribusi Global (Eropa & Timur Tengah)</span>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="relative">
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-red-100 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
                        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
                        <div class="relative bg-white p-3 rounded-3xl shadow-2xl">
                            <img src="{{ asset('images/bg-gudang.jpg') }}" alt="Aktivitas Produksi" class="w-full h-auto rounded-2xl grayscale-[20%] hover:grayscale-0 transition-all duration-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="bg-gray-100 py-24">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h3 class="text-3xl font-bold mb-4 tracking-tight">Katalog Produk Unggulan</h3>
                <div class="h-1 w-20 bg-red-600 mx-auto rounded-full mb-6"></div>
                <p class="text-gray-500">Hasil produksi terbaik dengan varian rasa inovatif yang siap memanjakan lidah Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $p)
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-transparent hover:border-red-100 overflow-hidden">
                    <div class="h-56 bg-gray-50 overflow-hidden relative">
                        @if($p->foto)
                            <img src="{{ asset('storage/' . $p->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <span class="text-gray-400 italic text-xs uppercase tracking-tighter">[Foto {{ $p->nama_barang }}]</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full shadow-sm">
                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-widest">{{ $p->JenisBarang->nama_jenis ?? 'Snack' }}</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h4 class="font-bold text-lg mb-4 text-gray-900 group-hover:text-red-600 transition-colors">{{ $p->nama_barang }}</h4>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="text-green-700 text-[10px] font-bold uppercase tracking-wider">Ready Stock</span>
                            </div>
                            <span class="text-gray-300 font-light">|</span>
                            <span class="text-xs font-medium text-gray-500 italic">Premium Quality</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-gray-900 rounded-[3rem] overflow-hidden shadow-2xl flex flex-wrap">
                <div class="w-full lg:w-1/2 h-80 lg:h-auto grayscale contrast-125 opacity-80">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15818.868713295!2d110.19!3d-7.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzAnMDAuMCJTIDExMMKwMTEnMjQuMCJF!5e0!3m2!1sid!2sid!4v123456789" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
                <div class="w-full lg:w-1/2 p-12 lg:p-20 text-white">
                    <h3 class="text-3xl font-bold mb-10 tracking-tight">Hubungi Operasional</h3>
                    <div class="space-y-8">
                        <div class="flex items-start group">
                            <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mr-6 group-hover:bg-red-600 transition-colors">
                                <span class="text-xl">📍</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-red-500 text-sm uppercase tracking-widest mb-1">Kantor Pusat</h5>
                                <p class="text-gray-400 text-sm leading-relaxed">Jl. Munggur No.2 Kalangan Wetan, Ambartawang, Japun I, Paremono, Kec. Mungkid, Magelang, Jawa Tengah 56512</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mr-6 group-hover:bg-red-600 transition-colors">
                                <span class="text-xl">📞</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-red-500 text-sm uppercase tracking-widest mb-1">WhatsApp Business</h5>
                                <p class="text-gray-400 text-sm leading-relaxed">+62 812-3456-7890</p>
                            </div>
                        </div>
                        <div class="flex items-start group">
                            <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mr-6 group-hover:bg-red-600 transition-colors">
                                <span class="text-xl">✉️</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-red-500 text-sm uppercase tracking-widest mb-1">Email Korespondensi</h5>
                                <p class="text-gray-400 text-sm leading-relaxed">admin@mirasafood.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-100 pt-20 pb-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <img src="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}" alt="Logo Mirasa" class="h-12 mb-6">
                    <p class="text-gray-500 text-sm max-w-sm leading-relaxed">Menjadi mitra terpercaya dalam industri olahan pangan berbasis singkong dengan kualitas standar internasional.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-gray-900 uppercase text-xs tracking-widest border-l-4 border-red-600 pl-3">Kemitraan</h4>
                    <p class="text-sm text-gray-500 leading-relaxed">Anak Perusahaan:<br><strong class="text-gray-800">CV. Bahtera Mandiri Bersama</strong></p>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-gray-900 uppercase text-xs tracking-widest border-l-4 border-red-600 pl-3">Media Sosial</h4>
                    <div class="flex flex-col space-y-3">
                        <a href="#" class="text-sm text-gray-500 hover:text-red-600 transition">Instagram</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-red-600 transition">Facebook</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-red-600 transition">Shopee Official</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-10 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em]">© 2026 PT Mirasa Food Industry. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>