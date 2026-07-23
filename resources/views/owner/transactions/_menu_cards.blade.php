@foreach ($menus as $menu)
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden cursor-pointer hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
        @click="addItem({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->photo ? Storage::url($menu->photo) : '' }}', '{{ $menu->category->name ?? '' }}', {{ $menu->category_id }})">
        <div class="w-full h-28 bg-[#FFF0DA] flex items-center justify-center overflow-hidden">
            @if ($menu->photo)
                <img src="{{ Storage::url($menu->photo) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover" loading="lazy">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
            @endif
        </div>
        <div class="p-3">
            <h4 class="text-sm font-bold text-[#2b1f17] truncate">{{ $menu->name }}</h4>
            <p class="text-xs text-[#8b684b]">{{ $menu->category->name ?? '' }}</p>
            <p class="text-sm font-black text-[#764706] mt-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
        </div>
    </div>
@endforeach