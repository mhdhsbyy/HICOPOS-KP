<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Hideout Coffee</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ho.png') }}">

    <script>
        (function () {
            try {
                document.documentElement.dataset.navKasir =
                    localStorage.getItem('navKasirCollapsed') === 'true' ? 'collapsed' : 'open';
            } catch (e) {
                document.documentElement.dataset.navKasir = 'open';
            }
        })();
    </script>

    <style>
        #nav-kasir {
            max-height: 4.5rem;
            transition: max-height 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }

        html[data-nav-kasir="collapsed"] #nav-kasir {
            max-height: 0;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(form, message) {
            Swal.fire({
                icon: 'warning',
                title: 'Yakin?',
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#764706',
                cancelButtonColor: '#e2d3c2',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal-card-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function confirmCancel(form, message) {
            Swal.fire({
                icon: 'warning',
                title: 'Yakin?',
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#e2d3c2',
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Tidak',
                customClass: {
                    popup: 'swal-card-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function blockedDelete(message) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message,
                confirmButtonColor: '#764706',
                confirmButtonText: 'OK',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-card-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            });
        }

        function confirmLogout(form, message) {
            Swal.fire({
                icon: 'warning',
                title: 'Keluar?',
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#e2d3c2',
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal-card-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    localStorage.removeItem('pos_cart');
                    localStorage.removeItem('pos_state');
                    form.submit();
                }
            });
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .swal-card-custom {
            border-radius: 1rem !important;
            border: 1px solid #f0e8e0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            padding: 1.5rem !important;
        }
        .swal-card-custom .swal2-icon {
            margin: 0 auto 0.5rem !important;
        }
        .swal-card-custom .swal2-title {
            color: #2b1f17 !important;
            font-weight: 900 !important;
            font-size: 1.25rem !important;
        }
        .swal-card-custom .swal2-html-container {
            color: #5f4a3b !important;
            font-size: 0.875rem !important;
            margin: 0.5rem 0 0 !important;
        }
        .swal-card-custom .swal2-confirm {
            border-radius: 0.75rem !important;
            font-weight: 700 !important;
        }
        .swal-card-custom .swal2-cancel {
            border-radius: 0.75rem !important;
            font-weight: 700 !important;
        }

        main {
            overflow-y: auto;
            padding: 2rem;
        }

        @keyframes ho-up {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }

        .ho-anim > div,
        .ho-anim > div > div {
            animation: ho-up 0.55s cubic-bezier(0.22, 1, 0.36, 1) backwards;
        }

        .ho-anim > div {
            --ho-t: 0.05s;
            animation-delay: var(--ho-t);
        }

        .ho-anim > div:nth-of-type(2) {
            --ho-t: 0.14s;
        }

        .ho-anim > div:nth-of-type(3) {
            --ho-t: 0.23s;
        }

        .ho-anim > div:nth-of-type(4) {
            --ho-t: 0.32s;
        }

        .ho-anim > div:nth-of-type(5) {
            --ho-t: 0.41s;
        }

        .ho-anim > div:nth-of-type(6) {
            --ho-t: 0.50s;
        }

        .ho-anim > div:nth-of-type(n+7) {
            --ho-t: 0.55s;
        }

        .ho-anim > div > div {
            animation-delay: calc(var(--ho-t, 0s) + var(--ho-step, 0.05s));
        }

        .ho-anim > div > div:nth-of-type(2) {
            --ho-step: 0.12s;
        }

        .ho-anim > div > div:nth-of-type(3) {
            --ho-step: 0.19s;
        }

        .ho-anim > div > div:nth-of-type(4) {
            --ho-step: 0.26s;
        }

        .ho-anim > div > div:nth-of-type(5) {
            --ho-step: 0.33s;
        }

        .ho-anim > div > div:nth-of-type(6) {
            --ho-step: 0.40s;
        }

        .ho-anim > div > div:nth-of-type(n+7) {
            --ho-step: 0.45s;
        }

        @keyframes ho-fade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }

        .ho-fade {
            animation: ho-fade 1.2s cubic-bezier(0.22, 1, 0.36, 1) both;
            animation-delay: var(--ho-fade-delay, 0s);
        }

        @media (prefers-reduced-motion: reduce) {
            .ho-anim > div,
            .ho-anim > div > div,
            .ho-fade {
                animation: none;
            }
        }
    </style>
</head>

<body class="bg-light font-inter text-dark overflow-hidden">

    <div class="h-screen flex flex-col">

        @include('layouts.part.header-kasir')

        @include('layouts.part.nav-kasir')

        <main class="flex-1 min-h-0">
            <div class="ho-anim">
                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartCanvas = document.getElementById('chart');
        if (chartCanvas) {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const slideFromBottom = {
                type: 'number',
                easing: 'easeOutQuart',
                duration: 1400,
                delay: 350,
                from: (ctx) => ctx.chart.scales.y.bottom
            };

            const chartLabels = chartCanvas.dataset.labels
                ? JSON.parse(chartCanvas.dataset.labels)
                : ['20', '21', '22', '23', '24', '25', '26'];
            const chartValues = chartCanvas.dataset.values
                ? JSON.parse(chartCanvas.dataset.values)
                : [2, 4, 6, 3, 7, 5, 8];

            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: chartValues,
                        borderColor: '#764706',
                        backgroundColor: 'rgba(118,71,6,0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#764706',
                        pointBorderColor: '#FFF0DA',
                        pointBorderWidth: 2,
                        pointHoverBackgroundColor: '#5a3606'
                    }]
                },
                options: {
                    layout: {
                        padding: 10
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Pendapatan: Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    animation: reduceMotion ? false : {
                        duration: 1400,
                        easing: 'easeOutQuart',
                        delay: 350,
                        y: slideFromBottom
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>

    <script>
        function hoCountUp(target, options = {}) {
            const duration = options.duration || 1400;
            const delay = options.delay || 0;
            const decimals = options.decimals || 0;
            const prefix = options.prefix || '';
            const suffix = options.suffix || '';
            const multiplier = Math.pow(10, decimals);

            return {
                shown: prefix + '0' + suffix,
                target: target,
                init() {
                    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.shown = this.format(this.target);

                        return;
                    }

                    this.shown = this.format(0);

                    const begin = performance.now() + delay;

                    const tick = (now) => {
                        if (now < begin) {
                            requestAnimationFrame(tick);

                            return;
                        }

                        const progress = Math.min((now - begin) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);

                        this.shown = this.format(this.target * eased);

                        if (progress < 1) {
                            requestAnimationFrame(tick);
                        } else {
                            this.shown = this.format(this.target);
                        }
                    };

                    requestAnimationFrame(tick);
                },
                format(n) {
                    const value = Math.round(n * multiplier) / multiplier;

                    return prefix + value.toLocaleString('id-ID', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals,
                    }) + suffix;
                },
            };
        }
    </script>

</body>

</html>
