<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;

class ClaimPolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar klaim secara umum (misal di Filament).
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Klaim hanya dapat dilihat oleh penklaim, pemilik laporan, dan admin.
     */
    public function view(User $user, Claim $claim): bool
    {
        return $user->id === $claim->claimer_id
            || $user->id === $claim->item->user_id
            || $user->isAdmin();
    }

    /**
     * Syarat mengajukan klaim:
     * 1. Pengguna bukan pemilik laporan (cegah klaim atas barang sendiri).
     * 2. Status barang masih terbuka (hilang atau ditemukan).
     * 3. Pengguna belum pernah mengajukan klaim sebelumnya untuk barang ini (cegah klaim ganda).
     * Catatan: $item dibuat nullable agar kompatibel dengan Gate Filament yang memanggil create(User).
     */
    public function create(User $user, ?Item $item = null): bool
    {
        if ($item === null) {
            return true;
        }

        if ($user->id === $item->user_id) {
            return false;
        }

        if (! $item->isOpen()) {
            return false;
        }

        $alreadyClaimed = $item->claims()->where('claimer_id', $user->id)->exists();

        return ! $alreadyClaimed;
    }

    /**
     * Hanya admin atau pemilik yang dapat memperbarui klaim.
     */
    public function update(User $user, Claim $claim): bool
    {
        return $user->isAdmin() || $user->id === $claim->item->user_id;
    }

    /**
     * Hanya admin yang dapat menghapus data klaim.
     */
    public function delete(User $user, Claim $claim): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya pemilik laporan atau admin yang dapat menyetujui klaim.
     */
    public function approve(User $user, Claim $claim): bool
    {
        $canManage = ($user->id === $claim->item->user_id) || $user->isAdmin();

        return $canManage
            && $claim->status === 'menunggu'
            && $claim->item->isOpen();
    }

    /**
     * Hanya pemilik laporan atau admin yang dapat menolak klaim.
     */
    public function reject(User $user, Claim $claim): bool
    {
        $canManage = ($user->id === $claim->item->user_id) || $user->isAdmin();

        return $canManage
            && $claim->status === 'menunggu'
            && $claim->item->isOpen();
    }
}
