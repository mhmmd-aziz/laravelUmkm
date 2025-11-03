<x-app-layout>
    {{-- Slot header untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Tampilkan nama toko jika ada --}}
            {{ Auth::user()->toko ? Auth::user()->toko->nama_toko : 'Dashboard Penjual' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses setelah buat toko (dari session) --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold">Selamat Datang, Penjual {{ Auth::user()->name }}!</h3>
                    <p class="mt-2">
                        Ini adalah dashboard penjual Anda. Nanti di sini akan ada:
                    </p>
                    <ul class="list-disc list-inside mt-2">
                        <li>Grafik Omset (Harian, Mingguan, Bulanan)</li>
                        <li>Manajemen Stok Produk</li>
                        <li>Daftar Pesanan (Dikemas, Dikirim, Selesai)</li>
                        <li>Fitur Ekspor ke Excel</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

