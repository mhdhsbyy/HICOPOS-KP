<x-guest-layout>
    <div class="w-full flex justify-center items-center">
        <div
            class="w-[1120px] h-[620px] bg-white rounded-[30px] overflow-hidden shadow-[0_25px_80px_rgba(0,0,0,0.35)] grid grid-cols-1 lg:grid-cols-[420px_1fr] origin-center scale-[0.78] xl:scale-[0.84]">

            {{-- LEFT PANEL --}}
            <div class="relative min-h-[620px] overflow-hidden bg-[#e6d7c7]">
                {{-- Background image full --}}
                <img src="{{ asset('images/bg-card.png') }}" alt="Hideout Coffee"
                    class="absolute inset-0 w-full h-full object-cover object-center">

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-[#e6d7c7]/45"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#eadccd]/55 via-[#e2d3c2]/25 to-[#d6c2ad]/10"></div>

                {{-- Content --}}
                <div class="relative z-10 h-full flex flex-col px-10 py-10">
                    {{-- Logo --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('images/logo-ho.png') }}" alt="Hideout Coffee"
                                class="w-20 h-20 object-contain">

                            <div class="leading-tight">
                                <h1 class="text-[2.5rem] font-black text-[#3a2b22] leading-none">
                                    Hideout
                                </h1>
                                <p class="text-[13px] tracking-[0.35em] font-bold text-[#5f4a3b] mt-1.5">
                                    COFFEE
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Text --}}
                    <div class="mt-16">
                        <h2 class="text-[58px] leading-[0.95] font-black text-[#3a2b22]">
                            Selamat<br>Datang!
                        </h2>

                        <p class="mt-6 text-[18px] leading-relaxed text-[#5d4a3c] font-medium">
                            Masuk untuk melanjutkan ke<br>
                            sistem POS Hideout Coffee
                        </p>

                        <div class="mt-8 flex items-center gap-3">
                            <span class="block w-14 h-[4px] rounded-full bg-[#3a2b22]"></span>
                            <span class="block w-2.5 h-2.5 rounded-full bg-[#3a2b22]"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="bg-white min-h-[620px] flex items-center justify-center px-10 lg:px-16 py-8">
                <div class="w-full max-w-[500px]">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    {{-- Heading --}}
                    <div class="mb-7">
                        <h2 class="text-[2rem] leading-tight font-black text-[#35271f]">
                            Masuk ke Akun Anda
                        </h2>
                        <p class="mt-2 text-[15px] text-[#8d8074] font-medium">
                            Silakan masukkan username dan password untuk melanjutkan
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Username --}}
                        <div>
                            <label for="username" class="block text-[14px] font-extrabold text-[#35271f] mb-2">
                                Username
                            </label>

                            <div
                                class="flex items-center gap-3 rounded-[16px] border border-[#ddd2c7] bg-white px-4 py-3 focus-within:border-[#6d4d36] focus-within:ring-4 focus-within:ring-[#6d4d36]/10 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4a372a] shrink-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0" />
                                </svg>

                                <input id="username" type="text" name="username" value="{{ old('username') }}"
                                    required autofocus autocomplete="username" placeholder="Masukkan username"
                                    class="w-full border-0 bg-transparent p-0 text-[#35271f] placeholder:text-[#a49689] focus:ring-0 text-[15px]">
                            </div>

                            <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-[14px] font-extrabold text-[#35271f] mb-2">
                                Password
                            </label>

                            <div
                                class="flex items-center gap-3 rounded-[16px] border border-[#ddd2c7] bg-white px-4 py-3 focus-within:border-[#6d4d36] focus-within:ring-4 focus-within:ring-[#6d4d36]/10 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4a372a] shrink-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V7.5a4.5 4.5 0 0 0-9 0v3m-.75 0h10.5A1.5 1.5 0 0 1 18.75 12v7.5A1.5 1.5 0 0 1 17.25 21H6.75a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                                </svg>

                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password" placeholder="Masukkan password"
                                    class="w-full border-0 bg-transparent p-0 text-[#35271f] placeholder:text-[#a49689] focus:ring-0 text-[15px]">
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        {{-- Remember / Forgot --}}
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="rounded-md border-[#d5c8bc] text-[#4c382a] shadow-sm focus:ring-[#7a5a3d]">
                                <span class="text-[14px] text-[#6f6258] font-medium">
                                    Ingat saya
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-[14px] font-semibold text-[#8b684b] hover:text-[#3a2b22] transition">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        {{-- Login --}}
                        <button type="submit"
                            class="mt-2 w-full rounded-[16px] bg-gradient-to-r from-[#3c2d23] to-[#5a4332] px-6 py-3.5 text-white font-extrabold text-base shadow-[0_10px_24px_rgba(60,45,35,0.22)] hover:scale-[1.01] active:scale-[0.99] transition">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
