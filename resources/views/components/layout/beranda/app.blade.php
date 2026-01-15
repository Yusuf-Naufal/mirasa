<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Beranda' }}</title>

    @if(auth()->user()->perusahaan && auth()->user()->perusahaan->logo)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . auth()->user()->perusahaan->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo/logo_pt_mirasa_food-removebg-preview.png') }}">
    @endif

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-gray-50 font-sans antialiased group/sidebar">

    <x-layout.user.nav />

    <main id="main-content" class="flex-1 overflow-y-auto p-4">
        <div>
            {{ $slot }}
        </div>
    </main>

</body>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


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

{{-- HANDLE ALL ALERT --}}
@if (session('success'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#DFF5E3',
            color: '#0D1630'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#FEE2E2',
            color: '#0D1630'
        });
    </script>
@endif

@if ($errors->any())
    <script>
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '- {{ $error }}\n';
        @endforeach

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: errorMessages,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            background: '#FEE2E2',
            color: '#0D1630'
        });
    </script>
@endif

</html>
