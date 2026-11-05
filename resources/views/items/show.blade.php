<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center space-x-2 text-xs text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-uin-500 transition-colors">Beranda</a>
                <span>&bull;</span>
                <a href="{{ route('home', ['tipe' => $item->tipe]) }}" class="hover:text-uin-500 transition-colors capitalize">
                    {{ $item->tipeLabel() }}
                </a>
                <span>&bull;</span>
                <span class="text-ink font-semibold truncate max-w-[200px]">{{ $item->judul }}</span>
            </div>

            <a href="{{ route('home') }}"
               class="text-xs font-semibold text-uin-500 hover:text-uin-700 flex items-center shrink-0">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Peringatan jika item disembunyikan -->
        @if ($item->disembunyikan)
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center space-x-2">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span><strong>Perhatian:</strong> Laporan ini sedang disembunyikan oleh administrator dari pencarian publik.</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 sm:p-8">
                <!-- Kolom Kiri: Foto Barang -->
                <div>
                    @if ($item->foto)
                        <div class="aspect-square rounded-2xl overflow-hidden border border-gray-200 shadow-inner bg-gray-50">
                            <img src="{{ asset('storage/' . $item->foto) }}"
                                 alt="{{ $item->judul }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-square rounded-2xl bg-gray-100 border border-gray-200 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs mt-2 font-medium">Foto Tidak Disertakan</span>
                        </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Info Lengkap & Pelapor -->
                <div class="flex flex-col justify-between">
                    <div>
                        <!-- Badges Header -->
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider {{ $item->tipe === 'hilang' ? 'bg-rose-600 text-white' : 'bg-uin-500 text-white' }}">
                                {{ $item->tipeLabel() }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $item->statusBadgeClasses() }}">
                                Status: {{ $item->statusLabel() }}
                            </span>
                        </div>

                        <!-- Judul -->
                        <h1 class="text-xl sm:text-2xl font-extrabold text-ink leading-tight mb-4">
                            {{ $item->judul }}
                        </h1>

                        <!-- Detail Informasi Metadata -->
                        <div class="space-y-2.5 py-4 border-y border-gray-100 text-xs">
                            <div class="flex items-start justify-between">
                                <span class="text-gray-500">Kategori:</span>
                                <span class="font-semibold text-ink text-right">{{ $item->category->nama }}</span>
                            </div>
                            <div class="flex items-start justify-between">
                                <span class="text-gray-500">Lokasi Kejadian:</span>
                                <span class="font-semibold text-ink text-right">{{ $item->location->nama }}</span>
                            </div>
                            <div class="flex items-start justify-between">
                                <span class="text-gray-500">Tanggal Kejadian:</span>
                                <span class="font-semibold text-ink text-right">{{ $item->tanggal->translatedFormat('d F Y') }}</span>
                            </div>

                            <!-- Titik Pengambilan Resmi -->
                            @if ($item->pickupPoint)
                                <div class="p-2.5 rounded-xl bg-gold-100/60 border border-gold-500/30 flex items-start justify-between">
                                    <span class="font-semibold text-uin-700">Titik Pengambilan:</span>
                                    <span class="font-bold text-ink text-right">
                                        {{ $item->pickupPoint->nama }}
                                        @if($item->pickupPoint->keterangan)
                                            <div class="text-[10px] text-gray-500 font-normal">({{ $item->pickupPoint->keterangan }})</div>
                                        @endif
                                    </span>
                                </div>
                            @endif

                            <!-- Info Pelapor & Prodi -->
                            <div class="p-3 rounded-xl bg-paper border border-gray-200/80">
                                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                    Informasi Pelapor
                                </div>
                                <div class="font-bold text-ink text-sm">{{ $item->user->name }}</div>
                                <div class="text-[11px] text-gray-600">
                                    {{ ucfirst($item->user->role) }} &bull; Program Studi: <strong>{{ $item->user->prodi ?? 'UIN SSC' }}</strong>
                                </div>
                                @if($item->user->fakultas)
                                    <div class="text-[10px] text-gray-500">Fakultas {{ $item->user->fakultas }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Deskripsi Barang -->
                        <div class="mt-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-ink mb-1.5">Deskripsi Lengkap</h3>
                            <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50/60 p-3.5 rounded-xl border border-gray-100">
                                {{ $item->deskripsi }}
                            </p>
                        </div>
                    </div>

                    <!-- Bagian Tombol Aksi Sesuai Peran Pengguna -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        @auth
                            @if (Auth::id() === $item->user_id)
                                <!-- Aksi untuk Pemilik Laporan -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-semibold text-uin-700 flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-uin-500 mr-1.5"></span>
                                            Ini adalah laporan Anda sendiri
                                        </span>
                                        <a href="{{ route('claims.incoming', $item) }}" class="font-semibold text-uin-500 hover:text-uin-700 underline">
                                            Lihat Klaim Masuk ({{ $item->claims_count }}) &rarr;
                                        </a>
                                    </div>

                                    @if ($item->status === 'diklaim')
                                        <form method="POST" action="{{ route('items.returned', $item) }}" onsubmit="return confirm('Apakah Anda yakin barang ini sudah diserahkan/dikembalikan kepada pemilik sah?');">
                                            @csrf
                                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors">
                                                &check; Tandai Barang Telah Dikembalikan
                                            </button>
                                        </form>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-2">
                                        <!-- Tombol Edit -->
                                        @can('update', $item)
                                            <a href="{{ route('items.edit', $item) }}"
                                               class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-semibold text-ink bg-gray-100 hover:bg-gray-200 transition-colors">
                                                Edit Laporan
                                            </a>
                                        @endcan

                                        <!-- Tombol Hapus -->
                                        @can('delete', $item)
                                            <form method="POST" action="{{ route('items.destroy', $item) }}"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');"
                                                  class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full py-2 px-3 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @else
                                <!-- Aksi untuk Non-Pemilik -->
                                @if ($item->isOpen())
                                    @if ($item->tipe === 'hilang')
                                        <!-- Tipe Hilang -> Tombol "Saya Menemukannya" -->
                                        <a href="{{ Route::has('claims.create') ? route('claims.create', $item) : '#' }}"
                                           class="w-full flex items-center justify-center py-3 px-4 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 shadow-md transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Saya Menemukannya
                                        </a>
                                    @else
                                        <!-- Tipe Temuan -> Tombol "Ini Barang Saya" -->
                                        <a href="{{ Route::has('claims.create') ? route('claims.create', $item) : '#' }}"
                                           class="w-full flex items-center justify-center py-3 px-4 rounded-xl text-xs font-bold text-ink bg-gold-500 hover:bg-gold-500/90 shadow-md transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Ini Barang Saya
                                        </a>
                                    @endif
                                @else
                                    <div class="text-center py-2.5 px-4 rounded-xl bg-gray-100 text-gray-500 text-xs font-semibold">
                                        Laporan barang ini telah berstatus {{ $item->statusLabel() }} (Klaim ditutup).
                                    </div>
                                @endif
                            @endif
                        @else
                            <!-- Belum Login: Arahkan Masuk Portal -->
                            <div class="p-3.5 rounded-xl bg-paper border border-gray-200 text-center">
                                <p class="text-xs text-gray-600 mb-2">
                                    Ingin mengajukan klaim untuk barang ini?
                                </p>
                                <a href="{{ route('login') }}"
                                   class="inline-block py-2 px-5 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 transition-colors shadow-sm">
                                    Masuk dengan Akun Portal untuk Klaim
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
