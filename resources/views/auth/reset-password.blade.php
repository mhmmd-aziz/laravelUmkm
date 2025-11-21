<x-guest-layout>

    <div class="min-h-screen flex justify-center items-center bg-gray-50">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 border border-gray-100">

            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>

                <h2 class="text-xl font-bold text-gray-800 mt-2">
                    Reset Password
                </h2>
                <p class="text-sm text-gray-500 text-center">
                    Masukkan email dan password baru Anda untuk melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- New Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password Baru')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm New Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end mt-6">
                    <button type="submit"
                        class="px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 shadow">
                        Reset Password
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-guest-layout>
