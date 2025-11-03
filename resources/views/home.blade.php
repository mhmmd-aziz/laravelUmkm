<x-guest-layout> {{-- Ganti layout menjadi 'guest' agar tidak ada header dashboard --}}
    
    <div class="bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            
            <!-- Header Halaman -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-gray-100 sm:text-5xl">
                    Jelajahi Kriya & Budaya
                </h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                    Temukan produk UMKM kreatif unik dari seluruh penjuru Indonesia.
                </p>
                <p class="mt-2 text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                    "Membuka Masa Depan Budaya Indonesia"
                </p>
            </div>

            <!-- Form Filter -->
            <form method="GET" action="{{ route('home') }}" class="mb-12 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <!-- Filter Pencarian -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Produk</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: Batik Megamendung..." class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                        <select name="kategori" id="kategori" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Provinsi -->
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Semua Provinsi</option>
                            @foreach ($provinsis as $provinsi)
                                <option value="{{ $provinsi->id }}" {{ request('provinsi') == $provinsi->id ? 'selected' : '' }}>
                                    {{ $provinsi->nama_provinsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <x-secondary-button-link href="{{ route('home') }}">
                        Reset
                    </x-secondary-button-link>
                    <x-primary-button type="submit">
                        Terapkan Filter
                    </x-primary-button>
                </div>
            </form>

            <!-- Grid Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($produks as $produk)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                        <a href="{{ route('produk.show', $produk->slug) }}">
                            <img class="h-56 w-full object-cover" src="{{ Storage::url($produk->gambar_produk_utama) }}" alt="{{ $produk->nama_produk }}">
                        </a>
                        <div class="p-4">
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $produk->kategori->nama_kategori }}</span>
                            <h3 class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('produk.show', $produk->slug) }}">
                                    {{ $produk->nama_produk }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 truncate" title="{{ $produk->deskripsi_singkat }}">{{ $produk->deskripsi_singkat }}</p>
                            <p class="mt-2 text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </p>
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $produk->toko->nama_toko }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $produk->toko->provinsi->nama_provinsi }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <h3 class="text-2xl font-semibold text-gray-700 dark:text-gray-300">Tidak ada produk ditemukan</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Coba ubah filter pencarian Anda atau <a href="{{ route('home') }}" class="text-indigo-600 hover:underline">reset filter</a>.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="mt-12">
                {{-- Tampilkan pagination, appends() penting agar filter tetap ada --}}
                {{ $produks->links() }}
            </div>

        </div>
    </div>

</x-guest-layout>

