@extends('layouts.app-nosidebar')

@php
    $isOwner = Auth::user()->hasRole('owner');
    $histPosRoute = $isOwner ? 'owner.transactions.create' : 'kasir.transactions.create';
    $histFilterRoute = $isOwner ? 'owner.transactions.history-filter' : 'kasir.transactions.history-filter';
@endphp

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17]">Riwayat Transaksi</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Riwayat transaksi yang dilakukan oleh anda.</p>
        </div>
        <a href="{{ route($histPosRoute) }}"
            class="inline-flex items-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Transaksi Baru
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5 truncate"><span id="stat-total-revenue" x-data="hoCountUp({{ (int) $totalRevenue }}, { prefix: 'Rp ', delay: 150 })" x-text="shown">Rp 0</span></h3>
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
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Transaksi Dibatalkan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span id="stat-cancelled-transactions" x-data="hoCountUp({{ $cancelledTransactions }}, { delay: 600 })" x-text="shown">0</span></h3>
            </div>
        </div>
    </div>

    {{-- Daftar Transaksi + Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0] flex items-center justify-between gap-4 flex-wrap">
            <h3 class="text-base font-black text-[#2b1f17]">Daftar Transaksi Saya</h3>
            <button type="button" onclick="resetHistoryFilter()"
                class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#764706] px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#ebe3db] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                Reset
            </button>
        </div>
        <div class="p-6 border-b border-[#f0e8e0]">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Cari</label>
                    <div class="relative">
                        <input type="text" id="filter-search" value="{{ request('search') }}" placeholder="Kode, ID, status, metode..."
                            class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition" autocomplete="off">
                        <button type="button" id="clear-search-btn" style="display:none" onclick="clearHistorySearch()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b0a090] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
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
                <div>
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Status</label>
                    <select id="filter-status"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#f0e8e0] text-sm text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="history-table">
            @include('cashier.transactions._history_table', ['transactions' => $transactions])
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
                window.addEventListener('history:reset-date', () => {
                    this.value = '';
                });
            }
        };
    }

    let filterTimer;
    const tableContainer = document.getElementById('history-table');
    const filterUrl = "{{ route($histFilterRoute) }}";

    function collectFilterParams() {
        const search = document.getElementById('filter-search').value.trim();
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;
        const status = document.getElementById('filter-status').value;
        const params = new URLSearchParams();
        if (search) params.set('search', search);
        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo) params.set('date_to', dateTo);
        if (status) params.set('status', status);
        return params;
    }

    async function fetchHistory() {
        const params = collectFilterParams();
        const res = await fetch(`${filterUrl}?${params.toString()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) return;
        const data = await res.json();
        tableContainer.innerHTML = data.html;
        if (data.stats) {
            document.getElementById('stat-total-revenue').textContent = 'Rp ' + Number(data.stats.totalRevenue || 0).toLocaleString('id-ID');
            document.getElementById('stat-completed-transactions').textContent = Number(data.stats.completedTransactions || 0).toLocaleString('id-ID');
            document.getElementById('stat-cancelled-transactions').textContent = Number(data.stats.cancelledTransactions || 0).toLocaleString('id-ID');
        }
        bindHistoryEvents();
    }

    function refreshHistory(resetOnly = false) {
        clearTimeout(filterTimer);
        if (resetOnly) {
            ['filter-search', 'filter-date-from', 'filter-date-to', 'filter-status'].forEach(id => {
                document.getElementById(id).value = '';
            });
            fetchHistory();
            return;
        }
        filterTimer = setTimeout(fetchHistory, 350);
    }

    function resetHistoryFilter() {
        clearTimeout(filterTimer);
        ['filter-search', 'filter-date-from', 'filter-date-to', 'filter-status'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('clear-search-btn').style.display = 'none';
        window.dispatchEvent(new Event('history:reset-date'));
        fetchHistory();
    }

    function clearHistorySearch() {
        document.getElementById('filter-search').value = '';
        document.getElementById('clear-search-btn').style.display = 'none';
        refreshHistory();
    }

    function bindHistoryEvents() {
        document.getElementById('filter-search').addEventListener('input', () => {
            document.getElementById('clear-search-btn').style.display = document.getElementById('filter-search').value ? 'flex' : 'none';
            refreshHistory();
        });
        document.getElementById('clear-search-btn').style.display = document.getElementById('filter-search').value ? 'flex' : 'none';
        ['filter-date-from', 'filter-date-to', 'filter-status'].forEach(id => {
            document.getElementById(id).addEventListener('change', () => refreshHistory());
        });
    }

    document.addEventListener('DOMContentLoaded', bindHistoryEvents);
</script>
@endsection
