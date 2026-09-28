<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-[#f0e8e0] bg-[#faf7f4]">
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider w-12">No</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kode Transaksi</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kasir / Owner</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Metode</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-[#8b684b] uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-center text-xs font-bold text-[#8b684b] uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e8e0]">
            @forelse ($transactions as $transaction)
                <tr class="hover:bg-[#faf7f4] transition">
                    <td class="px-6 py-4 text-center text-[#8b684b] w-12">{{ $transactions->firstItem() + $loop->index }}</td>
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
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold text-[#5f4a3b] bg-[#f5f0eb] px-2.5 py-1 rounded-full uppercase">{{ $transaction->payment_method }}</span>
                    </td>
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
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('owner.transactions.show', $transaction) }}"
                                class="inline-flex items-center gap-1.5 bg-[#f5f0eb] text-[#8b684b] px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#ebe3db] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                Detail
                            </a>
                            @if ($transaction->status === 'completed')
                                <form action="{{ route('owner.transactions.cancel', $transaction) }}" method="POST" onsubmit="event.preventDefault(); confirmCancel(this, 'Yakin ingin membatalkan transaksi {{ $transaction->code }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-red-100 transition cursor-pointer" title="Batalkan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                @php
                    $emptyMessage = match (true) {
                        request()->filled('search') => 'Tidak ada hasil pencarian untuk "'.request('search').'"',
                        request('status') === 'completed' => 'Tidak ada transaksi selesai',
                        request('status') === 'cancelled' => 'Tidak ada transaksi dibatalkan',
                        default => 'Belum ada transaksi',
                    };
                @endphp
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
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
