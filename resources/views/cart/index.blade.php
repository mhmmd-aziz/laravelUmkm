<x-app-layout> {{-- Harus login, jadi pakai app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                 <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if ($cartItems->isEmpty())
                        {{-- Tampilan Jika Keranjang Kosong --}}
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.612 0 1.174.407 1.357 1.006l.303 1.02a.75.75 0 01-1.42.424l-1.11-3.742M7.5 14.25V5.25A2.25 2.25 0 019.75 3h4.5M4.5 19.5h15a2.25 2.25 0 002.121-1.667L21 11.25a2.25 2.25 0 00-2.121-2.833H9.75M16.5 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Zm-9 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z" />
                            </svg>
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Keranjang Anda Kosong</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Sepertinya Anda belum menambahkan produk apapun ke keranjang.
                            </p>
                            <x-primary-button-link href="{{ route('home') }}" class="mt-6">
                                {{ __('Mulai Belanja') }}
                            </x-primary-button-link>
                        </div>
                        
                    @else
                        {{-- Tampilan Jika Keranjang Berisi --}}
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            
                            @foreach ($cartItems as $item)
                                <div class="py-4 flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-6">
                                    <!-- Gambar Produk -->
                                    <img src="{{ Storage::url($item->attributes->image) }}" alt="{{ $item->name }}" class="w-24 h-24 rounded-lg object-cover">
                                    
                                    <!-- Info Produk -->
                                    <div class="flex-1">
                                        <a href="{{ route('produk.show', $item->attributes->slug) }}" class="text-lg font-semibold text-gray-900 dark:text-gray-100 hover:underline">
                                            {{ $item->name }}
                                        </a>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Toko: {{ $item->attributes->toko_nama }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    
                                    <!-- Form Kuantitas -->
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <x-text-input type="number" name="quantity" class="w-20 text-center" 
                                                          value="{{ $item->quantity }}" 
                                                          min="1" max="{{ $item->attributes->stok_maks }}" 
                                                          onchange="this.form.submit()" {{-- Update otomatis saat diubah --}}
                                            />
                                            {{-- 
                                                Kita bisa tambahkan tombol update manual jika 'onchange' tidak diinginkan
                                                <x-secondary-button type="submit" class="ml-2">Update</x-secondary-button> 
                                            --}}
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-lg font-bold text-gray-900 dark:text-gray-100 md:w-32 md:text-right">
                                        Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}
                                    </div>

                                    <!-- Tombol Hapus -->
                                    <div class="md:w-16 md:text-right">
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" title="Hapus item">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer Keranjang (Total & Tombol) -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Keranjang</h3>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format(Cart::getTotal(), 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                                <!-- Tombol Hapus Semua -->
                                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">
                                        Kosongkan Keranjang
                                    </button>
                                </form>
                                
                                <!-- Tombol Lanjut Belanja & Checkout -->
                                <div class="flex space-x-4">
                                    <x-secondary-button-link href="{{ route('home') }}">
                                        {{ __('Lanjut Belanja') }}
                                    </x-secondary-button-link>
                                    
                                    {{-- INI DIA PERBAIKANNYA (checkout.index -> pembeli.checkout.index) --}}
                                    <x-primary-button-link href="{{ route('pembeli.checkout.index') }}">
                                        {{ __('Lanjut ke Checkout') }}
                                    </x-primary-button-link>
                                </div>
                            </div>

                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>