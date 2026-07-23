@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
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
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#f0e8e0] shrink-0">
                    <input type="text" x-model="searchQuery" placeholder="Cari menu..."
                        class="w-full px-4 py-3 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                </div>

                <div id="menu-scroll" class="flex-1 overflow-y-auto mt-4 pr-4 pb-4">
                    <div id="menu-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($menus as $menu)
                            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden cursor-pointer hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                x-show="searchQuery === '' || '{{ strtolower($menu->name) }}'.includes(searchQuery.toLowerCase())"
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
                <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] flex flex-col" style="height: calc(100vh - 8rem);">

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

                        {{-- Bayar --}}
                        <div class="space-y-2">
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
                            <div class="flex justify-between text-sm" x-show="change >= 0">
                                <span class="text-[#5f4a3b]">Kembalian</span>
                                <span class="font-bold text-green-600" x-text="'Rp ' + formatNumber(change)"></span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <form method="POST" action="{{ route('owner.transactions.store') }}" x-ref="form">
                            @csrf
                            <input type="hidden" name="items" :value="JSON.stringify(cart.map(i => ({menu_id: i.menu_id, quantity: i.quantity, discount: i.discount})))">
                            <input type="hidden" name="amount_paid" :value="amountPaid">
                            <input type="hidden" name="discount" :value="discount">
                            <input type="hidden" name="applied_promos" :value="JSON.stringify(appliedPromos.map(p => ({promo_id: p.id, discount_amount: p.discount_amount})))">

                            <button type="submit" @click="submitTransaction"
                                :disabled="cart.length === 0 || amountPaid < total"
                                class="w-full py-3 rounded-xl text-sm font-bold transition shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="cart.length > 0 && amountPaid >= total ? 'bg-[#764706] text-white hover:bg-[#5a3606]' : 'bg-gray-200 text-gray-500'">
                                Proses Pembayaran
                            </button>
                        </form>
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
                        <h3 class="text-xl font-black text-[#2b1f17]">{{ $totalTransactions }}</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan</p>
                        <h3 class="text-xl font-black text-[#2b1f17]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A1.5 1.5 0 0 1 18.75 20.25H5.25A1.5 1.5 0 0 1 3.75 18.75v-12A1.5 1.5 0 0 1 5.25 5.25Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Hari Ini</p>
                        <h3 class="text-xl font-black text-[#2b1f17]">{{ $todayTransactions }}</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#f0e8e0] flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan Hari Ini</p>
                        <h3 class="text-xl font-black text-[#2b1f17]">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f0e8e0]">
                            @forelse ($transactions as $transaction)
                                <tr class="hover:bg-[#faf7f4] transition">
                                    <td class="px-6 py-4 font-bold text-[#2b1f17]">#{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 text-[#5f4a3b]">{{ $transaction->created_at->format('d M Y H:i') }}</td>
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
                                        <a href="{{ route('owner.transactions.show', $transaction) }}"
                                            class="inline-flex items-center gap-1.5 bg-[#f5f0eb] text-[#8b684b] px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#ebe3db] transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
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
                        {{ $transactions->appends($queryParams)->links() }}
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
    return {
        cart: [],
        searchQuery: '',
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
            if (this.cart.length === 0 || this.amountPaid < this.total) return;
            this.$refs.form.submit();
        }
    }
}
</script>
@endsection
