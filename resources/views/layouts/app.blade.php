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
        #main-content {
            margin-left: 18rem;
            transition: margin-left .3s ease;
        }

        #main-content.sidebar-content-collapsed {
            margin-left: 5rem;
        }

        main {
            height: calc(100vh - 6rem);
            overflow-y: auto;
            padding: 2rem;
        }
    </style>
</head>

<body class="bg-light font-inter text-dark overflow-hidden">

    @include('layouts.part.sidebar')

    <div id="main-content" class="h-screen">

        @include('layouts.part.header')

        <main>
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartCanvas = document.getElementById('chart');
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: ['20', '21', '22', '23', '24', '25', '26'],
                    datasets: [{
                        data: [2, 4, 6, 3, 7, 5, 8],
                        borderColor: '#764706',
                        backgroundColor: 'rgba(118,71,6,0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                }
            });
        }

        const produkCanvas = document.getElementById('produkChart');
        if (produkCanvas) {
            new Chart(produkCanvas, {
                type: 'bar',
                data: {
                    labels: ['Es Kopi Susu', 'Cappuccino', 'Americano'],
                    datasets: [{
                        data: [2800000, 2200000, 1400000],
                        backgroundColor: ['#764706', '#ADA68A', '#FFF0DA'],
                        borderRadius: 8
                    }]
                }
            });
        }
    </script>

</body>

</html>
