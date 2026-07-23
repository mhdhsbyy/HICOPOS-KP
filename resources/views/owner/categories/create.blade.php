@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17] mt-1">Tambah Kategori Baru</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Isi nama kategori baru untuk menu Hideout Coffee.</p>
        </div>
        <a href="{{ route('owner.categories.index') }}"
            class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#8b684b] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
        <form action="{{ route('owner.categories.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                        placeholder="Masukkan nama kategori" autofocus>
                    <x-input-error :messages="$errors->get('name')" />
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-[#f0e8e0]">
                <a href="{{ route('owner.categories.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-[#8b684b] bg-[#f5f0eb] hover:bg-[#ebe3db] transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#764706] hover:bg-[#5a3606] transition shadow-md">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
