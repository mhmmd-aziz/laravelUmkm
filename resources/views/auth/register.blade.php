<x-guest-layout>

    <div class="min-h-screen flex justify-center items-center bg-gray-50">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl px-8 pb-3 border border-gray-100">

            <!-- ALERT HANDLER -->
            @if ($errors->any())
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: `
                                <ul style="text-align:left;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            `,
                            confirmButtonColor: '#e53e3e'
                        });
                    });
                </script>
            @endif

            @if (session('success'))
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            confirmButtonColor: '#48bb78'
                        });
                    });
                </script>
            @endif

            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <h2 class="text-xl font-bold text-gray-800 mt-2">Daftar Akun RupaNusa</h2>
                <p class="text-sm text-gray-500 text-center">
                    Pilih peran Anda dan mulai perjalanan di marketplace budaya Nusantara.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" x-data="{ role: 'pembeli' }">
                @csrf

                <!-- ROLE SELECT -->
                <div class="grid grid-cols-2 gap-4 mb-6">

                    <!-- Pembeli -->
                    <div
                        @click="role = 'pembeli'"
                        :class="role === 'pembeli'
                            ? 'border-orange-500 bg-orange-50'
                            : 'border-gray-300'"
                        class="cursor-pointer rounded-xl border-2 p-4 text-center transition-all hover:shadow">

                        <svg class="w-8 h-8 mx-auto mb-3 text-indigo-600" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c.612 0 1.174.407 1.357 1.006l.303 1.02a.75.75 0 0 1-1.42.424l-1.11-3.742M7.5 14.25V5.25A2.25 2.25 0 0 1 9.75 3h4.5M4.5 19.5h15a2.25 2.25 0 0 0 2.121-1.667L21 11.25a2.25 2.25 0 0 0-2.121-2.833H9.75M16.5 19.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm-9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"></path>
                        </svg>

                        <h3 class="font-semibold text-gray-900 text-sm">Saya Pembeli</h3>
                        <p class="text-xs text-gray-600">Cari & beli produk budaya.</p>
                    </div>

                    <!-- Penjual -->
                    <div
                        @click="role = 'penjual'"
                        :class="role === 'penjual'
                            ? 'border-teal-600 bg-teal-50'
                            : 'border-gray-300'"
                        class="cursor-pointer rounded-xl border-2 p-4 text-center transition-all hover:shadow">

                        <svg class="w-8 h-8 mx-auto mb-3 text-teal-600" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21M6 3v1.875A2.625 2.625 0 0 0 8.625 7.5h6.75A2.625 2.625 0 0 0 18 4.875V3M18 3A2.25 2.25 0 0 0 15.75 0h-7.5A2.25 2.25 0 0 0 6 3v1.875A2.625 2.625 0 0 0 8.625 7.5h6.75A2.625 2.625 0 0 0 18 4.875V3Z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5c.621 0 1.125-.504 1.125-1.125V9.75A2.25 2.25 0 0 0 20.25 7.5H3.75A2.25 2.25 0 0 0 1.5 9.75v10.125c0 .621.504 1.125 1.125 1.125Z"></path>
                        </svg>

                        <h3 class="font-semibold text-gray-900 text-sm">Saya Penjual</h3>
                        <p class="text-xs text-gray-600">Jual produk UMKM.</p>
                    </div>

                </div>

                <!-- Role Hidden -->
                <input type="hidden" name="role" x-model="role">

                <!-- NAME -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full"
                        type="text" name="name" :value="old('name')" required />
                </div>

                <!-- EMAIL -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full"
                        type="email" name="email" :value="old('email')" required />
                </div>

                <!-- PASSWORD -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password" name="password" required />
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required />
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}"
                        class="text-sm text-gray-600 hover:text-orange-600">
                        Sudah punya akun?
                    </a>

                    <button type="submit"
                        class="px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 shadow">
                        Daftar
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-guest-layout>
