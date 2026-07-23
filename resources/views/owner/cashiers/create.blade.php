@extends('layouts.app')

@section('title', 'Tambah Kasir')

@section('content')
<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17] mt-1">Tambah Kasir Baru</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Isi data kasir baru untuk bergabung di Hideout Coffee.</p>
        </div>
        <a href="{{ route('owner.cashiers.index') }}"
            class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#8b684b] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
        <form action="{{ route('owner.cashiers.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                        placeholder="Masukkan username" autofocus>
                    <x-input-error :messages="$errors->get('username')" />
                </div>

                {{-- Name --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Depan</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Nama depan">
                        <x-input-error :messages="$errors->get('first_name')" />
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Belakang</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Nama belakang">
                        <x-input-error :messages="$errors->get('last_name')" />
                    </div>
                </div>

                {{-- Email & Phone --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Email</label>
                        <input type="text" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Masukkan email">
                        <x-input-error :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-bold text-[#2b1f17] mb-1.5">No. Telp</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Masukkan no. telp">
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Minimal 8 karakter">
                        <x-input-error :messages="$errors->get('password')" />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Ulangi password">
                        <x-input-error :messages="$errors->get('password_confirmation')" />
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-[#f0e8e0]">
                <a href="{{ route('owner.cashiers.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-[#8b684b] bg-[#f5f0eb] hover:bg-[#ebe3db] transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#764706] hover:bg-[#5a3606] transition shadow-md">
                    Simpan Kasir
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
