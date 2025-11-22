<x-app-layout>

    <section class="bg-white dark:bg-gray-900 py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">

            <div>
                {{-- Menggunakan {!! !!} agar bisa menyisipkan HTML span warna jika Anda memodifikasi JSON nanti, 
                     tapi untuk sekarang ini akan memanggil "Tentang RupaNusa" / "About RupaNusa" --}}
                <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">
    
    {{-- Mengambil kata "Tentang" / "About" dari JSON --}}
    {{ __('about_title') }} 

    {{-- Kata "RupaNusa" ditulis manual agar warnanya tetap Oranye --}}
    <span class="text-orange-600">RupaNusa</span>

</h1>

                {{-- Deskripsi diambil dari JSON (menggabungkan 2 paragraf sebelumnya jadi 1 blok teks rapi) --}}
                <p class="mt-6 text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                    {{ __('about_desc') }}
                </p>

                <div class="mt-10 flex gap-5">
                    <a href="/"
                       class="px-7 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 shadow-lg transition">
                        {{ __('btn_back_home') }}
                    </a>

                    <a href="#visi"
                       class="px-7 py-3 border border-gray-300 dark:border-gray-700 rounded-xl 
                              text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        {{ __('btn_learn_more') }}
                    </a>
                </div>
            </div>

            <div class="flex justify-center">
                <img src="/images/image.png"
                     alt="RupaNusa Illustration"
                     class="w-[460px] drop-shadow-2xl rounded-2xl">
            </div>

        </div>
    </section>


    <section id="visi" class="bg-gray-50 dark:bg-gray-800 py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-16 text-center">
                {{ __('vision_title') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-14">

                <div class="bg-white dark:bg-gray-900 p-12 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-semibold text-orange-600 mb-4">{{ __('vis_title') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                        {{ __('vis_text') }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-900 p-12 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-semibold text-orange-600 mb-4">{{ __('mis_title') }}</h3>
                    <ul class="space-y-4 text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                        {{-- List Misi diambil satu per satu dari JSON --}}
                        <li>• {{ __('mis_1') }}</li>
                        <li>• {{ __('mis_2') }}</li>
                        <li>• {{ __('mis_3') }}</li>
                        <li>• {{ __('mis_4') }}</li>
                        <li>• {{ __('mis_5') }}</li>
                    </ul>
                </div>

            </div>

        </div>
    </section>


    <section class="bg-white dark:bg-gray-900 py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-16 text-center">
                {{ __('val_title') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">

                <div class="p-12 rounded-2xl bg-gray-50 dark:bg-gray-800 shadow border dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-orange-600 mb-4">{{ __('val_auth') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                        {{ __('val_auth_desc') }}
                    </p>
                </div>

                <div class="p-12 rounded-2xl bg-gray-50 dark:bg-gray-800 shadow border dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-orange-600 mb-4">{{ __('val_sust') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                        {{ __('val_sust_desc') }}
                    </p>
                </div>

                <div class="p-12 rounded-2xl bg-gray-50 dark:bg-gray-800 shadow border dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-orange-600 mb-4">{{ __('val_fair') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                        {{ __('val_fair_desc') }}
                    </p>
                </div>

            </div>

        </div>
    </section>


    <section class="bg-gray-50 dark:bg-gray-800 py-28">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-20 text-center">
                {{ __('why_title') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-14">

                <div class="bg-white dark:bg-gray-900 p-12 rounded-2xl shadow-xl border dark:border-gray-700 text-center">
                    <h3 class="text-xl font-semibold text-orange-600">{{ __('why_1_title') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-4 text-lg leading-relaxed">
                        {{ __('why_1_desc') }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-900 p-12 rounded-2xl shadow-xl border dark:border-gray-700 text-center">
                    <h3 class="text-xl font-semibold text-orange-600">{{ __('why_2_title') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-4 text-lg leading-relaxed">
                        {{ __('why_2_desc') }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-900 p-12 rounded-2xl shadow-xl border dark:border-gray-700 text-center">
                    <h3 class="text-xl font-semibold text-orange-600">{{ __('why_3_title') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-4 text-lg leading-relaxed">
                        {{ __('why_3_desc') }}
                    </p>
                </div>

            </div>

        </div>
    </section>

</x-app-layout>