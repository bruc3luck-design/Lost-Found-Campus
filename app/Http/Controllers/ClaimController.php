<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use App\Models\Item;
use App\Notifications\ClaimApprovedNotification;
use App\Notifications\ClaimRejectedNotification;
use App\Notifications\NewClaimReceivedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClaimController extends Controller
{
    /**
     * Tampilkan formulir pengajuan klaim untuk barang tertentu.
     */
    public function create(Item $item): View|RedirectResponse
    {
        // 1. Barang harus masih berstatus terbuka (hilang atau ditemukan)
        if (! $item->isOpen()) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', "Barang ini telah berstatus {$item->statusLabel()} sehingga tidak lagi menerima pengajuan klaim.");
        }

        // 2. Cegah klaim atas barang sendiri
        if (auth()->id() === $item->user_id) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', 'Anda tidak dapat mengajukan klaim atas barang yang Anda laporkan sendiri.');
        }

        // 3. Cegah klaim ganda dari penklaim yang sama
        $existingClaim = $item->claims()->where('claimer_id', auth()->id())->first();
        if ($existingClaim) {
            return redirect()
                ->route('my.history', ['tab' => 'klaim'])
                ->with('error', 'Anda sudah pernah mengajukan klaim untuk barang ini. Silakan pantau statusnya pada menu Riwayat.');
        }

        return view('claims.create', compact('item'));
    }

    /**
     * Simpan pengajuan klaim baru.
     */
    public function store(StoreClaimRequest $request, Item $item): RedirectResponse
    {
        // Validasi aturan bisnis tambahan
        if (! $item->isOpen()) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', 'Barang ini sudah ditutup dan tidak menerima klaim baru.');
        }

        if (auth()->id() === $item->user_id) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', 'Tidak dapat mengklaim barang milik sendiri.');
        }

        if ($item->claims()->where('claimer_id', auth()->id())->exists()) {
            return redirect()
                ->route('my.history', ['tab' => 'klaim'])
                ->with('error', 'Anda telah mengajukan klaim untuk barang ini sebelumnya.');
        }

        $validated = $request->validated();

        // Upload bukti foto jika disertakan
        $fotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $fotoPath = $request->file('bukti_foto')->store('claims', 'public');
        }

        $claim = Claim::create([
            'item_id'    => $item->id,
            'claimer_id' => auth()->id(),
            'bukti'      => $validated['bukti'],
            'bukti_foto' => $fotoPath,
            'status'     => 'menunggu',
            'catatan'    => null,
        ]);

        // Kirim notifikasi klaim masuk ke pelapor barang
        if ($item->user) {
            $item->user->notify(new NewClaimReceivedNotification($claim));
        }

        return redirect()
            ->route('my.history', ['tab' => 'klaim'])
            ->with('success', 'Pengajuan klaim berhasil dikirim! Pemilik laporan akan memverifikasi bukti yang Anda sampaikan.');
    }

    /**
     * Halaman klaim masuk untuk pemilik laporan atau admin.
     */
    public function incoming(Item $item): View
    {
        Gate::authorize('update', $item);

        $item->load(['claims.claimer', 'pickupPoint']);

        return view('claims.incoming', compact('item'));
    }

    /**
     * Setujui klaim (dalam DB::transaction):
     * 1. Status klaim terpilih diubah menjadi "disetujui".
     * 2. Klaim lain yang masih "menunggu" pada barang tersebut otomatis ditolak.
     * 3. Status barang diubah menjadi "diklaim".
     */
    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        Gate::authorize('approve', $claim);

        $item = $claim->item;

        DB::transaction(function () use ($claim, $item, $request) {
            // 1. Setujui klaim ini
            $claim->update([
                'status'  => 'disetujui',
                'catatan' => $request->input('catatan', 'Klaim disetujui oleh pemilik laporan.'),
            ]);

            // 2. Tolak klaim lain yang berstatus menunggu
            $otherClaims = $item->claims()
                ->where('id', '!=', $claim->id)
                ->where('status', 'menunggu')
                ->get();

            foreach ($otherClaims as $otherClaim) {
                $otherClaim->update([
                    'status'  => 'ditolak',
                    'catatan' => 'Laporan telah diklaim dan disetujui untuk pihak lain.',
                ]);

                if ($otherClaim->claimer) {
                    $otherClaim->claimer->notify(new ClaimRejectedNotification($otherClaim, 'Laporan telah diklaim dan disetujui untuk pihak lain.'));
                }
            }

            // 3. Ubah status barang menjadi "diklaim"
            $item->update([
                'status' => 'diklaim',
            ]);
        });

        // Notifikasi ke penklaim yang disetujui
        if ($claim->claimer) {
            $claim->claimer->notify(new ClaimApprovedNotification($claim));
        }

        return redirect()
            ->back()
            ->with('success', "Klaim berhasil disetujui! Status barang kini menjadi 'Diklaim' dan kontak WhatsApp penklaim dapat dihubungi.");
    }

    /**
     * Tolak klaim pengajuan.
     */
    public function reject(Request $request, Claim $claim): RedirectResponse
    {
        Gate::authorize('reject', $claim);

        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $catatan = $request->input('catatan', 'Bukti yang dilampirkan belum sesuai.');

        $claim->update([
            'status'  => 'ditolak',
            'catatan' => $catatan,
        ]);

        // Notifikasi ke penklaim bahwa klaim ditolak
        if ($claim->claimer) {
            $claim->claimer->notify(new ClaimRejectedNotification($claim, $catatan));
        }

        return redirect()
            ->back()
            ->with('success', 'Klaim telah ditolak.');
    }
}
