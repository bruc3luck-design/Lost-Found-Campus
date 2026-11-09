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
<body class="bg-[#838B98] text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-10">

    <!-- ======================================================== -->
    <!-- MAIN CANVAS FRAME (Gaya Referensi Canvas Card Melengkung) -->
    <!-- ======================================================== -->
    <div class="w-full max-w-5xl 2xl:max-w-6xl bg-gradient-to-b from-[#FDFCF7] via-[#FAF7EE] to-[#F3ECD8] rounded-[2.2rem] sm:rounded-[2.8rem] shadow-2xl border border-white/60 p-3 sm:p-5 lg:p-6 relative overflow-hidden">
        
        <div class="flex flex-col lg:flex-row items-stretch">
            
            <!-- ======================================================== -->
            <!-- KOLOM KIRI: FORMULIR LOGIN (Aksen Soft Ivory & Gold)     -->
            <!-- ======================================================== -->
            <div class="w-full lg:w-[48%] flex flex-col justify-between p-4 sm:p-8 lg:p-10 xl:p-12">
                
                <!-- Header Atas: Pill Brand Capsule (seperti referensi 'Crextio') -->
                <div class="flex items-center justify-between mb-6 sm:mb-8">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-4 py-2 rounded-full border border-gray-300/80 bg-white/70 backdrop-blur-xs text-xs font-bold text-gray-800 shadow-2xs hover:bg-white hover:border-gray-400 transition-all">
                        <img src="{{ asset('images/uin/logo-uinssc.png') }}" alt="Logo UIN SSC" class="h-5 w-auto object-contain mr-2">
                        <span>Lost &amp; Found</span>
                    </a>

                    <!-- Mobile Tombol Tutup / Kembali -->
                    <a href="{{ route('home') }}" class="lg:hidden p-2 rounded-full bg-white/80 text-gray-500 hover:text-gray-900 shadow-xs" title="Kembali ke Beranda">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>

                <!-- Konten Tengah: Judul & Form -->
                <div class="my-auto py-2">
                    <div class="text-center sm:text-left mb-6 sm:mb-8">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                            Masuk ke Akun
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1.5 font-medium">
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

                    <!-- Formulir Login Pill Rounded (sesuai referensi gambar) -->
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

                        <!-- Tombol Submit Warna Kuning Gold (Persis seperti referensi tombol Submit) -->
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

                    <!-- Tombol Cepat Bantuan Kampus (Model Apple / Google Pill di referensi) -->
                    <div class="mt-4 pt-2">
                        <a href="https://uinssc.ac.id" target="_blank" rel="noopener noreferrer"
                           class="w-full py-2.5 px-4 rounded-full border border-gray-300/80 bg-white/60 hover:bg-white text-gray-700 text-xs font-semibold flex items-center justify-center transition-all shadow-2xs">
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
            <!-- KOLOM KANAN: FOTO GEDUNG SIBER DENGAN FLOATING WIDGETS    -->
            <!-- ======================================================== -->
            <div class="w-full lg:w-[52%] p-2 sm:p-3 relative">
                <div class="relative h-72 sm:h-96 lg:h-full min-h-[460px] lg:min-h-[580px] rounded-[1.8rem] sm:rounded-[2.2rem] overflow-hidden shadow-lg border border-black/5 group">
                    
                    <!-- Foto Gedung Siber UIN SSC (Gambar 2 yang Diunggah Pengguna) -->
                    <img src="{{ asset('images/uin/gedung-siber.jpg') }}"
                         alt="Gedung Siber UIN Siber Syekh Nurjati Cirebon"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">

                    <!-- Overlay Gradient Halus -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/15 to-black/30 pointer-events-none"></div>

                    <!-- Tombol Tutup 'X' Pojok Kanan Atas (Persis seperti referensi gambar) -->
                    <a href="{{ route('home') }}"
                       class="absolute top-4 right-4 sm:top-5 sm:right-5 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-gray-800 flex items-center justify-center shadow-lg transition-all hover:scale-110 active:scale-95"
                       title="Kembali ke Beranda">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>

                    <!-- Floating Widget 1: Task Review Pill Kuning Emas (Persis seperti referensi) -->
                    <div class="absolute top-5 left-5 sm:top-6 sm:left-6 max-w-[240px] bg-[#F5BD47] text-gray-900 rounded-2xl p-3 shadow-xl backdrop-blur-md border border-white/30 transform hover:-translate-y-0.5 transition-transform">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-900">Gedung Siber &bull; PUSTIKOM</span>
                            <span class="w-2 h-2 rounded-full bg-gray-900 animate-pulse"></span>
                        </div>
                        <div class="text-xs font-bold leading-tight">
                            Pusat Layanan Terpadu Kampus
                        </div>
                        <div class="text-[10px] text-gray-800 mt-0.5 font-medium">
                            Lantai 1 Gerbang Utama
                        </div>
                    </div>

                    <!-- Floating Widget 2: Glassmorphism Badges Akreditasi (Tengah Bawah) -->
                    <div class="absolute bottom-20 left-5 right-5 sm:left-6 sm:right-auto bg-white/20 backdrop-blur-md rounded-2xl p-3.5 border border-white/30 text-white shadow-xl max-w-xs">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-amber-300 mb-1.5 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Identitas Resmi UIN SSC
                        </div>
                        <div class="flex items-center space-x-3 pt-1">
                            <img src="{{ asset('images/uin/badge-unggul.png') }}" alt="Unggul" class="h-8 w-auto object-contain drop-shadow" title="Akreditasi Unggul">
                            <img src="{{ asset('images/uin/badge-blu.png') }}" alt="BLU" class="h-6 w-auto object-contain drop-shadow" title="BLU Speed">
                            <img src="{{ asset('images/uin/badge-ciu.png') }}" alt="CIU" class="h-7 w-auto object-contain drop-shadow" title="Cyber Islamic University">
                        </div>
                    </div>

                    <!-- Floating Widget 3: Card Putih Bawah (Persis seperti kartu 'Daily Meeting' di referensi) -->
                    <div class="absolute bottom-4 left-5 right-5 sm:left-6 sm:right-auto bg-white/95 backdrop-blur-md rounded-2xl p-3 shadow-xl border border-white max-w-xs">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-xl bg-uin-50 text-uin-700 flex items-center justify-center shrink-0 border border-uin-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-900 leading-tight">Titik Serah Terima Resmi</div>
                                <div class="text-[10px] text-gray-500">Pos Satpam &amp; PUSTIKOM Lantai 1</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</body>
</html>
