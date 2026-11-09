<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Tampilkan seluruh daftar riwayat notifikasi pengguna.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Ambil notifikasi berpaginasi
        $notifications = $user->notifications()->paginate(15);
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca dan arahkan ke tujuan (jika ada).
     */
    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $targetUrl = $notification->data['url'] ?? null;

        if ($targetUrl) {
            return redirect()->to($targetUrl);
        }

        return redirect()->back();
    }

    /**
     * Tandai semua notifikasi yang belum dibaca sebagai dibaca.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
