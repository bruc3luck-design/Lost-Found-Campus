<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-ink">Klaim Masuk</h1>
                <p class="text-xs text-gray-500 mt-0.5">Daftar pengajuan klaim untuk laporan: <strong>{{ $item->judul }}</strong></p>
            </div>
            <a href="{{ route('my.history', ['tab' => 'laporan']) }}" class="text-xs font-medium text-uin-500 hover:text-uin-700 underline">
                &larr; Kembali ke Riwayat Saya
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Ringkasan Info Barang & Status -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start space-x-3.5">
                @if ($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-gray-200">
                @else
                    <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 shrink-0 border border-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                <div>
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->tipe === 'hilang' ? 'bg-rose-600 text-white' : 'bg-uin-500 text-white' }}">
                            {{ $item->tipeLabel() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $item->statusBadgeClasses() }}">
                            {{ $item->statusLabel() }}
                        </span>
                    </div>
                    <h2 class="font-bold text-sm text-ink">{{ $item->judul }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $item->category->nama }} &bull; {{ $item->location->nama }} &bull; {{ $item->tanggal->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            <!-- Tombol Tandai Dikembalikan jika sudah disetujui/diklaim -->
            @if ($item->status === 'diklaim')
                <form method="POST" action="{{ route('items.returned', $item) }}" onsubmit="return confirm('Apakah Anda yakin barang ini sudah diserahkan/dikembalikan kepada pemilik sah?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors">
                        &check; Tandai Barang Dikembalikan
                    </button>
                </form>
            @endif
        </div>

        <!-- Daftar Klaim yang Masuk -->
        @if ($item->claims->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-200/80 p-8 text-center shadow-sm max-w-md mx-auto">
                <div class="w-14 h-14 rounded-full bg-uin-50 text-uin-500 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-ink mb-1">Belum Ada Klaim</h3>
                <p class="text-xs text-gray-500">
                    Belum ada pihak yang mengajukan klaim atas barang ini. Notifikasi akan diperbarui ketika ada klaim masuk.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($item->claims as $claim)
                    <div class="bg-white rounded-2xl border border-gray-200/80 p-5 sm:p-6 shadow-sm {{ $claim->status === 'disetujui' ? 'ring-2 ring-emerald-500/40 bg-emerald-50/20' : '' }}">
                        <!-- Header Klaim: Penklaim & Status -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3 mb-4">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-sm text-ink">{{ $claim->claimer->name }}</span>
                                    <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">({{ $claim->claimer->role }} &bull; {{ $claim->claimer->username }})</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Program Studi: <strong>{{ $claim->claimer->prodi ?? '-' }}</strong> &bull;
                                    Diajukan pada: {{ $claim->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </div>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $claim->statusBadgeClasses() }}">
                                Status Klaim: {{ $claim->statusLabel() }}
                            </span>
                        </div>

                        <!-- Isi Uraian Bukti -->
                        <div class="mb-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Uraian Bukti yang Diajukan:</h4>
                            <p class="text-xs text-gray-700 leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-200 whitespace-pre-line">
                                {{ $claim->bukti }}
                            </p>
                        </div>

                        <!-- Foto Bukti jika ada -->
                        @if ($claim->bukti_foto)
                            <div class="mb-4">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Lampiran Foto Bukti:</h4>
                                <a href="{{ asset('storage/' . $claim->bukti_foto) }}" target="_blank" class="inline-block relative group">
                                    <img src="{{ asset('storage/' . $claim->bukti_foto) }}" alt="Bukti Foto" class="w-32 h-32 rounded-xl object-cover border border-gray-300 shadow-sm group-hover:opacity-90 transition-opacity">
                                    <span class="text-[10px] text-uin-500 underline block mt-1">Klik untuk memperbesar</span>
                                </a>
                            </div>
                        @endif

                        <!-- Jika Status Disetujui: Tampilkan Kontak WhatsApp & Titik Pengambilan -->
                        @if ($claim->status === 'disetujui')
                            <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 space-y-3">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="text-xs text-emerald-900">
                                        <strong class="font-bold">Klaim telah disetujui!</strong> Hubungi penklaim untuk penyerahan barang.
                                    </div>

                                    @php
                                        $waNum = $claim->claimer->nomorWaFormatted();
                                        $waUrl = $waNum ? 'https://wa.me/' . $waNum . '?text=' . urlencode("Halo {$claim->claimer->name}, saya telah menyetujui klaim Anda untuk barang {$item->judul} di Lost & Found UIN SSC.") : null;
                                    @endphp

                                    @if ($waUrl)
                                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors">
                                            <svg class="w-4 h-4 mr-1.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.539 1.765.814 2.791.814 3.179 0 5.766-2.587 5.767-5.766.001-3.181-2.585-5.766-5.767-5.766zm3.374 8.232c-.143.402-.834.774-1.162.825-.329.051-.734.073-2.18-.518-1.745-.714-2.874-2.483-2.96-2.599-.088-.116-.708-.941-.708-1.793s.447-1.272.607-1.446c.16-.175.348-.218.465-.218.116 0 .232.001.334.006.107.005.25.04.391.378.143.348.49 1.196.533 1.283.043.087.072.189.014.305-.058.116-.087.189-.174.29-.087.102-.183.228-.261.306-.088.087-.179.182-.077.357.102.174.453.748.972 1.211.669.596 1.233.78 1.408.867.174.088.276.073.378-.044.103-.116.436-.508.552-.682.116-.174.232-.146.392-.087.16.058 1.017.479 1.191.566.175.088.291.131.334.204.043.073.043.422-.1 1.224z"/>
                                            </svg>
                                            Hubungi via WhatsApp ({{ $claim->claimer->no_wa }})
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">Nomor WhatsApp penklaim belum tercantum.</span>
                                    @endif
                                </div>

                                @if ($item->pickupPoint)
                                    <div class="text-xs text-emerald-800 border-t border-emerald-200/60 pt-2 flex items-center">
                                        <strong class="mr-1">Titik Pengambilan Resmi:</strong> {{ $item->pickupPoint->nama }} ({{ $item->pickupPoint->keterangan ?? 'Layanan Kampus' }})
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Tombol Setujui / Tolak untuk Klaim Berstatus Menunggu -->
                        @if ($claim->status === 'menunggu' && $item->isOpen())
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-end space-x-3">
                                <!-- Form Tolak -->
                                <form method="POST" action="{{ route('claims.reject', $claim) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak klaim ini?');">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                        Tolak Klaim
                                    </button>
                                </form>

                                <!-- Form Setujui -->
                                <form method="POST" action="{{ route('claims.approve', $claim) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui klaim ini? Menyetujui klaim ini akan otomatis menolak klaim lain yang berstatus menunggu dan mengubah status barang menjadi Diklaim.');">
                                    @csrf
                                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 shadow-sm transition-colors">
                                        &check; Setujui Klaim Ini
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
