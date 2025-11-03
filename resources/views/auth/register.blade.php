<x-guest-layout>
    <!-- 
      Kita menggunakan Alpine.js (x-data) untuk menyimpan state 'role' yang dipilih.
      Nilai defaultnya adalah 'pembeli'.
    -->
    <form method="POST" action="{{ route('register') }}" x-data="{ role: 'pembeli' }">
        @csrf

        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">
            Bergabung dengan Komunitas Kami
        </h2>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-8">
            Pilih peran Anda untuk memulai.
        </p>

        <!-- Pilihan Role yang Menarik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Pilihan 1: Pembeli -->
            <div @click="role = 'pembeli'"
                 :class="role === 'pembeli' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/50' : 'border-gray-300 dark:border-gray-600'"
                 class="cursor-pointer rounded-lg border-2 p-6 text-center transition-all duration-200 hover:shadow-lg">
                
                <!-- Ikon (Contoh menggunakan SVG, Anda bisa ganti) -->
                <svg class="w-12 h-12 mx-auto mb-3 text-indigo-600" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c.612 0 1.174.407 1.357 1.006l.303 1.02a.75.75 0 0 1-1.42.424l-1.11-3.742M7.5 14.25V5.25A2.25 2.25 0 0 1 9.75 3h4.5M4.5 19.5h15a2.25 2.25 0 0 0 2.121-1.667L21 11.25a2.25 2.25 0 0 0-2.121-2.833H9.75M16.5 19.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm-9 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"></path>
                </svg>

                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Saya Seorang Pembeli</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Saya ingin mencari dan membeli produk kriya dan budaya unik.</p>
            </div>

            <!-- Pilihan 2: Penjual -->
            <div @click="role = 'penjual'"
                 :class="role === 'penjual' ? 'border-teal-600 bg-teal-50 dark:bg-teal-900/50' : 'border-gray-300 dark:border-gray-600'"
                 class="cursor-pointer rounded-lg border-2 p-6 text-center transition-all duration-200 hover:shadow-lg">
                
                <!-- Ikon (Contoh menggunakan SVG) -->
                <svg class="w-12 h-12 mx-auto mb-3 text-teal-600" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21M6 3v1.875A2.625 2.625 0 0 0 8.625 7.5h6.75A2.625 2.625 0 0 0 18 4.875V3M18 3A2.25 2.25 0 0 0 15.75 0h-7.5A2.25 2.25 0 0 0 6 3v1.875A2.625 2.625 0 0 0 8.625 7.5h6.75A2.625 2.625 0 0 0 18 4.875V3Z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5c.621 0 1.125-.504 1.125-1.125V9.75A2.25 2.25 0 0 0 20.25 7.5H3.75A2.25 2.25 0 0 0 1.5 9.75v10.125c0 .621.504 1.125 1.125 1.125Z"></path>
                </svg>

                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Saya Seorang Penjual</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Saya ingin menjual dan mempromosikan produk UMKM saya.</p>
            </div>
        </div>

        <!-- Input 'role' tersembunyi yang nilainya di-bind ke Alpine.js -->
        <input type="hidden" name="role" x-model="role">

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
