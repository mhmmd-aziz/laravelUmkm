<section id="hero" class="relative bg-white dark:bg-gray-900 pt-20 pb-32 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col-reverse lg:flex-row items-center gap-16">

        <div class="lg:w-1/2 text-center lg:text-left z-10" data-aos="fade-right">
            <h1
                class="text-5xl sm:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-tight mb-6">
                
                {{-- BAGIAN 1: Teks "Selamat Datang di" / "Welcome to" (Diambil dari JSON yang baru diedit) --}}
                {{ __('hero_welcome') }} 
                
                {{-- BAGIAN 2: Nama Brand (Tetap Oranye & Hardcoded) --}}
                <span class="text-orange-600 inline-block relative">
                    RupaNusa
                    <svg class="hidden sm:block absolute -bottom-2 left-0 w-full h-3 text-orange-200 dark:text-orange-800/30 -z-10"
                        viewBox="0 0 100 12" preserveAspectRatio="none">
                        <path d="M0,10 Q50,0 100,10" stroke="currentColor" stroke-width="4" fill="none"
                            stroke-linecap="round" />
                    </svg>
                </span>
                <br class="hidden sm:block">
                
                {{-- BAGIAN 3: Subtitle "E-Commerce Budaya Nusantara" --}}
                <span class="text-3xl sm:text-4xl font-bold text-gray-700 dark:text-gray-300 mt-4 block">
                    {{ __('hero_subtitle') }}
                </span>
            </h1>

            {{-- Deskripsi Paragraf --}}
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-10 max-w-2xl mx-auto lg:mx-0">
                {{ __('hero_desc') }}
            </p>

            <div class="flex flex-wrap justify-center lg:justify-start gap-5">
                {{-- Tombol Belanja Sekarang --}}
                <a href="#produk"
                    class="px-8 py-4 bg-orange-600 text-white text-lg font-semibold rounded-xl hover:bg-orange-700 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    {{ __('btn_shop_now') }}
                </a>
                
                {{-- Tombol Jelajahi Kategori --}}
                <a href="#kategori"
                    class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-800 dark:text-white text-lg font-semibold rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-orange-600 dark:hover:border-orange-600 hover:text-orange-600 dark:hover:text-orange-400 hover:shadow-md transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                    {{ __('btn_explore') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="lg:w-1/2 relative z-10" data-aos="fade-left" data-aos-delay="200">
            <div class="relative w-full max-w-lg mx-auto">
                <div
                    class="absolute top-0 -left-4 w-72 h-72 bg-orange-400 dark:bg-orange-600 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob">
                </div>
                <div
                    class="absolute top-0 -right-4 w-72 h-72 bg-yellow-400 dark:bg-yellow-600 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-2000">
                </div>
                <div
                    class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 dark:bg-pink-600 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-4000">
                </div>
                <img src="{{ asset('images/image.png') }}" alt="Koleksi Budaya Nusantara RupaNusa"
                    class="relative rounded-3xl drop-shadow-2xl hover:scale-[1.02] transition-transform duration-500">
            </div>
        </div>

    </div>

    <div class="absolute bottom-0 left-0 w-full leading-none z-0">
        <svg class="block w-full h-12 sm:h-24 text-gray-50 dark:text-gray-800" viewBox="0 0 1440 320"
            preserveAspectRatio="none">
            <path fill="currentColor"
                d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,208C1248,192,1344,192,1392,192L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>
</section>