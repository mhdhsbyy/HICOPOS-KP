<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | HICOPOS</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-ho.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes ho-guest-in {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }

        .ho-guest-in {
            animation: ho-guest-in 0.6s cubic-bezier(0.22, 1, 0.36, 1) backwards;
        }

        @keyframes ho-kenburns {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.08);
            }
        }

        .ho-kenburns {
            animation: ho-kenburns 20s ease-in-out infinite alternate;
        }

        @media (prefers-reduced-motion: reduce) {
            .ho-guest-in,
            .ho-kenburns {
                animation: none;
            }
        }
    </style>
</head>

<body class="font-sans antialiased overflow-hidden">
    <main class="h-screen w-full flex items-center justify-center px-4 relative overflow-hidden bg-[#1a1410]">

        {{-- Background Pattern --}}
        <div class="ho-kenburns absolute inset-0 bg-cover bg-center opacity-40"
            style="background-image: url('{{ asset('images/bg3.png') }}');">
        </div>

        <div class="ho-guest-in relative z-10 w-full max-w-6xl flex items-center justify-center">
            {{ $slot }}
        </div>
    </main>
</body>

</html>
