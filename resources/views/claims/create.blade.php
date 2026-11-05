<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-ink">Ajukan Klaim Barang</h1>
                <p class="text-xs text-gray-500 mt-0.5">Sampaikan bukti valid kepemilikan atau detail penemuan barang</p>
            </div>
            <a href="{{ route('items.show', $item) }}" class="text-xs font-medium text-uin-500 hover:text-uin-700 underline">
                &larr; Kembali ke Detail Barang
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Ringkasan Barang yang Diklaim -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm flex items-start space-x-4">
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
                <div class="flex items-center space-x-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $item->tipe === 'hilang' ? 'bg-rose-600 text-white' : 'bg-uin-500 text-white' }}">
                        {{ $item->tipeLabel() }}
                    </span>
                    <span class="text-[11px] text-gray-500">{{ $item->category->nama }} &bull; {{ $item->location->nama }}</span>
                </div>
                <h2 class="font-bold text-sm text-ink truncate">{{ $item->judul }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">Dilaporkan oleh: <strong>{{ $item->user->name }}</strong> ({{ $item->user->prodi ?? 'UIN SSC' }})</p>
            </div>
        </div>

        <!-- Formulir Bukti Klaim -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 sm:p-8"
             x-data="{
                 photoPreview: null,
                 previewFile(event) {
                     const file = event.target.files[0];
                     if (file) {
                         const reader = new FileReader();
                         reader.onload = (e) => { this.photoPreview = e.target.result; };
                         reader.readAsDataURL(file);
                     }
                 }
             }">

            <div class="border-b border-gray-100 pb-4 mb-6">
                <h3 class="text-base font-bold text-ink">Formulir Verifikasi Bukti</h3>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Agar klaim Anda dapat disetujui, sebutkan detail spesifik yang hanya diketahui oleh pemilik barang (misalnya nomor seri, ciri khusus, isi dompet/tas, gantungan kunci rahasia, atau wallpaper layar kunci).
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                    <div class="font-semibold mb-1">Gagal Mengirimkan Klaim:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('claims.store', $item) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Bukti Teks -->
                <div>
                    <label for="bukti" class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Uraian Bukti Kepemilikan <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        name="bukti"
                        id="bukti"
                        rows="5"
                        required
                        placeholder="Contoh: Dompet tersebut berisi KTP dan KTM atas nama saya, uang pecahan 50rb dua lembar, dan di pojok kiri bawah terdapat stiker kecil bergambar logo jurusan..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm text-ink bg-white focus:outline-none focus:ring-2 focus:ring-uin-500 focus:border-transparent transition-all shadow-sm @error('bukti') border-rose-400 ring-rose-200 @enderror"
                    >{{ old('bukti') }}</textarea>
                    <p class="text-[11px] text-gray-500 mt-1">Minimal 10 karakter, maksimal 2.500 karakter.</p>
                    @error('bukti')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Bukti Pendukung (Opsional) -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-ink mb-1.5">
                        Foto Bukti Pendukung (Opsional)
                    </label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <label class="cursor-pointer inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm text-xs font-semibold text-ink bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-2 text-uin-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Unggah Foto Bukti
                            <input type="file" name="bukti_foto" accept="image/jpeg,image/png,image/jpg" @change="previewFile" class="hidden">
                        </label>
                        <span class="text-xs text-gray-500">Kuitansi pembelian, dus barang, atau foto lama. Maks 2 MB.</span>
                    </div>

                    <div x-show="photoPreview" class="mt-3">
                        <p class="text-xs font-medium text-gray-600 mb-1">Pratinjau Foto Bukti:</p>
                        <div class="relative w-32 h-32 rounded-xl overflow-hidden border border-gray-200 shadow-inner bg-gray-50">
                            <img :src="photoPreview" alt="Pratinjau" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Catatan Keamanan Privasi Nomor WhatsApp -->
                <div class="p-3.5 rounded-xl bg-gold-100/50 border border-gold-500/30 text-xs text-ink flex items-start space-x-2.5">
                    <svg class="w-5 h-5 text-gold-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="leading-relaxed">
                        <strong>Privasi Terjaga:</strong> Nomor WhatsApp Anda (<strong>{{ auth()->user()->no_wa ?? 'Tercatat di Portal' }}</strong>) hanya akan diperlihatkan kepada pelapor setelah klaim ini disetujui untuk koordinasi pengambilan barang.
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('items.show', $item) }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-uin-500 hover:bg-uin-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin-500 transition-colors"
                    >
                        Kirimkan Klaim
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
