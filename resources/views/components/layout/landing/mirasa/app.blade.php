<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Mirasa Food Industry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/Mirasa-logo.webp') }}">
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

        .active-en .lang-en {
            display: block;
        }

        .active-en .lang-id {
            display: none;
        }

        .mobile-menu-open {
            overflow: hidden;
        }

        .img-border {
            border: 8px solid white;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            border-radius: 1.5rem;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .red-gradient {
            background: linear-gradient(135deg, #E30613 0%, #9b040d 100%);
        }
    </style>
</head>

<body class="bg-white text-slate-900 transition-all duration-500">

    {{ $slot }}

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
