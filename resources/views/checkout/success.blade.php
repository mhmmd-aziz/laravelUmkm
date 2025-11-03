<x-guest-layout>
    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 text-center">
            
            <div class="p-8 bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                
                {{-- Icon Sukses (Inline SVG) --}}
                <svg class="h-20 w-20 text-green-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <h1 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                    Pesanan Berhasil Dibuat!
                </h1>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Terima kasih atas pesanan Anda. Pesanan Anda telah kami terima dan akan segera diproses oleh penjual.
                </p>

                {{-- Tampilkan daftar transaksi yang baru dibuat --}}
                @if (session('transaksis'))
                    <div class="mt-6 text-left border-t border-b border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach (session('transaksis') as $transaksi)
                            <div class="py-4">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pesanan ke Toko: {{ $transaksi->toko->nama_toko }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Nomor Invoice: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->invoice_id }}</span></g>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total: <span class="font-medium text-gray-800 dark:text-gray-200">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></g>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status: <span class="font-medium text-yellow-600 dark:text-yellow-400">Menunggu Pembayaran</span></g>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 flex flex-col sm:flex-row sm:justify-center sm:space-x-4 space-y-4 sm:space-y-0">
                    <x-primary-button-link href="{{ route('pembeli.pesanan.index') }}">
                        Lihat Riwayat Pesanan Saya
                    </x-primary-button-link>
                    <x-secondary-button-link href="{{ route('home') }}">
                        Kembali ke Homepage
                    </x-secondary-button-link>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
