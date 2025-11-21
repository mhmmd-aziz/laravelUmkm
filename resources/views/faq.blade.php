<x-app-layout>

<section class="bg-gray-50 dark:bg-gray-900 py-24">
    <div class="max-w-[1290px] mx-auto px-6">

        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white">
                Frequently Asked Questions
            </h1>
            <p class="text-gray-600 dark:text-gray-300 mt-4 max-w-2xl mx-auto text-lg">
                Informasi lengkap mengenai RupaNusa—mulai dari belanja, keamanan produk hingga peluang menjadi penjual.
            </p>
        </div>

        <div class="space-y-6">

            @foreach([
                [
                    'Apa itu RupaNusa?',
                    'RupaNusa adalah platform e-commerce yang menghubungkan pembeli dengan UMKM lokal di seluruh Indonesia. Semua produk yang ditampilkan berasal dari pengrajin, produsen, dan pelaku usaha kecil yang telah melalui proses verifikasi. Tujuan kami adalah menghadirkan produk Nusantara berkualitas dengan pengalaman belanja yang aman, cepat, dan mudah.'
                ],
                [
                    'Bagaimana cara membeli produk?',
                    'Proses pembelian sangat sederhana. Pilih produk yang ingin Anda beli, klik “Tambah ke Keranjang”, lalu lanjutkan ke halaman checkout. Anda bisa memilih metode pembayaran seperti transfer bank, e-wallet, atau metode lain yang tersedia. Setelah pembayaran dikonfirmasi, pesanan Anda akan segera diproses oleh penjual dan dapat dilacak melalui dashboard akun.'
                ],
                [
                    'Apakah produk di RupaNusa asli?',
                    'Ya, setiap produk yang dijual telah melalui tahap kurasi dan verifikasi dokumen usaha. Kami hanya bekerja sama dengan UMKM resmi yang memiliki identitas dan legalitas jelas. Selain itu, tim RupaNusa secara berkala melakukan pengecekan kualitas untuk memastikan produk yang dikirimkan sesuai dengan deskripsi.'
                ],
                [
                    'Bagaimana cara menjadi penjual?',
                    'Untuk menjadi penjual, cukup daftar akun Penjual di RupaNusa. Isi data usaha Anda seperti nama toko, alamat, dan kontak. Setelah itu, unggah produk dengan foto, deskripsi, dan harga yang sesuai. Tim kami akan melakukan review cepat, dan setelah disetujui, toko Anda langsung tampil di platform. Anda bisa mulai menerima pesanan dan mengelola penjualan melalui dashboard yang mudah diakses.'
                ],
            ] as $faq)

            <div 
                x-data="{ open: false }"
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm transition hover:shadow-md"
            >

                <button
                    @click="open = !open"
                    class="w-full flex justify-between items-center px-6 py-5 text-left"
                >
                    <span class="font-semibold text-gray-900 dark:text-gray-100 text-xl">
                        {{ $faq[0] }}
                    </span>

                    <span class="text-gray-500 dark:text-gray-300 text-2xl leading-none">
                        <span x-show="!open">+</span>
                        <span x-show="open">−</span>
                    </span>
                </button>

                <div 
                    x-show="open"
                    x-transition
                    class="px-6 pb-6 text-gray-700 dark:text-gray-300 text-lg leading-relaxed"
                >
                    {{ $faq[1] }}
                </div>

            </div>

            @endforeach

        </div>

    </div>
</section>

</x-app-layout>
