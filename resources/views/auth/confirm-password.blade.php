<x-guest-layout>

    <div class="min-h-screen flex justify-center items-center bg-gray-50">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 border border-gray-100">

            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>

                <h2 class="text-xl font-bold text-gray-800 mt-2">
                    Konfirmasi Password
                </h2>
                <p class="text-sm text-gray-500 text-center">
                    Untuk alasan keamanan, silakan masukkan password Anda sebelum melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 shadow">
                        Konfirmasi
                    </button>
                </div>

            </form>

        </div>

    </div>

</x-guest-layout>
