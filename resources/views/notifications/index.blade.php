<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-ink">Notifikasi</h1>
                    @if ($unreadCount > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                            {{ $unreadCount }} Baru
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Pemberitahuan terkini seputar klaim dan laporan barang Anda</p>
            </div>

            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-xs font-semibold text-uin-700 bg-uin-50 hover:bg-uin-100 border border-uin-500/20 shadow-xs transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-4">
        @if ($notifications->isEmpty())
            <!-- Empty State Notifikasi -->
            <div class="bg-white rounded-2xl border border-gray-200/80 p-8 sm:p-12 text-center shadow-sm max-w-md mx-auto my-6">
                <div class="w-14 h-14 rounded-full bg-uin-50 text-uin-500 flex items-center justify-center mx-auto mb-3 border border-uin-500/20">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-ink mb-1">Belum Ada Notifikasi</h3>
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                    Setiap klaim baru yang masuk pada laporan Anda atau pembaharuan status klaim Anda akan muncul di sini.
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold text-white bg-uin-500 hover:bg-uin-700 transition-colors shadow-xs">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <!-- List Kartu Notifikasi -->
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs divide-y divide-gray-100 overflow-hidden">
                @foreach ($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                        $type = $data['type'] ?? 'general';
                    @endphp

                    <div class="p-4 sm:p-5 transition-colors flex items-start gap-3 sm:gap-4 {{ $isUnread ? 'bg-uin-50/40 hover:bg-uin-50/60' : 'bg-white hover:bg-gray-50/70' }}">
                        <!-- Ikon Indikator Tipe Notifikasi -->
                        <div class="shrink-0 mt-0.5">
                            @if ($type === 'claim_received')
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-gold-500 border border-gold-500/30 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                            @elseif ($type === 'claim_approved')
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-500/30 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @elseif ($type === 'claim_rejected')
                                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-500/30 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-uin-50 text-uin-500 border border-uin-500/30 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Konten Notifikasi -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs sm:text-sm font-bold text-ink">
                                        {{ $data['title'] ?? 'Pemberitahuan' }}
                                    </h4>
                                    @if ($isUnread)
                                        <span class="w-2 h-2 rounded-full bg-rose-500" title="Belum dibaca"></span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-gray-400 shrink-0 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed mb-2.5">
                                {{ $data['message'] ?? '' }}
                            </p>

                            <!-- Tombol Aksi Langsung -->
                            <div class="flex items-center gap-2">
                                @if (!empty($data['url']))
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold {{ $isUnread ? 'bg-uin-500 text-white hover:bg-uin-700 shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                                        <span>Buka Detail</span>
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif

                                @if ($isUnread && empty($data['url']))
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                       class="text-[11px] font-semibold text-gray-500 hover:text-ink">
                                        Tandai Dibaca
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginasi -->
            <div class="pt-2">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
