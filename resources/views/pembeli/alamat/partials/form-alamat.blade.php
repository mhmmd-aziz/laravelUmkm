@csrf

<!-- Label Alamat -->
<div>
    <x-input-label for="label_alamat" :value="__('Label Alamat')" />
    <x-text-input id="label_alamat" class="block mt-1 w-full" type="text" name="label_alamat" :value="old('label_alamat', $alamat->label_alamat ?? '')" required autofocus placeholder="Contoh: Rumah, Kantor, Kos" />
    <x-input-error :messages="$errors->get('label_alamat')" class="mt-2" />
</div>

<!-- Nama Penerima -->
<div class="mt-4">
    <x-input-label for="nama_penerima" :value="__('Nama Penerima')" />
    <x-text-input id="nama_penerima" class="block mt-1 w-full" type="text" name="nama_penerima" :value="old('nama_penerima', $alamat->nama_penerima ?? '')" required />
    <x-input-error :messages="$errors->get('nama_penerima')" class="mt-2" />
</div>

<!-- Nomor Telepon -->
<div class="mt-4">
    <x-input-label for="nomor_telepon" :value="__('Nomor Telepon')" />
    <x-text-input id="nomor_telepon" class="block mt-1 w-full" type="text" name="nomor_telepon" :value="old('nomor_telepon', $alamat->nomor_telepon ?? '')" required placeholder="Contoh: 0812xxxxxxxx" />
    <x-input-error :messages="$errors->get('nomor_telepon')" class="mt-2" />
</div>

<!-- Alamat Lengkap -->
<div class="mt-4">
    <x-input-label for="alamat_lengkap" :value="__('Alamat Lengkap')" />
    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required placeholder="Nama Jalan, Nomor Rumah, RT/RW...">{{ old('alamat_lengkap', $alamat->alamat_lengkap ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('alamat_lengkap')" class="mt-2" />
</div>

<!-- Provinsi -->
<div class="mt-4">
    <x-input-label for="provinsi" :value="__('Provinsi')" />
    <x-text-input id="provinsi" class="block mt-1 w-full" type="text" name="provinsi" :value="old('provinsi', $alamat->provinsi ?? '')" required placeholder="Contoh: Jawa Barat" />
    <x-input-error :messages="$errors->get('provinsi')" class="mt-2" />
    {{-- TODO: Ganti ini jadi Dropdown API RajaOngkir nanti --}}
</div>

<!-- Kota / Kabupaten -->
<div class="mt-4">
    <x-input-label for="kota_kabupaten" :value="__('Kota / Kabupaten')" />
    <x-text-input id="kota_kabupaten" class="block mt-1 w-full" type="text" name="kota_kabupaten" :value="old('kota_kabupaten', $alamat->kota_kabupaten ?? '')" required placeholder="Contoh: Kota Bandung" />
    <x-input-error :messages="$errors->get('kota_kabupaten')" class="mt-2" />
</div>

<!-- Kecamatan -->
<div class="mt-4">
    <x-input-label for="kecamatan" :value="__('Kecamatan')" />
    <x-text-input id="kecamatan" class="block mt-1 w-full" type="text" name="kecamatan" :value="old('kecamatan', $alamat->kecamatan ?? '')" required placeholder="Contoh: Sukajadi" />
    <x-input-error :messages="$errors->get('kecamatan')" class="mt-2" />
</div>

<!-- Kode Pos -->
<div class="mt-4">
    <x-input-label for="kode_pos" :value="__('Kode Pos')" />
    <x-text-input id="kode_pos" class="block mt-1 w-full" type="text" name="kode_pos" :value="old('kode_pos', $alamat->kode_pos ?? '')" required placeholder="Contoh: 40161" />
    <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <x-secondary-button-link href="{{ route('profile.edit') }}" class="ms-4">
        {{ __('Batal') }}
    </x-secondary-button-link>

    <x-primary-button class="ms-4">
        {{ $tombolSimpan ?? __('Simpan') }}
    </x-primary-button>
</div>
