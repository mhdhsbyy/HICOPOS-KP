<x-guest-layout>
    <div
        class="w-[560px] bg-white rounded-[30px] overflow-hidden shadow-[0_25px_80px_rgba(0,0,0,0.35)] py-10 px-10 lg:px-14">

        {{-- Logo + Branding --}}
        <div class="flex items-center gap-4 mb-9">
            <img src="{{ asset('images/logo-ho.png') }}" alt="Hideout Coffee"
                class="w-16 h-16 object-contain">
            <div class="leading-tight">
                <h1 class="text-[1.9rem] font-black text-[#35271f] leading-none">Hideout</h1>
                <p class="text-[11px] tracking-[0.35em] font-bold text-[#8d8074] mt-1">COFFEE</p>
            </div>
        </div>

        {{-- Heading --}}
        <div class="mb-7">
            <h2 class="text-[1.7rem] leading-tight font-black text-[#35271f]">
                Lupa Password?
            </h2>
            <p class="mt-2 text-[15px] text-[#8d8074] font-medium">
                Silakan masukkan alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password.
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-[14px] font-extrabold text-[#35271f] mb-2">
                    Alamat Email
                </label>

                <div
                    class="flex items-center gap-3 rounded-[16px] border border-[#ddd2c7] bg-white px-4 py-3 focus-within:border-[#6d4d36] focus-within:ring-4 focus-within:ring-[#6d4d36]/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4a372a] shrink-0"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>

                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        required autofocus autocomplete="username" placeholder="Masukkan alamat email"
                        class="w-full border-0 bg-transparent p-0 text-[#35271f] placeholder:text-[#a49689] focus:ring-0 text-[15px]">
                </div>

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="mt-2 w-full rounded-[16px] bg-gradient-to-r from-[#3c2d23] to-[#5a4332] px-6 py-3.5 text-white font-extrabold text-base shadow-[0_10px_24px_rgba(60,45,35,0.22)] hover:scale-[1.01] active:scale-[0.99] transition">
                Kirim Link Reset
            </button>
        </form>

        {{-- Back to login --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-[14px] font-semibold text-[#8b684b] hover:text-[#3a2b22] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke halaman login
            </a>
        </div>
    </div>
</x-guest-layout>