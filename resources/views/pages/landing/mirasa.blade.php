<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Mirasa Food Industry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#E30613',
                        'brand-dark': '#0F172A',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                }
            }
        }
    </script>
    <style>
        .lang-en {
            display: none;
        }
=======
<x-layout.landing.mirasa.app>

    <x-layout.landing.mirasa.nav />
>>>>>>> 655b55dac45b850aee465055544a53ccf8dd4129

    <x-layout.landing.mirasa.section.hero />

    <x-layout.landing.mirasa.section.history />

    {{-- <x-layout.landing.mirasa.section.owner /> --}}

    <x-layout.landing.mirasa.section.vision />

    <x-layout.landing.mirasa.section.stats />

    <x-layout.landing.mirasa.section.partners />

    <x-layout.landing.mirasa.section.certificates />

    <x-layout.landing.mirasa.section.news :berita="$berita" />

    <x-layout.landing.mirasa.section.distributor />

    <footer class="bg-[#0A0F1A] text-white pt-24 pb-12 px-6 relative overflow-hidden">
        <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-brand-red to-transparent opacity-50">
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-8 mb-20">

                <div class="lg:col-span-5 space-y-8 text-center lg:text-left">
                    <div class="flex flex-col items-center lg:items-start gap-4">
                        <img src="{{ asset('assets/logo/Mirasa-logo.webp') }}" loading="lazy" class="h-16 w-auto"
                            alt="Footer Logo">
                        <div>
                            <span class="block font-black text-xl tracking-tighter uppercase">PT Mirasa Food
                                Industry</span>
                            <span
                                class="text-[9px] font-bold text-brand-red uppercase tracking-[0.3em]">@translate('Warisan Kualitas')</span>
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
                            {{-- <li>
                                <a href="{{ url('/') }}?lang={{ request('lang', 'ID') }}#owner"
                                    class="hover:text-white transition-colors">
                                    @translate('Pemilik')
                                </a>
                            </li> --}}
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

                <div
                    class="lg:col-span-4 bg-white/5 p-6 md:p-8 rounded-[2.5rem] border border-white/10 hover:border-white/20 transition-all duration-300 backdrop-blur-md shadow-2xl flex flex-col h-full">

                    <div class="mb-8 text-left">
                        <span
                            class="text-[10px] font-black uppercase tracking-[0.3em] text-brand-red bg-brand-red/10 px-4 py-2 rounded-full inline-block border border-brand-red/20 shadow-sm">
                            @translate('Kantor Pusat')
                        </span>
                    </div>

                    <div class="space-y-8 flex-1 flex flex-col items-start justify-between">

                        <ul class="items-start space-y-6 w-full">
                            <li class="flex items-start gap-4 group">
                                <div
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center shrink-0 group-hover:bg-brand-red transition-colors duration-300 shadow-inner">
                                    <i
                                        class="fa-solid fa-location-dot text-brand-red group-hover:text-white transition-colors text-sm"></i>
                                </div>
                                <p class="text-sm text-slate-300 leading-relaxed font-medium mt-1 text-left max-w-xs">
                                    Jalan Munggur No. 2 Ambartawang, Japun Satu, Paremono, Kec. Mungkid, Kabupaten
                                    Magelang, Jawa Tengah 56512
                                </p>
                            </li>

                            <li class="flex items-start gap-4 group">
                                <div
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center shrink-0 group-hover:bg-brand-red transition-colors duration-300 shadow-inner">
                                    <i
                                        class="fa-solid fa-envelope text-brand-red group-hover:text-white transition-colors text-sm"></i>
                                </div>
                                <div class="flex flex-col text-left mt-0.5">
                                    <a href="mailto:mirasafoodhrd@gmail.com"
                                        class="text-sm text-slate-300 font-medium hover:text-white transition-colors">mirasafoodhrd@gmail.com</a>
                                    <a href="mailto:mirasafood@yahoo.co.id"
                                        class="text-sm text-slate-400 font-medium hover:text-white transition-colors">mirasafood@yahoo.co.id</a>
                                </div>
                            </li>

                            <li class="flex items-start gap-4 group">
                                <div
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center shrink-0 group-hover:bg-[#25D366] transition-colors duration-300 shadow-inner">
                                    <i
                                        class="fa-brands fa-whatsapp text-[#25D366] group-hover:text-white transition-colors text-lg"></i>
                                </div>
                                <div class="flex flex-col text-left mt-0.5">
                                    <a href="https://wa.me/6287880809279" target="_blank"
                                        class="text-sm text-slate-300 font-medium hover:text-[#25D366] transition-colors">
                                        +62 878-8080-9279<span
                                            class="text-[9px] text-slate-500 uppercase tracking-widest ml-1 bg-white/10 px-2 py-0.5 rounded">(CS
                                            1)</span>
                                    </a>
                                    <a href="https://wa.me/6281328040219" target="_blank"
                                        class="text-sm text-slate-400 font-medium hover:text-[#25D366] transition-colors mt-1">
                                        +62 813-2804-0219 <span
                                            class="text-[9px] text-slate-500 uppercase tracking-widest ml-1 bg-white/10 px-2 py-0.5 rounded">(CS
                                            2)</span>
                                    </a>
                                </div>
                            </li>
                        </ul>

                        <div class="space-y-4 pt-6 border-t border-white/5 w-full">
                            <div
                                class="relative w-full h-48 rounded-2xl overflow-hidden border border-white/10 shadow-inner group/map">
                                <iframe
                                    class="absolute inset-0 w-full h-full border-0 filter grayscale opacity-70 group-hover/map:grayscale-0 group-hover/map:opacity-100 transition-all duration-700"
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15820.473972317046!2d110.24919329705028!3d-7.562057721282352!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a8bfcfb06852f%3A0xdcdf376e26390df9!2sPT.%20Mirasa%20Food%20Industry!5e0!3m2!1sid!2sid!4v1770107697111!5m2!1sid!2sid"
                                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>

                                <div
                                    class="absolute inset-0 pointer-events-none bg-gradient-to-t from-[#0A0F1A] via-transparent to-transparent group-hover/map:opacity-0 transition-opacity duration-500">
                                </div>

                                <div
                                    class="absolute top-4 right-4 w-3 h-3 bg-brand-red rounded-full shadow-[0_0_15px_rgba(214,28,28,1)] animate-pulse">
                                </div>
                            </div>

                            <a href="https://maps.app.goo.gl/JcwMj1BrfzmVC4vT8" target="_blank"
                                class="flex items-center justify-center w-full lg:w-fit px-6 py-3 rounded-xl bg-white/5 hover:bg-brand-red border border-white/10 hover:border-brand-red text-[10px] font-black text-white uppercase tracking-[0.2em] transition-all duration-300 group/btn">
                                @translate('Buka di Google Maps')
                                <i
                                    class="fa-solid fa-arrow-up-right-from-square ml-3 text-[10px] group-hover/btn:-translate-y-0.5 group-hover/btn:translate-x-0.5 transition-transform"></i>
                            </a>
                        </div>

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
<<<<<<< HEAD
</body>
</html>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentYear = new Date().getFullYear();
            const startYear = 1979;
            const experience = currentYear - startYear;
            const expElement = document.getElementById('experience-years');
            if (expElement) expElement.innerText = experience + '+';
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon-font');
            const isHidden = menu.classList.contains('hidden');

            if (isHidden) {
                menu.classList.remove('hidden');

                setTimeout(() => {
                    menu.classList.remove('scale-y-0', 'opacity-0');
                    menu.classList.add('scale-y-100', 'opacity-100');
                }, 10);

                menuIcon.classList.replace('fa-bars-staggered', 'fa-xmark');
                document.body.style.overflow = 'hidden';
            } else {
                menu.classList.remove('scale-y-100', 'opacity-100');
                menu.classList.add('scale-y-0', 'opacity-0');
                menuIcon.classList.replace('fa-xmark', 'fa-bars-staggered');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 500);
                document.body.style.overflow = 'auto';
            }
        }

        function toggleLang() {
            document.body.classList.toggle('active-en');
            const label = document.getElementById('lang-label');
            label.innerText = document.body.classList.contains('active-en') ? 'ID' : 'EN';
        }
    </script>
</body>

</html>
=======

</x-layout.landing.mirasa.app>
>>>>>>> 655b55dac45b850aee465055544a53ccf8dd4129
