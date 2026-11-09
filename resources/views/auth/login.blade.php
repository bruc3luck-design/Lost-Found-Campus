<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Portal SSO – Lost &amp; Found Campus UIN Siber Syekh Nurjati</title>
    <meta name="description" content="Layanan resmi Single Sign-On (SSO) Lost &amp; Found Campus UIN Siber Syekh Nurjati Cirebon.">
    <link rel="icon" type="image/png" href="{{ asset('images/uin/logo-uinssc.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/uin/logo-uinssc.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-ink font-sans antialiased h-full">
    <div class="min-h-screen flex">
        <!-- ======================================================== -->
        <!-- SISI KIRI: BRANDING & VISUAL IDENTITY PANEL (DESKTOP)     -->
        <!-- ======================================================== -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#042013] via-[#084D31] to-[#03190F] text-white p-12 xl:p-16 flex-col justify-between overflow-hidden">
            <!-- Pola Garis Spiral/Geometris Halus Elegan -->
            <div class="absolute inset-0 pointer-events-none opacity-20">
                <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="200" r="160" stroke="#B88830" stroke-width="1.5" stroke-dasharray="4 4" />
                    <circle cx="200" cy="200" r="260" stroke="white" stroke-width="1.2" />
                    <circle cx="200" cy="200" r="380" stroke="white" stroke-width="1" />
                    <circle cx="200" cy="200" r="500" stroke="#B88830" stroke-width="1.5" stroke-dasharray="6 6" />
                    <circle cx="200" cy="200" r="640" stroke="white" stroke-width="0.8" />
                    <path d="M-100 800 C 200 400, 600 600, 900 200" stroke="white" stroke-width="1.2" opacity="0.4" />
                    <path d="M-50 850 C 250 450, 650 650, 950 250" stroke="#B88830" stroke-width="1.2" opacity="0.3" />
                </svg>
            </div>

            <!-- Bagian Atas: Brand Identity Card Elegan (Logo + Nama Institusi yang Balance) -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center bg-white/95 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-lg border border-white/20 space-x-3.5">
                    <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-11 w-auto object-contain">
                    <div class="border-l border-gray-300 pl-3">
                        <div class="font-extrabold text-sm text-ink uppercase tracking-tight leading-none">
                            Lost &amp; Found Campus
                        </div>
                        <div class="text-[10px] font-semibold text-uin-500 tracking-wider uppercase mt-1">
                            UIN Siber Syekh Nurjati Cirebon
                        </div>
                    </div>
                </div>

                <!-- Deretan Badge Akreditasi Resmi Kampus -->
                <div class="flex items-center space-x-3.5 pt-1 pl-1 opacity-90">
                    <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Akreditasi Unggul" class="h-8 w-auto object-contain" title="Terakreditasi Unggul">
                    <img src="{{ asset('images/uin/badge-blu.png') }}" alt="BLU Speed" class="h-6 w-auto object-contain" title="Badan Layanan Umum">
                    <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="Cyber Islamic University" class="h-7 w-auto object-contain" title="Cyber Islamic University">
                </div>
            </div>

            <!-- Bagian Tengah: Sambutan & Narasi Formal (Balance & Proporsional) -->
            <div class="relative z-10 max-w-lg my-auto py-10">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-gold-500/20 border border-gold-500/40 text-gold-300 text-xs font-semibold mb-4 tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold-400 mr-2 animate-pulse"></span>
                    Portal Layanan Resmi Civitas Kampus
                </div>

                <h1 class="text-3xl xl:text-4xl font-extrabold tracking-tight leading-snug text-white mb-4">
                    Selamat Datang di<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-300 via-white to-gold-200">
                        Lost &amp; Found Campus
                    </span>
                </h1>

                <p class="text-sm xl:text-base text-uin-50/90 leading-relaxed font-normal">
                    Layanan terpadu pelaporan dan penelusuran barang hilang &amp; temuan resmi civitas akademika UIN Siber Syekh Nurjati Cirebon. Menghubungkan pelapor dan penemu secara aman, transparan, dan terpercaya.
                </p>

                <!-- Poin Keunggulan Sistem -->
                <div class="mt-8 grid grid-cols-2 gap-4 text-xs text-uin-50/85">
                    <div class="flex items-center space-x-2.5 bg-white/5 border border-white/10 rounded-xl p-3">
                        <svg class="w-4 h-4 text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="font-medium">Single Sign-On (SSO)</span>
                    </div>
                    <div class="flex items-center space-x-2.5 bg-white/5 border border-white/10 rounded-xl p-3">
                        <svg class="w-4 h-4 text-gold-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium">Titik Ambil Resmi Kampus</span>
                    </div>
                </div>
            </div>

            <!-- Bagian Bawah: Hak Cipta Formal -->
            <div class="relative z-10 text-xs text-uin-50/70 border-t border-white/10 pt-4 flex items-center justify-between">
                <span>&copy; {{ date('Y') }} UIN Siber Syekh Nurjati Cirebon. All rights reserved.</span>
                <span class="text-gold-400/90 font-medium tracking-wider">PUSTIKOM</span>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- SISI KANAN: FORMULIR LOGIN FORMAL                         -->
        <!-- ======================================================== -->
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-between p-6 sm:p-12 xl:p-20 overflow-y-auto">
            <!-- Header Navigasi Cepat (Tombol Kembali) -->
            <div class="flex items-center justify-between pb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-uin-500 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>

                <!-- Mobile Only Badge Badges -->
                <div class="lg:hidden flex items-center space-x-1.5">
                    <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Unggul" class="h-6 w-auto object-contain">
                    <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="CIU" class="h-5 w-auto object-contain">
                </div>
            </div>

            <!-- Form Wrapper Container -->
            <div class="w-full max-w-md mx-auto my-auto py-4">
                <!-- Branding Header Kanan (Clean & Balance persis seperti Navbar) -->
                <div class="flex items-center space-x-3 mb-8 pb-5 border-b border-gray-100">
                    <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-12 w-auto object-contain">
                    <div class="border-l border-gray-200 pl-3">
                        <div class="font-extrabold text-base text-ink uppercase tracking-tight leading-none">
                            Lost &amp; Found
                        </div>
                        <div class="text-[11px] font-semibold text-uin-500 tracking-wide mt-1">
                            UIN Siber Syekh Nurjati Cirebon
                        </div>
                    </div>
                </div>

                <!-- Heading Form Login -->
                <div class="mb-7">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-2 leading-relaxed">
                        Gunakan NIM (Mahasiswa) atau NIP (Dosen/Tendik) serta password Single Sign-On portal kampus Anda.
                    </p>
                </div>

                <!-- Pesan Error Validasi -->
                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        <div class="font-semibold mb-1 flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Gagal Masuk:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulir Login Formal -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
                    @csrf

                    <!-- Input NIM / NIP -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            NIM / NIP (Nomor Induk)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan NIM atau NIP Anda"
                                class="w-full pl-10 pr-3.5 py-3 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-xs @error('username') border-rose-400 ring-rose-200 @enderror"
                            >
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Password Portal SSO
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password portal Anda"
                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-xs @error('password') border-rose-400 ring-rose-200 @enderror"
                            >
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                tabindex="-1"
                                aria-label="Toggle password visibility"
                            >
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-uin-500 shadow-xs focus:ring-uin-500"
                            >
                            <span class="ml-2 text-xs text-gray-600">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Masuk Formal (Hitam Pekat Elegan) -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-gray-900 hover:bg-black active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all shadow-sm flex items-center justify-center space-x-2"
                        >
                            <span>Masuk Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Informasi Bantuan Resmi Kampus -->
                <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
                    <p class="leading-relaxed">
                        Mengalami kendala saat masuk akun portal?
                    </p>
                    <a href="https://uinssc.ac.id" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex items-center font-bold text-uin-700 hover:underline">
                        <span>Layanan Helpdesk PUSTIKOM UIN SSC</span>
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Mobile Footer -->
            <div class="lg:hidden text-center text-xs text-gray-400 pt-6">
                &copy; {{ date('Y') }} UIN Siber Syekh Nurjati Cirebon
            </div>
        </div>
    </div>
</body>
</html>
