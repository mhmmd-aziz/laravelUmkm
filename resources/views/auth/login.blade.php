<x-guest-layout>

    <div class="min-h-screen flex justify-center items-center bg-gray-50">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 border border-gray-100">

            <div class="flex flex-col items-center mb-6">
                <!-- <img src="/images/logo-rupanusa.png" alt="RupaNusa" class="h-12 mb-2"> -->
                                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <h2 class="text-xl font-bold text-gray-800">Masuk ke RupaNusa</h2>
                <p class="text-sm text-gray-500 mt-1">Temukan produk budaya Nusantara</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full"
                        type="email" name="email"
                        :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center mt-4">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-orange-500 focus:ring-orange-400"
                        name="remember">
                    <label for="remember_me" class="ms-2 text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-orange-600 hover:text-orange-700">
                            Lupa password?
                        </a>
                    @endif

                    <button type="submit"
                        class="px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 shadow-md">
                        Masuk
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-700">
                    Daftar sekarang
                </a>
            </p>

        </div>
    </div>

</x-guest-layout>
