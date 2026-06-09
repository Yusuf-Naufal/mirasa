<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bahtera Mandiri Bersama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/BMB-logo.webp') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bmb-blue': '#003399',
                        'bmb-orange': '#FF6600',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#FF6600 0.5px, transparent 0.5px), radial-gradient(#003399 0.5px, #f8fafc 0.5px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Mengatur pagination agar benar-benar bulat sempurna */
        .product-swiper .swiper-pagination-bullet {
            width: 12px !important;
            height: 12px !important;
            background: #cbd5e1 !important;
            /* Warna Slate-300 */
            opacity: 1 !important;
            border-radius: 9999px !important;
            /* Full Circle */
            transition: all 0.3s ease;
        }

        /* Mengatur titik yang sedang aktif */
        .product-swiper .swiper-pagination-bullet-active {
            width: 30px !important;
            /* Membuat efek pil/lonjong untuk yang aktif */
            background: #f97316 !important;
            /* Warna bmb-orange (Orange-500) */
            border-radius: 10px !important;
        }

        /* Memberi jarak antara slider dan pagination */
        .product-swiper {
            padding-bottom: 50px !important;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    {{ $slot }}

    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        new Swiper(".product-swiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
            },
        });
    </script>
</body>

</html>
