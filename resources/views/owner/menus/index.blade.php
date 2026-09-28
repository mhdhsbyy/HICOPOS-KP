@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')

    <style>
        .swal-card-custom {
            border-radius: 1rem !important;
            border: 1px solid #f0e8e0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            padding: 1.5rem !important;
        }
        .swal-card-custom .swal2-icon { margin: 0 auto 0.5rem !important; }
        .swal-card-custom .swal2-title { color: #2b1f17 !important; font-weight: 900 !important; font-size: 1.25rem !important; }
        .swal-card-custom .swal2-html-container { color: #5f4a3b !important; font-size: 0.875rem !important; margin: 0.5rem 0 0 !important; }
    </style>

<div class="space-y-8">

    {{-- Dashboard Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black text-[#2b1f17]">Kelola Menu</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Kelola daftar menu produk Hideout Coffee.</p>
        </div>
        <a href="{{ route('owner.menus.create') }}"
            class="inline-flex items-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Menu
        </a>
    </div>

    {{-- Error Message --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error', title: 'Gagal!', text: '{{ session("error") }}',
                confirmButtonColor: '#764706', confirmButtonText: 'OK', showConfirmButton: false, timer: 3000, timerProgressBar: true,
                customClass: { popup: 'swal-card-custom' },
                showClass: { popup: 'animate__animated animate__zoomIn' },
                hideClass: { popup: 'animate__animated animate__zoomOut' }
            });
        </script>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
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

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Menu</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $total }}, { delay: 150 })" x-text="shown">0</span></h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Menu Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $active }}, { delay: 300 })" x-text="shown">0</span></h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Menu Nonaktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $inactive }}, { delay: 450 })" x-text="shown">0</span></h3>
            </div>
        </div>
    </div>

    {{-- Daftar Menu --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0] flex items-center justify-between">
            <h3 class="text-base font-black text-[#2b1f17]">Daftar Menu</h3>
            <div class="flex items-center gap-3 flex-wrap">
                <select id="statusFilter"
                    class="px-4 pr-9 py-2 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer"
                    onchange="var o = this.options[this.selectedIndex]; if (o.dataset.url) window.location.href = o.dataset.url;">
                    <option value="" data-url="{{ route('owner.menus.index') }}" {{ !$status ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" data-url="{{ route('owner.menus.index', ['status' => 'active']) }}" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" data-url="{{ route('owner.menus.index', ['status' => 'inactive']) }}" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <select id="categoryFilter"
                    class="py-2 pl-4 pr-9 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition appearance-none cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    @foreach ($menus as $categoryName => $items)
                        <option value="{{ $categoryName }}">{{ $categoryName }} ({{ $items->count() }})</option>
                    @endforeach
                </select>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" id="searchMenu" placeholder="Cari menu..."
                        class="pl-10 pr-10 py-2 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition w-64">
                    <button type="button" id="clearSearchMenu" style="display:none"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b8a898] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-8" id="menuList">
                @forelse ($menus as $categoryName => $items)
                    <div class="menu-category">
                        <h4 class="text-sm font-black text-[#764706] uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#764706]"></span>
                            {{ $categoryName }}
                            <span class="text-xs font-bold text-[#8b684b] normal-case">({{ $items->count() }})</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                            @foreach ($items as $menu)
                    <div class="bg-white rounded-2xl shadow-lg border border-[#f0e8e0] overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                        data-name="{{ $menu->name }}" data-category="{{ $menu->category->name }}" data-code="{{ $menu->code }}" data-status="{{ $menu->status }}">
                        <div class="w-full h-40 bg-[#FFF0DA] flex items-center justify-center overflow-hidden">
                            @if ($menu->photo)
                                <img src="{{ $menu->thumb_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                            @endif
                        </div>
                            <div class="p-4">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-sm font-black text-[#2b1f17] truncate">{{ $menu->name }}</h4>
                                <form action="{{ route('owner.menus.toggle-status', $menu) }}" method="POST" class="status-toggle-form shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <label class="inline-flex items-center cursor-pointer" title="{{ $menu->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <input type="checkbox" class="sr-only peer status-toggle-input" {{ $menu->status === 'active' ? 'checked' : '' }}>
                                        <div class="status-pill px-3 py-1.5 rounded-full text-[10px] font-bold transition-colors bg-red-100 text-red-700 peer-checked:bg-green-100 peer-checked:text-green-700">
                                            <span class="status-label">{{ $menu->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                        </div>
                                    </label>
                                </form>
                            </div>
                            <p class="text-xs text-[#8b684b] mt-0.5">{{ $menu->category->name }}</p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-sm font-black text-[#764706]">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                @if ($menu->code)
                                    <span class="text-[10px] font-bold text-[#8b684b]">{{ $menu->code }}</span>
                                @endif
                            </div>
                            <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-[#f0e8e0]">
                                <a href="{{ route('owner.menus.show', $menu) }}" class="inline-flex items-center justify-center bg-[#f5f0eb] text-[#8b684b] p-2 rounded-lg hover:bg-[#ebe3db] transition" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                </a>
                                <a href="{{ route('owner.menus.edit', $menu) }}" class="inline-flex items-center justify-center bg-[#FFF0DA] text-[#764706] p-2 rounded-lg hover:bg-[#f0e0c0] transition" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
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
                        </div>
                    </div>
                @empty
                    <div id="menuEmpty" class="flex flex-col items-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                        <p class="mt-3 text-sm font-bold text-[#8b684b]">
                            @if ($status === 'active')
                                Tidak ada menu aktif
                            @elseif ($status === 'inactive')
                                Tidak ada menu nonaktif
                            @else
                                Belum ada menu
                            @endif
                        </p>
                    </div>
                @endforelse

                <div id="menuSearchEmpty" class="hidden flex-col items-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    <p id="menuSearchEmptyText" class="mt-3 text-sm font-bold text-[#8b684b]">Menu tidak ditemukan</p>
                </div>
            </div>
        </div>
    </div>

<script>
    const searchMenuInput = document.getElementById('searchMenu');
    const clearSearchMenu = document.getElementById('clearSearchMenu');
    const categoryFilter = document.getElementById('categoryFilter');
    const menuCategories = document.querySelectorAll('.menu-category');
    const menuSearchEmpty = document.getElementById('menuSearchEmpty');
    const menuSearchEmptyText = document.getElementById('menuSearchEmptyText');

    function filterMenus() {
        const query = searchMenuInput.value.toLowerCase().trim();
        const activeCategory = categoryFilter.value;
        let visible = 0;

        clearSearchMenu.style.display = searchMenuInput.value ? 'flex' : 'none';

        menuCategories.forEach(function (category) {
            const catName = category.querySelector('h4').textContent.trim().split('(')[0].trim().toLowerCase();
            const catMatch = activeCategory === 'all' || catName === activeCategory.toLowerCase();
            let categoryVisible = 0;

            category.querySelectorAll('[data-name]').forEach(function (card) {
                const name = (card.dataset.name || '').toLowerCase();
                const category = (card.dataset.category || '').toLowerCase();
                const code = (card.dataset.code || '').toLowerCase();
                const match = (name.includes(query) || category.includes(query) || code.includes(query)) && catMatch;
                card.style.display = match ? '' : 'none';
                if (match) categoryVisible++;
            });

            category.style.display = categoryVisible ? '' : 'none';
            visible += categoryVisible;
        });

        const noResults = visible === 0 && (query !== '' || activeCategory !== 'all');

        if (noResults) {
            menuSearchEmptyText.textContent = query !== ''
                ? `Tidak ada hasil pencarian untuk "${query}"`
                : 'Menu tidak ditemukan';
        }

        menuSearchEmpty.classList.toggle('hidden', !noResults);
        menuSearchEmpty.classList.toggle('flex', noResults);
    }

    searchMenuInput.addEventListener('input', filterMenus);
    categoryFilter.addEventListener('change', filterMenus);

    clearSearchMenu.addEventListener('click', function () {
        searchMenuInput.value = '';
        filterMenus();
        searchMenuInput.focus();
    });

    filterMenus();

</script>

<script>
    document.querySelectorAll('.status-toggle-form').forEach(function (form) {
        const input = form.querySelector('.status-toggle-input');
        const token = form.querySelector('input[name="_token"]').value;

        input.addEventListener('change', function () {
            updateStatusLabel();

            fetch(form.getAttribute('action'), {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
            })
            .then(function (response) {
                if (!response.ok) {
                    input.checked = !input.checked;
                    updateStatusLabel();
                }
            })
            .catch(function () {
                input.checked = !input.checked;
                updateStatusLabel();
            });
        });

        function updateStatusLabel() {
            const label = form.querySelector('.status-label');
            if (!label) return;
            label.textContent = input.checked ? 'Aktif' : 'Nonaktif';
        }
    });
</script>
@endsection
