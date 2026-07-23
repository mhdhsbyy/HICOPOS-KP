@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $transaction->id)

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.transactions.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#f5f0eb] text-[#8b684b] hover:bg-[#ebe3db] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </a>
                <div>
                    <h2 class="text-xl font-black text-[#2b1f17]">Transaksi #{{ $transaction->id }}</h2>
                    <p class="text-sm text-[#5f4a3b] mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
        <div>
            @if ($transaction->status === 'completed')
                <span class="inline-flex items-center gap-1.5 text-sm font-bold text-green-700 bg-green-100 px-4 py-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    Selesai
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-sm font-bold text-red-700 bg-red-100 px-4 py-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    Dibatalkan
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Detail Transaksi --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Items --}}
            <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
                <div class="px-6 py-5 border-b border-[#f0e8e0]">
                    <h3 class="text-base font-black text-[#2b1f17]">Item yang Dibeli</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[#f0e8e0] bg-[#faf7f4]">
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Menu</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-[#8b684b] uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-[#8b684b] uppercase tracking-wider">Diskon</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-[#8b684b] uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#f0e8e0]">
                            @foreach ($transaction->items as $item)
                                <tr class="hover:bg-[#faf7f4] transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-[#FFF0DA] flex items-center justify-center shrink-0">
                                                @if ($item->menu && $item->menu->photo)
                                                    <img src="{{ Storage::url($item->menu->photo) }}" alt="{{ $item->menu->name }}" class="w-full h-full object-cover rounded-lg">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-[#2b1f17]">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                                                @if ($item->menu)
                                                    <p class="text-xs text-[#8b684b]">{{ $item->menu->category->name ?? '' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-[#5f4a3b]">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right text-[#5f4a3b]">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($item->discount > 0)
                                            <span class="text-red-600 font-bold">- Rp {{ number_format($item->discount * $item->quantity, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-[#b0a090]">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-[#2b1f17]">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="space-y-6">
            {{-- Info Kasir --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
                <h3 class="text-base font-black text-[#2b1f17] mb-4">Info Kasir</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Username</p>
                            <p class="text-sm font-bold text-[#2b1f17]">{{ $transaction->user->username }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Email</p>
                            <p class="text-sm font-bold text-[#2b1f17]">{{ $transaction->user->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pembayaran --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
                <h3 class="text-base font-black text-[#2b1f17] mb-4">Ringkasan Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#5f4a3b]">Subtotal</span>
                        <span class="text-sm font-bold text-[#2b1f17]">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>

                    @php
                        $itemDiscountsTotal = $transaction->items->sum(fn($item) => $item->discount * $item->quantity);
                    @endphp

                    @if ($itemDiscountsTotal > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-[#5f4a3b]">Diskon Item</span>
                            <span class="text-sm font-bold text-red-600">- Rp {{ number_format($itemDiscountsTotal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if ($transaction->promo_discount > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-[#5f4a3b] flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                                Diskon Promo
                            </span>
                            <span class="text-sm font-bold text-green-600">- Rp {{ number_format($transaction->promo_discount, 0, ',', '.') }}</span>
                        </div>
                        @if ($transaction->promos->count())
                            <div class="pl-4 space-y-1">
                                @foreach ($transaction->promos as $promo)
                                    <p class="text-xs text-green-600" x-data>- {{ $promo->name }} (Rp {{ number_format($promo->pivot->discount_amount, 0, ',', '.') }})</p>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if ($transaction->discount > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-[#5f4a3b]">Diskon Manual</span>
                            <span class="text-sm font-bold text-red-600">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="border-t border-[#f0e8e0] pt-3 flex justify-between items-center">
                        <span class="text-sm font-bold text-[#2b1f17]">Total</span>
                        <span class="text-lg font-black text-[#764706]">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-[#f0e8e0] pt-3 flex justify-between items-center">
                        <span class="text-sm text-[#5f4a3b]">Bayar</span>
                        <span class="text-sm font-bold text-[#2b1f17]">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    @if ($transaction->change > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-[#5f4a3b]">Kembalian</span>
                            <span class="text-sm font-bold text-green-600">Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="border-t border-[#f0e8e0] pt-3 flex justify-between items-center">
                        <span class="text-sm text-[#5f4a3b]">Metode Bayar</span>
                        <span class="text-sm font-bold text-[#2b1f17] capitalize">{{ $transaction->payment_method }}</span>
                    </div>
                </div>
            </div>

            @if ($transaction->notes)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
                    <h3 class="text-base font-black text-[#2b1f17] mb-2">Catatan</h3>
                    <p class="text-sm text-[#5f4a3b]">{{ $transaction->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
