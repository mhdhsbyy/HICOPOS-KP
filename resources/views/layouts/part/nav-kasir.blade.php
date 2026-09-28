@php
    $navIsOwner = Auth::user()->hasRole('owner');
    $navDashRoute = $navIsOwner ? 'owner.kasir.dashboard' : 'kasir.dashboard';
    $navPosRoute = $navIsOwner ? 'owner.transactions.create' : 'kasir.transactions.create';
    $navHistRoute = $navIsOwner ? 'owner.transactions.history' : 'kasir.transactions.history';
    $navIsDash = request()->routeIs($navDashRoute);
    $navIsPos = request()->routeIs($navPosRoute);
    $navIsHist = request()->routeIs($navHistRoute);
@endphp

<nav id="nav-kasir" class="shrink-0 bg-[#F5EDE0] border-b border-[#e2d3c2] overflow-hidden">
    <div class="flex items-center gap-1 px-8">

        {{-- Menu --}}
        <div id="nav-kasir-menu" class="flex items-center gap-1">
            <a href="{{ route($navDashRoute) }}"
                class="inline-flex items-center gap-2 px-5 py-3 text-sm font-bold transition-all duration-200 relative
                {{ $navIsDash
                    ? 'text-[#764706]'
                    : 'text-[#2b1f17] hover:text-[#764706]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75a2.25 2.25 0 0 1 2.25-2.25h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
                @if ($navIsDash)
                    <span class="absolute -bottom-px left-0 right-0 h-0.5 rounded-full bg-[#764706]"></span>
                @endif
            </a>
            <a href="{{ route($navPosRoute) }}"
                class="inline-flex items-center gap-2 px-5 py-3 text-sm font-bold transition-all duration-200 relative
                {{ $navIsPos
                    ? 'text-[#764706]'
                    : 'text-[#2b1f17] hover:text-[#764706]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
                Transaksi
                @if ($navIsPos)
                    <span class="absolute -bottom-px left-0 right-0 h-0.5 rounded-full bg-[#764706]"></span>
                @endif
            </a>
            <a href="{{ route($navHistRoute) }}"
                class="inline-flex items-center gap-2 px-5 py-3 text-sm font-bold transition-all duration-200 relative
                {{ $navIsHist
                    ? 'text-[#764706]'
                    : 'text-[#2b1f17] hover:text-[#764706]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m16.5 0V6.75A2.25 2.25 0 0 0 18 4.5H6A2.25 2.25 0 0 0 3.75 6.75v.75m16.5 0-1.5-6H5.25l-1.5 6m16.5 0h-16.5" />
                </svg>
                Riwayat Transaksi
                @if ($navIsHist)
                    <span class="absolute -bottom-px left-0 right-0 h-0.5 rounded-full bg-[#764706]"></span>
                @endif
            </a>
        </div>
    </div>
</nav>

<script>
    (function () {
        const root = document.documentElement;
        const toggleBtn = document.getElementById('nav-kasir-toggle');
        const toggleIcon = document.getElementById('nav-kasir-toggle-icon');
        if (!toggleBtn) return;

        function applyState(collapsed) {
            root.dataset.navKasir = collapsed ? 'collapsed' : 'open';
            const deg = collapsed ? 180 : 0;
            if (toggleIcon) toggleIcon.style.transform = 'rotate(' + deg + 'deg)';
        }

        applyState(root.dataset.navKasir === 'collapsed');

        toggleBtn.addEventListener('click', function () {
            const collapsed = root.dataset.navKasir === 'collapsed';
            applyState(!collapsed);
            localStorage.setItem('navKasirCollapsed', (!collapsed).toString());
        });
    })();
</script>