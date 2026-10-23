@props(['promo', 'number'])

@php
    $isExpired = $promo->end_date && $promo->end_date->isPast();
    $targetName = match ($promo->scope) {
        'item' => $promo->menus->pluck('name')->join(', '),
        'category' => $promo->category?->name,
        default => null,
    };
@endphp

<div class="promo-card bg-white rounded-2xl shadow-lg border border-[#f0e8e0] overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
    {{-- Banner --}}
    <div class="relative h-24 bg-[#0d0a08] flex flex-col items-center justify-center overflow-hidden">
        <div class="absolute inset-0 opacity-80 pointer-events-none"
            style="background-image: url('{{ asset('images/bg3.png') }}'); background-size: cover; background-position: center;"></div>
        <div class="relative z-10 flex flex-col items-center pointer-events-none">
            <p class="text-2xl font-black text-white leading-none">
                @if ($promo->type === 'percentage')
                    {{ $promo->value }}%
                @else
                    Rp {{ number_format($promo->value, 0, ',', '.') }}
                @endif
            </p>
            <p class="text-[10px] font-bold text-[#c4a88a] tracking-wider mt-1">DISKON PROMO</p>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 flex-1 flex flex-col">
        {{-- Name + Status --}}
        <div class="flex items-center justify-between gap-2">
            <h4 class="text-sm font-black text-[#2b1f17] truncate">{{ $number }}. {{ $promo->name }}</h4>
            <form action="{{ route('owner.promos.toggle-status', $promo) }}" method="POST" class="status-toggle-form shrink-0">
                @csrf @method('PATCH')
                <label class="inline-flex items-center cursor-pointer" title="{{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <input type="checkbox" class="sr-only peer status-toggle-input" {{ $promo->is_active ? 'checked' : '' }}>
                    <div class="status-pill text-[10px] font-bold px-2 py-0.5 rounded-full transition-colors {{ $isExpired ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700 peer-checked:bg-green-100 peer-checked:text-green-700' }}" data-expired="{{ $isExpired ? '1' : '0' }}">
                        <span class="status-label">{{ $isExpired ? 'Kadaluarsa' : ($promo->is_active ? 'Aktif' : 'Nonaktif') }}</span>
                    </div>
                </label>
            </form>
        </div>

        {{-- Tipe + Scope --}}
        <div class="flex items-center gap-1.5 mt-2 flex-wrap">
            @if ($promo->type === 'percentage')
                <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Persentase</span>
            @else
                <span class="text-[10px] font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">Flat (Rp)</span>
            @endif
            @if ($promo->scope === 'item')
                <span class="text-[10px] font-bold text-[#764706] bg-[#FFF0DA] px-2 py-0.5 rounded-full">Item</span>
            @elseif ($promo->scope === 'category')
                <span class="text-[10px] font-bold text-pink-700 bg-pink-100 px-2 py-0.5 rounded-full">Kategori</span>
            @else
                <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-full">Transaksi</span>
            @endif
        </div>

        @if ($promo->description)
            <p class="text-xs text-[#8b684b] mt-2">{{ $promo->description }}</p>
        @endif

        {{-- Syarat --}}
        <div class="space-y-1 mt-2">
            @if ($targetName)
                <p class="flex items-center gap-1.5 text-[10px] font-bold text-[#764706] truncate">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" /></svg>
                    {{ $targetName }}
                </p>
            @endif
            @if ($promo->min_quantity)
                <p class="flex items-center gap-1.5 text-[10px] font-bold text-[#8b684b] truncate">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" /></svg>
                    Min {{ $promo->min_quantity }} item
                </p>
            @endif
            @if ($promo->min_amount)
                <p class="flex items-center gap-1.5 text-[10px] font-bold text-[#8b684b] truncate">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    Min belanja Rp {{ number_format($promo->min_amount, 0, ',', '.') }}
                </p>
            @endif
            @if (!($targetName || $promo->min_quantity || $promo->min_amount))
                <p class="text-[10px] font-bold text-[#b8a898]">Tanpa syarat</p>
            @endif
        </div>

        {{-- Periode --}}
        <div class="mt-2 space-y-1 mb-3">
            @if ($promo->start_date || $promo->end_date)
                @if ($promo->start_date)
                    <p class="flex items-center gap-1.5 text-[10px] font-bold {{ $isExpired ? 'text-red-500 line-through' : 'text-[#8b684b]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        Mulai : {{ $promo->start_date->format('d M Y h:i A') }}
                    </p>
                @endif
                @if ($promo->end_date)
                    <p class="flex items-center gap-1.5 text-[10px] font-bold {{ $isExpired ? 'text-red-500 line-through' : 'text-[#8b684b]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        Sampai : {{ $promo->end_date->format('d M Y h:i A') }}
                    </p>
                @endif
            @else
                <p class="flex items-center gap-1.5 text-[10px] font-bold text-[#b0a090]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Selalu aktif
                </p>
            @endif
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-2 gap-2 mt-auto pt-3 border-t border-[#f0e8e0]">
            <a href="{{ route('owner.promos.edit', $promo) }}"
                class="inline-flex items-center justify-center bg-[#FFF0DA] text-[#764706] p-2 rounded-lg hover:bg-[#f0e0c0] transition" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
            </a>
            <form action="{{ route('owner.promos.destroy', $promo) }}" method="POST"
                onsubmit="event.preventDefault(); confirmDelete(this, 'Yakin ingin menghapus promo ini? Data transaksi yang sudah ada tidak akan terhapus.')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full inline-flex items-center justify-center bg-red-50 text-red-600 p-2 rounded-lg hover:bg-red-100 transition cursor-pointer" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>