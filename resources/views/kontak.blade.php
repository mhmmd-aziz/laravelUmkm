<x-app-layout>

<!-- SWEETALERT2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<section class="bg-white dark:bg-gray-900 py-24">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <!-- HEADER -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white">
                Hubungi <span class="text-orange-600">RupaNusa</span>
            </h1>
            <p class="mt-4 text-gray-600 dark:text-gray-300 max-w-2xl mx-auto text-lg">
                Kami selalu siap membantu Anda terkait layanan, kemitraan, maupun pertanyaan lainnya.
            </p>
        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- FORM -->
            <div class="bg-white dark:bg-gray-800 p-10 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">
                    Kirimkan Pesan Anda
                </h2>

                <form method="POST" action="{{ route('kontak.send') }}">
                    @csrf

                    <div class="mb-6">
                        <x-input-label value="Nama Lengkap" />
                        <input name="nama" type="text"
                               class="w-full mt-2 border-gray-300 dark:bg-gray-900 dark:border-gray-700 rounded-xl focus:ring-orange-600 focus:border-orange-600"
                               placeholder="Masukkan nama Anda" required>
                    </div>

                    <div class="mb-6">
                        <x-input-label value="Email" />
                        <input name="email" type="email"
                               class="w-full mt-2 border-gray-300 dark:bg-gray-900 dark:border-gray-700 rounded-xl focus:ring-orange-600 focus:border-orange-600"
                               placeholder="Alamat email" required>
                    </div>

                    <div class="mb-6">
                        <x-input-label value="Pesan" />
                        <textarea name="pesan"
                                  class="w-full h-36 mt-2 border-gray-300 dark:bg-gray-900 dark:border-gray-700 rounded-xl focus:ring-orange-600 focus:border-orange-600"
                                  placeholder="Ketik pesan Anda" required></textarea>
                    </div>

                    <button class="w-full bg-orange-600 text-white py-3 rounded-xl hover:bg-orange-700 shadow-lg transition">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- CONTACT INFO -->
            <div class="space-y-10">

                <div class="p-10 bg-gray-50 dark:bg-gray-800 rounded-3xl shadow-lg border dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Alamat</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg">Lhokseumawe, Aceh, Indonesia</p>
                </div>

                <div class="p-10 bg-gray-50 dark:bg-gray-800 rounded-3xl shadow-lg border dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Email</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg">support@rupanusa.id</p>
                </div>

                <div class="p-10 bg-gray-50 dark:bg-gray-800 rounded-3xl shadow-lg border dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Telepon</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg">+62 812-3456-7890</p>
                </div>

            </div>

        </div>

        <!-- GOOGLE MAP -->
        <div class="mt-20">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                Lokasi Kami di Google Maps
            </h2>

            <div class="rounded-3xl overflow-hidden shadow-xl border border-gray-200 dark:border-gray-700">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3974.084240689543!2d97.146!3d5.180!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3047774d751f2e6b%3A0xdee96dbb8d1fef3!2sLhokseumawe%2C%20Aceh!5e0!3m2!1sen!2sid!4v1700000000000"
                    width="100%" height="420" style="border:0;"
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>

    </div>
</section>

<!-- SWEETALERT SUCCESS -->
@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session("success") }}',
    confirmButtonColor: '#ea580c',
});
</script>
@endif

<!-- SWEETALERT ERROR -->
@if (session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal Mengirim!',
    text: '{{ session("error") }}',
    confirmButtonColor: '#ea580c',
});
</script>
@endif

</x-app-layout>
