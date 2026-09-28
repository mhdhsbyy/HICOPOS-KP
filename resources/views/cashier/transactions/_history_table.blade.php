@php
    $isOwner = Auth::user()->hasRole('owner');
    $tblShowRoute = $isOwner ? 'owner.transactions.kasir-show' : 'kasir.transactions.show';
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-[#f0e8e0] bg-[#faf7f4]">
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider w-12">No</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kode Transaksi</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Metode</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e8e0]">
            @forelse ($transactions as $transaction)
                <tr class="hover:bg-[#faf7f4] transition">
                    <td class="px-6 py-4 text-center text-[#8b684b] w-12">{{ $transactions->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4 text-left font-bold text-[#2b1f17]">{{ $transaction->code }}</td>
                    <td class="px-6 py-4 text-left text-[#5f4a3b]">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-[10px] font-bold text-[#5f4a3b] bg-[#f5f0eb] px-2.5 py-1 rounded-full uppercase">{{ $transaction->payment_method }}</span>
                    </td>
                    <td class="px-6 py-4 text-center font-bold text-[#764706]">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
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
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route($tblShowRoute, $transaction) }}"
                                class="inline-flex items-center gap-1.5 bg-[#f5f0eb] text-[#8b684b] px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#ebe3db] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                Detail
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m16.5 0V6.75A2.25 2.25 0 0 0 18 4.5H6A2.25 2.25 0 0 0 3.75 6.75v.75m16.5 0-1.5-6H5.25l-1.5 6m16.5 0h-16.5" />
                            </svg>
                            @php
                                $emptyMessage = match (true) {
                                    request()->filled('search') => 'Tidak ada hasil pencarian untuk "'.request('search').'"',
                                    request('status') === 'cancelled' => 'Tidak ada transaksi yang dibatalkan',
                                    default => 'Belum ada riwayat transaksi',
                                };
                            @endphp
                            <p class="mt-3 text-sm font-bold text-[#8b684b]">{{ $emptyMessage }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($transactions->hasPages())
    <div class="px-6 py-4 border-t border-[#f0e8e0]">
        {{ $transactions->appends(request()->query())->links('pagination::tailwind', ['resultsLabel' => 'Riwayat Transaksi']) }}
    </div>
@endif
