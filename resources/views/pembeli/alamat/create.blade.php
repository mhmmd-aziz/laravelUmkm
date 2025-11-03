<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Alamat Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <form method="POST" action="{{ route('pembeli.alamat.store') }}">
                        {{-- Panggil form partial --}}
                        @include('pembeli.alamat.partials.form-alamat', [
                            'tombolSimpan' => 'Simpan Alamat Baru'
                        ])
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
