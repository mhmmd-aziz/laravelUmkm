<x-guest-layout>
    
    <div class="bg-white dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class.="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Pesan Error (cth: stok tidak cukup) --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Grid 2 Kolom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                
                <!-- Kolom Kiri: Gambar Produk -->
                <div>
                    <img src="{{ Storage::url($produk->gambar_produk_utama) }}" alt="{{ $produk->nama_produk }}" class="w-full h-auto object-cover rounded-lg shadow-lg">
                </div>

                <!-- Kolom Kanan: Info Produk & Aksi -->
                <div class="flex flex-col justify-between">
                    <div>
                        <!-- Kategori & Toko -->
                        <div class="mb-2">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900 px-3 py-1 rounded-full">{{ $produk->kategori->nama_kategori }}</span>
                        </div>
                        
                        <!-- Nama Produk -->
                        <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-gray-100 mb-3">
                            {{ $produk->nama_produk }}
                        </h1>

                        <!-- Info Toko -->
                        <div class="mb-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">dijual oleh</span>
                            <a href="#" class="text-sm font-semibold text-gray-800 dark:text-gray-200 hover:underline">{{ $produk->toko->nama_toko }}</a>
                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $produk->toko->provinsi->nama_provinsi }})</span>
                        </div>
                        
                        <!-- Harga -->
                        <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-6">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </p>

                        <!-- Deskripsi Singkat -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Deskripsi Singkat</h2>
                            <p class="text-gray-700 dark:text-gray-300 text-base leading-relaxed">
                                {{ $produk->deskripsi_singkat }}
                            </p>
                        </div>
                        
                        <!-- Detail (Stok & Berat) -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Detail Produk</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Stok Tersisa</span>
                                    @if($produk->stok > 0)
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $produk->stok }} pcs</p>
                                    @else
                                        <p class="text-lg font-medium text-red-600 dark:text-red-400">Habis</p>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Berat</span>
                                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $produk->berat_gram }} gram</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi (Sekarang pakai Form) -->
                    <div class="mt-6">
                        @if($produk->stok > 0)
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                {{-- Kirim data produk yang tidak terlihat oleh user --}}
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <input type="hidden" name="produk_slug" value="{{ $produk->slug }}"> {{-- Untuk redirect kembali --}}

                                <div class="flex items-center space-x-4 mb-4">
                                    <x-input-label for="quantity" :value="__('Jumlah:')" class="text-lg" />
                                    <x-text-input id="quantity" name="quantity" type="number" value="1" min="1" max="{{ $produk->stok }}" class="block w-24" required />
                                </div>
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />

                                <x-primary-button class="w-full text-lg py-3 justify-center">
                                    <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.121.001.241.012.36.03l.345.09c.39.106.623.498.623.924v3.75C17.25 21.01 16.74 21.75 16 21.75H8c-.74 0-1.25-.74-1.25-1.5v-3.75c0-.426.233-.818.623-.924l.345-.09a2.25 2.25 0 01.36-.03h11.218M13.5 14.25a3 3 0 013-3h.75a3 3 0 013 3v3.75M6 6h12m-6 6h.008" />
                                    </svg>
                                    {{ __('+ Tambah ke Keranjang') }}
                                </x-primary-button>
                            </form>
                        @else
                            <x-primary-button class="w-full text-lg py-3 justify-center" disabled>
                                {{ __('Stok Habis') }}
                            </x-primary-button>
                        @endif
                        
                        {{-- Hanya tampilkan pesan login jika stok ada & user adalah tamu --}}
                        @guest
                            @if($produk->stok > 0)
                            <p class="text-center mt-3 text-sm text-gray-500 dark:text-gray-400">
                                Silakan <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:underline">login</a> untuk membeli.
                            </p>
                            @endif
                        @endguest

                        {{-- Hanya Penjual yang tidak bisa membeli --}}
                        @auth
                            @if(Auth::user()->role === 'penjual' || Auth::user()->role === 'admin')
                                <p class="text-center mt-3 text-sm text-red-500 dark:text-red-400">
                                    Anda tidak dapat membeli produk (Login sebagai Penjual/Admin).
                                </p>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Deskripsi Lengkap (di bawah) -->
            @if ($produk->deskripsi_lengkap)
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Deskripsi Lengkap Produk</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                     <p>{!! nl2br(e($produk->deskripsi_lengkap)) !!}</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-guest-layout>

