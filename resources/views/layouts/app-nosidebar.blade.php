<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Hideout Coffee</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ho.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        main {
            height: calc(100vh - 6rem);
            overflow-y: auto;
            padding: 2rem;
        }
    </style>
</head>

<body class="bg-light font-inter text-dark overflow-hidden">

    <div class="h-screen flex flex-col">

        @include('layouts.part.header')

        <main class="flex-1 min-h-0">
            @yield('content')
        </main>

    </div>

</body>

</html>
