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
<body class="bg-gradient-to-b from-[#FDFCF7] via-[#FAF7EE] to-[#F5EEDC] text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center p-3 sm:p-5 lg:p-7">

    <!-- ======================================================== -->
    <!-- MAIN CANVAS FRAME (Tampilan Penuh Melengkung Elegan)      -->
    <!-- ======================================================== -->
    <div class="w-full max-w-7xl min-h-[calc(100vh-2.5rem)] bg-gradient-to-b from-[#FDFCF7] via-[#FAF7EE] to-[#F3ECD8] rounded-[2.2rem] sm:rounded-[2.8rem] shadow-xl border border-white/80 p-3 sm:p-5 lg:p-7 flex flex-col justify-between overflow-hidden">
        
        <div class="flex flex-col lg:flex-row items-stretch flex-1 gap-4 lg:gap-8">
            
            <!-- ======================================================== -->
            <!-- KOLOM KIRI: FORMULIR LOGIN & DERETAN LOGO BERJEJER       -->
            <!-- ======================================================== -->
            <div class="w-full lg:w-[48%] flex flex-col justify-between p-3 sm:p-6 lg:p-8 xl:p-10">
                
                <!-- KOTAK HIJAU: Deretan Logo Berjejer Horizontal Sesuai Gambar 2 -->
                <div class="flex items-center space-x-3 sm:space-x-4 lg:space-x-5 mb-6 sm:mb-8 pb-4">
                    <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-12 sm:h-14 w-auto object-contain" title="UIN Siber Syekh Nurjati Cirebon">
                    <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Akreditasi Unggul" class="h-10 sm:h-12 w-auto object-contain" title="Akreditasi Unggul">
                    <img src="{{ asset('images/uin/badge-blu.png') }}" alt="BLU Speed" class="h-8 sm:h-9 w-auto object-contain" title="BLU Speed">
                    <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="Cyber Islamic University" class="h-9 sm:h-11 w-auto object-contain" title="Cyber Islamic University">
                </div>

                <!-- Konten Tengah: Judul & Form -->
                <div class="my-auto py-1">
                    <div class="text-left mb-6 sm:mb-8">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                            Masuk ke Akun
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 font-medium">
                            Portal Single Sign-On (SSO) Civitas UIN SSC
                        </p>
                    </div>

                    <!-- Alert Error Validasi -->
                    @if ($errors->any())
                        <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                            <div class="font-bold mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Kredensial Tidak Sesuai
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulir Login Pill Rounded -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Field NIM / NIP -->
                        <div>
                            <label for="username" class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5 pl-1">
                                NIM / NIP (Nomor Induk)
                            </label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Contoh: 2281050030 atau 198810..."
                                class="w-full px-5 py-3.5 rounded-full bg-white border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F5BD47] focus:border-transparent transition-all shadow-xs @error('username') border-rose-400 ring-rose-200 @enderror"
                            >
                        </div>

                        <!-- Field Password -->
                        <div x-data="{ showPassword: false }">
                            <label for="password" class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1.5 pl-1">
                                Password Portal SSO
                            </label>
                            <div class="relative">
                                <input
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    id="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password portal Anda"
                                    class="w-full pl-5 pr-12 py-3.5 rounded-full bg-white border border-gray-200 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F5BD47] focus:border-transparent transition-all shadow-xs @error('password') border-rose-400 ring-rose-200 @enderror"
                                >
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
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
                        <div class="flex items-center justify-between pt-1 pl-1">
                            <label class="inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"
                                >
                                <span class="ml-2 text-xs text-gray-500">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <!-- Tombol Submit Warna Kuning Gold -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-3.5 px-6 rounded-full text-sm font-bold text-gray-900 bg-[#F5BD47] hover:bg-[#F0B336] active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F5BD47] transition-all shadow-md flex items-center justify-center space-x-2"
                            >
                                <span>Masuk Portal SSO</span>
                                <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Tombol Bantuan Helpdesk PUSTIKOM -->
                    <div class="mt-4 pt-2">
                        <a href="https://uinssc.ac.id" target="_blank" rel="noopener noreferrer"
                           class="w-full py-2.5 px-4 rounded-full border border-gray-300/80 bg-white/70 hover:bg-white text-gray-700 text-xs font-semibold flex items-center justify-center transition-all shadow-2xs">
                            <svg class="w-3.5 h-3.5 mr-2 text-uin-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Layanan Helpdesk PUSTIKOM UIN SSC</span>
                        </a>
                    </div>
                </div>

                <!-- Bagian Bawah Kiri: Copyright & Info -->
                <div class="pt-6 border-t border-gray-200/50 flex flex-col sm:flex-row items-center justify-between text-[11px] text-gray-500 gap-2">
                    <span>&copy; {{ date('Y') }} UIN Siber Syekh Nurjati</span>
                    <a href="{{ route('home') }}" class="font-semibold text-gray-700 hover:text-uin-500 underline">
                        Kembali ke Beranda
                    </a>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- KOLOM KANAN: FOTO GEDUNG SIBER BERSIH (Murni Tanpa Widget)-->
            <!-- ======================================================== -->
            <div class="w-full lg:w-[52%] p-1 sm:p-2 relative flex items-stretch">
                <div class="w-full h-80 sm:h-96 lg:h-full min-h-[460px] lg:min-h-full rounded-[1.8rem] sm:rounded-[2.4rem] overflow-hidden shadow-lg border border-black/5 relative group">
                    <!-- Foto Gedung Siber UIN SSC Murni Bersih -->
                    <img src="{{ asset('images/uin/gedung-siber.jpg') }}"
                         alt="Gedung Siber UIN Siber Syekh Nurjati Cirebon"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">
                </div>
            </div>

        </div>

    </div>

</body>
</html>
