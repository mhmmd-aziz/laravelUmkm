<x-app-layout>

    <!-- PAGE TITLE -->
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Pengaturan Profil
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Kelola informasi pribadi, alamat, dan keamanan akun Anda.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-6 py-12 space-y-12">

        {{-- SUCCESS & ERROR ALERT --}}
        @if (session('success'))
            <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl shadow">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl shadow">
                {{ session('error') }}
            </div>
        @endif

        {{-- ============================================
              UPDATE PROFILE INFORMATION
        ============================================ --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Informasi Profil
            </h2>

            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- ============================================
              BUKU ALAMAT PEMBELI
        ============================================ --}}
        @if (Auth::user()->role === 'pembeli')
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Buku Alamat</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Kelola alamat pengiriman Anda.
                    </p>
                </div>

                <a href="{{ route('pembeli.alamat.create') }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow">
                    + Tambah Alamat
                </a>
            </div>

            <div class="space-y-4">

                @forelse ($alamats as $alamat)
                    <div class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <div class="flex justify-between items-start">

                            <div>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $alamat->label_alamat }}
                                </p>

                                <p class="text-gray-700 dark:text-gray-300 mt-1 leading-relaxed text-sm">
                                    <strong>{{ $alamat->nama_penerima }}</strong> ({{ $alamat->nomor_telepon }}) <br>
                                    {{ $alamat->alamat_lengkap }} <br>
                                    {{ $alamat->kecamatan }}, {{ $alamat->kota_kabupaten }} <br>
                                    {{ $alamat->provinsi }}, {{ $alamat->kode_pos }}
                                </p>
                            </div>

                            <div class="flex gap-3 text-sm">

                                <a href="{{ route('pembeli.alamat.edit', $alamat) }}"
                                    class="text-indigo-600 hover:underline dark:text-indigo-400">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('pembeli.alamat.destroy', $alamat) }}"
                                      onsubmit="return confirm('Hapus alamat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:underline dark:text-red-400">
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada alamat tersimpan.</p>
                @endforelse

            </div>

        </div>
        @endif

        {{-- ============================================
              UPDATE PASSWORD
        ============================================ --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Ubah Password
            </h2>

            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- ============================================
              DELETE ACCOUNT
        ============================================ --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow border border-red-300 dark:border-red-700 p-8">
            <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-4">
                Hapus Akun
            </h2>

            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>

</x-app-layout>
