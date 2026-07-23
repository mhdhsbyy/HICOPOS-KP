@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17]">Tambah Menu Baru</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Isi data menu baru untuk Hideout Coffee.</p>
        </div>
        <a href="{{ route('owner.menus.index') }}"
            class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#8b684b] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
        <form action="{{ route('owner.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-5">

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Menu</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                        placeholder="Masukkan nama menu" autofocus>
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                {{-- Category & Price --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Kategori</label>
                        <select name="category_id" id="category_id"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" />
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Harga (Rp)</label>
                        <input type="hidden" name="price" id="price_raw" value="{{ old('price') }}">
                        <input type="text" id="price_display" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                            oninput="let raw = this.value.replace(/\./g,'').replace(/[^0-9]/g,''); document.getElementById('price_raw').value = raw; this.value = raw ? Number(raw).toLocaleString('id-ID') : '';"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Masukkan harga" inputmode="numeric">
                        <x-input-error :messages="$errors->get('price')" />
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Deskripsi <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition resize-none"
                        placeholder="Deskripsi singkat menu">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                {{-- Recipe --}}
                <div>
                    <label for="recipe" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Resep Pembuatan <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                    <textarea name="recipe" id="recipe" rows="6"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition resize-none"
                        placeholder="Tulis resep pembuatan menu ini...">{{ old('recipe') }}</textarea>
                    <x-input-error :messages="$errors->get('recipe')" />
                </div>

                {{-- Photo --}}
                <div>
                    <label class="block text-sm font-bold text-[#2b1f17] mb-1.5">Foto <span class="text-[#b8a898] font-normal">(opsional, max 2MB)</span></label>
                    <div class="flex items-center gap-4">
                        <div id="photo-preview" class="w-20 h-20 rounded-xl bg-[#f5f0eb] flex items-center justify-center overflow-hidden shrink-0 border border-[#e2d3c2]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*"
                            class="text-sm text-[#5f4a3b] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-[#FFF0DA] file:text-[#764706] hover:file:bg-[#f0e0c0] file:cursor-pointer file:transition">
                    </div>
                    <x-input-error :messages="$errors->get('photo')" />
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-[#f0e8e0]">
                <a href="{{ route('owner.menus.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-[#8b684b] bg-[#f5f0eb] hover:bg-[#ebe3db] transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#764706] hover:bg-[#5a3606] transition shadow-md">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('photo-preview').innerHTML =
                    '<img src="' + ev.target.result + '" class="w-full h-full object-cover">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
