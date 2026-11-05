<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-ink">Beranda Lost &amp; Found</h1>
                <p class="text-xs text-gray-500 mt-0.5">Pusat Laporan Barang Hilang dan Temuan UIN Siber Syekh Nurjati Cirebon</p>
            </div>
            @auth
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ Auth::user()->isAdmin() ? 'bg-amber-100 text-amber-800' : 'bg-uin-50 text-uin-700' }}">
                        {{ Auth::user()->isAdmin() ? 'Administrator' : ucfirst(Auth::user()->role) }}
                    </span>
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Hero Banner Identitas Kampus -->
        <div class="bg-gradient-to-r from-uin-700 via-uin-500 to-uin-700 text-white rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden border border-gold-500/30">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-3 py-1 bg-gold-500 text-ink text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
                    Sistem Terintegrasi Kampus
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Kehilangan Barang atau Menemukan Barang di Kampus?
                </h2>
                <p class="mt-2 text-sm text-uin-50/90 leading-relaxed">
                    Laporkan segera atau cari barang milik Anda melalui sistem Lost &amp; Found resmi UIN Siber Syekh Nurjati Cirebon.
                </p>
            </div>

            <!-- Hiasan latar icon transparan -->
            <div class="absolute -right-6 -bottom-6 text-white/5 pointer-events-none">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Kartu Sambutan & Status Fase 1 -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-sm">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-xl bg-uin-50 text-uin-500 flex items-center justify-center shrink-0 border border-uin-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-ink">Fase 1 Berhasil Diselesaikan!</h3>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Fondasi sistem, autentikasi portal terpisah (PortalAuthenticator + FakePortalAuthenticator), skema database (Users, Categories, Locations, Items, Claims), seeder data master &amp; dummy, panel admin Filament v3, dan tema visual UIN SSC telah siap.
                    </p>

                    @auth
                        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-600">
                            <div><strong>Nama:</strong> {{ Auth::user()->name }}</div>
                            <div><strong>NIM/NIP:</strong> {{ Auth::user()->username }}</div>
                            <div><strong>Role:</strong> <span class="capitalize">{{ Auth::user()->role }}</span></div>
                            <div><strong>Fakultas:</strong> {{ Auth::user()->fakultas ?? '-' }}</div>
                            <div><strong>Prodi:</strong> {{ Auth::user()->prodi ?? '-' }}</div>
                            <div><strong>No. WA:</strong> {{ Auth::user()->no_wa ?? '-' }}</div>
                            <div><strong>Status Admin:</strong> {{ Auth::user()->isAdmin() ? 'Ya' : 'Bukan' }}</div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
