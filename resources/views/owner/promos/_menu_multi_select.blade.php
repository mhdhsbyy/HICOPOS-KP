@php
    $selectedIds = array_map('strval', $selectedIds ?? []);
@endphp

<div x-show="scope === 'item'" x-data="menuMulti()" x-init="init()" @click.away="open = false">
    <label class="block text-sm font-bold text-[#2b1f17] mb-1.5">Pilih Menu <span class="text-[#b8a898] font-normal">(bisa pilih lebih dari satu)</span></label>
    <div class="relative">
        <div class="flex items-center w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] focus-within:ring-2 focus-within:ring-[#764706] focus-within:border-transparent transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#b8a898] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" x-model="query" @input.debounce.200ms="filter()" @focus="open = true" @keydown.escape="open = false"
                class="w-full border-0 bg-transparent p-0 ml-2 text-sm text-[#2b1f17] placeholder-[#b8a898] focus:ring-0"
                placeholder="Ketik nama menu untuk filter..." autocomplete="off">
            <button x-show="query" type="button" @click="clear()" class="ml-1 text-[#b8a898] hover:text-[#764706] transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div x-show="open && filtered.length > 0" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
            class="absolute z-50 mt-1 w-full bg-white border border-[#e2d3c2] rounded-xl shadow-lg max-h-60 overflow-y-auto">
            <template x-for="(menu, i) in filtered" :key="menu.id">
                <label class="px-4 py-2.5 cursor-pointer hover:bg-[#f5f0eb] transition flex items-center gap-3"
                    :class="isSelected(menu) ? 'bg-[#f5f0eb]' : ''">
                    <input type="checkbox" name="menu_ids[]" :value="String(menu.id)" x-model="selectedIds"
                        :disabled="scope !== 'item'"
                        class="w-4 h-4 rounded border-[#e2d3c2] text-[#764706] focus:ring-[#764706] shrink-0">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-[#2b1f17] truncate" x-text="menu.name"></p>
                        <p class="text-xs text-[#8b684b]" x-text="menu.category"></p>
                    </div>
                    <span class="text-xs font-bold text-[#764706] shrink-0" x-text="'Rp ' + formatRupiah(menu.price)"></span>
                </label>
            </template>
            <p x-show="filtered.length === 0" class="px-4 py-3 text-sm text-[#b8a898] text-center">Menu tidak ditemukan.</p>
        </div>
    </div>

    <div x-show="selectedIds.length > 0" class="flex items-center flex-wrap gap-1.5 mt-2">
        <template x-for="menu in selectedMenus" :key="menu.id">
            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#764706] bg-[#FFF0DA] px-2 py-0.5 rounded-full">
                <span x-text="menu.name"></span>
                <button type="button" @click="toggle(menu)" class="hover:text-[#5a3606]" title="Hapus pilihan">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </span>
        </template>
        <span class="text-[10px] font-bold text-[#8b684b]" x-text="selectedIds.length + ' menu terpilih'"></span>
    </div>

    <x-input-error :messages="$errors->get('menu_ids')" />
</div>

<script>
function menuMulti() {
    let allMenus = {!! json_encode($menus->map(fn ($m) => ['id' => $m->id, 'name' => $m->name, 'category' => $m->category->name ?? '', 'price' => $m->price])->values(), JSON_HEX_TAG) !!};
    return {
        open: false,
        query: '',
        selectedIds: {!! json_encode($selectedIds, JSON_HEX_TAG) !!},
        filtered: allMenus,

        init() {
            this.filter();
        },

        filter() {
            let q = this.query.toLowerCase();
            this.filtered = q ? allMenus.filter(m => m.name.toLowerCase().includes(q) || m.category.toLowerCase().includes(q)) : allMenus;
        },

        isSelected(menu) {
            return this.selectedIds.some(id => id == menu.id);
        },

        toggle(menu) {
            if (this.isSelected(menu)) {
                this.selectedIds = this.selectedIds.filter(id => id != menu.id);
            } else {
                this.selectedIds.push(String(menu.id));
            }
        },

        get selectedMenus() {
            return allMenus.filter(m => this.isSelected(m));
        },

        clear() {
            this.query = '';
            this.filtered = allMenus;
        },

        formatRupiah(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        },
    }
}
</script>
