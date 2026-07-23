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
                    <div x-show="scope === 'item'" x-data="menuSearch()" x-init="init()">
                        <label class="block text-sm font-bold text-[#2b1f17] mb-1.5">Pilih Menu</label>
                        <input type="hidden" name="target_id" :value="selectedId">
                        <div class="relative">
                            <div class="flex items-center w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] focus-within:ring-2 focus-within:ring-[#764706] focus-within:border-transparent transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#b8a898] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                                <input type="text" x-model="query" @input.debounce.200ms="filter()" @focus="open = true" @keydown.escape="open = false"
                                    @keydown.arrow-down.prevent="$refs.list?.querySelector('[data-option]')?.focus()"
                                    class="w-full border-0 bg-transparent p-0 ml-2 text-sm text-[#2b1f17] placeholder-[#b8a898] focus:ring-0"
                                    :placeholder="selectedName || 'Ketik nama menu...'" autocomplete="off">
                                <button x-show="selectedId" type="button" @click="clear()" class="ml-1 text-[#b8a898] hover:text-[#764706] transition shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div x-show="open && filtered.length > 0" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                                @click.away="open = false" class="absolute z-50 mt-1 w-full bg-white border border-[#e2d3c2] rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="(menu, i) in filtered" :key="menu.id">
                                    <div data-option tabindex="0"
                                        @click="select(menu)" @keydown.enter="select(menu)"
                                        class="px-4 py-2.5 cursor-pointer hover:bg-[#f5f0eb] transition flex items-center justify-between"
                                        :class="selectedId === menu.id ? 'bg-[#f5f0eb]' : ''">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-[#2b1f17] truncate" x-text="menu.name"></p>
                                            <p class="text-xs text-[#8b684b]" x-text="menu.category"></p>
                                        </div>
                                        <span class="text-xs font-bold text-[#764706] shrink-0 ml-3" x-text="'Rp ' + formatRupiah(menu.price)"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('target_id')" />
                    </div>
                    <div x-show="scope === 'category'">
                        <label for="target_id_category" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Pilih Kategori</label>
                        <select name="target_id" id="target_id_category"
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
                    <div>
                        <label for="start_date" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Tanggal Mulai <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                        <x-input-error :messages="$errors->get('start_date')" />
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Tanggal Berakhir <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                        <x-input-error :messages="$errors->get('end_date')" />
                    </div>
                </div>

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

function menuSearch() {
    let allMenus = {!! json_encode($menus->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'category' => $m->category->name ?? '', 'price' => $m->price])) !!};
    return {
        open: false,
        query: '',
        selectedId: '{{ old("target_id") }}',
        selectedName: '',
        filtered: allMenus,

        init() {
            if (this.selectedId) {
                let m = allMenus.find(x => x.id == this.selectedId);
                if (m) this.selectedName = m.name + ' (' + m.category + ')';
            }
            this.filter();
        },

        filter() {
            let q = this.query.toLowerCase();
            this.filtered = q ? allMenus.filter(m => m.name.toLowerCase().includes(q) || m.category.toLowerCase().includes(q)) : allMenus;
        },

        select(menu) {
            this.selectedId = menu.id;
            this.selectedName = menu.name + ' (' + menu.category + ')';
            this.query = '';
            this.open = false;
        },

        clear() {
            this.selectedId = '';
            this.selectedName = '';
            this.query = '';
            this.filtered = allMenus;
        },

        formatRupiah(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        },
    }
}
</script>
@endsection
