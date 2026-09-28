<aside id="sidebar"
    class="fixed left-0 top-0 z-40 h-screen w-72 bg-[#0d0a08] border-r border-[#1f1813] shadow-lg transition-all duration-300">

    {{-- Background --}}
    <div id="sidebar-bg" class="absolute inset-0 opacity-15 pointer-events-none"
        style="background-image: url('{{ asset('images/bg3.png') }}'); background-size: cover; background-position: center;">
    </div>

    <div class="relative z-10 h-full flex flex-col px-4 py-6">

        {{-- Logo --}}
        <div id="sidebar-logo" class="flex items-center gap-3 px-2 mb-8 shrink-0">
            <img src="{{ asset('images/logo-ho.png') }}" alt="Hideout Coffee" class="w-14 h-14 object-contain shrink-0 brightness-0 invert">

            <div id="sidebar-logo-text" class="leading-tight">
                <h1 class="text-3xl font-black text-white leading-none tracking-tight">
                    Hideout
                </h1>
                <p class="text-[12px] font-bold text-[#c4a88a] uppercase tracking-[0.25em] mt-0.5">
                    Coffee
                </p>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 flex flex-col justify-between">

            <div class="space-y-3 sidebar-menu-group">

                {{-- UTAMA --}}
                <div>
                    <p class="sidebar-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-[#8a7a6a]">
                        Utama
                    </p>

                    <div class="space-y-0.5">
                        <a href="{{ route('kasir.dashboard') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('kasir.dashboard')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75a2.25 2.25 0 0 1 2.25-2.25h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>

                            <span class="sidebar-text text-sm font-bold">Dashboard</span>
                        </a>
                    </div>
                </div>

                {{-- TRANSAKSI --}}
                <div>
                    <p class="sidebar-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-[#8a7a6a]">
                        Transaksi
                    </p>

                    <div class="space-y-0.5">

                        <a href="{{ route('kasir.transactions.create') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('kasir.transactions.create')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                            <span class="sidebar-text text-sm font-bold">Transaksi</span>
                        </a>

                        <a href="{{ route('kasir.transactions.history') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('kasir.transactions.history')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m16.5 0V6.75A2.25 2.25 0 0 0 18 4.5H6A2.25 2.25 0 0 0 3.75 6.75v.75m16.5 0-1.5-6H5.25l-1.5 6m16.5 0h-16.5" />
                            </svg>
                            <span class="sidebar-text text-sm font-bold">Riwayat Transaksi</span>
                        </a>

                    </div>
                </div>
            {{-- AKUN --}}
            <div>
                <p class="sidebar-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-[#8a7a6a]">
                    Akun
                </p>

                <div class="space-y-0.5">
                    <a href="{{ route('profile.edit') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                        {{ request()->routeIs('profile.*')
                            ? 'bg-white/10 text-white'
                            : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>

                        <span class="sidebar-text text-sm font-bold">Profil</span>
                    </a>
                </div>
            </div>
            </div>

            {{-- Toggle --}}
            <div class="pt-4 border-t border-white/10 sidebar-bottom">
                <div class="flex justify-end sidebar-toggle-wrap">
                    <button id="sidebar-toggle"
                        class="sidebar-toggle-btn w-8 h-8 rounded-lg bg-white/10 text-[#c4a88a] flex items-center justify-center hover:bg-white/20 hover:text-white transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-4 h-4 transition-transform duration-200 sidebar-icon"
                            id="toggle-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                </div>
            </div>

        </nav>
    </div>

</aside>

<style>
    .sidebar-collapsed {
        width: 5rem !important;
    }

    #sidebar.no-transition {
        transition: none !important;
    }

    .sidebar-text.ho-text-replay,
    .sidebar-label.ho-text-replay,
    #sidebar-logo-text.ho-text-replay {
        animation: sidebar-text-in 0.3s cubic-bezier(0.22, 1, 0.36, 1) backwards;
    }

    @keyframes sidebar-text-in {
        from {
            opacity: 0;
            transform: translateX(10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .sidebar-collapsed .sidebar-text,
    .sidebar-collapsed .sidebar-label,
    .sidebar-collapsed #sidebar-logo-text {
        display: none;
        animation: none !important;
    }

    .sidebar-collapsed .sidebar-menu-group>div+div {
        padding-top: 1rem;
    }

    .sidebar-collapsed #sidebar-logo {
        justify-content: center;
        padding: 0;
        gap: 0 !important;
    }

    .sidebar-collapsed #sidebar-logo img {
        width: 2.5rem;
        height: 2.5rem;
    }

    .sidebar-collapsed .sidebar-link,
    .sidebar-collapsed .sidebar-menu-group a {
        justify-content: center;
        padding: 0;
        width: 2.75rem;
        height: 2.75rem;
        margin: 0 auto;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
    }

    .sidebar-collapsed .sidebar-link .sidebar-icon,
    .sidebar-collapsed .sidebar-menu-group a .sidebar-icon {
        width: 1.5rem;
        height: 1.5rem;
    }

    .sidebar-collapsed .sidebar-menu-group {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .sidebar-collapsed .sidebar-menu-group>div {
        width: 100%;
    }

    .sidebar-collapsed .sidebar-menu-group .space-y-0\.5 {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .sidebar-collapsed .px-4 {
        padding-left: 0.375rem;
        padding-right: 0.375rem;
    }

    .sidebar-collapsed .sidebar-bottom {
        display: flex;
        justify-content: center;
        border: none !important;
        padding-top: 0.5rem !important;
    }

    .sidebar-collapsed .sidebar-bottom .sidebar-toggle-btn {
        width: 2.75rem;
        height: 2.75rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
    }

    .sidebar-collapsed .sidebar-bottom .sidebar-toggle-btn svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    @media (prefers-reduced-motion: reduce) {
        .sidebar-text.ho-text-replay,
        .sidebar-label.ho-text-replay,
        #sidebar-logo-text.ho-text-replay {
            animation: none !important;
        }
    }
</style>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const toggleIcon = document.getElementById('toggle-icon');

        function applyState(collapsed) {
            if (collapsed) {
                sidebar.classList.add('sidebar-collapsed');
                if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
            }
            const mainContent = document.getElementById('main-content');
            if (mainContent) {
                mainContent.classList.toggle('sidebar-content-collapsed', collapsed);
            }
        }

        function animateSidebarText() {
            const els = sidebar.querySelectorAll('.sidebar-text, .sidebar-label, #sidebar-logo-text');
            els.forEach(function (el) {
                el.classList.remove('ho-text-replay');
                void el.offsetWidth;
                el.classList.add('ho-text-replay');
            });
        }

        const isCollapsedStored = localStorage.getItem('sidebarCollapsed') === 'true';

        sidebar.classList.add('no-transition');
        applyState(isCollapsedStored);
        sidebar.offsetHeight;
        sidebar.classList.remove('no-transition');

        document.addEventListener('DOMContentLoaded', function () {
            const mc = document.getElementById('main-content');
            if (mc) {
                mc.classList.add('no-transition');
                mc.classList.toggle('sidebar-content-collapsed', isCollapsedStored);
                mc.offsetHeight;
                mc.classList.remove('no-transition');
            }

            const toggleBtn = document.getElementById('sidebar-toggle');
            if (!toggleBtn) return;
            toggleBtn.addEventListener('click', function () {
                const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                applyState(!isCollapsed);
                if (isCollapsed) {
                    animateSidebarText();
                }
                localStorage.setItem('sidebarCollapsed', !isCollapsed);
            });
        });
    })();
</script>
