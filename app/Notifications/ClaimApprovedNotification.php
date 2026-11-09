<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClaimApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Claim $claim)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $item = $this->claim->item;

        return [
            'type'       => 'claim_approved',
            'title'      => 'Klaim Anda Disetujui! 🎉',
            'message'    => "Klaim Anda untuk barang \"{$item->judul}\" telah disetujui. Kontak WhatsApp pelapor kini dapat Anda hubungi untuk koordinasi serah terima.",
            'claim_id'   => $this->claim->id,
            'item_id'    => $item->id,
            'item_title' => $item->judul,
            'url'        => route('my.history', ['tab' => 'klaim']),
            'icon'       => 'check_circle',
        ];
    }
}
