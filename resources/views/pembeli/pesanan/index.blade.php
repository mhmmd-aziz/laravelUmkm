<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-6">
                @forelse ($transaksis as $transaksi)
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            {{-- Header Transaksi --}}
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pesanan Dibuat: {{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        Toko: <span class="text-indigo-600 dark:text-indigo-400">{{ $transaksi->toko->nama_toko }}</span>
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Invoice: {{ $transaksi->invoice_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full 
                                        @if($transaksi->status_pesanan == 'menunggu_pembayaran') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($transaksi->status_pesanan == 'dikemas') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($transaksi->status_pesanan == 'dikirim') bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300
                                        @elseif($transaksi->status_pesanan == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @endif">
                                        {{ ucwords(str_replace('_', ' ', $transaksi->status_pesanan)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Detail Item --}}
                            <div class="space-y-4">
                                @foreach ($transaksi->details as $detail)
                                <div class="flex items-center space-x-4">
                                    <img src="{{ Storage::url($detail->produk->gambar_produk_utama) }}" alt="{{ $detail->produk->nama_produk }}" class="w-20 h-20 rounded-md object-cover">
                                    <div class="flex-1 text-sm">
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $detail->produk->nama_produk }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>

                            {{-- Footer Transaksi --}}
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                <a href="{{ route('pembeli.pesanan.show', $transaksi) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Lihat Detail Pesanan
                                </a>
                                <div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Pesanan:</span>
                                    <span class="text-xl font-bold text-gray-900 dark:text-gray-100 ml-2">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Anda belum memiliki riwayat pesanan.</h3>
                        <x-primary-button-link href="{{ route('home') }}" class="mt-4">
                            Mulai Belanja
                        </x-primary-button-link>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $transaksis->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
