@php
    $profileLayout = ($user->hasRole('kasir') || $isKasirMode)
        ? 'layouts.app-nosidebar'
        : 'layouts.app';
@endphp

@extends($profileLayout)

@section('title', 'Profil')

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

<div class="space-y-8 @if ($profileLayout === 'layouts.app-nosidebar') bg-white min-h-full @endif">

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
                customClass: { popup: 'swal-card-custom' },
                showClass: { popup: 'animate__animated animate__zoomIn' },
                hideClass: { popup: 'animate__animated animate__zoomOut' }
            });
        </script>
    @endif

    {{-- Account Summary --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#f0e8e0]">
        <div class="flex items-center justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    <div id="photo-preview" class="w-16 h-16 rounded-2xl bg-[#0d0a08] flex items-center justify-center overflow-hidden">
                        <img src="{{ $user->avatar_url }}" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <label for="photo" title="Ganti foto profil"
                        class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-[#764706] text-white flex items-center justify-center shadow-md border-2 border-white cursor-pointer hover:bg-[#5a3606] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 12h.008v.008h-.008V12Z"/></svg>
                    </label>
                </div>
                <div>
                    <h2 class="text-xl font-black text-[#2b1f17]">{{ $user->full_name }}</h2>
                    <p class="text-sm font-semibold text-[#8b684b] mt-0.5">{{ $user->username }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold uppercase tracking-wide px-3 py-1.5 rounded-full bg-[#FFF0DA] text-[#764706]">
                    {{ ucfirst($user->getRoleNames()->first()) }}
                </span>
            </div>
        </div>
        <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="handlePhotoChange(this)">
        </form>
        <p id="photo-error" class="hidden text-xs text-red-600 mt-3"></p>
    </div>

    {{-- Data Profil & Ubah Password --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">

    {{-- Data Profil --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
            </div>
            <div>
                <h3 class="text-base font-black text-[#2b1f17]">Data Profil</h3>
                <p class="text-xs text-[#8b684b] mt-0.5">Perbarui informasi akun Anda.</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf @method('PATCH')

            <div class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                    <x-input-error :messages="$errors->get('username')" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Depan</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                        <x-input-error :messages="$errors->get('first_name')" />
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Nama Belakang <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                        <x-input-error :messages="$errors->get('last_name')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Email</label>
                        <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="Masukkan email">
                        <x-input-error :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-bold text-[#2b1f17] mb-1.5">No. Telepon <span class="text-[#b8a898] font-normal">(opsional)</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition"
                            placeholder="08xxxxxxxxxx">
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-[#f0e8e0]">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#764706] hover:bg-[#5a3606] transition shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Ubah Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-[#f0e8e0] p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#FFF0DA] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#764706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Zm10.5-2.25h.008v.008h-.008v-.008Zm.008-1.5h.008v.008h-.008v-.008Z" /></svg>
            </div>
            <div>
                <h3 class="text-base font-black text-[#2b1f17]">Ubah Password</h3>
                <p class="text-xs text-[#8b684b] mt-0.5">Ganti password akun Anda secara berkala.</p>
            </div>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" />
            </div>

            <div>
                <label for="password" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Password Baru</label>
                <input type="password" name="password" id="password" autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                <x-input-error :messages="$errors->updatePassword->get('password')" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-[#2b1f17] mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2d3c2] text-sm text-[#2b1f17] placeholder-[#b8a898] focus:outline-none focus:ring-2 focus:ring-[#764706] focus:border-transparent transition">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-[#f0e8e0]">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#764706] hover:bg-[#5a3606] transition shadow-md">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<script>
    const photoError = document.getElementById('photo-error');
    const maxSize = 2 * 1024 * 1024;

    function handlePhotoChange(input) {
        const file = input.files[0];
        photoError.classList.add('hidden');
        photoError.textContent = '';

        if (!file) return;

        if (file.size > maxSize) {
            photoError.textContent = 'Ukuran foto maksimal 2MB. File Anda ' + (file.size / 1024 / 1024).toFixed(1) + 'MB.';
            photoError.classList.remove('hidden');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('photo-preview').innerHTML =
                '<img src="' + ev.target.result + '" alt="Foto Profil" class="w-full h-full object-cover">';
        };
        reader.readAsDataURL(file);

        if (input.form && typeof input.form.requestSubmit === 'function') {
            input.form.requestSubmit();
        } else if (input.form) {
            input.form.submit();
        }
    }
</script>
@endsection