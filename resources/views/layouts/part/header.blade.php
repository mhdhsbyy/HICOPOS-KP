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

                    <div class="w-9 h-9 rounded-full bg-black flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logo-ho.png') }}" alt="Profile" class="w-7 h-7 object-contain">
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

                <div x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 z-50 mt-2 w-44 rounded-2xl bg-white shadow-xl border border-[#eadccd] overflow-hidden">

                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-3 text-sm font-bold text-[#2b1f17] hover:bg-[#f4e7d7] transition">
                        Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
