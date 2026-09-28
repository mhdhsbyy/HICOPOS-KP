@extends('layouts.app')

@section('title', 'Kelola Promo')

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
            <h2 class="text-xl font-black text-[#2b1f17]">Kelola Promo</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Kelola diskon dan promo otomatis untuk transaksi.</p>
        </div>
        <a href="{{ route('owner.promos.create') }}"
            class="inline-flex items-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Promo
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
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#764706',
                confirmButtonText: 'OK',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-card-custom'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            });
        </script>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                    </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Promo</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $total }}, { delay: 150 })" x-text="shown">0</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Promo Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $active }}, { delay: 300 })" x-text="shown">0</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Promo Kadaluarsa</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $expired }}, { delay: 450 })" x-text="shown">0</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Promo Nonaktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $inactive }}, { delay: 600 })" x-text="shown">0</span></h3>
            </div>
        </div>
    </div>

    {{-- Daftar Promo --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0] flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-base font-black text-[#2b1f17]">Daftar Promo</h3>
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" id="searchPromo" placeholder="Cari promo..."
                        class="pl-10 pr-10 py-2 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition w-64">
                    <button type="button" id="clearSearchPromo" style="display:none"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b8a898] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <select id="filterStatus" onchange="window.location.href = this.value"
                    class="px-4 pr-9 py-2 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer">
                    <option value="{{ route('owner.promos.index') }}" {{ ! $status ? 'selected' : '' }}>Semua Status</option>
                    <option value="{{ route('owner.promos.index', ['status' => 'active']) }}" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="{{ route('owner.promos.index', ['status' => 'inactive']) }}" {{ $status === 'inactive' ? 'selected' : '' }}>Non Aktif</option>
                    <option value="{{ route('owner.promos.index', ['status' => 'expired']) }}" {{ $status === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>
        </div>
        <div class="p-6 space-y-6">

    {{-- Promo Aktif --}}
    @if ($activePromos->count())
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden" data-promo-section data-status="active">
        <div class="px-6 py-5 border-b border-[#f0e8e0]">
            <h3 class="text-base font-black text-[#2b1f17]">Promo Aktif</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach ($activePromos as $promo)
            @include('owner.promos._promo-card', ['promo' => $promo, 'number' => $loop->iteration])
        @endforeach
        </div>
    </div>
    @endif

    {{-- Promo Kadaluarsa --}}
    @if ($expiredPromos->count())
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden" data-promo-section data-status="expired">
        <div class="px-6 py-5 border-b border-[#f0e8e0]">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <h3 class="text-base font-black text-[#2b1f17]">Promo Kadaluarsa</h3>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach ($expiredPromos as $promo)
            @include('owner.promos._promo-card', ['promo' => $promo, 'number' => $loop->iteration])
        @endforeach
        </div>
    </div>
    @endif

    {{-- Promo Nonaktif --}}
    @if ($inactivePromos->count())
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden" data-promo-section data-status="inactive">
        <div class="px-6 py-5 border-b border-[#f0e8e0] bg-gray-50/50">
            <h3 class="text-base font-black text-[#2b1f17]">Promo Nonaktif</h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach ($inactivePromos as $promo)
            @include('owner.promos._promo-card', ['promo' => $promo, 'number' => $loop->iteration])
        @endforeach
        </div>
    </div>
    @endif

    @if ($activePromos->isEmpty() && $expiredPromos->isEmpty() && $inactivePromos->isEmpty())
        <div class="flex flex-col items-center py-12">
            @if ($status === 'active')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <p class="mt-3 text-sm font-bold text-[#8b684b]">Tidak ada promo yang aktif</p>
            @elseif ($status === 'expired')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <p class="mt-3 text-sm font-bold text-[#8b684b]">Tidak ada promo yang kadaluarsa</p>
            @elseif ($status === 'inactive')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                <p class="mt-3 text-sm font-bold text-[#8b684b]">Tidak ada promo yang nonaktif</p>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /></svg>
                <p class="mt-3 text-sm font-bold text-[#8b684b]">Belum ada promo</p>
            @endif
        </div>
    @endif

    <div id="noResultsPromo" class="hidden flex-col items-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
        <p id="noResultsPromoText" class="mt-3 text-sm font-bold text-[#8b684b]">Tidak ada promo yang cocok</p>
    </div>

        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchPromo');
    const clearSearchPromo = document.getElementById('clearSearchPromo');
    const sections = document.querySelectorAll('[data-promo-section]');
    const cards = document.querySelectorAll('.promo-card');
    const noResults = document.getElementById('noResultsPromo');

    function applyFilters() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        let totalVisible = 0;

        if (clearSearchPromo) {
            clearSearchPromo.style.display = searchInput.value ? 'flex' : 'none';
        }

        sections.forEach(section => {
            section.querySelectorAll('.promo-card').forEach(card => {
                const matchText = card.textContent.toLowerCase().includes(query);
                card.style.display = matchText ? '' : 'none';
            });

            const visible = Array.from(section.querySelectorAll('.promo-card'))
                .filter(card => card.style.display !== 'none').length;
            section.style.display = visible ? '' : 'none';
            totalVisible += visible;
        });

        if (noResults) {
            if (totalVisible === 0 && query !== '') {
                noResults.style.display = 'flex';
                const noResultsText = document.getElementById('noResultsPromoText');
                if (noResultsText) {
                    noResultsText.textContent = `Tidak ada hasil pencarian untuk "${searchInput.value.trim()}"`;
                }
            } else {
                noResults.style.display = 'none';
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    if (clearSearchPromo) {
        clearSearchPromo.addEventListener('click', function () {
            searchInput.value = '';
            applyFilters();
            searchInput.focus();
        });
    }

    applyFilters();

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
            const pill = form.querySelector('.status-pill');
            if (pill && pill.dataset.expired === '1') return;
            label.textContent = input.checked ? 'Aktif' : 'Nonaktif';
        }
    });
</script>
@endsection
