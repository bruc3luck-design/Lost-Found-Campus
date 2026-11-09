<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk – Lost &amp; Found Campus UIN Siber Syekh Nurjati</title>
    <meta name="description" content="Layanan resmi Single Sign-On (SSO) Lost &amp; Found Campus UIN Siber Syekh Nurjati Cirebon.">
    <link rel="icon" type="image/png" href="{{ asset('images/uin/logo-uinssc.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/uin/logo-uinssc.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float-subtle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float {
            animation: float-subtle 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased h-full">
    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <!-- ======================================================== -->
        <!-- SISI KIRI: BRANDING & VISUAL (MINIMALIS & BOLD)           -->
        <!-- ======================================================== -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#064E3B] via-[#043E2E] to-[#02241A] text-white p-16 xl:p-20 flex-col justify-between overflow-hidden select-none">
            
            <!-- Garis Lengkung Geometris Halus (Subtle Background Curves) -->
            <div class="absolute inset-0 pointer-events-none opacity-20">
                <svg class="w-full h-full" viewBox="0 0 700 700" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="200" r="180" stroke="white" stroke-width="1.2" stroke-dasharray="3 3" />
                    <circle cx="200" cy="200" r="300" stroke="white" stroke-width="1.2" />
                    <circle cx="200" cy="200" r="440" stroke="#FBBF24" stroke-width="1.2" stroke-dasharray="5 5" opacity="0.6" />
                    <circle cx="200" cy="200" r="600" stroke="white" stroke-width="0.8" />
                    <path d="M-50 700 C 150 400, 500 550, 750 150" stroke="white" stroke-width="1.2" opacity="0.4" />
                </svg>
            </div>

            <!-- Header Atas: Badge Resmi UIN SSC -->
            <div class="relative z-10 flex items-center space-x-3">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-2 flex items-center justify-center shadow-inner">
                    <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-8 w-auto object-contain">
                </div>
                <div>
                    <div class="text-xs font-extrabold uppercase tracking-widest text-emerald-300">
                        Portal Resmi Kampus
                    </div>
                    <div class="text-[11px] text-emerald-100/70 tracking-wide font-medium">
                        UIN Siber Syekh Nurjati Cirebon
                    </div>
                </div>
            </div>

            <!-- Bagian Tengah: Headline Besar & Bersih (Persis seperti Referensi) -->
            <div class="relative z-10 max-w-md my-auto py-12">
                <!-- Simbol Ikon Bintang Halus -->
                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-amber-300 mb-8 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v18m9-9H3m15.364-6.364l-12.728 12.728m12.728 0L6.364 5.636"/>
                    </svg>
                </div>

                <h1 class="text-4xl xl:text-5xl font-black tracking-tight text-white leading-[1.15] mb-5">
                    Hello<br>
                    Civitas Kampus! 👋
                </h1>

                <p class="text-base text-emerald-100/80 leading-relaxed font-normal">
                    Pusat layanan informasi barang hilang dan temuan resmi civitas akademika UIN SSC. Transparan, amanah, dan terintegrasi Single Sign-On.
                </p>

                <div class="mt-8 flex items-center space-x-4 opacity-75">
                    <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Unggul" class="h-7 w-auto object-contain">
                    <img src="{{ asset('images/uin/badge-blu.png') }}" alt="BLU" class="h-5 w-auto object-contain">
                    <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="CIU" class="h-6 w-auto object-contain">
                </div>
            </div>

            <!-- Footer Bawah Kiri -->
            <div class="relative z-10 text-xs text-emerald-200/50 font-medium">
                &copy; {{ date('Y') }} UIN Siber Syekh Nurjati Cirebon. All rights reserved.
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- SISI KANAN: FORM LOGIN BERSIH, MODERN & ELEGAN            -->
        <!-- ======================================================== -->
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-between p-8 sm:p-14 xl:p-20 overflow-y-auto">
            
            <!-- Header Atas Kanan: Brand Name Bersih & Tombol Beranda -->
            <div class="flex items-center justify-between pb-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                    <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-9 w-auto object-contain group-hover:scale-105 transition-transform">
                    <span class="text-xl font-black text-gray-900 tracking-tight">
                        Lost<span class="text-emerald-700">&amp;</span>Found
                    </span>
                </a>

                <a href="{{ route('home') }}" class="text-xs font-semibold text-gray-500 hover:text-emerald-700 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Beranda
                </a>
            </div>

            <!-- Form Container di Tengah -->
            <div class="w-full max-w-sm mx-auto my-auto py-4">
                
                <!-- Judul & Subteks Form -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                        Welcome Back!
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-2 leading-relaxed">
                        Masuk menggunakan akun portal Single Sign-On (SSO) civitas UIN Siber Syekh Nurjati.
                    </p>
                </div>

                <!-- Pesan Error Validasi -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        <div class="font-bold mb-1 flex items-center text-rose-900">
                            <svg class="w-4 h-4 mr-1.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Gagal Masuk
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulir Login -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Input NIM / NIP -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-gray-700 mb-1.5">
                            NIM / NIP
                        </label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan NIM atau NIP"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('username') border-rose-400 ring-rose-200 @enderror"
                        >
                    </div>

                    <!-- Input Password -->
                    <div x-data="{ showPassword: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-gray-700">
                                Password Portal
                            </label>
                        </div>
                        <div class="relative">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                                class="w-full px-4 pr-11 py-3 rounded-xl bg-gray-50 border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('password') border-rose-400 ring-rose-200 @enderror"
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
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                            >
                            <span class="ml-2 text-xs text-gray-500">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Login Hitam Tegas (Persis seperti Referensi Foto) -->
                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gray-950 hover:bg-black active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all shadow-md flex items-center justify-center space-x-2"
                        >
                            <span>Login Now</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Bantuan Akun di Bawah Form -->
                <div class="mt-8 text-center text-xs text-gray-500">
                    Kendala akun atau lupa password? 
                    <a href="https://uinssc.ac.id" target="_blank" rel="noopener noreferrer" class="font-bold text-gray-900 hover:text-emerald-700 underline ml-0.5">
                        Bantuan PUSTIKOM
                    </a>
                </div>

            </div>

            <!-- Footer Bawah Mobile -->
            <div class="lg:hidden text-center text-xs text-gray-400 pt-6">
                &copy; {{ date('Y') }} UIN Siber Syekh Nurjati Cirebon
            </div>
        </div>

    </div>
</body>
</html>
