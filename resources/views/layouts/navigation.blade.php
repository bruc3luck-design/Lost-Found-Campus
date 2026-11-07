<nav x-data="{ open: false }" class="bg-uin-500 text-white shadow-md border-b-2 border-gold-500 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 sm:h-16">
            <!-- Sisi Kiri: Logo & Navigasi Desktop -->
            <div class="flex items-center space-x-3 sm:space-x-6">
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5 sm:space-x-3 text-white group">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white p-1 flex items-center justify-center border border-gold-500/50 shadow-sm group-hover:scale-105 transition-transform shrink-0">
                        <img src="{{ asset('images/logo-uinssc.png') }}" alt="Logo UIN SSC" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="font-bold text-sm sm:text-base tracking-tight leading-none sm:leading-tight text-white">Lost &amp; Found</div>
                        <div class="text-[10px] sm:text-[11px] text-gold-100 font-medium">UIN Siber Syekh Nurjati</div>
                    </div>
                </a>

                <!-- Navigasi Desktop -->
                <div class="hidden md:flex md:items-center md:space-x-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-uin-700 text-gold-100' : 'text-uin-50 hover:bg-uin-700/60' }}">
                        Beranda
                    </a>
                    @auth
                        @if (Route::has('items.create'))
                            <a href="{{ route('items.create') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-uin-50 hover:bg-uin-700/60 transition-colors">
                                Lapor Barang
                            </a>
                        @endif
                        @if (Route::has('my.history'))
                            <a href="{{ route('my.history') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs(['my.history', 'my.items']) ? 'bg-uin-700 text-gold-100' : 'text-uin-50 hover:bg-uin-700/60' }}">
                                Riwayat Saya
                            </a>
                        @endif
                        @if (Auth::user()->isAdmin())
                            <a href="/admin" class="px-3 py-2 rounded-lg text-sm font-semibold bg-gold-500 text-ink hover:bg-gold-500/90 transition-colors shadow-sm">
                                Panel Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Sisi Kanan: Profil & Tombol Keluar (Desktop) -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                @auth
                    <div class="text-right">
                        <div class="text-xs font-semibold text-white leading-none">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-gold-100 uppercase tracking-wider mt-0.5">
                            {{ Auth::user()->role }} &bull; {{ Auth::user()->username }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3.5 py-1.5 border border-white/20 rounded-lg text-xs font-medium text-white bg-uin-700 hover:bg-uin-700/80 hover:border-gold-500 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-gold-500 text-ink font-semibold rounded-lg text-sm hover:bg-gold-500/90 transition-colors shadow-sm">
                        Masuk Portal
                    </a>
                @endauth
            </div>

            <!-- Tombol Hamburger Mobile -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-uin-50 hover:bg-uin-700 focus:outline-none transition-colors border border-uin-700">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Dropdown Mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-uin-700 border-t border-uin-500/40">
        <div class="px-4 pt-3 pb-2 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-uin-500 text-gold-100' : 'text-uin-50 hover:bg-uin-500/50' }}">
                Beranda
            </a>
            @auth
                @if (Route::has('items.create'))
                    <a href="{{ route('items.create') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-uin-50 hover:bg-uin-500/50">
                        Lapor Barang
                    </a>
                @endif
                @if (Route::has('my.history'))
                    <a href="{{ route('my.history') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs(['my.history', 'my.items']) ? 'bg-uin-500 text-gold-100' : 'text-uin-50 hover:bg-uin-500/50' }}">
                        Riwayat Saya
                    </a>
                @endif
                @if (Auth::user()->isAdmin())
                    <a href="/admin" class="block px-3 py-2 rounded-lg text-sm font-semibold bg-gold-500 text-ink hover:bg-gold-500/90 my-2">
                        Panel Admin
                    </a>
                @endif
            @endauth
        </div>

        @auth
            <!-- Info Pengguna Mobile -->
            <div class="pt-3 pb-3 border-t border-uin-500/40 px-4 bg-uin-700/80">
                <div class="text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-[11px] text-gold-100 uppercase tracking-wider">{{ Auth::user()->role }} &bull; {{ Auth::user()->username }}</div>
                @if(Auth::user()->prodi)
                    <div class="text-[11px] text-uin-50/80 mt-0.5">{{ Auth::user()->prodi }}</div>
                @endif
                <div class="mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 px-3 rounded-lg text-xs font-semibold bg-rose-600/90 text-white hover:bg-rose-600 transition-colors">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="p-4 border-t border-uin-500/40">
                <a href="{{ route('login') }}" class="block w-full text-center py-2 px-4 rounded-xl font-semibold bg-gold-500 text-ink hover:bg-gold-500/90 text-sm shadow-sm">
                    Masuk Portal
                </a>
            </div>
        @endauth
    </div>
</nav>
