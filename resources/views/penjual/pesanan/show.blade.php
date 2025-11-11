<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Pesanan: ') }} {{ $transaksi->invoice_id }}
            </h2>
            <x-secondary-button-link href="{{ route('penjual.pesanan.index') }}">
                &larr; Kembali ke Daftar Pesanan
            </x-secondary-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Pesan Error --}}
            @if (session('error'))
                 <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri (Detail & Update) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Detail Item Pesanan -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Item Pesanan
                            </h3>
                            <div class="space-y-4">
                                {{-- INI DIA PERBAIKANNYA ($transaksi->detailTransaksis) --}}
                                @foreach ($transaksi->detailTransaksis as $detail)
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ Storage::url($detail->produk->gambar_produk_utama) }}" alt="{{ $detail->produk->nama_produk }}" class="w-20 h-20 rounded-md object-cover">
                                        <div class="flex-1 text-sm">
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $detail->produk->nama_produk }}</p>
                                            <p class="text-gray-600 dark:text-gray-400">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Total -->
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Harga Barang: <span class="font-semibold text-lg text-gray-900 dark:text-gray-100">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ongkos Kirim: <span class="font-semibold text-lg text-gray-900 dark:text-gray-100">Rp {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span></p>
                                <p class="text-lg text-gray-600 dark:text-gray-400">Total Tagihan: <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Update Status -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Update Status Pesanan
                            </h3>
                            <form action="{{ route('penjual.pesanan.updateStatus', $transaksi) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-4">
                                    <div>
                                        {{-- INI PERBAIKANNYA (status_transaksi) --}}
                                        <x-input-label for="status_transaksi" :value="__('Status Pesanan')" />
                                        <select id="status_transaksi" name="status_transaksi" class="block mt-1 w-full md:w-1/2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="menunggu_pembayaran" {{ $transaksi->status_transaksi == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                            <option value="diproses" {{ $transaksi->status_transaksi == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="dikemas" {{ $transaksi->status_transaksi == 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                                            <option value="dikirim" {{ $transaksi->status_transaksi == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="selesai" {{ $transaksi->status_transaksi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="dibatalkan" {{ $transaksi->status_transaksi == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                        {{-- INI PERBAIKANNYA (status_transaksi) --}}
                                        <x-input-error :messages="$errors->get('status_transaksi')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="nomor_resi" :value="__('Nomor Resi (Jika dikirim)')" />
                                        {{-- INI PERBAIKANNYA (status_transaksi) --}}
                                        <x-text-input id="nomor_resi" class="block mt-1 w-full md:w-1/2" type="text" name="nomor_resi" :value="old('nomor_resi', $transaksi->nomor_resi ?? '')" placeholder="Masukkan nomor resi..." />
                                        <x-input-error :messages="$errors->get('nomor_resi')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-primary-button>
                                            {{ __('Update Status') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan (Alamat & Info) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Detail Pembeli & Alamat -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Detail Pengiriman
                            </h3>
                            
                            {{-- Tombol Print Alamat --}}
                            <div class="mb-4">
                                {{-- Script JS sederhana untuk print bagian alamat --}}
                                <x-secondary-button onclick="printAlamat()">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6 18.25m.72-4.421c.168.354.36.69.59 1.006m-.59-1.006L9.75 12.5m0-1.5L6.72 13.829m0 0L6 18.25m.72-4.421l.48.849m-1.08-1.849L6.75 10.5m0 1.5l3 3m0 0l3-3m-3 3v-3m0 0l-3-3m3 3l3 3" /></svg>
                                    Print Label Alamat
                                </x-secondary-button>
                            </div>

                            {{-- Area Cetak (Label Kirim) --}}
                            <div id="label-pengiriman" class="border border-gray-400 dark:border-gray-600 border-dashed p-4 rounded-lg">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">KEPADA:</h4>
                                <p class="mt-1 text-base text-gray-800 dark:text-gray-200 font-semibold">{{ $transaksi->alamat_pengiriman['nama_penerima'] }}</p>
                                <p class="text-base text-gray-800 dark:text-gray-200">{{ $transaksi->alamat_pengiriman['nomor_telepon'] }}</p>
                                <p class="mt-2 text-base text-gray-800 dark:text-gray-200">
                                    {{ $transaksi->alamat_pengiriman['alamat_lengkap'] }}<br>
                                    {{ $transaksi->alamat_pengiriman['kecamatan'] }}, {{ $transaksi->alamat_pengiriman['kota_kabupaten'] }}<br>
                                    {{ $transaksi->alamat_pengiriman['provinsi'] }}, {{ $transaksi->alamat_pengiriman['kode_pos'] }}
                                </p>
                                <hr class="my-3 border-gray-400 dark:border-gray-600 border-dashed">
                                <p class="text-sm text-gray-700 dark:text-gray-300">DARI: <span class="font-semibold">{{ $transaksi->toko->nama_toko }}</span></p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $transaksi->toko->nomor_telepon }}</p>
                            </div>

                            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                <p><span class="font-medium">Pembeli:</span> {{ $transaksi->user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $transaksi->user->email }}</p>
                                <p><span class="font-medium">Metode Pembayaran:</span> {{ Str::title(str_replace('_', ' ', $transaksi->metode_pembayaran)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Print --}}
    @push('scripts')
    <script>
        function printAlamat() {
            const labelElement = document.getElementById('label-pengiriman');
            const printWindow = window.open('', '_blank', 'width=600,height=400');
            
            // Buat HTML baru untuk jendela print
            printWindow.document.write('<html><head><title>Cetak Label Pengiriman</title>');
            // Tambahkan style minimalis untuk print
            printWindow.document.write(`
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h4 { margin: 0; font-size: 16px; }
                    p { margin: 4px 0; font-size: 14px; }
                    hr { border: 0; border-top: 2px dashed #000; margin: 10px 0; }
                </style>
            `);
            printWindow.document.write('</head><body>');
            
            // Salin HTML dari label
            printWindow.document.write(labelElement.innerHTML);
            
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            // Tunggu sedikit agar DOM di-load, lalu print
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
    </script>
    @endpush

</x-app-layout>