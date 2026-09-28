<header class="shrink-0 h-24 bg-[#F5EDE0] border-b border-[#e2d3c2] relative">
    <div class="h-full px-8 flex items-center justify-between">

        {{-- Title --}}
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#2b1f17] shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25A1.5 1.5 0 0 1 3.75 18.75v-12A1.5 1.5 0 0 1 5.25 5.25Z" />
                </svg>
                <div class="flex flex-col items-start gap-0.5 ml-2.5">
                    <span id="live-date" class="text-sm font-extrabold text-[#2b1f17]"></span>
                    <span id="live-time" class="text-xs font-bold text-[#8b684b]"></span>
                </div>

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
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-4">

            {{-- User Dropdown --}}
            {{-- User Profile --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" @click="open = !open"
                    class="flex items-center gap-2 rounded-full bg-white px-1 pr-[15px] pl-[5px] py-1 shadow-md border border-[#eadccd] hover:shadow-lg transition">

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
                    class="absolute right-0 z-50 mt-2 w-44 rounded-2xl bg-white shadow-xl border border-[#eadccd] overflow-hidden">

                    <a href="{{ route('owner.kasir.dashboard') }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-bold text-[#2b1f17] hover:bg-[#f4e7d7] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" /></svg>
                        Halaman Kasir
                    </a>

                    <div class="border-t border-[#f0e8e0]"></div>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-bold text-[#2b1f17] hover:bg-[#f4e7d7] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" onsubmit="event.preventDefault(); confirmLogout(this, 'Yakin ingin keluar dari aplikasi?')">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-2 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
