@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="flex gap-6 h-full" x-data="posApp()">

    {{-- Menu Grid --}}
    <div class="flex-1 flex flex-col" style="height: calc(100vh - 2rem);">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#f0e8e0] shrink-0 space-y-3">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b0a090]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" x-model="searchQuery" @input.debounce.500ms="posSearchQuery = searchQuery; reloadMenus()" placeholder="Cari menu..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" @click="filterCategory(null)"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer"
                    :class="selectedCategory === null ? 'bg-[#764706] text-white' : 'bg-[#f0e8e0] text-[#764706] hover:bg-[#e5d5c0]'">
                    Semua
                </button>
                @foreach ($categories as $cat)
                    <button type="button" @click="filterCategory({{ $cat->id }})"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer"
                        :class="selectedCategory === {{ $cat->id }} ? 'bg-[#764706] text-white' : 'bg-[#f0e8e0] text-[#764706] hover:bg-[#e5d5c0]'">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <div id="menu-scroll" class="flex-1 overflow-y-auto mt-4 pr-4 pb-4">
            <div id="menu-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($menus as $menu)
                    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden cursor-pointer hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                        @click="addItem({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->photo ? Storage::url($menu->photo) : '' }}', '{{ $menu->category->name ?? '' }}', {{ $menu->category_id }})">
                        <div class="w-full h-28 bg-[#FFF0DA] flex items-center justify-center overflow-hidden">
                            @if ($menu->photo)
                                <img src="{{ Storage::url($menu->photo) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover" loading="lazy">
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
        <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] flex flex-col" style="height: calc(100vh - 2rem);">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-[#f0e8e0]">
                <h3 class="text-base font-black text-[#2b1f17]">Keranjang</h3>
            </div>

            {{-- Items --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-3">
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
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1" x-show="item.discount > 0">
                                    <span class="text-xs text-red-500" x-text="'-Rp ' + formatNumber(item.discount * item.quantity)"></span>
                                </div>
                                <p class="text-sm font-black text-[#764706]" x-text="'Rp ' + formatNumber(item.price * item.quantity - item.discount * item.quantity)"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-xs text-[#8b684b] shrink-0">Diskon/item:</label>
                            <input type="text" @input="formatItemDiscount(index, $event)" :value="item.discount ? formatNumber(item.discount) : ''" placeholder="0"
                                class="flex-1 px-2 py-1 rounded-lg border border-[#f0e8e0] text-xs text-[#2b1f17] text-right focus:outline-none focus:ring-1 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        </div>
                    </div>
                </template>
            </div>

            {{-- Footer --}}
            <div class="border-t border-[#f0e8e0] p-5 space-y-4">
                {{-- Subtotal --}}
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-[#5f4a3b]">Subtotal</span>
                        <span class="font-bold text-[#2b1f17]" x-text="'Rp ' + formatNumber(subtotal)"></span>
                    </div>

                    {{-- Diskon Item --}}
                    <div class="flex justify-between text-sm" x-show="itemDiscountsTotal > 0">
                        <span class="text-[#5f4a3b]">Diskon Item</span>
                        <span class="font-bold text-red-600" x-text="'- Rp ' + formatNumber(itemDiscountsTotal)"></span>
                    </div>

                    {{-- Diskon Promo --}}
                    <template x-for="promo in appliedPromos" :key="promo.id">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-[#5f4a3b] flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                                <span x-text="promo.name"></span>
                            </span>
                            <span class="font-bold text-green-600" x-text="'- Rp ' + formatNumber(promo.discount_amount)"></span>
                        </div>
                    </template>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b]">Diskon Manual</span>
                        <input type="text" x-ref="discountInput" @input="formatDiscount($event)" placeholder="0"
                            class="w-28 px-3 py-1.5 rounded-lg border border-[#f0e8e0] text-sm text-[#2b1f17] text-right focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                    </div>

                    <div class="flex justify-between text-base font-black border-t border-[#f0e8e0] pt-2">
                        <span class="text-[#2b1f17]">Total</span>
                        <span class="text-[#764706]" x-text="'Rp ' + formatNumber(total)"></span>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="space-y-2">
                    <label class="text-xs font-bold text-[#5f4a3b]">Metode Pembayaran</label>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'cash' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                            Cash
                        </button>
                        <button type="button" @click="paymentMethod = 'debit'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'debit' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                            Debit
                        </button>
                        <button type="button" @click="paymentMethod = 'card'"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'card' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg>
                            Card
                        </button>
                        <button type="button" @click="showQrisModal = true"
                            class="py-2 rounded-lg text-xs font-bold transition cursor-pointer border"
                            :class="paymentMethod === 'qris' ? 'bg-[#764706] text-white border-[#764706]' : 'bg-white text-[#5f4a3b] border-[#f0e8e0] hover:border-[#764706]'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 19.5h.75v.75h-.75v-.75Z" /></svg>
                            QRIS
                        </button>
                    </div>
                </div>

                {{-- Bayar --}}
                <div class="space-y-2" x-show="paymentMethod !== 'qris'">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-[#5f4a3b]">Bayar</span>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="setPaymentFromTotal()"
                                :disabled="total <= 0"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#FFF0DA] text-[#764706] hover:bg-[#f0e0c0] transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                Uang Pas
                            </button>
                            <input type="text" x-ref="paymentInput" @input="formatPayment($event)" placeholder="0"
                                class="w-36 px-3 py-1.5 rounded-lg border border-[#f0e8e0] text-sm text-[#2b1f17] text-right focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        </div>
                    </div>
                    <div class="flex justify-between text-sm" x-show="change >= 0 && amountPaid > 0">
                        <span class="text-[#5f4a3b]">Kembalian</span>
                        <span class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></span>
                    </div>
                </div>

                {{-- Submit --}}
                <form method="POST" action="{{ route('owner.transactions.store') }}" x-ref="form">
                    @csrf
                    <input type="hidden" name="items" :value="JSON.stringify(cart.map(i => ({menu_id: i.menu_id, quantity: i.quantity, discount: i.discount})))">
                    <input type="hidden" name="amount_paid" :value="paymentMethod === 'qris' ? total : amountPaid">
                    <input type="hidden" name="discount" :value="discount">
                    <input type="hidden" name="payment_method" :value="paymentMethod">
                    <input type="hidden" name="applied_promos" :value="JSON.stringify(appliedPromos.map(p => ({promo_id: p.id, discount_amount: p.discount_amount})))">

                    <button type="button" @click="submitTransaction()"
                        :disabled="cart.length === 0 || (paymentMethod !== 'qris' && amountPaid < total)"
                        class="w-full py-3 rounded-xl text-sm font-bold transition shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="cart.length > 0 && (paymentMethod === 'qris' || amountPaid >= total) ? 'bg-[#764706] text-white hover:bg-[#5a3606]' : 'bg-gray-200 text-gray-500'">
                        Proses Pembayaran
                    </button>
                </form>
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
            class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center space-y-5">

            <h3 class="text-lg font-black text-[#2b1f17]">Pembayaran QRIS</h3>

            <div class="w-52 h-52 mx-auto bg-[#f0e8e0] rounded-xl flex items-center justify-center border-2 border-dashed border-[#d4c8b8]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-[#764706]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 19.5h.75v.75h-.75v-.75Z" />
                </svg>
            </div>

            <div>
                <p class="text-sm text-[#8b684b]">Total Pembayaran</p>
                <p class="text-2xl font-black text-[#764706]" x-text="'Rp ' + formatNumber(total)"></p>
            </div>

            <div class="space-y-2">
                <button type="button" @click="paymentMethod = 'qris'; $refs.form.submit()"
                    class="w-full py-3 rounded-xl text-sm font-bold bg-[#764706] text-white hover:bg-[#5a3606] transition shadow-md cursor-pointer">
                    Sudah Bayar
                </button>
                <button type="button" @click="showQrisModal = false"
                    class="w-full py-2.5 rounded-xl text-sm font-bold text-[#8b684b] hover:bg-[#f0e8e0] transition cursor-pointer">
                    Batal
                </button>
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

        if (data.html) {
            document.getElementById('menu-grid').innerHTML = data.html;
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
    return {
        cart: [],
        searchQuery: '',
        selectedCategory: null,
        paymentMethod: 'cash',
        showQrisModal: false,
        discount: 0,
        amountPaid: 0,
        promos: @json($promos),

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get itemDiscountsTotal() {
            return this.cart.reduce((sum, item) => sum + (item.discount * item.quantity), 0);
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
                let qualifying = cartItems.filter(i => i.menu_id === promo.target_id);
                if (qualifying.length === 0) return 0;

                let totalQty = qualifying.reduce((s, i) => s + i.quantity, 0);
                let totalAmount = qualifying.reduce((s, i) => s + ((i.price - i.discount) * i.quantity), 0);

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
                let totalAmount = qualifying.reduce((s, i) => s + ((i.price - i.discount) * i.quantity), 0);

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
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },

        formatPayment(e) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            this.amountPaid = raw ? parseInt(raw) : 0;
            e.target.value = raw ? this.formatNumber(this.amountPaid) : '';
        },

        formatDiscount(e) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            this.discount = raw ? parseInt(raw) : 0;
            e.target.value = raw ? this.formatNumber(this.discount) : '';
        },

        formatItemDiscount(index, e) {
            let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            this.cart[index].discount = raw ? parseInt(raw) : 0;
            e.target.value = raw ? this.formatNumber(this.cart[index].discount) : '';
        },

        setPaymentFromTotal() {
            this.amountPaid = this.total;
            this.$nextTick(() => {
                let input = this.$refs.paymentInput;
                if (input) input.value = this.total > 0 ? this.formatNumber(this.total) : '';
            });
        },

        submitTransaction() {
            if (this.cart.length === 0) return;
            if (this.paymentMethod !== 'qris' && this.amountPaid < this.total) return;
            this.$refs.form.submit();
        }
    }
}
</script>
@endsection
