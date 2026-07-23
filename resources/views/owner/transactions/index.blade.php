@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17]">Data Transaksi</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Riwayat seluruh transaksi Hideout Coffee.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">{{ $totalTransactions }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Transaksi Hari Ini</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">{{ $todayTransactions }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0]">
            <h3 class="text-base font-black text-[#2b1f17]">Filter & Pencarian</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('owner.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-bold text-[#8b684b] uppercase tracking-wider mb-1.5">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ID, kasir, status..."
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
                    <a href="{{ route('owner.transactions.index') }}"
                        class="inline-flex items-center gap-2 bg-[#f5f0eb] text-[#764706] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#ebe3db] transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0]">
            <h3 class="text-base font-black text-[#2b1f17]">Daftar Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#f0e8e0] bg-[#faf7f4]">
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kasir</th>
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
                            <td class="px-6 py-4 text-[#5f4a3b]">{{ $transaction->user->username }}</td>
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                    <p class="mt-3 text-sm font-bold text-[#8b684b]">Belum ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($transactions->hasPages())
            <div class="px-6 py-4 border-t border-[#f0e8e0]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection