<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses untuk Alamat --}}
            @if (session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                 <div class="p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- --- BAGIAN BARU: BUKU ALAMAT (Hanya untuk Pembeli) --- --}}
            @if (Auth::user()->role === 'pembeli')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full"> {{-- Buat full width --}}
                    
                    <section>
                        <header class="flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Buku Alamat') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Kelola alamat pengiriman Anda.") }}
                                </p>
                            </div>
                            <x-primary-button-link href="{{ route('pembeli.alamat.create') }}">
                                {{ __('+ Tambah Alamat Baru') }}
                            </x-primary-button-link>
                        </header>

                        <div class="mt-6 space-y-4">
                            @forelse ($alamats as $alamat)
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg flex justify-between items-start">
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $alamat->label_alamat }}</div>
                                        <div class="text-sm text-gray-800 dark:text-gray-200 mt-1">
                                            <p class="font-medium">{{ $alamat->nama_penerima }} ({{ $alamat->nomor_telepon }})</p>
                                            <p>{{ $alamat->alamat_lengkap }}</p>
                                            <p>{{ $alamat->kecamatan }}, {{ $alamat->kota_kabupaten }}</p>
                                            <p>{{ $alamat->provinsi }}, {{ $alamat->kode_pos }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ml-4 space-x-2">
                                        <a href="{{ route('pembeli.alamat.edit', $alamat) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                        
                                        {{-- Tombol Hapus pakai form --}}
                                        <form action="{{ route('pembeli.alamat.destroy', $alamat) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Anda belum memiliki alamat tersimpan.
                                </p>
                            @endforelse
                        </div>
                    </section>

                </div>
            </div>
            @endif
            {{-- --- BATAS BAGIAN BARU --- --}}

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
