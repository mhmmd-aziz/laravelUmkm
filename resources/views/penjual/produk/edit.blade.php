<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Produk: ') }} {{ $produk->nama_produk }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <form method="POST" action="{{ route('penjual.produk.update', $produk) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 

                        <!-- Kategori -->
                        <div>
                            <x-input-label for="kategori_id" :value="__('Kategori Produk')" />
                            <select id="kategori_id" name="kategori_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="" disabled>Pilih Kategori...</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
                        </div>

                        <!-- Nama Produk -->
                        <div class="mt-4">
                            <x-input-label for="nama_produk" :value="__('Nama Produk')" />
                            <x-text-input id="nama_produk" class="block mt-1 w-full" type="text" name="nama_produk" :value="old('nama_produk', $produk->nama_produk)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_produk')" class="mt-2" />
                        </div>
                        
                        <!-- Tombol AI Generate -->
                        <div class="mt-4">
                            <x-secondary-button type="button" id="generate-ai-deskripsi">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 animate-spin" style="display: none;" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a7 7 0 100 14 7 7 0 000-14zM2 10a8 8 0 1116 0 8 8 0 01-16 0z" clip-rule="evenodd" />
                                </svg>
                                <span id="generate-ai-text">✨ Buat Deskripsi dengan AI (Ollama)</span>
                            </x-secondary-button>
                            <p id="generate-ai-error" class="mt-1 text-sm text-red-600 dark:text-red-400" style="display: none;"></p>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div class="mt-4">
                            <x-input-label for="deskripsi_singkat" :value="__('Deskripsi Singkat')" />
                            <textarea id="deskripsi_singkat" name="deskripsi_singkat" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('deskripsi_singkat', $produk->deskripsi_singkat) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_singkat')" class="mt-2" />
                        </div>

                        <!-- Deskripsi Lengkap -->
                        <div class="mt-4">
                            <x-input-label for="deskripsi_lengkap" :value="__('Deskripsi Lengkap (Opsional)')" />
                            <textarea id="deskripsi_lengkap" name="deskripsi_lengkap" rows="6" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi_lengkap', $produk->deskripsi_lengkap) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_lengkap')" class="mt-2" />
                        </div>

                        <!-- Harga -->
                        <div class="mt-4">
                            <x-input-label for="harga" :value="__('Harga (Rp)')" />
                            <x-text-input id="harga" class="block mt-1 w-full" type="number" name="harga" :value="old('harga', $produk->harga)" required placeholder="Contoh: 150000" />
                            <x-input-error :messages="$errors->get('harga')" class="mt-2" />
                        </div>

                        <!-- Stok -->
                        <div class="mt-4">
                            <x-input-label for="stok" :value="__('Stok')" />
                            <x-text-input id="stok" class="block mt-1 w-full" type="number" name="stok" :value="old('stok', $produk->stok)" required />
                            <x-input-error :messages="$errors->get('stok')" class="mt-2" />
                        </div>

                        <!-- Berat (gram) -->
                        <div class="mt-4">
                            <x-input-label for="berat_gram" :value="__('Berat (gram)')" />
                            <x-text-input id="berat_gram" class="block mt-1 w-full" type="number" name="berat_gram" :value="old('berat_gram', $produk->berat_gram)" required placeholder="Contoh: 1500" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Berat produk dalam gram, untuk perhitungan ongkir nanti.</p>
                            <x-input-error :messages="$errors->get('berat_gram')" class="mt-2" />
                        </div>

                        <!-- Gambar Produk Utama -->
                        <div class="mt-4">
                            <x-input-label for="gambar_produk_utama" :value="__('Ganti Gambar Produk Utama (Opsional)')" />
                            <div class="mt-2">
                                <img src="{{ Storage::url($produk->gambar_produk_utama) }}" alt="{{ $produk->nama_produk }}" class="w-32 h-32 object-cover rounded mb-2">
                            </div>
                            <input id="gambar_produk_utama" name="gambar_produk_utama" type="file" class="block mt-1 w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Biarkan kosong jika tidak ingin mengganti gambar. Format: JPG, PNG, WEBP. Maks: 2MB.</p>
                            <x-input-error :messages="$errors->get('gambar_produk_utama')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button-link href="{{ route('penjual.produk.index') }}" class="ms-4">
                                {{ __('Batal') }}
                            </x-secondary-button-link>

                            <x-primary-button class="ms-4">
                                {{ __('Perbarui Produk') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- INI DIA PERBAIKANNYA: Script dipindahkan ke @push('scripts') --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const generateButton = document.getElementById('generate-ai-deskripsi');
            const buttonText = document.getElementById('generate-ai-text');
            const buttonSpinner = generateButton.querySelector('svg');
            const errorText = document.getElementById('generate-ai-error');
            
            const namaProdukInput = document.getElementById('nama_produk');
            const kategoriSelect = document.getElementById('kategori_id');
            const deskripsiSingkatTextarea = document.getElementById('deskripsi_singkat');
            const deskripsiLengkapTextarea = document.getElementById('deskripsi_lengkap');

            if (generateButton) {
                generateButton.addEventListener('click', async function () {
                    const namaProduk = namaProdukInput.value;
                    const kategoriText = kategoriSelect.options[kategoriSelect.selectedIndex].text;

                    if (!namaProduk || !kategoriSelect.value) {
                        errorText.textContent = 'Harap isi Nama Produk dan Kategori terlebih dahulu.';
                        errorText.style.display = 'block';
                        return;
                    }

                    errorText.style.display = 'none';
                    buttonText.textContent = 'Memproses...';
                    buttonSpinner.style.display = 'inline';
                    generateButton.disabled = true;

                    try {
                        const response = await fetch('{{ route("penjual.ai.generateDeskripsi") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                nama_produk: namaProduk,
                                kategori: kategoriText
                            })
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        deskripsiSingkatTextarea.value = data.deskripsi_singkat;
                        deskripsiLengkapTextarea.value = data.deskripsi_lengkap;

                    } catch (error) {
                        console.error('Error:', error);
                        errorText.textContent = 'Gagal membuat deskripsi: ' + error.message;
                        errorText.style.display = 'block';
                    } finally {
                        buttonText.textContent = '✨ Buat Deskripsi dengan AI (Ollama)';
                        buttonSpinner.style.display = 'none';
                        generateButton.disabled = false;
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

