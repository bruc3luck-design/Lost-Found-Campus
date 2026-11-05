<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-ink">Laporan Saya</h1>
                <p class="text-xs text-gray-500 mt-0.5">Kelola seluruh barang hilang dan barang temuan yang pernah Anda laporkan</p>
            </div>
            <a href="{{ route('items.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if ($items->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-8 sm:p-12 text-center shadow-sm max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-full bg-uin-50 text-uin-500 flex items-center justify-center mx-auto mb-4 border border-uin-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-ink mb-1">Belum Ada Laporan</h3>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                    Anda belum pernah menerbitkan laporan kehilangan atau penemuan barang. Jika Anda kehilangan atau menemukan sesuatu, laporkan segera.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('items.create', ['tipe' => 'hilang']) }}"
                       class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                        Lapor Barang Hilang
                    </a>
                    <a href="{{ route('items.create', ['tipe' => 'temuan']) }}"
                       class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 transition-colors shadow-sm">
                        Lapor Barang Temuan
                    </a>
                </div>
            </div>
        @else
            <!-- Daftar Kartu Laporan (Responsif Mobile & Desktop) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($items as $item)
                    <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm hover:shadow transition-shadow flex flex-col justify-between">
                        <div>
                            <!-- Header Kartu: Tipe, Status, & Tanggal -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $item->tipeBadgeClasses() }}">
                                        {{ $item->tipeLabel() }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $item->statusBadgeClasses() }}">
                                        {{ $item->statusLabel() }}
                                    </span>
                                </div>
                                <span class="text-[11px] text-gray-400">
                                    {{ $item->tanggal->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <!-- Baris Utama: Foto Thumbnail & Judul -->
                            <div class="flex items-start space-x-3.5 mb-3">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                         alt="{{ $item->judul }}"
                                         class="w-20 h-20 rounded-xl object-cover border border-gray-200 shrink-0 bg-gray-50">
                                @else
                                    <div class="w-20 h-20 rounded-xl bg-gray-100 border border-gray-200 flex flex-col items-center justify-center text-gray-400 shrink-0">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[9px] mt-0.5">Tanpa Foto</span>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <h2 class="text-sm font-bold text-ink truncate">{{ $item->judul }}</h2>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-1 leading-relaxed">{{ $item->deskripsi }}</p>

                                    <!-- Informasi Kategori & Lokasi -->
                                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-gray-600">
                                        <span class="inline-flex items-center">
                                            <svg class="w-3.5 h-3.5 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $item->category->nama }}
                                        </span>
                                        <span class="inline-flex items-center">
                                            <svg class="w-3.5 h-3.5 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $item->location->nama }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Titik Pengambilan Khusus Temuan -->
                            @if ($item->tipe === 'temuan' && $item->pickupPoint)
                                <div class="mb-3 px-3 py-1.5 rounded-lg bg-gold-100/50 border border-gold-500/30 text-[11px] text-ink flex items-center">
                                    <strong class="font-semibold text-uin-700 mr-1">Titik Pengambilan:</strong>
                                    {{ $item->pickupPoint->nama }}
                                </div>
                            @endif

                            @if ($item->disembunyikan)
                                <div class="mb-3 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-[11px] text-amber-800 flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                    Laporan ini disembunyikan oleh moderator/admin.
                                </div>
                            @endif
                        </div>

                        <!-- Baris Aksi Kartu -->
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            <div class="text-[11px] text-gray-500">
                                <span class="font-semibold text-ink">{{ $item->claims_count }}</span> klaim masuk
                            </div>

                            <div class="flex items-center space-x-2">
                                @can('update', $item)
                                    <a href="{{ route('items.edit', $item) }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-medium text-ink bg-gray-100 hover:bg-gray-200 transition-colors">
                                        Edit
                                    </a>
                                @endcan

                                @can('delete', $item)
                                    <form method="POST" action="{{ route('items.destroy', $item) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginasi -->
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
