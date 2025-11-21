<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">

    <div class="max-w-[1310px] mx-auto px-4 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <!-- LEFT -->
            <div class="flex items-center gap-10">

                <!-- LOGO -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-application-logo class="h-10 w-auto text-orange-600" />
                    <span class="font-bold text-xl tracking-tight text-gray-800">
                        Rupa<span class="text-orange-600">Nusa</span>
                    </span>
                </a>

                <!-- NAVIGATION LINKS -->
  <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">

    {{-- ADMIN --}}
    @if(Auth::check() && Auth::user()->role === 'admin')
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>
    @endif

    {{-- PENJUAL --}}
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

    {{-- PEMBELI --}}
    @if(Auth::check() && Auth::user()->role === 'pembeli')
        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
            {{ __('Home') }}
        </x-nav-link>
        <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
            {{ __('About') }}
        </x-nav-link>
        <x-nav-link :href="route('kontak')" :active="request()->routeIs('kontak')">
            {{ __('Contact') }}
        </x-nav-link>
        <x-nav-link :href="route('faq')" :active="request()->routeIs('faq')">
            {{ __('FAQ') }}
        </x-nav-link>
 

        <x-nav-link :href="route('pembeli.pesanan.index')" :active="request()->routeIs('pembeli.pesanan.*')">
            {{ __('Pesanan Saya') }}
        </x-nav-link>
    @endif

    {{-- GUEST --}}
    @guest
        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
            {{ __('Home') }}
        </x-nav-link>
                <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
            {{ __('About') }}
        </x-nav-link>
        <x-nav-link :href="route('kontak')" :active="request()->routeIs('kontak')">
            {{ __('Contact') }}
        </x-nav-link>
        <x-nav-link :href="route('faq')" :active="request()->routeIs('faq')">
            {{ __('FAQ') }}
        </x-nav-link>

    @endguest

</div>

            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex items-center gap-6">

                <!-- LANGUAGE SWITCHER -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg 
                            text-gray-600 hover:bg-gray-100 font-medium">

                            <span>{{ strtoupper(app()->getLocale()) }}</span>

                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- ID -->
                        <form method="POST" action="{{ route('language.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="id">
                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                :active="app()->getLocale() == 'id'">
                                ID - Bahasa Indonesia
                            </x-dropdown-link>
                        </form>

                        <!-- EN -->
                        <form method="POST" action="{{ route('language.switch') }}">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                :active="app()->getLocale() == 'en'">
                                EN - English
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- CART Pembeli -->
                @if(Auth::check() && Auth::user()->role === 'pembeli')
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-orange-600 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l2 12h11l2-8H6" />
                        </svg>

                        @if(Cart::getContent()->count() > 0)
                            <span class="absolute -top-1 -right-2 bg-orange-600 text-white text-xs 
                                font-bold px-2 py-0.5 rounded-full">
                                {{ Cart::getContent()->count() }}
                            </span>
                        @endif
                    </a>
                @endif

                <!-- USER -->
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-gray-700 font-medium hover:text-orange-600">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- MOBILE HAMBURGER -->
            <button @click="open = !open" class="sm:hidden text-gray-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- RESPONSIVE MENU -->
    <div 
        x-show="open"
        x-transition
        class="sm:hidden bg-white shadow-md rounded-b-2xl">

        <div class="pt-2 pb-3 space-y-1">

            {{-- ADMIN --}}
            @if(Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')">Dashboard</x-responsive-nav-link>
            @endif

            {{-- PENJUAL --}}
            @if(Auth::check() && Auth::user()->role === 'penjual')
                <x-responsive-nav-link :href="route('penjual.dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.produk.index')">Produk Saya</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.pesanan.index')">Pesanan Masuk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.omset.index')">Laporan Omset</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjual.ai.index')">AI Insight</x-responsive-nav-link>
            @endif

            {{-- PEMBELI --}}
            @if(Auth::check() && Auth::user()->role === 'pembeli')
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
            {{ __('Home') }}
        </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
            {{ __('About') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('kontak')" :active="request()->routeIs('kontak')">
            {{ __('Contact') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('faq')" :active="request()->routeIs('faq')">
            {{ __('FAQ') }}
        </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pembeli.pesanan.index')">Pesanan Saya</x-responsive-nav-link>
            @endif

            {{-- GUEST --}}
            @guest
                                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
            {{ __('Home') }}
        </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">
            {{ __('About') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('kontak')" :active="request()->routeIs('kontak')">
            {{ __('Contact') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('faq')" :active="request()->routeIs('faq')">
            {{ __('FAQ') }}
        </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')">Masuk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">Daftar</x-responsive-nav-link>
            @endguest
        </div>

        <!-- RESPONSIVE USER -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        <!-- RESPONSIVE LANGUAGE -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 font-medium text-base text-gray-800">Bahasa</div>
            <div class="mt-3 space-y-1">

                <!-- ID -->
                <form method="POST" action="{{ route('language.switch') }}">
                    @csrf
                    <input type="hidden" name="locale" value="id">
                    <x-responsive-nav-link 
                        href="#" 
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        :active="app()->getLocale() == 'id'">
                        ID - Bahasa Indonesia
                    </x-responsive-nav-link>
                </form>

                <!-- EN -->
                <form method="POST" action="{{ route('language.switch') }}">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <x-responsive-nav-link 
                        href="#" 
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        :active="app()->getLocale() == 'en'">
                        EN - English
                    </x-responsive-nav-link>
                </form>

            </div>
        </div>
    </div>

</nav>
