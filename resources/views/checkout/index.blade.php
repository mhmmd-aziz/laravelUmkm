<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout Pesanan') }}
        </h2>
    </x-slot>

    {{-- 
      Script untuk Midtrans Snap. 
      PENTING: Gunakan URL sandbox, BUKAN production, untuk tes.
    --}}
    @push('scripts')
        <script type="text/javascript"
                src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tampilkan error validasi (jika ada) --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 
              Form ini TIDAK AKAN di-submit. 
              Kita hanya butuh data 'alamat_id' dan 'snap_token' untuk JavaScript.
            --}}
            <form id="checkout-form">
                
                {{-- Simpan Snap Token di input tersembunyi --}}
                <input type="hidden" id="snap-token" value="{{ $snapToken }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Kolom Kiri (Alamat & Item) -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Pilihan Alamat -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Pilih Alamat Pengiriman
                                </h3>
                                <div class="space-y-4">
                                   @forelse ($alamats as $alamat)
    <div 
        class="flex items-start p-4 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition"
        onclick="document.getElementById('alamat-{{ $alamat->id }}').checked = true"
    >
        <input 
            type="radio" 
            id="alamat-{{ $alamat->id }}" 
            name="alamat_id" 
            value="{{ $alamat->id }}" 
            class="mt-1 h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
            required 
            {{ $loop->first ? 'checked' : '' }}
        >

        <div class="ml-3 text-sm flex-1">
            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $alamat->label_alamat }}</p>
            <div class="text-gray-800 dark:text-gray-200 mt-1">
                <p class="font-medium">{{ $alamat->nama_penerima }} ({{ $alamat->nomor_telepon }})</p>
                <p>{{ $alamat->alamat_lengkap }}, {{ $alamat->kecamatan }}, {{ $alamat->kota_kabupaten }}, {{ $alamat->provinsi }}, {{ $alamat->kode_pos }}</p>
            </div>
        </div>
    </div>
@empty

                                        <p class="text-gray-600 dark:text-gray-400">
                                            Anda belum memiliki alamat. 
                                            <a href="{{ route('profile.edit') }}" class="text-indigo-600 hover:underline">Silakan tambah alamat di profil Anda.</a>
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Ringkasan Item -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Ringkasan Pesanan
                                </h3>
                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($cartItems as $item)
                                        <div class="py-4 flex items-center space-x-4">
                                            <img src="{{ Storage::url($item->attributes->image) }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-lg object-cover">
                                            <div class="flex-1 text-sm">
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $item->name }}</d>
                                                <p class="text-gray-600 dark:text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                <p class="text-gray-500 dark:text-gray-500">Toko: {{ $item->attributes->toko_nama }}</p>
                                            </div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($item->getPriceSum(), 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Kolom Kanan (Total & Tombol Bayar) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg sticky top-24">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Total Tagihan
                                </h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal Produk</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">Rp 0</span> {{-- TODO: Integrasi RajaOngkir --}}
                                    </div>
                                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-baseline">
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Total</span>
                                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    {{-- Tombol ini akan memicu Snap.js --}}
                                    <x-primary-button id="pay-button" class="w-full text-lg py-3 justify-center">
                                        {{ __('Bayar Sekarang (via Midtrans)') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
            
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            var payButton = document.getElementById('pay-button');
            var snapToken = document.getElementById('snap-token').value;
            var checkoutForm = document.getElementById('checkout-form');

            payButton.addEventListener('click', function (event) {
                event.preventDefault(); // Hentikan aksi default (submit form)

                // 1. Ambil alamat_id yang dipilih
                var selectedAlamat = document.querySelector('input[name="alamat_id"]:checked');
                
                if (!selectedAlamat) {
                    alert('Silakan pilih alamat pengiriman terlebih dahulu.');
                    return;
                }
                var alamatId = selectedAlamat.value;

                // 2. Ambil Snap Token (sudah ada di #snap-token)
                if (!snapToken) {
                    alert('Gagal mendapatkan token pembayaran. Silakan muat ulang halaman.');
                    return;
                }

                // 3. Tampilkan popup Midtrans
                window.snap.pay(snapToken, {
                    onSuccess: function(result){
                        /* Pembayaran sukses */
                        console.log('Payment Success:', result);
                        // Redirect ke halaman process, kirim hasil & alamat_id
                        window.location.href = '{{ route("pembeli.checkout.process") }}' + 
                                               '?order_id=' + result.order_id + 
                                               '&status_code=' + result.status_code + 
                                               '&transaction_id=' + result.transaction_id +
                                               '&alamat_id=' + alamatId; // Kirim alamat_id
                    },
                    onPending: function(result){
                        /* Pembayaran pending (cth: bayar di Indomaret) */
                        console.log('Payment Pending:', result);
                         // Redirect ke halaman process, kirim hasil & alamat_id
                        window.location.href = '{{ route("pembeli.checkout.process") }}' + 
                                               '?order_id=' + result.order_id + 
                                               '&status_code=' + result.status_code + 
                                               '&transaction_id=' + result.transaction_id +
                                               '&alamat_id=' + alamatId; // Kirim alamat_id
                    },
                    onError: function(result){
                        /* Pembayaran error */
                        console.error('Payment Error:', result);
                        alert('Pembayaran gagal. ' + (result.status_message || ''));
                    },
                    onClose: function(){
                        /* Popup ditutup tanpa menyelesaikan pembayaran */
                        console.log('Popup closed');
                        alert('Anda menutup jendela pembayaran sebelum selesai.');
                    }
                });
            });
        });
    </script>
    @endpush

</x-app-layout>