<x-app-layout>
    {{-- Slot header untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="mt-2">
                        Anda berada di panel admin. Di sini Anda bisa mengelola pengguna, melihat insight total penjualan,
                        dan memanajemen kategori produk.
                    </p>
                    {{-- Nanti kita tambahkan widget insight di sini --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

    