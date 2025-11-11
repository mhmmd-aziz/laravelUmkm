{{-- INI ADALAH PERBAIKAN: Ganti x-guest-layout menjadi x-app-layout --}}
<x-app-layout>
    
    {{-- Slot header untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Jelajahi Kriya & Budaya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Grid Utama (Filter di Kiri, Produk di Kanan) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                <!-- Kolom Filter (Kiri) -->
                <aside class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 sticky top-24"> {{-- Dibuat sticky --}}
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Filter Produk
                        </h3>
                        
                        <form action="{{ route('home') }}" method="GET">
                            <div class="space-y-4">
                                <!-- Filter Pencarian -->
                                <div>
                                    <x-input-label for="search" :value="__('Cari Produk')" />
                                    <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" 
                                                  :value="request('search')" 
                                                  placeholder="Contoh: Batik Megamendung..." />
                                </div>
                                
                                <!-- Filter Kategori -->
                                <div>
                                    <x-input-label for="kategori_id" :value="__('Kategori')" />
                                    <select id="kategori_id" name="kategori_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filter Provinsi -->
                                <div>
                                    <x-input-label for="provinsi_id" :value="__('Provinsi')" />
                                    <select id="provinsi_id" name="provinsi_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Semua Provinsi</option>
                                        @foreach ($provinsis as $provinsi)
                                            <option value="{{ $provinsi->id }}" {{ request('provinsi_id') == $provinsi->id ? 'selected' : '' }}>
                                                {{ $provinsi->nama_provinsi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tombol Filter -->
                                <div class="flex space-x-2 pt-2">
                                    <x-primary-button class="w-full justify-center">
                                        {{ __('Terapkan Filter') }}
                                    </x-primary-button>
                                    <x-secondary-button-link href="{{ route('home') }}" class="w-full justify-center">
                                        {{ __('Reset') }}
                                    </x-secondary-button-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </aside>

                <!-- Kolom Produk (Kanan) -->
                <main class="md:col-span-3">
                    
                    {{-- Pesan Jika Produk Kosong --}}
                    @if ($produks->isEmpty())
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-center">
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Tidak ada produk ditemukan</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">
                                Coba ubah filter pencarian Anda atau <a href="{{ route('home') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">reset filter</a>.
                            </p>
                        </div>
                    @else
                        
                        {{-- Grid untuk Card Produk --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($produks as $produk)
                                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                                    {{-- Link ke Halaman Detail Produk --}}
                                    <a href="{{ route('produk.show', $produk->slug) }}">
                                        {{-- Gambar Produk --}}
                                        <img src="{{ Storage::url($produk->gambar_produk_utama) }}" alt="{{ $produk->nama_produk }}" 
                                             class="w-full h-48 object-cover">
                                        
                                        <div class="p-4">
                                            {{-- Info Toko & Provinsi --}}
                                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-1">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21M6 3v1.875A2.625 2.625 0 008.625 7.5h6.75A2.625 2.625 0 0018 4.875V3M18 3A2.25 2.25 0 0015.75 0h-7.5A2.25 2.25 0 006 3v1.875A2.625 2.625 0 008.625 7.5h6.75A2.625 2.625 0 0018 4.875V3z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5c.621 0 1.125-.504 1.125-1.125V9.75A2.25 2.25 0 0020.25 7.5H3.75A2.25 2.25 0 001.5 9.75v10.125c0 .621.504 1.125 1.125 1.125z" />
                                                </svg>
                                                <span>{{ $produk->toko->nama_toko }}</span>
                                                <span class="mx-1">·</span>
                                                <span>{{ $produk->toko->provinsi->nama_provinsi }}</span>
                                            </div>
                                            
                                            {{-- Nama Produk --}}
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $produk->nama_produk }}">
                                                {{ $produk->nama_produk }}
                                            </h3>
                                            
                                            {{-- Harga --}}
                                            <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination Links --}}
                        <div class="mt-8">
                            {{ $produks->links() }}
                        </div>
                        
                    @endif
                </main>
            </div>

        </div>
    </div>

</x-app-layout>
{{-- AKHIRI DENGAN x-app-layout --}}