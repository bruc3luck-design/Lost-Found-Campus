<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    /**
     * Siapapun yang terautentikasi dapat melihat daftar umum atau laporan sendiri.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Melihat detail laporan.
     * Item tersembunyi atau belum disetujui admin hanya dapat dilihat oleh pemilik atau admin.
     */
    public function view(?User $user, Item $item): bool
    {
        if ($item->disembunyikan || $item->status_moderasi !== 'disetujui') {
            if (! $user) {
                return false;
            }

            return $user->id === $item->user_id || $user->isAdmin();
        }

        return true;
    }

    /**
     * Semua civitas yang login dapat membuat laporan.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Hanya pemilik atau admin yang dapat mengedit laporan.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->id === $item->user_id || $user->isAdmin();
    }

    /**
     * Hanya pemilik atau admin yang dapat menghapus laporan.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->id === $item->user_id || $user->isAdmin();
    }
}
