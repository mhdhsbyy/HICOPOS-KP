<header class="shrink-0 h-24 bg-[#0d0a08] border-b border-[#1f1813] relative z-20">
    <div class="absolute inset-0 opacity-15 pointer-events-none overflow-hidden"
        style="background-image: url('{{ asset('images/bg3.png') }}'); background-size: cover; background-position: center;">
    </div>

    <div class="relative z-10 h-full px-8 flex items-center justify-between gap-6">

        {{-- Left: Brand --}}
        <div class="flex items-center gap-4 shrink-0">
            <div class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/logo-ho.png') }}" alt="Hideout Coffee" class="w-12 h-12 object-contain shrink-0 brightness-0 invert">
                <div class="leading-tight">
                    <h1 class="text-2xl font-black text-white leading-none tracking-tight">
                        Hideout
                    </h1>
                    <p class="text-[11px] font-bold text-[#c4a88a] uppercase tracking-[0.25em] mt-0.5">
                        Coffee
                    </p>
                </div>
            </div>
        </div>

        {{-- Right: Date/Time + Profile --}}
        <div class="flex items-center gap-4 shrink-0">
            <div class="hidden md:flex flex-col items-end gap-0.5 pr-4 border-r border-white/10">
                <span id="live-date" class="text-sm font-extrabold text-white"></span>
                <span id="live-time" class="text-xs font-bold text-[#c4a88a]"></span>

                <script>
                    function updateClock() {
                        const now = new Date();
                        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
                        document.getElementById('live-date').textContent = now.toLocaleDateString('id-ID', dateOptions);
                        document.getElementById('live-time').textContent = now.toLocaleTimeString('id-ID', timeOptions);
                    }
                    updateClock();
                    setInterval(updateClock, 1000);
                </script>
            </div>
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" @click="open = !open"
                    class="flex items-center gap-2 rounded-full bg-white px-1 pr-[15px] pl-[5px] py-1 border border-white/20 hover:bg-[#F5EDE0] transition">

                    <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center overflow-hidden">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="w-full h-full object-cover">
                    </div>

                    <div class="flex flex-col items-start leading-tight">
                        <span class="text-sm font-black text-[#2b1f17]">
                            {{ Auth::user()->username }}
                        </span>
                        <span class="text-[8px] font-semibold text-[#8b684b] uppercase">
                            {{ Auth::user()->getRoleNames()->first() }}
                        </span>
                    </div>
                </button>

                <div x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 z-50 mt-2 w-44 rounded-2xl bg-white shadow-xl border border-[#f0e8e0] overflow-hidden">

                    @if (Auth::user()->hasRole('owner'))
                        <a href="{{ route('owner.dashboard') }}"
                            class="flex items-center gap-2 px-4 py-3 text-sm font-bold text-[#2b1f17] hover:bg-[#f5f0eb] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>
                            Halaman Owner
                        </a>

                        <div class="border-t border-[#f0e8e0]"></div>
                    @endif

                    <a href="{{ route('profile.edit', ['from' => 'kasir']) }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-bold text-[#2b1f17] hover:bg-[#f5f0eb] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" onsubmit="event.preventDefault(); confirmLogout(this, 'Yakin ingin keluar dari aplikasi?')">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Toggle menu handle --}}
    <button id="nav-kasir-toggle"
        class="absolute left-1/2 -translate-x-1/2 bottom-1.5 z-30 w-10 h-7 rounded-t-lg bg-[#0d0a08] border-x border-t border-[#1f1813] flex items-center justify-center hover:bg-[#1f1813] transition-all duration-200 cursor-pointer group"
        title="Buka / Tutup menu">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
            stroke="currentColor" class="w-4 h-4 text-[#c4a88a] group-hover:text-white transition-transform duration-300"
            id="nav-kasir-toggle-icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
        </svg>
    </button>
</header>