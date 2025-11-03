<x-app-layout> {{-- Harus login, jadi pakai app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Form utama yang membungkus semua --}}
            <form action="{{ route('pembeli.checkout.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Kolom Kiri: Alamat & Pembayaran (Lebar 2/3) -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- 1. Pilihan Alamat Pengiriman -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Pilih Alamat Pengiriman
                                </h3>
                                
                                <div class="space-y-4">
                                    @forelse ($alamats as $alamat)
                                        <label for="alamat_{{ $alamat->id }}" class="flex items-start p-4 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                            {{-- Gunakan Komponen Radio Button baru kita --}}
                                            <x-input-radio 
                                                id="alamat_{{ $alamat->id }}"
                                                name="alamat_id" 
                                                value="{{ $alamat->id }}" 
                                                :checked="$loop->first" {{-- Pilih alamat pertama sebagai default --}}
                                            />
                                            <div class="ml-4 text-sm flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $alamat->label_alamat }}</div>
                                                <div class="text-gray-800 dark:text-gray-200 mt-1">
                                                    <p class="font-medium">{{ $alamat->nama_penerima }} ({{ $alamat->nomor_telepon }})</p>
                                                    <p>{{ $alamat->alamat_lengkap }}</p>
                                                    <p>{{ $alamat->kecamatan }}, {{ $alamat->kota_kabupaten }}, {{ $alamat->provinsi }}, {{ $alamat->kode_pos }}</p>
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-gray-600 dark:text-gray-400">
                                            Anda belum punya alamat. 
                                            <a href="{{ route('pembeli.alamat.create') }}" class="text-indigo-600 hover:underline">Silakan tambah alamat baru</a>.
                                        </p>
                                    @endforelse
                                </div>
                                <x-input-error :messages="$errors->get('alamat_id')" class="mt-2" />

                                <x-primary-button-link href="{{ route('pembeli.alamat.create') }}" class="mt-4">
                                    + Tambah Alamat Baru
                                </x-primary-button-link>
                            </div>
                        </div>

                        <!-- 2. Metode Pembayaran (Placeholder) -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Pilih Metode Pembayaran
                                </h3>
                                <div class="space-y-4">
                                    {{-- Ini adalah placeholder, nanti kita ganti dengan Midtrans/Xendit --}}
                                    <label for="metode_transfer" class="flex items-start p-4 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <x-input-radio id="metode_transfer" name="metode_pembayaran" value="bank_transfer" checked />
                                        <div class="ml-4 text-sm flex-1">
                                            <span class="font-semibold text-gray-900 dark:text-gray-100">Bank Transfer (Manual)</span>
                                            <p class="text-gray-600 dark:text-gray-400">Anda akan menerima nomor rekening setelah membuat pesanan.</p>
                                        </div>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('metode_pembayaran')" class="mt-2" />
                            </div>
                        </div>
                        
                    </div>

                    <!-- Kolom Kanan: Ringkasan Pesanan (Lebar 1/3) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg sticky top-24"> {{-- Dibuat sticky --}}
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Ringkasan Pesanan
                                </h3>
                                
                                <div class="space-y-4 mb-4 border-b border-gray-200 dark:border-gray-700 pb-4">
                                    @foreach ($cartItems as $item)
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ Storage::url($item->attributes->image) }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-md object-cover">
                                            <div class="flex-1 text-sm">
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $item->name }}</p>
                                                <p class="text-gray-600 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Subtotal</span>
                                        <span class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                        <span>Ongkos Kirim</span>
                                        <span class="font-medium">Rp ???</span> {{-- TODO: Hitung Ongkir --}}
                                    </div>
                                    <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-gray-100">
                                        <span>Total</span>
                                        <span>Rp ???</span>
                                    </div>
                                </div>

                                <x-primary-button class="w-full text-lg py-3 justify-center" {{ $alamats->isEmpty() ? 'disabled' : '' }}>
                                    {{ $alamats->isEmpty() ? 'Harap Isi Alamat Dulu' : 'Buat Pesanan' }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>
