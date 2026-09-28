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
                        <a href="{{ route('owner.dashboard') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('owner.dashboard')
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

                {{-- MANAGEMENT --}}
                <div>
                    <p class="sidebar-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-[#8a7a6a]">
                        Management
                    </p>

                    <div class="space-y-0.5">

                        <a href="{{ route('owner.cashiers.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('owner.cashiers.*')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <span class="sidebar-text text-sm font-bold">Kelola Kasir</span>
                        </a>

                        <a href="{{ route('owner.categories.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('owner.categories.*')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            </svg>
                            <span class="sidebar-text text-sm font-bold">Kategori Menu</span>
                        </a>

                        <a href="{{ route('owner.menus.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('owner.menus.*')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>

                            <span class="sidebar-text text-sm font-bold">Menu Produk</span>
                        </a>

                        <a href="{{ route('owner.promos.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('owner.promos.*')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                            <span class="sidebar-text text-sm font-bold">Kelola Promo</span>
                        </a>

                    </div>
                </div>

                {{-- TRANSAKSI --}}
                <div>
                    <p class="sidebar-label px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-[#8a7a6a]">
                        Transaksi
                    </p>

                    <div class="space-y-0.5">

                        <a href="{{ route('owner.transactions.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                            {{ request()->routeIs('owner.transactions.index')
                                ? 'bg-white/10 text-white'
                                : 'text-[#c4a88a] hover:bg-white/5 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="w-5 h-5 shrink-0 sidebar-icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span class="sidebar-text text-sm font-bold">Data Transaksi</span>
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
