<x-app-layout>
    {{-- Slot header untuk judul halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Langkah Terakhir: Buat Toko Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Informasi Toko
                        </h2>
                
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Isi detail toko Anda untuk mulai menjual produk kriya dan budaya Anda.
                        </p>
                    </header>
                    
                    {{-- Tampilkan pesan warning jika ada (dari middleware EnsureTokoExists) --}}
                    @if (session('warning'))
                        <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-200 rounded-lg">
                            {{ session('warning') }}
                        </div>
                    @endif

                    {{-- Tampilkan pesan error jika validasi gagal (dari controller) --}}
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Form Aksi ke route 'penjual.store_toko' --}}
                    <form method="POST" action="{{ route('penjual.store_toko') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Nama Toko -->
                        <div>
                            <x-input-label for="nama_toko" :value="__('Nama Toko')" />
                            <x-text-input id="nama_toko" class="block mt-1 w-full" type="text" name="nama_toko" :value="old('nama_toko')" required autofocus autocomplete="organization" />
                            <x-input-error :messages="$errors->get('nama_toko')" class="mt-2" />
                        </div>

                        <!-- Provinsi -->
                        <div class="mt-4">
                            <x-input-label for="provinsi_id" :value="__('Lokasi Provinsi Toko')" />
                            
                            {{-- 
                                Ini adalah dropdown yang datanya diambil dari Controller.
                                Kita butuh Langkah 4 (Seeder) untuk mengisi data ini.
                            --}}
                            <select id="provinsi_id" name="provinsi_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Pilih Provinsi...</option>
                                {{-- Data akan diisi dari controller --}}
                                @isset($provinsis)
                                    @foreach ($provinsis as $provinsi)
                                        <option value="{{ $provinsi->id }}" {{ old('provinsi_id') == $provinsi->id ? 'selected' : '' }}>
                                            {{ $provinsi->nama_provinsi }}
                                        </option>
                                    @endforeach
                                @else
                                    {{-- Ini akan tampil jika $provinsis kosong (sebelum Langkah 4) --}}
                                    <option value="" disabled>Data provinsi belum tersedia.</option>
                                @endisset
                            </select>
                            <x-input-error :messages="$errors->get('provinsi_id')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Filter produk berdasarkan provinsi akan menggunakan data ini.</p>
                        </div>

                        <!-- Alamat Lengkap Toko -->
                        <div class="mt-4">
                            <x-input-label for="alamat_toko" :value="__('Alamat Lengkap Toko')" />
                            <textarea id="alamat_toko" name="alamat_toko" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required placeholder="Contoh: Jl. Merdeka No. 10, Kelurahan Cempaka, Kecamatan Suka Maju, Kota... Kode Pos 12345">{{ old('alamat_toko') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat_toko')" class="mt-2" />
                        </div>

                        <!-- Nomor Telepon Toko -->
                        <div class="mt-4">
                            <x-input-label for="nomor_telepon" :value="__('Nomor Telepon (WhatsApp)')" />
                            <x-text-input id="nomor_telepon" class="block mt-1 w-full" type="tel" name="nomor_telepon" :value="old('nomor_telepon')" required autocomplete="tel" placeholder="Contoh: 081234567890" />
                            <x-input-error :messages="$errors->get('nomor_telepon')" class="mt-2" />
                        </div>

                        <!-- Deskripsi Toko -->
                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi Singkat Toko')" />
                            <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan tentang toko Anda, produk apa yang Anda jual, sejarah singkat, dll." class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        {{-- Nanti kita tambahkan upload logo di sini --}}

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Buat Toko Saya') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

