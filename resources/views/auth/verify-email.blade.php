<x-guest-layout>

    <div class="min-h-screen flex justify-center items-center bg-gray-50">

        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 border border-gray-100">

            <!-- Logo -->
            <div class="flex flex-col items-center mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>

                <h2 class="text-xl font-bold text-gray-800 mt-2">
                    Verifikasi Email Anda
                </h2>
                <p class="text-sm text-gray-500 text-center mt-1">
                    Terima kasih telah bergabung dengan RupaNusa!  
                    Sebelum memulai, silakan cek email Anda dan klik tautan verifikasi.
                </p>
            </div>

            <!-- Status: Link Sent -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 p-3 rounded-lg">
                    Tautan verifikasi baru telah dikirim ke email Anda.
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-4 flex items-center justify-between">

                <!-- Resend -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 shadow">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="underline text-sm text-gray-600 hover:text-orange-600">
                        Log Out
                    </button>
                </form>

            </div>

        </div>

    </div>

</x-guest-layout>
