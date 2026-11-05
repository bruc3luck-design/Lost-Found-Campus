<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Portal – Lost &amp; Found Campus UIN SSC</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-screen flex flex-col justify-center items-center px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-uin-500 text-gold-500 shadow-lg border-2 border-gold-500/30 mb-3">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
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
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Password Portal
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password portal Anda"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('password') border-rose-400 ring-rose-200 @enderror"
                    >
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
                        <div>&bull; <strong>2281050030</strong> – Langit Kala Fajar (Mahasiswa)</div>
                        <div>&bull; <strong>2281050031</strong> – Rindu Senja (Mahasiswa)</div>
                        <div>&bull; <strong>198810112011012019</strong> – Bintang Hadar (Dosen / Admin)</div>
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
