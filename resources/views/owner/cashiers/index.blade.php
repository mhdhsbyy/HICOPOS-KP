@extends('layouts.app')

@section('title', 'Kelola Kasir')

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
            <h2 class="text-xl font-black text-[#2b1f17]">Kelola Kasir</h2>
            <p class="text-sm text-[#5f4a3b] mt-1">Kelola data kasir yang bertugas di Hideout Coffee.</p>
        </div>
        <a href="{{ route('owner.cashiers.create') }}"
            class="inline-flex items-center gap-2 bg-[#764706] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#5a3606] transition shadow-md shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Kasir
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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Total Kasir</p>
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
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kasir Aktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $active }}, { delay: 300 })" x-text="shown">0</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0] flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#8b684b] uppercase tracking-wider">Kasir Nonaktif</p>
                <h3 class="text-2xl font-black text-[#2b1f17] mt-0.5"><span x-data="hoCountUp({{ $inactive }}, { delay: 450 })" x-text="shown">0</span></h3>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] overflow-hidden">
        <div class="px-6 py-5 border-b border-[#f0e8e0] flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-base font-black text-[#2b1f17]">Daftar Kasir</h3>
            <div class="flex flex-wrap items-center gap-3">
                <select id="filterStatus" onchange="window.location.href = this.value"
                    class="px-4 pr-9 py-2 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition cursor-pointer">
                    <option value="{{ route('owner.cashiers.index') }}" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="{{ route('owner.cashiers.index', ['status' => 'active']) }}" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="{{ route('owner.cashiers.index', ['status' => 'inactive']) }}" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b684b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" id="searchKasir" placeholder="Cari kasir..."
                        class="pl-10 pr-10 py-2 text-sm rounded-xl border border-[#e2d3c2] bg-[#faf6f2] text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706]/30 focus:border-[#764706] transition w-64">
                    <button type="button" id="clearSearchKasir" style="display:none"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full text-[#b8a898] hover:bg-[#f0e8e0] hover:text-[#5f4a3b] flex items-center justify-center transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#8b684b] text-xs font-bold uppercase tracking-wider bg-[#faf6f2]">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">No. Telp</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0e8e0]">
                    @foreach ($cashiers as $cashier)
                        <tr class="hover:bg-[#faf6f2] transition">
                            <td class="px-6 py-4 text-[#5f4a3b]">{{ $cashiers->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4 font-bold text-[#2b1f17]">{{ $cashier->username }}</td>
                            <td class="px-6 py-4 text-[#5f4a3b]">{{ $cashier->first_name }} {{ $cashier->last_name }}</td>
                            <td class="px-6 py-4 text-[#5f4a3b]">{{ $cashier->email }}</td>
                            <td class="px-6 py-4 text-[#5f4a3b]">{{ $cashier->phone ?: '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <form action="{{ route('owner.cashiers.toggle-status', $cashier) }}" method="POST" class="status-toggle-form">
                                        @csrf
                                        @method('PATCH')
                                    <label class="inline-flex items-center cursor-pointer" title="{{ $cashier->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <input type="checkbox" class="sr-only peer status-toggle-input" {{ $cashier->status === 'active' ? 'checked' : '' }}>
                                        <div class="status-pill px-3 py-1.5 rounded-full text-[10px] font-bold transition-colors bg-red-100 text-red-700 peer-checked:bg-green-100 peer-checked:text-green-700">
                                            <span class="status-label">{{ $cashier->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                                        </div>
                                    </label>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('owner.cashiers.edit', $cashier) }}"
                                        class="inline-flex items-center gap-1.5 bg-[#FFF0DA] text-[#764706] px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#f0e0c0] transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('owner.cashiers.destroy', $cashier) }}" method="POST"
                                        @if ($cashier->transactions_count > 0)
                                            onsubmit="event.preventDefault(); blockedDelete('Kasir ini sudah memiliki data transaksi dan tidak dapat dihapus.');"
                                        @else
                                            onsubmit="event.preventDefault(); confirmDelete(this, 'Yakin ingin menghapus kasir ini?')"
                                        @endif>
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-100 transition cursor-pointer" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if ($cashiers->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    <p class="mt-3 text-sm font-bold text-[#8b684b]">
                                        @if (request('status') === 'active')
                                            Tidak ada kasir aktif
                                        @elseif (request('status') === 'inactive')
                                            Tidak ada kasir nonaktif
                                        @else
                                            Belum ada kasir terdaftar
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if ($cashiers->hasPages())
            <div class="px-6 py-4 border-t border-[#f0e8e0]">
                {{ $cashiers->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchKasir');
    const clearSearchKasir = document.getElementById('clearSearchKasir');
    const tbody = document.querySelector('table tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const serverEmptyRow = rows.find(r => r.querySelector('td[colspan]'));
    const defaultEmptyMessage = serverEmptyRow
        ? serverEmptyRow.querySelector('td p').textContent
        : '';

    let dynamicEmptyRow = null;

    function createEmptyRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td colspan="7" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#d4c8b8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <p class="mt-3 text-sm font-bold text-[#8b684b]"></p>
                </div>
            </td>`;
        return tr;
    }

    function showEmptyState(message) {
        if (serverEmptyRow) {
            serverEmptyRow.style.display = '';
            serverEmptyRow.querySelector('td p').textContent = message;
            return;
        }
        if (!dynamicEmptyRow) {
            dynamicEmptyRow = createEmptyRow();
            tbody.appendChild(dynamicEmptyRow);
        }
        dynamicEmptyRow.style.display = '';
        dynamicEmptyRow.querySelector('td p').textContent = message;
    }

    function hideEmptyState() {
        if (serverEmptyRow) serverEmptyRow.style.display = 'none';
        if (dynamicEmptyRow) dynamicEmptyRow.style.display = 'none';
    }

    function filterKasir() {
        const query = searchInput.value.toLowerCase().trim();
        let visible = 0;

        clearSearchKasir.style.display = searchInput.value ? 'flex' : 'none';

        rows.forEach(row => {
            if (row === serverEmptyRow) return;
            const text = row.textContent.toLowerCase();
            const match = text.includes(query);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        if (visible === 0) {
            showEmptyState(query !== ''
                ? `Tidak ada hasil pencarian untuk "${searchInput.value.trim()}"`
                : defaultEmptyMessage);
        } else {
            hideEmptyState();
        }
    }

    searchInput.addEventListener('input', filterKasir);

    clearSearchKasir.addEventListener('click', function () {
        searchInput.value = '';
        filterKasir();
        searchInput.focus();
    });
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
