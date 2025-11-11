<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    {{-- Link untuk ADMIN --}}
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    {{-- Link untuk PENJUAL --}}
                    @if(Auth::check() && Auth::user()->role === 'penjual')
                        <x-nav-link :href="route('penjual.dashboard')" :active="request()->routeIs('penjual.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('penjual.produk.index')" :active="request()->routeIs('penjual.produk.*')">
                            {{ __('Produk Saya') }}
                        </x-nav-link>
                         <x-nav-link :href="route('penjual.pesanan.index')" :active="request()->routeIs('penjual.pesanan.*')">
                            {{ __('Pesanan Masuk') }}
                        </x-nav-link>
                         <x-nav-link :href="route('penjual.omset.index')" :active="request()->routeIs('penjual.omset.*')">
                            {{ __('Laporan Omset') }}
                        </x-nav-link>
                         <x-nav-link :href="route('penjual.ai.index')" :active="request()->routeIs('penjual.ai.*')">
                            {{ __('AI Insight') }}
                        </x-nav-link>
                        {{-- Hapus Link Chat (Langkah 16 belum) --}}
                    @endif

                    {{-- Link untuk PEMBELI --}}
                    @if(Auth::check() && Auth::user()->role === 'pembeli')
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                         <x-nav-link :href="route('pembeli.pesanan.index')" :active="request()->routeIs('pembeli.pesanan.*')">
                            {{ __('Pesanan Saya') }}
                        </x-nav-link>
                        {{-- Hapus Link Chat (Langkah 16 belum) --}}
                    @endif

                    {{-- Link untuk TAMU (Guest) --}}
                    @guest
                         <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                    @endguest
                    
                </div>
            </div>

            <!-- Settings Dropdown & Language Switcher -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                <!-- Language Switcher -->
                <div class="me-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ strtoupper(app()->getLocale()) }}</div> 
                                <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c.24 0 .467.013.69.04M12 21c-.24 0-.467.013-.69.04M12 3.04c-4.512 0-8.248 3.513-8.69 7.956M12 3.04c4.512 0 8.248 3.513 8.69 7.956M12 3.04c.24 0 .467-.013.69-.04M12 3.04c-.24 0-.467-.013-.69-.04M20.957 10.996C20.728 11 20.48 11 20.22 11h-.44c-.26 0-.508.01-.748.028M3.043 10.996C3.272 11 3.52 11 3.78 11h.44c.26 0 .508.01.748.028m12.456 0A4.5 4.5 0 0012.5 10.5h-1a4.5 4.5 0 00-4.956 1.528A2.25 2.25 0 0012 15a2.25 2.25 0 004.956-1.528z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- INI PERBAIKANNYA: Gunakan Form POST -->
                            <form method="POST" action="{{ route('language.switch') }}">
                                @csrf
                                <input type="hidden" name="locale" value="id">
                                <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('ID - Bahasa Indonesia') }}
                                </x-dropdown-link>
                            </form>
                            <form method="POST" action="{{ route('language.switch') }}">
                                @csrf
                                <input type="hidden" name="locale" value="en">
                                <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('EN - English') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                @auth
                    <!-- Ikon Keranjang (Hanya untuk Pembeli) -->
                    @if(Auth::user()->role === 'pembeli')
                        <a href="{{ route('cart.index') }}" class="me-3 p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 relative">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.612 0 1.174.407 1.357 1.006l.303 1.02a.75.75 0 01-1.42.424l-1.11-3.742M7.5 14.25V5.25A2.25 2.25 0 019.75 3h4.5M4.5 19.5h15a2.25 2.25 0 002.121-1.667L21 11.25a2.25 2.25 0 00-2.121-2.833H9.75M16.5 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Zm-9 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z" />
                            </svg>
                            {{-- Badge Jumlah Item Keranjang --}}
                            @if(Cart::getContent()->count() > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                    {{ Cart::getContent()->count() }}
                                </span>
                            @endif
                        </a>
                    @endif

                    <!-- Settings Dropdown (Profil, Logout) -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- Tombol Login & Register untuk TAMU --}}
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">{{ __('Log in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">{{ __('Register') }}</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            
            {{-- Link Responsive untuk ADMIN --}}
            @if(Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif

            {{-- Link Responsive untuk PENJUAL --}}
            @if(Auth::check() && Auth::user()->role === 'penjual')
                <x-responsive-nav-link :href="route('penjual.dashboard')" :active="request()->routeIs('penjual.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.produk.index')" :active="request()->routeIs('penjual.produk.*')">
                    {{ __('Produk Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.pesanan.index')" :active="request()->routeIs('penjual.pesanan.*')">
                    {{ __('Pesanan Masuk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.omset.index')" :active="request()->routeIs('penjual.omset.*')">
                    {{ __('Laporan Omset') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.ai.index')" :active="request()->routeIs('penjual.ai.*')">
                    {{ __('AI Insight') }}
                </x-responsive-nav-link>
                {{-- Hapus Link Chat (Langkah 16 belum) --}}
            @endif

            {{-- Link Responsive untuk PEMBELI --}}
            @if(Auth::check() && Auth::user()->role === 'pembeli')
                 <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pembeli.pesanan.index')" :active="request()->routeIs('pembeli.pesanan.*')">
                    {{ __('Pesanan Saya') }}
                </x-responsive-nav-link>
                {{-- Hapus Link Chat (Langkah 16 belum) --}}
            @endif

            {{-- Link Responsive untuk TAMU (Guest) --}}
            @guest
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Log In') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            @endguest
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        <!-- Responsive Language Switcher -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600 sm:hidden">
            <div class="px-4 font-medium text-base text-gray-800 dark:text-gray-200">{{ __('Bahasa') }}</div>
            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('language.switch') }}">
                    @csrf
                    <input type="hidden" name="locale" value="id">
                    <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" :active="app()->getLocale() == 'id'">
                        {{ __('ID - Bahasa Indonesia') }}
                    </x-responsive-nav-link>
                </form>
                <form method="POST" action="{{ route('language.switch') }}">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" :active="app()->getLocale() == 'en'">
                        {{ __('EN - English') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>

    </div>
</nav>