<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Super Admin</title>

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

{{-- HANDLE SIDEBAR --}}
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

{{-- HANDLE MODAL --}}
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return console.error(`Modal dengan id "${id}" tidak ditemukan.`);
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // pastikan tampil dengan flex centering
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Tutup modal saat klik di luar konten
    window.addEventListener('click', (e) => {
        const openModals = document.querySelectorAll('[id$="Modal"]:not(.hidden)');
        openModals.forEach(modal => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
</script>

{{-- HANDLE DELETE & ACTIVE ALERT --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Data ini akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hilang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika dikonfirmasi
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function confirmActivate(id) {
        Swal.fire({
            title: 'Aktifkan Data ini?',
            text: "Data akan diaktifkan kembali.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a', // Hijau
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('aktif-form-' + id).submit();
            }
        });
    }
</script>

{{-- HANDLE NAV BUTTON --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        // Fungsi Toggle Dropdown
        userMenuButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Mencegah klik menyebar ke window
            userDropdown.classList.toggle('hidden');
        });

        // Tutup dropdown jika klik di luar area menu
        window.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    });
</script>

</html>
