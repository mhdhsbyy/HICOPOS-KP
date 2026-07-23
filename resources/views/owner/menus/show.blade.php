@extends('layouts.app')

@section('title', $menu->name)

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17]">{{ $menu->name }}</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Detail menu {{ $menu->name }}.</p>
        </div>
        <a href="{{ route('owner.menus.index') }}"
            class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#8b684b] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Foto & Info --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Foto --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
                <div class="w-full h-64 bg-[#FFF0DA] flex items-center justify-center overflow-hidden">
                    @if ($menu->photo)
                        <img src="{{ Storage::url($menu->photo) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                        </svg>
                    @endif
                </div>
            </div>

            {{-- Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6 space-y-4">
                <h3 class="text-base font-black text-[#2b1f17]">Informasi Menu</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kategori</span>
                        <span class="text-sm font-bold text-[#2b1f17]">{{ $menu->category->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Harga</span>
                        <span class="text-sm font-black text-[#764706]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Status</span>
                        @if ($menu->status === 'active')
                            <span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="text-xs font-bold text-red-700 bg-red-100 px-3 py-1 rounded-full">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-[#f0e8e0]">
                    <a href="{{ route('owner.menus.edit', $menu) }}"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit Menu
                    </a>
                </div>
            </div>
        </div>

        {{-- Deskripsi & Resep --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Deskripsi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
                <h3 class="text-base font-black text-[#2b1f17] mb-3">Deskripsi</h3>
                @if ($menu->description)
                    <p class="text-sm text-[#5f4a3b] leading-relaxed">{{ $menu->description }}</p>
                @else
                    <p class="text-sm text-[#b8a898] italic">Tidak ada deskripsi.</p>
                @endif
            </div>

            {{-- Resep --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
                <h3 class="text-base font-black text-[#2b1f17] mb-3">Resep Pembuatan</h3>
                @if ($menu->recipe)
                    <div class="text-sm text-[#5f4a3b] leading-relaxed whitespace-pre-line">{{ $menu->recipe }}</div>
                @else
                    <p class="text-sm text-[#b8a898] italic">Belum ada resep.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
