<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Lost & Found Campus – UIN Siber Syekh Nurjati Cirebon' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    @include('layouts.navigation')

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif
    </div>

    <!-- Header Opsional -->
    @isset($header)
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Konten Utama Halaman -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        {{ $slot }}
    </main>

    <!-- Footer Identitas UIN SSC -->
    <footer class="bg-uin-700 text-uin-50 border-t-4 border-gold-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-7 h-7 rounded-lg bg-uin-500 flex items-center justify-center border border-gold-500/50">
                            <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white text-sm">Lost &amp; Found Campus</span>
                    </div>
                    <p class="text-xs text-uin-50/80 leading-relaxed">
                        Platform resmi pelaporan barang hilang dan barang temuan untuk civitas akademika UIN Siber Syekh Nurjati Cirebon.
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gold-500 uppercase tracking-wider mb-2">Pusat Bantuan &amp; Pengambilan</h3>
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
                    <p class="text-[11px] text-uin-50/60 mt-3">
                        &copy; {{ date('Y') }} UIN SSC. Hak Cipta Dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
