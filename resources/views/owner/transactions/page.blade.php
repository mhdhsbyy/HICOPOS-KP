@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    @keyframes ho-pos-in {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: none; }
    }

    .ho-menu-card {
        animation: ho-pos-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) backwards;
        animation-delay: var(--md, 0s);
    }

    @media (prefers-reduced-motion: reduce) {
        .ho-menu-card {
            animation: none;
        }
    }
</style>
<div x-data="{ activeTab: '{{ $activeTab }}' }" class="h-full flex flex-col">

    {{-- Tab Buttons --}}
    <div class="shrink-0 flex items-center gap-2 mb-4">
        <button @click="activeTab = 'create'; window.history.replaceState(null, '', '{{ route('owner.transactions.page', ['tab' => 'create']) }}')"
            :class="activeTab === 'create' ? 'bg-[#764706] text-white shadow-md' : 'bg-white text-[#5f4a3b] hover:bg-[#f5f0eb]'"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition border border-[#f0e8e0] cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Buat Transaksi
        </button>
        <button @click="activeTab = 'history'; window.history.replaceState(null, '', '{{ route('owner.transactions.page', ['tab' => 'history']) }}')"
            :class="activeTab === 'history' ? 'bg-[#764706] text-white shadow-md' : 'bg-white text-[#5f4a3b] hover:bg-[#f5f0eb]'"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition border border-[#f0e8e0] cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            Riwayat Transaksi
        </button>
    </div>

    {{-- Tab: Buat Transaksi --}}
    <div x-show="activeTab === 'create'" x-cloak class="flex-1 min-h-0">
        <div class="flex gap-6 h-full" x-data="posApp()">

            {{-- Menu Grid --}}
            <div class="flex-1 flex flex-col" style="height: calc(100vh - 8rem);">
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#f0e8e0] shrink-0 flex items-center gap-3">
                    <div class="relative flex-1">
                        <input type="text" x-model="searchQuery" placeholder="Cari menu..."
                            class="w-full pl-4 pr-10 py-3 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        <button type="button" @click="searchQuery = ''" x-show="searchQuery !== ''"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <select x-model="selectedCategory" @change="$el.form && null"
                        class="py-3 pl-4 pr-9 text-sm rounded-xl border border-[#f0e8e0] bg-[#faf6f2] text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition appearance-none cursor-pointer shrink-0">
                        <option value="">Semua</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="menu-scroll" class="flex-1 overflow-y-auto mt-4 pr-4 pb-4">
                    <div id="menu-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($menus as $menu)
                            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden cursor-pointer hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 ho-menu-card" style="--md: {{ $loop->index * 0.05 }}s"
                                x-show="menuMatches('{{ addslashes(strtolower($menu->name)) }}', {{ $menu->category_id }})"
                                @click="addItem({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->thumb_url ?: '' }}', '{{ $menu->category->name ?? '' }}', {{ $menu->category_id }})">
                                <div class="w-full h-28 bg-[#FFF0DA] flex items-center justify-center overflow-hidden">
                                    @if ($menu->photo)
                                        <img src="{{ $menu->thumb_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h4 class="text-sm font-bold text-[#2b1f17] truncate">{{ $menu->name }}</h4>
                                    <p class="text-xs text-[#8b684b]">{{ $menu->category->name ?? '' }}</p>
                                    <p class="text-sm font-black text-[#764706] mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="menu-empty" x-show="noMenusFound" x-cloak class="flex-col items-center justify-center py-16">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                        <p class="mt-3 text-sm font-bold text-[#8b684b]" x-text="searchQuery !== '' ? `Tidak ada hasil pencarian untuk &quot;${searchQuery.trim()}&quot;` : 'Menu tidak ditemukan'"></p>
                    </div>

                    <div id="menu-sentinel" class="h-1"></div>

                    <div id="menu-skeleton" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4" style="display:none">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="rounded-2xl border border-[#f0e8e0] overflow-hidden animate-pulse">
                                <div class="w-full h-28 bg-[#f0e8e0]"></div>
                                <div class="p-3 space-y-2">
                                    <div class="h-4 bg-[#f0e8e0] rounded w-2/3"></div>
                                    <div class="h-3 bg-[#f0e8e0] rounded w-1/3"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Keranjang --}}
            <div class="w-96 shrink-0 flex flex-col">
                <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] flex flex-col" style="height: calc(100vh - 8rem);">

                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-[#f0e8e0] shrink-0 flex items-center justify-between">
                        <h3 class="text-base font-black text-[#2b1f17]">Keranjang</h3>
                        <button type="button" @click="showPromoModal = true"
                            class="inline-flex items-center gap-1.5 text-[11px] font-bold bg-[#FFF0DA] text-[#764706] px-2.5 py-1.5 rounded-lg hover:bg-[#f0e0c0] transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                            Lihat Promo
                        </button>
                    </div>

                    {{-- Items --}}
                    <div class="flex-1 overflow-y-auto p-4 space-y-2 min-h-0">
                        <template x-if="cart.length === 0">
                            <div class="flex flex-col items-center justify-center h-full text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.046A1.125 1.125 0 0 0 18.054 3H5.106m2.394 11.25l-1.5-6H7.5m0 0L6.106 3" /></svg>
                                <p class="mt-2 text-sm font-bold text-[#8b684b]">Keranjang kosong</p>
                                <p class="text-xs text-[#b0a090]">Klik menu untuk menambah item</p>
                            </div>
                        </template>

                        <template x-for="(item, index) in cart" :key="item.menu_id">
                            <div class="bg-[#faf7f4] rounded-xl p-2.5 border border-[#f0e8e0] space-y-1.5">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-[#FFF0DA] flex items-center justify-center shrink-0 overflow-hidden">
                                        <template x-if="item.photo">
                                            <img :src="item.photo" :alt="item.name" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!item.photo">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-[#2b1f17] leading-tight" x-text="item.name"></p>
                                        <p class="text-[11px] text-[#8b684b] mt-0.5" x-text="'Rp ' + formatNumber(item.price) + ' / item'"></p>
                                    </div>
                                    <button @click="removeItem(index)" class="w-5 h-5 rounded text-red-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition cursor-pointer shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1">
                                        <button @click="updateQty(index, -1)" class="w-6 h-6 rounded bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition text-xs font-bold cursor-pointer">-</button>
                                        <span class="w-7 text-center text-sm font-bold text-[#2b1f17]" x-text="item.quantity"></span>
                                        <button @click="updateQty(index, 1)" class="w-6 h-6 rounded bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200 transition text-xs font-bold cursor-pointer">+</button>
                                    </div>
                                    <div class="flex items-baseline gap-1.5">
                                        <div class="flex items-center gap-0.5" x-show="item.discount > 0">
                                            <span class="text-[11px] text-red-500" x-text="'-Rp ' + formatNumber(item.discount)"></span>
                                        </div>
                                        <p class="text-sm font-black text-[#764706]" x-text="'Rp ' + formatNumber(item.price * item.quantity - item.discount)"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <label class="text-[11px] text-[#8b684b] shrink-0">Diskon:</label>
                                    <div class="relative flex-1">
                                        <input type="text" @input="formatItemDiscount(index, $event)" :value="item.discount ? formatNumber(item.discount) : ''" placeholder="0"
                                            class="w-full pl-6 py-0.5 pr-1.5 rounded border border-[#f0e8e0] text-[11px] text-[#2b1f17] text-right focus:outline-none focus:ring-1 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                        <button type="button" @click="item.discount = 0; saveCart()" x-show="item.discount > 0"
                                            class="absolute left-0.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <div class="border-t border-[#f0e8e0] px-4 py-3 space-y-2 shrink-0">
                        {{-- Ringkasan --}}
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs">
                                <span class="text-[#5f4a3b] font-bold">Subtotal</span>
                                <span class="font-bold text-[#2b1f17]" x-text="'Rp ' + formatNumber(subtotal)"></span>
                            </div>

                            <div class="border-t border-dashed border-[#eadfd2]" x-show="itemDiscountsTotal > 0"></div>

                            <template x-for="item in discountedItems" :key="item.menu_id">
                                <div class="flex justify-between text-xs" x-show="item.discount > 0">
                                    <span class="text-[#5f4a3b] flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                                        <span>Diskon Item <span x-text="item.name"></span></span>
                                    </span>
                                    <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(item.discount)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-xs" x-show="itemDiscountsTotal > 0">
                                <span class="text-[#5f4a3b] font-bold">Total Diskon Item</span>
                                <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(itemDiscountsTotal)"></span>
                            </div>

                            <div class="border-t border-dashed border-[#eadfd2]" x-show="promoDiscountTotal > 0"></div>

                            <template x-for="promo in appliedPromos" :key="promo.id">
                                <div class="flex justify-between text-xs">
                                    <span class="text-[#5f4a3b]" x-text="promo.name"></span>
                                    <span class="font-bold text-green-600" x-text="'- Rp ' + formatNumber(promo.discount_amount)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between text-xs" x-show="promoDiscountTotal > 0">
                                <span class="text-[#5f4a3b] font-bold">Total Diskon Promo</span>
                                <span class="font-bold text-green-600" x-text="'- Rp ' + formatNumber(promoDiscountTotal)"></span>
                            </div>

                            <div class="border-t border-dashed border-[#eadfd2]"></div>

                            <div class="flex justify-between items-center text-xs">
                                <span class="text-[#5f4a3b] font-bold">Diskon Manual</span>
                                <div class="flex items-center gap-1.5">
                                    <span x-show="discount > 0" class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded-full">-Rp <span x-text="formatNumber(discount)"></span></span>
                                    <div class="relative">
                                        <input type="text" x-ref="discountInput" @input="formatDiscount($event)" placeholder="0"
                                            class="w-20 py-0.5 pl-6 pr-1.5 rounded border border-[#f0e8e0] text-xs text-[#2b1f17] text-right focus:outline-none focus:ring-1 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                        <button type="button" @click="clearDiscount()" x-show="discount > 0"
                                            class="absolute left-0.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between text-sm font-black border-t border-[#f0e8e0] pt-1">
                                <span class="text-[#2b1f17]">Total</span>
                                <span class="text-[#764706]" x-text="'Rp ' + formatNumber(total)"></span>
                            </div>

                            {{-- Nama Pelanggan --}}
                            <div class="space-y-1 pt-1">
                                <label class="text-[10px] font-bold text-[#8b684b] uppercase">Nama Pelanggan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" x-model="customerName" x-ref="customerInput"
                                        @input.debounce.500ms="savePosState(); customerNameError = false"
                                        maxlength="255" placeholder="Nama pelanggan"
                                        :class="customerNameError ? 'border-red-400 focus:ring-red-300 focus:border-red-400' : 'border-[#f0e8e0] focus:ring-[#764706]/30 focus:border-[#764706]'"
                                        class="w-full pl-3 pr-8 py-1.5 rounded-lg border text-sm text-[#2b1f17] placeholder-[#b0a090] focus:outline-none focus:ring-2 transition">
                                    <button type="button" x-show="customerName.length > 0" x-cloak
                                        @click="customerName = ''; customerNameError = false; savePosState(); $refs.customerInput.focus()"
                                        class="absolute right-1.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-md text-[#b0a090] hover:text-[#764706] hover:bg-[#f5f0eb] flex items-center justify-center transition cursor-pointer"
                                        title="Hapus nama pelanggan" aria-label="Hapus nama pelanggan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                <p class="text-[10px] text-red-500" x-show="customerNameError" x-cloak>Nama pelanggan wajib diisi.</p>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-[#8b684b] uppercase">Metode Pembayaran</label>
                                <div class="grid grid-cols-5 gap-1">
                                    <button type="button" @click="paymentMethod = 'cash'"
                                        class="px-1 py-1.5 rounded-lg text-[10px] font-bold transition cursor-pointer border"
                                        :class="paymentMethod === 'cash' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">CASH</button>
                                    <button type="button" @click="paymentMethod = 'debit'"
                                        class="px-1 py-1.5 rounded-lg text-[10px] font-bold transition cursor-pointer border"
                                        :class="paymentMethod === 'debit' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">DEBIT</button>
                                    <button type="button" @click="paymentMethod = 'kredit'"
                                        class="px-1 py-1.5 rounded-lg text-[10px] font-bold transition cursor-pointer border"
                                        :class="paymentMethod === 'kredit' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">KREDIT</button>
                                    <button type="button" @click="paymentMethod = 'transfer'; setPaymentFromTotal()"
                                        class="px-1 py-1.5 rounded-lg text-[10px] font-bold transition cursor-pointer border"
                                        :class="paymentMethod === 'transfer' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">BANK</button>
                                    <button type="button" @click="paymentMethod = 'qris'"
                                        class="px-1 py-1.5 rounded-lg text-[10px] font-bold transition cursor-pointer border"
                                        :class="paymentMethod === 'qris' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">QRIS</button>
                                </div>
                            </div>
                        </div>

                        {{-- Bayar --}}
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="text" x-ref="paymentInput" @input="formatPayment($event)" placeholder="Bayar"
                                    class="w-full pr-2 pl-8 py-1.5 rounded-lg border border-[#f0e8e0] text-sm text-[#2b1f17] text-right focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                <button type="button" @click="clearPayment()" x-show="amountPaid > 0"
                                    class="absolute left-1.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            <button type="button" @click="setPaymentFromTotal()"
                                :disabled="total <= 0"
                                class="px-2 py-1.5 rounded-lg text-[11px] font-bold bg-[#FFF0DA] text-[#764706] hover:bg-[#f0e0c0] transition cursor-pointer disabled:opacity-50 shrink-0">
                                Uang Pas
                            </button>
                        </div>

                        <div class="flex justify-between text-xs" x-show="change >= 0">
                            <span class="text-[#5f4a3b]">Kembalian</span>
                            <span class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></span>
                        </div>

                        {{-- Keterangan metode non-cash --}}
                        <p class="text-[11px] text-[#8b684b]" x-show="paymentMethod === 'debit' || paymentMethod === 'kredit'">Pelanggan membayar melalui EDC, lalu tekan "Proses Pembayaran" untuk konfirmasi.</p>
                        <p class="text-[11px] text-[#8b684b]" x-show="paymentMethod === 'qris'">Pelanggan membayar melalui QRIS, lalu tekan "Proses Pembayaran" untuk menampilkan kode QR dan konfirmasi pembayaran.</p>
                        <p class="text-[11px] text-[#8b684b]" x-show="paymentMethod === 'transfer'">Transfer bank akan dikonfirmasi setelah menekan "Proses Pembayaran".</p>

                        {{-- Submit --}}
                        <form method="POST" action="{{ route('owner.transactions.store') }}" x-ref="form">
                            @csrf
                            <input type="hidden" name="items" :value="JSON.stringify(cart.map(i => ({menu_id: i.menu_id, quantity: i.quantity, discount: i.discount})))">
                            <input type="hidden" name="amount_paid" :value="amountPaid">
                            <input type="hidden" name="discount" :value="discount">
                            <input type="hidden" name="applied_promos" :value="JSON.stringify(appliedPromos.map(p => ({promo_id: p.id, discount_amount: p.discount_amount})))">
                            <input type="hidden" name="customer_name" :value="customerName.trim()">
                            <input type="hidden" name="payment_method" :value="paymentMethod">

                            <button type="submit" @click.prevent="submitTransaction"
                                :disabled="cart.length === 0 || (paymentMethod === 'cash' && amountPaid < total)"
                                class="w-full py-2 rounded-xl text-sm font-bold transition shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="cart.length > 0 && (paymentMethod !== 'cash' || amountPaid >= total) ? 'bg-[#764706] text-white hover:bg-[#5a3606]' : 'bg-gray-200 text-gray-500'">
                                Proses Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Transfer Bank Modal --}}
            <div x-show="showBankModal" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showBankModal = false">
                <div x-show="showBankModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white rounded-2xl shadow-xl p-8 max-w-md w-full mx-4 text-center space-y-5">

                    <button type="button" @click="showBankModal = false"
                        class="absolute top-3 right-3 w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="w-14 h-14 mx-auto bg-[#FFF0DA] rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-black text-[#2b1f17]">Transfer Bank</h3>
                        <p class="text-sm text-[#8b684b] mt-1">Minta pelanggan transfer ke rekening di bawah ini.</p>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(account, index) in bankAccounts" :key="index">
                            <button type="button" @click="selectedBankIndex = index"
                                class="w-full px-4 py-3 rounded-xl border-2 text-left transition cursor-pointer"
                                :class="selectedBankIndex === index ? 'border-[#764706] bg-[#FFF0DA]' : 'border-[#f0e8e0] bg-white hover:border-[#b8a898]'">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-sm font-black text-[#2b1f17]" x-text="account.bank"></span>
                                    <span class="text-[10px] font-black tracking-wide text-[#764706]" x-show="selectedBankIndex === index">DIPILIH</span>
                                </div>
                                <p class="text-lg font-black tracking-widest text-[#764706] mt-1" x-text="account.account_number"></p>
                                <p class="text-xs text-[#8b684b]" x-text="account.account_name"></p>
                            </button>
                        </template>
                    </div>

                    <div class="bg-[#faf7f4] rounded-xl p-4">
                        <p class="text-sm text-[#5f4a3b]">Total yang harus dibayar</p>
                        <p class="text-2xl font-black text-[#764706]" x-text="'Rp ' + formatNumber(total)"></p>
                    </div>

                    <div class="space-y-2">
                        <button type="button" @click="bankTransferPaid()"
                            class="w-full py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-green-600 to-[#764706] text-white hover:from-green-700 hover:to-[#5a3606] transition shadow-md cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            Pembayaran Berhasil
                        </button>
                        <button type="button" @click="showBankModal = false"
                            class="w-full py-2.5 rounded-xl text-sm font-bold text-[#764706] bg-[#764706]/10 border-2 border-[#764706]/30 hover:bg-[#764706]/20 transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            {{-- Daftar Promo Modal --}}
            <div x-show="showPromoModal" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="showPromoModal = false">
                <div x-show="showPromoModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden">

                    {{-- Modal Header --}}
                    <div class="px-5 py-4 border-b border-[#f0e8e0] flex items-center justify-between">
                        <h3 class="text-base font-black text-[#2b1f17] flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-black"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                            Daftar Promo
                        </h3>
                        <button type="button" @click="showPromoModal = false"
                            class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-5 space-y-3 overflow-y-auto max-h-[70vh]">
                        @forelse ($promos as $promo)
                            <div class="bg-[#faf7f4] rounded-xl border border-[#f0e8e0] p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <h4 class="text-sm font-black text-[#2b1f17] leading-tight">{{ $promo->name }}</h4>
                                    <span class="shrink-0 text-xs font-bold text-[#764706] bg-[#FFF0DA] px-2.5 py-0.5 rounded-full">
                                        @if ($promo->type === 'percentage')
                                            {{ $promo->value }}%
                                        @else
                                            Rp {{ number_format($promo->value, 0, ',', '.') }}{{ $promo->scope !== 'transaction' ? ' / item' : '' }}
                                        @endif
                                    </span>
                                </div>
                                @if ($promo->description)
                                    <p class="text-xs text-[#8b684b] mt-1">{{ $promo->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 flex-wrap mt-2">
                                    @if ($promo->scope === 'item')
                                        <span class="text-[10px] font-bold text-[#764706] bg-[#FFF0DA] px-2 py-0.5 rounded-full">Item{{ $promo->menus->isNotEmpty() ? ' · '.$promo->menus->pluck('name')->join(', ') : '' }}</span>
                                    @elseif ($promo->scope === 'category')
                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Kategori{{ $promo->target ? ' · '.$promo->target->name : '' }}</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-full">Transaksi</span>
                                    @endif
                                    @if ($promo->min_quantity)
                                        <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Min {{ $promo->min_quantity }} item</span>
                                    @endif
                                    @if ($promo->min_amount)
                                        <span class="text-[10px] font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">Min Rp {{ number_format($promo->min_amount, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-[#b0a090] mt-2">
                                    @if ($promo->start_date || $promo->end_date)
                                        Berlaku {{ $promo->start_date ? $promo->start_date->format('d M Y') : '...' }} — {{ $promo->end_date ? $promo->end_date->format('d M Y') : '...' }}
                                    @else
                                        Tanpa batas waktu
                                    @endif
                                </p>
                            </div>
                        @empty
                            <div class="flex flex-col items-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                                <p class="mt-2 text-sm font-bold text-[#8b684b]">Belum ada promo tersedia</p>
                            </div>
                        @endforelse
                        <p class="text-[11px] text-[#b0a090] text-center pt-1">Promo otomatis berlaku saat syarat terpenuhi.</p>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-5 py-4 border-t border-[#f0e8e0]">
                        <a href="{{ route('owner.promos.index') }}"
                            class="w-full block text-center py-2.5 rounded-xl text-sm font-bold bg-[#764706] text-white hover:bg-[#5a3606] transition cursor-pointer">
                            Kelola Promo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab: Riwayat Transaksi --}}
    <div x-show="activeTab === 'history'" x-cloak class="flex-1 min-h-0 overflow-y-auto">
        <div class="space-y-6">

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total</p>
                        <h3 class="text-xl font-black text-[#2b1f17]"><span x-data="hoCountUp({{ $totalTransactions }}, { delay: 150 })" x-text="shown">0</span></h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan</p>
                        <h3 class="text-xl font-black text-[#2b1f17]"><span x-data="hoCountUp({{ (int) $totalRevenue }}, { prefix: 'Rp ', delay: 300 })" x-text="shown">Rp 0</span></h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25A1.5 1.5 0 0 1 3.75 18.75v-12A1.5 1.5 0 0 1 5.25 5.25Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Hari Ini</p>
                        <h3 class="text-xl font-black text-[#2b1f17]"><span x-data="hoCountUp({{ $todayTransactions }}, { delay: 450 })" x-text="shown">0</span></h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan Hari Ini</p>
                        <h3 class="text-xl font-black text-[#2b1f17]"><span x-data="hoCountUp({{ (int) $todayRevenue }}, { prefix: 'Rp ', delay: 600 })" x-text="shown">Rp 0</span></h3>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#f0e8e0]">
                    <h3 class="text-sm font-black text-[#2b1f17]">Filter & Pencarian</h3>
                </div>
                <div class="p-5">
                    <form method="GET" action="{{ route('owner.transactions.page', ['tab' => 'history']) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <input type="hidden" name="tab" value="history">
                        <div>
                            <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ID, status..."
                                class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Status</label>
                            <select name="status"
                                class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                <option value="">Semua Status</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                Cari
                            </button>
                            <a href="{{ route('owner.transactions.page', ['tab' => 'history']) }}"
                                class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#764706] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar Transaksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#f0e8e0]">
                    <h3 class="text-sm font-black text-[#2b1f17]">Daftar Transaksi Saya</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#f0e8e0] bg-[#faf7f4]">
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider w-12">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kode Transaksi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f0e8e0]">
                            @forelse ($transactions as $transaction)
                                <tr class="hover:bg-[#faf7f4] transition">
                                    <td class="px-6 py-4 text-[#8b684b] w-12">{{ $transactions->firstItem() + $loop->index }}</td>
                                    <td class="px-6 py-4 font-bold text-[#2b1f17]">{{ $transaction->code }}</td>
                                    <td class="px-6 py-4 text-[#5f4a3b]">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 font-bold text-[#2b1f17]">{{ $transaction->customer_name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] font-bold text-[#5f4a3b] bg-[#f5f0eb] px-2.5 py-1 rounded-full uppercase">{{ $transaction->payment_method }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-[#764706]">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($transaction->status === 'completed')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-700 bg-green-100 px-2.5 py-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-700 bg-red-100 px-2.5 py-1 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('owner.transactions.show', $transaction) }}"
                                                class="inline-flex items-center gap-1.5 bg-[#f5f0eb] text-[#8b684b] px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#ebe3db] transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                Detail
                                            </a>
                                            @if ($transaction->status === 'completed')
                                                <form action="{{ route('owner.transactions.cancel', $transaction) }}" method="POST" onsubmit="event.preventDefault(); confirmCancel(this, 'Yakin ingin membatalkan transaksi {{ $transaction->code }}?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-red-100 transition cursor-pointer" title="Batalkan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            <p class="mt-3 text-sm font-bold text-[#8b684b]">Belum ada riwayat transaksi</p>
                                            <button @click="activeTab = 'create'" class="mt-2 text-sm text-[#764706] hover:underline font-bold cursor-pointer">Buat Transaksi Baru</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-[#f0e8e0]">
                        @php
                            $queryParams = array_merge(request()->query(), ['tab' => 'history']);
                        @endphp
                        {{ $transactions->appends($queryParams)->links('pagination::tailwind', ['resultsLabel' => 'Riwayat Transaksi']) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
let menuPage = {{ $menus->currentPage() }};
let menuHasMore = {{ $menus->hasMorePages() ? 'true' : 'false' }};
let menuLoading = false;

async function loadMoreMenus() {
    if (menuLoading || !menuHasMore) return;
    menuLoading = true;
    menuPage++;

    document.getElementById('menu-skeleton').style.display = 'grid';

    try {
        const res = await fetch(`{{ route('owner.transactions.load-more-menus') }}?page=${menuPage}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();

        document.getElementById('menu-skeleton').style.display = 'none';

        if (data.html) {
            document.getElementById('menu-grid').insertAdjacentHTML('beforeend', data.html);
        }

        menuHasMore = data.hasMore;
    } catch (e) {
        document.getElementById('menu-skeleton').style.display = 'none';
        menuPage--;
    }

    menuLoading = false;
}

const menuScroll = document.getElementById('menu-scroll');
const menuSentinel = document.getElementById('menu-sentinel');
const menuObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && menuHasMore && !menuLoading) {
        loadMoreMenus();
    }
}, { root: menuScroll, rootMargin: '200px' });

menuObserver.observe(menuSentinel);



function posApp() {
    const currentUserId = {{ auth()->id() }};
    let posState = JSON.parse(localStorage.getItem('pos_state') || '{}');
    let posCart = JSON.parse(localStorage.getItem('pos_cart') || '[]');

    if (posState.userId !== undefined && Number(posState.userId) !== Number(currentUserId)) {
        localStorage.removeItem('pos_cart');
        localStorage.removeItem('pos_state');
        posCart = [];
        posState = {};
    }

    return {
        cart: posCart,
        searchQuery: '',
        selectedCategory: '',
        discount: posState.discount || 0,
        amountPaid: posState.amountPaid || 0,
        customerName: posState.customerName || '',
        customerNameError: false,
        paymentMethod: 'cash',
        showBankModal: false,
        bankAccounts: @json(config('hideout.bank_accounts')),
        selectedBankIndex: 0,
        showPromoModal: false,
        noMenusFound: false,
        promos: @json($promos),

        saveCart() {
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        },

        savePosState() {
            localStorage.setItem('pos_state', JSON.stringify({ userId: {{ auth()->id() }}, discount: this.discount, amountPaid: this.amountPaid, customerName: this.customerName }));
        },

        menuMatches(name, categoryId) {
            const q = this.searchQuery.toLowerCase().trim();
            const nameMatch = q === '' || (name || '').includes(q);
            const catMatch = this.selectedCategory === '' || categoryId == this.selectedCategory;
            return nameMatch && catMatch;
        },

        syncMenuEmpty() {
            this.$nextTick(() => {
                let visible = 0;
                this.$root.querySelectorAll('#menu-grid .ho-menu-card').forEach(function (card) {
                    if (card.style.display !== 'none') visible++;
                });
                this.noMenusFound = visible === 0 && (this.searchQuery !== '' || this.selectedCategory !== '');
            });
        },

        init() {
            this.$watch('searchQuery', () => this.syncMenuEmpty());
            this.$watch('selectedCategory', () => this.syncMenuEmpty());
            this.$nextTick(() => {
                if (this.discount > 0 && this.$refs.discountInput) {
                    this.$refs.discountInput.value = this.formatNumber(this.discount);
                }
                if (this.amountPaid > 0 && this.$refs.paymentInput) {
                    this.$refs.paymentInput.value = this.formatNumber(this.amountPaid);
                }
            });
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get itemDiscountsTotal() {
            return this.cart.reduce((sum, item) => sum + item.discount, 0);
        },

        get discountedItems() {
            return this.cart.filter(item => item.discount > 0);
        },

        get appliedPromos() {
            let applied = [];
            let cartSubtotal = this.subtotal;
            let cartItems = this.cart;

            for (let promo of this.promos) {
                let discountAmount = this.calculatePromoDiscount(promo, cartItems, cartSubtotal);
                if (discountAmount > 0) {
                    applied.push({
                        id: promo.id,
                        name: promo.name,
                        discount_amount: discountAmount
                    });
                }
            }

            return applied;
        },

        get promoDiscountTotal() {
            return this.appliedPromos.reduce((sum, p) => sum + p.discount_amount, 0);
        },

        get total() {
            return Math.max(0, this.subtotal - this.itemDiscountsTotal - this.promoDiscountTotal - this.discount);
        },

        get change() {
            if (this.paymentMethod === 'cash') {
                return Math.max(0, this.amountPaid - this.total);
            }
            return 0;
        },

        calculatePromoDiscount(promo, cartItems, cartSubtotal) {
            if (cartItems.length === 0) return 0;

            if (promo.scope === 'item') {
                let targetMenuIds = (promo.menus || []).map(m => m.id);
                let qualifying = cartItems.filter(i => targetMenuIds.some(id => id == i.menu_id));
                if (qualifying.length === 0) return 0;

                let totalQty = qualifying.reduce((s, i) => s + i.quantity, 0);
                let totalAmount = qualifying.reduce((s, i) => s + ((i.price * i.quantity) - i.discount), 0);

                if (promo.min_quantity && totalQty < promo.min_quantity) return 0;
                if (promo.min_amount && totalAmount < promo.min_amount) return 0;

                if (promo.type === 'percentage') {
                    return Math.round(totalAmount * (promo.value / 100));
                }
                return promo.value * totalQty;
            }

            if (promo.scope === 'category') {
                let qualifying = cartItems.filter(i => i.category_id === promo.target_id);
                if (qualifying.length === 0) return 0;

                let totalQty = qualifying.reduce((s, i) => s + i.quantity, 0);
                let totalAmount = qualifying.reduce((s, i) => s + ((i.price * i.quantity) - i.discount), 0);

                if (promo.min_quantity && totalQty < promo.min_quantity) return 0;
                if (promo.min_amount && totalAmount < promo.min_amount) return 0;

                if (promo.type === 'percentage') {
                    return Math.round(totalAmount * (promo.value / 100));
                }
                return promo.value * totalQty;
            }

            if (promo.scope === 'transaction') {
                let afterItemDiscounts = cartSubtotal - this.itemDiscountsTotal;

                if (promo.min_amount && afterItemDiscounts < promo.min_amount) return 0;

                if (promo.min_quantity) {
                    let totalQty = cartItems.reduce((s, i) => s + i.quantity, 0);
                    if (totalQty < promo.min_quantity) return 0;
                }

                if (promo.type === 'percentage') {
                    return Math.round(afterItemDiscounts * (promo.value / 100));
                }
                return promo.value;
            }

            return 0;
        },

        addItem(menuId, name, price, photo, category, categoryId) {
            const existing = this.cart.find(i => i.menu_id === menuId);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({ menu_id: menuId, name, price, photo, category, category_id: categoryId, quantity: 1, discount: 0 });
            }
            this.saveCart();
        },

        updateQty(index, delta) {
            this.cart[index].quantity += delta;
            if (this.cart[index].quantity <= 0) {
                this.cart.splice(index, 1);
            }
            this.saveCart();
        },

        removeItem(index) {
            this.cart.splice(index, 1);
            this.saveCart();
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },

        formatPayment(e) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            this.amountPaid = raw ? parseInt(raw) : 0;
            e.target.value = raw ? this.formatNumber(this.amountPaid) : '';
            this.savePosState();
        },

        formatDiscount(e) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            this.discount = raw ? parseInt(raw) : 0;
            e.target.value = raw ? this.formatNumber(this.discount) : '';
            this.savePosState();
        },

        clearDiscount() {
            this.discount = 0;
            this.$nextTick(() => {
                if (this.$refs.discountInput) this.$refs.discountInput.value = '';
            });
            this.savePosState();
        },

        clearPayment() {
            this.amountPaid = 0;
            this.$nextTick(() => {
                if (this.$refs.paymentInput) this.$refs.paymentInput.value = '';
            });
            this.savePosState();
        },

        formatItemDiscount(index, e) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            this.cart[index].discount = raw ? parseInt(raw) : 0;
            e.target.value = raw ? this.formatNumber(this.cart[index].discount) : '';
            this.saveCart();
        },

        setPaymentFromTotal() {
            this.amountPaid = this.total;
            this.$nextTick(() => {
                let input = this.$refs.paymentInput;
                if (input) input.value = this.total > 0 ? this.formatNumber(this.total) : '';
            });
            this.savePosState();
        },

        validateCustomerName() {
            if (!this.customerName.trim()) {
                this.customerNameError = true;
                this.$nextTick(() => {
                    let input = this.$refs.customerInput;
                    if (input) input.focus();
                });
                return false;
            }
            this.customerNameError = false;
            return true;
        },

        submitTransaction() {
            if (this.cart.length === 0) return;
            if (!this.validateCustomerName()) return;
            if (this.paymentMethod === 'transfer') {
                this.showBankModal = true;
                return;
            }
            if (this.amountPaid < this.total) return;
            localStorage.removeItem('pos_cart');
            localStorage.removeItem('pos_state');
            this.$refs.form.submit();
        },

        bankTransferPaid() {
            this.showBankModal = false;
            if (!this.validateCustomerName()) return;
            this.amountPaid = this.total;
            this.$nextTick(() => {
                let input = this.$refs.paymentInput;
                if (input) input.value = this.total > 0 ? this.formatNumber(this.total) : '';
            });
            localStorage.removeItem('pos_cart');
            localStorage.removeItem('pos_state');
            this.$refs.form.submit();
        }
    }
}
</script>
@endsection
