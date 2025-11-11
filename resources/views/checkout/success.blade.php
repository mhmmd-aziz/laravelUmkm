<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesanan Berhasil Dibuat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100 text-center">
                    
                    {{-- Ikon Sukses --}}
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Terima Kasih Atas Pesanan Anda!</h3>
                    
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Pesanan Anda telah berhasil kami terima dan sedang diproses. 
                        Jika Anda memilih metode pembayaran yang perlu konfirmasi (seperti transfer bank), status akan menjadi 'Diproses' setelah pembayaran kami terima.
                    </p>

                    {{-- Tampilkan Daftar Invoice ID --}}
                    @if(isset($invoice_ids) && !empty($invoice_ids))
                        <div class="mt-6 text-left bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <p class="font-semibold text-gray-800 dark:text-gray-200">Nomor Invoice Anda:</p>
                            <ul class="list-disc list-inside mt-2 text-sm text-gray-700 dark:text-gray-300">
                                @foreach ($invoice_ids as $invoiceId)
                                    <li>{{ $invoiceId }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-8 flex justify-center space-x-4">
                        <x-primary-button-link href="{{ route('pembeli.pesanan.index') }}">
                            {{ __('Lihat Riwayat Pesanan') }}
                        </x-primary-button-link>
                        <x-secondary-button-link href="{{ route('home') }}">
                            {{ __('Kembali ke Beranda') }}
                        </x-secondary-button-link>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>