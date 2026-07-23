@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17] mt-1">Selamat Datang, {{ Auth::user()->first_name }}!</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Berikut ringkasan bisnis Hideout Coffee hari ini.</p>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75a2.25 2.25 0 0 1 2.25-2.25h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pendapatan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5 truncate">Rp 2.450.000</h3>
                <p class="text-[11px] font-bold text-green-600 mt-1">+12%</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Pesanan</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">48</h3>
                <p class="text-[11px] font-bold text-green-600 mt-1">+8%</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Menu Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">24</h3>
                <p class="text-[11px] font-bold text-[#8b684b] mt-1">6 kategori</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kasir Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5">3</h3>
                <p class="text-[11px] font-bold text-green-600 mt-1">Bertugas</p>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
        <div class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-black text-[#2b1f17]">Grafik Penjualan 7 Hari</h3>
                <select class="text-xs font-bold text-[#8b684b] bg-transparent border border-[#e2d3c2] rounded-xl px-3 py-2 focus:ring-0 focus:border-[#764706]">
                    <option>Minggu Ini</option>
                    <option>Bulan Ini</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <canvas id="chart" height="120"></canvas>
        </div>

        <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
            <h3 class="text-base font-black text-[#2b1f17] mb-6">Produp Terlaris</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-[#764706] shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-[#2b1f17]">Es Kopi Susu</p>
                        <div class="w-full h-2 bg-[#f0e8e0] rounded-full mt-1.5">
                            <div class="h-full bg-[#764706] rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-black text-[#2b1f17] shrink-0">85</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-[#ADA68A] shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-[#2b1f17]">Cappuccino</p>
                        <div class="w-full h-2 bg-[#f0e8e0] rounded-full mt-1.5">
                            <div class="h-full bg-[#ADA68A] rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-black text-[#2b1f17] shrink-0">70</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-[#FFF0DA] shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-[#2b1f17]">Americano</p>
                        <div class="w-full h-2 bg-[#f0e8e0] rounded-full mt-1.5">
                            <div class="h-full bg-[#FFF0DA] rounded-full" style="width: 55%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-black text-[#2b1f17] shrink-0">55</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-[#c4a88a] shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-[#2b1f17]">Matcha Latte</p>
                        <div class="w-full h-2 bg-[#f0e8e0] rounded-full mt-1.5">
                            <div class="h-full bg-[#c4a88a] rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-black text-[#2b1f17] shrink-0">40</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#f0e8e0]">
            <h3 class="text-base font-black text-[#2b1f17]">Transaksi Terbaru</h3>
            <a href="#" class="text-sm font-bold text-[#764706] hover:text-[#5a3606] transition">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#8b684b] text-xs font-bold uppercase tracking-wider bg-[#faf6f2]">
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Kasir</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e8e0]">
                    <tr class="hover:bg-[#faf6f2] transition">
                        <td class="px-6 py-4 font-bold text-[#2b1f17]">#INV-001</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Walk-in</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Muhammad Hasby</td>
                        <td class="px-6 py-4 font-black text-[#2b1f17]">Rp 50.000</td>
                        <td class="px-6 py-4"><span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1.5 rounded-full">Selesai</span></td>
                        <td class="px-6 py-4 text-[#8b684b]">10:30</td>
                    </tr>
                    <tr class="hover:bg-[#faf6f2] transition">
                        <td class="px-6 py-4 font-bold text-[#2b1f17]">#INV-002</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Dinda</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Muhammad Hasby</td>
                        <td class="px-6 py-4 font-black text-[#2b1f17]">Rp 87.000</td>
                        <td class="px-6 py-4"><span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1.5 rounded-full">Selesai</span></td>
                        <td class="px-6 py-4 text-[#8b684b]">11:15</td>
                    </tr>
                    <tr class="hover:bg-[#faf6f2] transition">
                        <td class="px-6 py-4 font-bold text-[#2b1f17]">#INV-003</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Budi</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Daniel</td>
                        <td class="px-6 py-4 font-black text-[#2b1f17]">Rp 124.000</td>
                        <td class="px-6 py-4"><span class="text-xs font-bold text-yellow-700 bg-yellow-100 px-3 py-1.5 rounded-full">Diproses</span></td>
                        <td class="px-6 py-4 text-[#8b684b]">11:45</td>
                    </tr>
                    <tr class="hover:bg-[#faf6f2] transition">
                        <td class="px-6 py-4 font-bold text-[#2b1f17]">#INV-004</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Sari</td>
                        <td class="px-6 py-4 text-[#5f4a3b]">Muhammad Hasby</td>
                        <td class="px-6 py-4 font-black text-[#2b1f17]">Rp 35.000</td>
                        <td class="px-6 py-4"><span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1.5 rounded-full">Selesai</span></td>
                        <td class="px-6 py-4 text-[#8b684b]">12:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
