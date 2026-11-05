<x-app-layout>
    <div class="space-y-6">
        <!-- Hero Banner Identitas Kampus -->
        <div class="bg-gradient-to-r from-uin-700 via-uin-500 to-uin-700 text-white rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden border border-gold-500/30">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-3 py-1 bg-gold-500 text-ink text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
                    Sistem Lost &amp; Found Kampus
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Pusat Informasi Barang Hilang &amp; Temuan
                </h1>
                <p class="mt-2 text-sm text-uin-50/90 leading-relaxed">
                    UIN Siber Syekh Nurjati Cirebon. Temukan barang Anda yang hilang atau bantu kembalikan barang temuan kepada pemilik sahnya.
                </p>

                <!-- Tombol CTA Cepat -->
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('items.create', ['tipe' => 'hilang']) }}"
                       class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Lapor Kehilangan
                    </a>
                    <a href="{{ route('items.create', ['tipe' => 'temuan']) }}"
                       class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-gold-500 hover:bg-gold-500/90 text-ink transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Lapor Penemuan
                    </a>
                </div>
            </div>

            <!-- Ikon dekoratif transparan -->
            <div class="absolute -right-8 -bottom-8 text-white/5 pointer-events-none">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Tab Navigasi: Barang Hilang vs Barang Temuan -->
        <div class="flex items-center border-b border-gray-200">
            <a href="{{ route('home', array_merge(request()->query(), ['tipe' => 'hilang', 'page' => 1])) }}"
               class="flex items-center py-3 px-5 border-b-2 font-bold text-sm transition-colors {{ $tipeAktif === 'hilang' ? 'border-rose-500 text-rose-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mr-2"></span>
                Barang Hilang
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold {{ $tipeAktif === 'hilang' ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $countHilang }}
                </span>
            </a>

            <a href="{{ route('home', array_merge(request()->query(), ['tipe' => 'temuan', 'page' => 1])) }}"
               class="flex items-center py-3 px-5 border-b-2 font-bold text-sm transition-colors {{ $tipeAktif === 'temuan' ? 'border-uin-500 text-uin-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span class="w-2.5 h-2.5 rounded-full bg-uin-500 mr-2"></span>
                Barang Temuan
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold {{ $tipeAktif === 'temuan' ? 'bg-uin-50 text-uin-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $countTemuan }}
                </span>
            </a>
        </div>

        <!-- Filter & Pencarian -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm"
             x-data="{ showFilterDetails: {{ request()->hasAny(['kategori', 'lokasi', 'dari', 'sampai']) ? 'true' : 'false' }} }">
            <form method="GET" action="{{ route('home') }}" class="space-y-4">
                <!-- Pertahankan Tab Aktif -->
                <input type="hidden" name="tipe" value="{{ $tipeAktif }}">

                <!-- Baris Pencarian Utama -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari berdasarkan nama barang atau ciri-ciri deskripsi..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm"
                        >
                    </div>

                    <div class="flex items-center space-x-2">
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 shadow-sm transition-colors flex items-center shrink-0"
                        >
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari Barang
                        </button>

                        <button
                            type="button"
                            @click="showFilterDetails = ! showFilterDetails"
                            class="px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors flex items-center shrink-0"
                        >
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                            <span x-show="showFilterDetails" class="ml-1 text-[10px]">&#9650;</span>
                            <span x-show="! showFilterDetails" class="ml-1 text-[10px]">&#9660;</span>
                        </button>
                    </div>
                </div>

                <!-- Detail Filter Tambahan (Kategori, Lokasi, Rentang Tanggal) -->
                <div x-show="showFilterDetails"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block font-semibold text-gray-600 mb-1">Kategori</label>
                        <select name="kategori" id="kategori" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs text-ink bg-white focus:ring-uin-500 focus:border-uin-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block font-semibold text-gray-600 mb-1">Lokasi Kejadian</label>
                        <select name="lokasi" id="lokasi" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs text-ink bg-white focus:ring-uin-500 focus:border-uin-500">
                            <option value="">Semua Lokasi</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('lokasi') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Dari -->
                    <div>
                        <label for="dari" class="block font-semibold text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari" id="dari" value="{{ request('dari') }}" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs text-ink bg-white focus:ring-uin-500 focus:border-uin-500">
                    </div>

                    <!-- Tanggal Sampai -->
                    <div>
                        <label for="sampai" class="block font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" id="sampai" value="{{ request('sampai') }}" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs text-ink bg-white focus:ring-uin-500 focus:border-uin-500">
                    </div>

                    <!-- Tombol Terapkan & Reset Filter -->
                    <div class="sm:col-span-2 lg:col-span-4 flex items-center justify-end space-x-2 pt-1">
                        <a href="{{ route('home', ['tipe' => $tipeAktif]) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            Reset Filter
                        </a>
                        <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Kartu Barang (12 Per Halaman) -->
        @if ($items->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-8 sm:p-12 text-center shadow-sm max-w-md mx-auto">
                <div class="w-16 h-16 rounded-full bg-uin-50 text-uin-500 flex items-center justify-center mx-auto mb-3 border border-uin-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-ink mb-1">Belum Ada Laporan</h3>
                <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                    Tidak ditemukan laporan barang {{ $tipeAktif === 'hilang' ? 'kehilangan' : 'temuan' }} yang cocok dengan filter atau kata kunci pencarian Anda.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                    @if (request()->hasAny(['q', 'kategori', 'lokasi', 'dari', 'sampai']))
                        <a href="{{ route('home', ['tipe' => $tipeAktif]) }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Hapus Filter
                        </a>
                    @endif
                    <a href="{{ route('items.create', ['tipe' => $tipeAktif]) }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 transition-colors shadow-sm">
                        Buat Laporan Baru
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($items as $item)
                    <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between group">
                        <div>
                            <!-- Foto Barang -->
                            <a href="{{ route('items.show', $item) }}" class="block relative aspect-video bg-gray-100 overflow-hidden">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}"
                                         alt="{{ $item->judul }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[11px] mt-1 font-medium text-gray-400">Foto Tidak Tersedia</span>
                                    </div>
                                @endif

                                <!-- Badge Status Berwarna di Pojok Kanan Atas Foto -->
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold shadow-sm border {{ $item->statusBadgeClasses() }}">
                                        {{ $item->statusLabel() }}
                                    </span>
                                </div>

                                <!-- Badge Tipe di Pojok Kiri Atas Foto -->
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold shadow-sm uppercase tracking-wider {{ $item->tipe === 'hilang' ? 'bg-rose-600 text-white' : 'bg-uin-500 text-white' }}">
                                        {{ $item->tipe === 'hilang' ? 'Hilang' : 'Temuan' }}
                                    </span>
                                </div>
                            </a>

                            <!-- Konten Kartu -->
                            <div class="p-4">
                                <!-- Kategori & Tanggal -->
                                <div class="flex items-center justify-between text-[11px] text-gray-500 mb-1.5">
                                    <span class="font-medium text-uin-700 bg-uin-50 px-2 py-0.5 rounded-md">
                                        {{ $item->category->nama }}
                                    </span>
                                    <span>
                                        {{ $item->tanggal->translatedFormat('d M Y') }}
                                    </span>
                                </div>

                                <!-- Judul Barang -->
                                <h3 class="font-bold text-sm text-ink group-hover:text-uin-500 transition-colors line-clamp-1 mb-1">
                                    <a href="{{ route('items.show', $item) }}">
                                        {{ $item->judul }}
                                    </a>
                                </h3>

                                <!-- Deskripsi Ringkas -->
                                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-3">
                                    {{ $item->deskripsi }}
                                </p>

                                <!-- Lokasi Kejadian -->
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="truncate">{{ $item->location->nama }}</span>
                                </div>

                                <!-- Titik Pengambilan Khusus Barang Temuan -->
                                @if ($item->tipe === 'temuan' && $item->pickupPoint)
                                    <div class="mt-2 text-[11px] text-gold-500 font-medium flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                        <span class="truncate">Pos: {{ $item->pickupPoint->nama }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Kartu: Pelapor & Link Detail -->
                        <div class="px-4 py-3 bg-paper/60 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-[11px] text-gray-500 truncate max-w-[60%]">
                                Oleh: <strong class="font-medium text-ink">{{ $item->user->name }}</strong>
                            </span>
                            <a href="{{ route('items.show', $item) }}"
                               class="text-xs font-semibold text-uin-500 hover:text-uin-700 flex items-center">
                                Detail
                                <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginasi dengan withQueryString() -->
            <div class="mt-8">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
