@props(['produks'])

<div class="bg-white dark:bg-gray-900">
  <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:pt-24 lg:max-w-7xl lg:px-8">

    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100 mb-6">
        Produk Terbaru
    </h2>

    <!-- GRID PRODUK -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

      @foreach ($produks as $produk)
      <a href="{{ route('produk.show', $produk->slug) }}"
         class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/70 rounded-2xl overflow-hidden shadow 
                hover:shadow-xl hover:-translate-y-1 transform transition duration-300 block group">

        <!-- GAMBAR -->
        <div class="h-64 bg-gray-100 dark:bg-gray-700 overflow-hidden">
            <img src="{{ Storage::url($produk->gambar_produk_utama) }}"
                 alt="{{ $produk->nama_produk }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        </div>

        <!-- DETAIL -->
        <div class="p-4">

          <!-- TOKO + PROVINSI -->
          <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-1">
              <span class="truncate">{{ $produk->toko->nama_toko }}</span>
              <span class="mx-1">•</span>
              <span>{{ $produk->toko->provinsi->nama_provinsi }}</span>
          </div>

          <!-- NAMA PRODUK -->
          <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 leading-tight line-clamp-2 group-hover:text-orange-600 transition">
              {{ $produk->nama_produk }}
          </h3>

          <!-- HARGA -->
          <p class="text-xl font-bold text-orange-600 dark:text-orange-400 mt-3">
            Rp {{ number_format($produk->harga, 0, ',', '.') }}
          </p>

        </div>

      </a>
      @endforeach

    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $produks->links() }}
    </div>

  </div>
</div>
