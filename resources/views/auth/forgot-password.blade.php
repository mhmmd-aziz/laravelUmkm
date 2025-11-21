<x-guest-layout>

    <div class="min-h-screen flex justify-center items-center bg-gray-50">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 border border-gray-100">

            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>

                <h2 class="text-xl font-bold text-gray-800 mt-2">
                    Lupa Password?
                </h2>
                <p class="text-sm text-gray-500 text-center">
                    Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end mt-6">
                    <button type="submit"
                        class="px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 shadow">
                        Kirim Link Reset Password
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-guest-layout>
