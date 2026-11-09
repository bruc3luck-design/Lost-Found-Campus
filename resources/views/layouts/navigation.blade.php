<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-100">
    <!-- Main Navbar Putih Bersih Bergaya Portal Resmi UIN SSC -->
    <div class="w-full max-w-7xl 2xl:max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 2xl:px-12">
        <div class="flex justify-between h-20 items-center">
            <!-- Sisi Kiri: Logo Utama & Deretan Badge Akreditasi Resmi -->
            <div class="flex items-center space-x-3 sm:space-x-5">
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5 sm:space-x-3 group">
                    <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-12 sm:h-14 w-auto object-contain group-hover:scale-105 transition-transform">
                    <div class="border-l border-gray-200 pl-2.5 sm:pl-3">
                        <div class="font-extrabold text-sm sm:text-base text-ink tracking-tight uppercase leading-none">
                            Lost &amp; Found
                        </div>
                        <div class="text-[10px] sm:text-[11px] font-semibold text-uin-500 tracking-wide mt-0.5">
                            UIN Siber Syekh Nurjati
                        </div>
                    </div>
                </a>

                <!-- Deretan Badge Akreditasi (Identitas Resmi UIN SSC) -->
                <div class="hidden lg:flex items-center space-x-3 pl-4 border-l border-gray-200">
                    <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Akreditasi Unggul" class="h-10 w-auto object-contain" title="Terakreditasi Unggul">
                    <img src="{{ asset('images/uin/badge-blu.png') }}" alt="BLU Speed" class="h-8 w-auto object-contain" title="Badan Layanan Umum">
                    <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="Cyber Islamic University" class="h-9 w-auto object-contain" title="Cyber Islamic University">
                </div>
            </div>

            <!-- Sisi Kanan: Menu Navigasi Desktop -->
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <a href="{{ route('home') }}"
                   class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'text-uin-500 bg-uin-50' : 'text-gray-700 hover:text-uin-500 hover:bg-gray-50' }}">
                    Beranda
                </a>

                @auth
                    @if (Route::has('items.create'))
                        <a href="{{ route('items.create') }}"
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors {{ request()->routeIs('items.create') ? 'text-uin-500 bg-uin-50' : 'text-gray-700 hover:text-uin-500 hover:bg-gray-50' }}">
                            Lapor Barang
                        </a>
                    @endif

                    @if (Route::has('my.history'))
                        <a href="{{ route('my.history') }}"
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors {{ request()->routeIs(['my.history', 'my.items']) ? 'text-uin-500 bg-uin-50' : 'text-gray-700 hover:text-uin-500 hover:bg-gray-50' }}">
                            Riwayat Saya
                        </a>
                    @endif

                    @if (Auth::user()->isAdmin())
                        <a href="/admin"
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold text-amber-900 bg-gold-100 hover:bg-gold-500 hover:text-white transition-colors border border-gold-500/40">
                            Panel Admin
                        </a>
                    @endif

                    <!-- User Pill & Keluar -->
                    <div class="flex items-center space-x-2 pl-3 ml-2 border-l border-gray-200">
                        <div class="text-right">
                            <div class="text-xs font-bold text-ink leading-tight">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">{{ Auth::user()->role }} &bull; {{ Auth::user()->username }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Keluar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Tombol Aksi Khas Portal UIN SSC -->
                    <div class="flex items-center space-x-2.5 pl-3">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-4 py-2 border-2 border-gold-500 text-gold-500 hover:bg-gold-500 hover:text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                            Bantuan
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-uin-500 hover:bg-uin-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk Portal
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Tombol Hamburger Mobile -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-gray-600 hover:text-uin-500 hover:bg-gray-100 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Dropdown Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg">
        <!-- Badges di Mobile Menu -->
        <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center justify-around">
            <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Unggul" class="h-7 w-auto object-contain">
            <img src="{{ asset('images/uin/badge-blu.png') }}" alt="BLU" class="h-6 w-auto object-contain">
            <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="CIU" class="h-7 w-auto object-contain">
        </div>

        <div class="px-4 pt-3 pb-2 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-uin-50 text-uin-500' : 'text-gray-700 hover:bg-gray-50' }}">
                Beranda
            </a>
            @auth
                @if (Route::has('items.create'))
                    <a href="{{ route('items.create') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Lapor Barang
                    </a>
                @endif
                @if (Route::has('my.history'))
                    <a href="{{ route('my.history') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs(['my.history', 'my.items']) ? 'bg-uin-50 text-uin-500' : 'text-gray-700 hover:bg-gray-50' }}">
                        Riwayat Saya
                    </a>
                @endif
                @if (Auth::user()->isAdmin())
                    <a href="/admin" class="block px-3 py-2 rounded-xl text-sm font-bold bg-gold-100 text-amber-900 border border-gold-500/40 my-2">
                        Panel Admin
                    </a>
                @endif
            @endauth
        </div>

        @auth
            <!-- Profil Singkat Mobile -->
            <div class="pt-3 pb-3 border-t border-gray-100 px-4 bg-gray-50/70">
                <div class="text-sm font-bold text-ink">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-gray-500 uppercase tracking-wider">{{ Auth::user()->role }} &bull; {{ Auth::user()->username }}</div>
                <div class="mt-2.5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 px-3 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition-colors">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="p-4 border-t border-gray-100">
                <a href="{{ route('login') }}" class="block w-full text-center py-2.5 px-4 rounded-xl font-bold bg-uin-500 text-white hover:bg-uin-700 text-sm shadow-sm">
                    Masuk Portal Kampus
                </a>
            </div>
        @endauth
    </div>
</nav>
