@extends('layouts.app')

@section('title', 'Tambah Promo')

@section('content')
<div class="space-y-8" x-data="promoForm()">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17] mt-1">Tambah Promo Baru</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Buat promo diskon baru untuk transaksi.</p>
        </div>
        <a href="{{ route('owner.promos.index') }}"
            class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#8b684b] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
        <form action="{{ route('owner.promos.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Promo</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                        placeholder="Contoh: Diskon Latte 10%" autofocus>
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Deskripsi <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                        placeholder="Deskripsi singkat promo">
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                {{-- Tipe & Nilai --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#2b1f17] mb-1.5">Tipe Diskon</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="percentage" x-model="type"
                                    {{ old('type', 'percentage') === 'percentage' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#764706] focus:ring-[#764706] border-[#e2d3c2]">
                                <span class="text-sm font-bold text-[#2b1f17]">Persentase (%)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="flat" x-model="type"
                                    {{ old('type') === 'flat' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#764706] focus:ring-[#764706] border-[#e2d3c2]">
                                <span class="text-sm font-bold text-[#2b1f17]">Flat (Rp)</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('type')" />
                    </div>
                    <div>
                        <label for="value" class="block text-sm font-bold text-[#2b1f17] mb-1.5" x-text="type === 'percentage' ? 'Persentase (%)' : 'Nominal (Rp)'"></label>
                        <input type="hidden" name="value" id="value_raw" value="{{ old('value') }}">
                        <input type="text" id="value_display" @input="formatPrice($event, 'value_raw')"
                            value="{{ old('value') ? number_format(old('value'), 0, ',', '.') : '' }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            :placeholder="type === 'percentage' ? 'Contoh: 10' : 'Contoh: 50.000'" inputmode="numeric">
                        <x-input-error :messages="$errors->get('value')" />
                    </div>
                </div>

                {{-- Target --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#2b1f17] mb-1.5">Target Promo</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="scope" value="item" x-model="scope"
                                    {{ old('scope', 'item') === 'item' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#764706] focus:ring-[#764706] border-[#e2d3c2]">
                                <span class="text-sm font-bold text-[#2b1f17]">Item</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="scope" value="category" x-model="scope"
                                    {{ old('scope') === 'category' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#764706] focus:ring-[#764706] border-[#e2d3c2]">
                                <span class="text-sm font-bold text-[#2b1f17]">Kategori</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="scope" value="transaction" x-model="scope"
                                    {{ old('scope') === 'transaction' ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#764706] focus:ring-[#764706] border-[#e2d3c2]">
                                <span class="text-sm font-bold text-[#2b1f17]">Transaksi</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('scope')" />
                    </div>
                    <div x-show="scope === 'item'">
                        @include('owner.promos._menu_multi_select', ['menus' => $menus, 'selectedIds' => old('menu_ids', [])])
                    </div>
                    <div x-show="scope === 'category'">
                        <label for="target_id_category" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Pilih Kategori</label>
                        <select name="target_id" id="target_id_category"
                            :disabled="scope !== 'category'"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                            <option value="">Pilih kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('target_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('target_id')" />
                    </div>
                </div>

                {{-- Syarat Minimum --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="min_quantity" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Min. Jumlah Item <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                        <input type="number" name="min_quantity" id="min_quantity" value="{{ old('min_quantity') }}" min="1"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Contoh: 2">
                        <x-input-error :messages="$errors->get('min_quantity')" />
                    </div>
                    <div>
                        <label for="min_amount" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Min. Total Belanja <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                        <input type="hidden" name="min_amount" id="min_amount_raw" value="{{ old('min_amount') }}">
                        <input type="text" id="min_amount_display" @input="formatPrice($event, 'min_amount_raw')"
                            value="{{ old('min_amount') ? number_format(old('min_amount'), 0, ',', '.') : '' }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Contoh: 50.000" inputmode="numeric">
                        <x-input-error :messages="$errors->get('min_amount')" />
                    </div>
                </div>

                {{-- Periode --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @include('owner.promos._datetime_field', ['label' => 'Tanggal Mulai', 'name' => 'start_date', 'id' => 'start_date', 'value' => old('start_date'), 'errorKey' => 'start_date'])

                    @include('owner.promos._datetime_field', ['label' => 'Tanggal Berakhir', 'name' => 'end_date', 'id' => 'end_date', 'value' => old('end_date'), 'errorKey' => 'end_date'])
                </div>
                <p class="mt-2 text-xs font-medium text-[#8b684b]">Kosongkan kedua tanggal di atas jika promo ingin selalu aktif.</p>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-[#f0e8e0]">
                <a href="{{ route('owner.promos.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-[#8b684b] bg-[#f5f0eb] hover:bg-[#ebe3db] transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#764706] hover:bg-[#5a3606] transition shadow-md">
                    Simpan Promo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function promoForm() {
    return {
        type: '{{ old("type", "percentage") }}',
        scope: '{{ old("scope", "item") }}',
        formatPrice(e, hiddenId) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            document.getElementById(hiddenId).value = raw;
            e.target.value = raw ? Number(raw).toLocaleString('id-ID') : '';
        },
    }
}
</script>
@endsection
