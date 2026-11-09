<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Lost & Found Campus – UIN Siber Syekh Nurjati Cirebon' }}</title>
    <meta name="description" content="Platform resmi pelaporan barang hilang dan temuan civitas akademika UIN Siber Syekh Nurjati Cirebon.">
    <link rel="icon" type="image/png" href="{{ asset('images/uin/logo-uinssc.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/uin/logo-uinssc.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col pb-16 sm:pb-0">
    <!-- Navbar -->
    @include('layouts.navigation')

    <!-- Flash Messages (Full-width responsive container) -->
    <div class="w-full max-w-7xl 2xl:max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 2xl:px-12 mt-3 sm:mt-4">
        @if (session('success'))
            <div class="mb-3 p-3 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start space-x-2.5 shadow-xs">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-3 p-3 sm:p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-start space-x-2.5 shadow-xs">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif
    </div>

    <!-- Header Opsional -->
    @isset($header)
        <header class="bg-white border-b border-gray-200 shadow-xs">
            <div class="w-full max-w-7xl 2xl:max-w-screen-2xl mx-auto py-3.5 sm:py-5 px-4 sm:px-6 lg:px-8 2xl:px-12">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Konten Utama Halaman (Full-width di Desktop Lebar, Responsif di Mobile) -->
    <main class="flex-1 w-full max-w-7xl 2xl:max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 2xl:px-12 py-4 sm:py-6">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar Khusus Mobile -->
    <div class="sm:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-gray-200/80 z-30 px-4 py-2 shadow-lg">
        <div class="flex items-center justify-around">
            <a href="{{ route('home') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs('home') ? 'text-uin-500 font-bold' : 'text-gray-500 font-medium' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Beranda</span>
            </a>

            @auth
                <a href="{{ route('items.create') }}" class="flex flex-col items-center -mt-5">
                    <div class="w-11 h-11 rounded-full bg-uin-500 text-white flex items-center justify-center shadow-lg border-2 border-white active:scale-95 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold text-uin-700 mt-0.5">Lapor</span>
                </a>

                <a href="{{ route('my.history') }}" class="flex flex-col items-center py-1 px-3 text-xs {{ request()->routeIs(['my.history', 'my.items']) ? 'text-uin-500 font-bold' : 'text-gray-500 font-medium' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Riwayat</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center py-1 px-3 text-xs text-gold-500 font-bold">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Login</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- Footer Identitas UIN SSC -->
    <footer class="bg-uin-700 text-uin-50 border-t-4 border-gold-500 mt-auto">
        <div class="w-full max-w-7xl 2xl:max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 2xl:px-12 py-6 sm:py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                <div>
                    <div class="flex items-center space-x-2.5 mb-2">
                        <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-8 w-auto object-contain bg-white rounded-lg p-1">
                        <span class="font-bold text-white text-sm">Lost &amp; Found Campus</span>
                    </div>
                    <p class="text-xs text-uin-50/80 leading-relaxed">
                        Platform resmi pelaporan barang hilang dan barang temuan untuk civitas akademika UIN Siber Syekh Nurjati Cirebon.
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gold-500 uppercase tracking-wider mb-2">Pusat Bantuan &amp; Titik Ambil</h3>
                    <p class="text-xs text-uin-50/80 leading-relaxed">
                        Barang temuan dapat diserahkan ke <strong>Pos Satpam Gerbang Utama</strong> atau <strong>Gedung PUSTIKOM Lantai 1</strong>.
                    </p>
                </div>

                <div class="md:text-right">
                    <h3 class="text-xs font-semibold text-gold-500 uppercase tracking-wider mb-2">Pengelola Sistem</h3>
                    <p class="text-xs text-uin-50/80">
                        Pusat Teknologi Informasi dan Komunikasi (PUSTIKOM)<br>
                        UIN Siber Syekh Nurjati Cirebon
                    </p>
                    <p class="text-[11px] text-uin-50/60 mt-2 sm:mt-3">
                        &copy; {{ date('Y') }} UIN SSC. Hak Cipta Dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
