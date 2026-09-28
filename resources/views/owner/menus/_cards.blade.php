@foreach ($menus as $menu)
    <div class="bg-white rounded-2xl shadow-lg border border-[#f0e8e0] overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
        data-name="{{ $menu->name }}" data-category="{{ $menu->category->name }}" data-code="{{ $menu->code }}">
        {{-- Photo --}}
        <div class="w-full h-40 bg-[#FFF0DA] flex items-center justify-center overflow-hidden">
            @if ($menu->photo)
                <img src="{{ $menu->thumb_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover" loading="lazy">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                </svg>
            @endif
        </div>

        {{-- Content --}}
        <div class="p-4">
            {{-- Name + Status --}}
            <div class="flex items-center justify-between gap-2">
                <h4 class="text-sm font-black text-[#2b1f17] truncate">{{ $menu->name }}</h4>
                <form action="{{ route('owner.menus.toggle-status', $menu) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    @if ($menu->status === 'active')
                        <button type="submit" class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded-full hover:bg-green-200 transition cursor-pointer shrink-0">Aktif</button>
                    @else
                        <button type="submit" class="text-[10px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded-full hover:bg-red-200 transition cursor-pointer shrink-0">Nonaktif</button>
                    @endif
                </form>
            </div>

            {{-- Kategori --}}
            <p class="text-xs text-[#8b684b] mt-0.5">{{ $menu->category->name }}</p>

            {{-- Harga + Kode --}}
            <div class="flex items-center justify-between mt-1">
                <p class="text-sm font-black text-[#764706]">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                @if ($menu->code)
                    <span class="text-[10px] font-bold text-[#8b684b]">{{ $menu->code }}</span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-[#f0e8e0]">
                <a href="{{ route('owner.menus.show', $menu) }}"
                    class="inline-flex items-center justify-center bg-[#f5f0eb] text-[#8b684b] p-2 rounded-lg hover:bg-[#ebe3db] transition" title="Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </a>
                <a href="{{ route('owner.menus.edit', $menu) }}"
                    class="inline-flex items-center justify-center bg-[#FFF0DA] text-[#764706] p-2 rounded-lg hover:bg-[#f0e0c0] transition" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                </a>
                <form action="{{ route('owner.menus.destroy', $menu) }}" method="POST"
                    @if ($menu->transaction_items_count > 0)
                        onsubmit="event.preventDefault(); blockedDelete('Menu ini sudah memiliki data transaksi dan tidak dapat dihapus.');"
                    @else
                        onsubmit="event.preventDefault(); confirmDelete(this, 'Yakin ingin menghapus menu ini?')"
                    @endif>
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center bg-red-50 text-red-600 p-2 rounded-lg hover:bg-red-100 transition cursor-pointer" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endforeach
