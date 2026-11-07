<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Portal – Lost &amp; Found Campus UIN SSC</title>
    <meta name="description" content="Masuk ke portal Lost & Found Campus UIN Siber Syekh Nurjati Cirebon.">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col justify-center items-center px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white p-2 shadow-md border-2 border-gold-500/40 mb-3">
                <img src="{{ asset('images/logo-uinssc.png') }}" alt="Logo UIN SSC" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-ink">Lost &amp; Found Campus</h1>
            <p class="text-xs font-medium text-uin-500 tracking-wide mt-1">UIN SIBER SYEKH NURJATI CIREBON</p>
            <p class="text-xs text-gray-500 mt-0.5">Pusat Informasi Barang Hilang dan Temuan Kampus</p>
        </div>

        <!-- Kartu Formulir Login -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h2 class="text-lg font-bold text-ink">Masuk Akun Portal</h2>
                <p class="text-xs text-gray-500 mt-0.5">Gunakan NIM (Mahasiswa) atau NIP (Dosen) serta password portal akademik Anda.</p>
            </div>

            <!-- Pesan Error Umum -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <div class="font-semibold mb-1">Gagal Masuk:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Input Username (NIM / NIP) -->
                <div>
                    <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
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
                        placeholder="Contoh: 2281050030 atau 198810..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('username') border-rose-400 ring-rose-200 @enderror"
                    >
                </div>

                <!-- Input Password -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Password Portal
                    </label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            id="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password portal Anda"
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('password') border-rose-400 ring-rose-200 @enderror"
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
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
                            class="rounded border-gray-300 text-uin-500 shadow-sm focus:ring-uin-500"
                        >
                        <span class="ml-2 text-xs text-gray-600">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-uin-500 hover:bg-uin-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin-500 transition-colors"
                    >
                        Masuk ke Sistem
                    </button>
                </div>
            </form>

            <!-- Informasi Akun Dummy untuk Pengembangan -->
            @if (config('services.portal.driver') === 'fake')
                <div class="mt-6 pt-5 border-t border-dashed border-gray-200">
                    <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                        Akun Dummy Tersedia (Password: 123)
                    </div>
                    <div class="space-y-1.5 text-xs text-gray-600 bg-paper p-3 rounded-lg border border-gray-200">
                        <div>&bull; <strong>2281050030</strong> – Gamine (Mahasiswa)</div>
                        <div>&bull; <strong>2281050031</strong> – Gamine 2 (Mahasiswa)</div>
                        <div>&bull; <strong>198810112011012019</strong> – Gamine (Dosen Admin)</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer Copyright -->
        <p class="text-center text-xs text-gray-500 mt-6">
            &copy; {{ date('Y') }} PUSTIKOM UIN Siber Syekh Nurjati Cirebon.<br>
            Khusus civitas akademika aktif.
        </p>
    </div>
</body>
</html>
