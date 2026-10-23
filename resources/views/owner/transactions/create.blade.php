@extends('layouts.app-nosidebar')

@section('title', 'Transaksi Baru')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    main { padding: 0 !important; overflow-y: auto !important; }
    .ho-anim { height: 100%; }

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
@if (session('success'))
    <style>
        .swal-card-custom { border-radius: 1rem !important; border: 1px solid #f0e8e0 !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important; padding: 1.5rem !important; }
        .swal-card-custom .swal2-icon { margin: 0 auto 0.5rem !important; }
        .swal-card-custom .swal2-title { color: #2b1f17 !important; font-weight: 900 !important; font-size: 1.25rem !important; }
        .swal-card-custom .swal2-html-container { color: #5f4a3b !important; font-size: 0.875rem !important; margin: 0.5rem 0 0 !important; }
    </style>
@endif

<div class="grid grid-cols-[1fr_24rem] gap-6 px-4 pb-4 pt-8 h-full" x-data="posApp()">

    {{-- Menu Grid --}}
    <div class="flex flex-col min-h-0">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#f0e8e0] shrink-0 flex items-center gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b0a090]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" x-model="searchQuery" @input.debounce.500ms="posSearchQuery = searchQuery; reloadMenus()" placeholder="Cari menu..."
                    class="w-full pl-10 pr-10 py-3 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                <button type="button" @click="searchQuery = ''; posSearchQuery = ''; reloadMenus()" x-show="searchQuery !== ''"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <select x-model="selectedCategory" @change="filterCategory(selectedCategory === '' ? null : parseInt(selectedCategory))"
                class="py-3 pl-4 pr-9 text-sm rounded-xl border border-[#f0e8e0] bg-[#faf6f2] text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition appearance-none cursor-pointer shrink-0">
                <option value="">Semua</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="menu-scroll" class="flex-1 overflow-y-auto min-h-0 mt-4 pr-4 pb-4">
            <div id="menu-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($menus as $menu)
                    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden cursor-pointer hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 ho-menu-card" style="--md: {{ $loop->index * 0.05 }}s"
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

            <div id="menu-empty" class="{{ $menus->isEmpty() ? 'flex' : 'hidden' }} flex-col items-center justify-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                <p id="menu-empty-text" class="mt-3 text-sm font-bold text-[#8b684b]"></p>
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
    <div class="flex flex-col pb-[20px]">
        <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] flex-1 flex flex-col">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-[#f0e8e0] flex items-center justify-between">
                <h3 class="text-base font-black text-[#2b1f17]">Keranjang</h3>
                <div class="flex items-center gap-2">
                    <button type="button" @click="resetCart()" x-show="cart.length > 0"
                        class="inline-flex items-center gap-1.5 text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        Reset
                    </button>
                    <button type="button" @click="showPromoModal = true"
                        class="inline-flex items-center gap-1.5 text-xs font-bold bg-[#FFF0DA] text-[#764706] px-3 py-1.5 rounded-lg hover:bg-[#f0e0c0] transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                        Lihat Promo
                    </button>
                </div>
            </div>

            {{-- Items --}}
            <div class="overflow-y-auto min-h-0 max-h-[330px] p-5 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.046A1.125 1.125 0 0 0 18.054 3H5.106m2.394 11.25l-1.5-6H7.5m0 0L6.106 3" /></svg>
                        <p class="mt-2 text-sm font-bold text-[#8b684b]">Keranjang kosong</p>
                        <p class="text-xs text-[#b0a090]">Klik menu untuk menambah item</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.menu_id">
                    <div class="bg-[#faf7f4] rounded-xl p-3 border border-[#f0e8e0] space-y-2">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#FFF0DA] flex items-center justify-center shrink-0 overflow-hidden">
                                <template x-if="item.photo">
                                    <img :src="item.photo" :alt="item.name" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.photo">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-[#2b1f17] leading-tight" x-text="item.name"></p>
                                <p class="text-xs text-[#8b684b] mt-0.5" x-text="'Rp ' + formatNumber(item.price) + ' / item'"></p>
                            </div>
                            <button @click="removeItem(index)" class="w-6 h-6 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition cursor-pointer shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <button @click="updateQty(index, -1)" class="w-7 h-7 rounded-lg bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition text-sm font-bold cursor-pointer">-</button>
                                <span class="w-8 text-center text-sm font-bold text-[#2b1f17]" x-text="item.quantity"></span>
                                <button @click="updateQty(index, 1)" class="w-7 h-7 rounded-lg bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200 transition text-sm font-bold cursor-pointer">+</button>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <div class="flex items-center gap-1" x-show="item.discount > 0">
                                    <span class="text-xs text-red-500" x-text="'-Rp ' + formatNumber(item.discount)"></span>
                                </div>
                                <p class="text-sm font-black text-[#764706]" x-text="'Rp ' + formatNumber(item.price * item.quantity - item.discount)"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-[#8b684b] shrink-0">Diskon:</label>
                            <div class="relative flex-1">
                                <input type="text" @input="formatItemDiscount(index, $event)" :value="item.discount ? formatNumber(item.discount) : ''" placeholder="0"
                                    class="w-full pl-8 py-1 pr-2 rounded-lg border border-[#f0e8e0] text-xs text-[#2b1f17] text-right focus:outline-none focus:ring-1 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                <button type="button" @click="item.discount = 0; saveCart()" x-show="item.discount > 0"
                                    class="absolute left-1.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="border-t border-[#f0e8e0] p-5 space-y-4 shrink-0 mt-auto">
                {{-- Subtotal --}}
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-[#5f4a3b] font-bold">Subtotal</span>
                        <span class="font-bold text-[#2b1f17]" x-text="'Rp ' + formatNumber(subtotal)"></span>
                    </div>

                    <div class="border-t border-dashed border-[#eadfd2]" x-show="itemDiscountsTotal > 0"></div>

                    {{-- Diskon Item --}}
                    <template x-for="item in discountedItems" :key="item.menu_id">
                        <div class="flex justify-between text-sm" x-show="item.discount > 0">
                            <span class="text-[#5f4a3b] flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                                <span>Diskon Item <span x-text="item.name"></span></span>
                            </span>
                            <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(item.discount)"></span>
                        </div>
                    </template>
                    <div class="flex justify-between text-sm" x-show="itemDiscountsTotal > 0">
                        <span class="text-[#5f4a3b] font-bold">Total Diskon Item</span>
                        <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(itemDiscountsTotal)"></span>
                    </div>

                    <div class="border-t border-dashed border-[#eadfd2]" x-show="promoDiscountTotal > 0"></div>

                    {{-- Diskon Promo --}}
                    <template x-for="promo in appliedPromos" :key="promo.id">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-[#5f4a3b] flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" /></svg>
                                <span x-text="promo.name"></span>
                            </span>
                            <span class="font-bold text-green-600" x-text="'- Rp ' + formatNumber(promo.discount_amount)"></span>
                        </div>
                    </template>

                    <div class="flex justify-between text-sm" x-show="promoDiscountTotal > 0">
                        <span class="text-[#5f4a3b] font-bold">Total Diskon Promo</span>
                        <span class="font-bold text-green-600" x-text="'- Rp ' + formatNumber(promoDiscountTotal)"></span>
                    </div>

                    <div class="border-t border-dashed border-[#eadfd2]"></div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b] font-bold">Diskon Manual</span>
                        <div class="flex items-center gap-2">
                            <span x-show="discount > 0" class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">-Rp <span x-text="formatNumber(discount)"></span></span>
                            <div class="relative">
                                <input type="text" x-ref="discountInput" @input="formatDiscount($event)" placeholder="0"
                                    class="w-28 pr-3 pl-8 py-1.5 rounded-lg border border-[#f0e8e0] text-sm text-[#2b1f17] text-right focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                <button type="button" @click="clearDiscount()" x-show="discount > 0"
                                    class="absolute left-1.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between text-base font-black border-t border-[#f0e8e0] pt-2">
                        <span class="text-[#2b1f17]">Total</span>
                        <span class="text-[#764706]" x-text="'Rp ' + formatNumber(total)"></span>
                    </div>

                    {{-- Nama Pelanggan --}}
                    <div class="space-y-2 pt-1">
                        <label class="text-xs font-bold text-[#5f4a3b]">Nama Pelanggan <span class="text-[#b0a090] font-normal">(opsional)</span></label>
                        <input type="text" x-model="customerName" @input.debounce.500ms="savePosState()" maxlength="255" placeholder="Contoh: Budi"
                            class="w-full px-3 py-2 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] placeholder-[#b0a090] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-[#5f4a3b]">Metode Pembayaran</label>
                    <div class="grid grid-cols-5 gap-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'cash' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                            CASH
                        </button>
                        <button type="button" @click="paymentMethod = 'debit'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'debit' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                            DEBIT
                        </button>
                        <button type="button" @click="paymentMethod = 'kredit'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'kredit' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                            KREDIT
                        </button>
                        <button type="button" @click="paymentMethod = 'transfer'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'transfer' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                            BANK
                        </button>
                        <button type="button" @click="paymentMethod = 'qris'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'qris' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 19.5h.75v.75h-.75v-.75Z" /></svg>
                            QRIS
                        </button>
                    </div>
                </div>

                {{-- Bayar (Cash) --}}
                <div class="space-y-2" x-show="paymentMethod === 'cash'">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b]">Bayar</span>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="setPaymentFromTotal()"
                                :disabled="total <= 0"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FFF0DA] text-[#764706] hover:bg-[#f0e0c0] transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                Uang Pas
                            </button>
                            <div class="relative">
                                <input type="text" x-ref="paymentInput" @input="formatPayment($event)"
                                    :value="amountPaid > 0 ? formatNumber(amountPaid) : ''"
                                    placeholder="0"
                                    class="w-36 pr-3 pl-8 py-1.5 rounded-lg border border-[#f0e8e0] text-sm text-[#2b1f17] text-right focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                                <button type="button" @click="clearPayment()" x-show="amountPaid > 0"
                                    class="absolute left-1.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm" x-show="change >= 0 && amountPaid > 0">
                        <span class="text-[#5f4a3b]">Kembalian</span>
                        <span class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></span>
                    </div>
                </div>

                {{-- Bayar (Debit/Kredit via EDC) --}}
                <div class="space-y-2" x-show="paymentMethod === 'debit' || paymentMethod === 'kredit'">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b]">Total Pembayaran</span>
                        <span class="font-bold text-[#764706]" x-text="'Rp ' + formatNumber(total)"></span>
                    </div>
                    <p class="text-xs text-[#8b684b]">Pelanggan membayar melalui EDC, lalu tekan "Proses Pembayaran" untuk konfirmasi berhasil / gagal.</p>
                </div>

                {{-- Bayar (Transfer) --}}
                <div class="space-y-2" x-show="paymentMethod === 'transfer'">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b]">Total Pembayaran</span>
                        <span class="font-bold text-[#764706]" x-text="'Rp ' + formatNumber(total)"></span>
                    </div>
                    <p class="text-xs text-[#8b684b]">Pilih bank tujuan, lalu tekan "Proses Pembayaran" untuk menampilkan detail rekening.</p>
                </div>

                {{-- Bayar (QRIS) --}}
                <div class="space-y-2" x-show="paymentMethod === 'qris'">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b]">Total Pembayaran</span>
                        <span class="font-bold text-[#764706]" x-text="'Rp ' + formatNumber(total)"></span>
                    </div>
                    <p class="text-xs text-[#8b684b]">Pelanggan membayar melalui QRIS, lalu tekan "Proses Pembayaran" untuk menampilkan kode QR dan konfirmasi pembayaran.</p>
                </div>

                {{-- Submit --}}
                <form method="POST" action="{{ route('owner.transactions.store') }}" x-ref="form">
                    @csrf
                    <input type="hidden" name="items" :value="JSON.stringify(cart.map(i => ({menu_id: i.menu_id, quantity: i.quantity, discount: i.discount})))">
                    <input type="hidden" name="amount_paid" :value="paymentMethod === 'cash' ? amountPaid : total">
                    <input type="hidden" name="discount" :value="discount">
                    <input type="hidden" name="payment_method" :value="paymentMethod">
                    <input type="hidden" name="customer_name" :value="customerName.trim()">
                    <input type="hidden" name="applied_promos" :value="JSON.stringify(appliedPromos.map(p => ({promo_id: p.id, discount_amount: p.discount_amount})))">
                    <input type="hidden" name="print_receipt" :value="printReceipt ? '1' : '0'">

                    <button type="button" @click="proceedPayment()"
                        :disabled="cart.length === 0 || paymentMethod === '' || (paymentMethod === 'cash' && amountPaid < total)"
                        class="w-full py-3 rounded-xl text-sm font-bold transition shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="cart.length > 0 && paymentMethod !== '' && (paymentMethod !== 'cash' || amountPaid >= total) ? 'bg-[#764706] text-white hover:bg-[#5a3606]' : 'bg-gray-200 text-gray-500'">
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
                    Sudah Dibayar
                </button>
                <button type="button" @click="showBankModal = false"
                    class="w-full py-2.5 rounded-xl text-sm font-bold text-[#764706] bg-[#764706]/10 border-2 border-[#764706]/30 hover:bg-[#764706]/20 transition shadow-sm cursor-pointer">
                    Batal
                </button>
            </div>
        </div>
    </div>

    {{-- QRIS Modal --}}
    <div x-show="showQrisModal" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showQrisModal = false">
        <div x-show="showQrisModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center space-y-5">

            <button type="button" @click="showQrisModal = false"
                class="absolute top-3 right-3 w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>

            <template x-if="!qrisFailed">
                <div class="space-y-5">
                    <h3 class="text-lg font-black text-[#2b1f17]">Scan QRIS</h3>

                    <div class="w-52 h-52 mx-auto bg-[#f0e8e0] rounded-xl flex items-center justify-center border-2 border-dashed border-[#d4c8b8] overflow-hidden">
                        <img src="{{ asset('images/qris.jpeg') }}" alt="QRIS" class="w-full h-full object-contain">
                    </div>

                    <div>
                        <p class="text-sm text-[#8b684b]">Total Pembayaran</p>
                        <p class="text-2xl font-black text-[#764706]" x-text="'Rp ' + formatNumber(total)"></p>
                    </div>

                    <div class="space-y-2">
                        <button type="button" @click="qrisPaid()"
                            class="w-full py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-green-600 to-[#764706] text-white hover:from-green-700 hover:to-[#5a3606] transition shadow-md cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            Pembayaran Berhasil
                        </button>
                        <button type="button" @click="qrisFail()"
                            class="w-full py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-red-50 via-red-50 to-[#764706]/15 text-red-600 border-2 border-[#764706]/40 hover:from-red-100 transition cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            Pembayaran Gagal
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="qrisFailed">
                <div class="space-y-5">
                    <div class="w-14 h-14 mx-auto bg-red-50 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-red-600">Pembayaran Gagal</h3>
                        <p class="text-sm text-[#8b684b] mt-1">Pelanggan belum menyelesaikan pembayaran. Silakan coba lagi.</p>
                    </div>
                    <div class="space-y-2">
                        <button type="button" @click="qrisFailed = false"
                            class="w-full py-2.5 rounded-xl text-sm font-bold bg-[#FFF0DA] text-[#764706] border-2 border-[#764706]/30 hover:bg-[#f0e0c0] transition cursor-pointer">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- EDC Modal (Debit/Kredit) --}}
    <div x-show="showEdcModal" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showEdcModal = false">
        <div x-show="showEdcModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center space-y-5">

            <button type="button" @click="showEdcModal = false"
                class="absolute top-3 right-3 w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>

            <template x-if="!edcFailed">
                <div class="space-y-5">
                    <div class="w-14 h-14 mx-auto bg-blue-50 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-[#2b1f17]">Pembayaran Debit/Kredit</h3>
                        <p class="text-sm text-[#8b684b] mt-1">Pelanggan telah membayar melalui EDC.</p>
                    </div>

                    <div>
                        <p class="text-sm text-[#5f4a3b]">Total Pembayaran</p>
                        <p class="text-2xl font-black text-[#764706]" x-text="'Rp ' + formatNumber(total)"></p>
                    </div>

                    <div class="space-y-2">
                        <button type="button" @click="edcPaid()"
                            class="w-full py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-green-600 to-[#764706] text-white hover:from-green-700 hover:to-[#5a3606] transition shadow-md cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            Pembayaran Berhasil
                        </button>
                        <button type="button" @click="edcFail()"
                            class="w-full py-3 rounded-xl text-sm font-bold bg-gradient-to-r from-red-50 via-red-50 to-[#764706]/15 text-red-600 border-2 border-[#764706]/40 hover:from-red-100 transition cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            Pembayaran Gagal
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="edcFailed">
                <div class="space-y-5">
                    <div class="w-14 h-14 mx-auto bg-red-50 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-red-600">Pembayaran Gagal</h3>
                        <p class="text-sm text-[#8b684b] mt-1">Silakan pilih metode pembayaran kembali.</p>
                    </div>
                    <div class="space-y-2">
                        <button type="button" @click="edcFailed = false"
                            class="w-full py-2.5 rounded-xl text-sm font-bold bg-[#FFF0DA] text-[#764706] border-2 border-[#764706]/30 hover:bg-[#f0e0c0] transition cursor-pointer">
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Confirm Modal --}}
    <div x-show="showConfirmModal" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showConfirmModal = false">
        <div x-show="showConfirmModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center space-y-5">

            <button type="button" @click="showConfirmModal = false"
                class="absolute top-3 right-3 w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>

            <div class="w-14 h-14 mx-auto bg-[#FFF0DA] rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
            </div>

            <h3 class="text-lg font-black text-[#2b1f17]">Konfirmasi Pembayaran</h3>

            <div class="bg-[#faf7f4] rounded-xl p-4 space-y-2 text-sm">
                <div class="flex justify-between" x-show="customerName.trim() !== ''">
                    <span class="text-[#5f4a3b] font-bold">Pelanggan</span>
                    <span class="font-bold text-[#2b1f17]" x-text="customerName.trim()"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#5f4a3b] font-bold">Subtotal</span>
                    <span class="font-bold text-[#2b1f17]" x-text="'Rp ' + formatNumber(subtotal)"></span>
                </div>
                <div class="flex justify-between" x-show="itemDiscountsTotal > 0">
                    <span class="text-[#5f4a3b] font-bold">Total Diskon Item</span>
                    <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(itemDiscountsTotal)"></span>
                </div>
                <div class="flex justify-between" x-show="promoDiscountTotal > 0">
                    <span class="text-[#5f4a3b] font-bold">Total Diskon Promo</span>
                    <span class="font-bold text-green-600" x-text="'- Rp ' + formatNumber(promoDiscountTotal)"></span>
                </div>
                <div class="flex justify-between" x-show="discount > 0">
                    <span class="text-[#5f4a3b] font-bold">Diskon Manual</span>
                    <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(discount)"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#5f4a3b] font-bold">Total Bayar</span>
                    <span class="font-bold text-[#2b1f17]" x-text="'Rp ' + formatNumber(total)"></span>
                </div>
                <div class="flex justify-between" x-show="paymentMethod === 'cash'">
                    <span class="text-[#5f4a3b]">Bayar</span>
                    <span class="font-bold text-[#2b1f17]" x-text="'Rp ' + formatNumber(amountPaid)"></span>
                </div>
                <div class="flex justify-between" x-show="paymentMethod === 'cash' && change >= 0">
                    <span class="text-[#5f4a3b]">Kembalian</span>
                    <span class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#5f4a3b]">Metode Pembayaran</span>
                    <span class="font-bold text-[#2b1f17] uppercase" x-text="paymentMethod"></span>
                </div>
            </div>

            <div class="space-y-2">
                <button type="button" @click="submitTransaction(true)"
                    class="w-full py-3 rounded-xl text-sm font-bold bg-[#764706] text-white hover:bg-[#5a3606] transition shadow-md cursor-pointer flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034l.006.042m-.006-.042L15 9.375m3.25-2.342-.006.042m.006-.042L15 5.625" /></svg>
                    Simpan & Cetak Struk
                </button>
                <button type="button" @click="submitTransaction(false)"
                    class="w-full py-3 rounded-xl text-sm font-bold text-[#764706] bg-[#764706]/10 border-2 border-[#764706]/30 hover:bg-[#764706]/20 hover:border-[#764706] transition shadow-sm cursor-pointer flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Saja
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

<script>
let menuPage = 1;
let menuHasMore = true;
let menuLoading = false;
let posSearchQuery = '';
let posSelectedCategory = null;

async function loadMoreMenus() {
    if (menuLoading || !menuHasMore) return;
    menuLoading = true;
    menuPage++;

    document.getElementById('menu-skeleton').style.display = 'grid';

    let params = new URLSearchParams({ page: menuPage });
    if (posSearchQuery) params.set('search', posSearchQuery);
    if (posSelectedCategory) params.set('category_id', posSelectedCategory);

    try {
        const res = await fetch(`{{ route('owner.transactions.load-more-menus') }}?${params.toString()}`, {
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

    if (menuHasMore && menuSentinel.getBoundingClientRect().top - menuScroll.getBoundingClientRect().top < menuScroll.clientHeight + 200) {
        loadMoreMenus();
    }
}

async function reloadMenus() {
    menuPage = 1;
    menuHasMore = true;
    menuLoading = true;

    document.getElementById('menu-grid').innerHTML = '';
    document.getElementById('menu-skeleton').style.display = 'grid';

    let params = new URLSearchParams({ page: 1 });
    if (posSearchQuery) params.set('search', posSearchQuery);
    if (posSelectedCategory) params.set('category_id', posSelectedCategory);

    try {
        const res = await fetch(`{{ route('owner.transactions.load-more-menus') }}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();

        document.getElementById('menu-skeleton').style.display = 'none';

        const menuHtml = data.html || '';
        const menuEmpty = document.getElementById('menu-empty');
        const menuEmptyText = document.getElementById('menu-empty-text');

        document.getElementById('menu-grid').innerHTML = menuHtml;
        menuEmpty.classList.toggle('hidden', menuHtml !== '');
        menuEmpty.classList.toggle('flex', menuHtml === '');
        if (menuHtml === '') {
            menuEmptyText.textContent = posSearchQuery
                ? `Tidak ada hasil pencarian untuk "${posSearchQuery.trim()}"`
                : 'Menu tidak ditemukan';
        }

        menuHasMore = data.hasMore;
    } catch (e) {
        document.getElementById('menu-skeleton').style.display = 'none';
    }

    menuLoading = false;

    if (menuHasMore && menuSentinel.getBoundingClientRect().top - menuScroll.getBoundingClientRect().top < menuScroll.clientHeight + 200) {
        loadMoreMenus();
    }
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
        paymentMethod: 'cash',
        showQrisModal: false,
        qrisFailed: false,
        showConfirmModal: false,
        showEdcModal: false,
        edcFailed: false,
        showBankModal: false,
        bankAccounts: @json(config('hideout.bank_accounts')),
        selectedBankIndex: 0,
        showPromoModal: false,
        printReceipt: false,
        discount: posState.discount || 0,
        amountPaid: posState.amountPaid || 0,
        customerName: posState.customerName || '',
        promos: @json($promos),

        saveCart() {
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        },

        savePosState() {
            localStorage.setItem('pos_state', JSON.stringify({ userId: {{ auth()->id() }}, discount: this.discount, amountPaid: this.amountPaid, customerName: this.customerName }));
        },

        init() {
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
            return Math.max(0, this.amountPaid - this.total);
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

        filterCategory(categoryId) {
            this.selectedCategory = categoryId;
            posSelectedCategory = categoryId;
            reloadMenus();
        },

        reloadMenus() {
            posSearchQuery = this.searchQuery;
            posSelectedCategory = this.selectedCategory;
            reloadMenus();
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

        resetCart() {
            this.cart = [];
            this.discount = 0;
            this.amountPaid = 0;
            localStorage.removeItem('pos_cart');
            localStorage.removeItem('pos_state');
            this.$nextTick(() => {
                if (this.$refs.discountInput) this.$refs.discountInput.value = '';
                if (this.$refs.paymentInput) this.$refs.paymentInput.value = '';
            });
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

        qrisPaid() {
            this.paymentMethod = 'qris';
            this.showQrisModal = false;
            this.qrisFailed = false;
            this.showConfirmModal = true;
        },

        qrisFail() {
            this.qrisFailed = true;
        },

        proceedPayment() {
            if (this.paymentMethod === 'debit' || this.paymentMethod === 'kredit') {
                this.edcFailed = false;
                this.showEdcModal = true;
                return;
            }
            if (this.paymentMethod === 'transfer') {
                this.showBankModal = true;
                return;
            }
            if (this.paymentMethod === 'qris') {
                this.qrisFailed = false;
                this.showQrisModal = true;
                return;
            }
            this.showConfirmModal = true;
        },

        edcPaid() {
            this.showEdcModal = false;
            this.edcFailed = false;
            this.showConfirmModal = true;
        },

        edcFail() {
            this.edcFailed = true;
        },

        bankTransferPaid() {
            this.showBankModal = false;
            this.amountPaid = this.total;
            this.$nextTick(() => {
                let input = this.$refs.paymentInput;
                if (input) input.value = this.total > 0 ? this.formatNumber(this.total) : '';
            });
            this.showConfirmModal = true;
        },

        submitTransaction(shouldPrint = false) {
            if (this.cart.length === 0) return;
            if (this.paymentMethod === 'cash' && this.amountPaid < this.total) return;
            this.printReceipt = shouldPrint;
            this.showConfirmModal = false;
            this.$refs.form.target = shouldPrint ? '_blank' : '_self';
            localStorage.removeItem('pos_cart');
            localStorage.removeItem('pos_state');
            this.$nextTick(() => {
                this.$refs.form.submit();
                if (shouldPrint) {
                    this.resetCart();
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!', text: 'Transaksi berhasil disimpan.',
                        confirmButtonColor: '#764706', confirmButtonText: 'OK', showConfirmButton: false, timer: 2000, timerProgressBar: true,
                        customClass: { popup: 'swal-card-custom' },
                        showClass: { popup: 'animate__animated animate__zoomIn' },
                        hideClass: { popup: 'animate__animated animate__zoomOut' }
                    });
                }
            });
        }
    }
}
</script>

@endsection

@push('scripts')
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}',
                confirmButtonColor: '#764706', confirmButtonText: 'OK', showConfirmButton: false, timer: 2000, timerProgressBar: true,
                customClass: { popup: 'swal-card-custom' },
                showClass: { popup: 'animate__animated animate__zoomIn' },
                hideClass: { popup: 'animate__animated animate__zoomOut' }
            });
        });
    </script>
@endif
@endpush
