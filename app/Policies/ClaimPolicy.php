<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;

class ClaimPolicy
{
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
     */
    public function create(User $user, Item $item): bool
    {
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

        return $canManage && $claim->status === 'menunggu';
    }
}
