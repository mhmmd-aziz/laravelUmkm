<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pesanan: ') }} {{ $transaksi->invoice_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                 <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Kolom Kiri: Detail Pesanan & Alamat -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Detail Item -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Detail Item Dipesan
                            </h3>
                            <div class="space-y-4">
                                @foreach ($transaksi->details as $detail)
                                <div class="flex items-center space-x-4 p-4 border-b border-gray-200 dark:border-gray-700">
                                    <img src="{{ Storage::url($detail->produk->gambar_produk_utama) }}" alt="{{ $detail->produk->nama_produk }}" class="w-20 h-20 rounded-md object-cover">
                                    <div class="flex-1 text-sm">
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $detail->produk->nama_produk }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-right">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Harga Barang:</span>
                                <span class="text-xl font-bold text-gray-900 dark:text-gray-100 ml-2">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Alamat Pengiriman (Printable)
                            </h3>
                            @php
                                $alamat = $transaksi->alamat_pengiriman_json;
                            @endphp
                            <div class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg relative">
                                {{-- Tombol Print (Sesuai permintaan Anda) --}}
                                <button onclick="printAlamat()" class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" title="Cetak Alamat">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6 13.5m0 0l-.675.325M6.72 13.829m10.56 0c.24.03.48.062.72.096m-.72-.096L18 13.5m0 0l.675.325M6.72 13.829M18 13.5m-1.125.175L15 14.75l-1.125-.175m-4.5 0L9 14.75l-1.125-.175M18 13.5m-1.125.175l-4.5 0m4.5 0l-1.125 1.063M18 13.5m0 0l-1.125 1.063m0 0l-1.125-.175m-4.5 0L9 15.813l-1.125-.175m0 0l-1.125 1.063M15 14.75l-1.125-.175m-4.5 0L9 14.75l-1.125-.175M9 14.75l1.125 1.063M9 14.75l-1.125 1.063M15 14.75l1.125 1.063m0 0l1.125-.175m-4.5 0l1.125 1.063m-4.5 0l1.125 1.063M9 15.813l1.125-.175m0 0l-1.125.175M15 15.813l-1.125-.175m0 0l1.125.175M9 15.813l-1.125.175M15 15.813l1.125.175M10.125 17.438l1.125-.175m-2.25 0l1.125.175M13.875 17.438l-1.125-.175m2.25 0l-1.125.175M4.875 17.438l1.125.175M19.125 17.438l-1.125.175M6 13.5m0 0l-1.125.175M18 13.5m0 0l1.125.175M6 13.5m0 0L4.875 15m1.125-1.5L6 13.5m0 0l-1.125.175m2.25-1.35l-1.125.175M18 13.5m0 0l1.125.175m-2.25-1.35l1.125.175M4.875 15L6 13.5m0 0l1.125 1.5M19.125 15L18 13.5m0 0l-1.125 1.5" />
                                    </svg>
                                </button>
                                <div id="alamat-print-area">
                                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $alamat['nama_penerima'] }}</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $alamat['nomor_telepon'] }}</p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $alamat['alamat_lengkap'] }}</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $alamat['kecamatan'] }}, {{ $alamat['kota_kabupaten'] }}</S>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $alamat['provinsi'] }}, {{ $alamat['kode_pos'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Status & Aksi -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Status Pesanan
                            </h3>
                            
                            <div class="mb-4">
                                <span class="px-3 py-1 text-sm font-medium rounded-full 
                                    @if($transaksi->status_pesanan == 'menunggu_pembayaran') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($transaksi->status_pesanan == 'dikemas') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($transaksi->status_pesanan == 'dikirim') bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300
                                    @elseif($transaksi->status_pesanan == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $transaksi->status_pesanan)) }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Metode Pembayaran:</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ ucwords(str_replace('_', ' ', $transaksi->metode_pembayaran)) }}</p>
                            
                            {{-- TODO: Tambahkan info upload bukti bayar di sini --}}

                            <hr class="border-gray-200 dark:border-gray-700 mb-4">

                            {{-- Form Update Status --}}
                            <form action="{{ route('penjual.pesanan.updateStatus', $transaksi) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <x-input-label for="status_pesanan" :value="__('Ubah Status Pesanan')" />
                                    <select id="status_pesanan" name="status_pesanan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        {{-- Logika status: hanya bisa maju, tidak bisa mundur --}}
                                        <option value="menunggu_pembayaran" @if($transaksi->status_pesanan == 'menunggu_pembayaran') selected @endif>Menunggu Pembayaran</option>
                                        <option value="dikemas" @if($transaksi->status_pesanan == 'dikemas') selected @endif>Dikemas</option>
                                        <option value="dikirim" @if($transaksi->status_pesanan == 'dikirim') selected @endif>Dikirim</option>
                                        <option value="selesai" @if($transaksi->status_pesanan == 'selesai') selected @endif>Selesai</option>
                                        <option value="dibatalkan" @if($transaksi->status_pesanan == 'dibatalkan') selected @endif>Dibatalkan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status_pesanan')" class="mt-2" />
                                </div>

                                {{-- TODO: Jika status 'dikirim', minta Nomor Resi --}}
                                
                                <x-primary-button class="mt-4 w-full justify-center">
                                    Perbarui Status
                                </x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Print (Sesuai permintaan Anda) --}}
    <script>
        function printAlamat() {
            var printContents = document.getElementById('alamat-print-area').innerHTML;
            
            // Buat iframe sementara untuk print
            var iframe = document.createElement('iframe');
            iframe.style.height = '0';
            iframe.style.width = '0';
            iframe.style.visibility = 'hidden';
            iframe.style.position = 'absolute';
            document.body.appendChild(iframe);
            
            var iframeDoc = iframe.contentWindow.document;
            iframeDoc.open();
            // Tambahkan style minimalis untuk print
            iframeDoc.write('<html><head><title>Cetak Alamat</title>');
            // Style yang lebih rapi untuk cetak label pengiriman
            iframeDoc.write('<style>' +
                'body { font-family: Arial, sans-serif; margin: 0; padding: 10px; } ' +
                '.label-container { border: 2px dashed #000; padding: 15px; width: 90%; max-width: 400px; } ' +
                '.label-header { font-size: 1.2rem; font-weight: bold; margin-bottom: 10px; } ' +
                '.label-content { margin-top: 10px; } ' +
                '.label-content p { margin: 4px 0; font-size: 0.9rem; } ' +
                '.label-content p.nama { font-size: 1.1rem; font-weight: bold; } ' +
                '.label-footer { margin-top: 15px; font-weight: bold; } ' +
                '</style>');
            iframeDoc.write('</head><body>');
            
            // Ambil data dari elemen
            var nama = document.querySelector('#alamat-print-area .text-lg.font-bold').innerText;
            var telepon = document.querySelector('#alamat-print-area .text-gray-700.dark\\:text-gray-300:nth-of-type(1)').innerText;
            var alamatLengkap = document.querySelector('#alamat-print-area .text-gray-700.dark\\:text-gray-300.mt-2').innerText;
            var kecamatanKota = document.querySelector('#alamat-print-area .text-gray-700.dark\\:text-gray-300:nth-of-type(3)').innerText;
            var provinsiPos = document.querySelector('#alamat-print-area .text-gray-700.dark\\:text-gray-300:nth-of-type(4)').innerText;

            // Format label pengiriman
            iframeDoc.write('<div class="label-container">');
            iframeDoc.write('<div class="label-header">Kepada Yth:</div>');
            iframeDoc.write('<div class="label-content">');
            iframeDoc.write('<p class="nama">' + nama + '</p>');
            iframeDoc.write('<p>' + telepon + '</p>');
            iframeDoc.write('<p>' + alamatLengkap + '</p>');
            iframeDoc.write('<p>' + kecamatanKota + '</p>');
            iframeDoc.write('<p>' + provinsiPos + '</p>');
            iframeDoc.write('</div>');
            // Tambahkan info Pengirim (Toko Anda)
            @php 
                $toko = Auth::user()->toko;
            @endphp
            iframeDoc.write('<div class="label-footer">');
            iframeDoc.write('<p>Dari: {{ $toko->nama_toko }}</p>');
            iframeDoc.write('<p>({{ $toko->provinsi->nama_provinsi }})</p>');
            iframeDoc.write('</div>');

            iframeDoc.write('</div>');
            
            iframeDoc.write('</body></html>');
            iframeDoc.close();
            
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            
            // Hapus iframe setelah print
            setTimeout(() => { document.body.removeChild(iframe); }, 1000);
        }
    </script>
</x-app-layout>
