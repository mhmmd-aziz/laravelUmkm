{{-- INI ADALAH PERBAIKAN: Ganti x-guest-layout menjadi x-app-layout --}}
<x-app-layout>
    
    {{-- Slot header untuk judul halaman --}}

 <x-hero/>
 <x-label />
<x-kategori :items="[
    ['icon' => '/icons/batik.png'],
    ['icon' => '/icons/anyaman.png'],
    ['icon' => '/icons/ukiran.png'],
    ['icon' => '/icons/perhiasan.png'],
    ['icon' => '/icons/tenun.png'],
    ['icon' => '/icons/keramik.png'],
    ['icon' => '/icons/wayang.png'],
    ['icon' => '/icons/makanan.png'],
]" />

  <x-category :produks="$produks" />



    <div class="bg-white pb-12" id="produk">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 " >
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 mb-6 " >
        Semua Produk
    </h1>

<!-- GRID UTAMA -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-10" >
    
    <!-- FILTER (LEFT SIDEBAR) -->
    <aside class="md:col-span-1">

        <div class="bg-white/80 dark:bg-gray-800/60 backdrop-blur-sm border border-gray-200 dark:border-gray-700 shadow-lg rounded-2xl p-6 sticky top-28">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 tracking-tight">
                Filter Produk
            </h3>

            <form action="{{ route('home') }}" method="GET" class="space-y-6">

                <!-- PENCARIAN -->
                <div>
                    <x-input-label for="search" :value="__('Cari Produk')" class="font-medium text-gray-700 dark:text-gray-300" />
                    <x-text-input id="search" name="search" type="text"
                        class="mt-2 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 
                               rounded-xl focus:border-orange-600 focus:ring-orange-600"
                        :value="request('search')"
                        placeholder="Cari produk terbaik..." />
                </div>

                <!-- KATEGORI -->
                <div>
                    <x-input-label for="kategori_id" :value="__('Kategori')" class="font-medium text-gray-700 dark:text-gray-300" />
                    <select id="kategori_id" name="kategori_id"
                        class="mt-2 block w-full border-gray-300 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 
                               focus:border-orange-600 focus:ring-orange-600 rounded-xl shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PROVINSI -->
                <div>
                    <x-input-label for="provinsi_id" :value="__('Provinsi')" class="font-medium text-gray-700 dark:text-gray-300" />
                    <select id="provinsi_id" name="provinsi_id"
                        class="mt-2 block w-full border-gray-300 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 
                               focus:border-orange-600 focus:ring-orange-600 rounded-xl shadow-sm">
                        <option value="">Semua Provinsi</option>
                        @foreach ($provinsis as $provinsi)
                            <option value="{{ $provinsi->id }}" {{ request('provinsi_id') == $provinsi->id ? 'selected' : '' }}>
                                {{ $provinsi->nama_provinsi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- TOMBOL -->
                <div class="flex flex-col gap-3 pt-2">
                    <button
                        class="w-full justify-center text-base py-3 rounded-xl shadow-md bg-orange-600 hover:bg-orange-700 text-white transition">
                        Terapkan Filter
                    </button>

                    <a href="{{ route('home') }}"
                       class="text-center w-full py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 
                              rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                       Reset
                    </a>
                </div>

            </form>
        </div>

    </aside>

    <!-- PRODUK (RIGHT CONTENT) -->
    <main class="md:col-span-3">

        @if ($produks->isEmpty())

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-2xl p-10 text-center">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Tidak ada produk ditemukan</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    Coba ubah filter atau
                    <a href="{{ route('home') }}" class="text-orange-600 font-medium hover:underline">
                        reset filter
                    </a>.
                </p>
            </div>

        @else

            <!-- GRID PRODUK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                @foreach ($produks as $produk)
                    <a href="{{ route('produk.show', $produk->slug) }}"
                       class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/70 rounded-2xl overflow-hidden shadow-sm 
                              hover:shadow-xl hover:-translate-y-1 transform transition duration-300 block">

                        <!-- GAMBAR -->
                        <div class="h-56 bg-gray-50 dark:bg-gray-700 overflow-hidden">
                            <img src="{{ Storage::url($produk->gambar_produk_utama) }}"
                                 alt="{{ $produk->nama_produk }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>

                        <!-- DETAIL -->
                        <div class="p-5">

                            <!-- TOKO -->
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                <span class="truncate">{{ $produk->toko->nama_toko }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $produk->toko->provinsi->nama_provinsi }}</span>
                            </div>

                            <!-- NAMA -->
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 leading-tight line-clamp-2 group-hover:text-orange-600 transition">
                                {{ $produk->nama_produk }}
                            </h3>

                            <!-- HARGA -->
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-4">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </p>

                        </div>
                    </a>
                @endforeach

            </div>

            <!-- PAGINATION -->
            <div class="mt-12">
                {{ $produks->links() }}
            </div>

        @endif

    </main>
</div>



        </div>
    </div>



</x-app-layout>
{{-- AKHIRI DENGAN x-app-layout --}}