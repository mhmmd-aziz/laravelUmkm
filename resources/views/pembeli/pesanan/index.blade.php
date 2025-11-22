<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('info'))
                <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 border border-blue-300 dark:border-blue-700 text-blue-800 dark:text-blue-200 rounded-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   
                    <div class="space-y-6">
                        @forelse ($transaksis as $transaksi)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                                <!-- Header Transaksi -->
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 flex flex-col md:flex-row justify-between md:items-center rounded-t-lg">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pesan: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->created_at->format('d M Y, H:i') }}</span></g>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Invoice: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->invoice_id }}</span></g>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Toko: <span class="font-medium text-gray-800 dark:text-gray-200">{{ $transaksi->toko->nama_toko }}</span></g>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Total: <span class="font-bold text-lg text-gray-900 dark:text-gray-100">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></g>
                                    </div>
                                    <div class="mt-2 md:mt-0">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($transaksi->status_transaksi == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($transaksi->status_transaksi == 'dibatalkan') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($transaksi->status_transaksi == 'dikirim') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                            {{ Str::title(str_replace('_', ' ', $transaksi->status_transaksi)) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Detail Item (Looping) -->
                                <div class="p-4 space-y-3 divide-y divide-gray-200 dark:divide-gray-700">
                                    {{-- INI DIA PERBAIKANNYA (details -> detailTransaksis) --}}
                                   @foreach ($transaksi->detailTransaksis as $detail)
                                        <div class="flex items-center space-x-4 pt-3 first:pt-0">
                                            
                                            {{-- 1. Cek Gambar: Jika produk ada, tampilkan gambar. Jika null, tampilkan kotak abu --}}
                                            @if($detail->produk)
                                                <img src="{{ Storage::url($detail->produk->gambar_produk_utama) }}" 
                                                     alt="{{ $detail->produk->nama_produk }}" 
                                                     class="w-16 h-16 rounded-md object-cover">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-md flex items-center justify-center text-xs text-gray-500 dark:text-gray-400">
                                                    Dihapus
                                                </div>
                                            @endif

                                            <div class="flex-1 text-sm">
                                                {{-- 2. Cek Nama: Gunakan '??' untuk mencegah error jika nama null --}}
                                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $detail->produk->nama_produk ?? 'Produk Tidak Tersedia' }}
                                                </p>
                                                
                                                <p class="text-gray-600 dark:text-gray-400">
                                                    {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            
                                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- TODO: Tambahkan link ke 'pembeli.pesanan.show' jika sudah dibuat --}}
                                {{-- <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg text-right">
                                    <x-secondary-button-link href="#">
                                        Lihat Detail Pesanan
                                    </x-secondary-button-link>
                                </div> --}}
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.37 12.126c-.227.42.023.956.45 1.182s.956-.023 1.182-.45M12 12.832V12m0 7.5A7.5 7.5 0 104.5 12a7.5 7.5 0 007.5 7.5z" />
                                </svg>
                                <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Anda Belum Punya Pesanan</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">
                                    Semua pesanan yang Anda buat akan muncul di sini.
                                </p>
                                <x-primary-button-link href="{{ route('home') }}" class="mt-6">
                                    {{ __('Mulai Belanja') }}
                                </x-primary-button-link>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $transaksis->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>