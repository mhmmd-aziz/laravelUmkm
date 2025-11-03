<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}"> {{-- Ubah dari dashboard ke home --}}
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
                        {{-- Tambahkan link admin lainnya di sini --}}
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
                    @endif

                    {{-- Link untuk PEMBELI (atau user biasa) --}}
                    @if(Auth::check() && Auth::user()->role === 'pembeli')
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('pembeli.pesanan.index')" :active="request()->routeIs('pembeli.pesanan.*')">
                            {{ __('Pesanan Saya') }}
                        </x-nav-link>
                    @endif

                    {{-- Link untuk TAMU (Guest) --}}
                    @guest
                         <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                    @endguest
                    
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                {{-- TAMBAHKAN ICON KERANJANG (DARI LANGKAH 7) --}}
                @if(!Auth::check() || Auth::check() && Auth::user()->role === 'pembeli')
                    <a href="{{ route('cart.index') }}" class="relative me-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.121.001.241.012.36.03l.345.09c.39.106.623.498.623.924v3.75C17.25 21.01 16.74 21.75 16 21.75H8c-.74 0-1.25-.74-1.25-1.5v-3.75c0-.426.233-.818.623-.924l.345-.09a2.25 2.25 0 01.36-.03h11.218M13.5 14.25a3 3 0 013-3h.75a3 3 0 013 3v3.75M6 6h12m-6 6h.008" />
                        </svg>
                        {{-- Badge Jumlah Item --}}
                        @if(Cart::getContent()->count() > 0)
                            <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                {{ Cart::getContent()->count() }}
                            </span>
                        @endif
                    </a>
                @endif
                {{-- BATAS ICON KERANJANG --}}

                @auth
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
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            
                            {{-- Link Pesanan Saya untuk Pembeli --}}
                            @if(Auth::user()->role === 'pembeli')
                                <x-dropdown-link :href="route('pembeli.pesanan.index')">
                                    {{ __('Pesanan Saya') }}
                                </x-dropdown-link>
                            @endif

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
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Register</a>
                        @endif
                    </div>
                @endauth {{-- <-- INI @endauth YANG HILANG --}}
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
            @endif

            {{-- Link Responsive untuk PEMBELI --}}
            @if(Auth::check() && Auth::user()->role === 'pembeli')
                 <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pembeli.pesanan.index')" :active="request()->routeIs('pembeli.pesanan.*')">
                    {{ __('Pesanan Saya') }}
                </x-responsive-nav-link>
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
                        {{ __('Profile') }}
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
    </div>
</nav>

