<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $host = request()->getHost();

        // Tentukan Nama PT berdasarkan domain
        if (str_contains($host, 'bahtera')) {
            $namaPT = 'Bahtera Login';
        } else {
            $namaPT = 'Mirasa Login';
        }
    @endphp

    <title>{{ $namaPT }}</title>

    <!-- Styles -->
    @vite('resources/css/app.css')

    <meta name="robots" content="noindex, nofollow">
</head>
