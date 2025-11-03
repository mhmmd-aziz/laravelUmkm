<x-guest-layout>
    <div class="bg-white dark:bg-gray-900 py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-8">
                Keranjang Belanja Anda
            </h1>

            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class.="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                @if (Cart::isEmpty())
                    <div class="p-8 text-center">
                        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300">Keranjang Anda kosong.</h2>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Ayo mulai jelajahi produk!</p>
                        <x-primary-button-link href="{{ route('home') }}" class="mt-6">
                            Kembali ke Homepage
                        </x-primary-button-link>
                    </div>
                @else
                    <!-- Daftar Item -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kuantitas
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Hapus</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach (Cart::getContent() as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-16 w-16">
                                                    <img class="h-16 w-16 rounded-md object-cover" src="{{ Storage::url($item->attributes->image) }}" alt="{{ $item->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        <a href="{{ route('produk.show', $item->attributes->slug) }}" class="hover:underline">{{ $item->name }}</a>
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->attributes->toko_nama }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Form Update Kuantitas --}}
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                @csrf
                                                <x-text-input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->attributes->stok_maks }}" class="w-20 text-sm" />
                                                <x-primary-button class="ml-2 text-xs">Update</x-primary-button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('cart.destroy', $item->id) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" onclick="return confirm('Hapus item ini dari keranjang?')">Hapus</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total & Checkout -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-700">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('cart.clear') }}" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('Kosongkan keranjang belanja?')">
                                Kosongkan Keranjang
                            </a>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    Total: Rp {{ number_format(Cart::getTotal(), 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    (Belum termasuk ongkos kirim)
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            {{-- --- PERUBAHAN DI SINI --- --}}
                            {{-- Hanya user 'pembeli' yang bisa checkout --}}
                            @auth
                                @if(Auth::user()->role === 'pembeli')
                                    <x-primary-button-link href="{{ route('checkout.index') }}" class="text-lg py-3 px-8">
                                        Lanjut ke Checkout
                                    </x-primary-button-link>
                                @else
                                    <p class="text-sm text-red-500 dark:text-red-400">Anda harus login sebagai Pembeli untuk Checkout.</p>
                                @endif
                            @endauth
                            @guest
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Silakan <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:underline">login sebagai Pembeli</a> untuk melanjutkan.
                                </p>
                            @endguest
                             {{-- --- BATAS PERUBAHAN --- --}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>

