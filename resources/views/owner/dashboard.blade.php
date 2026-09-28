@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php($barColors = ['#764706', '#ADA68A', '#FFF0DA', '#c4a88a'])
<style>
    @keyframes ho-bar-in {
        from {
            width: 0;
        }
        to {
            width: var(--w, 100%);
        }
    }

    .ho-bar-in {
        animation: ho-bar-in 1.1s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: var(--bar-delay, 0s);
    }

    @media (prefers-reduced-motion: reduce) {
        .ho-bar-in {
            animation: none;
        }
    }
</style>
<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17] mt-1">Selamat Datang, {{ Auth::user()->first_name }}! ☕</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Berikut ringkasan bisnis Hideout Coffee hari ini.</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#764706] mr-[10px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75a2.25 2.25 0 0 1 2.25-2.25h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5 truncate">
                    <span x-data="hoCountUp({{ $todayRevenue }}, { prefix: 'Rp ', delay: 150 })" x-text="shown">Rp 0</span>
                </h3>
                <p class="text-[11px] font-bold mt-1 {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% dari kemarin
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Transaksi Hari Ini</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5 truncate">
                    <span x-data="hoCountUp({{ $todayTransactions }}, { delay: 300 })" x-text="shown">0</span>
                </h3>
                <p class="text-[11px] font-bold mt-1 {{ $ordersGrowth >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $ordersGrowth >= 0 ? '+' : '' }}{{ $ordersGrowth }}% dari kemarin
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Menu Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">
                    <span x-data="hoCountUp({{ $menuActive }}, { delay: 450 })" x-text="shown">0</span>
                </h3>
                <p class="text-[11px] font-bold text-[#8b684b] mt-1">{{ $categoryCount }} kategori</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kasir Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">
                    <span x-data="hoCountUp({{ $cashierActive }}, { delay: 600 })" x-text="shown">0</span>
                </h3>
                <p class="text-[11px] font-bold text-green-600 mt-1">Bertugas</p>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-black text-[#2b1f17]">Grafik Pendapatan 7 Hari Terakhir</h3>
            </div>
            <canvas id="chart" height="120" data-labels="{{ json_encode($chartLabels) }}" data-values="{{ json_encode($chartValues) }}"></canvas>
        </div>

        <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex flex-col justify-center">
            <h3 class="text-base font-black text-[#2b1f17] mb-6">Produk Terlaris</h3>
            <div class="space-y-4 px-1.5">
                @forelse ($topMenus as $index => $topMenu)
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $barColors[$index % count($barColors)] }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-[#2b1f17]">{{ $topMenu['name'] }}</p>
                            <div class="w-full h-2 bg-[#f0e8e0] rounded-full mt-1.5">
                                <div class="h-full rounded-full ho-bar-in"
                                    style="background-color: {{ $barColors[$index % count($barColors)] }}; --w: {{ $topMenu['percent'] }}%; --bar-delay: {{ 0.6 + $index * 0.15 }}s"></div>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-sm font-black text-[#2b1f17]">
                                <span x-data="hoCountUp({{ $topMenu['qty'] }}, { delay: {{ 700 + $index * 150 }} })" x-text="shown">0</span>
                            </span>
                            <p class="text-[10px] font-bold text-[#8b684b]">terjual</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm font-bold text-[#8b684b] text-center py-8">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#f0e8e0]">
            <h3 class="text-base font-black text-[#2b1f17]">Transaksi Terbaru</h3>
            <a href="{{ route('owner.transactions.index') }}"
                class="inline-flex items-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#8b684b] text-xs font-bold uppercase tracking-wider bg-[#faf6f2]">
                        <th class="px-6 py-4 text-center w-12">No</th>
                        <th class="px-6 py-4">Kode Transaksi</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kasir / Owner</th>
                        <th class="px-6 py-4 text-center">Metode</th>
                        <th class="px-6 py-4 text-center">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e8e0]">
                    @forelse ($recentTransactions as $transaction)
                        <tr class="hover:bg-[#faf6f2] transition">
                            <td class="px-6 py-4 text-center text-[#8b684b] w-12">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-[#2b1f17]">{{ $transaction->code }}</td>
                            <td class="px-6 py-4 text-[#5f4a3b]">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @if ($transaction->user)
                                    <div>
                                        <span class="text-[#2b1f17] font-bold">{{ $transaction->user->first_name ?: $transaction->user->username }}</span>
                                        <p class="text-xs font-bold mt-0.5 {{ $transaction->user->hasRole('owner') ? 'text-purple-700' : 'text-blue-700' }}">
                                            {{ $transaction->user->hasRole('owner') ? 'Owner' : 'Kasir' }}
                                        </p>
                                    </div>
                                @else
                                    <span class="text-[#b0a090]">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[10px] font-bold text-[#5f4a3b] bg-[#f5f0eb] px-2.5 py-1 rounded-full uppercase">{{ $transaction->payment_method }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-black text-[#2b1f17]">Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
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
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                    <p class="mt-3 text-sm font-bold text-[#8b684b]">Belum ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection