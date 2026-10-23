@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17]">Data Transaksi</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Riwayat seluruh transaksi Hideout Coffee.</p>
        </div>
    </div>

    {{-- Grafik Pendapatan --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
        <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
            <h3 class="text-base font-black text-[#2b1f17]">Grafik Pendapatan</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <select id="filter-revenue-period"
                    class="pl-4 pr-8 py-2 rounded-xl text-xs font-bold bg-[#f5f0eb] text-[#764706] border border-[#f0e8e0] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer">
                    <option value="today">7 Hari Terakhir</option>
                    <option value="week" selected>Minggu ini</option>
                    <option value="month">Bulan ini</option>
                    <option value="year">Tahun ini</option>
                    <option value="custom">Custom / pilih tanggal</option>
                </select>
                <div id="filter-revenue-range" class="flex items-center flex-wrap" style="display:none">
                    <div class="flex items-center rounded-xl border border-[#f0e8e0] bg-white shadow-sm">
                        <div class="relative" x-data="hoDatePicker('filter-chart-date-from')" @click.outside="open = false" @keydown.enter.prevent="open && commitCandidate()">
                    <div class="flex items-center gap-2">
                    <input type="hidden" id="filter-chart-date-from" value="">
                    <button type="button" @click="toggle()"
                        class="flex items-center gap-2 pl-3 pr-2 py-2.5 text-xs font-bold text-[#2b1f17] hover:bg-[#faf7f4] transition cursor-pointer border-0 bg-transparent focus:outline-none">
                        <span class="text-[9px] font-bold uppercase tracking-wide text-[#8b684b] shrink-0">Dari</span>
                        <span class="truncate min-w-[6rem] text-left" x-text="display"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b684b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    </div>
                    <div x-show="open" x-cloak x-transition
                        class="absolute left-0 z-30 mt-2 w-72 rounded-2xl bg-white shadow-xl border border-[#f0e8e0] p-4">
                        <div class="flex items-center justify-between mb-3">
                            <button type="button" @click="prevMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                            <span class="text-sm font-black text-[#2b1f17]" x-text="viewLabel"></span>
                            <button type="button" @click="nextMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <template x-for="w in weekdays" :key="w">
                                <span class="text-[10px] font-bold text-[#8b684b] uppercase text-center" x-text="w"></span>
                            </template>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="(cell, i) in dayCells" :key="i">
                                <button type="button"
                                    class="flex flex-col items-center justify-center rounded-lg text-xs font-bold leading-tight py-1 transition cursor-pointer"
                                    :class="cell === null
                                        ? 'invisible'
                                        : (isSelected(cell)
                                            ? 'bg-[#764706] text-white'
                                            : (isToday(cell) ? 'text-[#764706] hover:bg-[#FFF0DA]' : 'text-[#2b1f17] hover:bg-[#FFF0DA]'))"
                                    @click="cell !== null && select(cell)">
                                    <span x-text="cell"></span>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 pt-3 border-t border-[#f0e8e0] flex items-center justify-between">
                            <span class="text-[10px] font-bold text-[#b0a090]" x-text="value ? 'Dipilih: ' + display : 'Belum dipilih'"></span>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="clearDate()"
                                    class="text-xs font-bold text-red-500 hover:underline transition cursor-pointer">
                                    Hapus
                                </button>
                                <button type="button" @click="selectToday()"
                                    class="text-xs font-bold text-[#764706] hover:underline transition cursor-pointer">
                                    Hari Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                    <span class="w-px h-8 bg-[#f0e8e0] shrink-0"></span>
                    <div class="relative" x-data="hoDatePicker('filter-chart-date-to')" @click.outside="open = false" @keydown.enter.prevent="open && commitCandidate()">
                    <div class="flex items-center gap-2">
                    <input type="hidden" id="filter-chart-date-to" value="">
                    <button type="button" @click="toggle()"
                        class="flex items-center gap-2 pl-3 pr-2 py-2.5 text-xs font-bold text-[#2b1f17] hover:bg-[#faf7f4] transition cursor-pointer border-0 bg-transparent focus:outline-none">
                        <span class="text-[9px] font-bold uppercase tracking-wide text-[#8b684b] shrink-0">Sampai</span>
                        <span class="truncate min-w-[6rem] text-left" x-text="display"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8b684b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    </div>
                    <div x-show="open" x-cloak x-transition
                        class="absolute left-0 z-30 mt-2 w-72 rounded-2xl bg-white shadow-xl border border-[#f0e8e0] p-4">
                        <div class="flex items-center justify-between mb-3">
                            <button type="button" @click="prevMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                            <span class="text-sm font-black text-[#2b1f17]" x-text="viewLabel"></span>
                            <button type="button" @click="nextMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <template x-for="w in weekdays" :key="w">
                                <span class="text-[10px] font-bold text-[#8b684b] uppercase text-center" x-text="w"></span>
                            </template>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="(cell, i) in dayCells" :key="i">
                                <button type="button"
                                    class="flex flex-col items-center justify-center rounded-lg text-xs font-bold leading-tight py-1 transition cursor-pointer"
                                    :class="cell === null
                                        ? 'invisible'
                                        : (isSelected(cell)
                                            ? 'bg-[#764706] text-white'
                                            : (isToday(cell) ? 'text-[#764706] hover:bg-[#FFF0DA]' : 'text-[#2b1f17] hover:bg-[#FFF0DA]'))"
                                    @click="cell !== null && select(cell)">
                                    <span x-text="cell"></span>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 pt-3 border-t border-[#f0e8e0] flex items-center justify-between">
                            <span class="text-[10px] font-bold text-[#b0a090]" x-text="value ? 'Dipilih: ' + display : 'Belum dipilih'"></span>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="clearDate()"
                                    class="text-xs font-bold text-red-500 hover:underline transition cursor-pointer">
                                    Hapus
                                </button>
                                <button type="button" @click="selectToday()"
                                    class="text-xs font-bold text-[#764706] hover:underline transition cursor-pointer">
                                    Hari Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                <button type="button" onclick="resetChartFilter()"
                    class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#764706] px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#ebe3db] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    Reset
                </button>
            </div>
        </div>

        <canvas id="chart" height="120" data-labels="{{ json_encode($chartLabels) }}" data-values="{{ json_encode($chartValues) }}"></canvas>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5 truncate"><span id="stat-total-revenue" x-data="hoCountUp({{ (int) $totalRevenue }}, { prefix: 'Rp ', delay: 300 })" x-text="shown">Rp 0</span></h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-light flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span id="stat-total-transactions" x-data="hoCountUp({{ $totalTransactions }}, { delay: 150 })" x-text="shown">0</span></h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Transaksi Selesai</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span id="stat-completed-transactions" x-data="hoCountUp({{ $completedTransactions }}, { delay: 450 })" x-text="shown">0</span></h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Transaksi Dibatalkan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span id="stat-cancelled-transactions" x-data="hoCountUp({{ $cancelledTransactions }}, { delay: 600 })" x-text="shown">0</span></h3>
            </div>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0] flex items-center justify-between gap-4 flex-wrap">
            <h3 class="text-base font-black text-[#2b1f17]">Daftar Transaksi</h3>
            <div class="flex items-center gap-3">
                <button type="button" onclick="resetDataFilter()"
                    class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#764706] px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#ebe3db] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    Reset
                </button>
                <a href="#" onclick="event.preventDefault(); printTransactionData()"
                    class="inline-flex items-center gap-2 bg-[#764706] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#5f3905] transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" /></svg>
                    Cetak
                </a>
            </div>
        </div>
        {{-- Filter data transaksi --}}
        <div class="p-6 border-b border-[#f0e8e0] space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Kasir / Owner</label>
                    <select id="filter-cashier"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] bg-white text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer">
                        <option value="">Semua Kasir</option>
                        @foreach ($cashiers as $cashier)
                            <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                                {{ trim($cashier->first_name.' '.$cashier->last_name) }} ({{ ucfirst($cashier->getRoleNames()->first() ?? 'kasir') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Status</label>
                    <select id="filter-status"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] bg-white text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Metode Bayar</label>
                    <select id="filter-payment"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] bg-white text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer">
                        <option value="">Semua Metode</option>
                        @foreach (['cash' => 'Cash', 'debit' => 'Debit', 'kredit' => 'Kredit', 'transfer' => 'Transfer', 'qris' => 'QRIS'] as $method => $label)
                            <option value="{{ $method }}" {{ request('payment_method') === $method ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            <div>
                <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <div class="relative" x-data="hoDatePicker('filter-date-from')" @click.outside="open = false" @keydown.enter.prevent="open && commitCandidate()">
                    <input type="hidden" id="filter-date-from" value="{{ request('date_from') }}">
                    <button type="button" @click="toggle()"
                        class="w-full flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl border border-[#f0e8e0] bg-white text-sm text-[#2b1f17] hover:border-[#764706] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 transition cursor-pointer">
                        <span class="truncate" x-text="display"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#b0a090] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition
                        class="absolute left-0 z-30 mt-2 w-72 rounded-2xl bg-white shadow-xl border border-[#f0e8e0] p-4">
                        <div class="flex items-center justify-between mb-3">
                            <button type="button" @click="prevMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                            <span class="text-sm font-black text-[#2b1f17]" x-text="viewLabel"></span>
                            <button type="button" @click="nextMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <template x-for="w in weekdays" :key="w">
                                <span class="text-[10px] font-bold text-[#8b684b] uppercase text-center" x-text="w"></span>
                            </template>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="(cell, i) in dayCells" :key="i">
                                <button type="button"
                                    class="flex flex-col items-center justify-center rounded-lg text-xs font-bold leading-tight py-1 transition cursor-pointer"
                                    :class="cell === null
                                        ? 'invisible'
                                        : (isSelected(cell)
                                            ? 'bg-[#764706] text-white'
                                            : (isToday(cell) ? 'text-[#764706] hover:bg-[#FFF0DA]' : 'text-[#2b1f17] hover:bg-[#FFF0DA]'))"
                                    @click="cell !== null && select(cell)">
                                    <span x-text="cell"></span>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 pt-3 border-t border-[#f0e8e0] flex items-center justify-between">
                            <span class="text-[10px] font-bold text-[#b0a090]" x-text="value ? 'Dipilih: ' + display : 'Belum dipilih'"></span>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="clearDate()"
                                    class="text-xs font-bold text-red-500 hover:underline transition cursor-pointer">
                                    Hapus
                                </button>
                                <button type="button" @click="selectToday()"
                                    class="text-xs font-bold text-[#764706] hover:underline transition cursor-pointer">
                                    Hari Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <div class="relative" x-data="hoDatePicker('filter-date-to')" @click.outside="open = false" @keydown.enter.prevent="open && commitCandidate()">
                    <input type="hidden" id="filter-date-to" value="{{ request('date_to') }}">
                    <button type="button" @click="toggle()"
                        class="w-full flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl border border-[#f0e8e0] bg-white text-sm text-[#2b1f17] hover:border-[#764706] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 transition cursor-pointer">
                        <span class="truncate" x-text="display"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#b0a090] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition
                        class="absolute left-0 z-30 mt-2 w-72 rounded-2xl bg-white shadow-xl border border-[#f0e8e0] p-4">
                        <div class="flex items-center justify-between mb-3">
                            <button type="button" @click="prevMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                            </button>
                            <span class="text-sm font-black text-[#2b1f17]" x-text="viewLabel"></span>
                            <button type="button" @click="nextMonth()"
                                class="w-7 h-7 rounded-lg text-[#8b684b] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <template x-for="w in weekdays" :key="w">
                                <span class="text-[10px] font-bold text-[#8b684b] uppercase text-center" x-text="w"></span>
                            </template>
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="(cell, i) in dayCells" :key="i">
                                <button type="button"
                                    class="flex flex-col items-center justify-center rounded-lg text-xs font-bold leading-tight py-1 transition cursor-pointer"
                                    :class="cell === null
                                        ? 'invisible'
                                        : (isSelected(cell)
                                            ? 'bg-[#764706] text-white'
                                            : (isToday(cell) ? 'text-[#764706] hover:bg-[#FFF0DA]' : 'text-[#2b1f17] hover:bg-[#FFF0DA]'))"
                                    @click="cell !== null && select(cell)">
                                    <span x-text="cell"></span>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 pt-3 border-t border-[#f0e8e0] flex items-center justify-between">
                            <span class="text-[10px] font-bold text-[#b0a090]" x-text="value ? 'Dipilih: ' + display : 'Belum dipilih'"></span>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="clearDate()"
                                    class="text-xs font-bold text-red-500 hover:underline transition cursor-pointer">
                                    Hapus
                                </button>
                                <button type="button" @click="selectToday()"
                                    class="text-xs font-bold text-[#764706] hover:underline transition cursor-pointer">
                                    Hari Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="flex items-end gap-3">
                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Cari</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#b0a090] absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                        <input type="text" id="filter-search" value="{{ request('search') }}" placeholder="Cari kode transaksi, kasir, status, metode..."
                            class="w-full pl-10 pr-10 py-2 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition" autocomplete="off">
                        <button type="button" id="clear-search-btn" style="display:none" onclick="clearDataSearch()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="data-table">
            @include('owner.transactions._data_table', ['transactions' => $transactions])
        </div>
    </div>
</div>

<script>
    function hoDatePicker(inputId) {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const weekdays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const now = new Date();
        const pad = (n) => String(n).padStart(2, '0');
        const fmt = (dt) => `${dt.getFullYear()}-${pad(dt.getMonth() + 1)}-${pad(dt.getDate())}`;
        const fmtLong = (dt) => dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const parse = (v) => {
            if (!v || !/^\d{4}-\d{2}-\d{2}$/.test(v)) return null;
            const [y, m, d] = v.split('-').map(Number);
            return new Date(y, m - 1, d);
        };
        const initial = parse(document.getElementById(inputId).value) || now;

        return {
            open: false,
            value: document.getElementById(inputId).value,
            candidate: '',
            viewYear: initial.getFullYear(),
            viewMonth: initial.getMonth(),
            months: months,
            weekdays: weekdays,
            today: fmt(now),

            get display() {
                const dt = parse(this.value);
                return dt ? fmtLong(dt) : 'Semua tanggal';
            },
            get viewLabel() {
                return this.months[this.viewMonth] + ' ' + this.viewYear;
            },
            get dayCells() {
                const first = new Date(this.viewYear, this.viewMonth, 1);
                const offset = (first.getDay() + 6) % 7;
                const daysInMonth = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
                const cells = [];
                for (let i = 0; i < offset; i++) cells.push(null);
                for (let d = 1; d <= daysInMonth; d++) cells.push(d);
                while (cells.length % 7 !== 0) cells.push(null);
                return cells;
            },
            isToday(day) {
                return this.today === fmt(new Date(this.viewYear, this.viewMonth, day));
            },
            isSelected(day) {
                return this.candidate === fmt(new Date(this.viewYear, this.viewMonth, day));
            },
            prevMonth() {
                this.viewMonth--;
                if (this.viewMonth < 0) { this.viewMonth = 11; this.viewYear--; }
            },
            nextMonth() {
                this.viewMonth++;
                if (this.viewMonth > 11) { this.viewMonth = 0; this.viewYear++; }
            },
            toggle() {
                if (!this.open) {
                    const t = new Date();
                    this.viewYear = t.getFullYear();
                    this.viewMonth = t.getMonth();
                    this.candidate = fmt(t);
                }
                this.open = !this.open;
            },
            commitCandidate() {
                if (!this.candidate) return;
                this.value = this.candidate;
                this.sync();
                this.open = false;
            },
            select(day) {
                this.candidate = fmt(new Date(this.viewYear, this.viewMonth, day));
                this.value = this.candidate;
                this.sync();
                this.open = false;
            },
            clearDate() {
                this.candidate = '';
                this.value = '';
                this.sync();
            },
            selectToday() {
                this.candidate = this.today;
                this.value = this.today;
                this.sync();
                this.open = false;
            },
            sync() {
                const input = document.getElementById(inputId);
                input.value = this.value;
                input.dispatchEvent(new Event('change'));
            },
            init() {
                window.addEventListener('data:reset-date:' + inputId, () => {
                    this.value = '';
                });
            }
        };
    }

    let dataFilterTimer;
    const dataTableContainer = document.getElementById('data-table');
    const dataFilterUrl = "{{ route('owner.transactions.index-filter') }}";

    function collectChartFilterParams() {
        const dateFrom = document.getElementById('filter-chart-date-from').value;
        const dateTo = document.getElementById('filter-chart-date-to').value;
        const params = new URLSearchParams();
        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo) params.set('date_to', dateTo);
        return params;
    }

    function collectDataFilterParams() {
        const cashier = document.getElementById('filter-cashier').value;
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;
        const status = document.getElementById('filter-status').value;
        const payment = document.getElementById('filter-payment').value;
        const search = document.getElementById('filter-search').value.trim();
        const params = new URLSearchParams();
        if (cashier) params.set('cashier_id', cashier);
        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo) params.set('date_to', dateTo);
        if (status) params.set('status', status);
        if (payment) params.set('payment_method', payment);
        if (search) params.set('search', search);
        return params;
    }

    async function fetchDataHistory() {
        const params = collectDataFilterParams();
        const res = await fetch(`${dataFilterUrl}?${params.toString()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) return;
        const data = await res.json();
        dataTableContainer.innerHTML = data.html;
        bindDataEvents();
        if (data.stats) {
            updateDataStats(data.stats);
        }
    }

    function updateDataStats(stats) {
        const set = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        };
        set('stat-total-revenue', 'Rp ' + Number(stats.totalRevenue).toLocaleString('id-ID'));
        set('stat-total-transactions', Number(stats.totalTransactions).toLocaleString('id-ID'));
        set('stat-completed-transactions', Number(stats.completedTransactions).toLocaleString('id-ID'));
        set('stat-cancelled-transactions', Number(stats.cancelledTransactions).toLocaleString('id-ID'));
    }

    function refreshData(resetOnly = false) {
        clearTimeout(dataFilterTimer);
        if (resetOnly) {
            ['filter-search', 'filter-cashier', 'filter-status', 'filter-payment'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('filter-date-from').value = '';
            document.getElementById('filter-date-to').value = '';
            fetchDataHistory();
            return;
        }
        dataFilterTimer = setTimeout(() => fetchDataHistory(), 350);
    }

    function resetDataFilter() {
        clearTimeout(dataFilterTimer);
        ['filter-search', 'filter-cashier', 'filter-status', 'filter-payment'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('filter-date-from').value = '';
        document.getElementById('filter-date-to').value = '';
        window.dispatchEvent(new Event('data:reset-date:filter-date-from'));
        window.dispatchEvent(new Event('data:reset-date:filter-date-to'));
        document.getElementById('clear-search-btn').style.display = 'none';
        fetchDataHistory();
    }

    function clearDataSearch() {
        document.getElementById('filter-search').value = '';
        document.getElementById('clear-search-btn').style.display = 'none';
        refreshData();
    }

    function printTransactionData() {
        const params = collectDataFilterParams();
        window.open(`{{ route('owner.transactions.print-index') }}?${params.toString()}`, '_blank');
    }

    function bindDataEvents() {
        document.getElementById('filter-search').addEventListener('input', () => {
            document.getElementById('clear-search-btn').style.display = document.getElementById('filter-search').value ? 'flex' : 'none';
            refreshData();
        });
        document.getElementById('clear-search-btn').style.display = document.getElementById('filter-search').value ? 'flex' : 'none';
        ['filter-cashier', 'filter-status', 'filter-payment'].forEach(id => {
            document.getElementById(id).addEventListener('change', () => refreshData());
        });
        document.getElementById('filter-date-from').addEventListener('change', () => refreshData());
        document.getElementById('filter-date-to').addEventListener('change', () => refreshData());
    }

    document.addEventListener('DOMContentLoaded', () => {
        bindDataEvents();
        const periodSelect = document.getElementById('filter-revenue-period');
        const rangeContainer = document.getElementById('filter-revenue-range');
        if (periodSelect && rangeContainer) {
            rangeContainer.style.display = periodSelect.value === 'custom' ? 'flex' : 'none';
            periodSelect.addEventListener('change', () => {
                rangeContainer.style.display = periodSelect.value === 'custom' ? 'flex' : 'none';
                if (periodSelect.value !== 'custom') {
                    document.getElementById('filter-chart-date-from').value = '';
                    document.getElementById('filter-chart-date-to').value = '';
                    window.dispatchEvent(new Event('data:reset-date:filter-chart-date-from'));
                    window.dispatchEvent(new Event('data:reset-date:filter-chart-date-to'));
                }
                fetchRevenueChart();
            });
        }
        ['filter-chart-date-from', 'filter-chart-date-to'].forEach(id => {
            document.getElementById(id).addEventListener('change', () => fetchRevenueChart());
        });
    });

    function resetChartFilter() {
        document.getElementById('filter-chart-date-from').value = '';
        document.getElementById('filter-chart-date-to').value = '';
        window.dispatchEvent(new Event('data:reset-date:filter-chart-date-from'));
        window.dispatchEvent(new Event('data:reset-date:filter-chart-date-to'));
        const periodSelect = document.getElementById('filter-revenue-period');
        if (periodSelect) {
            periodSelect.value = 'week';
        }
        const rangeContainer = document.getElementById('filter-revenue-range');
        if (rangeContainer) {
            rangeContainer.style.display = 'none';
        }
        fetchRevenueChart();
    }

    const revenueChartUrl = "{{ route('owner.transactions.chart-filter') }}";

    async function fetchRevenueChart() {
        const params = collectChartFilterParams();
        const periodSelect = document.getElementById('filter-revenue-period');
        params.set('period', periodSelect ? periodSelect.value : 'week');
        const res = await fetch(`${revenueChartUrl}?${params.toString()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) return;
        const data = await res.json();
        const chart = Chart.getChart('chart');
        if (chart) {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.values;
            chart.update();
        }
    }
</script>
@endsection

@push('scripts')
@if (session('success'))
    <style>
        .swal-card-custom { border-radius: 1rem !important; border: 1px solid #f0e8e0 !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important; padding: 1.5rem !important; }
        .swal-card-custom .swal2-icon { margin: 0 auto 0.5rem !important; }
        .swal-card-custom .swal2-title { color: #2b1f17 !important; font-weight: 900 !important; font-size: 1.25rem !important; }
        .swal-card-custom .swal2-html-container { color: #5f4a3b !important; font-size: 0.875rem !important; margin: 0.5rem 0 0 !important; }
    </style>
    <script>
        Swal.fire({
            icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}',
            confirmButtonColor: '#764706', confirmButtonText: 'OK', showConfirmButton: false, timer: 2000, timerProgressBar: true,
            customClass: { popup: 'swal-card-custom' },
            showClass: { popup: 'animate__animated animate__zoomIn' },
            hideClass: { popup: 'animate__animated animate__zoomOut' }
        });
    </script>
@endif
@endpush