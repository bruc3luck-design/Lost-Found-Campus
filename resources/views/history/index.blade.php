<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-ink">Riwayat Saya</h1>
                <p class="text-xs text-gray-500 mt-0.5">Kelola seluruh barang yang Anda laporkan dan pantau status klaim Anda</p>
            </div>
            <a href="{{ route('items.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Tab Navigasi: Laporan Saya vs Klaim Saya -->
        <div class="flex items-center border-b border-gray-200">
            <a href="{{ route('my.history', ['tab' => 'laporan']) }}"
               class="flex items-center py-3 px-5 border-b-2 font-bold text-sm transition-colors {{ $activeTab === 'laporan' ? 'border-uin-500 text-uin-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Saya
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold {{ $activeTab === 'laporan' ? 'bg-uin-50 text-uin-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $myItems->total() }}
                </span>
            </a>

            <a href="{{ route('my.history', ['tab' => 'klaim']) }}"
               class="flex items-center py-3 px-5 border-b-2 font-bold text-sm transition-colors {{ $activeTab === 'klaim' ? 'border-uin-500 text-uin-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Klaim Saya
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold {{ $activeTab === 'klaim' ? 'bg-uin-50 text-uin-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $myClaims->total() }}
                </span>
            </a>
        </div>

        <!-- ========================================== -->
        <!-- KONTEN TAB 1: LAPORAN SAYA -->
        <!-- ========================================== -->
        @if ($activeTab === 'laporan')
            @if ($myItems->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200/80 p-8 sm:p-12 text-center shadow-sm max-w-md mx-auto">
                    <div class="w-14 h-14 rounded-full bg-uin-50 text-uin-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-ink mb-1">Belum Ada Laporan</h3>
                    <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                        Anda belum pernah membuat laporan barang hilang atau temuan di portal ini.
                    </p>
                    <a href="{{ route('items.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 transition-colors shadow-sm">
                        Buat Laporan Sekarang
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($myItems as $item)
                        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm hover:shadow transition-shadow flex flex-col justify-between">
                            <div>
                                <!-- Header: Tipe & Status -->
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $item->tipe === 'hilang' ? 'bg-rose-600 text-white' : 'bg-uin-500 text-white' }}">
                                            {{ $item->tipeLabel() }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->statusBadgeClasses() }}">
                                            {{ $item->statusLabel() }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-gray-400">
                                        {{ $item->tanggal->translatedFormat('d M Y') }}
                                    </span>
                                </div>

                                <!-- Body: Foto & Detail -->
                                <div class="flex items-start space-x-3.5 mb-3">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-gray-200">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 shrink-0 border border-gray-200">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-sm text-ink truncate">
                                            <a href="{{ route('items.show', $item) }}" class="hover:text-uin-500 transition-colors">
                                                {{ $item->judul }}
                                            </a>
                                        </h3>
                                        <p class="text-xs text-gray-500 line-clamp-2 mt-0.5 leading-relaxed">{{ $item->deskripsi }}</p>
                                        <div class="text-[11px] text-gray-500 mt-1">
                                            {{ $item->category->nama }} &bull; {{ $item->location->nama }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Titik Pengambilan jika temuan -->
                                @if ($item->pickupPoint)
                                    <div class="mb-3 px-3 py-1.5 rounded-lg bg-gold-100/50 border border-gold-500/30 text-[11px] text-ink flex items-center">
                                        <strong class="text-uin-700 mr-1">Titik Pengambilan:</strong> {{ $item->pickupPoint->nama }}
                                    </div>
                                @endif

                                <!-- Info Klaim yang Disetujui + Tombol WhatsApp -->
                                @if ($item->approvedClaim)
                                    <div class="mb-3 p-3 rounded-xl bg-emerald-50 border border-emerald-200 space-y-2">
                                        <div class="text-xs text-emerald-900 flex items-center justify-between">
                                            <span>Klaim disetujui untuk: <strong>{{ $item->approvedClaim->claimer->name }}</strong></span>
                                        </div>

                                        @php
                                            $waPenklaim = $item->approvedClaim->claimer->nomorWaFormatted();
                                            $waUrlPemilik = $waPenklaim ? 'https://wa.me/' . $waPenklaim . '?text=' . urlencode("Halo {$item->approvedClaim->claimer->name}, terkait laporan barang {$item->judul} yang telah Anda klaim di Lost & Found UIN SSC.") : null;
                                        @endphp

                                        @if ($waUrlPemilik)
                                            <a href="{{ $waUrlPemilik }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5 mr-1 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.539 1.765.814 2.791.814 3.179 0 5.766-2.587 5.767-5.766.001-3.181-2.585-5.766-5.767-5.766zm3.374 8.232c-.143.402-.834.774-1.162.825-.329.051-.734.073-2.18-.518-1.745-.714-2.874-2.483-2.96-2.599-.088-.116-.708-.941-.708-1.793s.447-1.272.607-1.446c.16-.175.348-.218.465-.218.116 0 .232.001.334.006.107.005.25.04.391.378.143.348.49 1.196.533 1.283.043.087.072.189.014.305-.058.116-.087.189-.174.29-.087.102-.183.228-.261.306-.088.087-.179.182-.077.357.102.174.453.748.972 1.211.669.596 1.233.78 1.408.867.174.088.276.073.378-.044.103-.116.436-.508.552-.682.116-.174.232-.146.392-.087.16.058 1.017.479 1.191.566.175.088.291.131.334.204.043.073.043.422-.1 1.224z"/>
                                                </svg>
                                                Hubungi WhatsApp Penklaim ({{ $item->approvedClaim->claimer->no_wa }})
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Baris Aksi -->
                            <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                                <a href="{{ route('claims.incoming', $item) }}" class="inline-flex items-center text-xs font-semibold text-uin-500 hover:text-uin-700">
                                    <svg class="w-4 h-4 mr-1 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    Klaim Masuk ({{ $item->claims_count }})
                                </a>

                                <div class="flex items-center space-x-1.5">
                                    <!-- Tombol Tandai Dikembalikan jika status diklaim -->
                                    @if ($item->status === 'diklaim')
                                        <form method="POST" action="{{ route('items.returned', $item) }}" onsubmit="return confirm('Apakah Anda yakin barang ini sudah diserahkan/dikembalikan kepada pemilik sah?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                                                &check; Tandai Dikembalikan
                                            </button>
                                        </form>
                                    @endif

                                    @can('update', $item)
                                        <a href="{{ route('items.edit', $item) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete', $item)
                                        <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $myItems->links() }}
                </div>
            @endif
        @endif

        <!-- ========================================== -->
        <!-- KONTEN TAB 2: KLAIM SAYA -->
        <!-- ========================================== -->
        @if ($activeTab === 'klaim')
            @if ($myClaims->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200/80 p-8 sm:p-12 text-center shadow-sm max-w-md mx-auto">
                    <div class="w-14 h-14 rounded-full bg-gold-100 text-gold-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-ink mb-1">Belum Ada Pengajuan Klaim</h3>
                    <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                        Anda belum pernah mengajukan klaim untuk barang hilang atau barang temuan apapun.
                    </p>
                    <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 transition-colors shadow-sm">
                        Cari Barang di Beranda
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($myClaims as $claim)
                        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm {{ $claim->status === 'disetujui' ? 'ring-2 ring-emerald-500/30 bg-emerald-50/10' : '' }}">
                            <!-- Baris Header Klaim -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3 mb-3">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Diajukan pada: {{ $claim->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <h3 class="font-bold text-sm text-ink mt-0.5">
                                        <a href="{{ route('items.show', $claim->item) }}" class="hover:text-uin-500 transition-colors">
                                            {{ $claim->item->judul }}
                                        </a>
                                    </h3>
                                    <div class="text-xs text-gray-500">
                                        Pelapor: <strong>{{ $claim->item->user->name }}</strong> ({{ $claim->item->user->prodi ?? 'UIN SSC' }})
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $claim->statusBadgeClasses() }}">
                                        Klaim: {{ $claim->statusLabel() }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border {{ $claim->item->statusBadgeClasses() }}">
                                        Barang: {{ $claim->item->statusLabel() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Bukti yang Diajukan -->
                            <div class="text-xs mb-3">
                                <span class="font-semibold text-gray-600">Bukti yang Anda kirimkan:</span>
                                <p class="text-gray-700 bg-gray-50 p-2.5 rounded-xl mt-1 leading-relaxed border border-gray-200/60">
                                    {{ $claim->bukti }}
                                </p>
                            </div>

                            @if ($claim->catatan)
                                <div class="text-xs mb-3 p-2.5 rounded-xl bg-gray-50 border border-gray-200 text-gray-700">
                                    <strong class="font-semibold text-ink">Catatan dari Pelapor:</strong> {{ $claim->catatan }}
                                </div>
                            @endif

                            <!-- JIKA KLAIM DISETUJUI: TAMPILKAN TOMBOL WHATSAPP PELAPOR & TITIK PENGAMBILAN -->
                            @if ($claim->status === 'disetujui')
                                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 space-y-2.5">
                                    <div class="text-xs text-emerald-950 font-semibold flex items-center">
                                        <svg class="w-4 h-4 text-emerald-600 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Selamat! Klaim Anda telah disetujui oleh pelapor barang.
                                    </div>

                                    @php
                                        $waPelapor = $claim->item->user->nomorWaFormatted();
                                        $waUrlPenklaim = $waPelapor ? 'https://wa.me/' . $waPelapor . '?text=' . urlencode("Halo {$claim->item->user->name}, klaim saya atas barang {$claim->item->judul} telah disetujui di Lost & Found UIN SSC. Kapan dan di mana saya bisa mengambil barang tersebut?") : null;
                                    @endphp

                                    <div class="flex flex-wrap items-center gap-3">
                                        @if ($waUrlPenklaim)
                                            <a href="{{ $waUrlPenklaim }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.539 1.765.814 2.791.814 3.179 0 5.766-2.587 5.767-5.766.001-3.181-2.585-5.766-5.767-5.766zm3.374 8.232c-.143.402-.834.774-1.162.825-.329.051-.734.073-2.18-.518-1.745-.714-2.874-2.483-2.96-2.599-.088-.116-.708-.941-.708-1.793s.447-1.272.607-1.446c.16-.175.348-.218.465-.218.116 0 .232.001.334.006.107.005.25.04.391.378.143.348.49 1.196.533 1.283.043.087.072.189.014.305-.058.116-.087.189-.174.29-.087.102-.183.228-.261.306-.088.087-.179.182-.077.357.102.174.453.748.972 1.211.669.596 1.233.78 1.408.867.174.088.276.073.378-.044.103-.116.436-.508.552-.682.116-.174.232-.146.392-.087.16.058 1.017.479 1.191.566.175.088.291.131.334.204.043.073.043.422-.1 1.224z"/>
                                                </svg>
                                                Hubungi Pelapor via WhatsApp ({{ $claim->item->user->no_wa }})
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-500">Nomor WhatsApp pelapor belum dicantumkan.</span>
                                        @endif

                                        @if ($claim->item->pickupPoint)
                                            <div class="text-xs text-emerald-900 bg-white px-3 py-1.5 rounded-lg border border-emerald-300">
                                                <strong>Titik Pengambilan:</strong> {{ $claim->item->pickupPoint->nama }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $myClaims->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
