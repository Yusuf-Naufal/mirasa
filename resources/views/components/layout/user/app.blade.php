<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Super Admin</title>

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')

    <style>
        /* Hanya geser konten di layar Desktop (min-width 640px) */
        @media (min-width: 640px) {
            #logo-sidebar:hover~#main-content {
                margin-left: 16rem;
                /* 16rem = w-64 */
            }

            /* Pastikan teks sidebar selalu terlihat saat di-hover di desktop */
            #logo-sidebar:hover span,
            #logo-sidebar:hover p {
                opacity: 1 !important;
            }
        }

        /* Di Mobile, pastikan teks sidebar selalu terlihat jika sidebar sedang terbuka */
        @media (max-width: 639px) {

            #logo-sidebar span,
            #logo-sidebar p {
                opacity: 1 !important;
            }
        }
    </style>

</head>

<body class="bg-gray-50 font-sans antialiased group/sidebar">

    <x-layout.user.nav />

    <x-layout.user.aside />

    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-gray-900/50 hidden sm:hidden"></div>

    <main id="main-content" class="p-4 sm:ml-20 transition-all duration-300 ease-in-out">
        <div class="p-4 mt-14 space-y-4">
            <x-layout.user.breadcrumbs />

            <div>
                {{ $slot }}
            </div>
        </div>
    </main>

</body>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('logo-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.querySelector('[data-drawer-toggle="logo-sidebar"]');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            // Mencegah body scroll saat menu buka di HP
            document.body.classList.toggle('overflow-hidden');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }
    });
</script>

</html>
