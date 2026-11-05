<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-ink">Edit Laporan Barang</h1>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui rincian laporan "{{ $item->judul }}"</p>
            </div>
            <a href="{{ route('my.items') }}" class="text-xs font-medium text-uin-500 hover:text-uin-700 underline">
                &larr; Batal &amp; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8"
             x-data="{
                 tipe: '{{ old('tipe', $item->tipe) }}',
                 photoPreview: null,
                 hasExistingPhoto: {{ $item->foto ? 'true' : 'false' }},
                 hapusFoto: false,
                 previewFile(event) {
                     const file = event.target.files[0];
                     if (file) {
                         const reader = new FileReader();
                         reader.onload = (e) => { this.photoPreview = e.target.result; };
                         reader.readAsDataURL(file);
                         this.hapusFoto = false;
                     }
                 }
             }">

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <div class="font-semibold text-sm mb-1">Terdapat kesalahan pengisian formulir:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Pilihan Tipe Laporan -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink mb-2">
                        Jenis Laporan <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer border-2 rounded-xl p-3.5 flex items-center space-x-3 transition-all"
                               :class="tipe === 'hilang' ? 'border-rose-500 bg-rose-50/50 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300'">
                            <input type="radio" name="tipe" value="hilang" x-model="tipe" class="text-rose-600 focus:ring-rose-500">
                            <div>
                                <div class="font-bold text-sm text-ink flex items-center">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mr-2"></span>
                                    Barang Hilang
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">Laporan kehilangan barang</div>
                            </div>
                        </label>

                        <label class="cursor-pointer border-2 rounded-xl p-3.5 flex items-center space-x-3 transition-all"
                               :class="tipe === 'temuan' ? 'border-uin-500 bg-uin-50 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300'">
                            <input type="radio" name="tipe" value="temuan" x-model="tipe" class="text-uin-500 focus:ring-uin-500">
                            <div>
                                <div class="font-bold text-sm text-ink flex items-center">
                                    <span class="w-2.5 h-2.5 rounded-full bg-uin-500 mr-2"></span>
                                    Barang Temuan
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">Laporan barang ditemukan</div>
                            </div>
                        </label>
                    </div>
                    @error('tipe')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Laporan -->
                <div>
                    <label for="judul" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Judul Laporan <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="judul"
                        id="judul"
                        value="{{ old('judul', $item->judul) }}"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('judul') border-rose-400 ring-rose-200 @enderror"
                    >
                    @error('judul')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori & Lokasi Kejadian -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                            Kategori Barang <span class="text-rose-500">*</span>
                        </label>
                        <select
                            name="category_id"
                            id="category_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('category_id') border-rose-400 ring-rose-200 @enderror"
                        >
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi Kejadian -->
                    <div>
                        <label for="location_id" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                            Lokasi Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <select
                            name="location_id"
                            id="location_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('location_id') border-rose-400 ring-rose-200 @enderror"
                        >
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id', $item->location_id) == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Kejadian -->
                <div>
                    <label for="tanggal" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Tanggal Kejadian <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal"
                        id="tanggal"
                        value="{{ old('tanggal', $item->tanggal->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        required
                        class="w-full sm:w-1/2 px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('tanggal') border-rose-400 ring-rose-200 @enderror"
                    >
                    @error('tanggal')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Titik Pengambilan (Untuk Tipe Temuan) -->
                <div x-show="tipe === 'temuan'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 rounded-xl bg-gold-100/40 border border-gold-500/40">
                    <label for="pickup_point_id" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5 flex items-center">
                        <svg class="w-4 h-4 text-gold-500 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Titik Pengambilan Barang <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="pickup_point_id"
                        id="pickup_point_id"
                        :required="tipe === 'temuan'"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('pickup_point_id') border-rose-400 ring-rose-200 @enderror"
                    >
                        <option value="">-- Pilih Titik Pengambilan Resmi --</option>
                        @foreach ($pickupPoints as $point)
                            <option value="{{ $point->id }}" {{ old('pickup_point_id', $item->pickup_point_id) == $point->id ? 'selected' : '' }}>
                                {{ $point->nama }} {{ $point->keterangan ? '('.$point->keterangan.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('pickup_point_id')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Barang -->
                <div>
                    <label for="deskripsi" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Deskripsi Lengkap &amp; Ciri-Ciri Khusus <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="4"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('deskripsi') border-rose-400 ring-rose-200 @enderror"
                    >{{ old('deskripsi', $item->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengelolaan Foto Barang -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Foto Barang
                    </label>

                    @if ($item->foto)
                        <div x-show="! hapusFoto && ! photoPreview" class="mb-3 p-3 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-14 h-14 rounded-lg object-cover border border-gray-300">
                                <div>
                                    <div class="text-xs font-semibold text-ink">Foto Saat Ini</div>
                                    <div class="text-[11px] text-gray-500">Tersimpan di sistem</div>
                                </div>
                            </div>
                            <label class="inline-flex items-center cursor-pointer text-xs text-rose-600 hover:text-rose-800">
                                <input type="checkbox" name="hapus_foto" value="1" x-model="hapusFoto" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500 mr-1.5">
                                Hapus foto ini
                            </label>
                        </div>
                    @endif

                    <!-- Upload Foto Baru -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label class="cursor-pointer inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm text-xs font-semibold text-ink bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-2 text-uin-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ganti / Unggah Foto
                            <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg" @change="previewFile" class="hidden">
                        </label>
                        <span class="text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2 MB.</span>
                    </div>

                    <!-- Pratinjau Foto Baru -->
                    <div x-show="photoPreview" class="mt-3">
                        <p class="text-xs font-medium text-gray-600 mb-1">Pratinjau Foto Baru:</p>
                        <div class="relative w-36 h-36 rounded-xl overflow-hidden border border-gray-200 shadow-inner bg-gray-50">
                            <img :src="photoPreview" alt="Pratinjau" class="w-full h-full object-cover">
                        </div>
                    </div>

                    @error('foto')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('my.items') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin-500 transition-colors"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
