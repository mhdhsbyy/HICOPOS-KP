@props(['promo', 'number'])

@php
    $isExpired = $promo->end_date && $promo->end_date->isPast();
@endphp

<div class="bg-white rounded-2xl border border-[#f0e8e0] p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h4 class="text-sm font-black text-[#2b1f17]">{{ $number }}. {{ $promo->name }}</h4>
                @if ($promo->type === 'percentage')
                    <span class="text-xs font-bold text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full">Persentase</span>
                @else
                    <span class="text-xs font-bold text-purple-700 bg-purple-100 px-2.5 py-0.5 rounded-full">Flat (Rp)</span>
                @endif
                @if ($promo->scope === 'item')
                    <span class="text-xs font-bold text-[#764706] bg-[#FFF0DA] px-2.5 py-0.5 rounded-full">Item</span>
                @elseif ($promo->scope === 'category')
                    <span class="text-xs font-bold text-green-700 bg-green-100 px-2.5 py-0.5 rounded-full">Kategori</span>
                @else
                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-0.5 rounded-full">Transaksi</span>
                @endif
            </div>
            @if ($promo->description)
                <p class="text-xs text-[#8b684b] mt-1">{{ $promo->description }}</p>
            @endif
        </div>
        <form action="{{ route('owner.promos.toggle-status', $promo) }}" method="POST" class="shrink-0">
            @csrf @method('PATCH')
            @if ($promo->is_active)
                <button type="submit" class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1.5 rounded-full hover:bg-green-200 transition cursor-pointer">Aktif</button>
            @else
                <button type="submit" class="text-xs font-bold text-red-700 bg-red-100 px-3 py-1.5 rounded-full hover:bg-red-200 transition cursor-pointer">Nonaktif</button>
            @endif
        </form>
    </div>

    <div class="flex items-center gap-6 mt-3 text-xs text-[#5f4a3b] flex-wrap">
        <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            <span class="font-bold">
                @if ($promo->type === 'percentage')
                    {{ $promo->value }}%
                @else
                    Rp {{ number_format($promo->value, 0, ',', '.') }}
                @endif
            </span>
        </div>
        @if ($promo->min_quantity)
            <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                <span>Min {{ $promo->min_quantity }} item</span>
            </div>
        @endif
        @if ($promo->min_amount)
            <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <span>Min Rp {{ number_format($promo->min_amount, 0, ',', '.') }}</span>
            </div>
        @endif
        @if ($promo->start_date || $promo->end_date)
            <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                <span class="{{ $isExpired ? 'text-red-500 font-bold line-through' : '' }}">{{ $promo->start_date ? $promo->start_date->format('d M Y') : '-' }} — {{ $promo->end_date ? $promo->end_date->format('d M Y') : '-' }}</span>
            </div>
        @else
            <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                <span class="text-[#b0a090]">Selalu aktif</span>
            </div>
        @endif
    </div>

    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-[#f0e8e0]">
        <a href="{{ route('owner.promos.edit', $promo) }}" class="inline-flex items-center justify-center bg-[#FFF0DA] text-[#764706] p-2 rounded-lg hover:bg-[#f0e0c0] transition" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
        </a>
        <form action="{{ route('owner.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 p-2 rounded-lg hover:bg-red-100 transition cursor-pointer" title="Hapus">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
            </button>
        </form>
    </div>
</div>
