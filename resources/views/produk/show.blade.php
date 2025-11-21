<x-app-layout>

    <div class="bg-white dark:bg-gray-900 py-12">
        <div class="max-w-[1270px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Grid 50% / 50% -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                <!-- LEFT: Product Image -->
                <div class="flex flex-col space-y-4">
                    <div class="w-full aspect-square bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden shadow-xl">
                        <img 
                            src="{{ Storage::url($produk->gambar_produk_utama) }}" 
                            alt="{{ $produk->nama_produk }}" 
                            class="w-full h-full object-cover"
                        >
                    </div>
                </div>

                <!-- RIGHT: Product Info -->
                <div class="flex flex-col justify-between">

                    <div>

                        <!-- Category badge -->
                        <span class="inline-block mb-3 text-sm font-medium text-orange-700 dark:text-orange-300 bg-orange-100 dark:bg-orange-900/40 px-3 py-1 rounded-full">
                            {{ $produk->kategori->nama_kategori }}
                        </span>

                        <!-- Product name -->
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 leading-tight mb-4">
                            {{ $produk->nama_produk }}
                        </h1>

                        <!-- Store info -->
                        <div class="flex items-center space-x-2 text-sm mb-6">
                            <span class="text-gray-600 dark:text-gray-400">Dijual oleh</span>
                            <a href="#" class="font-semibold text-gray-800 dark:text-gray-200 hover:text-orange-600 hover:underline transition">
                                {{ $produk->toko->nama_toko }}
                            </a>
                            <span class="text-gray-500 dark:text-gray-400">• {{ $produk->toko->provinsi->nama_provinsi }}</span>
                        </div>

                        <!-- Price -->
                        <p class="text-4xl font-extrabold text-orange-600 dark:text-orange-400 mb-8">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </p>

                        <!-- Short Description -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Deskripsi Singkat</h2>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $produk->deskripsi_singkat }}
                            </p>
                        </div>

                        <!-- Product Details -->
                        <div class="mb-10">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Detail Produk</h2>
                            <div class="grid grid-cols-2 gap-6 pt-3">

                                <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Stok</span>
                                    <p class="text-xl font-semibold mt-1 
                                        @if($produk->stok <= 0) text-red-600 dark:text-red-400 @else text-gray-900 dark:text-gray-100 @endif">
                                        @if($produk->stok > 0)
                                            {{ $produk->stok }} pcs
                                        @else
                                            Habis
                                        @endif
                                    </p>
                                </div>

                                <div class="p-4 bg-gray-50 dark:bg-gray-800/60 rounded-lg border dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Berat</span>
                                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-1">
                                        {{ $produk->berat_gram }} gram
                                    </p>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6">

                        @if($produk->stok > 0)

                            <form action="{{ route('cart.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <input type="hidden" name="produk_slug" value="{{ $produk->slug }}">

                                <div class="flex items-center space-x-4">
                                    <x-input-label for="quantity" value="Jumlah:" class="text-lg" />
                                    <x-text-input 
                                        id="quantity" 
                                        name="quantity" 
                                        type="number" 
                                        value="1" 
                                        min="1" 
                                        max="{{ $produk->stok }}" 
                                        class="block w-24"
                                        required
                                    />
                                </div>
                                <x-input-error :messages="$errors->get('quantity')" class="mt-1" />

                                <button class="w-full text-lg py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg shadow transition">
                                    + Tambah ke Keranjang
                                </button>
                            </form>

                        @else

                            <button class="w-full text-lg py-3 bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>

                        @endif

                        {{-- Guest login reminder --}}
                        @guest
                            @if($produk->stok > 0)
                            <p class="text-center mt-3 text-sm text-gray-500 dark:text-gray-400">
                                Silakan <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:underline">login</a> untuk membeli.
                            </p>
                            @endif
                        @endguest

                        {{-- Penjual/Admin restriction --}}
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

            <!-- FULL DESCRIPTION SECTION -->
            @if ($produk->deskripsi_lengkap)
            <div class="mt-16 pt-8 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Deskripsi Lengkap</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                    {!! nl2br(e($produk->deskripsi_lengkap)) !!}
                </div>
            </div>
            @endif

            {{-- RELATED PRODUCTS (CATEGORY) --}}
            @php
                $relatedProduk = \App\Models\Produk::where('kategori_id', $produk->kategori_id)
                    ->where('id', '!=', $produk->id)
                    ->latest()
                    ->take(6)
                    ->get();
            @endphp

            @if ($relatedProduk->count() > 0)
            <div class="mt-20 border-t border-gray-200 dark:border-gray-700 pt-12">

                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">
                    Produk Lainnya di Kategori: {{ $produk->kategori->nama_kategori }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8">

                    @foreach($relatedProduk as $item)
                    <a href="{{ route('produk.show', $item->slug) }}" 
                       class="group block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700/70 overflow-hidden shadow hover:shadow-xl hover:-translate-y-1 transform transition">

                        <div class="w-full aspect-square overflow-hidden">
                            <img src="{{ Storage::url($item->gambar_produk_utama) }}" 
                                 alt="{{ $item->nama_produk }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>

                        <div class="p-4 flex flex-col space-y-2">
                            
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 group-hover:text-orange-600 transition">
                                {{ $item->nama_produk }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->toko->nama_toko }} • {{ $item->toko->provinsi->nama_provinsi }}
                            </p>

                            <p class="text-orange-600 dark:text-orange-400 font-bold text-xl">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>

                            <div class="mt-4">
                                <span class="inline-block w-full text-center py-2 rounded-lg bg-orange-600 text-white font-medium hover:bg-orange-700 transition">
                                    Lihat Detail
                                </span>
                            </div>

                        </div>
                    </a>
                    @endforeach

                </div>

            </div>
            @endif

        </div>
    </div>

</x-app-layout>
